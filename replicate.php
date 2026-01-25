<?php
require 'db.php';

/*
|--------------------------------------------------------------------------
| KONFIGURÁCIA OSTATNÝCH UZLOV
|--------------------------------------------------------------------------
| Uprav URL podľa reálnej IP / domény jednotlivých uzlov
*/
$nodes = [
//    1 => 'http://25.26.138.185/mojprojekt/replicate.php?receive=1',
      2 => 'http://25.56.188.24/mojprojekt/replicate.php?receive=1',
      3 => 'http://25.5.25.66/mojprojekt/replicate.php?receive=1',
];

/*
|--------------------------------------------------------------------------
| PRIJÍMANIE SQL OPERÁCIE Z INÉHO UZLA
|--------------------------------------------------------------------------
*/
if (isset($_GET['receive'])) {

    $sql = file_get_contents('php://input');

    if (empty($sql)) {
        http_response_code(400);
        exit('EMPTY_SQL');
    }

    try {
        $pdo->exec($sql);
        exit('OK');
    } catch (PDOException $e) {
        http_response_code(500);
        exit('ERROR');
    }
}

/*
|--------------------------------------------------------------------------
| ODOSIELANIE SQL OPERÁCIÍ Z FRONTY
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT *
    FROM replication_queue
    ORDER BY created_at ASC
");
$stmt->execute();

$queue = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sent = 0;
$failed = 0;

foreach ($queue as $item) {

    $targetNode = (int)$item['target_node'];

    // ak cieľový uzol neexistuje, preskoč
    if (!isset($nodes[$targetNode])) {
        continue;
    }

    $url = $nodes[$targetNode];

    // cURL – pošleme SQL príkaz
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $item['sql_query']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $error    = curl_error($ch);

    curl_close($ch);

    if ($error || trim($response) !== 'OK') {
        $failed++;
        continue;
    }

    // úspech → zmažeme z fronty
    $stmtDel = $pdo->prepare("
        DELETE FROM replication_queue
        WHERE id = ?
    ");
    $stmtDel->execute([$item['id']]);

    $sent++;
}

?>

<div class="glass-card">
    <h2>Replikácia databázy</h2>
    <p>
        Replikujú sa SQL operácie uložené vo fronte (<b>replication_queue</b>).
        Replikácia je manuálna.
    </p>

    <p>
        <b>Odoslané:</b> <?= $sent ?><br>
        <b>Zlyhané:</b> <?= $failed ?>
    </p>

    <a href="index.php?page=list" class="btn">⬅️ Späť na zoznam</a>
</div>
