<?php
require 'db.php';

// ID produktu (lokálne)
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

// Spracovanie formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quantity = (int)$_POST['quantity'];

    if ((int)$product['node_origin'] === (int)$node_id) {

        // autor – môže meniť všetko
        $title        = $_POST['title'];
        $description  = $_POST['description'];
        $price        = (float)$_POST['price'];
        $size         = $_POST['size'];
        $color        = $_POST['color'];
        $product_code = $_POST['product_code'];

        // lokálny UPDATE (cez id – OK)
        $stmt = $pdo->prepare("
            UPDATE products SET
                title = ?, description = ?, quantity = ?, price = ?,
                size = ?, color = ?, product_code = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $title, $description, $quantity, $price,
            $size, $color, $product_code, $id
        ]);

        // SQL pre replikáciu (STABILNÝ IDENTIFIKÁTOR)
        $sql = "
        UPDATE products SET
            title = ".$pdo->quote($title).",
            description = ".$pdo->quote($description).",
            quantity = $quantity,
            price = $price,
            size = ".$pdo->quote($size).",
            color = ".$pdo->quote($color).",
            product_code = ".$pdo->quote($product_code)."
        WHERE node_origin = {$product['node_origin']}
          AND product_code = ".$pdo->quote($product['product_code']);

    } else {

        // cudzí uzol – môže meniť len quantity
        $stmt = $pdo->prepare("
            UPDATE products SET quantity = ?
            WHERE id = ?
        ");
        $stmt->execute([$quantity, $id]);

        $sql = "
        UPDATE products
        SET quantity = $quantity
        WHERE node_origin = {$product['node_origin']}
          AND product_code = ".$pdo->quote($product['product_code']);
    }

    // zápis do replication_queue
    $nodes = [1, 2, 3];
    foreach ($nodes as $target) {
        if ($target != $node_id) {
            $stmt = $pdo->prepare("
                INSERT INTO replication_queue (target_node, operation, sql_query)
                VALUES (?, 'UPDATE', ?)
            ");
            $stmt->execute([$target, $sql]);
        }
    }

    header("Location: index.php?page=list");
    exit;
}

include 'templates/header.php';
?>

<h2>Upraviť produkt</h2>

<form method="post">
    <?php if ((int)$product['node_origin'] === (int)$node_id): ?>

        <label>Názov produktu</label>
        <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>

        <label>Popis</label>
        <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Cena (€)</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label>Veľkosť</label>
        <input type="text" name="size" value="<?= htmlspecialchars($product['size']) ?>">

        <label>Farba</label>
        <input type="text" name="color" value="<?= htmlspecialchars($product['color']) ?>">

        <label>Kód produktu</label>
        <input type="text" name="product_code" value="<?= htmlspecialchars($product['product_code']) ?>">

    <?php endif; ?>

    <label>Počet kusov</label>
    <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>

    <button type="submit">💾 Uložiť zmeny</button>
    <a href="index.php?page=list">Späť</a>
</form>

<?php include 'templates/footer.php'; ?>
