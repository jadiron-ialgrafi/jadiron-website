<?php 
require 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$current_user_id = $_SESSION['user']['id'];

// دالة لتسجيل العمليات في جدول activity_logs
function log_activity($pdo, $user_id, $action, $details) {
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

    // جلب جميع الخدمات بترتيب ID 
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // الحصول على آخر ID وتحديد ID جديد
    $last_id = $pdo->query("SELECT id FROM services ORDER BY id DESC LIMIT 1")->fetchColumn();
    $new_id = $last_id ? $last_id + 1 : 1;

    // إضافة خدمة جديدة
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
        $service_name = $_POST['service_name'];
        $stmt = $pdo->prepare("INSERT INTO services (id, name) VALUES (:id, :name)");
        $stmt->execute([':id' => $new_id, ':name' => $service_name]);

        log_activity($pdo, $current_user_id, 'إضافة خدمة', "تمت إضافة الخدمة $service_name.");
        $_SESSION['success_message'] = "تمت إضافة الخدمة بنجاح.";
        header("Location: index.php?page=manage_services");
        exit;
    }

    // تعديل اسم الخدمة
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
        $service_id = $_POST['service_id'];
        $new_name = $_POST['service_name'];

        $stmt = $pdo->prepare("UPDATE services SET name = :name WHERE id = :id");
        $stmt->execute([':name' => $new_name, ':id' => $service_id]);

        log_activity($pdo, $current_user_id, 'تعديل خدمة', "تم تعديل اسم الخدمة إلى $new_name.");
        $_SESSION['success_message'] = "تم تعديل الخدمة بنجاح.";
        header("Location: index.php?page=manage_services");
        exit;
    }

    // حذف الخدمة
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service'])) {
        $service_id = $_POST['service_id'];

        $stmt = $pdo->prepare("SELECT name FROM services WHERE id = :id");
        $stmt->execute([':id' => $service_id]);
        $service_name = $stmt->fetchColumn();

        $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
        $stmt->execute([':id' => $service_id]);

        log_activity($pdo, $current_user_id, 'حذف خدمة', "تم حذف الخدمة $service_name.");
        $_SESSION['success_message'] = "تم حذف الخدمة بنجاح.";
        header("Location: index.php?page=manage_services");
        exit;
    }
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>

<div class="container mt-5">
    <div class="card" style="background-color: rgba(255, 255, 255, 0.35);">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">إدارة الخدمات</h3>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-10">
                    <input type="text" id="searchInput" class="form-control" placeholder="ابحث عن خدمة...">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addServiceModal">إضافة خدمة جديدة</button>
                    </div>
                </div>
            <!-- جدول الخدمات -->
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-light">
                    <tr>
                        <th>رقم الخدمة</th>
                        <th>اسم الخدمة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="servicesBody">
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['id']) ?></td>
                            <td><?= htmlspecialchars($service['name']) ?></td>
                            <td>
                                <!-- زر تحديث -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateServiceModal<?= $service['id'] ?>">تحديث</button>
                                <!-- زر حذف -->
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteServiceModal<?= $service['id'] ?>">حذف</button>
                            </td>
                        </tr>

                        <!-- Modal تحديث الخدمة -->
                        <div class="modal fade" id="updateServiceModal<?= $service['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">تحديث الخدمة</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                            <div class="form-group">
                                                <label for="service_name">اسم الخدمة:</label>
                                                <input type="text" class="form-control" name="service_name" value="<?= htmlspecialchars($service['name']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                            <button type="submit" name="edit_service" class="btn btn-primary">تحديث</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal تأكيد الحذف -->
                        <div class="modal fade" id="deleteServiceModal<?= $service['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">تأكيد الحذف</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            هل أنت متأكد من حذف الخدمة "<?= htmlspecialchars($service['name']) ?>"؟
                                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                            <button type="submit" name="delete_service" class="btn btn-danger">حذف</button>
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

<!-- Modal إضافة خدمة جديدة -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة خدمة جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="service_name">اسم الخدمة:</label>
                        <input type="text" class="form-control" name="service_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" name="add_service" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
    &nbsp;
<!-- JavaScript Bootstrap و jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // فلترة البحث في الجدول
    document.getElementById("searchInput").addEventListener("keyup", function() {
        var filter = this.value.toLowerCase();
        var rows = document.getElementById("servicesBody").getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
            var cell = rows[i].getElementsByTagName("td")[1];
            rows[i].style.display = cell && cell.innerText.toLowerCase().includes(filter) ? "" : "none";
        }
    });
</script>

