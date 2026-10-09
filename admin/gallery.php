<?php
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$success = $error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_gallery'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed)) {
            $newname = uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/gallery/' . $newname)) {
                $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image) VALUES (?, ?, ?)");
                if($stmt->execute([$title, $description, $newname])) {
                    $success = "Gallery item added!";
                }
            }
        }
    }
}
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $item = $stmt->fetch();
    if($item) {
        @unlink('../uploads/gallery/' . $item['image']);
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
    }
    header("Location: gallery.php");
    exit();
}
$page_title = 'Manage Gallery';
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div class="admin-nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Manage Users</a>
    <a href="places.php">Manage Places</a>
    <a href="gallery.php" style="font-weight: bold;">Manage Gallery</a>
    <a href="messages.php">View Messages</a>
</div>
<h2 style="margin-bottom: 30px;">Manage Gallery</h2>
<?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<div class="form-container">
    <h3 style="margin-bottom: 20px;">Add Gallery Item</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" required>
        </div>
        <button type="submit" name="add_gallery" class="btn">Add to Gallery</button>
    </form>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($gallery as $item): ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><img src="../uploads/gallery/<?php echo $item['image']; ?>" class="thumbnail"></td>
            <td><?php echo htmlspecialchars($item['title']); ?></td>
            <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)); ?></td>
            <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
            <td>
                <a href="?delete=<?php echo $item['id']; ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>