<?php 
require_once 'db.php';
$page_title = 'All Destinations - TravelGuide';

// Get filter parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// Build query
$query = "SELECT p.*, 
          COALESCE(AVG(r.rating), 0) as avg_rating,
          COUNT(r.id) as review_count
          FROM places p
          LEFT JOIN reviews r ON p.id = r.place_id ";

$params = [];
$conditions = [];

if ($search) {
    $conditions[] = "(p.name LIKE ? OR p.location LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $query .= " JOIN place_categories pc ON p.id = pc.place_id ";
    $conditions[] = "pc.category_id = ?";
    $params[] = $category;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " GROUP BY p.id";

// Sorting
switch($sort) {
    case 'rating':
        $query .= " ORDER BY avg_rating DESC, review_count DESC";
        break;
    case 'name':
        $query .= " ORDER BY p.name ASC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filter
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Get user favorites
$user_favorites = [];
if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT place_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

include 'header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Explore All Destinations</h2>
        <p class="section-subtitle">Find your perfect getaway from our curated collection</p>
        
        <!-- Search and Filter Bar -->
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <form method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: end;">
                <div class="form-group" style="flex: 1; min-width: 250px; margin: 0;">
                    <label>🔍 Search</label>
                    <input type="text" name="search" placeholder="Search destinations..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           style="width: 100%; padding: 0.75rem; border: 2px solid var(--gray-200); border-radius: var(--radius-md);">
                </div>
                
                <div class="form-group" style="flex: 0.5; min-width: 200px; margin: 0;">
                    <label>📂 Category</label>
                    <select name="category" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid var(--gray-200); border-radius: var(--radius-md);">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['icon'] . ' ' . $cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="flex: 0.5; min-width: 200px; margin: 0;">
                    <label>⚡ Sort By</label>
                    <select name="sort" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid var(--gray-200); border-radius: var(--radius-md);">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name (A-Z)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" style="margin: 0;">Apply Filters</button>
                <a href="places.php" class="btn btn-outline" style="margin: 0;">Clear</a>
            </form>
        </div>
        
        <!-- Results Count -->
        <p style="margin-bottom: 1.5rem; color: var(--gray-600); font-weight: 500;">
            Found <?php echo count($places); ?> destination<?php echo count($places) != 1 ? 's' : ''; ?>
            <?php if($search): ?>
                for "<?php echo htmlspecialchars($search); ?>"
            <?php endif; ?>
        </p>
        
        <!-- Destinations Grid -->
        <?php if(empty($places)): ?>
            <div class="card" style="text-align: center; padding: 3rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🔍</div>
                <h3 style="color: var(--gray-700); margin-bottom: 0.5rem;">No destinations found</h3>
                <p style="color: var(--gray-600);">Try adjusting your search or filters</p>
                <a href="places.php" class="btn" style="margin-top: 1rem;">View All Destinations</a>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach($places as $place): 
                    $isFavorite = in_array($place['id'], $user_favorites);
                ?>
                <div class="card">
                    <img src="uploads/places/<?php echo htmlspecialchars($place['image']); ?>" 
                         alt="<?php echo htmlspecialchars($place['name']); ?>" 
                         class="card-img">
                    
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h3 class="card-title" style="margin: 0; flex: 1;">
                                <?php echo htmlspecialchars($place['name']); ?>
                            </h3>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button class="favorite-btn <?php echo $isFavorite ? 'active' : ''; ?>" 
                                        data-place-id="<?php echo $place['id']; ?>"
                                        title="<?php echo $isFavorite ? 'Remove from favorites' : 'Add to favorites'; ?>">
                                    <?php echo $isFavorite ? '❤️' : '🤍'; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <p class="card-text" style="margin-bottom: 0.5rem;">
                            <strong>📍 Location:</strong> <?php echo htmlspecialchars($place['location']); ?>
                        </p>
                        
                        <?php if($place['avg_rating'] > 0): ?>
                        <div class="rating" style="margin-bottom: 0.75rem;">
                            <span class="stars">
                                <?php 
                                $rating = round($place['avg_rating']);
                                echo str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating); 
                                ?>
                            </span>
                            <span style="color: var(--gray-600); font-size: 0.9rem;">
                                <?php echo number_format($place['avg_rating'], 1); ?> 
                                (<?php echo $place['review_count']; ?> reviews)
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <p class="card-text">
                            <?php echo htmlspecialchars(substr($place['description'], 0, 120)) . '...'; ?>
                        </p>
                        
                        <a href="place-detail.php?id=<?php echo $place['id']; ?>" 
                           class="btn btn-sm" 
                           style="width: 100%; margin-top: 1rem;">
                            View Full Details →
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>