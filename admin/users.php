<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: users.php");
    exit();
}

$page_title = 'Manage Users';

// Get users with their activity stats
$stmt = $pdo->query("
    SELECT u.*, 
           COUNT(DISTINCT f.id) as favorites_count,
           COUNT(DISTINCT r.id) as reviews_count
    FROM users u
    LEFT JOIN favorites f ON u.id = f.user_id
    LEFT JOIN reviews r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="admin-nav" style="background: white; padding: 1rem 0; margin-bottom: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
        <a href="dashboard.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📊 Dashboard</a>
        <a href="users.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">👥 Users</a>
        <a href="places.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📍 Places</a>
        <a href="categories.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📂 Categories</a>
        <a href="gallery.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📸 Gallery</a>
        <a href="reviews.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">⭐ Reviews</a>
        <a href="messages.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">💬 Messages</a>
        <a href="newsletter.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📧 Newsletter</a>
    </div>
</div>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">👥 Manage Users (<?php echo count($users); ?>)</h2>

<?php if(empty($users)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--gray-600);">No users registered yet</p>
    </div>
<?php else: ?>
    <div class="card" style="padding: 0; overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Reviews</th>
                    <th>Favorites</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span style="background: var(--accent); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600;">
                            ⭐ <?php echo $user['reviews_count']; ?>
                        </span>
                    </td>
                    <td>
                        <span style="background: var(--secondary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600;">
                            ❤️ <?php echo $user['favorites_count']; ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <a href="?delete=<?php echo $user['id']; ?>" 
                           onclick="return confirm('Delete user <?php echo htmlspecialchars($user['username']); ?>? This will also delete their reviews and favorites.')" 
                           class="btn btn-sm btn-danger">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</div></body></html>