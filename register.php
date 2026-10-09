<?php 
require_once 'db.php';
$page_title = 'Register - TravelGuide';

$error = $success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if($stmt->rowCount() > 0) {
            $error = "Username or email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if($stmt->execute([$username, $email, $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed";
            }
        }
    }
}

include 'header.php';
?>

<section class="section">
    <div class="container">
        <div class="form-container">
            <h2 style="text-align: center; margin-bottom: 1.5rem; font-family: var(--font-heading);">
                Create Your Account
            </h2>
            <p style="text-align: center; color: var(--gray-600); margin-bottom: 2rem;">
                Join TravelGuide and start exploring amazing destinations
            </p>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <a href="login.php" style="color: var(--success); font-weight: 600; text-decoration: underline;">
                        Click here to login
                    </a>
                </div>
            <?php endif; ?>
            
            <form method="POST" data-validate>
                <div class="form-group">
                    <label>👤 Username</label>
                    <input type="text" name="username" required 
                           placeholder="Choose a unique username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>📧 Email Address</label>
                    <input type="email" name="email" required 
                           placeholder="your.email@example.com"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>🔒 Password</label>
                    <input type="password" name="password" required 
                           placeholder="At least 6 characters">
                    <small style="color: var(--gray-600); display: block; margin-top: 0.5rem;">
                        Must be at least 6 characters long
                    </small>
                </div>
                
                <div class="form-group">
                    <label>🔒 Confirm Password</label>
                    <input type="password" name="confirm_password" required 
                           placeholder="Re-enter your password">
                </div>
                
                <button type="submit" class="btn" style="width: 100%; font-size: 1.1rem;">
                    Create Account 🚀
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                <p style="color: var(--gray-600);">
                    Already have an account? 
                    <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                        Login here →
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
