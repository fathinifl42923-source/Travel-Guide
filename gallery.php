<?php 
require_once 'db.php';
$page_title = 'Photo Gallery - TravelGuide';

// Get all gallery images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">📸 Photo Gallery</h2>
        <p class="section-subtitle">Stunning visuals from around the world</p>
        
        <?php if(empty($gallery)): ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.5;">📷</div>
                <h3 style="color: var(--gray-700); margin-bottom: 0.5rem;">No gallery images yet</h3>
                <p style="color: var(--gray-600);">Check back soon for amazing travel photography!</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php foreach($gallery as $item): ?>
                <div class="card" style="cursor: pointer;" onclick="openGalleryLightbox(this)">
                    <img src="uploads/gallery/<?php echo htmlspecialchars($item['image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>" 
                         class="card-img gallery-img"
                         style="height: 280px;">
                    
                    <div class="card-body">
                        <h3 class="card-title" style="font-size: 1.2rem; margin-bottom: 0.5rem;">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h3>
                        <p class="card-text" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($item['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function openGalleryLightbox(card) {
    const img = card.querySelector('img');
    const alt = img.alt;
    const src = img.src;
    
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
</script>

<?php include 'footer.php'; ?>