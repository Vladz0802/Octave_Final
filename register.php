<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'Register';
$errors = [];
$success = false;
$verifyLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name      = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $confirmPass    = $_POST['confirm_password'] ?? '';
    $address        = trim($_POST['address'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    if ($full_name === '') $errors[] = 'Complete name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirmPass) $errors[] = 'Passwords do not match.';
    if ($address === '') $errors[] = 'Complete address is required.';
    if (!preg_match('/^[0-9+\-\s]{7,20}$/', $contact_number)) $errors[] = 'A valid contact number is required.';

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        $stmt = $conn->prepare(
            'INSERT INTO users (full_name, email, password, address, contact_number, is_verified, verification_token)
             VALUES (?, ?, ?, ?, ?, 0, ?)'
        );
        $stmt->bind_param('ssssss', $full_name, $email, $hashedPassword, $address, $contact_number, $token);

        if ($stmt->execute()) {
            $success = true;

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
            $verifyLink = "$protocol://$host$basePath/verify.php?token=$token";

            $subject = 'Verify your Octave account';
            $emailBody = "Hi $full_name,\n\n"
                       . "Thanks for registering at Octave. Please click the link below to verify your account:\n\n"
                       . "$verifyLink\n\n"
                       . "If you didn't create this account, you can ignore this email.\n\n"
                       . "- Octave Team";
            $headers = "From: no-reply@octave.local\r\n";

            $mailSent = @mail($email, $subject, $emailBody, $headers);
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <h3 class="mb-4">Create an Account</h3>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php if ($mailSent): ?>
                    <p class="mb-0">Registration successful! We've sent a verification link to <strong><?php echo e($email); ?></strong> — please check your inbox (and spam folder) to activate your account.</p>
                <?php else: ?>
                    <p class="mb-2">Registration successful! We tried to send a verification email to <strong><?php echo e($email); ?></strong>, but this server may not be able to send real emails right now.</p>
                    <p class="mb-0">
                        As a backup, here's your verification link:
                        <a href="<?php echo e($verifyLink); ?>">Click here to verify your account</a>
                    </p>
                <?php endif; ?>
            </div>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php" novalidate>
                <div class="mb-3">
                    <label class="form-label">Complete Name</label>
                    <input type="text" name="full_name" class="form-control"
                           value="<?php echo e($_POST['full_name'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo e($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Complete Address</label>
                    <textarea name="address" class="form-control" rows="2" required><?php echo e($_POST['address'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control"
                           value="<?php echo e($_POST['contact_number'] ?? ''); ?>" placeholder="09xxxxxxxxx" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Register</button>
            </form>

            <p class="text-center mt-3">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
