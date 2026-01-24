<?php
require 'db.php';

$page = $_GET['page'] ?? 'add';

// ak chceme zobrazovať hlášky
session_start();

/* =========================
   LOAD PRODUCT LIST
   ========================= */
if ($page === 'list') {
    $stmt = $pdo->query("
        SELECT *
        FROM products
        WHERE deleted_at IS NULL
        ORDER BY id DESC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'templates/header.php';

switch ($page) {
    case 'add':
        include 'templates/add.php';
        break;

    case 'list':
        include 'templates/list.php';
        break;

    case 'replicate':
        include 'replicate.php';
        break;

    default:
        include 'templates/add.php';
}

include 'templates/footer.php';
