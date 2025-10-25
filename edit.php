<?php
session_start(); // spusti session
require 'db.php';

// Získanie ID z URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ak ID chýba, presmeruj naspäť
if ($id <= 0) {
    header('Location: index.php?page=list');
    exit;
}

// --- Načítanie záznamu z DB ---
$stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Ak záznam neexistuje
if (!$product) {
    die("Záznam s ID $id neexistuje.");
}

// --- Uloženie zmien po odoslaní formulára ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $product_code = $_POST['product_code'];

    $stmt = $pdo->prepare("UPDATE records SET 
        title = ?, 
        description = ?, 
        quantity = ?, 
        price = ?, 
        size = ?, 
        color = ?, 
        product_code = ?
        WHERE id = ?");
    $stmt->execute([$title, $description, $quantity, $price, $size, $color, $product_code, $id]);

    // Nastavenie hlášky do session
    $_SESSION['message'] = "Produkt bol úspešne upravený!";

    header('Location: index.php?page=list');
    exit;
}
?>

<?php include 'templates/header.php'; ?>

<div class="container glass-card">
    <h2>Upraviť produkt</h2>

    <form method="POST" class="filter-form">
        <label for="title">Názov produktu</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($product['title']) ?>" required>

        <label for="description">Popis</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label for="quantity">Počet</label>
        <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>

        <label for="price">Cena (€)</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="size">Veľkosť</label>
        <input type="text" name="size" id="size" value="<?= htmlspecialchars($product['size']) ?>">

        <label for="color">Farba</label>
        <input type="text" name="color" id="color" value="<?= htmlspecialchars($product['color']) ?>">

        <label for="product_code">Kód produktu</label>
        <input type="text" name="product_code" id="product_code" value="<?= htmlspecialchars($product['product_code']) ?>">

        <div class="center-btn">
            <button type="submit">💾 Uložiť zmeny</button>
            <a href="index.php?page=list" style="margin-left:10px; color:white; text-decoration:none;">↩️ Späť</a>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
