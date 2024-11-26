<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['message'] = "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    header("Location: index.php?page=manage_contact");
    exit;
}

// تحقق من العملية المطلوبة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign_task'])) {
        // إسناد المهمة
        $request_id = $_POST['request_id'];
        $assigned_to = $_POST['assigned_to'];

        // تنفيذ الإسناد
        $stmt = $pdo->prepare("UPDATE contact_requests SET status = 'closed' WHERE id = :id");
        $stmt->bindParam(':id', $request_id);
        $stmt->execute();

        $_SESSION['message'] = "تم تحويل الطلب إلى مهمة بنجاح";
        header("Location: index.php?page=manage_contact");
        exit;
        
    } elseif (isset($_POST['delete_request'])) {
        // حذف الطلب
        $request_id = $_POST['request_id'];

        $stmt = $pdo->prepare("UPDATE contact_requests SET status = 'deleted' WHERE id = :id");
        $stmt->bindParam(':id', $request_id);
        $stmt->execute();

        $_SESSION['message'] = "تم حذف الطلب بنجاح";
        header("Location: index.php?page=manage_contact");
        exit;
        
    } elseif (isset($_POST['restore_request'])) {
        // استعادة الطلب
        $request_id = $_POST['request_id'];

        $stmt = $pdo->prepare("UPDATE contact_requests SET status = 'open' WHERE id = :id");
        $stmt->bindParam(':id', $request_id);
        $stmt->execute();

        $_SESSION['message'] = "تم استعادة الطلب بنجاح";
        header("Location: index.php?page=manage_contact");
        exit;
    }
}
?>
