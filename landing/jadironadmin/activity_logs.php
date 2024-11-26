<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$config = require 'config.php';

try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    die();
}


// جلب سجل الحركات
$stmt = $pdo->query("SELECT activity_logs.*, users.email FROM activity_logs JOIN users ON activity_logs.user_id = users.id ORDER BY created_at DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>سجل الحركات</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr style="background-color: <?php echo ($log['action'] === 'Task Assigned') ? '#d1ecf1' : '#fff'; ?>;">
                <th>المستخدم</th>
                <th>الإجراء</th>
                <th>التفاصيل</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <?php
            
                $userColor = '';
                $actionColor = '';
                $detailsColor = '';
                $dateColor = '';
                $icon = ''; // الرمز المستخدم لكل عملية

                if (strpos($log['action'], 'إضافة') !== false) {
                    $actionColor = 'rgba(230, 249, 230, 0.40)'; // أخضر شفاف 25%
                    $icon = '🟢'; // رمز الإضافة
                } elseif (strpos($log['action'], 'حذف') !== false) {
                    $actionColor = 'rgba(249, 230, 230, 0.40)'; // أحمر شفاف 25%
                    $icon = '🔴'; // رمز الحذف
                } elseif (strpos($log['action'], 'تحديث') !== false || strpos($log['action'], 'تعديل') !== false) {
                    $actionColor = 'rgba(255, 249, 230, 0.40)'; // أصفر شفاف 25%
                    $icon = '🟡'; // رمز التحديث أو التعديل
                } else {
                    $actionColor = 'rgba(230, 242, 249, 0.40)'; // أزرق شفاف 25%
                    $icon = '🔵'; // رمز العمليات الأخرى
                }
            ?>
            <tr>
                <td style="background-color: <?= $actionColor; ?>;"><?= htmlspecialchars($log['email']); ?></td>
                <td style="background-color: <?= $actionColor; ?>;"><?= $icon . ' ' . htmlspecialchars($log['action']); ?></td>
                <td style="background-color: <?= $actionColor; ?>;"><?= htmlspecialchars($log['details']); ?></td>
                <td style="background-color: <?= $actionColor; ?>;"><?= htmlspecialchars($log['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
