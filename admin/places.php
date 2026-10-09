<?php
session_start();
require_once '../db.php';
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$success = $error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_place'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed)) {
            $newname = uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/places/' . $newname)) {
                $stmt = $pdo->prepare("INSERT INTO places (name, description, location, image) VALUES (?, ?, ?, ?)");
                if($stmt->execute([$name, $description, $location, $newname])) {
                    $success = "Place added successfully!";
                }
            }
        }
    }
}
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image FROM places WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $place = $stmt->fetch();
    if($place) {
        @unlink('../uploads/places/' . $place['image']);
        $stmt = $pdo->prepare("DELETE FROM places WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
    }
    header("Location: places.php");
    exit();
}
$page_title = 'Manage Places';
$stmt = $pdo->query("SELECT * FROM places ORDER BY created_at DESC");
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div class="admin-nav">
    <a href="dashboard.php">Dashboard</a>
    <a href="users.php">Manage Users</a>
    <a href="places.php" style="font-weight: bold;">Manage Places</a>
    <a href="gallery.php">Manage Gallery</a>
    <a href="messages.php">View Messages</a>
</div>
<h2 style="margin-bottom: 30px;">Manage Places</h2>
<?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
<div class="form-container">
    <h3 style="margin-bottom: 20px;">Add New Place</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Place Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" required></textarea>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" required>
        </div>
        <button type="submit" name="add_place" class="btn">Add Place</button>
    </form>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Location</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($places as $place): ?>
        <tr>
            <td><?php echo $place['id']; ?></td>
            <td><img src="../uploads/places/<?php echo $place['image']; ?>" class="thumbnail"></td>
            <td><?php echo htmlspecialchars($place['name']); ?></td>
            <td><?php echo htmlspecialchars($place['location']); ?></td>
            <td><?php echo htmlspecialchars(substr($place['description'], 0, 50)) . '...'; ?></td>
            <td>
                <a href="?delete=<?php echo $place['id']; ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>