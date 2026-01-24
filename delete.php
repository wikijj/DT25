<?php
require 'db.php';

// lokálne ID
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php?page=list");
    exit;
}

// Načítanie produktu
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php?page=list");
    exit;
}

// len autor môže mazať
if ((int)$product['node_origin'] !== (int)$node_id) {
    header("Location: index.php?page=list");
    exit;
}

// lokálny soft delete (cez id – OK)
$stmt = $pdo->prepare("
    UPDATE products SET deleted_at = NOW()
    WHERE id = ?
");
$stmt->execute([$id]);

// SQL pre replikáciu (STABILNÝ IDENTIFIKÁTOR)
$sql = "
UPDATE products
SET deleted_at = NOW()
WHERE node_origin = {$product['node_origin']}
  AND product_code = ".$pdo->quote($product['product_code']);

// zápis do replication_queue
$nodes = [1, 2, 3];
foreach ($nodes as $target) {
    if ($target != $node_id) {
        $stmt = $pdo->prepare("
            INSERT INTO replication_queue (target_node, operation, sql_query)
            VALUES (?, 'DELETE', ?)
        ");
        $stmt->execute([$target, $sql]);
    }
}

header("Location: index.php?page=list");
exit;
