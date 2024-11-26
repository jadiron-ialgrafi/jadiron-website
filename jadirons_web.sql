-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 26 نوفمبر 2024 الساعة 15:50
-- إصدار الخادم: 8.0.40
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jadirons_web`
--

-- --------------------------------------------------------

--
-- بنية الجدول `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(1, 10, 'حذف إيميل', 'تم حذف الإيميل ID 8 من الخدمة.', '2024-10-25 07:34:33'),
(2, 10, 'إضافة إيميل', 'تمت إضافة الإيميل it@jadiron.sa للخدمة ID 10.', '2024-10-25 07:35:01'),
(3, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa للخدمة ID 1.', '2024-10-25 07:35:32'),
(4, 10, 'حذف إيميل', 'تم حذف الإيميل ID 10 من الخدمة.', '2024-10-25 07:35:39'),
(5, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa في القائمة العامة.', '2024-10-25 07:35:55'),
(6, 10, 'حذف إيميل', 'تم حذف الإيميل ID 3 من القائمة العامة.', '2024-10-25 07:39:49'),
(7, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa في القائمة العامة.', '2024-10-25 07:40:33'),
(8, 10, 'حذف إيميل', 'تم حذف الإيميل ID 4 من القائمة العامة.', '2024-10-25 07:41:06'),
(9, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa في القائمة العامة.', '2024-10-25 07:41:45'),
(56, 10, 'إضافة مستخدم', 'تم إضافة المستخدم hr@jadiron.sa بدور admin.', '2024-10-26 03:59:02'),
(57, 10, 'حذف إيميل', 'تم حذف الإيميل info@jadiron.sa من القائمة العامة.', '2024-10-26 03:59:29'),
(58, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa للخدمة دراسة الجدوى الاقتصادية.', '2024-10-26 03:59:45'),
(59, 10, 'حذف إيميل', 'تم حذف الإيميل info@jadiron.sa من الخدمة دراسة الجدوى الاقتصادية.', '2024-10-26 03:59:49'),
(60, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa إلى القائمة العامة.', '2024-10-26 04:00:07'),
(61, NULL, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم hr@jadiron.sa إلى manager.', '2024-10-26 04:04:23'),
(65, 10, 'إضافة مستخدم', 'تم إضافة المستخدم hr@jadiron.sa بدور manager.', '2024-10-26 04:14:34'),
(67, 10, 'إضافة مستخدم', 'تم إضافة المستخدم hr@jadiron.sa بدور manager.', '2024-10-26 04:17:30'),
(68, NULL, 'حذف مستخدم', 'تم حذف المستخدم hr@jadiron.sa.', '2024-10-26 04:17:33'),
(69, 10, 'إضافة مستخدم', 'تم إضافة المستخدم hr@jadiron.sa بدور manager.', '2024-10-26 04:20:49'),
(70, 10, 'حذف مستخدم', 'تم حذف المستخدم hr@jadiron.sa.', '2024-10-26 04:20:59'),
(71, 10, 'إضافة مستخدم', 'تم إضافة المستخدم sultan@jadiron.sa بدور manager.', '2024-10-26 04:35:57'),
(72, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم sultan@jadiron.sa إلى manager.', '2024-10-26 04:36:10'),
(73, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم sultan@jadiron.sa إلى manager.', '2024-10-26 04:36:50'),
(74, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم sultan@jadiron.sa إلى manager.', '2024-10-26 04:36:58'),
(75, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم sultan@jadiron.sa إلى admin.', '2024-10-26 04:37:51'),
(76, 10, 'إضافة مستخدم', 'تم إضافة المستخدم hr@jadiron.sa بدور user.', '2024-10-26 04:38:12'),
(77, 10, 'إضافة مستخدم', 'تم إضافة المستخدم finance@jadiron.sa بدور manager.', '2024-10-26 04:38:45'),
(78, 10, 'حذف إيميل', 'تم حذف الإيميل info@jadiron.sa من القائمة العامة.', '2024-10-26 04:41:56'),
(79, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa إلى القائمة العامة.', '2024-10-26 04:42:21'),
(80, 10, 'حذف إيميل', 'تم حذف الإيميل info@jadiron.sa من القائمة العامة.', '2024-10-26 04:42:30'),
(81, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa إلى القائمة العامة.', '2024-10-26 04:42:54'),
(82, 10, 'حذف إيميل', 'تم حذف الإيميل info@jadiron.sa من القائمة العامة.', '2024-10-26 04:46:28'),
(83, 10, 'إضافة إيميل', 'تمت إضافة الإيميل info@jadiron.sa إلى القائمة العامة.', '2024-10-26 04:54:13'),
(84, 10, 'حذف خدمة', 'تم حذف الخدمة rx.', '2024-10-26 07:51:47'),
(85, 10, 'إضافة خدمة', 'تمت إضافة الخدمة x.', '2024-10-26 08:44:38'),
(86, 10, 'حذف خدمة', 'تم حذف الخدمة x.', '2024-10-26 08:44:43'),
(87, 10, 'إضافة خدمة', 'تمت إضافة الخدمة rx.', '2024-10-26 08:44:49'),
(88, 10, 'تعديل خدمة', 'تم تعديل اسم الخدمة إلى x1.', '2024-10-26 08:45:02'),
(89, 10, 'حذف خدمة', 'تم حذف الخدمة x1.', '2024-10-26 08:45:06'),
(90, 10, 'إضافة إيميل', 'تمت إضافة الإيميل it@jadiron.sa للخدمة دراسة الجدوى الاقتصادية.', '2024-10-26 13:47:41'),
(91, 10, 'حذف إيميل', 'تم حذف الإيميل it@jadiron.sa من الخدمة دراسة الجدوى الاقتصادية.', '2024-10-26 13:47:51'),
(92, 10, 'إضافة إيميل', 'تمت إضافة الإيميل hr@jadiron.sa للخدمة خدمات تدريب للمحاسبين والخريجين.', '2024-10-26 17:14:21'),
(93, 10, 'حذف إيميل', 'تم حذف الإيميل hr@jadiron.sa من الخدمة خدمات تدريب للمحاسبين والخريجين.', '2024-10-26 17:14:25'),
(94, 10, 'إضافة إيميل', 'تمت إضافة الإيميل hr@jadiron.sa للخدمة خدمات التصميم.', '2024-10-26 19:36:34'),
(95, 10, 'حذف إيميل', 'تم حذف الإيميل hr@jadiron.sa من الخدمة خدمات التصميم.', '2024-10-26 19:36:44'),
(96, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم finance@jadiron.sa إلى user.', '2024-10-26 19:42:49'),
(97, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم finance@jadiron.sa إلى manager.', '2024-10-26 19:43:01'),
(98, 10, 'إضافة خدمة', 'تمت إضافة الخدمة x.', '2024-10-26 21:31:42'),
(99, 10, 'تعديل خدمة', 'تم تعديل اسم الخدمة إلى تصميم مواقع.', '2024-10-26 21:33:39'),
(100, 10, 'حذف خدمة', 'تم حذف الخدمة تصميم مواقع.', '2024-10-26 21:34:13'),
(101, 10, 'إضافة إيميل', 'تمت إضافة الإيميل it@jadiron.sa للخدمة استشارات تقنية.', '2024-10-27 00:19:51'),
(102, 10, 'حذف مستخدم', 'تم حذف المستخدم finance@jadiron.sa.', '2024-10-27 12:41:30'),
(103, 10, 'إضافة مستخدم', 'تم إضافة المستخدم finance@jadiron.sa بدور admin.', '2024-10-27 12:43:07'),
(104, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم finance@jadiron.sa إلى user.', '2024-10-27 12:43:20'),
(105, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم finance@jadiron.sa إلى admin.', '2024-10-27 12:43:30'),
(106, 20, 'إضافة إيميل', 'تمت إضافة الإيميل finance@jadiron.sa إلى القائمة العامة.', '2024-10-27 12:51:08'),
(107, 20, 'حذف إيميل', 'تم حذف الإيميل finance@jadiron.sa من القائمة العامة.', '2024-10-27 12:51:35'),
(108, 20, 'إضافة إيميل', 'تمت إضافة الإيميل finance@jadiron.sa للخدمة استشارات مالية.', '2024-10-27 12:51:47'),
(109, 20, 'تعديل خدمة', 'تم تعديل اسم الخدمة إلى خدمات اخرى.', '2024-10-27 12:56:14'),
(110, 10, 'تعديل خدمة', 'تم تعديل اسم الخدمة إلى استشارات اخرى.', '2024-10-29 06:56:47'),
(111, 10, 'إضافة إيميل', 'تمت إضافة الإيميل sultan@jadiron.sa للخدمة استشارات زكوية.', '2024-10-29 06:58:26'),
(112, 10, 'إضافة إيميل', 'تمت إضافة الإيميل sultan@jadiron.sa للخدمة استشارات ضريبية.', '2024-10-29 06:58:37'),
(113, 10, 'إضافة إيميل', 'تمت إضافة الإيميل finance@jadiron.sa للخدمة المحاسبة السحابية.', '2024-10-29 06:59:05'),
(114, 10, 'إضافة إيميل', 'تمت إضافة الإيميل sultan@jadiron.sa للخدمة استشارات إدارية.', '2024-10-29 06:59:42'),
(115, 10, 'إضافة إيميل', 'تمت إضافة الإيميل hr@jadiron.sa للخدمة خدمات تدريب للمحاسبين والخريجين.', '2024-10-29 07:01:51'),
(116, 10, 'إضافة إيميل', 'تمت إضافة الإيميل hr@jadiron.sa للخدمة استشارات تسويقية.', '2024-10-29 07:03:01'),
(117, 10, 'إضافة إيميل', 'تمت إضافة الإيميل sultan@jadiron.sa للخدمة استشارات اخرى.', '2024-10-29 07:03:21'),
(118, 10, 'تعديل صلاحيات', 'تم تعديل صلاحيات المستخدم hr@jadiron.sa إلى manager.', '2024-10-29 07:04:29'),
(119, 10, 'استلام الطلب', 'تمت عملية استلام الطلب للطلب رقم 30', '2024-11-06 00:41:39'),
(120, 10, 'إغلاق الطلب', 'تم إغلاق الطلب رقم 30', '2024-11-06 00:54:28'),
(121, 10, 'استلام الطلب', 'تم استلام الطلب رقم 29', '2024-11-06 00:55:24'),
(122, 10, 'تحويل إلى مهمة', 'تم تحويل الطلب رقم 29 إلى مهمة بعنوان \'تصميم موقع الكتروني \'', '2024-11-06 01:27:47'),
(123, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 29 إلى مهمة بعنوان \'تصميم موقع\' وتم إغلاقه', '2024-11-06 02:45:35'),
(124, 10, 'إضافة تعليق', 'تم إضافة تعليق على المهمة رقم 5', '2024-11-06 04:18:24'),
(125, 10, 'إضافة تعليق', 'تم إضافة تعليق على المهمة رقم 5', '2024-11-06 04:20:02'),
(126, 10, 'إضافة تعليق', 'تم إضافة تعليق على المهمة رقم 5', '2024-11-06 15:57:01'),
(127, 10, 'إضافة تعليق', 'تم إضافة تعليق على المهمة رقم 5', '2024-11-06 15:57:26'),
(128, 10, 'تغيير المسؤول', 'تم تغيير المسؤول عن المهمة رقم 5 إلى المستخدم رقم 18', '2024-11-06 16:29:25'),
(129, 10, 'إضافة تعليق', 'تم إضافة تعليق على المهمة رقم 5', '2024-11-06 17:09:06'),
(130, 10, 'استلام الطلب', 'تم استلام الطلب رقم 31', '2024-11-19 13:42:12'),
(131, 10, 'استلام الطلب', 'تم استلام الطلب رقم 32', '2024-11-19 13:42:18'),
(132, 10, 'استلام الطلب', 'تم استلام الطلب رقم 33', '2024-11-19 13:42:20'),
(133, 10, 'استلام الطلب', 'تم استلام الطلب رقم 34', '2024-11-19 13:42:21'),
(134, 10, 'استلام الطلب', 'تم استلام الطلب رقم 35', '2024-11-19 13:42:23'),
(135, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 31 إلى مهمة بعنوان \'1\' وتم إغلاقه', '2024-11-19 13:42:36'),
(136, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 32 إلى مهمة بعنوان \'1\' وتم إغلاقه', '2024-11-19 13:42:43'),
(137, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 33 إلى مهمة بعنوان \'1\' وتم إغلاقه', '2024-11-19 13:42:52'),
(138, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 34 إلى مهمة بعنوان \'1\' وتم إغلاقه', '2024-11-19 13:42:59'),
(139, 10, 'تحويل إلى مهمة وإغلاق الطلب', 'تم تحويل الطلب رقم 35 إلى مهمة بعنوان \'111\' وتم إغلاقه', '2024-11-19 13:43:09');

-- --------------------------------------------------------

--
-- بنية الجدول `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `service_id` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `source` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_method` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_time` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed','received') COLLATE utf8mb4_general_ci NOT NULL,
  `closed_comment` text COLLATE utf8mb4_general_ci,
  `closed_at` datetime DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `contact_requests`
--

INSERT INTO `contact_requests` (`id`, `name`, `email`, `phone`, `service_id`, `description`, `source`, `contact_method`, `contact_time`, `created_at`, `status`, `closed_comment`, `closed_at`, `received_at`, `receiver_name`) VALUES
(4, 'إبراهيم عبدالعزيز القرافي', 'The1Programmer@gmail.com', '0552022522', 10, 'تصميم منصة سيتم الشرح بعد التواصل', 'unknown', 'phone', 'evening', '2024-10-27 03:13:11', 'closed', NULL, NULL, NULL, NULL),
(7, 'test', 'test@test.com', '0555555555', 10, 'test', 'unknown', 'phone', 'evening', '2024-10-27 05:16:06', 'closed', NULL, NULL, NULL, NULL),
(9, 'test', 'test@test.net', '0555555555', 10, 'test', 'unknown', 'email', 'morning', '2024-10-27 05:29:14', 'closed', NULL, NULL, NULL, NULL),
(28, 'محمد علي ظافر', 'riyadh.a.r.m@gmail.com', '0507883413', 2, '', 'Whatsapp', 'phone', 'morning', '2024-11-04 12:02:44', 'open', NULL, NULL, NULL, NULL),
(29, 'إبراهيم القرافي', '', '0552022522', 10, '', 'Snapchat', 'phone', 'evening', '2024-11-05 04:50:51', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-06 05:45:35', '2024-11-06 00:55:24', 'it@jadiron.sa'),
(30, 'ابراهيم', '', '0552022522', 10, '', 'Snapchat', 'phone', 'evening', '2024-11-05 06:52:53', 'closed', 'الطلب مكرر', '2024-11-06 03:54:28', '2024-11-06 00:41:39', 'it@jadiron.sa'),
(31, 'Ibrahim', 'the1programmer@gmail.com', '0552022522', 10, '', 'Instagram', 'phone', 'morning', '2024-11-19 13:15:27', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-19 16:42:36', '2024-11-19 13:42:12', 'it@jadiron.sa'),
(32, 'إبراهيم عبدالعزيز القرافي', 'The1Programmer@gmail.com', '0552022522', 10, '', 'Instagram', 'phone', 'evening', '2024-11-19 13:16:42', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-19 16:42:43', '2024-11-19 13:42:18', 'it@jadiron.sa'),
(33, 'Abdulrahman Alhazmi', '', '00000000', 3, '', 'Instagram', 'phone', 'evening', '2024-11-19 13:22:38', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-19 16:42:52', '2024-11-19 13:42:20', 'it@jadiron.sa'),
(34, 'إبراهيم القرافي', 'The1Programmer@gmail.com', '0552022522', 10, '', 'Twitter(X)', 'email', 'morning', '2024-11-19 13:31:11', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-19 16:42:59', '2024-11-19 13:42:21', 'it@jadiron.sa'),
(35, 'إبراهيم القرافي', 'The1Programmer@gmail.com', '0552022522', 10, '', 'Whatsapp', 'phone', 'evening', '2024-11-19 13:35:25', 'closed', 'تم تحويل الطلب إلى مهمة', '2024-11-19 16:43:09', '2024-11-19 13:42:23', 'it@jadiron.sa'),
(36, 'Joanna Riggs', 'joannariggs94@gmail.com', '812151157', 9, 'Hi,\r\n\r\nI just visited jadiron.sa and wondered if you\'d ever thought about having an engaging video to explain what you do?\r\n\r\nOur videos cost just $195 for a 30 second video ($239 for 60 seconds) and include a full script, voice-over and video.\r\n\r\nI can show you some previous videos we\'ve done if you want me to send some over. Let me know if you\'re interested in seeing samples of our previous work.\r\n\r\nRegards,\r\nJoanna\r\n\r\nUnsubscribe: https://removeme.live/unsubscribe.php?d=jadiron.sa', 'Whatsapp', 'email', 'morning', '2024-11-24 09:39:18', 'open', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `deleted_requests`
--

CREATE TABLE `deleted_requests` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `description` text,
  `original_created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- إرجاع أو استيراد بيانات الجدول `deleted_requests`
--

INSERT INTO `deleted_requests` (`id`, `name`, `email`, `phone`, `service_id`, `description`, `original_created_at`, `deleted_at`) VALUES
(21, 'إبراهيم عبدالعزيز القرافي', 'The1Programmer@gmail.com', '0552022522', 10, '', '2024-11-03 09:52:49', '2024-11-03 09:55:23');

-- --------------------------------------------------------

--
-- بنية الجدول `global_emails`
--

CREATE TABLE `global_emails` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `global_emails`
--

INSERT INTO `global_emails` (`id`, `email`) VALUES
(15, 'info@jadiron.sa');

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'manage_users', 'إدارة المستخدمين (إضافة/تعديل/حذف)'),
(2, 'manage_departments', 'إدارة الأقسام'),
(3, 'view_logs', 'عرض السجلات'),
(4, 'edit_personal_data', 'تعديل البيانات الشخصية'),
(5, 'view_department_data', 'عرض بيانات القسم'),
(6, 'manage_projects', 'إدارة المشاريع'),
(7, 'view_personal_reports', 'عرض التقارير الشخصية');

-- --------------------------------------------------------

--
-- بنية الجدول `platforms`
--

CREATE TABLE `platforms` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `platforms`
--

INSERT INTO `platforms` (`id`, `name`, `slug`) VALUES
(1, 'Instagram', 'instagram'),
(2, 'Google Ads', 'google_ads'),
(3, 'Snapchat', 'snapchat'),
(4, 'Twitter', 'twitter');

-- --------------------------------------------------------

--
-- بنية الجدول `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'مسؤول النظام الكامل'),
(2, 'Admin', 'مسؤول'),
(3, 'Manager', 'مدير'),
(4, 'User', 'مستخدم');

-- --------------------------------------------------------

--
-- بنية الجدول `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3),
(1, 4),
(2, 4),
(3, 4),
(4, 4),
(1, 5),
(2, 5),
(3, 5),
(1, 6),
(2, 6),
(3, 6),
(1, 7),
(4, 7);

-- --------------------------------------------------------

--
-- بنية الجدول `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `services`
--

INSERT INTO `services` (`id`, `name`) VALUES
(1, 'دراسة الجدوى الاقتصادية'),
(2, 'المحاسبة السحابية'),
(3, 'استشارات زكوية'),
(4, 'استشارات ضريبية'),
(5, 'استشارات مالية'),
(6, 'استشارات إدارية'),
(7, 'خدمات تدريب للمحاسبين والخريجين'),
(8, 'استشارات تسويقية'),
(9, 'استشارات قانونية'),
(10, 'استشارات تقنية'),
(11, 'خدمات التصميم'),
(12, 'استشارات اخرى');

-- --------------------------------------------------------

--
-- بنية الجدول `service_emails`
--

CREATE TABLE `service_emails` (
  `id` int NOT NULL,
  `service_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `service_emails`
--

INSERT INTO `service_emails` (`id`, `service_id`, `email`) VALUES
(11, 1, 'sultan@jadiron.sa'),
(24, 10, 'it@jadiron.sa'),
(25, 5, 'finance@jadiron.sa'),
(26, 3, 'sultan@jadiron.sa'),
(27, 4, 'sultan@jadiron.sa'),
(28, 2, 'finance@jadiron.sa'),
(29, 6, 'sultan@jadiron.sa'),
(30, 7, 'hr@jadiron.sa'),
(31, 8, 'hr@jadiron.sa'),
(32, 12, 'sultan@jadiron.sa');

-- --------------------------------------------------------

--
-- بنية الجدول `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'img/logo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `social_media`
--

CREATE TABLE `social_media` (
  `id` int NOT NULL,
  `platform` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `added_by_admin` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `social_media`
--

INSERT INTO `social_media` (`id`, `platform`, `url`, `added_by_admin`) VALUES
(6, 'Twitter(X)', 'https://x.com/Jadiron1', 1),
(7, 'Snapchat', 'https://snapchat.com/t/MVzlvBrv', 1),
(8, 'Instagram', 'https://www.instagram.com/jadiron1/', 1),
(9, 'Whatsapp', 'https://wa.me/message/FENIFAOHURFAF1', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `contact_request_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `details` text COLLATE utf8mb4_general_ci,
  `assigned_to` int DEFAULT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_general_ci DEFAULT 'in_progress',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'Normal',
  `category` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tasks`
--

INSERT INTO `tasks` (`id`, `contact_request_id`, `title`, `details`, `assigned_to`, `status`, `created_at`, `updated_at`, `priority`, `category`, `start_date`, `due_date`, `closed_at`) VALUES
(5, 29, 'تصميم موقع', 'العميل طلب تحديث لموقعه الالكتروني الرجاء التواصل معه هلى رقم الجوال 0552022522\r\n\r\nمع مراعات رفع عرض السعر قبل الشروع بالعمل', 18, 'in_progress', '2024-11-06 02:45:35', '2024-11-06 16:29:25', 'Normal', NULL, NULL, NULL, NULL),
(6, 31, '1', '111', 10, 'in_progress', '2024-11-19 13:42:35', '2024-11-19 13:42:35', 'Normal', NULL, NULL, NULL, NULL),
(7, 32, '1', '111', 10, 'in_progress', '2024-11-19 13:42:43', '2024-11-19 13:42:43', 'Normal', NULL, NULL, NULL, NULL),
(8, 33, '1', '111', 10, 'in_progress', '2024-11-19 13:42:52', '2024-11-19 13:42:52', 'Normal', NULL, NULL, NULL, NULL),
(9, 34, '1', '111', 10, 'in_progress', '2024-11-19 13:42:59', '2024-11-19 13:42:59', 'Normal', NULL, NULL, NULL, NULL),
(10, 35, '111', '1111', 10, 'in_progress', '2024-11-19 13:43:09', '2024-11-19 13:43:09', 'Normal', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` int NOT NULL,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- إرجاع أو استيراد بيانات الجدول `task_attachments`
--

INSERT INTO `task_attachments` (`id`, `task_id`, `user_id`, `file_path`, `uploaded_at`) VALUES
(1, 5, 10, 'uploads/672b94315c30e.png', '2024-11-06 16:07:13'),
(2, 5, 10, 'uploads/672b943dbda89.png', '2024-11-06 16:07:25');

-- --------------------------------------------------------

--
-- بنية الجدول `task_comments`
--

CREATE TABLE `task_comments` (
  `id` int NOT NULL,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- إرجاع أو استيراد بيانات الجدول `task_comments`
--

INSERT INTO `task_comments` (`id`, `task_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 5, 10, 'سيتم رفع عرض السعر بعد التواصل مع العميل مساءاً حسب رغبته', '2024-11-06 04:18:24'),
(2, 5, 10, 'تم التواصل مع العميل لا يرغب العميل باكمال بناء الموقع معنا\r\n\r\nمتطلبات العميل بالسعر متدنية جداً ولا ترقى بخدماتنا', '2024-11-06 04:20:02'),
(3, 5, 10, 'ا', '2024-11-06 15:57:01'),
(4, 5, 10, '.', '2024-11-06 15:57:26'),
(5, 5, 10, 'تم انهاء المهمة', '2024-11-06 17:09:06');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_superadmin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `password`, `role`, `email`, `is_superadmin`) VALUES
(10, '$2y$10$9hmDw9d5CoyeXTLIl0iU1.QEB6FIFxDifAtSlT5rsr.imWix4DAmC', 'superadmin', 'it@jadiron.sa', 1),
(17, '$2y$10$U16QtAseMKMiM3f5cg.yBuc14lQkV61YwsR3Y7jK5ZOj2Usgb7mGK', 'admin', 'sultan@jadiron.sa', 0),
(18, '$2y$10$ByG.PTE7pQv.6R7GtxhJDOrUNBCMeiNua2Y0JNuWsQ/Czo0Lt0XNq', 'manager', 'hr@jadiron.sa', 0),
(20, '$2y$10$0laUmGarYBkppOLqCSnUz.2ZR21vcb22i078scDNUSLiU05DeZfR2', 'admin', 'finance@jadiron.sa', 0);

-- --------------------------------------------------------

--
-- بنية الجدول `visitor_tracking`
--

CREATE TABLE `visitor_tracking` (
  `id` int NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `source` varchar(255) DEFAULT 'unknown',
  `started_fill` tinyint(1) DEFAULT '0',
  `submitted` tinyint(1) DEFAULT '0',
  `exited_directly` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- إرجاع أو استيراد بيانات الجدول `visitor_tracking`
--

INSERT INTO `visitor_tracking` (`id`, `session_id`, `source`, `started_fill`, `submitted`, `exited_directly`, `created_at`) VALUES
(1, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 06:48:43'),
(2, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 06:48:49'),
(3, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:58:30'),
(4, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:58:35'),
(5, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:58:37'),
(6, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:58:42'),
(7, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:58:48'),
(8, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 07:59:01'),
(9, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 07:59:49'),
(10, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 08:02:30'),
(11, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 08:03:58'),
(12, 'c24b3298c995cc88123c2355e2e027a2', 'unknown', 0, 0, 1, '2024-11-05 08:04:53'),
(13, '2ab17da2a090ea86b7fb72ee25ffac30', 'unknown', 0, 0, 1, '2024-11-05 08:05:35'),
(14, 'cf5372b1e3cf8441a5b141a6ea60eacb', 'unknown', 0, 0, 1, '2024-11-05 09:20:11'),
(15, 'beebe40c87ab9cfb05fc6eff07255a50', 'unknown', 0, 0, 1, '2024-11-05 12:00:58'),
(16, 'c48f3e8d66dcd58cb8eea3b0fb480161', 'unknown', 0, 0, 1, '2024-11-06 14:06:46'),
(17, '1a0220fcc6802f173341d024ef5be45b', 'unknown', 0, 0, 1, '2024-11-06 14:07:12'),
(18, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:07:20'),
(19, 'c9fddb2961444a97250976f8b25f6841', 'unknown', 0, 0, 1, '2024-11-06 14:07:21'),
(20, '3b858ef9298daea62dec4e47454b3a50', 'unknown', 0, 0, 1, '2024-11-06 14:07:21'),
(21, '42ebe1036dc0d84f06a04acc7446d99b', 'unknown', 0, 0, 1, '2024-11-06 14:07:46'),
(22, 'cc795ada46be1de2564124661b9a5441', 'unknown', 0, 0, 1, '2024-11-06 14:07:46'),
(23, '7e9878718691e235b27a4e4fe64758fb', 'unknown', 0, 0, 1, '2024-11-06 14:07:47'),
(24, '3adf8c33df4fb8bbc9029cbdf74e2a76', 'unknown', 0, 0, 1, '2024-11-06 14:07:47'),
(25, '69dd8a54bcdf21a319cf94d794804126', 'unknown', 0, 0, 1, '2024-11-06 14:07:48'),
(26, '6032537a89329cae6f6a814e88e7664c', 'unknown', 0, 0, 1, '2024-11-06 14:07:48'),
(27, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:07:51'),
(28, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:08:13'),
(29, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:08:29'),
(30, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:09:00'),
(31, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:09:20'),
(32, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:09:41'),
(33, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:10:00'),
(34, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:10:56'),
(35, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:11:57'),
(36, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:12:19'),
(37, 'ce596ae25d3c957695e986cfe7eb4a94', 'unknown', 0, 0, 1, '2024-11-06 14:12:41'),
(38, '64583053316ee9bbc502f70820af7855', 'unknown', 0, 0, 1, '2024-11-06 14:16:34'),
(39, '2c701d29011476df1b688e955d2ae41b', 'unknown', 0, 0, 1, '2024-11-06 14:16:34'),
(40, '2d0bac814c81c0c4334df6c1f663b11c', 'unknown', 0, 0, 1, '2024-11-06 14:16:34'),
(41, '0526903c073c002242d3ae802fb2bf96', 'unknown', 0, 0, 1, '2024-11-06 14:24:03'),
(42, 'c9fc6a0d5a6439a332842c781fc3cd2c', 'unknown', 0, 0, 1, '2024-11-06 14:24:57'),
(43, 'de532c64ee3089d72d1b2af802df4df8', 'unknown', 0, 0, 1, '2024-11-06 14:25:32'),
(44, '4398684fea8a75bf7cc4d8d963f49854', 'unknown', 0, 0, 1, '2024-11-06 14:27:02'),
(45, 'da485c6797fa3b420200787588f8f8c7', 'unknown', 0, 0, 1, '2024-11-06 14:29:10'),
(46, 'd0d2bc4fa57bd14aafb7187c9ed9f73b', 'unknown', 0, 0, 1, '2024-11-06 14:32:41'),
(47, 'b0a0493d3782170fb723cee575cd1c09', 'unknown', 0, 0, 1, '2024-11-06 14:32:52'),
(48, 'aebd550ed1ef8b07bc2ecf024b83f7c1', 'unknown', 0, 0, 1, '2024-11-06 14:33:10'),
(49, 'ed8e42f0f19de5738119fb322cba759a', 'unknown', 0, 0, 1, '2024-11-06 15:05:08'),
(50, '18f8fa18a4c4e8b97c43ad1e5d5336c7', 'unknown', 0, 0, 1, '2024-11-06 15:19:29'),
(51, 'a880e0e32bb4c6b53e6ee7e492b8f0a3', 'unknown', 0, 0, 1, '2024-11-06 15:19:30'),
(52, '0d4d59c6b9e7d4dbfcca3edd2b9f7ffe', 'unknown', 0, 0, 1, '2024-11-06 15:19:30'),
(53, 'f14343b1fde9b8337fe8cd66021efde4', 'unknown', 0, 0, 1, '2024-11-06 17:40:30'),
(54, 'beebe40c87ab9cfb05fc6eff07255a50', 'unknown', 0, 0, 1, '2024-11-06 17:42:48'),
(55, 'a918a6b37bd9a7a708940e6a0a267807', 'unknown', 0, 0, 1, '2024-11-06 19:07:57'),
(56, '168f7a076fca11b4934fa4cf7e872f29', 'unknown', 0, 0, 1, '2024-11-06 19:07:57'),
(57, 'fd10a93773fa72600e17518cb444456d', 'unknown', 0, 0, 1, '2024-11-06 19:07:58'),
(58, '06cbdaf2955ff818839f0ad37dc530b5', 'unknown', 0, 0, 1, '2024-11-06 23:44:56'),
(59, 'e4a9fb6c062f175de8b2f248dbf668ba', 'unknown', 0, 0, 1, '2024-11-06 23:44:56'),
(60, 'e7e331c07710ebf532670c0e31f7dea3', 'unknown', 0, 0, 1, '2024-11-06 23:44:57'),
(61, '03ff2360fda14c41f77e47d9b668a2a1', 'unknown', 0, 0, 1, '2024-11-07 14:09:50'),
(62, 'ddd6ba0ce0e85869522a5568305677d3', 'unknown', 0, 0, 1, '2024-11-07 14:09:50'),
(63, 'aa4e3c90cd9edc73d0dc625d0f56a655', 'unknown', 0, 0, 1, '2024-11-07 14:09:51'),
(64, 'b0590ce7a86a29f035d83cfffdeb51b2', 'unknown', 0, 0, 1, '2024-11-10 14:12:38'),
(65, '25b241db07f6d1266843d907cad8af51', 'unknown', 0, 0, 1, '2024-11-11 14:45:14'),
(66, 'd9e7fb8136847349898025b4110ff1e4', 'unknown', 0, 0, 1, '2024-11-11 20:00:54'),
(67, '4bb73728fe9d53110991f258a83ea966', 'unknown', 0, 0, 1, '2024-11-11 20:00:54'),
(68, '47b29913fcd971360733bfe0a4f60e0f', 'unknown', 0, 0, 1, '2024-11-12 07:29:59'),
(69, '9eaa04b610d5f01569b39e14423aa15b', 'unknown', 0, 0, 1, '2024-11-19 10:48:34'),
(70, '9eaa04b610d5f01569b39e14423aa15b', 'unknown', 0, 0, 1, '2024-11-19 11:14:28'),
(71, '969002831f7726409d0caeeee283f5f1', 'unknown', 0, 0, 1, '2024-11-20 11:47:17'),
(72, 'be9a86368e228d398aef3f90430f44c1', 'unknown', 0, 0, 1, '2024-11-21 12:56:09'),
(73, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-21 13:01:44'),
(74, '36409ef3d00c4b8129f6aed1f8ab5a0c', 'unknown', 0, 0, 1, '2024-11-21 13:06:16'),
(75, '36409ef3d00c4b8129f6aed1f8ab5a0c', 'unknown', 0, 0, 1, '2024-11-21 13:06:27'),
(76, '86c2a6b971751f958fa9609b5002209a', 'unknown', 0, 0, 1, '2024-11-21 13:06:53'),
(77, '6574cc949f85457fc16c2cd9be91818c', 'unknown', 0, 0, 1, '2024-11-21 13:06:53'),
(78, '6559129832b2aa880e5e8b776fab93d3', 'unknown', 0, 0, 1, '2024-11-21 13:06:53'),
(79, 'c6cb121ca9c9ecb266b4206ec1fd0eb2', 'unknown', 0, 0, 1, '2024-11-21 13:06:59'),
(80, '70fcc3b7a188c601a9f9122df9497ded', 'unknown', 0, 0, 1, '2024-11-21 13:07:05'),
(81, 'af835065559e428863df2485d1ec447a', 'unknown', 0, 0, 1, '2024-11-21 13:21:14'),
(82, '36409ef3d00c4b8129f6aed1f8ab5a0c', 'unknown', 0, 0, 1, '2024-11-21 13:28:51'),
(83, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:02:53'),
(84, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:06:58'),
(85, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:11:31'),
(86, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:15:14'),
(87, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:16:43'),
(88, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:20:54'),
(89, '5839d0ac3d1c072f7f46f5f0b235536a', 'unknown', 0, 0, 1, '2024-11-22 01:34:33'),
(90, 'dc8dc6671748a91ac3c28f859e4f5563', 'unknown', 0, 0, 1, '2024-11-22 01:36:08'),
(91, '0fadf33e55c733d55bca7d8f956e086b', 'unknown', 0, 0, 1, '2024-11-22 13:56:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_ibfk_1` (`user_id`);

--
-- Indexes for table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service_id` (`service_id`);

--
-- Indexes for table `deleted_requests`
--
ALTER TABLE `deleted_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_emails`
--
ALTER TABLE `global_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_emails`
--
ALTER TABLE `service_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_request_id` (`contact_request_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_tracking`
--
ALTER TABLE `visitor_tracking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `deleted_requests`
--
ALTER TABLE `deleted_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `global_emails`
--
ALTER TABLE `global_emails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `platforms`
--
ALTER TABLE `platforms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `service_emails`
--
ALTER TABLE `service_emails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `visitor_tracking`
--
ALTER TABLE `visitor_tracking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD CONSTRAINT `fk_service_id` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- قيود الجداول `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- قيود الجداول `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `service_emails`
--
ALTER TABLE `service_emails`
  ADD CONSTRAINT `service_emails_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`contact_request_id`) REFERENCES `contact_requests` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- قيود الجداول `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD CONSTRAINT `task_attachments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_attachments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
