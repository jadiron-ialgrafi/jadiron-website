<?php
require 'config.php';
session_start();

$task_id = $_GET['task_id'] ?? null;
$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];

if (!$task_id) {
    echo "لم يتم تحديد معرف المهمة";
    exit;
}

try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // جلب بيانات المهمة بناءً على الدور
    if ($user_role == 'admin' || $user_role == 'superadmin') {
        // إذا كان المستخدم admin أو superadmin، يمكنه رؤية أي مهمة
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :task_id");
    } else {
        // إذا كان مستخدمًا عاديًا، يمكنه فقط رؤية المهام الموكلة إليه
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :task_id AND assigned_to = :user_id");
        $stmt->bindParam(':user_id', $user_id);
    }
    $stmt->bindParam(':task_id', $task_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        ?>
        <div class="container mt-5">
            <div class="alert alert-danger text-center p-5" style="border-radius: 10px; background-color: #f8d7da; color: #721c24; font-size: 1.25rem;">
                <h4><i class="fas fa-exclamation-triangle"></i> عذرًا، لا يمكن الوصول إلى المهمة</h4>
                <p>المهمة غير موجودة أو ليس لديك الصلاحية للوصول إليها. يرجى التأكد من صلاحياتك أو التواصل مع الدعم الفني.</p>
                <a href="index.php" class="btn btn-secondary mt-3">العودة إلى الصفحة الرئيسية</a>
            </div>
        </div>
        <?php
        exit;
    }

    // جلب التعليقات
    $stmt_comments = $pdo->prepare("SELECT * FROM task_comments WHERE task_id = :task_id ORDER BY created_at DESC");
    $stmt_comments->execute([':task_id' => $task_id]);
    $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

    // جلب المستخدمين لتحويل المهمة
    $stmt_users = $pdo->query("SELECT id, email FROM users");
    $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

    // إضافة تعليق جديد وتسجيل الحركة
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        $comment = nl2br(htmlspecialchars($_POST['comment']));
        if (!empty($comment)) {
            $stmt_add_comment = $pdo->prepare("INSERT INTO task_comments (task_id, user_id, comment, created_at) VALUES (:task_id, :user_id, :comment, NOW())");
            $stmt_add_comment->execute([
                ':task_id' => $task_id,
                ':user_id' => $user_id,
                ':comment' => $comment
            ]);

            // تسجيل الحركة
            $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:user_id, 'إضافة تعليق', :details, NOW())");
            $stmt_log->execute([
                ':user_id' => $user_id,
                ':details' => "تم إضافة تعليق على المهمة رقم $task_id"
            ]);

            header("Location: index.php?page=task_details&task_id=" . $task_id);
            exit;
        }
    }

    // تحويل المسؤول عن المهمة
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_assigned'])) {
        $new_user_id = $_POST['assigned_to'];
        if ($new_user_id && ($user_role == 'admin' || $user_role == 'superadmin' || $user_id == $task['assigned_to'])) {
            // جلب البريد الإلكتروني للمستخدم الجديد
            $stmt_get_email = $pdo->prepare("SELECT email FROM users WHERE id = :id");
            $stmt_get_email->execute([':id' => $new_user_id]);
            $new_user_email = $stmt_get_email->fetchColumn();

            $stmt_change_assigned = $pdo->prepare("UPDATE tasks SET assigned_to = :assigned_to WHERE id = :task_id");
            $stmt_change_assigned->execute([
                ':assigned_to' => $new_user_id,
                ':task_id' => $task_id
            ]);

            // تسجيل الحركة مع البريد الإلكتروني بدلاً من الرقم
            $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:user_id, 'تغيير المسؤول', :details, NOW())");
            $stmt_log->execute([
                ':user_id' => $user_id,
                ':details' => "تم تغيير المسؤول عن المهمة رقم $task_id إلى $new_user_email"
            ]);

            header("Location: index.php?page=task_details&task_id=" . $task_id);
            exit;
        }
    }

} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    die();
}

// Function to get the user's name
function getUserNameById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetchColumn();
}
?>

