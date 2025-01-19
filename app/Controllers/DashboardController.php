<?php
namespace app\Controllers;

use app\Models\FinancialRecord;
use app\Models\Category;

class DashboardController {
    public function index() {
        include_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function overview() {

        session_start();
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        $financialRecords = FinancialRecord::getAll();


        $categories = Category::getAll($user_id);

        include_once __DIR__ . '/../views/dashboard/overview.php';
    }

    public function annualStatistics() {

        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];


        $yearlyStatistics = FinancialRecord::getYearlyStatistics($user_id);

        include_once __DIR__ . '/../views/dashboard/annual_statistics.php';
    }

    public function records() {

        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../profile/profile.php");
            exit;
        }


        $financialRecords = FinancialRecord::getAll();

        include_once __DIR__ . '/../views/dashboard/records.php';
    }

    public function categories() {

        session_start();
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];


        $categories = Category::getAll($user_id);

        include_once __DIR__ . '/../views/dashboard/categories.php';
    }

    public function addCategory() {

        session_start();
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $categoryName = $_POST["category_name"];
            $categoryType = $_POST["category_type"];
            $categoryColor = $_POST["category_color"];


            Category::add($categoryName, $categoryType, $categoryColor, $user_id);

            header("Location: categories.php");
            exit;
        }
    }

    public function deleteCategory() {

        session_start();
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../profile/profile.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        if (isset($_GET["delete_category"])) {
            $categoryId = $_GET["delete_category"];

            Category::delete($categoryId);

            header("Location: categories.php");
            exit;
        }
    }
}

