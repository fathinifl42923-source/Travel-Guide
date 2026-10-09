<?php 
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = 'My Profile - TravelGuide';

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's reviews
$stmt = $pdo->prepare("
    SELECT r.*, p.name as place_name, p.id as place_id
    FROM reviews r
    JOIN places p ON r.place_id = p.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get stats
$favorites_count = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ?");
$favorites_count->execute([$_SESSION['user_id']]);
$favorites_count = $favorites_count->fetchColumn();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    
    if ($username && $email) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?");
            $stmt->execute([$username, $email, $bio, $_SESSION['user_id']]);
            $_SESSION['success'] = "Profile updated successfully!";
            $_SESSION['username'] = $username;
            header("Location: profile.php");
            exit();
        } catch(PDOException $e) {
            $error = "Failed to update profile";
        }
    }
}

include 'header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">👤 My Profile</h2>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
            <!-- Main Content -->
            <div>
                <!-- Profile Info Card -->
                <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; font-weight: bold;">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <div>
                            <h2 style="font-family: var(--font-heading); margin-bottom: 0.25rem;">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </h2>
                            <p style="color: var(--gray-600); margin-bottom: 0.5rem;">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>
                            <p style="color: var(--gray-500); font-size: 0.9rem;">
                                Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Edit Profile Form -->
                    <form method="POST" style="margin-top: 2rem;">
                        <h3 style="font-family: var(--font-heading); margin-bottom: 1.5rem;">Edit Profile</h3>
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" required 
                                   value="<?php echo htmlspecialchars($user['username']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required 
                                   value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn">
                            Update Profile
                        </button>
                    </form>
                </div>
                
                <!-- My Reviews -->
                <div class="card" style="padding: 2rem;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 1.5rem;">
                        My Reviews (<?php echo count($reviews); ?>)
                    </h3>
                    
                    <?php if(empty($reviews)): ?>
                        <p style="text-align: center; color: var(--gray-600); padding: 2rem;">
                            You haven't written any reviews yet
                        </p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <?php foreach($reviews as $review): ?>
                            <div style="border-bottom: 1px solid var(--gray-200); padding-bottom: 1.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                                    <a href="place-detail.php?id=<?php echo $review['place_id']; ?>" 
                                       style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($review['place_name']); ?>
                                    </a>
                                    <span style="color: var(--gray-500); font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <div class="rating" style="margin-bottom: 0.75rem;">
                                    <?php echo str_repeat('⭐', $review['rating']); ?>
                                </div>
                                
                                <p style="color: var(--gray-700); line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar Stats -->
            <div>
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 1.5rem;">Activity Stats</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
                            <div style="font-size: 2.5rem; color: var(--primary); font-weight: 700;">
                                <?php echo count($reviews); ?>
                            </div>
                            <div style="color: var(--gray-600); font-weight: 500;">Reviews Written</div>
                        </div>
                        
                        <div style="text-align: center; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
                            <div style="font-size: 2.5rem; color: var(--secondary); font-weight: 700;">
                                <?php echo $favorites_count; ?>
                            </div>
                            <div style="color: var(--gray-600); font-weight: 500;">Favorite Places</div>
                        </div>
                    </div>
                    
                    <a href="favorites.php" class="btn" style="width: 100%; margin-top: 1.5rem;">
                        ❤️ View My Favorites
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media (max-width: 968px) {
        .container > div {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php include 'footer.php'; ?>
