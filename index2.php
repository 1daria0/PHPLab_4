<?php
declare(strict_types=1);

$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" => "2023-10-26",
        "amount" => 2500.00,
        "description" => "Monthly rent payment",
        "merchant" => "City Housing",
    ],
    [
        "id" => 4,
        "date" => "2023-11-15",
        "amount" => 45.99,
        "description" => "Internet subscription",
        "merchant" => "ISP Company",
    ],
];


function calculateTotalAmount(array $transactions): float {
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}


function findTransactionByDescription(string $descriptionPart, array $transactions): array {
    $found = [];
    foreach ($transactions as $transaction) {
        if (stripos($transaction['description'], $descriptionPart) !== false) {
            $found[] = $transaction;
        }
    }
    return $found;
}


function findTransactionById(int $id, array $transactions): ?array {
    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }
    return null;
}


function findTransactionByIdWithFilter(int $id, array $transactions): ?array {
    $filtered = array_filter($transactions, fn($t) => $t['id'] === $id);
    $filtered = array_values($filtered); // переиндексируем
    return $filtered[0] ?? null;
}


function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $today = new DateTime();
    $interval = $transactionDate->diff($today);
    return (int)$interval->days;
}


function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void {
    global $transactions;
    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

addTransaction(5, '2024-03-01', 123.45, 'New purchase from function', 'Online Store');


usort($transactions, fn($a, $b) => $b['amount'] <=> $a['amount']);

$found = findTransactionById(2, $transactions);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа: транзакции и галерея</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        h1, h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th { background-color: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background-color: #f5f5f5; }
        .total-row { background-color: #e8f5e9; font-weight: bold; }
        .gallery { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .gallery img { width: 150px; height: auto; border: 1px solid #ccc; border-radius: 4px; transition: transform 0.2s; }
        .gallery img:hover { transform: scale(1.05); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .search-result { background-color: #e3f2fd; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>

    <h2>Список банковских транзакций</h2>

    <?php if ($found): ?>
        <div class="search-result">
            <strong>Демонстрация поиска:</strong> найдена транзакция с ID 2: "<?= htmlspecialchars($found['description']) ?>"
        </div>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Получатель</th>
                <th>Дней с момента</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$transaction['id']) ?></td>
                    <td><?= htmlspecialchars($transaction['date']) ?></td>
                    <td>$<?= number_format($transaction['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['merchant']) ?></td>
                    <td><?= daysSinceTransaction($transaction['date']) ?> дн.</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>Итого:</strong></td>
                <td colspan="4"><strong>$<?= number_format(calculateTotalAmount($transactions), 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

  
<h2>Галерея изображений</h2>
 <?php
  
    $dir = 'images/';   

    if (is_dir($dir)) {
      
        $files = glob($dir . '*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE);

        if (empty($files)) {
            echo "<p>JPG-файлы не найдены в папке '$dir'.</p>";
        } else {
            echo "<p>Найдено файлов: " . count($files) . "</p>";
            echo '<div class="gallery">';

            foreach ($files as $file) {
                
                echo '<div style="border: 1px solid #ccc; padding: 5px; text-align: center;">';
                echo '<img src="' . htmlspecialchars($file) . '" alt="' . htmlspecialchars(basename($file)) . '"><br>';
                echo '<small>' . htmlspecialchars($file) . '</small>';
                echo '</div>';
            }

            echo '</div>';
        }
    } else {
        echo "<p>Ошибка: папка '$dir' не найдена.</p>";

        echo "Текущая директория: " . __DIR__ . "<br>";
        echo "realpath('image/'): " . realpath('image/') . "<br>";
        echo "realpath('images/'): " . realpath('images/') . "<br>";
    }
    ?>

</body>
</html>