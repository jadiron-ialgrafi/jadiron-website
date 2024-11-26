<?php
// بدء الجلسة
session_start();

// تفريغ جميع المتغيرات المسجلة في الجلسة
session_unset();

// إتلاف جميع بيانات الجلسة
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header('Location: login.php');
exit;

