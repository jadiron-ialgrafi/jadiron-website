<?php
session_start();
$source = $_GET['source'] ?? 'unknown';

// إنشاء معرف فريد للجلسة إذا لم يكن موجودًا
if (!isset($_SESSION['visitor_id'])) {
    $_SESSION['visitor_id'] = session_id();
}

// Load the configuration
$config = require 'jadironadmin/config.php';

try {
    // Establish database connection using configuration details
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    die();
}

// تسجيل الزيارة عند تحميل الصفحة
$stmt = $pdo->prepare("INSERT INTO visitor_tracking (session_id, source, started_fill, submitted, exited_directly) VALUES (:session_id, :source, 0, 0, 1) ON DUPLICATE KEY UPDATE exited_directly = 1");
$stmt->execute([
    'session_id' => $_SESSION['visitor_id'],
    'source' => $source
]);

// استرجاع مسار الشعار وروابط التواصل الاجتماعي من قاعدة البيانات
$logoPath = $pdo->query("SELECT logo_path FROM settings")->fetchColumn();
$socialMedia = $pdo->query("SELECT * FROM social_media")->fetchAll(PDO::FETCH_ASSOC);

// Fetch services from the database
$stmt = $pdo->query("SELECT id, name FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تحديث حالة "بدء ملء النموذج" عند تحميل الصفحة لأول مرة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_fill'])) {
    $stmt = $pdo->prepare("UPDATE visitor_tracking SET started_fill = 1, exited_directly = 0 WHERE session_id = :session_id");
    $stmt->execute(['session_id' => $_SESSION['visitor_id']]);
}

// تحديث حالة "تم الإرسال" عند إرسال النموذج بالكامل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $stmt = $pdo->prepare("UPDATE visitor_tracking SET submitted = 1, exited_directly = 0 WHERE session_id = :session_id");
    $stmt->execute(['session_id' => $_SESSION['visitor_id']]);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جديرون - تواصل معنا</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            direction: rtl;
        }
        .contact-form {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .contact-form h3 {
            color: #003366;
            font-weight: 700;
            margin-bottom: 30px;
        }
        .contact-form .form-group label {
            color: #333;
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            text-align: right;
        }
        .contact-form .form-group label span.required {
            color: red;
            margin-left: 5px;
        }
        .contact-form .form-control {
            border: 2px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
            text-align: right;
        }
        .contact-form .form-control:focus {
            border-color: #003366;
            box-shadow: 0 0 8px rgba(0, 51, 102, 0.1);
        }
        .contact-form .btn {
            background: #003366;
            color: #fff;
            border-radius: 10px;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
            display: block;
            width: 100%;
        }
        .contact-form .btn:hover {
            background: #002244;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="contact-form">
        <div class="logo">
        <div class="logo">
        <div class="logo">
            <img src="img/logo.png" alt="شعار مِراس" style="width:150px;">
        <h3>املأ البيانات التالية للتواصل معك</h3>
        <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="errorAlert">
            <span id="errorMessage"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- نموذج بدء ملء النموذج لتحديث الحالة في قاعدة البيانات -->
        <form method="POST">
            <input type="hidden" name="start_fill" value="1">
            <button type="submit" style="display: none;">بدء ملء النموذج</button>
        </form>

        <!-- نموذج التواصل الرئيسي -->
        <form id="contactForm" method="POST">
            <div class="form-group">
                <label for="name"><span class="required">*</span>الاسم:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="phone"><span class="required">*</span>رقم الجوال:</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="05XXXXXXXX" required>
            </div>
            <div class="form-group">
                <label for="service"><span class="required">*</span>الخدمة المطلوبة:</label>
                <select name="service" class="form-control" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">وصف الخدمة:</label>
                <textarea id="description" name="description" class="form-control" style="resize: none;" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="platform">اختر منصة التواصل الاجتماعي:</label>
                <select name="platform" class="form-control">
                    <?php foreach ($socialMedia as $media): ?>
                        <option value="<?= htmlspecialchars($media['platform']) ?>"><?= htmlspecialchars($media['platform']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>طريقة التواصل المفضلة:</label>
                <input type="radio" name="contact_method" value="email" required> البريد الإلكتروني
                <input type="radio" name="contact_method" value="phone" required> الجوال
            </div>
            <div class="form-group">
                <label>الوقت المناسب:</label>
                <input type="radio" name="contact_time" value="morning" required> صباحًا
                <input type="radio" name="contact_time" value="evening" required> مساءً
            </div>
            <button type="submit" class="btn" name="submit">إرسال</button>
        </form>
    </div>
</body>
</html>
