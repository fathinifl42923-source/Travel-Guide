<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About TravelGuide</h3>
                <p>Discover the world's most breathtaking destinations with expert recommendations and insider tips.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="places.php">Destinations</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">YouTube</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p style="margin-bottom: 1rem;">Subscribe to get travel tips and special offers.</p>
                <form class="newsletter-form" style="display: flex; gap: 0.5rem;">
                    <input type="email" name="email" placeholder="Your email" required 
                           style="flex: 1; padding: 0.65rem; border: none; border-radius: 6px; font-family: var(--font-body);">
                    <button type="submit" class="btn btn-sm" style="white-space: nowrap;">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> TravelGuide. All rights reserved. Made with ❤️ for travelers.</p>
        </div>
    </div>
</footer>
<script src="js/main.js"></script>
</body>
</html>