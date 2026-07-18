<?php
require 'config/db.php';
require 'includes/functions.php';

requireLogin();

$pageTitle = 'Payment';

if (empty($_SESSION['cart']) || empty($_SESSION['checkout'])) {
    header('Location: cart.php');
    exit;
}

$errors = [];
$orderPlaced = false;
$orderId = 0;

function getCartItemsAndTotal($conn) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare(
        "SELECT product_id AS id, product_name AS name, price, stock
         FROM products WHERE product_id IN ($placeholders)"
    );
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'id' => $row['id'], 'name' => $row['name'], 'price' => $row['price'],
            'stock' => $row['stock'], 'quantity' => $qty, 'subtotal' => $subtotal,
        ];
    }
    return [$items, $total];
}

[$cartItems, $total] = getCartItemsAndTotal($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $validMethods = ['Cash on Delivery', 'GCash', 'Bank Transfer'];

    if (!in_array($paymentMethod, $validMethods, true)) {
        $errors[] = 'Please select a valid payment method.';
    }

    if (empty($errors)) {
        $checkout = $_SESSION['checkout'];

        $stmt = $conn->prepare(
            'INSERT INTO orders (user_id, full_name, address, contact_number, payment_method, total_price, status)
             VALUES (?, ?, ?, ?, ?, ?, "Pending")'
        );
        $stmt->bind_param(
            'issssd',
            $_SESSION['user_id'],
            $checkout['full_name'],
            $checkout['address'],
            $checkout['contact_number'],
            $paymentMethod,
            $total
        );
        $stmt->execute();
        $orderId = $conn->insert_id;
        $stmt->close();

        foreach ($cartItems as $item) {
            $stmt = $conn->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('iisid', $orderId, $item['id'], $item['name'], $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare('UPDATE products SET stock = stock - ? WHERE product_id = ?');
            $stmt->bind_param('ii', $item['quantity'], $item['id']);
            $stmt->execute();
            $stmt->close();
        }

        unset($_SESSION['cart']);
        unset($_SESSION['checkout']);

        $orderPlaced = true;
    }
}

include 'includes/header.php';
?>

<?php if ($orderPlaced): ?>
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="alert alert-success">
                <h4>Order Confirmed!</h4>
                <p class="mb-1">Your order number is <strong>#<?php echo (int)$orderId; ?></strong>.</p>
                <p class="mb-0">Thank you for shopping with Octave.</p>
            </div>
            <a href="store.php" class="btn btn-dark">Continue Shopping</a>
        </div>
    </div>
<?php else: ?>
    <h3 class="mb-4">Payment</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <p class="mb-0"><?php echo e($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <h5 class="mb-3">Select Payment Method</h5>
            <form method="POST" action="payment.php" class="checkout-panel">
                <div class="form-check border rounded p-3 mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" value="Cash on Delivery" id="cod" checked>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <div class="form-check border rounded p-3 mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" value="GCash" id="gcash">
                    <label class="form-check-label" for="gcash">GCash</label>
                </div>
                <div class="form-check border rounded p-3 mb-3">
                    <input class="form-check-input" type="radio" name="payment_method" value="Bank Transfer" id="bank">
                    <label class="form-check-label" for="bank">Bank Transfer</label>
                </div>
                <button type="submit" class="btn btn-dark w-100">Place Order</button>
            </form>
        </div>

        <div class="col-md-5">
            <div class="summary-panel">
            <h5>Order Summary</h5>
            <ul class="list-group mb-0">
                <?php foreach ($cartItems as $item): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?php echo e($item['name']); ?> &times; <?php echo (int)$item['quantity']; ?></span>
                        <span>₱<?php echo number_format($item['subtotal'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>₱<?php echo number_format($total, 2); ?></span>
                </li>
            </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
