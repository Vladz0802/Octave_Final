<?php
require 'config/db.php';
require 'includes/functions.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare(
    'SELECT p.product_id AS id, p.product_name AS name, p.description, p.price, p.stock, p.image,
            c.category_name AS category_name
     FROM products p
     JOIN categories c ON p.category_id = c.category_id
     WHERE p.product_id = ?'
);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: store.php');
    exit;
}

$product = $result->fetch_assoc();
$pageTitle = $product['name'];

include 'includes/header.php';
?>

<div class="row g-4 product-detail">
    <div class="col-md-5">
        <img src="assets/images/<?php echo e($product['image']); ?>"
             class="img-fluid product-image"
             onerror="this.onerror=null;this.src='assets/images/default-instrument.png';"
             alt="<?php echo e($product['name']); ?>">
    </div>
    <div class="col-md-7">
        <div class="product-info">
        <span class="badge product-category mb-2"><?php echo e($product['category_name']); ?></span>
        <h3><?php echo e($product['name']); ?></h3>
        <p class="fs-4 text-success fw-bold">₱<?php echo number_format($product['price'], 2); ?></p>
        <p><?php echo nl2br(e($product['description'])); ?></p>
        <p class="text-muted">
            <?php echo $product['stock'] > 0 ? $product['stock'] . ' units in stock' : 'Currently out of stock'; ?>
        </p>

        <?php if ($product['stock'] > 0): ?>
            <form method="POST" action="cart.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <label class="mb-0">Qty:</label>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo (int)$product['stock']; ?>"
                       class="form-control" style="width: 80px;">
                <button type="submit" class="btn btn-dark">Add to Cart</button>
            </form>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>Out of Stock</button>
        <?php endif; ?>

        <div class="mt-3">
            <a href="store.php" class="btn btn-link px-0">&laquo; Back to Store</a>
        </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
