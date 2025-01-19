<?php
// FinancialRecord.php

namespace app\Models;

use PDO;

class FinancialRecord {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public static function getAll($conn, $user_id)
    {
        $stmt = $conn->prepare("SELECT * FROM financial_records WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFinancialRecords() {
        $sql = "SELECT financial_records.*, categories.name AS category_info
                FROM financial_records 
                JOIN categories ON financial_records.category_id = categories.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add(PDO $pdo, $user_id, $month, $year, $category_id, $description, $attachment, $currency, $amount, $type) {
        try {
            $stmt = $pdo->prepare("INSERT INTO financial_records (user_id, month, year, category_id, description, attachment, currency, amount, type) 
                                VALUES (:user_id, :month, :year, :category_id, :description, :attachment, :currency, :amount, :type)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':attachment', $attachment);
            $stmt->bindParam(':currency', $currency);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function delete(PDO $pdo, $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM financial_records WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getDistinctMonths(PDO $conn) {
        try {
            $stmt = $conn->query("SELECT DISTINCT Month FROM financial_records");

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
            return []; 
        }
    }

    public static function getDistinctMonthsForYear(PDO $conn, $year) {
        try {
            $stmt = $conn->prepare("SELECT DISTINCT Month FROM financial_records WHERE Year = ?");
            $stmt->execute([$year]);

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
            return [];
        }
    }

    public static function getTotalByTypeAndMonth(PDO $conn, $type, $month, $year) {
        try {
            $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM financial_records WHERE type = ? AND Month = ? AND Year = ?");
            $stmt->execute([$type, $month, $year]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total'])) {
                return $result['total'];
            } else {
                return 0;
            }
        } catch(PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
            return 0;
        }
    }

    public static function getYearlyStatistics(PDO $conn, $year) {
        try {
            $stmt = $conn->prepare("SELECT Month, SUM(amount) AS income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS expenses FROM financial_records WHERE Year = ? GROUP BY Month");
            $stmt->execute([$year]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
            return []; 
        }
    }
    public static function getTotalByCategory(PDO $pdo, $user_id, $category_name) {
        try {
            $stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM financial_records 
                                    JOIN categories ON financial_records.category_id = categories.id 
                                    WHERE categories.name = :category_name AND financial_records.user_id = :user_id");
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public static function getTotalByType(PDO $pdo, $user_id, $type) {
        try {
            $stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM financial_records WHERE type = :type AND user_id = :user_id");
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }

    public static function getWalletBalance(PDO $pdo, $user_id) {
        try {
            $stmt = $pdo->prepare("SELECT 
                                        (SELECT SUM(amount) FROM financial_records WHERE type = 'income' AND user_id = :user_id) 
                                        - 
                                        (SELECT SUM(amount) FROM financial_records WHERE type = 'expense' AND user_id = :user_id) 
                                        AS balance");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['balance'] ?? 0;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
}

?>