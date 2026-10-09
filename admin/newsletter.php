<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Newsletter Subscribers';

// Get all subscribers
$subscribers = $pdo->query("
    SELECT * FROM newsletter_subscribers 
    ORDER BY subscribed_at DESC
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
        <a href="reviews.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">⭐ Reviews</a>
        <a href="messages.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">💬 Messages</a>
        <a href="newsletter.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">📧 Newsletter</a>
    </div>
</div>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">📧 Newsletter Subscribers (<?php echo count($subscribers); ?>)</h2>

<?php if(empty($subscribers)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">📧</div>
        <p style="color: var(--gray-600);">No newsletter subscribers yet</p>
    </div>
<?php else: ?>
    <div class="card" style="padding: 2rem; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Email Address</th>
                    <th>Subscribed On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($subscribers as $subscriber): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($subscriber['email']); ?></strong>
                    </td>
                    <td>
                        <?php echo date('F d, Y', strtotime($subscriber['subscribed_at'])); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card" style="padding: 1.5rem; margin-top: 1.5rem; background: var(--primary-light);">
        <h3 style="margin-bottom: 0.75rem; font-size: 1.1rem;">Export Subscribers</h3>
        <p style="color: var(--gray-700); margin-bottom: 1rem;">
            You can copy the email addresses below to use in your email marketing service:
        </p>
        <textarea readonly 
                  style="width: 100%; padding: 1rem; border: 2px solid var(--primary); border-radius: var(--radius-md); font-family: monospace; min-height: 100px;"
                  onclick="this.select()"><?php 
            echo implode(', ', array_column($subscribers, 'email')); 
        ?></textarea>
        <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--gray-600);">
            Click the text area to select all emails
        </p>
    </div>
<?php endif; ?>

</div></body></html>
