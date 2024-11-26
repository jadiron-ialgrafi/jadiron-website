<?php
session_start();
$config = require 'config.php';

try {
    $pdo = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['database']}", $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // التحقق من وجود أي مستخدمين في قاعدة البيانات
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $user_count = $stmt->fetchColumn();

    // إذا لم يكن هناك مستخدمين، يتم توجيه المستخدم لإنشاء Super Admin
    if ($user_count == 0) {
        header('Location: create_admin.php');
        exit;
    }
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// الاحتفاظ بالبريد الإلكتروني في حال حدوث خطأ
$email = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $password = $_POST['password'];

        // التحقق من وجود المستخدم في قاعدة البيانات
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $error_message = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
        }
    } catch (PDOException $e) {
        die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f6f9;
            direction: rtl;
        }
        .login-container {
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-group {
            text-align: right;
        }
        .password-toggle {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body onload="focusPassword()">
    <div class="login-container">
        <h2 class="text-center">تسجيل الدخول</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        <?php endif; ?>
        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group position-relative">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group position-relative">
                <label for="password">كلمة المرور</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="password-toggle" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">تسجيل الدخول</button>
        </form>
    </div>

    <!-- JavaScript لإظهار وإخفاء كلمة المرور -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function focusPassword() {
            const passwordField = document.getElementById('password');
            if (passwordField.value === "") {
                passwordField.focus();
            }
        }

        $(document).ready(function() {
            // اجعل الرسالة تختفي بعد 5 ثواني
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });

        function validateForm() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            if (emailField.value.trim() !== "" && passwordField.value.trim() === "") {
                passwordField.focus();
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

