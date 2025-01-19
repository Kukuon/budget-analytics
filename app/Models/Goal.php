<?php

namespace app\Models;

class Goal
{
    public static function getAllGoals($conn, $user_id)
    {
        $stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function addGoal($conn, $user_id, $name, $description, $price, $currency, $photo)
    {
        $stmt = $conn->prepare("INSERT INTO goals (name, description, price, currency, status, photo, user_id) VALUES (:name, :description, :price, :currency, 'не виконано', :photo, :user_id)");
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, \PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, \PDO::PARAM_STR);
        $stmt->bindParam(':currency', $currency, \PDO::PARAM_STR);
        $stmt->bindParam(':photo', $photo, \PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function deleteGoal($conn, $id, $user_id)
    {
        $stmt = $conn->prepare("DELETE FROM goals WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function updateGoalStatus($conn, $id, $user_id, $status)
    {
        $stmt = $conn->prepare("UPDATE goals SET status = :status WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
