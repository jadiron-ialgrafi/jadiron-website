    <?php
require 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user']['id'];

// دالة لتسجيل العمليات في جدول activity_logs
function log_activity($pdo, $user_id, $action, $details) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (:user_id, :action, :details)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':action' => $action,
        ':details' => $details
    ]);
}
// إضافة الإيميل الجديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_email'])) {
    $service_id = $_POST['service_id'];
    $email = trim($_POST['email']); // إزالة المسافات البيضاء قبل وبعد الإيميل
    $is_global = isset($_POST['is_global']) ? 1 : 0;

    // التحقق من صحة الإيميل باستخدام filter_var
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "يرجى إدخال إيميل صالح.";
        header("Location: index.php?page=manage_emails");
        exit;
    }

    try {
        if ($is_global) {
            // تحقق مما إذا كان الإيميل موجودًا في الإيميلات العامة
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM global_emails WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION['error_message'] = "الإيميل مضاف مسبقًا في قائمة الإيميلات العامة.";
            } else {
                // إضافة الإيميل إلى القائمة العامة
                $stmt = $pdo->prepare("INSERT INTO global_emails (email) VALUES (:email)");
                $stmt->execute([':email' => $email]);
                $_SESSION['success_message'] = "تمت إضافة الإيميل بنجاح في القائمة العامة.";

                // تسجيل العملية مع توضيح أن الإيميل أُضيف إلى القائمة العامة
                log_activity($pdo, $user_id, 'إضافة إيميل', "تمت إضافة الإيميل $email إلى القائمة العامة.");
            }
        } else {
            // تحقق مما إذا كان الإيميل موجودًا للخدمة المحددة
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM service_emails WHERE service_id = :service_id AND email = :email");
            $stmt->execute([':service_id' => $service_id, ':email' => $email]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION['error_message'] = "الإيميل مضاف مسبقًا لهذه الخدمة.";
            } else {
                // إضافة الإيميل إلى الخدمة المحددة
                $stmt = $pdo->prepare("INSERT INTO service_emails (service_id, email) VALUES (:service_id, :email)");
                $stmt->execute([':service_id' => $service_id, ':email' => $email]);
                $_SESSION['success_message'] = "تمت إضافة الإيميل بنجاح.";

                // جلب اسم الخدمة
                $stmt = $pdo->prepare("SELECT name FROM services WHERE id = :service_id");
                $stmt->execute([':service_id' => $service_id]);
                $service_name = $stmt->fetchColumn();

                // تسجيل العملية مع توضيح الخدمة المرتبطة
                log_activity($pdo, $user_id, 'إضافة إيميل', "تمت إضافة الإيميل $email للخدمة $service_name.");
            }
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "حدث خطأ أثناء إضافة الإيميل: " . $e->getMessage();
    }

    header("Location: index.php?page=manage_emails");
    exit;
}

