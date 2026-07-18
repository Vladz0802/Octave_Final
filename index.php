<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'Home';

$sql = "SELECT product_id AS id, product_name AS name, price, image
        FROM products WHERE is_featured = 1 LIMIT 4";
$result = $conn->query($sql);

include 'includes/header.php';
?>

<div class="hero">
    <h1 class="fw-bold">Welcome to Octave</h1>
    <p class="lead mb-0">Guitars, drums, keyboards, and everything else you need to make music.</p>
</div>

<div class="section-heading d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
        <p class="eyebrow mb-1">Handpicked for you</p>
        <h3 class="mb-3">Featured Products</h3>
    </div>
    <a href="store.php" class="section-link mb-3">View all instruments &rarr;</a>
</div>
<div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100 shadow-sm">
                    <img src="assets/images/<?php echo e($row['image']); ?>"
                         class="card-img-top"
                         onerror="this.onerror=null;this.src='assets/images/default-instrument.png';"
                         alt="<?php echo e($row['name']); ?>">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo e($row['name']); ?></h6>
                        <p class="fw-bold text-success mb-2">₱<?php echo number_format($row['price'], 2); ?></p>
                        <a href="product.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-dark btn-sm mt-auto">
                            View Product
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">No featured products yet.</p>
    <?php endif; ?>
</div>

<div class="text-center mt-4">
    <a href="store.php" class="btn btn-warning">Browse Full Store</a>
</div>

<?php include 'includes/footer.php'; ?>
