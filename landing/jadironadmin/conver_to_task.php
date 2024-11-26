<?php
require 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// جلب بيانات الطلب
$request_id = $_GET['request_id'];
$user_id = $_SESSION['user']['id'];

// جلب معلومات الطلب
$stmt = $pdo->prepare("SELECT * FROM contact_requests WHERE id = :id");
$stmt->execute([':id' => $request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    die("الطلب غير موجود.");
}

// جلب المستخدم المعني بالخدمة
$stmt = $pdo->prepare("SELECT id FROM users WHERE service_id = :service_id LIMIT 1");
$stmt->execute([':service_id' => $request['service_id']]);
$responsible_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$responsible_user) {
    die("لم يتم العثور على المستخدم المسؤول عن الخدمة.");
}

// تحويل الطلب إلى مهمة
try {
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, service_id, assigned_to, status, created_at, due_date) VALUES (:title, :description, :service_id, :assigned_to, 'جديدة', NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY))");
    $stmt->execute([
        ':title' => $request['title'],
        ':description' => $request['description'],
        ':service_id' => $request['service_id'],
        ':assigned_to' => $responsible_user['id']
    ]);

    // تسجيل العملية
    log_activity($pdo, $user_id, 'تحويل إلى مهمة', "تم تحويل الطلب {$request['title']} إلى مهمة.");

    $_SESSION['success_message'] = "تم تحويل الطلب إلى مهمة بنجاح.";
} catch (Exception $e) {
    $_SESSION['error_message'] = "حدث خطأ أثناء تحويل الطلب إلى مهمة: " . $e->getMessage();
}

header("Location: index.php?page=tasks");
exit;
?>

