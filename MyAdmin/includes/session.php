<?php

session_start();

if(!isset($_SESSION['admin'])){

    echo "<script>

            window.location='../admin/login.php';

          </script>";

    exit();

}

?>