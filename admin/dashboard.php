<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Admin Dashboard';

// Get statistics
$users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$places = $pdo->query("SELECT COUNT(*) FROM places")->fetchColumn();
$gallery = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$reviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
$favorites = $pdo->query("SELECT COUNT(*) FROM favorites")->fetchColumn();
$subscribers = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers")->fetchColumn();

// Get recent activity
$recent_reviews = $pdo->query("
    SELECT r.*, u.username, p.name as place_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN places p ON r.place_id = p.id
    ORDER BY r.created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="admin-nav" style="background: white; padding: 1rem 0; margin-bottom: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
        <a href="dashboard.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">📊 Dashboard</a>
        <a href="users.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">👥 Users</a>
        <a href="places.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📍 Places</a>
        <a href="categories.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📂 Categories</a>
        <a href="gallery.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📸 Gallery</a>
        <a href="reviews.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">⭐ Reviews</a>
        <a href="messages.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">💬 Messages</a>
        <a href="newsletter.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📧 Newsletter</a>
    </div>
</div>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">📊 Dashboard Statistics</h2>

<div class="grid grid-4" style="margin-bottom: 3rem;">
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--primary-light), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">👥</div>
        <h3 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"><?php echo $users; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Total Users</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, hsl(142, 71%, 92%), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">📍</div>
        <h3 style="font-size: 2.5rem; color: var(--success); margin-bottom: 0.5rem;"><?php echo $places; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Total Places</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, hsl(340, 82%, 92%), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">⭐</div>
        <h3 style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 0.5rem;"><?php echo $reviews; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Total Reviews</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, hsl(35, 100%, 92%), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">❤️</div>
        <h3 style="font-size: 2.5rem; color: var(--accent); margin-bottom: 0.5rem;"><?php echo $favorites; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Total Favorites</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--gray-100), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">📸</div>
        <h3 style="font-size: 2.5rem; color: var(--gray-700); margin-bottom: 0.5rem;"><?php echo $gallery; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Gallery Images</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--gray-100), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">💬</div>
        <h3 style="font-size: 2.5rem; color: var(--gray-700); margin-bottom: 0.5rem;"><?php echo $messages; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Messages</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--primary-light), var(--white));">
        <div style="font-size: 3rem; margin-bottom: 0.5rem;">📧</div>
        <h3 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"><?php echo $subscribers; ?></h3>
        <p style="color: var(--gray-700); font-weight: 600;">Subscribers</p>
    </div>
</div>

<?php if(!empty($recent_reviews)): ?>
<div class="card" style="padding: 2rem;">
    <h2 style="font-family: var(--font-heading); margin-bottom: 1.5rem;">Recent Reviews</h2>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <?php foreach($recent_reviews as $review): ?>
        <div style="border-bottom: 1px solid var(--gray-200); padding-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                <div>
                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                    <span style="color: var(--gray-600);"> reviewed </span>
                    <strong><?php echo htmlspecialchars($review['place_name']); ?></strong>
                </div>
                <span class="rating"><?php echo str_repeat('⭐', $review['rating']); ?></span>
            </div>
            <p style="color: var(--gray-700); font-size: 0.95rem;">
                <?php echo htmlspecialchars(substr($review['comment'], 0, 150)) . '...'; ?>
            </p>
            <p style="color: var(--gray-500); font-size: 0.85rem; margin-top: 0.5rem;">
                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

</div></body></html>
