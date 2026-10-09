<?php 
require_once 'db.php';
$page_title = 'Login - TravelGuide';
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if(empty($username) || empty($password)) {
        $error = "All fields are required";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    }
}
include 'header.php';
?>
<div class="form-container">
    <h2 style="text-align: center; margin-bottom: 30px;">Login</h2>
    <?php if($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Username or Email</label><input type="text" name="username" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit" class="btn" style="width: 100%;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.php" style="color: #005a9c;">Register here</a></p>
    <p style="text-align: center; margin-top: 10px;"><a href="admin/login.php" style="color: #6c757d; font-size: 0.9rem;">Admin Login</a></p>
</div>
<?php include 'footer.php'; ?>
