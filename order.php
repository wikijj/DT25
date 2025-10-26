<?php
require 'db.php';

// Načítať všetky produkty so skladom > 0
$stmt = $pdo->query("SELECT * FROM records WHERE quantity > 0 ORDER BY title ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vytvoríme asociatívne pole pre JS
$productMap = [];
foreach ($products as $p) {
    $productMap[$p['title']][] = $p;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = (int)($_POST['quantity'] ?? 0);

    if (!$product_id || $quantity <= 0) {
        $_SESSION['message'] = "Vyberte produkt a zadajte platné množstvo.";
        header("Location: ?page=order");
        exit;
    }

    // Skontrolovať dostupnosť
    $stmt = $pdo->prepare("SELECT title, description, price, quantity FROM records WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['message'] = "Produkt neexistuje.";
        header("Location: ?page=order");
        exit;
    }

    if ($product['quantity'] < $quantity) {
        $_SESSION['message'] = "Na sklade nie je dostatok produktov.";
        header("Location: ?page=order");
        exit;
    }

    $total_price = $product['price'] * $quantity;
    $transaction_name = "Uzol{$node_id}_" . date("Y-m-d_H-i-s");

    $stmt = $pdo->prepare("INSERT INTO transactions (transaction_name, product_id, quantity, total_price, node_id)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$transaction_name, $product_id, $quantity, $total_price, $node_id]);

    $stmt = $pdo->prepare("UPDATE records SET quantity = quantity - ? WHERE id = ?");
    $stmt->execute([$quantity, $product_id]);

    $_SESSION['message'] = "Objednávka bola úspešne spracovaná!";
    header("Location: ?page=list");
    exit;
}
?>

<div>
    <h2>Nová objednávka</h2>
    <p>Vytvorte objednávku výberom typu a popisu produktu.</p>

    <form method="post" class="order-form">
        <label for="product_type">Typ produktu:</label>
        <select id="product_type" required onchange="updateDescription()">
            <option value="">-- Vyberte typ --</option>
            <?php foreach ($productMap as $type => $arr): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="product_id">Popis produktu:</label>
        <select name="product_id" id="product_id" required onchange="updatePrice()">
            <option value="">-- Najprv vyberte typ --</option>
        </select>

        <label for="quantity">Množstvo:</label>
        <input type="number" name="quantity" id="quantity" min="1" required oninput="updatePrice()">

        <p id="price-info">Cena: <span id="total">0.00</span> €</p>

        <div class="center-btn">
            <button type="submit">Odoslať objednávku</button>
        </div>
    </form>
</div>

<script>
const products = <?= json_encode($products) ?>;

function updateDescription() {
    const typeSelect = document.getElementById('product_type');
    const descSelect = document.getElementById('product_id');
    const selectedType = typeSelect.value;

    descSelect.innerHTML = '<option value="">-- Vyberte popis --</option>';

    const filtered = products.filter(p => p.title === selectedType);
    filtered.forEach(p => {
        const option = document.createElement('option');
        option.value = p.id;
        option.textContent = `${p.description} (${p.quantity} ks na sklade)`;
        option.setAttribute('data-price', p.price);
        option.setAttribute('data-quantity', p.quantity);
        descSelect.appendChild(option);
    });

    updatePrice();
}

function updatePrice() {
    const descSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const totalDisplay = document.getElementById('total');

    const selected = descSelect.options[descSelect.selectedIndex];
    const price = parseFloat(selected?.getAttribute('data-price')) || 0;
    const available = parseInt(selected?.getAttribute('data-quantity')) || 0;
    let quantity = parseInt(quantityInput.value) || 0;

    if (quantity > available) {
        alert(`Na sklade je len ${available} ks tohto produktu.`);
        quantityInput.value = available;
        quantity = available;
    }

    const total = (price * quantity).toFixed(2);
    totalDisplay.textContent = total;
}
</script>
