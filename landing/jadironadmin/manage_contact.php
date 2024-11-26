<?php
session_start();

// تفعيل عرض الأخطاء في PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$config = require 'config.php';

// الاتصال بقاعدة البيانات
try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// تنفيذ عمليات الحذف والاستعادة والإغلاق والاستلام
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'] ?? null;
    $user_id = $_SESSION['user']['id'];
    $comment = $_POST['comment'] ?? '';
    $action = '';

    if (!$request_id) {
        $_SESSION['message'] = "لم يتم تحديد الطلب بشكل صحيح.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=manage_contact");
        exit;
    }

    // استلام الطلب
    if (isset($_POST['receive_request'])) {
        $stmt_receive = $pdo->prepare("UPDATE contact_requests SET status = 'received', receiver_name = :receiver_name, received_at = NOW() WHERE id = :id AND status = 'open'");
        $stmt_receive->bindParam(':id', $request_id, PDO::PARAM_INT);
        $stmt_receive->bindParam(':receiver_name', $_SESSION['user']['email'], PDO::PARAM_STR);

        try {
            if ($stmt_receive->execute()) {
                $_SESSION['message'] = "تم استلام الطلب بنجاح.";
                $action = "استلام الطلب";
                // تسجيل العملية في activity_logs
                $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:user_id, :action, :details, NOW())");
                $stmt_log->execute([
                    ':user_id' => $user_id,
                    ':action' => $action,
                    ':details' => "تم استلام الطلب رقم $request_id"
                ]);
            } else {
                $_SESSION['message'] = "فشل في عملية الاستلام أو أن الطلب ليس مفتوحًا.";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "خطأ أثناء استلام الطلب: " . $e->getMessage();
        }
    }

    // إغلاق الطلب
    elseif (isset($_POST['close_request'])) {
        $stmt_check = $pdo->prepare("SELECT status FROM contact_requests WHERE id = :id");
        $stmt_check->bindParam(':id', $request_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $status = $stmt_check->fetchColumn();

        if ($status === 'received') {
            $stmt_close = $pdo->prepare("UPDATE contact_requests SET status = 'closed', closed_comment = :closed_comment, closed_at = NOW() WHERE id = :id");
            $stmt_close->bindParam(':id', $request_id, PDO::PARAM_INT);
            $stmt_close->bindParam(':closed_comment', $comment, PDO::PARAM_STR);

            if ($stmt_close->execute()) {
                $_SESSION['message'] = "تم إغلاق الطلب بنجاح.";
                $action = "إغلاق الطلب";
                // تسجيل العملية في activity_logs
                $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:user_id, :action, :details, NOW())");
                $stmt_log->execute([
                    ':user_id' => $user_id,
                    ':action' => $action,
                    ':details' => "تم إغلاق الطلب رقم $request_id"
                ]);
            } else {
                $_SESSION['message'] = "فشل في عملية الإغلاق.";
            }
        } else {
            $_SESSION['message'] = "لا يمكن إغلاق الطلب إلا بعد استلامه.";
        }
    }

    // تحويل الطلب إلى مهمة
    elseif (isset($_POST['convert_to_task'])) {
        $title = $_POST['title'] ?? 'بدون عنوان';
        $details = $_POST['details'] ?? 'بدون تفاصيل';
    
        // إنشاء المهمة
        $stmt_task = $pdo->prepare("INSERT INTO tasks (contact_request_id, assigned_to, title, details, status, created_at) VALUES (:request_id, :assigned_to, :title, :details, 'in_progress', NOW())");
        $stmt_task->bindParam(':request_id', $request_id, PDO::PARAM_INT);
        $stmt_task->bindParam(':assigned_to', $user_id, PDO::PARAM_INT);
        $stmt_task->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt_task->bindParam(':details', $details, PDO::PARAM_STR);
    
        if ($stmt_task->execute()) {
            // تحديث حالة الطلب إلى "مغلق"
            $stmt_close_request = $pdo->prepare("UPDATE contact_requests SET status = 'closed', closed_comment = 'تم تحويل الطلب إلى مهمة', closed_at = NOW() WHERE id = :id");
            $stmt_close_request->bindParam(':id', $request_id, PDO::PARAM_INT);
            $stmt_close_request->execute();
    
            $_SESSION['message'] = "تم تحويل الطلب إلى مهمة وتم إغلاقه بنجاح.";
            $action = "تحويل إلى مهمة وإغلاق الطلب";
    
            // تسجيل العملية في activity_logs
            $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (:user_id, :action, :details, NOW())");
            $stmt_log->execute([
                ':user_id' => $user_id,
                ':action' => $action,
                ':details' => "تم تحويل الطلب رقم $request_id إلى مهمة بعنوان '$title' وتم إغلاقه"
            ]);
        } else {
            $_SESSION['message'] = "فشل في عملية التحويل إلى مهمة.";
        }
    }
    

    header("Location: " . $_SERVER['PHP_SELF'] . "?page=manage_contact");
    exit;
}

// جلب الطلبات المفتوحة والمستلمة
$stmt_open = $pdo->query("SELECT cr.*, s.name AS service_name, TIMESTAMPDIFF(SECOND, cr.created_at, NOW()) AS duration_seconds FROM contact_requests cr LEFT JOIN services s ON cr.service_id = s.id WHERE cr.status = 'open' OR cr.status = 'received'");
$requests = $stmt_open->fetchAll(PDO::FETCH_ASSOC);

