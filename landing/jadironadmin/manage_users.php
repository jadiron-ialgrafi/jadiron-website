<?php 
require 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$current_user_id = $_SESSION['user']['id'];

// دالة لتسجيل العمليات في جدول activity_logs
function log_activity($pdo, $user_id, $action, $details) {
    // التحقق مما إذا كان user_id موجودًا في جدول users
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $exists = $stmt->fetchColumn();

    // إذا لم يكن user_id موجودًا، اجعله NULL
    $user_id = $exists ? $user_id : null;

    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (:user_id, :action, :details)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':action' => $action,
        ':details' => $details
    ]);
}

try {
    $pdo = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['database']}", $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // إضافة مستخدم جديد
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        if ($role == 'superadmin') {
            $_SESSION['error_message'] = "لا يمكن إضافة Super Admin آخر.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            log_activity($pdo, $current_user_id, 'إضافة مستخدم', "تم إضافة المستخدم $email بدور $role.");
            $_SESSION['success_message'] = "تم إضافة المستخدم بنجاح.";
        }
        header('Location: index.php?page=manage_users');
        exit;
    }

    // تعديل صلاحيات المستخدم
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
        $target_user_id = $_POST['user_id'];
        $new_role = $_POST['role'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $target_user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['role'] === 'superadmin') {
            $_SESSION['error_message'] = "لا يمكن تعديل صلاحيات الـ Super Admin.";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
            $stmt->bindParam(':role', $new_role);
            $stmt->bindParam(':id', $target_user_id);
            $stmt->execute();

            log_activity($pdo, $current_user_id, 'تعديل صلاحيات', "تم تعديل صلاحيات المستخدم {$user['email']} إلى $new_role.");
            $_SESSION['success_message'] = "تم تعديل صلاحيات المستخدم بنجاح.";
        }

        header('Location: index.php?page=manage_users');
        exit;
    }

    // حذف المستخدم
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
        $target_user_id = $_POST['user_id'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $target_user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['role'] === 'superadmin') {
            $_SESSION['error_message'] = "لا يمكن حذف Super Admin.";
        } elseif ($user['id'] == $current_user_id) {
            $_SESSION['error_message'] = "لا يمكن حذف حسابك الخاص.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $target_user_id);
            $stmt->execute();

            log_activity($pdo, $current_user_id, 'حذف مستخدم', "تم حذف المستخدم {$user['email']}.");
            $_SESSION['success_message'] = "تم حذف المستخدم بنجاح.";
        }

        header('Location: index.php?page=manage_users');
        exit;
    }

    // عرض جميع المستخدمين
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

?>

<div class="container mt-5">
    <div class="card" style="background-color: rgba(255, 255, 255, 0.35);">
        <div class="card-body">
            <h2 class="text-center">إدارة المستخدمين</h2>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- إضافة مستخدم جديد -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="email" class="form-control" id="email" name="email" placeholder="البريد الإلكتروني" required>
                </div>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="كلمة المرور" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="role" name="role">
                        <option value="admin">مسؤول</option>
                        <option value="manager">مدير</option>
                        <option value="user">مستخدم</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_user" class="btn btn-primary w-100">إضافة المستخدم</button>
                </div>
            </form>

            <h3 class="mt-4">قائمة المستخدمين</h3>
            <table class="table table-hover table-bordered">
                <thead style="background-color: rgba(0, 0, 0, 0);">
                    <tr>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحية</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <?php if ($user['role'] !== 'superadmin'): ?>
                                <!-- زر لتعديل الصلاحيات -->
                                <button class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id']; ?>">تعديل الصلاحيات</button>
                                <!-- زر لحذف المستخدم -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger btn-action" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">حذف</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>لا يمكن التعديل أو الحذف</button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Modal لتعديل الصلاحيات -->
                    <div class="modal fade" id="editModal<?= $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $user['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $user['id']; ?>">تعديل صلاحيات المستخدم</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <div class="form-group">
                                            <label for="role">اختر الصلاحية</label>
                                            <select class="form-select" id="role" name="role">
                                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>مسؤول</option>
                                                <option value="manager" <?= $user['role'] == 'manager' ? 'selected' : ''; ?>>مدير</option>
                                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>مستخدم</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" name="edit_user" class="btn btn-primary">حفظ التغييرات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

