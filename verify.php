<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'Verify Account';
$message = '';
$isSuccess = false;

$token = $_GET['token'] ?? '';

if ($token !== '') {
    $stmt = $conn->prepare('SELECT id FROM users WHERE verification_token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $update = $conn->prepare('UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?');
        $update->bind_param('i', $user['id']);
        $update->execute();
        $update->close();

        $isSuccess = true;
        $message = 'Your account has been verified! You may now log in.';
    } else {
        $message = 'Invalid or expired verification link.';
    }
    $stmt->close();
} else {
    $message = 'No verification token provided.';
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="alert <?php echo $isSuccess ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo e($message); ?>
        </div>
        <?php if ($isSuccess): ?>
            <a href="login.php" class="btn btn-dark">Go to Login</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