// جلب الطلبات المغلقة
$stmt_closed = $pdo->query("SELECT cr.*, s.name AS service_name, TIMESTAMPDIFF(SECOND, cr.created_at, cr.closed_at) AS duration_seconds FROM contact_requests cr LEFT JOIN services s ON cr.service_id = s.id WHERE cr.status = 'closed'");
$closed_requests = $stmt_closed->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML لعرض الطلبات -->
<div class="container mt-4">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <h2 class="mb-4 text-center">طلبات التواصل</h2>
    
    <h4 class="mt-5 text-center">الطلبات المفتوحة والمستلمة</h4>
    <table class="table table-bordered">
        <thead class="thead-dark text-center">
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الجوال</th>
                <th>الخدمة</th>
                <th>الوصف</th>
                <th>مدة الطلب</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                    <td><?php echo htmlspecialchars($request['email']); ?></td>
                    <td><?php echo htmlspecialchars($request['phone']); ?></td>
                    <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['description']); ?></td>
                    <td><?php echo gmdate("H:i:s", $request['duration_seconds']); ?></td>
                    <td class="text-center">
                        <?php if ($request['status'] == 'open'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="receive_request" class="btn btn-primary btn-sm">استلام</button>
                            </form>
                        <?php elseif ($request['status'] == 'received'): ?>
                            <button class="btn btn-warning btn-sm" onclick="openCloseModal(<?php echo $request['id']; ?>)">إغلاق</button>
                            <button class="btn btn-success btn-sm" onclick="openTaskModal(<?php echo $request['id']; ?>)">تحويل لمهمة</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">لا توجد طلبات مفتوحة أو مستلمة حالياً.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- نافذة منبثقة لإغلاق الطلب -->
<div id="closeModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">أدخل التعليق لإغلاق الطلب</h5>
                    <button type="button" class="close" onclick="closeCloseModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea name="comment" class="form-control" placeholder="اكتب تعليقك هنا..." required></textarea>
                    <input type="hidden" name="request_id" id="closeRequestId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCloseModal()">إلغاء</button>
                    <button type="submit" name="close_request" class="btn btn-warning">موافق</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
try {
    $users_stmt = $pdo->query("SELECT id, email FROM users");
    $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<option>خطأ في استرجاع المستخدمين: " . $e->getMessage() . "</option>";
}
?>

<!-- نافذة منبثقة لتحويل الطلب إلى مهمة مع خطوات -->
<div id="taskModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" onsubmit="return validateTaskForm()">
                <div class="modal-header">
                    <h5 class="modal-title">تحويل الطلب إلى مهمة</h5>
                    <button type="button" class="close" onclick="closeTaskModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- الخطوة 1: اختيار المستخدم -->
                    <div id="step1">
                        <label for="assigned_user">اختر المستخدم</label>
                        <select name="assigned_to" id="assigned_user" class="form-control mb-3" required>
                            <?php
                            if (!empty($users)) {
                                foreach ($users as $user) {
                                    echo "<option value='{$user['id']}'>{$user['email']}</option>";
                                }
                            } else {
                                echo "<option value=''>لا يوجد مستخدمين متاحين</option>";
                            }
                            ?>
                        </select>
                        <button type="button" class="btn btn-primary" onclick="goToStep(2)">التالي</button>
                    </div>
                    
                    <!-- الخطوة 2: كتابة العنوان والتفاصيل -->
                    <div id="step2" style="display: none;">
                        <label for="title">عنوان المهمة</label>
                        <input type="text" name="title" id="title" class="form-control mb-3" placeholder="عنوان المهمة" required>

                        <label for="details">تفاصيل المهمة</label>
                        <textarea name="details" id="details" class="form-control" placeholder="تفاصيل المهمة" required></textarea>
                        <input type="hidden" name="request_id" id="taskRequestId">

                        <button type="button" class="btn btn-secondary" onclick="goToStep(1)">رجوع</button>
                        <button type="submit" name="convert_to_task" class="btn btn-success">موافق</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// الانتقال بين الخطوات في النموذج
function goToStep(step) {
    document.getElementById('step1').style.display = (step === 1) ? 'block' : 'none';
    document.getElementById('step2').style.display = (step === 2) ? 'block' : 'none';
}

function openTaskModal(requestId) {
    document.getElementById("taskRequestId").value = requestId;
    document.getElementById("taskModal").style.display = "block";
    goToStep(1); // بدء النافذة من الخطوة الأولى
}

function closeTaskModal() {
    document.getElementById("taskModal").style.display = "none";
}

// التحقق من صحة النموذج قبل الإرسال
function validateTaskForm() {
    const assignedUser = document.getElementById("assigned_user").value;
    const title = document.getElementById("title").value;
    const details = document.getElementById("details").value;

    if (!assignedUser) {
        alert("يرجى اختيار مستخدم.");
        goToStep(1);
        return false;
    }

    if (!title || !details) {
        alert("يرجى ملء العنوان والتفاصيل.");
        goToStep(2);
        return false;
    }

    return true;
}
</script>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    padding: 20px;
    background: white;
    border-radius: 8px;
    max-width: 500px;
    width: 100%;
}
.modal-header, .modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h5 {
    margin: 0;
}
.modal-body label {
    font-weight: bold;
    margin-bottom: 5px;
}
.modal-body .form-control {
    margin-bottom: 15px;
}
</style>
