<?php
declare(strict_types=1);

/**
 * ==========================================
 * Лабораторная работа №4
 * Массивы и функции + Файловая система
 * ==========================================
 */

/**
 * Массив транзакций
 */
$transactions = [
    [
        "id" => 1,
        "date" => "2024-01-10",
        "amount" => 150.50,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2024-02-15",
        "amount" => 80.00,
        "description" => "Internet bill",
        "merchant" => "Moldtelecom",
    ],
    [
        "id" => 3,
        "date" => "2024-03-01",
        "amount" => 200.75,
        "description" => "New headphones",
        "merchant" => "TechStore",
    ],
];

/**
 * Вычисляет общую сумму транзакций
 */
function calculateTotalAmount(array $transactions): float
{
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}

/**
 * Поиск по части описания
 */
function findTransactionByDescription(string $descriptionPart): array
{
    global $transactions;

    return array_filter(
        $transactions,
        fn($t) => stripos($t['description'], $descriptionPart) !== false
    );
}

/**
 * Поиск по ID через foreach
 */
function findTransactionById(int $id): ?array
{
    global $transactions;

    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}

/**
 * Поиск по ID через array_filter (на высшую оценку)
 */
function findTransactionByIdFilter(int $id): ?array
{
    global $transactions;

    $result = array_filter(
        $transactions,
        fn($t) => $t['id'] === $id
    );

    return $result ? array_values($result)[0] : null;
}

/**
 * Количество дней с момента транзакции
 */
function daysSinceTransaction(string $date): int
{
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime();
    return $transactionDate->diff($currentDate)->days;
}

/**
 * Добавление новой транзакции
 */
function addTransaction(
    int $id,
    string $date,
    float $amount,
    string $description,
    string $merchant
): void
{
    global $transactions;

    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

/* Добавляем новую транзакцию */
addTransaction(4, "2024-03-20", 99.99, "Cinema tickets", "CinemaCity");

/* Сортировка по дате */
usort($transactions, function ($a, $b) {
    return strtotime($a['date']) <=> strtotime($b['date']);
});

/* Сортировка по сумме (по убыванию) */
usort($transactions, function ($a, $b) {
    return $b['amount'] <=> $a['amount'];
});

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lab 4</title>

<style>
body { font-family: Arial; margin: 20px; background:#f2f2f2; }
table { border-collapse: collapse; width: 100%; background:white; }
th, td { border:1px solid #ccc; padding:8px; text-align:center; }
th { background:#eee; }
.gallery { display:grid; grid-template-columns:repeat(3,1fr); gap:15px; margin-top:30px; }
.gallery img { width:100%; height:200px; object-fit:cover; border-radius:8px; }
footer { text-align:center; margin-top:40px; }
</style>

</head>
<body>

<h2>Bank Transactions</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Amount</th>
    <th>Description</th>
    <th>Merchant</th>
    <th>Days Passed</th>
</tr>
</thead>

<tbody>
<?php foreach ($transactions as $transaction): ?>
<tr>
    <td><?= $transaction['id'] ?></td>
    <td><?= $transaction['date'] ?></td>
    <td><?= $transaction['amount'] ?></td>
    <td><?= $transaction['description'] ?></td>
    <td><?= $transaction['merchant'] ?></td>
    <td><?= daysSinceTransaction($transaction['date']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<p><strong>Total Amount: <?= calculateTotalAmount($transactions); ?></strong></p>

<hr>

<h2>Image Gallery</h2>

<div class="gallery">
<?php
$dir = 'images/cat1';
$dir = 'images/cat2';
$dir = 'images/cat3';
$dir = 'images/cat4';
$files = scandir($dir);

if ($files !== false) {
    foreach ($files as $file) {
        if ($file !== "." && $file !== "..") {
            echo "<img src='$dir$file' alt='Image'>";
        }
    }
}
?>
</div>

<footer>
USM © 2024
</footer>

</body>
</html>