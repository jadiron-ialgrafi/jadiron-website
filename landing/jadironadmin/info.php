<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// $servername = "localhost";
// $username = "jadirons_admin";
// $password = "Eb@1122335566";  // تأكد من أن هذا مطابق لما في config.php
// $dbname = "jadirons_wp_umqjj";

// // إنشاء الاتصال
// //$conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
// } else {
//     echo "تم الاتصال بنجاح";
// }

// // التحقق من الاتصال
// if ($conn->connect_error) {
//     die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
// }

// // استعلام لجلب عدد الزوار من جدول ahc_daily_visitors_stats
// $sql = "SELECT SUM(vst_visitors) AS total_visitors FROM ahc_daily_visitors_stats";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     // جلب عدد الزوار
//     while($row = $result->fetch_assoc()) {
//         $total_visitors = $row["total_visitors"];
//     }
// } else {
//     $total_visitors = 0;
// }

// // إغلاق الاتصال بقاعدة البيانات
// $conn->close();
?>

<div class="content-wrapper">
    <!-- بطاقة المزايا الرئيسية -->
    <div class="feature-card">
        <i class="fas fa-trophy feature-icon"></i>
        <div class="feature-background"></div>
        <h3>المزايا الرئيسية</h3>
        <p><strong>لوحة تحكم متكاملة:</strong> تتيح "مِراس" لوحة تحكم ديناميكية تعرض بيانات فورية تسهل عملية اتخاذ القرارات المستندة إلى بيانات حقيقية، مما يعزز كفاءة الإدارة ويساعد على تحقيق الأهداف.</p>
        <p><strong>إدارة شاملة للمستخدمين والصلاحيات:</strong> تخصيص الوصول للمستخدمين بناءً على الأدوار والصلاحيات، مما يسهم في أمان وسلاسة النظام.</p>
        <p><strong>إحصاءات تفاعلية:</strong> نظرة عامة على التقدم في الأداء من خلال رسوم بيانية متجددة تتيح المتابعة الدقيقة.</p>
        <p><strong>نظام متكامل للطلبات:</strong> يسمح بتتبع الطلبات بدءًا من الإنشاء حتى الإكمال، مع إمكانية مراجعة جميع العمليات.</p>
    </div>

    <!-- بطاقة الرؤية المستقبلية -->
    <div class="feature-card">
        <i class="fas fa-bullseye feature-icon"></i>
        <div class="feature-background"></div>
        <h3>الرؤية المستقبلية</h3>
        <p>نسعى في "مِراس" إلى توفير أدوات رقمية متقدمة تلبي احتياجات المؤسسات في المستقبل، من خلال تعزيز التحليل الذكي وتطوير حلول مبتكرة تُسهل متابعة سير العمل وتحليل الأداء بطرق تفاعلية ومرنة.</p>
    </div>
</div>

<!-- الفوتر -->
<footer>
    © 2024 منصة مراس - جميع الحقوق محفوظة
</footer>

<!-- سكربتات جافا سكريبت -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

