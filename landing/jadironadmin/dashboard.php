<?php
require 'config.php';

try {
    // الاتصال بقاعدة بيانات مِراس
    $meraas_config = require 'config.php';
    $meraas_dsn = 'mysql:host=' . $meraas_config['database']['host'] . ';dbname=' . $meraas_config['database']['database'];
    $meraas_pdo = new PDO($meraas_dsn, $meraas_config['database']['username'], $meraas_config['database']['password']);
    $meraas_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // الاتصال بقاعدة بيانات ووردبريس
    $wp_config = $meraas_config;
    $wp_dsn = 'mysql:host=' . $wp_config['wp_database']['host'] . ';dbname=' . $wp_config['wp_database']['database'];
    $wp_pdo = new PDO($wp_dsn, $wp_config['wp_database']['username'], $wp_config['wp_database']['password']);
    $wp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // جلب بيانات الطلبات من قاعدة بيانات مِراس
    $openRequests = $meraas_pdo->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'open'")->fetchColumn();
    $closedRequests = $meraas_pdo->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'closed'")->fetchColumn();
    $receivedRequests = $meraas_pdo->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'received'")->fetchColumn();

    // جلب بيانات الزوار من قاعدة بيانات ووردبريس
    $onlineUsers = $wp_pdo->query("SELECT COUNT(DISTINCT vtr_ip_address) AS onlineUsers FROM ahc_recent_visitors WHERE CONCAT(vtr_date, ' ', vtr_time) >= NOW() - INTERVAL 5 MINUTE")->fetchColumn();
    $todaysVisitors = $wp_pdo->query("SELECT COUNT(DISTINCT vtr_id) AS todaysVisitors FROM ahc_recent_visitors WHERE DATE(vtr_date) = CURDATE()")->fetchColumn();
    $weeklyVisitors = $wp_pdo->query("SELECT COUNT(DISTINCT vtr_id) AS weeklyVisitors FROM ahc_recent_visitors WHERE YEARWEEK(vtr_date, 1) = YEARWEEK(CURDATE(), 1)")->fetchColumn();
    $monthlyVisitors = $wp_pdo->query("SELECT COUNT(DISTINCT vtr_id) AS monthlyVisitors FROM ahc_recent_visitors WHERE MONTH(vtr_date) = MONTH(CURDATE()) AND YEAR(vtr_date) = YEAR(CURDATE())")->fetchColumn();

    // حساب متوسط مدة الطلبات المغلقة
    $stmt_avg_duration = $meraas_pdo->query("SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, closed_at)) AS avg_duration_seconds FROM contact_requests WHERE status = 'closed'");
    $avg_duration_seconds = $stmt_avg_duration->fetchColumn();
    $avg_duration = $avg_duration_seconds ? gmdate("H:i:s", $avg_duration_seconds) : '00:00:00';

    // جلب المواقع التي تشير إلى الزيارات من قاعدة بيانات ووردبريس
    $topReferringSites = $wp_pdo->query("SELECT vtr_referer AS site_name, COUNT(*) AS total_times FROM ahc_recent_visitors WHERE vtr_referer IS NOT NULL AND vtr_referer != '' GROUP BY vtr_referer ORDER BY total_times DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

    // جلب إحصائيات تتبع الزوار من جدول visitor_tracking
    $totalVisits = $meraas_pdo->query("SELECT COUNT(*) FROM visitor_tracking")->fetchColumn();
    $startedFill = $meraas_pdo->query("SELECT COUNT(*) FROM visitor_tracking WHERE started_fill = 1")->fetchColumn();
    $submittedForm = $meraas_pdo->query("SELECT COUNT(*) FROM visitor_tracking WHERE submitted = 1")->fetchColumn();
    $exitedDirectly = $meraas_pdo->query("SELECT COUNT(*) FROM visitor_tracking WHERE exited_directly = 1")->fetchColumn();

    // جلب أكثر المدن زيارة من ووردبريس
    $topCities = $wp_pdo->query("SELECT ahc_city AS city, COUNT(*) AS total_visits FROM ahc_recent_visitors GROUP BY ahc_city ORDER BY total_visits DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    exit;
}
?>
<div class="container mt-5">
    <h1 class="text-center mb-4">لوحة البيانات</h1>

    <!-- قسم البطاقات التفاعلية الرئيسية مع تحسينات الألوان والتظليل -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-lg border-primary animate-card">
                <div class="card-body">
                    <h6>المتصلين حالياً</h6>
                    <h3><strong><?= htmlspecialchars($onlineUsers) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-success animate-card">
                <div class="card-body">
                    <h6>الزوار اليوم</h6>
                    <h3><strong><?= htmlspecialchars($todaysVisitors) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-info animate-card">
                <div class="card-body">
                    <h6>الزوار هذا الأسبوع</h6>
                    <h3><strong><?= htmlspecialchars($weeklyVisitors) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-warning animate-card">
                <div class="card-body">
                    <h6>الزوار هذا الشهر</h6>
                    <h3><strong><?= htmlspecialchars($monthlyVisitors) ?></strong></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم معدل الإنجاز ومتوسط مدة الطلبات المغلقة -->
    <div class="row text-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h6>متوسط مدة الطلبات المغلقة</h6>
                    <h3><strong><?= htmlspecialchars($avg_duration) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h6>معدل الإنجاز</h6>
                    <h3><strong><?= round(($closedRequests / ($openRequests + $closedRequests + $receivedRequests)) * 100, 2) ?>%</strong></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم تتبع الزوار -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-lg border-secondary animate-card">
                <div class="card-body">
                    <h6>إجمالي الزيارات</h6>
                    <h3><strong><?= htmlspecialchars($totalVisits) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-secondary animate-card">
                <div class="card-body">
                    <h6>الزيارات مع تعبئة بيانات</h6>
                    <h3><strong><?= htmlspecialchars($startedFill) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-secondary animate-card">
                <div class="card-body">
                    <h6>النماذج المرسلة</h6>
                    <h3><strong><?= htmlspecialchars($submittedForm) ?></strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-secondary animate-card">
                <div class="card-body">
                    <h6>المغادرين بدون إرسال</h6>
                    <h3><strong><?= htmlspecialchars($exitedDirectly) ?></strong></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- قسم الرسوم البيانية -->
    <div class="row text-center mb-5">
        <div class="col-md-4">
            <h5 class="text-center">حالة الطلبات</h5>
            <div id="requestStatusChart"></div>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">المواقع التي تشير للزيارات</h5>
            <div id="referringSitesChart"></div>
        </div>
        <div class="col-md-4">
            <h5 class="text-center">أكثر المدن زيارة</h5>
            <div id="topCitiesChart"></div>
        </div>
    </div>
</div>

<!-- تضمين مكتبة الرسوم البيانية باستخدام ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // إعداد الرسم البياني لحالة الطلبات
    var optionsRequestStatus = {
        chart: { type: 'donut', height: 350 },
        series: [<?= $openRequests ?>, <?= $closedRequests ?>, <?= $receivedRequests ?>],
        labels: ["مفتوح", "مغلق", "مستلم"],
        colors: ["#F7464A", "#46BFBD", "#FDB45C"]
    };
    new ApexCharts(document.querySelector("#requestStatusChart"), optionsRequestStatus).render();

    // إعداد الرسم البياني للمواقع التي تشير للزيارات
    var optionsReferringSites = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'عدد الزيارات', data: [<?php foreach ($topReferringSites as $site) { echo $site['total_times'] . ','; } ?>] }],
        xaxis: { categories: [<?php foreach ($topReferringSites as $site) { echo '"' . $site['site_name'] . '",'; } ?>] },
        colors: ['#FF5733']
    };
    new ApexCharts(document.querySelector("#referringSitesChart"), optionsReferringSites).render();

    // إعداد الرسم البياني لأكثر المدن زيارة
    var optionsTopCities = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'عدد الزيارات', data: [<?php foreach ($topCities as $city) { echo $city['total_visits'] . ','; } ?>] }],
        xaxis: { categories: [<?php foreach ($topCities as $city) { echo '"' . $city['city'] . '",'; } ?>] },
        colors: ['#1F618D']
    };
    new ApexCharts(document.querySelector("#topCitiesChart"), optionsTopCities).render();
</script>

<style>
    /* تحسين التصميم للبطاقات والرسوم */
    .animate-card { transition: transform 0.2s ease; }
    .animate-card:hover { transform: scale(1.05); }
    .card { border-radius: 10px; }
    .shadow-lg { box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
</style>
