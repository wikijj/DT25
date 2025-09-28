<?php
$hostname = 'localhost';
$database = 'distributed_db';   // názov tvojej DB
$username = 'root';             // na WAMP je štandardne root
$password = '';                 // prázdne heslo

$node_id = 1;

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
