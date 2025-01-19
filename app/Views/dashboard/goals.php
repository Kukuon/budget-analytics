<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../Models/Goal.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Controllers/GoalController.php';

use app\Models\FinancialRecord;
use app\Models\Goal;
use app\Controllers\GoalController;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$user_id = $_SESSION['user_id'];
GoalController::handleRequest($conn, $user_id);

$saves_total = FinancialRecord::getTotalByCategory($conn, $user_id, 'saves');
$expenses_total = FinancialRecord::getTotalByType($conn, $user_id, 'expense');
$income_total = FinancialRecord::getTotalByType($conn, $user_id, 'income');
$wallet_balance = FinancialRecord::getWalletBalance($conn, $user_id);
$goals = Goal::getAllGoals($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Analytics</title>
    <link rel="stylesheet" href="../../../public/css/goalss.css?v=<?php echo time(); ?>">

</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="container-row">
        <div class="text-container">
            <h2>Кошелек</h2>
            <p>Общая сумма: <?php echo $wallet_balance; ?></p>
        </div>
        <div class="text-container">
            <h2>Сбережения</h2>
            <p>Общая сумма сбережений: <?php echo $saves_total; ?></p>
        </div>
    </div>

    <div class="goals-container">
        <h2>Цели</h2>
        <?php foreach ($goals as $goal): ?>
            <?php if ($goal['status'] !== 'досягнуто'): ?>
                <div class="goal">
                    <img src="../../../public/goals/<?php echo $goal['photo']; ?>" alt="Goal Image">
                    <h3><?php echo $goal['name']; ?></h3>
                    <p><?php echo $goal['description']; ?></p>
                    <p>Цена: <?php echo $goal['price'] . ' ' . $goal['currency']; ?></p>
                    <p>Статус: <?php echo $goal['status']; ?></p>
                    <?php
                    $difference = $wallet_balance - $goal['price'];
                    if ($difference >= 0) {
                        echo "<p>Можно купить</p>";
                    } else {
                        echo "<p>Не можете купить</p>";
                    }
                    ?>
                    <form method="post" action="goals.php">
                        <input type="hidden" name="goal_id" value="<?php echo $goal['id']; ?>">
                        <button type="submit" name="delete_goal">Удалить</button>
                        <button type="submit" name="update_status">Отметить как достигнуто</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h2>Достигнутые цели</h2>
    <div class="achieved-goals">
        <?php foreach ($goals as $goal): ?>
            <?php if ($goal['status'] === 'досягнуто'): ?>
                <div class="goal">
                    <img src="../../../public/goals/<?php echo $goal['photo']; ?>" alt="Goal Image">
                    <h3><?php echo $goal['name']; ?></h3>
                    <p><?php echo $goal['description']; ?></p>
                    <p>Цена: <?php echo $goal['price'] . ' ' . $goal['currency']; ?></p>
                    <p>Статус: <?php echo $goal['status']; ?></p>
                    <form method="post" action="goals.php">
                        <input type="hidden" name="goal_id" value="<?php echo $goal['id']; ?>">
                        <button type="submit" name="delete_goal">Удалить</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h2>Добавить новую цель</h2>
    <form method="post" action="goals.php" enctype="multipart/form-data">
        <label for="name">Название цели:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" required>
        <label for="currency">Валюта:</label>
        <input type="text" id="currency" name="currency" required>
        <label for="photo">Фото:</label>
        <input type="file" id="photo" name="photo" required>
        <button type="submit" name="add_goal">Добавить цель: </button>
    </form>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
