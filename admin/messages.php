<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: messages.php");
    exit();
}

$page_title = 'View Messages';

// Messages are from contact form, no user_id
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="admin-nav" style="background: white; padding: 1rem 0; margin-bottom: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
        <a href="dashboard.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📊 Dashboard</a>
        <a href="users.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">👥 Users</a>
        <a href="places.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📍 Places</a>
        <a href="categories.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📂 Categories</a>
        <a href="gallery.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📸 Gallery</a>
        <a href="reviews.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">⭐ Reviews</a>
        <a href="messages.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">💬 Messages</a>
        <a href="newsletter.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📧 Newsletter</a>
    </div>
</div>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">💬 Contact Messages (<?php echo count($messages); ?>)</h2>

<?php if(empty($messages)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">💬</div>
        <p style="color: var(--gray-600);">No messages yet</p>
    </div>
<?php else: ?>
    <div style="display: grid; gap: 1.5rem;">
        <?php foreach($messages as $msg): ?>
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 0.5rem; color: var(--primary);">
                        📧 <?php echo htmlspecialchars($msg['subject']); ?>
                    </h3>
                    <div style="display: flex; gap: 1.5rem; color: var(--gray-600); font-size: 0.9rem;">
                        <span><strong>From:</strong> <?php echo htmlspecialchars($msg['name']); ?></span>
                        <span><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color: var(--primary);"><?php echo htmlspecialchars($msg['email']); ?></a></span>
                        <span><strong>Date:</strong> <?php echo date('M d, Y g:i A', strtotime($msg['created_at'])); ?></span>
                    </div>
                </div>
                <a href="?delete=<?php echo $msg['id']; ?>" 
                   onclick="return confirm('Delete this message?')" 
                   class="btn btn-sm btn-danger">
                    Delete
                </a>
            </div>
            
            <div style="padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
                <p style="color: var(--gray-800); line-height: 1.6; white-space: pre-wrap;">
                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div></body></html>