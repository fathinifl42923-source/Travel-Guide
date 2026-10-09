// Travel Guide - Main JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Favorite Toggle Functionality
    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    favoriteBtns.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const placeId = this.dataset.placeId;
            const isActive = this.classList.contains('active');
            
            try {
                const response = await fetch(isActive ? 'api/remove-favorite.php' : 'api/add-favorite.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ place_id: placeId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.classList.toggle('active');
                    this.textContent = this.classList.contains('active') ? '❤️' : '🤍';
                } else {
                    if (data.message === 'Not logged in') {
                        window.location.href = 'login.php';
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update favorite');
            }
        });
    });
    
    // Image Lightbox
    const galleryImages = document.querySelectorAll('.gallery-img, .lightbox-trigger');
    if (galleryImages.length > 0) {
        galleryImages.forEach(img => {
            img.addEventListener('click', function() {
                openLightbox(this.src, this.alt);
            });
        });
    }
    
    function openLightbox(src, alt) {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.innerHTML = `
            <div class="lightbox-content">
                <span class="lightbox-close">&times;</span>
                <img src="${src}" alt="${alt}">
                <p class="lightbox-caption">${alt}</p>
            </div>
        `;
        document.body.appendChild(lightbox);
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .lightbox {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.95);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                animation: fadeIn 0.3s ease;
            }
            .lightbox-content {
                max-width: 90%;
                max-height: 90%;
                position: relative;
            }
            .lightbox-content img {
                max-width: 100%;
                max-height: 80vh;
                border-radius: 10px;
                box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
            }
            .lightbox-caption {
                color: white;
                text-align: center;
                margin-top: 1rem;
                font-size: 1.1rem;
            }
            .lightbox-close {
                position: absolute;
                top: -40px;
                right: 0;
                color: white;
                font-size: 3rem;
                cursor: pointer;
                transition: transform 0.2s;
            }
            .lightbox-close:hover {
                transform: scale(1.2);
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        
        // Close lightbox
        const close = lightbox.querySelector('.lightbox-close');
        close.addEventListener('click', () => {
            lightbox.remove();
            style.remove();
        });
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.remove();
                style.remove();
            }
        });
    }
    
    // Search Functionality
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('input[name="search"]').value;
            window.location.href = `places.php?search=${encodeURIComponent(searchTerm)}`;
        });
    }
    
    // Newsletter Subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = this.querySelector('input[name="email"]').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Subscribing...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('api/subscribe-newsletter.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Successfully subscribed to newsletter!', 'success');
                    this.reset();
                } else {
                    showNotification(data.message || 'Subscription failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to subscribe', 'error');
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // Review Form
    const reviewForm = document.querySelector('.review-form');
    if (reviewForm) {
        const stars = reviewForm.querySelectorAll('.star-rating span');
        const ratingInput = reviewForm.querySelector('input[name="rating"]');
        
        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                ratingInput.value = index + 1;
                updateStars(index);
            });
        });
        
        function updateStars(activeIndex) {
            stars.forEach((star, index) => {
                star.textContent = index <= activeIndex ? '⭐' : '☆';
            });
        }
    }
    
    // Social Share
    window.shareOnSocial = function(platform, url, text) {
        const encodedUrl = encodeURIComponent(url);
        const encodedText = encodeURIComponent(text);
        let shareUrl;
        
        switch(platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
                break;
            case 'whatsapp':
                shareUrl = `https://wa.me/?text=${encodedText}%20${encodedUrl}`;
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    };
    
    // Notification System
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        const style = document.createElement('style');
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 10px;
                color: white;
                font-weight: 600;
                z-index: 10000;
                animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            .notification-success {
                background: hsl(142, 71%, 45%);
            }
            .notification-error {
                background: hsl(0, 72%, 59%);
            }
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes fadeOut {
                to {
                    opacity: 0;
                    transform: translateX(400px);
                }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
            style.remove();
        }, 3000);
    }
    
    // Form Validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'var(--danger)';
                } else {
                    input.style.borderColor = 'var(--gray-200)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill all required fields', 'error');
            }
        });
    });
    
    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
