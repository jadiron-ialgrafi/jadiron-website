<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$configPath = '/home/jadirons/config/config.php';
if (!file_exists($configPath)) {
    die("Configuration file not found at: " . $configPath);
}
$config = require $configPath;


try {
    $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


try {
    $stmt = $pdo->query("SELECT platform FROM social_media");
    $socialMedia = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch social media platforms: " . $e->getMessage());
}


try {
    $stmt = $pdo->query("SELECT id, name FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch services: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $service_id = $_POST['service'] ?? null;
    $description = $_POST['description'] ?? '';
    $source = $_POST['platform'] ?? 'unknown';
    $contact_method = $_POST['contact_method'] ?? null;
    $contact_time = $_POST['contact_time'] ?? null;


    if (!$name || !$phone || !$service_id || !$contact_method || !$contact_time) {
        die("يرجى تعبئة جميع الحقول المطلوبة.");
    }


    $contact_method_translated = ($contact_method === 'email') ? 'البريد الإلكتروني' : 'الجوال';
    $contact_time_translated = ($contact_time === 'morning') ? 'صباحاً' : 'مساءً';


    try {
        $stmt = $pdo->prepare("SELECT name FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $service_name = $stmt->fetchColumn();
        if (!$service_name) {
            die("الخدمة المحددة غير موجودة.");
        }
    } catch (Exception $e) {
        die("Failed to fetch service name: " . $e->getMessage());
    }


    try {
        $stmt = $pdo->prepare("INSERT INTO contact_requests (name, email, phone, service_id, description, source, contact_method, contact_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'open')");
        $stmt->execute([$name, $email, $phone, $service_id, $description, $source, $contact_method, $contact_time]);
    } catch (Exception $e) {
        die("Failed to insert contact request: " . $e->getMessage());
    }


    $subject = "طلب تواصل جديد";
    $messageHTML = "
        <div style='background-color: #f4f6f9; padding: 20px; border-radius: 10px; font-family: Arial, sans-serif; direction: rtl; text-align: right;'>
            <div style='background: #ffffff; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: auto;'>
                <h2 style='color: #003366; text-align: center;'>طلب تواصل جديد</h2>
                <p><strong>الاسم:</strong> {$name}</p>
                <p><strong>البريد الإلكتروني:</strong> {$email}</p>
                <p><strong>رقم الجوال:</strong> {$phone}</p>
                <p><strong>الخدمة المطلوبة:</strong> {$service_name}</p>
                <p><strong>وصف الخدمة:</strong> {$description}</p>
                <p><strong>منصة التواصل الاجتماعي:</strong> {$source}</p>
                <p><strong>طريقة التواصل المفضلة:</strong> {$contact_method_translated}</p>
                <p><strong>الوقت المناسب:</strong> {$contact_time_translated}</p>
            </div>
        </div>
    ";


    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $config['mail']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['mail']['username'];
        $mail->Password = $config['mail']['password'];
        $mail->SMTPSecure = $config['mail']['encryption'];
        $mail->Port = $config['mail']['port'];
        $mail->setFrom($config['mail']['from_address'], $config['mail']['from_name']);


        $emailRecipients = [];
        $globalStmt = $pdo->query("SELECT email FROM global_emails");
        $globalEmails = $globalStmt->fetchAll(PDO::FETCH_COLUMN);

        $serviceStmt = $pdo->prepare("SELECT email FROM service_emails WHERE service_id = ?");
        $serviceStmt->execute([$service_id]);
        $serviceEmails = $serviceStmt->fetchAll(PDO::FETCH_COLUMN);

        $emailRecipients = array_unique(array_merge($globalEmails, $serviceEmails));


        foreach ($emailRecipients as $recipient) {
            $mail->addAddress($recipient);
        }

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $messageHTML;


        if ($mail->send()) {
            echo "تم إرسال طلب التواصل بنجاح.";
        } else {
            echo "فشل إرسال البريد.";
        }
    } catch (Exception $e) {
        die("Failed to send email: " . $mail->ErrorInfo);
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <meta name="description" content="جديرون للاستشارات الإدارية والمالية">


    <meta property="og:site_name" content="" />
    <meta property="og:site" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="" />
    <meta property="og:url" content="" />
    <meta name="twitter:card" content="summary_large_image">

    <title>جديرون</title>

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Almarai&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha384-L6K3E7D8H9Jw+q29I7fHtYgKfGc8W6IskB3PRx8xSTyQnAsurcK/JsNVsOfbD/jR" crossorigin="anonymous">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome-all.min.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/all.min.css">



    <link rel="icon" href="images/logo.png">
</head>

<body class="loading">


    <div id="preloader">
        <div id="loader"></div>
    </div>

    <nav id="navbar" class="navbar navbar-expand-lg fixed-top navbar-dark" aria-label="Main navigation">
        <div class="container">
            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault" style="right:20%;">
                <ul class="navbar-nav ms-auto navbar-nav-scroll">
                    <li>
                        <img src="images/title.png" style="width:100px;padding-top:5px">
                    </li>
                    <li>
                        <p class="nav-link" aria-current="page" href="#header"></p>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="goToTop">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">خدماتنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#vision">رؤيتنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#clients">عملائنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">تواصل معنا</a>
                    </li>
                </ul>
            </div> 
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse"
                aria-label="Toggle navigation" style="margin-right:20px">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- <a class="navbar-brand logo-text" href="index.html">جديرون</a> -->
            <span class="nav-item social-icons">
   
                <span class="fa-stack">
                    <a href="https://www.instagram.com/jadiron1/" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-instagram fa-stack-1x"></i>
                    </a>
                </span>
      
                <span class="fa-stack">
                    <a href="https://x.com/Jadiron1" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-x-twitter fa-stack-1x"></i>
                    </a>
                </span>
                <!-- Snapchat Icon
                <span class="fa-stack">
                    <a href="https://snapchat.com/t/MVzlvBrv" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-snapchat fa-stack-1x"></i>
                    </a>
                </span> -->

                <span class="fa-stack">
                    <a href="https://wa.me/message/FENIFAOHURFAF1" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-whatsapp fa-stack-1x"></i>
                    </a>
                </span>
            </span>
        </div>
    </nav>


    <div id="headerDesktop" class="header-desktop">
        <header id="header" class="header" style="height: 50vh; ">
            <div class="header-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 d-flex flex-column align-items-center justify-content-center">

                            <div style="padding-top:85px">
                                <h1 class="h1" style="color: #ffffff; font-family: 'Cairo', sans-serif; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); font-size: 3vw; text-align: center;">
                                    مستشارك الجدير لريادة أعمالك بجدارة
                                </h1>
                            </div>
                            <div style="padding-top:50px"><button
                                    onclick="document.querySelector('#contact').scrollIntoView({ behavior: 'smooth' });"
                                    style="background-color: #32a05f; color: white; font-family: 'Cairo', sans-serif; font-size: 18px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                                    احجز استشارتك الآن
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="video-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
                <video autoplay loop muted id="video-background" poster="images/header-background.jpg" playsinline style="width: 100%; height: 100%; object-fit: cover;">
                    <source src="images/header-background-video.mp4" type="video/mp4" />
                </video>
            </div>
        </header>
    </div>

    <div id="headerMobile" class="header-mobile">
        <header id="header" class="header">
            <div class="header-content" style="background: #161223f5; height: 100vh;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="cardx" style="opacity: 0.8;">
                                <div class="wrap">
                                    <span class="cardcover" style="font-family: 'Cairo', sans-serif; font-size: 18px;">بوصلة
                                        الريادة</span>
                                </div>
                                <br>
                                <img src="images/logo.png" style="width: 300px; height: 300px;">
                                <br>

                                <h1 class="h1"
                                    style="color: #1C3656; font-family: 'Cairo', sans-serif; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); margin-top: 0;">
                                    مستشارك الجدير لريادة أعمالك بجدارة
                                </h1>
                                <br>
                                <hr style="border: 1px solid #ddd; margin: 15px 0;">

                                <button
                                    onclick="document.querySelector('#contact').scrollIntoView({ behavior: 'smooth' });"
                                    style="background-color: #32a05f; color: white; font-family: 'Cairo', sans-serif; font-size: 18px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                                    احجز استشارتك الآن
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="card" style="height:50vh"></div>-->
        </header>
    </div>

    <section id="about" class="about-statistics">
        <div class="container">
            <div class="row">
                <div id="aboutlogo" class="col-lg-3 col-12 stats-content d-flex justify-content-center align-items-center order-1 order-lg-2">
                    <div class="cardx" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); border-radius: 8px;margin-bottom: -10px;background: transparent;">
                        <div class="wrap">
                            <span class="cardcover" style="font-family: 'Cairo', sans-serif; font-size: 18px;">بوصلة
                                الريادة</span>
                        </div>
                        <br>
                        <img src="images/logo.png" alt="شعار الشركة" style="width: 300px; height: 300px; padding: 15px;">
                        <br>
                    </div>
                </div>
                <div class="col-lg-8 col-12 about-content order-2 order-lg-1">
                    <h2 class="section-title">من نحن
                        <img src="images/arrow-icon.png" alt="Arrow Icon" class="arrow-icon">
                    </h2>
                    <p class="about-text"><strong>
                            شركة سعودية متخصصة في مجال الاستشارات والخدمات الإدارية والمالية والتسويقية والقانونية والتقنية
                            والتدريبية، حيث نقدم خدماتنا <strong>بأعلى مستوى</strong> تحت إشراف خبراء ومهنيون سعوديون ذوي مؤهلات أكاديمية
                            وخبرات عملية <strong>لضمان رضا عملائنا وبناء علاقة طويلة الأمد</strong> معهم.
                    </p></strong>
                    <p class="about-text"><strong>
                            ولأن <strong>طموحنا يعانق عنان السماء وهمتنا كجبل طويق</strong>، نطمح أن نكون <strong>مستشارك الجدير</strong> لريادة أعمالك
                            بجدارة، و<strong>المحفز الأساسي</strong> لأي شراكة ناجحة.
                    </p></strong>
                </div>
                <div class="col-lg-1 col-12 about-content order-2 order-lg-1"></div>
            </div>
        </div>
    </section>

    <section class="status-section">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="counter-item">
                    <i class="icon icon-happy-clients"></i>
                    <div class="counter-value" data-target="148">0</div>
                    <p class="counter-label">الاستشارات</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="counter-item">
                    <i class="icon icon-solved-issues"></i>
                    <div class="counter-value" data-target="102">0</div>
                    <p class="counter-label">العملاء</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="counter-item">
                    <i class="icon icon-good-ratings"></i>
                    <div class="counter-value" data-target="78">0</div>
                    <p class="counter-label">دراسة الجدوى</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="counter-item">
                    <i class="icon icon-case-studies"></i>
                    <div class="counter-value" data-target="21">0</div>
                    <p class="counter-label">دورات تدريبية</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services-section">
        <div class="container"><br>
            <div class="arrows-container" style="width: 13%; height: 8%;">

            </div>
            <h2 class="section-title">خدماتنا
                <img src="images/arrow-icon.png" alt="Arrow Icon" class="arrow-icon">
            </h2>
            <p class="section-description" style="font-size: 20px;">
                في جديرون نسعى لتخفيف حمل الخدمات الإدارية والمالية لعملائنا بكل فعالية وتميز، لذا نقدم لكم مجموعة خدمات
                تكاملية وحلول نموذجية لرفع الطاقة الإنتاجية.
            </p>

            <div class="service-item ">
                <div class="service-text">
                    <h3>دراسة الجدوى الاقتصادية تحت إشراف خبراء</h3>
                    <p>
                        عندك فكرة مشروع وتبغا تدرس جدواها؟ في جديرون نمتلك مجموعة من الخبرات القادرة على تقديم دراسة
                        جدوى تفصيلية ومنظمة تساعد في التعرف على مدى إمكانية تحقيق المشروع للأرباح المالية المرجوة منه،
                        ونقدم خدمات استشارية في إدارة المشاريع من تحليل المخاطر وتخطيط المشروع، إلى إدارة الموارد
                        ومراقبة التقدم، وإعادة الهيكلة للشركات التي ترغب في تحسين أدائها وتعزيز تنافسيتها.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s1.jpg" alt="دراسة الجدوى الاقتصادية">
                </div>
            </div>

            <div class="service-item reverse ">
                <div class="service-text">
                    <h3>المحاسبة السحابية</h3>
                    <p>
                        نساعدك على تتبع معاملاتك المالية وتسجيلها بشكل منظم، وعرض جميع التقارير المالية من قائمة ميزان
                        المراجعة، والميزانية العمومية، والارباح والخسائر، مما يتيح التحقق الدقيق لأرقامك الرئيسية. كما
                        اننا نقدم خدمات إقفال السنة المالية، وتنصيب البرامج المحاسبية والمالية.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s2.jpg" alt="المحاسبة السحابية">
                </div>
            </div>

            <div class="service-item">
                <div class="service-text">
                    <h3>استشارات زكوية وضريبية</h3>
                    <p>
                        نسير معكم خطوة بخطوة للتسجيل لدى الهيئة العامة للزكاة والدخل، وإعداد الإقرارات الزكوية، وضريبة
                        الدخل، وضريبة الاستقطاع، وضريبة القيمة المضافة بما يتوافق مع الأنظمة بكل سهولة لتلافي غرامات
                        تأخير عدم تقديم الإقرار في الوقت المناسب.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s3.jpg" alt="استشارات زكوية وضريبية">
                </div>
            </div>

            <div class="service-item reverse">
                <div class="service-text">
                    <h3>استشارات مالية وإدارية</h3>
                    <p>
                        نساعدكم في المجال المالي عبر تقديم استشارات مالية متخصصة في إدارة الأموال والاستثمارات بفعالية
                        واستدامة، وتحليل الأداء والتخطيط للميزانية. وأيضًا عن طريق تقديم استشارات الموارد البشرية لتحقيق
                        اقصى استفادة من الكوادر والطاقات البشرية. كما اننا نقدم استشارات تشغيلية لتحسين كفاءة العمليات
                        الداخلية وتحقيق التفوق التشغيلي.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s4.jpg" alt="استشارات مالية وإدارية">
                </div>
            </div>

            <div class="service-item">
                <div class="service-text">
                    <h3>خدمات تدريب للمحاسبين والخريجين</h3>
                    <p>
                        دعم سوق العمل بالدورات التدريبية للمحاسبين والخريجين والطلبة، وتأهيلهم لسوق العمل بشهادات خبرة
                        عملية مع ترشيحهم للعمل في نخبة الشركات والمؤسسات.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s5.jpg" alt="خدمات تدريب للمحاسبين والخريجين">
                </div>
            </div>

            <div class="service-item reverse">
                <div class="service-text">
                    <h3>استشارات تسويقية</h3>
                    <p>
                        تقديم خدمات دراسة التسويق وبحوث السوق، وتقديم الخدمات الاستشارية في مجال الاتصالات التسويقية،
                        والعلاقات العامة، للمساعدة في فهم احتياجات العملاء وتحديد فرص السوق المستقبلية، وبناء وتعزيز
                        هوية العلامة التجارية وتحديد أكثر الطرق فعالية لإيصال الرسائل التسويقية للجمهور المستهدف.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s6.jpg" alt="استشارات تسويقية">
                </div>
            </div>

            <div class="service-item">
                <div class="service-text">
                    <h3>استشارات قانونية</h3>
                    <p>
                        نساعد في إعادة هيكلة المنشآت وخدمات الاندماج والاستحواذ، ونقدم الخدمات القضائية كخدمات حل
                        النزاعات، والتحقيق والاستقصاء المالي، وإعداد تقارير التحليل الجنائي المالي، كما نقدم أيضا خدمات
                        الإفلاس من إدارة إجراءات التسوية الوقائية، وإدارة إجراءات إعادة التنظيم المالي، وإجراءات التصفية
                        وذلك بالتعاون مع نخبة من المحامين والموثقين.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s7.jpg" alt="استشارات قانونية">
                </div>
            </div>

            <div class="service-item reverse">
                <div class="service-text">
                    <h3>استشارات تقنية</h3>
                    <p>
                        نقدم في جديرون استشارات تقنية متخصصة تشمل تطوير البرمجيات وإدارة المشاريع لضمان تنفيذ فعال
                        وتحقيق أهداف الأعمال. كما نحرص على تصميم وإدارة أنظمة نقاط البيع (POS) لتحسين كفاءة عمليات البيع
                        والتعاملات التجارية.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s8.jpg" alt="استشارات تقنية">
                </div>
            </div>

            <div class="service-item">
                <div class="service-text">
                    <h3>خدمات التصميم</h3>
                    <p>
                        في جديرون، نقدم حلول تصميم متكاملة تضمن لك هوية بصرية قوية وجذابة. تشمل خدماتنا تصميم شعار
                        احترافي يعكس هوية علامتك التجارية، اختيار ألوان وخطوط متناسقة، وتصميم بطاقات العمل. كما نتميز
                        بتصميم منشورات مخصصة لمنصات التواصل الاجتماعي.
                    </p>
                </div>
                <div class="service-image">
                    <img src="images/s9.jpeg" alt="خدمات التصميم">
                </div>
            </div>
        </div>
    </section>


    <section id="vision" class="dynamic-info-section">
        <div class="container">
            <div class="sec-title">
                <h2 class="section-title">رؤية واضحة
                    <img src="images/arrow-icon.png" alt="Arrow Icon" class="arrow-icon">
                </h2>
                <p class="section-subtitle" style="font-size: 20px;">قيمنا، رؤيتنا، ورسالتنا لتحقيق التميز والريادة</p>

            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="card-face front">
                            <div class="card-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h4 class="card-title">رؤيتنا</h4>
                        </div>
                        <div class="card-face back">
                            <p class="card-text">شركة مهنية رائدة في تقديم الاستشارات الإدارية والمالية للوصول إلى التميز، وتلبية كافة احتياجات عملائنا لتقديم خدمات استثنائية.</p>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="card-face front">
                            <div class="card-icon">
                                <i class="fas fa-compass"></i>
                            </div>
                            <h4 class="card-title">رسالتنا</h4>
                        </div>
                        <div class="card-face back">
                            <p class="card-text">تقديم خدمات نوعية في مجال الاستشارات وتطوير الأعمال لرفع الطاقة الإنتاجية، ومنح الكوادر المهنية الفرص التدريبية.</p>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="card-face front">
                            <div class="card-icon">
                                <i class="fas fa-gem"></i>
                            </div>
                            <h4 class="card-title">قيمنا</h4>
                        </div>
                        <div class="card-face back">
                            <p class="card-text">قيمنا هي لبنة أساس عملنا، فنحن نتعهد بتقديم خدماتنا بأعلى جودة ونلتزم بالمسؤولية تجاه عملائنا.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    



    <section id="clients" class="dynamic-clients-section" style="direction: rtl;">
        <h2 class="section-title" style="padding-top: 10px;">عملائنا
            <img src="images/arrow-icon.png" alt="Arrow Icon" class="arrow-icon">
        </h2>
        <p class="section-description">
            نحن نعتز بشراكتنا مع المؤسسات والشركات الكبرى، ونتعامل مع جميع عملائنا كشركاء حقيقيين. نسعى لبناء علاقات متينة قائمة على التعاون المستمر، ونسعد بأن نكون جزءًا من كل نجاح يحققونه. </p>

        <div class="clients-container">
            <div class="client-logo"><img src="images/c1.jpeg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c2.jpeg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c3.jpeg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c4.jpg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c5.jpeg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c6.jpg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c7.png" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c8.jpg" style="width: 150px;height: 80px;"></div>
            <div class="client-logo"><img src="images/c9.jpeg" style="width: 150px;height: 80px;"></div>


        </div>
    </section>
    
     <section id="contact" class="contact-page-section">
        <div class="container">
            <h2 class="section-title" style="padding-top: 10px;">تواصل معنا
                <img src="images/arrow-icon.png" alt="Arrow Icon" class="arrow-icon">
            </h2>
            <div class="inner-container">
                <div class="row clearfix">
                    <div class="col-lg-12 col-12">
                        <div class="contact-form">
                            <form id="contactForm" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name"><span class="required">*</span>الاسم:</label>
                                            <input type="text" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone"><span class="required">*</span>رقم الجوال:</label>
                                            <input type="text" id="phone" name="phone" placeholder="05XXXXXXXX" required>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">البريد الإلكتروني:</label>
                                            <input type="email" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="platform">اختر منصة التواصل الاجتماعي:</label>
                                            <select id="platform" name="platform">
                                                <?php foreach ($socialMedia as $media): ?>
                                                    <option value="<?= htmlspecialchars($media['platform']) ?>"><?= htmlspecialchars($media['platform']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service"><span class="required">*</span>الخدمة المطلوبة:</label>
                                            <select id="service" name="service" required>
                                                <?php foreach ($services as $service): ?>
                                                    <option value="<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">وصف الخدمة:</label>
                                            <textarea id="description" name="description"></textarea>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>الوقت المناسب:</label>
                                            <div>
                                                <input type="radio" id="time-morning" name="contact_time" value="morning" required>
                                                <label for="time-morning">صباحًا</label>
                                                <input type="radio" id="time-evening" name="contact_time" value="evening" required>
                                                <label for="time-evening">مساءً</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>طريقة التواصل المفضلة:</label>
                                            <div>
                                                <input type="radio" id="contact-email" name="contact_method" value="email" required>
                                                <label for="contact-email">البريد الإلكتروني</label>
                                                <input type="radio" id="contact-phone" name="contact_method" value="phone" required>
                                                <label for="contact-phone">الجوال</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <button type="submit" class="theme-btn">إرسال</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="footer" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 footer-col">
                    <img src="images/title.png" style="width:100px;padding-top:5px;padding-bottom:5px;">
                    <p>
                        شركة سعودية رائدة تقدم الاستشارات والخدمات الإدارية والمالية والتسويقية والتدريبية بجودة عالية. نسعى لبناء علاقات طويلة الأمد مع عملائنا من خلال تقديم حلول متكاملة وفعالة.
                    </p>
                </div>

                <div class="col-lg-4 footer-col">
                    <ul class="list-unstyled li-space-lg">
                        <li><a href="#" id="goToTop"> الرئيسية</a></li>
                        <li><a href="#about">من نحن</a></li>
                        <li><a href="#services">خدماتنا</a></li>
                        <li><a href="#vision">رؤيتنا</a></li>
                        <li><a href="#contact">تواصل معنا</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 footer-col">
                    <h6>تابعنا على</h6>
                    <div class="social-icons">
                        <span class="fa-stack">
                            <a href="https://www.instagram.com/jadiron1/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://x.com/Jadiron1" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-x-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://snapchat.com/t/MVzlvBrv" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-snapchat fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://wa.me/message/FENIFAOHURFAF1" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-whatsapp fa-stack-1x"></i>
                            </a>
                        </span>
                    </div>
                    <p>تواصل معنا عبر البريد: <a href="mailto:contact@site.com"><strong>info@jadiron.sa</strong></a></p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center copyright">
                    <p> <a href="#">جديرون التقنية </a> - جميع الحقوق محفوظة</p>
                </div>
            </div>
        </div>
    </section>


    <button onclick="topFunction()" id="myBtn">
        <img src="images/up-arrow.png" alt="alternative">
    </button>
    <script>
            document.addEventListener("DOMContentLoaded", function () {
                const preloader = document.getElementById("preloader");
            
                document.body.classList.add("loading");
            
                window.addEventListener("load", function () {
                    preloader.classList.add("hidden");
                    document.body.classList.remove("loading");
                });
            });

    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/swiper.min.js"></script>
    <script src="js/purecounter.min.js"></script> 
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="/vendor/jquery.min.js"></script>
    <script src="/vendor/nouislider.min.js"></script>
    <script src="/vendor/jquery.validate.min.js"></script>
</body>

</html>