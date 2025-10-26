<?php
require 'db.php';
session_start(); // ak ešte nie je spustená session

// Získanie ID z URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Načítať záznam
$stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['message'] = "Záznam neexistuje!";
    header("Location: index.php?page=list");
    exit;
}

// Kontrola autora
if ($product['node_id'] != $node_id) {
    $_SESSION['message'] = "Nemôžete zmazať tento produkt – nie ste autor.";
    header("Location: index.php?page=list");
    exit;
}

// Namiesto priameho DELETE nastavíme needs_replication = 1 a deleted_at
$stmt = $pdo->prepare("UPDATE records 
                       SET needs_replication = 1, deleted_at = NOW() 
                       WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message'] = "Produkt bol vymazaný a replikácia sa postará o odstránenie na ostatných uzloch!";
header("Location: index.php?page=list");
exit;
?>
