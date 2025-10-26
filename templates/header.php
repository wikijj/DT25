<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = '';
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // odstráni hlášku po zobrazení
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Distribuovaný IS - Uzol <?php echo $node_id; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
   
        <h1>Distribuovaný IS - Uzol <?php echo $node_id; ?></h1>
    
</header>
<nav>
    <div class="container">
        <a href="?page=add">Pridať produkt</a>
        <a href="?page=list">Zobraziť databázu</a>
        <a href="?page=order">Vytvoriť objednávku</a>
        <a href="?page=transactions">Zobraziť prehľad objednávok</a>
        <a href="?page=replicate">Synchronizovať záznamy</a>
    </div>
</nav>
<div class="container">
<?php if(!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
