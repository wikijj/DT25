<?php
require 'db.php'; // pripojenie na DB

// --- Načítanie všetkých transakcií ---
$stmt = $pdo->query("
    SELECT t.id, t.transaction_name, t.product_id, t.quantity, t.total_price, t.created_at, 
           r.title AS product_name, r.description AS product_description
    FROM transactions t
    LEFT JOIN records r ON t.product_id = r.id
    ORDER BY t.created_at DESC
");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div>
    <h2>Prehľad objednávok</h2>

    <?php if(empty($transactions)): ?>
        <p>Žiadne objednávky zatiaľ neexistujú.</p>
    <?php else: ?>
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Názov transakcie</th>
                    <th>Produkt</th>
                    <th>Popis</th>
                    <th>Množstvo</th>
                    <th>Celková suma (€)</th>
                    <th>Dátum a čas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['id']) ?></td>
                        <td><?= htmlspecialchars($t['transaction_name']) ?></td>
                        <td><?= htmlspecialchars($t['product_name'] ?? 'Produkt neexistuje') ?></td>
                        <td><?= htmlspecialchars($t['product_description'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($t['quantity']) ?></td>
                        <td><?= number_format($t['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($t['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
