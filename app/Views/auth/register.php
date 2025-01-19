<?php
session_start();

require_once __DIR__ . '/../../Controllers/AuthController.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Controllers\AuthController;
use app\Models\User;

$userModel = new User($conn);
$authController = new AuthController($userModel);

// Обробка відправленої форми
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->register($_POST['username'], $_POST['password'], $_POST['email']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../../../public/css/register.css?v=<?php echo time(); ?>">

</head>
<body>
<div class="container">
    <h2>Регистрация</h2>
    <form id="register-form" method="post">
        <label for="username">Логин:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Зарегистрироваться">
    </form>
    <p>Есть аккаунт? <a href="login.php">Войти</a>.</p>
    <div id="message"></div>
</div>
<script>
    document.getElementById('register-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });

        const result = await response.text();
        document.getElementById('message').innerHTML = result;

        if (response.ok) {
            window.location.href = 'login.php';
        }
    });
</script>
</body>
</html>
