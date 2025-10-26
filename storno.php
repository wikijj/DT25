<?php
require 'db.php';
session_start();

$transaction_id = $_POST['transaction_id'] ?? 0;

if (!$transaction_id) {
    $_SESSION['message'] = "Neplatná transakcia!";
    header("Location: index.php?page=transactions"); // presmerovanie priamo na stránku transakcií
    exit;
}

// Načítať transakciu
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->execute([$transaction_id]);
$tx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tx) {
    $_SESSION['message'] = "Transakcia neexistuje!";
    header("Location: index.php?page=transactions");
    exit;
}

// Ak už je storno, nič nerobiť
if ($tx['cancelled']) {
    $_SESSION['message'] = "Transakcia už bola stornovaná!";
    header("Location: index.php?page=transactions");
    exit;
}

// Označiť transakciu ako storno
$stmt = $pdo->prepare("UPDATE transactions SET cancelled = 1 WHERE id = ?");
$stmt->execute([$transaction_id]);

// Navrátiť produkty do skladu
$stmt = $pdo->prepare("UPDATE records SET quantity = quantity + ?, needs_replication = 1 WHERE id = ?");
$stmt->execute([$tx['quantity'], $tx['product_id']]);

$_SESSION['message'] = "Transakcia bola stornovaná a produkty navrátené do skladu.";
header("Location: index.php?page=transactions"); // presmerovanie priamo na stránku transakcií
exit;
?>
