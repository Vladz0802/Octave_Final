<?php

session_start();

include("../includes/db_connection.php");
include("../includes/functions.php");

addAuditLog($conn, $_SESSION['admin'], "Logged Out");

if (isset($_SESSION['admin'])) {
    addAuditLog($conn, $_SESSION['admin'], "Logged Out");
}

session_destroy();

header("Location: login.php");
exit();

?>