// حذف الإيميل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_email'])) {
    $email_id = $_POST['email_id'];
    $table = $_POST['table'];

    try {
        if ($table === 'global') {
            // جلب الإيميل المحذوف من القائمة العامة
            $stmt = $pdo->prepare("SELECT email FROM global_emails WHERE id = :id");
            $stmt->execute([':id' => $email_id]);
            $email = $stmt->fetchColumn();

            // حذف الإيميل من القائمة العامة
            $stmt = $pdo->prepare("DELETE FROM global_emails WHERE id = :id");
            $stmt->execute([':id' => $email_id]);
            $_SESSION['success_message'] = "تم حذف الإيميل بنجاح.";

            // تسجيل العملية مع توضيح أن الإيميل تم حذفه من القائمة العامة
            log_activity($pdo, $user_id, 'حذف إيميل', "تم حذف الإيميل $email من القائمة العامة.");
        } else {
            // جلب اسم الخدمة المرتبطة بالإيميل
            $stmt = $pdo->prepare("SELECT service_emails.email, services.name as service_name FROM service_emails JOIN services ON service_emails.service_id = services.id WHERE service_emails.id = :id");
            $stmt->execute([':id' => $email_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $email = $result['email'];
            $service_name = $result['service_name'];

            // حذف الإيميل من الخدمة المحددة
            $stmt = $pdo->prepare("DELETE FROM service_emails WHERE id = :id");
            $stmt->execute([':id' => $email_id]);
            $_SESSION['success_message'] = "تم حذف الإيميل بنجاح.";

            // تسجيل العملية مع توضيح الخدمة المرتبطة
            log_activity($pdo, $user_id, 'حذف إيميل', "تم حذف الإيميل $email من الخدمة $service_name.");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "حدث خطأ أثناء حذف الإيميل: " . $e->getMessage();
    }

    header("Location: index.php?page=manage_emails");
    exit;
}
// جلب قائمة المستخدمين
$users = $pdo->query("SELECT id, email FROM users")->fetchAll(PDO::FETCH_ASSOC);

// جلب الخدمات والإيميلات المرتبطة
$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
$service_emails = $pdo->query("
    SELECT se.id, s.name AS service_name, se.email, se.service_id 
    FROM service_emails se 
    JOIN services s ON se.service_id = s.id
")->fetchAll(PDO::FETCH_ASSOC);
$global_emails = $pdo->query("SELECT * FROM global_emails")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h3>إدارة الإيميلات المرتبطة بالخدمات</h3>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $_SESSION['error_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
<!-- تعديل النموذج لإضافة إيميل جديد -->
<div class="card mb-4 shadow-sm" style="background-color: rgba(255, 255, 255, 0.35);">
    <div class="card-body">
        <form method="post">
            <div class="form-group mb-3">
                <label for="service_id" class="form-label">اختر الخدمة:</label>
                <select name="service_id" id="service_id" class="form-select">
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label for="email" class="form-label">اختر الإيميل:</label>
                <select name="email" id="email" required class="form-select">
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['email'] ?>"><?= $user['email'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group form-check mb-3">
                <input type="checkbox" name="is_global" id="is_global" class="form-check-input">
                <label class="form-check-label" for="is_global">استقبال جميع الطلبات</label>
            </div>
            
            <button type="submit" name="add_email" class="btn btn-success w-100" style="opacity: 0.5;">إضافة</button>
        </form>
    </div>
</div>


    <!-- عرض الإيميلات العامة -->
    <h4>الإيميلات العامة (استقبال جميع الطلبات)</h4>
    <div class="card mb-3">
        <div class="card-header">
            الإيميلات العامة
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($global_emails as $global_email): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($global_email['email']) ?>
                    <form method="post" class="mb-0">
                        <input type="hidden" name="email_id" value="<?= $global_email['id'] ?>">
                        <input type="hidden" name="table" value="global">
                        <button type="submit" name="delete_email" class="btn btn-danger btn-sm">حذف</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- عرض الإيميلات المرتبطة بالخدمات -->
    <h4>الإيميلات المرتبطة بكل خدمة</h4>
    <?php foreach ($services as $service): ?>
        <?php
        // جلب عدد الإيميلات المرتبطة بالخدمة
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM service_emails WHERE service_id = :service_id");
        $stmt->execute([':service_id' => $service['id']]);
        $email_count = $stmt->fetchColumn();
        ?>
        <div class="card mb-3">
            <div class="card-header">
                <?= htmlspecialchars($service['name']) ?> (<?= $email_count ?> إيميلات مرتبطة)
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($service_emails as $email): ?>
                    <?php if ($email['service_id'] == $service['id']): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($email['email']) ?>
                            <form method="post" class="mb-0">
                                <input type="hidden" name="email_id" value="<?= $email['id'] ?>">
                                <input type="hidden" name="table" value="service">
                                <button type="submit" name="delete_email" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<!-- سكريبت لإخفاء الرسالة بعد 5 ثوانٍ -->
<script>
    setTimeout(function() {
        const alertBox = document.querySelector('.alert');
        if (alertBox) {
            alertBox.classList.add('fade');
            setTimeout(function() {
                alertBox.remove();
            }, 500);
        }
    }, 5000); // إخفاء بعد 5 ثوانٍ
</script>

<?php
ob_end_flush(); // إنهاء التخزين المؤقت وإرسال الإخراج
?>

