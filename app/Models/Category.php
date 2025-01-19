<?php
// Category.php
namespace app\Models;

use PDO;

class Category {
    private PDO $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public static function getAll(PDO $pdo, $user_id) : array{
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add(PDO $pdo, $categoryName, $categoryType, $categoryColor, $user_id) : void{
        $stmt = $pdo->prepare("INSERT INTO categories (name, type, color, user_id) VALUES (:name, :type, :color, :user_id)");
        $stmt->bindParam(':name', $categoryName);
        $stmt->bindParam(':type', $categoryType);
        $stmt->bindParam(':color', $categoryColor);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public static function delete(PDO $pdo, $categoryId): void{
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
    }

    public static function getById(PDO $pdo, $categoryId) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
