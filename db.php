<?php
$hostname = 'localhost';
$database = 'distributed_db';   // názov DB
$username = 'root';             // ponechávame štandardný root
$password = '';                 // prázdne heslo

$node_id = 2;

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
