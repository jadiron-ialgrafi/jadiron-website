<?php
require 'config.php';
session_start();

$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];

try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    die();
}

// دالة لجلب المهام بناءً على الصلاحيات
if ($user_role == 'admin') {
    $stmt = $pdo->query("SELECT * FROM tasks");
} elseif ($user_role == 'manager') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to IN (SELECT id FROM users WHERE manager_id = :manager_id)");
    $stmt->execute([':manager_id' => $user_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to = :user_id");
    $stmt->execute([':user_id' => $user_id]);
}
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// دالة لعرض الوقت منذ بداية المهمة
function calculateElapsedTime($start_date) {
    $now = new DateTime();
    $start = new DateTime($start_date);
    $interval = $now->diff($start);
    return $interval->format('%a يوم و %h ساعة');
}

// دالة لجلب اسم المستخدم حسب معرف المستخدم
function getUserNameById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetchColumn();
}
?>

<div class="container mt-5">
    <h3>إدارة المهام</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>عنوان المهمة</th>
                <th>الأولوية</th>
                <th>الحالة</th>
                <th>المسؤول</th>
                <th>المدة منذ البداية</th>
                <th>آخر تحديث</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><span class="badge badge-<?= $task['priority'] == 'High' ? 'danger' : ($task['priority'] == 'Normal' ? 'primary' : 'secondary') ?>"><?= htmlspecialchars($task['priority']) ?></span></td>
                    <td><span class="badge badge-<?= $task['status'] == 'new' ? 'danger' : ($task['status'] == 'in_progress' ? 'info' : 'success') ?>"><?= htmlspecialchars($task['status'] == 'new' ? 'جديدة' : ($task['status'] == 'in_progress' ? 'جارية' : 'مكتملة')) ?></span></td>
                    <td><?= htmlspecialchars(getUserNameById($pdo, $task['assigned_to'])) ?></td>
                    <td><?= calculateElapsedTime($task['created_at']) ?></td>
                    <td><?= $task['updated_at'] ?></td>
                    <td><a href="index.php?page=task_details&task_id=<?= $task['id'] ?>">عرض التفاصيل</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
