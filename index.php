<?php 
require_once 'db.php';
$page_title = 'Home - Discover Your Next Adventure | TravelGuide';

// Get featured places with ratings
$stmt = $pdo->query("
    SELECT p.*, 
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as review_count
    FROM places p
    LEFT JOIN reviews r ON p.id = r.place_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT 6
");
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$categories = $pdo->query("SELECT * FROM categories LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);

// Get recent reviews
$recent_reviews = $pdo->query("
    SELECT r.*, u.username, p.name as place_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN places p ON r.place_id = p.id
    ORDER BY r.created_at DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

// Check if user is logged in and get their favorites
$user_favorites = [];
if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT place_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

include 'header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Discover Your Next Adventure</h1>
        <p>Explore the world's most breathtaking destinations with expert tips and insider knowledge</p>
        <div class="hero-search">
            <form class="search-form search-box">
                <input type="text" name="search" placeholder="Search destinations, cities, countries..." required>
                <button type="submit">🔍 Search</button>
            </form>
        </div>
    </div>
</section>

<?php if(!empty($categories)): ?>
<section class="section" style="background: white;">
    <div class="container">
        <h2 class="section-title">Explore by Category</h2>
        <p class="section-subtitle">Find destinations that match your travel style</p>
        <div class="grid grid-4">
            <?php foreach($categories as $category): ?>
            <a href="places.php?category=<?php echo $category['id']; ?>" 
               class="card" 
               style="text-decoration: none; text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">
                    <?php echo htmlspecialchars($category['icon']); ?>
                </div>
                <h3 class="card-title" style="margin: 0; font-size: 1.2rem;">
                    <?php echo htmlspecialchars($category['name']); ?>
                </h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Featured Destinations</h2>
        <p class="section-subtitle">Handpicked locations for your next unforgettable journey</p>
        
        <?php if(empty($places)): ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <p style="font-size: 1.2rem; color: var(--gray-600);">
                    No destinations available yet. Check back soon!
                </p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach($places as $place): 
                    $isFavorite = in_array($place['id'], $user_favorites);
                ?>
                <div class="card">
                    <img src="uploads/places/<?php echo htmlspecialchars($place['image']); ?>" 
                         alt="<?php echo htmlspecialchars($place['name']); ?>" 
                         class="card-img">
                    
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h3 class="card-title" style="margin: 0; flex: 1;">
                                <?php echo htmlspecialchars($place['name']); ?>
                            </h3>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button class="favorite-btn <?php echo $isFavorite ? 'active' : ''; ?>" 
                                        data-place-id="<?php echo $place['id']; ?>"
                                        title="Add to favorites">
                                    <?php echo $isFavorite ? '❤️' : '🤍'; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($place['avg_rating'] > 0): ?>
                        <div class="rating" style="margin-bottom: 0.75rem;">
                            <span class="stars">
                                <?php 
                                $rating = round($place['avg_rating']);
                                echo str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating); 
                                ?>
                            </span>
                            <span style="color: var(--gray-600); font-size: 0.9rem;">
                                <?php echo number_format($place['avg_rating'], 1); ?> 
                                (<?php echo $place['review_count']; ?> reviews)
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <p class="card-text">
                            <?php echo htmlspecialchars(substr($place['description'], 0, 120)) . '...'; ?>
                        </p>
                        
                        <div class="card-footer">
                            <span style="color: var(--primary); font-weight: 600;">
                                📍 <?php echo htmlspecialchars($place['location']); ?>
                            </span>
                            <a href="place-detail.php?id=<?php echo $place['id']; ?>" class="btn btn-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <a href="places.php" class="btn">View All Destinations</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if(!empty($recent_reviews)): ?>
<section class="section" style="background: white;">
    <div class="container">
        <h2 class="section-title">Recent Reviews</h2>
        <p class="section-subtitle">See what travelers are saying</p>
        <div class="grid grid-2" style="max-width: 900px; margin: 0 auto;">
            <?php foreach($recent_reviews as $review): ?>
            <div class="card">
                <div class="card-body">
                    <div class="rating" style="margin-bottom: 0.75rem;">
                        <?php echo str_repeat('⭐', $review['rating']); ?>
                    </div>
                    <p class="card-text" style="font-style: italic; margin-bottom: 1rem;">
                        "<?php echo htmlspecialchars($review['comment']); ?>"
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; color: var(--gray-600);">
                        <span>👤 <?php echo htmlspecialchars($review['username']); ?></span>
                        <a href="place-detail.php?id=<?php echo $review['place_id']; ?>" 
                           style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            <?php echo htmlspecialchars($review['place_name']); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>