<?php

namespace app\Controllers;

use app\Models\Goal;

class GoalController
{
    public static function handleRequest($conn, $user_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_goal'])) {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $currency = $_POST['currency'];
                $photo = self::uploadPhoto();
                Goal::addGoal($conn, $user_id, $name, $description, $price, $currency, $photo);
            } elseif (isset($_POST['delete_goal'])) {
                $id = $_POST['goal_id'];
                Goal::deleteGoal($conn, $id, $user_id);
            } elseif (isset($_POST['update_status'])) {
                $id = $_POST['goal_id'];
                $status = 'достигнуто';
                Goal::updateGoalStatus($conn, $id, $user_id, $status);
            }
        }
    }

    private static function uploadPhoto()
    {
        $target_dir = "../../../public/goals/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        return basename($_FILES["photo"]["name"]);
    }
}
?>
