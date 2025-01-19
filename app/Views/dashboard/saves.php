<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$saves_total = FinancialRecord::getTotalByCategory($conn, $user_id, 'saves');
$expenses_total = FinancialRecord::getTotalByType($conn, $user_id, 'expense');
$income_total = FinancialRecord::getTotalByType($conn, $user_id, 'income');
$wallet_balance = FinancialRecord::getWalletBalance($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Financial Planner - Saves</title>
    <link rel="stylesheet" href="../../../public/css/savess.css?v=<?php echo time(); ?>">

</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="container-row">
        <div class="text-container">
            <h2>Кошелек</h2>
            <p>Общая сумма на кошельке: <?php echo $wallet_balance; ?></p>
        </div>
        <div class="text-container">
            <h2>Сохранение</h2>
            <p>Общая сумма сохранений:<?php echo $saves_total; ?></p>
        </div>
    </div>
    <div class="container-row">
        <div class="text-container">
            <h2>Поступление</h2>
            <p>Общая сумма поступлений: <?php echo $income_total; ?></p>
        </div>
        <div class="text-container">
            <h2>Затраты</h2>
            <p>Общая сумма расходов: <?php echo $expenses_total; ?></p>
        </div>
    </div>


</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
