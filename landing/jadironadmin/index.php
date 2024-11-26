<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// إعداد متغيرات الصفحات
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// تحميل إعدادات قاعدة البيانات
$config = require 'config.php';

try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    die();
}

// تضمين محتوى الصفحة المطلوبة
$content = 'dashboard.php'; // الصفحة الافتراضية
if (file_exists($page . '.php')) {
    $content = $page . '.php';
}

ob_start(); // بدء التخزين المؤقت للإخراج
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إدارة مِراس</title>
    <!-- مكتبة Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- مكتبة أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- الخط المستخدم (Tajawal) -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&display=swap" rel="stylesheet"> <!-- خط مميز لمِراس -->
    <!-- ملف CSS المخصص -->
    <!-- Import fonts from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- شريط التنقل العلوي -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="index.php?page=info">
        <div class="logo">مِراس</div>
    </a>
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="change_password.php">تغيير كلمة المرور</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">تسجيل خروج</a>
        </li>
    </ul>
</nav>

<!-- الشريط الجانبي -->
<div class="sidebar">
    <a href="index.php?page=dashboard"><i class="fas fa-home"></i> الصفحة الرئيسية</a>
    <a href="index.php?page=manage_contact"><i class="fas fa-address-book"></i> إدارة التواصل</a>
    <a href="index.php?page=manage_users"><i class="fas fa-users-cog"></i> إدارة المستخدمين</a>
    <a href="index.php?page=control_panel"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
    <a href="index.php?page=activity_logs"><i class="fas fa-history"></i> سجل الحركات</a>
    <a href="index.php?page=manage_emails"><i class="fas fa-envelope"></i> إدارة الإيميلات</a>
    <a href="index.php?page=manage_tasks"><i class="fas fa-tasks"></i> إدارة المهام</a>
    <a href="index.php?page=manage_services"><i class="fas fa-concierge-bell"></i> إدارة الخدمات</a>
</div>


<!-- المحتوى الرئيسي -->
<div class="container-fluid">
    <?php include $content; ?>
</div>

<!-- الفوتر الثابت -->
<footer>
    <p>© 2024 مِراس - جميع الحقوق محفوظة</p>
</footer>

<!-- سكربتات Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

