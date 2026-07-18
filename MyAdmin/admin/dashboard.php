<?php

include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/header.php");

$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
$total_admins   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM admins"))['total'];
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))['total'];
$low_stock      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE stock <= 5"))['total'];

?>

<div class="container-fluid">

    <div class="row">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-lg-10 p-4">

            <div class="topbar">

                <div>

                   <h2 class="fw-bold">
                        Dashboard
                    </h2>

                    <small>
                        Welcome back,
                        <strong>
                            <?php echo $_SESSION['fullname']; ?>
                        </strong>
                    </small>

                </div>

            </div>

            <div class="row mt-4 g-4">

                <div class="col-lg-3">

                    <div class="dashboard-card">

                        <i class="bi bi-box-seam card-icon"></i>

                        <h6>Products</h6>

                        <h1><?php echo $total_products; ?></h1>

                        <small class="text-muted">
                            Total Products
                        </small>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="dashboard-card">

                        <i class="bi bi-people card-icon"></i>

                        <h6>Admin Users</h6>

                        <h1><?php echo $total_admins; ?></h1>

                        <small class="text-muted">
                            Registered Admins
                        </small>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="dashboard-card">

                        <i class="bi bi-tags card-icon"></i>

                        <h6>Categories</h6>

                        <h1><?php echo $total_categories; ?></h1>

                        <small class="text-muted">
                            Available Categories
                        </small>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="dashboard-card">

                        <i class="bi bi-exclamation-triangle card-icon"></i>

                        <h6>Low Stock</h6>

                        <h1><?php echo $low_stock; ?></h1>

                        <small class="text-muted">
                            Products Running Low
                        </small>

                    </div>

                </div>

            </div>

            <div class="card shadow mt-4">

                <div class="card-header fw-bold">

                    <i class="bi bi-clock-history"></i>

                    Recent Activities

                </div>

                <div class="card-body">

                    <p>✔ Welcome to MyAdmin.</p>

                    <p>✔ Login Successful.</p>

                    <p>Inventory activities will appear here.</p>

                </div>

            </div>

            <?php include("../includes/footer.php"); ?>

        </div>

    </div>

</div>