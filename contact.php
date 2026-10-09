<?php 
require_once 'db.php';
$page_title = 'Contact Us - TravelGuide';

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if($name && $email && $subject && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            $success = "Thank you for contacting us! We'll get back to you soon.";
        } catch(PDOException $e) {
            $error = "Failed to send message. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

include 'header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">✉️ Contact Us</h2>
        <p class="section-subtitle">Have a question? We'd love to hear from you</p>
        
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start;">
            <!-- Contact Form -->
            <div class="form-container" style="margin: 0; max-width: none;">
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" data-validate>
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" required placeholder="What is this about?">
                    </div>
                    
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" required placeholder="Tell us what you're thinking..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%;">
                        Send Message 📨
                    </button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div>
                <div class="card" style="padding: 2rem;">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 1.5rem;">Get In Touch</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div>
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📧</div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Email</strong>
                            <a href="mailto:info@travelguide.com" 
                               style="color: var(--primary); text-decoration: none;">
                                info@travelguide.com
                            </a>
                        </div>
                        
                        <div>
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📞</div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Phone</strong>
                            <a href="tel:+1234567890" 
                               style="color: var(--primary); text-decoration: none;">
                                +1 (234) 567-890
                            </a>
                        </div>
                        
                        <div>
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📍</div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Address</strong>
                            <p style="color: var(--gray-700); margin: 0;">
                                123 Travel Street<br>
                                Adventure City, AC 12345
                            </p>
                        </div>
                        
                        <div>
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">⏰</div>
                            <strong style="display: block; margin-bottom: 0.25rem;">Business Hours</strong>
                            <p style="color: var(--gray-700); margin: 0;">
                                Monday - Friday: 9am - 5pm<br>
                                Saturday - Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="card" style="padding: 1.5rem; margin-top: 1.5rem; background: linear-gradient(135deg, var(--primary-light), var(--white));">
                    <h4 style="margin-bottom: 0.75rem;">💡 Quick Tip</h4>
                    <p style="color: var(--gray-700); font-size: 0.95rem; margin: 0;">
                        For faster response, please include as many details as possible in your message.
                    </p>
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