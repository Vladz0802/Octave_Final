<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'Store';

$categories = $conn->query('SELECT category_id AS id, category_name AS name FROM categories ORDER BY category_name');

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($selectedCategory > 0) {
    $stmt = $conn->prepare(
        'SELECT product_id AS id, product_name AS name, price, stock, image
         FROM products WHERE category_id = ? ORDER BY product_name'
    );
    $stmt->bind_param('i', $selectedCategory);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query(
        'SELECT product_id AS id, product_name AS name, price, stock, image
         FROM products ORDER BY product_name'
    );
}

include 'includes/header.php';
?>

<div class="page-intro">
    <p class="eyebrow mb-1">Find your sound</p>
    <h3 class="mb-1">Our Products</h3>
    <p class="text-muted mb-4">Browse instruments and essentials for every kind of musician.</p>
</div>

<div class="row">
    <!-- Category filter sidebar -->
    <div class="col-md-3 mb-4">
        <div class="category-filter">
            <p class="filter-label mb-2">Browse by category</p>
            <div class="list-group">
            <a href="store.php" class="list-group-item list-group-item-action <?php echo $selectedCategory === 0 ? 'active' : ''; ?>">
                All Products
            </a>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <a href="store.php?category=<?php echo (int)$cat['id']; ?>"
                   class="list-group-item list-group-item-action <?php echo $selectedCategory === (int)$cat['id'] ? 'active' : ''; ?>">
                    <?php echo e($cat['name']); ?>
                </a>
            <?php endwhile; ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="row g-4">
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while ($p = $products->fetch_assoc()): ?>
                    <div class="col-6 col-md-4">
                        <div class="card product-card h-100 shadow-sm">
                            <img src="assets/images/<?php echo e($p['image']); ?>"
                                 class="card-img-top"
                                 onerror="this.onerror=null;this.src='assets/images/default-instrument.png';"
                                 alt="<?php echo e($p['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title"><?php echo e($p['name']); ?></h6>
                                <p class="fw-bold text-success mb-1">₱<?php echo number_format($p['price'], 2); ?></p>
                                <p class="small text-muted mb-2">
                                    <?php echo $p['stock'] > 0 ? $p['stock'] . ' in stock' : 'Out of stock'; ?>
                                </p>
                                <a href="product.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-outline-dark btn-sm mt-auto">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No products found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
