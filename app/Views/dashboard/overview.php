<?php
require_once __DIR__ . '/../../Models/FinancialRecord.php';
require_once __DIR__ . '/../../Controllers/RecordController.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\FinancialRecord;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

$months = FinancialRecord::getDistinctMonths($conn);

$chart_data_json = null;
$yearChartDataJson = null;

if (isset($_GET['month']) && isset($_GET['year'])) {
    $selected_month = $_GET['month'];
    $selected_year = $_GET['year'];

    $total_expenses = FinancialRecord::getTotalByTypeAndMonth($conn, 'expense', $selected_month, $selected_year);
    $total_income = FinancialRecord::getTotalByTypeAndMonth($conn, 'income', $selected_month, $selected_year);

    $chart_data = [
        'labels' => ['Income', 'Expenses'],
        'datasets' => [
            [
                'label' => 'Overview',
                'data' => [$total_income, $total_expenses],
                'backgroundColor' => ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                'borderColor' => ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                'borderWidth' => 1
            ]
        ]
    ];
    $chart_data_json = json_encode($chart_data);
}

if (isset($_GET['year2'])) {
    $selected_year2 = $_GET['year2'];
    $yearChartData = FinancialRecord::getYearlyStatistics($conn, $selected_year2);
    $yearChartLabels = [];
    $yearChartIncome = [];
    $yearChartExpenses = [];
    foreach ($yearChartData as $data) {
        $yearChartLabels[] = $data['Month']; // Отримання місяців
        $yearChartIncome[] = $data['income'];
        $yearChartExpenses[] = $data['expenses'];
    }
    $yearChart = [
        'labels' => $yearChartLabels,
        'datasets' => [
            [
                'label' => 'Income',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'data' => $yearChartIncome
            ],
            [
                'label' => 'Expenses',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
                'data' => $yearChartExpenses
            ]
        ]
    ];
    $yearChartDataJson = json_encode($yearChart);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="../../../public/css/overview.css?v=<?php echo time(); ?>">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../../public/js/overview.js" defer></script>
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <h2>Просмотреть статистику</h2>
    <form action="overview.php" method="get">
        <select name="month" id="month">
            <option value="January">January</option>
            <option value="February">February</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>
        </select>
        <input type="text" name="year" id="year" placeholder="Введите год" required>
        <button type="submit">Показать</button>
    </form>
    <form action="overview.php" method="get">
        <input type="text" name="year2" id="year2" placeholder="Введите год для статистики" required>
        <button type="submit">Показать статистику</button>
    </form>
    <?php if (isset($chart_data_json)): ?>
        <h2>Выбраный месяц: <?php echo $selected_month . ' ' . $selected_year; ?></h2>
        <div class="chart-container">
            <canvas id="myChart"></canvas>
            <input type="hidden" id="chartDataJson" value='<?php echo $chart_data_json; ?>'>
        </div>
    <?php endif; ?>
    <?php if (isset($yearChartDataJson)): ?>
        <h2>Статистика для выбранного года: <?php echo $selected_year2; ?></h2>
        <div class="chart-container">
            <canvas id="yearChart"></canvas>
            <input type="hidden" id="yearChartDataJson" value='<?php echo $yearChartDataJson; ?>'>
        </div>
    <?php endif; ?>

</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
