<?php

$host = 'sql207.infinityfree.com';      
$username = 'if0_40024415';             
$password = 'lth05022004huy'; 
$dbname = 'if0_40024415_banhang';     

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());}
?>