<?php
session_start();
$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['database']}", $config['database']['username'], $config['database']['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // إدخال SuperAdmin في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role, is_superadmin) VALUES (:email, :password, 'superadmin', 1)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء SuperAdmin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f6f9;
        }
        .register-container {
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="text-center">إنشاء SuperAdmin</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">إنشاء SuperAdmin</button>
        </form>
    </div>
</body>
</html>

