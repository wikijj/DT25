<h2>Product List</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Node</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price (€)</th>
            <th>Size</th>
            <th>Color</th>
            <th>Product Code</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['node_id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['size']); ?></td>
                <td><?php echo htmlspecialchars($row['color']); ?></td>
                <td><?php echo htmlspecialchars($row['product_code']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
