<?php
session_start();

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// اتصال بقاعدة البيانات
$config = require 'config.php';
$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
$pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// إضافة أو تعديل البيانات
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['platform'], $_POST['url'])) {
        $platform = $_POST['platform'];
        $url = $_POST['url'];

        if ($platform === 'other' && !empty($_POST['platform_other'])) {
            $platform = $_POST['platform_other'];
        }

        $stmt = $pdo->prepare("INSERT INTO social_media (platform, url, added_by_admin) VALUES (?, ?, 1)");
        if ($stmt->execute([$platform, $url])) {
            $message = "تمت إضافة المنصة بنجاح!";
        } else {
            $message = "حدث خطأ أثناء إضافة المنصة.";
        }
    } elseif (isset($_POST['delete_platform'])) {
        $platform_id = $_POST['platform_id'];
        $stmt = $pdo->prepare("DELETE FROM social_media WHERE id = ?");
        $stmt->execute([$platform_id]);
        $message = "تم حذف المنصة بنجاح.";
    } elseif (isset($_POST['landing_page_title'])) {
        // حفظ عنوان صفحة اللاندينق بيج
        $landingPageTitle = $_POST['landing_page_title'];
        file_put_contents('landing_page_title.txt', $landingPageTitle);
        $message = "تم تحديث عنوان صفحة اللاندينق بنجاح.";
    }
}

// جلب المنصات الاجتماعية
$stmt = $pdo->query("SELECT * FROM social_media");
$socialMedia = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

// قراءة عنوان صفحة اللاندينق بيج
$landingPageTitle = file_exists('landing_page_title.txt') ? file_get_contents('landing_page_title.txt') : 'عنوان افتراضي';

?>

<div class="container my-5">
    <h2 class="mb-4 text-center">لوحة التحكم</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- تعديل عنوان صفحة اللاندينق بيج -->
    <div class="card card-custom mb-4">
        <h5>تعديل عنوان صفحة اللاندينق بيج</h5>
        <form method="POST">
            <div class="mb-3">
                <label>عنوان اللاندينق بيج</label>
                <input type="text" name="landing_page_title" value="<?= htmlspecialchars($landingPageTitle) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </form>
    </div>

    <!-- إدارة منصات التواصل الاجتماعي -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom">
                <h5>إدارة منصات التواصل الاجتماعي</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label>المنصة</label>
                        <select name="platform" class="form-select mb-2" id="platformSelect">
                            <option value="Snapchat">Snapchat</option>
                            <option value="Twitter(X)">Twitter(X)</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Linkedin">LinkedIn</option>
                            <option value="other">أخرى</option>
                        </select>
                        <input type="text" name="platform_other" placeholder="أضف منصة جديدة" class="form-control mb-2 d-none" id="platformOther">
                    </div>
                    <div class="mb-3">
                        <label>الرابط</label>
                        <input type="url" name="url" placeholder="https://example.com/username" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </form>
                <ul class="list-group mt-3">
                    <?php if (!empty($socialMedia)): ?>
                        <?php foreach ($socialMedia as $media): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($media['platform']) ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="platform_id" value="<?= $media['id'] ?>">
                                    <button type="submit" name="delete_platform" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">لا توجد منصات مضافة</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- إعدادات عامة -->
        <div class="col-md-6">
            <div class="card card-custom">
                <h5>الإعدادات العامة</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="logoInput" class="form-label">تغيير الشعار</label>
                        <input type="file" class="form-control" id="logoInput" name="logo">
                    </div>
                    <div class="mb-3">
                        <label for="primaryColor" class="form-label">اللون الأساسي</label>
                        <input type="color" class="form-control form-control-color" id="primaryColor" name="primary_color">
                    </div>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('#platformSelect').addEventListener('change', function() {
        const otherField = document.getElementById('platformOther');
        if (this.value === 'other') {
            otherField.classList.remove('d-none');
            otherField.required = true;
        } else {
            otherField.classList.add('d-none');
            otherField.required = false;
        }
    });
</script>
