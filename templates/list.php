<?php
require 'db.php';

// ----- Načítanie produktov -----
$stmt = $pdo->query("
    SELECT *
    FROM products
    WHERE deleted_at IS NULL
    ORDER BY created_at DESC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Zoznam produktov</h2>

<?php if (!empty($_SESSION['message'])): ?>
    <p style="color: green;">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead style="background:#eee;">
        <tr>
            <th>ID</th>
            <th>Uzol</th>
            <th>Produkt</th>
            <th>Popis</th>
            <th>Počet</th>
            <th>Cena (€)</th>
            <th>Veľkosť</th>
            <th>Farba</th>
            <th>Kód</th>
            <th>Akcie</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['node_origin']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['size']) ?></td>
                    <td><?= htmlspecialchars($row['color']) ?></td>
                    <td><?= htmlspecialchars($row['product_code']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">✏️</a>

                        <?php if ((int)$row['node_origin'] === (int)$node_id): ?>
                            <a href="delete.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Naozaj zmazať tento produkt?');">
                                🗑️
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" style="text-align:center; opacity:0.7;">
                    Žiadne produkty
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
