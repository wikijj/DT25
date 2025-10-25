
    <h2>Zoznam produktov</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Node</th>
                <th>Produkt</th>
                <th>Popis</th>
                <th>Počet</th>
                <th>Cena (€)</th>
                <th>Veľkosť</th>
                <th>Farba</th>
                <th>Kód</th>
                <th>Vytvorené</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['node_id']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['size']) ?></td>
                        <td><?= htmlspecialchars($row['color']) ?></td>
                        <td><?= htmlspecialchars($row['product_code']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <!-- EDIT -->
                            <a href="edit.php?id=<?= $row['id'] ?>">✏️</a>
                            <!-- DELETE -->
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Naozaj zmazať tento záznam?');">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align:center; opacity:0.8;">Žiadne produkty</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

