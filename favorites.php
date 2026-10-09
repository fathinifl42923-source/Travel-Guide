<?php 
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'My Favorites - TravelGuide';

// Get user's favorite places
$stmt = $pdo->prepare("
    SELECT p.*, f.created_at as favorited_at,
           COALESCE(AVG(r.rating), 0) as avg_rating,
           COUNT(r.id) as review_count
    FROM favorites f
    JOIN places p ON f.place_id = p.id
    LEFT JOIN reviews r ON p.id = r.place_id
    WHERE f.user_id = ?
    GROUP BY p.id
    ORDER BY f.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">❤️ My Favorite Destinations</h2>
        <p class="section-subtitle">Your saved destinations for future adventures</p>
        
        <?php if(empty($favorites)): ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.5;">🤍</div>
                <h3 style="color: var(--gray-700); margin-bottom: 0.5rem;">No favorites yet</h3>
                <p style="color: var(--gray-600); margin-bottom: 1.5rem;">
                    Start exploring and save your favorite destinations
                </p>
                <a href="places.php" class="btn">Explore Destinations</a>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach($favorites as $place): ?>
                <div class="card">
                    <img src="uploads/places/<?php echo htmlspecialchars($place['image']); ?>" 
                         alt="<?php echo htmlspecialchars($place['name']); ?>" 
                         class="card-img">
                    
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h3 class="card-title" style="margin: 0; flex: 1;">
                                <?php echo htmlspecialchars($place['name']); ?>
                            </h3>
                            <button class="favorite-btn active" 
                                    data-place-id="<?php echo $place['id']; ?>"
                                    title="Remove from favorites">
                                ❤️
                            </button>
                        </div>
                        
                        <p class="card-text" style="margin-bottom: 0.5rem;">
                            <strong>📍 Location:</strong> <?php echo htmlspecialchars($place['location']); ?>
                        </p>
                        
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
                        
                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                            <a href="place-detail.php?id=<?php echo $place['id']; ?>" 
                               class="btn btn-sm" 
                               style="flex: 1;">
                                View Details →
                            </a>
                        </div>
                        
                        <p style="margin-top: 0.75rem; font-size: 0.85rem; color: var(--gray-500); text-align: center;">
                            Added <?php echo date('M d, Y', strtotime($place['favorited_at'])); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
