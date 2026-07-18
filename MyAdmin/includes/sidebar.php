<?php

$current_page = basename($_SERVER['PHP_SELF']);

?>

<div class="col-lg-2 sidebar text-white p-4">

    <div class="text-center">

        <img src="../uploads/logo.png" alt="Octave logo" width="48" height="48" class="mb-2">
        <h2 class="fw-bold">OCTAVE</h2>

        <small>Administration</small>

    </div>

    <hr>

    <a href="dashboard.php"
       class="menu <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </a>

    <a href="manage_products.php"
       class="menu <?php echo ($current_page == 'manage_products.php') ? 'active' : ''; ?>">

        <i class="bi bi-box-seam"></i>

        Manage Products

    </a>

    <a href="admin_users.php"
       class="menu <?php echo ($current_page == 'admin_users.php') ? 'active' : ''; ?>">

        <i class="bi bi-people"></i>

        Admin Users

    </a>

    <a href="inventory_reports.php"
       class="menu <?php echo ($current_page == 'inventory_reports.php') ? 'active' : ''; ?>">

        <i class="bi bi-bar-chart"></i>

        Inventory Report

    </a>

    <a href="audit_logs.php"
       class="menu <?php echo ($current_page == 'audit_logs.php') ? 'active' : ''; ?>">

        <i class="bi bi-clock-history"></i>

        Audit Logs

    </a>

    <a href="logout.php" class="logout">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>

</div>