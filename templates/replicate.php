<?php
require 'db.php'; // pripojenie na DB

// Zoznam všetkých uzlov v sieti (okrem tohto uzla)
$other_nodes = [
    ['id'=>1,'url'=>'http://node1.local/DT25/replicate.php?receive=1'],
    ['id'=>2,'url'=>'http://node2.local/DT25/replicate.php?receive=1'],
    ['id'=>3,'url'=>'http://node3.local/DT25/replicate.php?receive=1'],
];

// --- PRIJÍMANIE DAT Z INÉHO UZLA ---
if (isset($_GET['receive'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data)) {
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

        // Zaznamenať replikáciu
        $stmt = $pdo->prepare("INSERT IGNORE INTO record_replication (record_id, node_id) VALUES (?, ?)");
        $stmt->execute([$data['id'], $node_id]);
    }
    exit('OK');
}

// --- SPUSTENIE REPLIKÁCIE ---
$message = '';

if (isset($_POST['replicate'])) {
    // Načítať záznamy, ktoré ešte neboli replikované do ostatných uzlov
    $stmt = $pdo->query("SELECT * FROM records ORDER BY created_at ASC");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $replicated_count = 0;

    foreach ($records as $record) {
        foreach ($other_nodes as $node) {
            if ($node['id'] == $node_id) continue; // neodosielať sebe

            // Skontrolovať, či už bol záznam replikovaný do tohto uzla
            $stmtCheck = $pdo->prepare("SELECT 1 FROM record_replication WHERE record_id = ? AND node_id = ?");
            $stmtCheck->execute([$record['id'], $node['id']]);
            if ($stmtCheck->fetch()) continue;

            // Odoslať záznam cez POST
            $ch = curl_init($node['url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($record));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            // Ak úspešné, zaznamenať replikáciu
            if ($response === 'OK') {
                $stmtUpdate = $pdo->prepare("UPDATE records SET last_replicated_at = NOW() WHERE id = ?");
                $stmtUpdate->execute([$record['id']]);
                $replicated_count++;
            }
        }
    }

    $message = "Replikácia dokončená! ($replicated_count záznamov)";
}
?>

<div class="glass-card">
    <h2>Replikácia záznamov</h2>
    <p>Spustite replikáciu ne-replikovaných záznamov do ostatných uzlov.</p>
    <div class="center-btn">
        <form method="post">
            <button type="submit" name="replicate">Spustiť replikáciu</button>
        </form>
    </div>
    <?php if(!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</div>

