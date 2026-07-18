<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'Your Cart';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // format: [product_id => quantity]
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = max(1, (int)($_POST['quantity'] ?? 1));

        if ($productId > 0) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
            $currentQuantity = $_SESSION['cart'][$productId];
            $_SESSION['cart_message'] = 'Added ' . $quantity . ' item' . ($quantity === 1 ? '' : 's')
                . '. You now have ' . $currentQuantity . ' of this item in your cart.';
        }
    }

    if ($action === 'update' && isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        $updatedQuantity = null;
        foreach ($_POST['quantities'] as $productId => $qty) {
            $productId = (int)$productId;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
                $updatedQuantity = 0;
            } elseif (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] = $qty;
                $updatedQuantity = $qty;
            }
        }
        if ($updatedQuantity !== null) {
            $_SESSION['cart_message'] = $updatedQuantity > 0
                ? 'Quantity updated. You now have ' . $updatedQuantity . ' of this item in your cart.'
                : 'Item removed from your cart.';
        }
    }

    if ($action === 'remove') {
        $productId = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$productId]);
        $_SESSION['cart_message'] = 'Item removed from your cart.';
    }

    header('Location: cart.php');
    exit;
}

$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare(
        "SELECT product_id AS id, product_name AS name, price, stock, image
         FROM products WHERE product_id IN ($placeholders)"
    );
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;

        $cartItems[] = [
            'id'       => $row['id'],
            'name'     => $row['name'],
            'price'    => $row['price'],
            'stock'    => $row['stock'],
            'image'    => $row['image'],
            'quantity' => $qty,
            'subtotal' => $subtotal,
        ];
    }
}

include 'includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
    <div>
        <p class="eyebrow mb-1">Your selections</p>
        <h3 class="mb-0">Your Cart</h3>
    </div>
    <p class="cart-count mb-0"><?php echo getCartCount(); ?> item<?php echo getCartCount() === 1 ? '' : 's'; ?> in cart</p>
</div>

<?php if (!empty($_SESSION['cart_message'])): ?>
    <div class="alert alert-success cart-message" role="status">
        <?php echo e($_SESSION['cart_message']); unset($_SESSION['cart_message']); ?>
    </div>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <div class="alert alert-info">
        Your cart is empty. <a href="store.php">Browse products</a> to add something.
    </div>
<?php else: ?>
    <table class="table align-middle bg-white">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th style="width: 160px;">Quantity</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="assets/images/<?php echo e($item['image']); ?>"
                                 onerror="this.onerror=null;this.src='assets/images/default-instrument.png';"
                                 width="50" height="50" style="object-fit:cover;" class="rounded">
                            <?php echo e($item['name']); ?>
                        </div>
                    </td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <!-- Each row is its own small form: update quantity for this one item -->
                        <form method="POST" action="cart.php" class="d-flex gap-1">
                            <input type="hidden" name="action" value="update">
                            <input type="number" name="quantities[<?php echo (int)$item['id']; ?>]" aria-label="Quantity for <?php echo e($item['name']); ?>"
                                   value="<?php echo (int)$item['quantity']; ?>"
                                   min="0" max="<?php echo (int)$item['stock']; ?>" class="form-control form-control-sm quantity-input">
                            <button type="submit" class="btn btn-sm btn-outline-dark">Update</button>
                        </form>
                    </td>
                    <td>₱<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td>
                        <!-- Separate small form just for removing this item -->
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo (int)$item['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        <h5 class="mb-0">Total: ₱<?php echo number_format($total, 2); ?></h5>
    </div>

    <div class="text-end mt-3">
        <a href="checkout.php" class="btn btn-dark">Proceed to Checkout</a>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
