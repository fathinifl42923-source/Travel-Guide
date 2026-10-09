<?php 
require_once 'db.php';

$place_id = $_GET['id'] ?? 0;

// Get place details with rating
$stmt = $pdo->prepare("
    SELECT p.*, 
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as review_count
    FROM places p
    LEFT JOIN reviews r ON p.id = r.place_id
    WHERE p.id = ?
    GROUP BY p.id
");
$stmt->execute([$place_id]);
$place = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$place) {
    header("Location: places.php");
    exit();
}

$page_title = htmlspecialchars($place['name']) . ' - TravelGuide';

// Get reviews for this place
$stmt = $pdo->prepare("
    SELECT r.*, u.username, u.profile_image
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.place_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$place_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if user has already reviewed
$user_reviewed = false;
if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE place_id = ? AND user_id = ?");
    $stmt->execute([$place_id, $_SESSION['user_id']]);
    $user_reviewed = $stmt->fetchColumn() > 0;
    
    // Check if favorited
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE place_id = ? AND user_id = ?");
    $stmt->execute([$place_id, $_SESSION['user_id']]);
    $is_favorite = $stmt->fetchColumn() > 0;
}

// Get related places
$stmt = $pdo->prepare("
    SELECT p.*, 
           COALESCE(AVG(r.rating), 0) as avg_rating
    FROM places p
    LEFT JOIN reviews r ON p.id = r.place_id
    WHERE p.id != ?
    GROUP BY p.id
    ORDER BY RAND()
    LIMIT 3
");
$stmt->execute([$place_id]);
$related_places = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<section class="section">
    <div class="container">
        <!-- Back Button -->
        <a href="places.php" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary); text-decoration: none; margin-bottom: 1.5rem; font-weight: 600;">
            ← Back to All Destinations
        </a>
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
            <!-- Main Content -->
            <div>
                <!-- Place Header -->
                <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--gray-900);">
                                <?php echo htmlspecialchars($place['name']); ?>
                            </h1>
                            <p style="font-size: 1.2rem; color: var(--gray-600);">
                                📍 <?php echo htmlspecialchars($place['location']); ?>
                            </p>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <button class="favorite-btn <?php echo $is_favorite ? 'active' : ''; ?>" 
                                data-place-id="<?php echo $place['id']; ?>"
                                style="font-size: 2rem;">
                            <?php echo $is_favorite ? '❤️' : '🤍'; ?>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($place['avg_rating'] > 0): ?>
                    <div class="rating" style="font-size: 1.1rem; margin-bottom: 1.5rem;">
                        <span class="stars">
                            <?php 
                            $rating = round($place['avg_rating']);
                            echo str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating); 
                            ?>
                        </span>
                        <span style="color: var(--gray-700);">
                            <strong><?php echo number_format($place['avg_rating'], 1); ?></strong>
                            (<?php echo $place['review_count']; ?> reviews)
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Main Image -->
                    <img src="uploads/places/<?php echo htmlspecialchars($place['image']); ?>" 
                         alt="<?php echo htmlspecialchars($place['name']); ?>" 
                         class="lightbox-trigger"
                         style="width: 100%; height: 400px; object-fit: cover; border-radius: var(--radius-lg); margin-bottom: 1.5rem; cursor: pointer;">
                    
                    <h2 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 1rem;">About This Destination</h2>
                    <p style="line-height: 1.8; color: var(--gray-700); font-size: 1.05rem;">
                        <?php echo nl2br(htmlspecialchars($place['description'])); ?>
                    </p>
                </div>
                
                <!-- Reviews Section -->
                <div class="card" style="padding: 2rem;">
                    <h2 style="font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 1.5rem;">
                        Reviews (<?php echo count($reviews); ?>)
                    </h2>
                    
                    <!-- Add Review Form -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(!$user_reviewed): ?>
                        <form method="POST" action="api/add-review.php" class="review-form" style="background: var(--gray-50); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
                            <h3 style="margin-bottom: 1rem;">Write a Review</h3>
                            <input type="hidden" name="place_id" value="<?php echo $place_id; ?>">
                            
                            <div class="form-group">
                                <label>Your Rating</label>
                                <div class="star-rating" style="font-size: 2rem; cursor: pointer;">
                                    <span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
                                </div>
                                <input type="hidden" name="rating" required value="0">
                            </div>
                            
                            <div class="form-group">
                                <label>Your Review</label>
                                <textarea name="comment" required placeholder="Share your experience..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn">Submit Review</button>
                        </form>
                        <?php else: ?>
                        <div class="alert alert-success" style="margin-bottom: 2rem;">
                            ✓ You've already reviewed this destination. Thank you!
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert" style="background: var(--primary-light); color: var(--primary-dark); margin-bottom: 2rem;">
                            <a href="login.php" style="color: var(--primary-dark); font-weight: 600;">Login</a> to write a review
                        </div>
                    <?php endif; ?>
                    
                    <!-- Reviews List -->
                    <?php if(empty($reviews)): ?>
                        <p style="text-align: center; color: var(--gray-600); padding: 2rem;">
                            No reviews yet. Be the first to share your experience!
                        </p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <?php foreach($reviews as $review): ?>
                            <div style="border-bottom: 1px solid var(--gray-200); padding-bottom: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                        👤
                                    </div>
                                    <div style="flex: 1;">
                                        <strong style="color: var(--gray-900);"><?php echo htmlspecialchars($review['username']); ?></strong>
                                        <div class="rating" style="font-size: 0.9rem;">
                                            <?php echo str_repeat('⭐', $review['rating']); ?>
                                        </div>
                                    </div>
                                    <span style="color: var(--gray-500); font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                    </span>
                                </div>
                                <p style="color: var(--gray-700); line-height: 1.6; margin-left: 3.5rem;">
                                    <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Share Card -->
                <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 1rem;">Share This Destination</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <button onclick="shareOnSocial('facebook', '<?php echo $_SERVER['REQUEST_URI']; ?>', '<?php echo htmlspecialchars($place['name']); ?>')" 
                                class="btn" style="background: #1877f2;">
                            📘 Share on Facebook
                        </button>
                        <button onclick="shareOnSocial('twitter', '<?php echo $_SERVER['REQUEST_URI']; ?>', '<?php echo htmlspecialchars($place['name']); ?>')" 
                                class="btn" style="background: #1da1f2;">
                            🐦 Share on Twitter
                        </button>
                        <button onclick="shareOnSocial('whatsapp', '<?php echo $_SERVER['REQUEST_URI']; ?>', '<?php echo htmlspecialchars($place['name']); ?>')" 
                                class="btn" style="background: #25d366;">
                            💬 Share on WhatsApp
                        </button>
                    </div>
                </div>
                
                <!-- Related Destinations -->
                <?php if(!empty($related_places)): ?>
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 1rem;">You May Also Like</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach($related_places as $related): ?>
                        <a href="place-detail.php?id=<?php echo $related['id']; ?>" 
                           style="text-decoration: none; display: block; border-bottom: 1px solid var(--gray-200); padding-bottom: 1rem;">
                            <strong style="color: var(--gray-900); display: block; margin-bottom: 0.25rem;">
                                <?php echo htmlspecialchars($related['name']); ?>
                            </strong>
                            <span style="color: var(--gray-600); font-size: 0.9rem;">
                                📍 <?php echo htmlspecialchars($related['location']); ?>
                            </span>
                            <?php if($related['avg_rating'] > 0): ?>
                            <div class="rating" style="font-size: 0.85rem; margin-top: 0.25rem;">
                                <?php echo str_repeat('⭐', round($related['avg_rating'])); ?>
                                <span style="color: var(--gray-600);">
                                    <?php echo number_format($related['avg_rating'], 1); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
    @media (max-width: 968px) {
        .container > div > div {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php include 'footer.php'; ?>
