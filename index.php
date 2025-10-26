<?php
include 'db.php';

$message = "";
$page = $_GET['page'] ?? 'add';

// Add product
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $product_code = $_POST['product_code'];

    $stmt = $pdo->prepare("
        INSERT INTO records (node_id, title, description, quantity, price, size, color, product_code) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$node_id, $title, $description, $quantity, $price, $size, $color, $product_code]);

    $message = "Product successfully added!";
}

// Replication
if (isset($_POST['replicate'])) {
    // here can be added logic for replication to other nodes
    $message = "Replication started (demo).";
}

// Load data for list view
if ($page === 'list') {
    $stmt = $pdo->query("SELECT * FROM records ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'templates/header.php';

switch($page) {
    case 'add':
        include 'templates/add.php';
        break;
    case 'list':
        include 'templates/list.php';
        break;
    case 'replicate':
        include 'replicate.php';
        break;
    case 'order':
        include 'order.php';
        break;
    case 'transactions':
        include 'transactions.php';
        break;
    default:
        include 'templates/add.php';
}

include 'templates/footer.php';
?>
