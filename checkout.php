<?php
require 'config/db.php';
require 'includes/functions.php';

requireLogin(); 

$pageTitle = 'Checkout';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$stmt = $conn->prepare('SELECT full_name, email, address, contact_number FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

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

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']];
    $subtotal = $row['price'] * $qty;
    $total += $subtotal;
    $cartItems[] = ['name' => $row['name'], 'price' => $row['price'], 'quantity' => $qty, 'subtotal' => $subtotal];
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    if ($full_name === '') $errors[] = 'Full name is required.';
    if ($address === '') $errors[] = 'Address is required.';
    if (!preg_match('/^[0-9+\-\s]{7,20}$/', $contact_number)) $errors[] = 'A valid contact number is required.';

    if (empty($errors)) {
        $_SESSION['checkout'] = [
            'full_name'      => $full_name,
            'address'        => $address,
            'contact_number' => $contact_number,
        ];
        header('Location: payment.php');
        exit;
    }
}

include 'includes/header.php';
?>

<h3 class="mb-4">Checkout</h3>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-7">
        <h5 class="mb-3">Customer Information</h5>
        <form method="POST" action="checkout.php" class="checkout-panel">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control"
                       value="<?php echo e($_POST['full_name'] ?? $user['full_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?php echo e($user['email']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Delivery Address</label>
                <textarea name="address" class="form-control" rows="2" required><?php echo e($_POST['address'] ?? $user['address']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="<?php echo e($_POST['contact_number'] ?? $user['contact_number']); ?>" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Continue to Payment</button>
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

<?php include 'includes/footer.php'; ?>
