<?php
namespace app\Models;

use PDO;

class User {
    private $db;

    public function __construct($db) {
        if ($db instanceof PDO) {
            $this->db = $db;
        } else {
            $config = require_once(__DIR__ . '/../../config/database.php');
            $dsn = "mysql:host={$config['servername']};dbname={$config['dbname']}";
            $this->db = new PDO($dsn, $config['username'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public function getUserByUsernameAndPassword($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt;
    }

    public function getUserProfile($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $result = $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);

        return $result;
    }

    public function exists($username, $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? true : false;
    }

    public static function findById($id, $db) {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $newUsername, $newEmail) {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $result = $stmt->execute(['username' => $newUsername, 'email' => $newEmail, 'id' => $id]);

        return $result;
    }

    public function logout() {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: ../auth/login.php");
        exit;
    }

    public function deleteAccount($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $result = $stmt->execute(['id' => $id]);

        return $result;
    }

    public function updateProfileImage($id, $fileName) {
        $stmt = $this->db->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
        $result = $stmt->execute(['profile_image' => $fileName, 'id' => $id]);

        return $result;
    }

    public function getProfileImage($userId, $conn) {
        try {
            $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['profile_image'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

}
?>
