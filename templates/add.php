<?php
require 'db.php';

// --- SPRACOVANIE PRIDANIA PRODUKTU ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $product_code = $_POST['product_code'];

    // Vloženie do DB s označením needs_replication = 1
    $stmt = $pdo->prepare(
        "INSERT INTO records 
            (node_id, title, description, quantity, price, size, color, product_code, needs_replication) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)"
    );
    $stmt->execute([$node_id, $title, $description, $quantity, $price, $size, $color, $product_code]);

    $_SESSION['message'] = "Produkt bol úspešne pridaný a bude replikovaný do ostatných uzlov.";
    header("Location: ?page=list");
    exit;
}
?>

<div>
    <h2>Pridať nový produkt</h2>

    <form method="post" class="filter-form">
        <div>
            <label for="title">Názov produktu</label>
            <input id="title" type="text" name="title" placeholder="Názov produktu" required>
        </div>

        <div>
            <label for="description">Popis</label>
            <textarea id="description" name="description" placeholder="Popis"></textarea>
        </div>

        <div>
            <label for="quantity">Počet kusov</label>
            <input id="quantity" type="number" name="quantity" placeholder="Počet ks" min="0" required>
        </div>

        <div>
            <label for="price">Cena (€)</label>
            <input id="price" type="number" name="price" placeholder="Cena" step="0.01" min="0" required>
        </div>

        <div>
            <label for="size">Veľkosť</label>
            <input id="size" type="text" name="size" placeholder="Veľkosť (napr. S, M, L, XL)">
        </div>

        <div>
            <label for="color">Farba</label>
            <input id="color" type="text" name="color" placeholder="Farba">
        </div>

        <div>
            <label for="product_code">Kód produktu</label>
            <input id="product_code" type="text" name="product_code" placeholder="Kód produktu">
        </div>

        <div class="center-btn">
            <button type="submit" name="add">Pridať produkt</button>
        </div>
    </form>
</div>

