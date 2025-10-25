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
        <a href="?page=add">Pridať POKUS produkt</a>
        <a href="?page=list">Zobraziť databázu</a>
        <a href="?page=replicate">Synchronizovať záznamy</a>
    </div>
</nav>
<div class="container">
<?php if($message): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>
