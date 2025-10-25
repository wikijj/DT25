<?php
session_start(); // spusti session
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM records WHERE id = ?");
    $stmt->execute([$id]);

    // Nastavenie hlášky do session
    $_SESSION['message'] = "Produkt bol vymazaný!";
}

header("Location: index.php?page=list");
exit;
?>
