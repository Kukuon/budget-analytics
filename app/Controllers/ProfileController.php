<?php
namespace app\Controllers;

use app\Models\User;
use PDO;

class ProfileController {
    private $userModel;
    private $db;

    public function __construct(PDO $pdo) {
        $this->userModel = new User();
        $this->db = $pdo;
    }

    public function updateProfile($userId, $newUsername) {
        $sql = "UPDATE users SET username = :username WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function handleProfileForm() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newUsername = $_POST["new_username"];
            $userId = $_SESSION['user_id']; 

            $this->updateProfile($userId, $newUsername);
        }
    }
}
