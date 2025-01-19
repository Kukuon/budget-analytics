<?php
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../Controllers/CategoryController.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Models\Category;
use app\Controllers\CategoryController;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../profile/profile.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST["category_name"];
    $categoryType = $_POST["category_type"];
    $categoryColor = $_POST["category_color"];
    $user_id = $_SESSION['user_id'];

    Category::add($conn, $categoryName, $categoryType, $categoryColor, $user_id);
}

if (isset($_GET["delete_category"])) {
    $categoryId = $_GET["delete_category"];
    Category::delete($conn, $categoryId);
}

$user_id = $_SESSION['user_id'];
$categories = Category::getAll($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления - Категории</title>
    <link rel="stylesheet" href="../../../public/css/categories.css?v=<?php echo time(); ?>">

</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="search-container">
        <label for="search-filter">Поиск категорий:</label>
        <input type="text" id="searchInput" onkeyup="search()" placeholder="Поиск по названию...">
        <button onclick="search()">Поиск</button>
    </div>
    <table id="categories-table">
        <thead>
        <tr>
            <th onclick="sortTable(0)">Название категории<span class="filter-icon" onclick="toggleFilter(0)">🔍</span></th>
            <th onclick="sortTable(1)">Тип категории<span class="filter-icon" onclick="toggleFilter(1)">🔍</span></th>
            <th>Цвет</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody id="category-list">
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo $category['type']; ?></td>
                <td><span class="category-color-box" style="background-color: <?php echo $category['color']; ?>"></span></td>
                <td><a href="?delete_category=<?php echo $category['id']; ?>">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form class="add-category-form" method="post">
        <label for="category-name">Название новой категории:</label>
        <input type="text" id="category-name" name="category_name" required>
        <label for="category-type">Тип новой категории:</label>
        <select id="category-type" name="category_type" required>
            <option value="income">Доход</option>
            <option value="expense">Расход</option>
        </select>
        <label for="category-color">Цвет новой категории:</label>
        <input type="color" id="category-color" name="category_color" required>
        <button type="submit">Добавить новую категорию</button>
    </form>
</main>

<?php include('../partials/footer.php'); ?>

<script src="../../../public/js/categories.js" defer></script>
<script>
    function search() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("categories-table");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body>
</html>