<div class="container mt-5">
    <?php if (!$task): ?>
        <!-- رسالة الخطأ عند عدم وجود المهمة -->
        <div class="alert alert-danger text-center p-5" style="border-radius: 10px; background-color: #f8d7da; color: #721c24;">
            <h4>المهمة غير موجودة أو ليس لديك صلاحية للوصول إليها</h4>
            <p>يرجى التأكد من المعرف الصحيح للمهمة أو التواصل مع الدعم الفني للمساعدة.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- العنوان والتفاصيل -->
            <div class="col-md-8">
                <div class="card p-4 bg-light border rounded shadow-sm h-100">
                    <h5 class="text-primary mb-3" style="font-weight: bold; font-size: 1.5rem;"><?= htmlspecialchars($task['title']) ?></h5>
                    <p class="task-description" style="font-size: 1.1rem; line-height: 1.6;"><strong>الوصف:</strong> <?= nl2br(htmlspecialchars($task['details'])) ?></p>
                </div>
            </div>

            <!-- معلومات المهمة -->
            <div class="col-md-4">
                <div class="card p-4 bg-light border rounded shadow-sm h-100">
                    <h5 class="border-bottom pb-2 mb-3">معلومات المهمة</h5>
                    <p><strong>الحالة:</strong> 
                        <span class="badge badge-<?= $task['status'] == 'new' ? 'danger' : ($task['status'] == 'in_progress' ? 'info' : 'success') ?>">
                            <?= htmlspecialchars($task['status'] == 'new' ? 'جديدة' : ($task['status'] == 'in_progress' ? 'جارية' : 'مكتملة')) ?>
                        </span>
                    </p>
                    <p><strong>الأولوية:</strong> 
                        <span class="badge badge-<?= $task['priority'] == 'High' ? 'danger' : ($task['priority'] == 'Normal' ? 'primary' : 'secondary') ?>">
                            <?= htmlspecialchars($task['priority']) ?>
                        </span>
                    </p>
                    <p><strong>الفئة:</strong> <?= htmlspecialchars($task['category']) ?></p>
                    <p><strong>المسؤول:</strong> <?= htmlspecialchars(getUserNameById($pdo, $task['assigned_to'])) ?>
                        <?php if ($user_role == 'admin' || $user_id == $task['assigned_to']): ?>
                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#changeAssignedModal">تغيير</button>
                        <?php endif; ?>
                    </p>
                    <p><strong>تاريخ البدء:</strong> <?= htmlspecialchars($task['created_at']) ?></p>
                </div>
            </div>
        </div>

        <br>

        <!-- قسم التحديثات (التعليقات) -->
        <div class="comments-section mb-5">
            <h5 class="border-bottom pb-2 mb-3">التحديثات</h5>
            <div class="comments-list">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment mb-3 p-3 border rounded shadow-sm">
                            <strong><?= htmlspecialchars(getUserNameById($pdo, $comment['user_id'])) ?>:</strong>
                            <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            <small class="text-muted"><?= $comment['created_at'] ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">لا توجد تعليقات حالياً.</p>
                <?php endif; ?>
            </div>

            <!-- إضافة تعليق -->
            <form method="post" class="mt-4">
                <input type="hidden" name="add_comment" value="1">
                <textarea name="comment" class="form-control mb-2" placeholder="أضف تحديث..." required></textarea>
                <button type="submit" class="btn btn-primary">إضافة تحديث</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- الفووتر -->
<footer class="footer mt-auto py-3 bg-dark text-white text-center">
    <div class="container">
        <span>© 2024 شركة جديرون. جميع الحقوق محفوظة.</span>
    </div>
</footer>

<!-- CSS للتصميم -->
<style>
.container {
    max-width: 900px;
    margin: auto;
}
h5 {
    font-weight: bold;
}
.card {
    margin-bottom: 20px;
}
.comments-section h5 {
    font-weight: bold;
}
.comment {
    margin-bottom: 10px;
    padding: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #343a40;
    color: #fff;
    padding: 10px;
}
</style>

<!-- JavaScript لـ Bootstrap Modal -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
