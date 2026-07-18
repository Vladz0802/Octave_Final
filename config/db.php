<?php
// pls wag pakiealaman di naka save pass ko!!!!!!!!!!
$host     = 'sql104.infinityfree.com';
$dbname   = 'if0_42439251_myadmin_db';
$dbuser   = 'if0_42439251';
$dbpass   = 'gkTgUnPoKa';

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
