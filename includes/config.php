<?php
$host = 'localhost';
$dbname = 'computer_inventory';
$user = 'computer_inventory_manager';
$password = 'b(79yKo8Ei';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
