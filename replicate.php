<?php
require 'db.php'; // pripojenie na DB

// Zoznam všetkých uzlov v sieti (okrem tohto uzla)
$other_nodes = [
    ['id'=>1,'url'=>'http://node1.local/DT25/replicate.php?receive=1'],
//    ['id'=>2,'url'=>'http://node2.local/DT25/replicate.php?receive=1'],
    ['id'=>3,'url'=>'http://node3.local/DT25/replicate.php?receive=1'],
];

// --- PRIJÍMANIE DAT Z INÉHO UZLA ---
if (isset($_GET['receive'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data)) {
        if (isset($data['product_code'])) {
            // === Produkt ===
            $stmt = $pdo->prepare(
                "INSERT INTO records (id,node_id,title,description,quantity,price,size,color,product_code,created_at)
                 VALUES (?,?,?,?,?,?,?,?,?,?)
                 ON DUPLICATE KEY UPDATE
                   title=VALUES(title),
                   description=VALUES(description),
                   quantity=VALUES(quantity),
                   price=VALUES(price),
                   size=VALUES(size),
                   color=VALUES(color),
                   product_code=VALUES(product_code)"
            );
            $stmt->execute([
                $data['id'],
                $data['node_id'],
                $data['title'],
                $data['description'],
                $data['quantity'],
                $data['price'],
                $data['size'],
                $data['color'],
                $data['product_code'],
                $data['created_at']
            ]);

            $stmt = $pdo->prepare("INSERT IGNORE INTO record_replication (record_id, node_id) VALUES (?, ?)");
            $stmt->execute([$data['id'], $node_id]);

        } elseif (isset($data['transaction_name'])) {
            // === Transakcia ===
            $stmt = $pdo->prepare(
                "INSERT INTO transactions (id, transaction_name, product_id, quantity, total_price, created_at, node_id)
                 VALUES (?,?,?,?,?,?,?)
                 ON DUPLICATE KEY UPDATE
                   product_id=VALUES(product_id),
                   quantity=VALUES(quantity),
                   total_price=VALUES(total_price)"
            );
            $stmt->execute([
                $data['id'],
                $data['transaction_name'],
                $data['product_id'],
                $data['quantity'],
                $data['total_price'],
                $data['created_at'],
                $data['node_id']
            ]);

            $stmt = $pdo->prepare("INSERT IGNORE INTO transaction_replication (transaction_id, node_id) VALUES (?, ?)");
            $stmt->execute([$data['id'], $node_id]);
        }
    }
    exit('OK');
}

// --- FUNKCIE PRE REPLIKÁCIU ---
function replicateToNode($node, $data) {
    $ch = curl_init($node['url']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response === 'OK';
}

function replicatePendingTransactions($pdo, $other_nodes, $node_id) {
    $stmt = $pdo->query("SELECT * FROM transactions WHERE needs_replication=1 ORDER BY id ASC");
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($transactions as $tx) {
        foreach ($other_nodes as $node) {
            if ($node['id'] == $node_id) continue;
            if (replicateToNode($node, $tx)) {
                $stmtUpdate = $pdo->prepare("UPDATE transactions SET needs_replication=0 WHERE id=?");
                $stmtUpdate->execute([$tx['id']]);
            }
        }
    }
}

function replicatePendingProducts($pdo, $other_nodes, $node_id) {
    $stmt = $pdo->query("SELECT * FROM records WHERE needs_replication=1 ORDER BY created_at ASC");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($records as $record) {
        foreach ($other_nodes as $node) {
            if ($node['id'] == $node_id) continue;
            $stmtCheck = $pdo->prepare("SELECT 1 FROM record_replication WHERE record_id=? AND node_id=?");
            $stmtCheck->execute([$record['id'], $node['id']]);
            if ($stmtCheck->fetch()) continue;

            if (replicateToNode($node, $record)) {
                $stmtInsert = $pdo->prepare("INSERT IGNORE INTO record_replication (record_id, node_id) VALUES (?,?)");
                $stmtInsert->execute([$record['id'], $node['id']]);
            }
        }
        // Označiť ako replikované
        $stmt = $pdo->prepare("UPDATE records SET needs_replication=0, posledna_synchronizacia=NOW() WHERE id=?");
        $stmt->execute([$record['id']]);
    }
}

// --- MANUÁLNA REPLIKÁCIA CEZ TLAČIDLO ---
$message = '';
if (isset($_POST['replicate'])) {
    replicatePendingProducts($pdo, $other_nodes, $node_id);
    replicatePendingTransactions($pdo, $other_nodes, $node_id);
    $message = "Replikácia záznamov dokončená.";
}
?>

<div class="glass-card">
    <h2>Replikácia záznamov</h2>
    <p>Spustite replikáciu ne-replikovaných produktov a transakcií do ostatných uzlov.</p>
    <div class="center-btn">
        <form method="post">
            <button type="submit" name="replicate">Spustiť replikáciu</button>
        </form>
    </div>
    <?php if(!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</div>
