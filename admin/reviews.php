<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Manage Reviews';

// Handle delete
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = "Review deleted successfully!";
}

// Get all reviews
$reviews = $pdo->query("
    SELECT r.*, u.username, p.name as place_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN places p ON r.place_id = p.id
    ORDER BY r.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="admin-nav" style="background: white; padding: 1rem 0; margin-bottom: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
        <a href="dashboard.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📊 Dashboard</a>
        <a href="users.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">👥 Users</a>
        <a href="places.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📍 Places</a>
        <a href="categories.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📂 Categories</a>
        <a href="gallery.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📸 Gallery</a>
        <a href="reviews.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">⭐ Reviews</a>
        <a href="messages.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">💬 Messages</a>
        <a href="newsletter.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📧 Newsletter</a>
    </div>
</div>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">⭐ Manage Reviews (<?php echo count($reviews); ?>)</h2>

<?php if(empty($reviews)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--gray-600);">No reviews yet</p>
    </div>
<?php else: ?>
    <div class="card" style="padding: 2rem;">
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach($reviews as $review): ?>
            <div style="border-bottom: 1px solid var(--gray-200); padding-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                    <div style="flex: 1;">
                        <div style="margin-bottom: 0.5rem;">
                            <strong style="color: var(--gray-900); font-size: 1.05rem;">
                                <?php echo htmlspecialchars($review['username']); ?>
                            </strong>
                            <span style="color: var(--gray-600);"> reviewed </span>
                            <strong style="color: var(--primary);">
                                <?php echo htmlspecialchars($review['place_name']); ?>
                            </strong>
                        </div>
                        <div class="rating" style="margin-bottom: 0.75rem;">
                            <?php echo str_repeat('⭐', $review['rating']); ?>
                        </div>
                        <p style="color: var(--gray-700); line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                        </p>
                        <p style="color: var(--gray-500); font-size: 0.85rem; margin-top: 0.75rem;">
                            Posted on <?php echo date('F d, Y', strtotime($review['created_at'])); ?>
                        </p>
                    </div>
                    <a href="?delete=<?php echo $review['id']; ?>" 
                       onclick="return confirm('Delete this review?')"
                       class="btn btn-sm btn-danger">
                        Delete
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

</div></body></html>
