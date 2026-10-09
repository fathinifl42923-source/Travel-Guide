<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'Manage Categories';

// Handle add category
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $icon = trim($_POST['icon']);
    
    if($name && $icon) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, icon) VALUES (?, ?)");
        $stmt->execute([$name, $icon]);
        $success = "Category added successfully!";
    }
}

// Handle delete
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = "Category deleted successfully!";
}

// Get all categories
$categories = $pdo->query("
    SELECT c.*, COUNT(pc.place_id) as place_count
    FROM categories c
    LEFT JOIN place_categories pc ON c.id = pc.category_id
    GROUP BY c.id
    ORDER BY c.name ASC
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="admin-nav" style="background: white; padding: 1rem 0; margin-bottom: 2rem; box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
        <a href="dashboard.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📊 Dashboard</a>
        <a href="users.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">👥 Users</a>
        <a href="places.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📍 Places</a>
        <a href="categories.php" style="font-weight: bold; color: var(--primary); text-decoration: none; padding: 0.5rem 1rem; background: var(--primary-light); border-radius: var(--radius-md);">📂 Categories</a>
        <a href="gallery.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📸 Gallery</a>
        <a href="reviews.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">⭐ Reviews</a>
        <a href="messages.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">💬 Messages</a>
        <a href="newsletter.php" style="color: var(--gray-700); text-decoration: none; padding: 0.5rem 1rem;">📧 Newsletter</a>
    </div>
</div>

<?php if(isset($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<h2 style="margin-bottom: 2rem; font-family: var(--font-heading);">📂 Manage Categories</h2>

<div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
    <!-- Categories List -->
    <div class="card" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">All Categories (<?php echo count($categories); ?>)</h3>
        
        <?php if(empty($categories)): ?>
            <p style="text-align: center; color: var(--gray-600); padding: 2rem;">No categories yet</p>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                <?php foreach($categories as $cat): ?>
                <div class="card" style="text-align: center; padding: 1.5rem; position: relative;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                        <?php echo htmlspecialchars($cat['icon']); ?>
                    </div>
                    <h4 style="margin-bottom: 0.5rem; font-size: 1rem;">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </h4>
                    <p style="color: var(--gray-600); font-size: 0.85rem; margin-bottom: 1rem;">
                        <?php echo $cat['place_count']; ?> places
                    </p>
                    <a href="?delete=<?php echo $cat['id']; ?>" 
                       onclick="return confirm('Delete this category?')"
                       class="btn btn-sm btn-danger"
                       style="font-size: 0.85rem;">
                        Delete
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Add Category Form -->
    <div class="card" style="padding: 2rem; height: fit-content;">
        <h3 style="margin-bottom: 1.5rem;">Add New Category</h3>
        <form method="POST">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" required placeholder="e.g., Beach, Mountain">
            </div>
            
            <div class="form-group">
                <label>Icon (Emoji)</label>
                <input type="text" name="icon" required placeholder="e.g., 🏖️, ⛰️">
                <small style="color: var(--gray-600); display: block; margin-top: 0.5rem;">
                    Use an emoji as the category icon
                </small>
            </div>
            
            <button type="submit" name="add_category" class="btn" style="width: 100%;">
                Add Category
            </button>
        </form>
        
        <div style="margin-top: 2rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
            <h4 style="margin-bottom: 0.75rem; font-size: 0.95rem;">Popular Emojis:</h4>
            <div style="font-size: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                🏖️ ⛰️ 🏙️ 🏛️ 🎿 🌲 🎭 🧘 🏕️ 🏰 🌋 🏝️ 🌊 🎡 🏞️
            </div>
        </div>
    </div>
</div>

</div></body></html>
