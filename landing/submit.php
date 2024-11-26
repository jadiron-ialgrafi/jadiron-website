<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$config = require 'jadironadmin/config.php';
$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['database'] . ';port=' . $config['database']['port'];
$pdo = new PDO($dsn, $config['database']['username'], $config['database']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service_id = $_POST['service'];
    $description = $_POST['description'];
    $source = $_POST['platform'] ?? 'unknown';
    $contact_method = $_POST['contact_method'];
    $contact_time = $_POST['contact_time'];


    $contact_method_translated = ($contact_method == 'email') ? 'البريد الإلكتروني' : 'الجوال';
    $contact_time_translated = ($contact_time == 'morning') ? 'صباحاً' : 'مساءً';

    // Fetch service name
    $stmt = $pdo->prepare("SELECT name FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service_name = $stmt->fetchColumn();

    // Insert the request into contact_requests table
    $stmt = $pdo->prepare("INSERT INTO contact_requests (name, email, phone, service_id, description, source, contact_method, contact_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'open')");
    $stmt->execute([$name, $email, $phone, $service_id, $description, $source, $contact_method, $contact_time]);

    // Email content
    $subject = "Customer Contact Request Received";
    $message = "
        <div style='background-color: #f4f6f9; padding: 20px; border-radius: 10px; font-family: Arial, sans-serif; direction: rtl; text-align: right;'>
            <div style='background: #ffffff; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: auto;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='https://jadiron.sa/landing/img/logo.png' alt='شعار جديرون' style='max-width: 100px;'>
                </div>
                <h2 style='color: #003366; text-align: center; font-size: 24px; margin-bottom: 10px;'>طلب تواصل جديد</h2>
                <hr style='border: none; border-top: 2px solid #003366; margin: 20px 0;'>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>الاسم:</strong> {$name}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>البريد الإلكتروني:</strong> {$email}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>رقم الجوال:</strong> {$phone}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>الخدمة المطلوبة:</strong> {$service_name}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>وصف الخدمة:</strong> {$description}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>منصة التواصل الإجتماعي:</strong> {$source}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>طريقة التواصل المفضلة:</strong> {$contact_method_translated}</p>
                <p style='font-size: 16px; color: #333; line-height: 1.5;'><strong style='color: #003366;'>الوقت المناسب:</strong> {$contact_time_translated}</p>
                <hr style='border: none; border-top: 2px solid #ddd; margin: 20px 0;'>
                <footer style='text-align: center; color: #777; font-size: 14px;'>
                    <p style='margin-bottom: 0;'>هذه الرسالة تم إرسالها تلقائيًا - تواصل جديرون - جميع الحقوق محفوظة © 2024</p>
                </footer>
            </div>
        </div>
    ";

    // Fetch email recipients from the database
    $emailRecipients = [];

    // Fetch global emails
    $globalStmt = $pdo->query("SELECT email FROM global_emails");
    $globalEmails = $globalStmt->fetchAll(PDO::FETCH_COLUMN);
    $emailRecipients = array_merge($emailRecipients, $globalEmails);

    // Fetch service-specific emails
    $serviceStmt = $pdo->prepare("SELECT email FROM service_emails WHERE service_id = ?");
    $serviceStmt->execute([$service_id]);
    $serviceEmails = $serviceStmt->fetchAll(PDO::FETCH_COLUMN);
    $emailRecipients = array_merge($emailRecipients, $serviceEmails);

    // Setup PHPMailer
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
        foreach ($emailRecipients as $recipient) {
            $mail->addAddress($recipient);
        }
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $message;

        // Send the email and redirect on success
        if ($mail->send()) {
            header("Location: success.php?success=1");
            exit;
        } else {
            echo "فشل إرسال البريد الإلكتروني.";
        }
    } catch (Exception $e) {
        echo "فشل إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
    }
}
?>
