<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {

    $title        = $_POST['title'];
    $description  = $_POST['description'];
    $quantity     = (int)$_POST['quantity'];
    $price        = (float)$_POST['price'];
    $size         = $_POST['size'];
    $color        = $_POST['color'];
    $product_code = $_POST['product_code'];

    try {
        // 1️⃣ Lokálny INSERT
        $stmt = $pdo->prepare("
            INSERT INTO products
            (title, description, quantity, price, size, color, product_code, node_origin)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $title,
            $description,
            $quantity,
            $price,
            $size,
            $color,
            $product_code,
            $node_id
        ]);

        // 2️⃣ SQL pre replikáciu
        $sql = "
            INSERT INTO products
            (title, description, quantity, price, size, color, product_code, node_origin)
            VALUES (
                ".$pdo->quote($title).",
                ".$pdo->quote($description).",
                $quantity,
                $price,
                ".$pdo->quote($size).",
                ".$pdo->quote($color).",
                ".$pdo->quote($product_code).",
                $node_id
            )
        ";

        $nodes = [1, 2, 3];
        foreach ($nodes as $target_node) {
            if ($target_node != $node_id) {
                $stmt = $pdo->prepare("
                    INSERT INTO replication_queue
                    (target_node, operation, sql_query)
                    VALUES (?, 'INSERT', ?)
                ");
                $stmt->execute([$target_node, $sql]);
            }
        }

        $_SESSION['message'] = "Produkt bol úspešne pridaný a zaradený do replikácie.";
        header("Location: ?page=list");
        exit;

    } catch (PDOException $e) {

        // 🔴 DUPLICITA (UNIQUE constraint)
        if ($e->getCode() === '23000') {
            $_SESSION['message'] = "❗ Produkt s týmto kódom už bol na tomto uzle pridaný.";
            header("Location: ?page=add");
            exit;
        }

        // 🔴 Iná DB chyba
        $_SESSION['message'] = "❌ Nastala chyba pri ukladaní produktu.";
        header("Location: ?page=add");
        exit;
    }
}
?>


<h2>Pridať nový produkt</h2>

<form method="post">
    <label>Názov produktu</label>
    <input type="text" name="title" required>

    <label>Popis</label>
    <textarea name="description"></textarea>

    <label>Počet kusov</label>
    <input type="number" name="quantity" min="0" required>

    <label>Cena (€)</label>
    <input type="number" name="price" step="0.01" min="0" required>

    <label>Veľkosť</label>
    <input type="text" name="size">

    <label>Farba</label>
    <input type="text" name="color">

    <label>Kód produktu</label>
    <input type="text" name="product_code">

    <button type="submit" name="add">Pridať produkt</button>
</form>
