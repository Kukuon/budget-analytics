<?php

namespace app\Controllers;

use app\Models\User;
use PDO;
use PDOException;

class AuthController
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
    }

    public function login($username = null, $password = null): void
    {
        if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
            echo "Метод запроса должен быть POST.";
            include_once __DIR__ . '/../views/auth/login.php';
            return;
        }

        if ($username === null || $password === null) {
            echo "Имя пользователя или пароль не указаны.";
            include_once __DIR__ . '/../views/auth/login.php';
            return;
        }

        try {
            $stmt = $this->user->getUserByUsernameAndPassword($username, $password);

            if ($stmt->rowCount() !== 1) {
                echo "Неправильное имя пользователя или пароль.";
                include_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["authenticated"] = true;
            $_SESSION["username"] = $user['username'];
            $_SESSION["user_id"] = $user['id'];

            header("location: ../dashboard/index.php");
            exit;
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    }

    public function register($username = null, $password = null, $email = null): void
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            include_once __DIR__ . '/../views/auth/register.php';
            return;
        }

        if ($username === null || $password === null || $email === null) {
            echo "Пожалуйста, заполните все поля.";
            return;
        }

        if ($this->user->exists($username, $email)) {
            echo "Пользователь с таким именем или email уже существует.";
            return;
        }

        if ($this->user->register($username, $email, $password)) {
            echo "Регистрация успешна.";
        } else {
            echo "Произошла ошибка при регистрации.";
        }
    }
}
