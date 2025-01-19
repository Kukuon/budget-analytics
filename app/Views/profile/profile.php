<?php
session_start();

require_once __DIR__ . '/../../Controllers/AuthController.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../../config/database.php';

use app\Controllers\AuthController;
use app\Models\User;

$authController = new AuthController(new User($conn));
if (!$authController->isAuthenticated()) {
    header("Location: ../auth/login.php");
    exit;
}

$username = $_SESSION["username"];
$userModel = new User($conn);
$userProfile = $userModel->getUserProfile($username);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $newUsername = $_POST['new_username'];
    $userModel->updateProfile($userProfile['id'], $newUsername, $userProfile['email']);
    header("Location: profile.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $userModel->deleteAccount($userProfile['id']);
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    $userModel->logout();
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_photo'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['profile_image']['name'];
        $fileTmpName = $_FILES['profile_image']['tmp_name'];
        $uploadDir = __DIR__ . '/../../../public/uploads/';
        move_uploaded_file($fileTmpName, $uploadDir . $fileName);
        $userModel->updateProfileImage($userProfile['id'], $fileName);
        header("Location: profile.php");
        exit;
    } else {
        echo "Ошибка: файл не был загружен.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="../../../public/css/profile.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include('../partials/header.php'); ?>
<main>
    <div class="profile-info">
        <div class="profile-section">
            <h2>Добро пожаловать, <?php echo $username; ?>!</h2>

            <h2>Обновление профиля</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="new_username">Новое имя пользователя:</label>
                <input type="text" id="new_username" name="new_username" value="<?php echo $userProfile['username']; ?>">
                <input type="submit" name="update_profile" value="Обновить профиль">
            </form>

            <h2>Обновление фотографии профиля</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="file-input-container">
                    <label for="profile_image">Выберите изображение:</label>
                    <input type="file" id="profile_image" name="profile_image">
                </div>
                <input type="submit" name="update_photo" value="Обновить фото">
            </form>
        </div>
        <div class="logout-section">
            <div class="forms-container">
                <div class="form-item">
                    <h2>Выход</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p>Вы уверены, что хотите завершить сеанс?</p>
                        <input type="submit" name="logout" value="Выйти">
                        <p></p>
                    </form>
                </div>

                <div class="form-item">
                    <h2>Удалить аккаунт</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p>Вы уверены, что хотите удалить свой аккаунт?</p>
                        <input type="submit" name="delete_account" value="Удалить аккаунт">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('../partials/footer.php'); ?>
</body>
</html>
