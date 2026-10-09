<?php
require_once '../db.php';
if(isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if(empty($username) || empty($password)) {
        $error = "All fields are required";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .form-container { max-width: 400px; width: 100%; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; color: #005a9c; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; }
        .btn { width: 100%; padding: 12px; background: #005a9c; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #004080; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; background: #f8d7da; color: #721c24; }
        a { color: #6c757d; text-decoration: none; text-align: center; display: block; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Admin Login</h2>
        <?php if($error): ?><div class="alert"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <a href="../index.php">Back to Website</a>
    </div>
</body>
</html>