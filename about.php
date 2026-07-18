<?php
require 'config/db.php';
require 'includes/functions.php';

$pageTitle = 'About Us';

include 'includes/header.php';
?>

<div class="page-intro">
    <p class="eyebrow mb-1">Made for music lovers</p>
    <h3 class="mb-4">About Octave</h3>
</div>

<div class="row">
    <div class="col-md-8">
        <h5>Company Information</h5>
        <p>
            Octave is an online musical instrument shop built as a final project to demonstrate a working
            e-commerce buyer flow using PHP and MySQL, from browsing instruments by category to placing an order.
        </p>

        <h5 class="mt-4">Group Members</h5>
        <ul>
            <li>Jamie Noelle Flores</li>
            <li>Miguel Martin</li>
            <li>Maria Allison See</li>
            <li>Vladimir Jedric Vasquez</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
