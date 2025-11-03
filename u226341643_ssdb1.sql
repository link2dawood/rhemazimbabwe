-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 03, 2025 at 03:35 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u226341643_ssdb1`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`u226341643_ssdb1`@`127.0.0.1` PROCEDURE `sp_get_partner_balance` (IN `p_partner_id` INT)   BEGIN
    DECLARE v_expected DECIMAL(10,2);
    DECLARE v_contributed DECIMAL(10,2);

    SELECT 
        CASE 
            WHEN gf.days_interval IS NOT NULL 
            THEN p.contribution_amount * FLOOR(DATEDIFF(CURDATE(), COALESCE(p.start_date, p.created_at)) / gf.days_interval)
            ELSE p.contribution_amount 
        END INTO v_expected
    FROM partners p
    LEFT JOIN giving_frequencies gf ON p.giving_frequency_id = gf.id
    WHERE p.id = p_partner_id;

    SELECT COALESCE(SUM(amount), 0) INTO v_contributed
    FROM partner_contributions
    WHERE partner_id = p_partner_id AND status = 'completed';

    SELECT 
        p_partner_id AS partner_id,
        v_expected AS expected_amount,
        v_contributed AS contributed_amount,
        (v_expected - v_contributed) AS balance,
        CASE
            WHEN (v_expected - v_contributed) > (v_expected * 0.5) THEN 'Critical'
            WHEN (v_expected - v_contributed) > (v_expected * 0.25) THEN 'High'
            WHEN (v_expected - v_contributed) > 0 THEN 'Low'
            ELSE 'Up to Date'
        END AS balance_status;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `name` varchar(500) DEFAULT NULL,
  `config_name` varchar(200) NOT NULL DEFAULT '',
  `short_name` varchar(100) NOT NULL,
  `directory` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `price` float(10,2) NOT NULL DEFAULT 0.00,
  `current_version` varchar(50) DEFAULT NULL,
  `article_link` text NOT NULL,
  `installation_by` int(11) DEFAULT NULL,
  `uninstall_version` varchar(50) DEFAULT NULL,
  `unistall_by` int(11) DEFAULT NULL,
  `addon_prod` text DEFAULT NULL,
  `addon_ver` text DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `current_stage` int(11) NOT NULL DEFAULT 0 COMMENT '0 for buy addon,1 for folder available ready to install,2 for folder addon installed',
  `product_order` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `product_id`, `image`, `name`, `config_name`, `short_name`, `directory`, `description`, `price`, `current_version`, `article_link`, `installation_by`, `uninstall_version`, `unistall_by`, `addon_prod`, `addon_ver`, `last_update`, `current_stage`, `product_order`, `created_at`, `updated_at`) VALUES
(1, 47443722, 'uploads/addon_images/sscbse_images.jpg', 'Smart School CBSE Examination', 'cbse-config', 'sscbse', 'cbse_examination', 'CBSE Examination addon adds CBSE Examination module in Smart School. Using this module teacher/staff can create and print marksheets with advance features.', 0.00, '3.0', 'https://go.smart-school.in/cbse-exam', 1, NULL, NULL, 'TUGUH-M37RP-KESFW-8JUEY', 'bU1IMVdZd1dUSVJvR2MwdEk1c3l4S3dGMC9YSWM4RlRSY2pzYUQydmhKelVJcktOTHFZU0diWEd5dCsrZUZPQzo6YuUjZMnCuYYTOvyb2d90vQ==', '2025-05-22 11:47:15', 0, 4, '2024-09-03 16:04:58', '2025-05-22 11:49:23'),
(2, 44278049, 'uploads/addon_images/sstfa_images.jpg', 'Smart School Two Factor Authentication', 'google-authenticate-config', 'sstfa', 'two_factor_authentication', 'Two Factor Authentication addon adds Two Factor Authentication module in Smart School. Using this module you can enhance login security of your Smart School users.', 0.00, '3.0', 'https://go.smart-school.in/2fa', 1, NULL, NULL, 'MNGZF-9KZNM-9RTTY-5J4KN', 'dmFSa3pIelVrUWVEK3hLbTdya20wc0w3ZzlsOTg5L2xQZnJoOW1wUVROUGM4WTFZdE1TQnlzMzIxcCtyY0tNSjo6aoRtCNG3u8MGkS149V3EIg==', '2025-05-22 11:47:02', 0, 5, '2024-09-07 10:45:18', '2025-05-22 11:48:56'),
(3, 44277916, 'uploads/addon_images/ssmb_images.jpg', 'Smart School Multi Branch', 'multibranch-config', 'ssmb', 'multi_branch', 'Multi Branch addon adds Multi Branch module in Smart School. Using this module Superadmin user can add other any number of schools/branches.', 0.00, '3.0', 'https://go.smart-school.in/multi-branch', 1, NULL, NULL, 'YBHVC-S2TR4-H7ENB-AZCC7', 'a04xaTczdCtIbXRMWUVzckFqZDFzQ05sOTR2MjZJMDA0YWJBY3lDaGRqaXIxK25GSkc5aHRVQkFIS1RCc0orRjo6UOSlExCDdRRe/Eb4KT7zQA==', '2025-05-22 11:46:57', 0, 6, '2024-09-07 10:45:18', '2025-05-22 11:50:07'),
(4, 44247532, 'uploads/addon_images/ssbr_images.jpg', 'Smart School Behaviour Records', 'behaviour-report-config', 'ssbr', 'behavior_records', 'Behaviour Records addon adds Behaviour Records module in Smart School. Using this module teacher/staff can create different incidents with positive/negative marks and then assign these incidents on students.', 0.00, '3.0', 'https://go.smart-school.in/behaviour-records', 1, NULL, NULL, '55MVP-DXES6-MYBXN-SNEK8', 'THFhTWtuN0hsSVNvT1F2cFBnR3UySmNnTkhXVGgrYVdwbFhwV09sRWlDbnZMTnlkdDdFNDN5bUduaDNDc1VuOTo6kU+zhuNCUgZJe1cAPW9Yjw==', '2025-05-22 11:46:54', 0, 7, '2024-09-07 10:45:42', '2025-05-22 11:50:20'),
(5, 33101540, 'uploads/addon_images/ssoclc_images.jpg', 'Smart School Online Course', 'onlinecourse-config', 'ssoclc', 'online_course', 'Online Course addon adds Online Course module in Smart School. Using this module teacher/staff can create free or paid online course with their study material based on video, audio or in document content format.', 0.00, '4.0', 'https://go.smart-school.in/online-course', 1, NULL, NULL, 'YY5JB-ZUR99-K7P3C-PDQQV', 'ZEFaYUxLYUM2QUJ4S1VLem1mOTUzMGo0bURkLzBkamlFZVlBZDJEVWdUZm9IdkVXUUk1UEZIdE5KV2MvTXUyZTo6ytOxQ58YrWzByZMI18Xsvw==', '2025-05-22 11:46:50', 0, 8, '2024-09-07 10:45:42', '2025-05-22 11:48:42'),
(6, 27492043, 'uploads/addon_images/sszlc_images.jpg', 'Smart School Zoom Live Classes', 'zoom-config', 'sszlc', 'zoom_live_class', 'Zoom Live Class addon adds Zoom Live Class module in Smart School. Using this module teacher/staff can create live online classes using Zoom.us service. Further students can join these classes.', 0.00, '7.0', 'https://go.smart-school.in/zoom', 1, NULL, NULL, 'PQMLZ-CFWWN-8DS8D-LRD3F', 'MXdZbXVjRis1SHdyNWtWaUxPT1B2MVBBYXZ1VWMrdjJFN2g0R3M3NWoxVjM1SGV5aWRBajZkZUhVWmFoUmpkTTo65aM3G8CSkdS5dgk8mhYM5w==', '2025-05-22 11:46:33', 0, 10, '2024-09-07 10:46:10', '2025-05-22 11:48:25'),
(7, 28941973, 'uploads/addon_images/ssglc_images.jpg', 'Smart School Gmeet Live Class', 'gmeet-config', 'ssglc', 'gmeet_live_class', 'Gmeet Live Class addon adds Gmeet Live Class module in Smart School. Using this module teacher/staff can create live online classes using http://meet.google.com service. Further students can join these classes.', 0.00, '6.0', 'https://go.smart-school.in/gmeet', 1, NULL, NULL, '46YFB-VTKL4-QT5MW-JTYA7', 'R0loNDROVkJYcDdvb3ZaMnpTNXkvbVlNOGs0L1VNTUNPQTNqS1JSd0czODF3VE41OU9HRFRRYW5rOEhGYUJ1Vjo6fGzzHSprZx30GUSl4o12sw==', '2025-05-22 11:46:38', 0, 9, '2024-09-07 10:46:10', '2025-05-22 11:50:35'),
(8, 50336584, 'uploads/addon_images/ssqra_images.jpg', 'Smart School QR Code Attendance', 'qrattendance-config', 'ssqra', 'qr_code_attendance', 'QR Code Attendance addon adds automated Student/Staff attendance using QR/Barcode module in Smart School. Using this module Student/Staff can submit their attendance by just scanning their ID Card.', 0.00, '2.0', 'https://go.smart-school.in/qr-attendance', 1, NULL, NULL, 'RXG9L-H6GP2-DWQUE-EJL6S', 'Q3FIZm5rRXNScHREWE05YyttVVF2OFB4RXNxK1lFY3lHTE9MbnRiRzhGblhiVkc0MGR6ZFRJTEFobnFEdjhrVjo6X2Q9snGw1z8h1PwaU/7fCg==', '2025-05-22 11:47:17', 0, 3, '2025-01-13 13:10:06', '2025-05-22 11:49:37'),
(9, 57220011, 'uploads/addon_images/ssqfc_images.jpg', 'Smart School Quick Fees Create', 'quickfees-config', 'ssqfc', 'quick_fees_create', 'Quick Fees Create addon adds one click fees create feature in Smart School Fees Collection module. Using this you can create and assign fees on students in just few seconds and all Fees Category, Fees Groups, Fees Masters will be create automatically in your Smart School.', 0.00, '1.0', 'https://go.smart-school.in/quick-fees', 1, NULL, NULL, '5NWE2-YWEX4-5DBWZ-AV5CY', 'a2dmYXRvb2xFRU5aTHlWZ2FSUVMyYUt0eVZ1Q2R6cnRHd0JtOW95L24wTWltNjJWd1h4dEhiOTdBOGxZcnYzTzo6+tJlbPn6OTiybG+TjqTT0Q==', '2025-05-22 11:47:24', 0, 2, '2025-01-13 13:10:06', '2025-05-22 11:49:50'),
(10, 57219905, 'uploads/addon_images/sstpa_images.jpg', 'Smart School Thermal Print', 'thermalprint-config', 'sstpa', 'thermal_print', 'Thermal Print addon adds Thermal Printer compatible small size fees receipt print capability in Smart School. Using this module you can utilize modern cost effective fees receipt printing in Smart School.', 0.00, '1.0', 'https://go.smart-school.in/thermal-print', 1, NULL, NULL, '7UPGE-AS7ND-ADVYP-KWC8U', 'OTh5Q2VHVGs1K0pKeWpQN1RJOXNYMjBacFlxbUpUVXJlZklQN0xwWTE2aG5iUSs1c3dQQ3oyQzNGa1FaaURaSjo6uDg7/DrW0N1cIpHMavGFMw==', '2025-05-22 11:47:33', 0, 1, '2025-01-13 13:10:06', '2025-05-22 11:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `addon_versions`
--

CREATE TABLE `addon_versions` (
  `id` int(11) NOT NULL,
  `addon_id` int(11) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `version_order` int(11) DEFAULT NULL,
  `folder_path` text DEFAULT NULL,
  `sort_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alumni_events`
--

CREATE TABLE `alumni_events` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `event_for` varchar(100) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section` varchar(255) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_date` datetime NOT NULL,
  `note` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `event_notification_message` text NOT NULL,
  `show_onwebsite` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `alumni_events`
--

INSERT INTO `alumni_events` (`id`, `title`, `event_for`, `session_id`, `class_id`, `section`, `from_date`, `to_date`, `note`, `photo`, `is_active`, `event_notification_message`, `show_onwebsite`, `created_at`, `updated_at`) VALUES
(1, 'Annual Graduation', 'all', NULL, NULL, 'null', '2025-11-22 00:00:00', '2025-11-22 23:59:00', '', '', 0, '', 0, '2025-06-18 07:29:31', '2025-06-18 07:29:31'),
(2, 'Homecoming', 'all', NULL, NULL, 'null', '2025-04-26 00:00:00', '2025-04-26 23:59:00', '', '', 0, '', 0, '2025-06-18 07:32:15', '2025-06-18 07:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `alumni_students`
--

CREATE TABLE `alumni_students` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `current_email` varchar(255) NOT NULL,
  `current_phone` varchar(255) NOT NULL,
  `occupation` text NOT NULL,
  `address` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `annual_calendar`
--

CREATE TABLE `annual_calendar` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `holiday_type` int(11) NOT NULL,
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `holiday_color` varchar(200) NOT NULL,
  `front_site` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `annual_calendar`
--

INSERT INTO `annual_calendar` (`id`, `session_id`, `holiday_type`, `from_date`, `to_date`, `description`, `is_active`, `holiday_color`, `front_site`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 21, 1, '2025-05-26 00:00:00', '2025-05-26 23:59:00', 'Africa Freedom Day', 1, '#008000', 0, 1, '2025-05-27 14:25:51', '2025-05-27 00:00:00'),
(2, 21, 1, '2025-07-07 00:00:00', '2025-07-07 23:59:00', 'Heroes Day', 1, '#008000', 0, 1, '2025-05-27 14:27:05', '2025-05-27 12:27:05'),
(3, 21, 1, '2025-07-08 00:00:00', '2025-07-08 23:59:00', 'Unity Day', 1, '#008000', 0, 1, '2025-05-27 14:27:33', '2025-05-27 12:27:33'),
(4, 21, 1, '2025-08-04 00:00:00', '2025-08-04 23:59:00', 'Zambia Farmers Day', 1, '#008000', 0, 1, '2025-05-27 14:28:30', '2025-05-27 12:28:30'),
(5, 21, 1, '2025-10-18 00:00:00', '2025-10-18 23:59:00', 'Zambia National Day of Prayer', 1, '#008000', 0, 1, '2025-05-27 14:29:14', '2025-05-27 12:29:14'),
(6, 21, 1, '2025-10-24 00:00:00', '2025-10-24 23:59:00', 'Zambia Independence Day', 1, '#008000', 0, 1, '2025-05-27 14:29:59', '2025-05-27 12:29:59'),
(7, 21, 1, '2025-12-25 00:00:00', '2025-12-25 23:59:00', 'Christmas Day', 1, '#008000', 0, 1, '2025-05-27 14:30:50', '2025-05-27 12:30:50'),
(8, 21, 3, '2025-08-07 00:00:00', '2025-08-07 23:59:00', 'Recreation Day', 1, '#008000', 1, 1, '2025-06-26 14:03:27', '2025-06-26 12:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `attendence_type`
--

CREATE TABLE `attendence_type` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `key_value` varchar(50) NOT NULL,
  `long_lang_name` varchar(250) DEFAULT NULL,
  `long_name_style` varchar(250) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `for_qr_attendance` int(11) NOT NULL DEFAULT 1,
  `for_schedule` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `attendence_type`
--

INSERT INTO `attendence_type` (`id`, `type`, `key_value`, `long_lang_name`, `long_name_style`, `is_active`, `for_qr_attendance`, `for_schedule`, `created_at`, `updated_at`) VALUES
(1, 'Present', '<b class=\"text text-success\">P</b>', 'present', 'label label-success', 'yes', 1, 1, '2023-12-13 07:53:10', '2025-05-10 12:45:57'),
(2, 'Late With Excuse', '<b class=\"text text-warning\">E</b>', 'late_with_excuse', 'label label-warning text-dark', 'no', 0, 0, '2023-12-13 07:51:03', '0000-00-00 00:00:00'),
(3, 'Late', '<b class=\"text text-warning\">L</b>', 'late', 'label label-warning text-dark', 'yes', 1, 1, '2023-12-13 07:51:09', '2025-05-10 12:45:57'),
(4, 'Absent', '<b class=\"text text-danger\">A</b>', 'absent', 'label label-danger', 'yes', 0, 0, '2023-12-15 06:18:05', '0000-00-00 00:00:00'),
(5, 'Holiday', 'H', 'holiday', 'label label-info', 'yes', 0, 0, '2023-12-14 12:57:13', '0000-00-00 00:00:00'),
(6, 'Half Day', '<b class=\"text text-warning\">F</b>', 'half_day', 'label label-warning text-dark', 'yes', 1, 1, '2023-12-15 06:18:37', '2025-05-10 12:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `aws_s3_settings`
--

CREATE TABLE `aws_s3_settings` (
  `id` int(11) NOT NULL,
  `api_key` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `bucket_name` varchar(250) DEFAULT NULL,
  `region` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `behaviour_settings`
--

CREATE TABLE `behaviour_settings` (
  `id` int(11) NOT NULL,
  `comment_option` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `behaviour_settings`
--

INSERT INTO `behaviour_settings` (`id`, `comment_option`, `created_at`) VALUES
(1, '[\"student\",\"parent\"]', '2022-12-31 11:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_title` varchar(100) NOT NULL,
  `book_no` varchar(50) NOT NULL,
  `isbn_no` varchar(100) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `rack_no` varchar(100) NOT NULL,
  `publish` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `perunitcost` float(10,2) DEFAULT NULL,
  `postdate` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `available` varchar(10) DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_issues`
--

CREATE TABLE `book_issues` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `duereturn_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `is_returned` int(11) DEFAULT 0,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `captcha`
--

CREATE TABLE `captcha` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `captcha`
--

INSERT INTO `captcha` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'userlogin', 0, '2021-01-19 08:10:29', '2025-05-10 12:45:58'),
(2, 'login', 0, '2021-01-19 08:10:31', '2025-05-10 12:45:58'),
(3, 'admission', 0, '2021-01-19 04:48:11', '2025-05-10 12:45:58'),
(4, 'complain', 0, '2021-01-19 04:48:13', '2025-05-10 12:45:58'),
(5, 'contact_us', 0, '2021-01-19 04:48:15', '2025-05-10 12:45:58'),
(6, 'guest_login_signup', 0, '2022-12-07 07:11:31', '2025-05-22 11:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exams`
--

CREATE TABLE `cbse_exams` (
  `id` int(11) NOT NULL,
  `total_working_days` int(11) DEFAULT 0,
  `cbse_term_id` int(11) DEFAULT NULL,
  `cbse_exam_assessment_id` int(11) DEFAULT NULL,
  `cbse_exam_grade_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `exam_code` varchar(200) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `is_publish` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `use_exam_roll_no` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_assessments`
--

CREATE TABLE `cbse_exam_assessments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cbse_exam_assessments`
--

INSERT INTO `cbse_exam_assessments` (`id`, `name`, `description`) VALUES
(1, 'Termly Assessment', '');

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_assessment_types`
--

CREATE TABLE `cbse_exam_assessment_types` (
  `id` int(11) NOT NULL,
  `cbse_exam_assessment_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `maximum_marks` float NOT NULL,
  `pass_percentage` float NOT NULL,
  `description` mediumtext NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cbse_exam_assessment_types`
--

INSERT INTO `cbse_exam_assessment_types` (`id`, `cbse_exam_assessment_id`, `name`, `code`, `maximum_marks`, `pass_percentage`, `description`, `created_by`, `created_at`) VALUES
(1, 1, 'Online Exam', 'OE', 75, 40, 'Online Exam results', 1, '2025-06-20 11:27:35'),
(2, 1, 'Assignment', 'AT', 25, 15, 'Assignments', 1, '2025-06-20 11:27:35'),
(3, 1, 'Church Work Hours', 'CWH', 30, 15, 'Church serving hours', 1, '2025-06-20 11:27:35'),
(4, 1, 'Behaviour Records', 'BH', 10, 5, 'behaviour records', 1, '2025-06-20 11:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_class_sections`
--

CREATE TABLE `cbse_exam_class_sections` (
  `id` int(11) NOT NULL,
  `cbse_exam_id` int(11) NOT NULL,
  `class_section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_grades`
--

CREATE TABLE `cbse_exam_grades` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_grades_range`
--

CREATE TABLE `cbse_exam_grades_range` (
  `id` int(11) NOT NULL,
  `cbse_exam_grade_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `minimum_percentage` float NOT NULL,
  `maximum_percentage` float NOT NULL,
  `description` mediumtext NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_observations`
--

CREATE TABLE `cbse_exam_observations` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_students`
--

CREATE TABLE `cbse_exam_students` (
  `id` int(11) NOT NULL,
  `cbse_exam_id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `total_present_days` int(11) DEFAULT NULL,
  `delete_student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_student_subject_rank`
--

CREATE TABLE `cbse_exam_student_subject_rank` (
  `id` int(11) NOT NULL,
  `cbse_template_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `rank_percentage` float(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_timetable`
--

CREATE TABLE `cbse_exam_timetable` (
  `id` int(11) NOT NULL,
  `cbse_exam_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `duration` int(11) NOT NULL,
  `room_no` varchar(255) NOT NULL,
  `is_written` int(11) NOT NULL DEFAULT 1,
  `written_maximum_marks` float NOT NULL,
  `is_practical` int(11) NOT NULL,
  `practical_maximum_mark` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_exam_timetable_assessment_types`
--

CREATE TABLE `cbse_exam_timetable_assessment_types` (
  `id` int(11) NOT NULL,
  `cbse_exam_timetable_id` int(11) DEFAULT NULL,
  `cbse_exam_assessment_type_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_marksheet_type`
--

CREATE TABLE `cbse_marksheet_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cbse_marksheet_type`
--

INSERT INTO `cbse_marksheet_type` (`id`, `name`, `short_code`) VALUES
(1, 'All Terms', 'all_term'),
(2, 'Term Wise', 'term_wise'),
(3, 'Single Exam Without Term', 'exam_wise'),
(4, 'Multiple Exams Without Term', 'without_term');

-- --------------------------------------------------------

--
-- Table structure for table `cbse_observation_class_section`
--

CREATE TABLE `cbse_observation_class_section` (
  `id` int(11) NOT NULL,
  `cbse_observation_parameter_id` int(11) NOT NULL,
  `class_section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_observation_parameters`
--

CREATE TABLE `cbse_observation_parameters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_observation_subparameter`
--

CREATE TABLE `cbse_observation_subparameter` (
  `id` int(11) NOT NULL,
  `cbse_exam_observation_id` int(11) NOT NULL,
  `cbse_observation_parameter_id` int(11) NOT NULL,
  `maximum_marks` float NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_observation_terms`
--

CREATE TABLE `cbse_observation_terms` (
  `id` int(11) NOT NULL,
  `cbse_exam_observation_id` int(11) NOT NULL,
  `cbse_term_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_observation_term_student_subparameter`
--

CREATE TABLE `cbse_observation_term_student_subparameter` (
  `id` int(11) NOT NULL,
  `cbse_ovservation_term_id` int(11) DEFAULT NULL,
  `cbse_observation_subparameter_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `obtain_marks` float(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_student_exam_ranks`
--

CREATE TABLE `cbse_student_exam_ranks` (
  `id` int(11) NOT NULL,
  `cbse_exam_id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `rank_percentage` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_student_subject_marks`
--

CREATE TABLE `cbse_student_subject_marks` (
  `id` int(11) NOT NULL,
  `cbse_exam_timetable_assessment_type_id` int(11) NOT NULL,
  `cbse_exam_timetable_id` int(11) DEFAULT NULL,
  `cbse_exam_student_id` int(11) DEFAULT NULL,
  `cbse_exam_assessment_type_id` int(11) DEFAULT NULL,
  `is_absent` int(11) NOT NULL DEFAULT 0,
  `marks` float(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_student_subject_result`
--

CREATE TABLE `cbse_student_subject_result` (
  `id` int(11) NOT NULL,
  `cbse_exam_timetable_id` int(11) DEFAULT NULL,
  `cbse_exam_student_id` int(11) DEFAULT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_student_template_rank`
--

CREATE TABLE `cbse_student_template_rank` (
  `id` int(11) NOT NULL,
  `cbse_template_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `rank_percentage` float(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_template`
--

CREATE TABLE `cbse_template` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `orientation` varchar(1) NOT NULL DEFAULT 'P',
  `description` varchar(255) NOT NULL,
  `gradeexam_id` int(11) DEFAULT NULL,
  `remarkexam_id` int(11) DEFAULT NULL,
  `is_weightage` varchar(10) NOT NULL,
  `marksheet_type` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `header_image` varbinary(500) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `left_logo` varchar(200) DEFAULT NULL,
  `right_logo` varchar(200) DEFAULT NULL,
  `exam_name` varchar(200) DEFAULT NULL,
  `school_name` varchar(200) DEFAULT NULL,
  `exam_center` varchar(200) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `left_sign` varchar(200) DEFAULT NULL,
  `middle_sign` varchar(200) DEFAULT NULL,
  `right_sign` varchar(200) DEFAULT NULL,
  `background_img` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `content_footer` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `is_name` int(11) DEFAULT 1,
  `is_father_name` int(11) DEFAULT 1,
  `is_mother_name` int(11) DEFAULT 1,
  `exam_session` int(11) DEFAULT 1,
  `is_admission_no` int(11) DEFAULT 1,
  `is_division` int(11) NOT NULL DEFAULT 1,
  `is_roll_no` int(11) DEFAULT 1,
  `is_photo` int(11) DEFAULT 1,
  `is_class` int(11) NOT NULL DEFAULT 0,
  `is_section` int(11) NOT NULL DEFAULT 0,
  `is_dob` int(11) DEFAULT 1,
  `is_remark` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_template_class_sections`
--

CREATE TABLE `cbse_template_class_sections` (
  `id` int(11) NOT NULL,
  `cbse_template_id` int(11) NOT NULL,
  `class_section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_template_terms`
--

CREATE TABLE `cbse_template_terms` (
  `id` int(11) NOT NULL,
  `cbse_template_id` int(11) NOT NULL,
  `cbse_term_id` int(11) NOT NULL,
  `weightage` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_template_term_exams`
--

CREATE TABLE `cbse_template_term_exams` (
  `id` int(11) NOT NULL,
  `cbse_template_term_id` int(11) DEFAULT NULL,
  `cbse_exam_id` int(11) NOT NULL,
  `cbse_template_id` int(11) NOT NULL,
  `weightage` float NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cbse_terms`
--

CREATE TABLE `cbse_terms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `term_code` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `cbse_terms`
--

INSERT INTO `cbse_terms` (`id`, `name`, `term_code`, `description`, `created_by`, `created_at`) VALUES
(1, 'Term 1', 'T1', '', 1, '2025-06-20 11:18:33'),
(2, 'Term 2', 'T2', '', 1, '2025-06-20 11:18:44'),
(3, 'Term 3', 'T3', '', 1, '2025-06-20 11:18:56'),
(4, 'Term 4', 'T4', '', 1, '2025-06-20 11:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_name` varchar(100) NOT NULL,
  `certificate_text` text NOT NULL,
  `left_header` varchar(100) NOT NULL,
  `center_header` varchar(100) NOT NULL,
  `right_header` varchar(100) NOT NULL,
  `left_footer` varchar(100) NOT NULL,
  `right_footer` varchar(100) NOT NULL,
  `center_footer` varchar(100) NOT NULL,
  `background_image` varchar(100) DEFAULT NULL,
  `created_for` tinyint(1) NOT NULL COMMENT '1 = staff, 2 = students',
  `status` tinyint(1) NOT NULL,
  `header_height` int(11) NOT NULL,
  `content_height` int(11) NOT NULL,
  `footer_height` int(11) NOT NULL,
  `content_width` int(11) NOT NULL,
  `enable_student_image` tinyint(1) NOT NULL COMMENT '0=no,1=yes',
  `enable_image_height` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `certificate_name`, `certificate_text`, `left_header`, `center_header`, `right_header`, `left_footer`, `right_footer`, `center_footer`, `background_image`, `created_for`, `status`, `header_height`, `content_height`, `footer_height`, `content_width`, `enable_student_image`, `enable_image_height`, `created_at`, `updated_at`) VALUES
(1, 'Sample Transfer Certificate', 'This is certify that <b>[name]</b> has born on [dob]  <br> and have following details [present_address] [guardian] [created_at] [admission_no] [roll_no] [class] [section] [gender] [admission_date] [category] [cast] [father_name] [mother_name] [religion] [email] [phone] .<br>We wish best of luck for future endeavors.', 'Reff. No.....1111111.........', 'To Whomever It May Concern', 'Date: _10__/_10__/__2019__', '.................................<br>admin', '.................................<br>principal', '.................................<br>admin', 'sampletc121.png', 2, 1, 360, 400, 480, 810, 1, 230, '2019-12-21 15:14:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `chat_connections`
--

CREATE TABLE `chat_connections` (
  `id` int(11) NOT NULL,
  `chat_user_one` int(11) NOT NULL,
  `chat_user_two` int(11) NOT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `chat_user_id` int(11) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `time` int(11) NOT NULL,
  `is_first` int(11) DEFAULT 0,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `chat_connection_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_users`
--

CREATE TABLE `chat_users` (
  `id` int(11) NOT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `create_staff_id` int(11) DEFAULT NULL,
  `create_student_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('8mapp39hbnahjjfu9fjt33l55ml1n62b', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762176560, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363536303b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a31343a2261646d696e2f706172746e657273223b63617074636861436f64657c733a363a226f614d416d65223b),
('imf512nevcqh02902cl8ilbdinh13f8l', '59.103.32.168', 1762171719, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137313731383b706172746e65725f72656469726563745f75726c7c733a34323a2268747470733a2f2f7268656d617a696d62616277652e636f6d2f706172746e657264617368626f617264223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('u4nqlvbf302euaavca7t5u5crcm1iu8a', '2a06:98c0:3600::103', 1762172698, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137323639383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('ifafnkljbrstrfokjtp1p4bdnnnmq8sa', '2a06:98c0:3600::103', 1762172761, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137323736313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('b9da0na19lpdkmip0h99abok0m6eski7', '2a06:98c0:3600::103', 1762172786, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137323738363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('63aaicnc1n8qomug3t68m6ubpof6kqmj', '2a06:98c0:3600::103', 1762172810, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137323831303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('ou83m2l47j78658um1t9771844cf16l9', '170.199.224.30', 1762173243, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137333234323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('1bu57m000j5i2ccu6tq0bv2i0mjahq33', '2a06:98c0:3600::103', 1762173871, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137333837303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('mebo6cv1ng2pljacmvp8129olu3bu7fd', '2a06:98c0:3600::103', 1762173920, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137333932303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('o80pardqpp273mb26um39855rtah73o9', '2a06:98c0:3600::103', 1762173973, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137333937333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('51ms08hfm0sg2acjnchdc7ola758d5tv', '2a06:98c0:3600::103', 1762174042, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343034313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('6q2sij8r2jbruaug0pnuir3nr2hv15ci', '94.139.60.230', 1762174270, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343237303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('5ocmlhqsoiqtb0tr77k7u9q0145vp44i', '59.103.32.168', 1762174364, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343336333b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a2230456e717738223b),
('paable42too32s49n1h3eqpqbmrnsgk7', '59.103.32.168', 1762174376, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343337353b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a22444d5a526f71223b),
('cttujmcsb4plub12f10in0u4g64de2i7', '59.103.32.168', 1762174389, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343338383b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a226a3453556242223b),
('b5048bvjue7f219qo9k6tj1o57chgpha', '59.103.32.168', 1762174933, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343933333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b72656469726563745f746f5f757365727c733a34393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f757365722f757365722f64617368626f617264223b63617074636861436f64657c733a363a22364d49345348223b),
('sblqpruuf7c5m8raoj20v7sdo65hsfa9', '44.222.206.227', 1762174872, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343837323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('mu5oc6k9kturdv0a9gern0t91n5drepm', '59.103.32.168', 1762174904, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343930333b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a22614237726442223b),
('uug0ir83d9j8o58pp0cuiu35totp75t3', '59.103.32.168', 1762174905, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137343930343b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a22657862416338223b),
('c140ea8c2qb38khmb1073nbvt9pa09o1', '59.103.32.168', 1762175270, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353237303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b72656469726563745f746f5f757365727c733a34393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f757365722f757365722f64617368626f617264223b63617074636861436f64657c733a363a22364d49345348223b),
('965bqb89j4qjd4v4mvf07s2fevblbmhc', '2a06:98c0:3600::103', 1762175106, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353130363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('f7amekfjomlja00500aooru27l0q8p8r', '59.103.32.168', 1762176278, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363237383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d63617074636861436f64657c733a363a2268623234366a223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a31343a2261646d696e2f706172746e657273223b6d73677c733a37313a223c64697620636c6173733d22616c65727420616c6572742d73756363657373223e5065726d697373696f6e732075706461746564207375636365737366756c6c793c2f6469763e223b5f5f63695f766172737c613a313a7b733a333a226d7367223b733a333a226f6c64223b7d),
('4ebuhs4sj66ncf6aqcjnhr2c8i495bkf', '2a06:98c0:3600::103', 1762175195, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353139353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('8512plrtgl1gtalovhjamj08bcirnqs7', '2a06:98c0:3600::103', 1762175238, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353233383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('27ktsc7atnqmq8a1sbcllfl8do6sic2a', '59.103.32.168', 1762175666, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353636363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b72656469726563745f746f5f757365727c733a34393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f757365722f757365722f64617368626f617264223b63617074636861436f64657c733a363a22364d49345348223b),
('fn8rt2d3b17k0im8aoeejr2edq9gnspd', '54.87.195.79', 1762175306, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353330363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('l7q60bc45eq2qcf6fu1mtb9eb5kmcsii', '54.87.195.79', 1762175313, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353331333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('a45imn3t37tanjavsmhdstukc85gdio7', '2a06:98c0:3600::103', 1762175350, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353335303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('vm311vr440l15k265nb0conlnn8klb5l', '107.22.112.128', 1762175373, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353337333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('v3h07ft1j97m7o6j5cf265jodm4jsjem', '3.237.84.11', 1762175435, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353433353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('clhe8fvbosp9bd00e431kgbi6mfrrp3b', '59.103.32.168', 1762175563, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137353536323b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a2254325265356e223b),
('jjkbepoc140ge99a72q0649gvpaumb4f', '59.103.32.168', 1762176236, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363233363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b72656469726563745f746f5f757365727c733a34393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f757365722f757365722f64617368626f617264223b63617074636861436f64657c733a363a22364d49345348223b),
('ijdp6v2q465nlk59r23a3he8iu7u50f5', '216.234.213.27', 1762176189, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363038383b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b63617074636861436f64657c733a363a22443479584f68223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a32363a2261646d696e2f706172746e6572636f6e747269627574696f6e73223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('g14q6gl09823vbef46ucfr16vdi3uobm', '66.249.93.13', 1762176106, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363130363b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3337223b),
('bvgn3nl0pd6lfh20v190k3ml8h28rqur', '66.249.93.13', 1762176107, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363130373b),
('12p24s76dedle3ld72mt99ajf48f2dh1', '66.249.93.14', 1762176108, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363130383b63617074636861436f64657c733a363a22555141545337223b),
('3dkrh8l8gb3364heouhdhqf41npbghpp', '66.249.93.10', 1762176142, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363134323b72656469726563745f746f7c733a34343a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e657273223b),
('aef88lf26khpq9hhskjlu8mgltgs3588', '66.249.93.11', 1762176143, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363134333b),
('pt40160rcqgtuk9bhhp8s0ncliohabih', '66.249.93.11', 1762176144, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363134343b63617074636861436f64657c733a363a22307630417239223b),
('u5ajfhb1unrph1bj65kgl7gp5v2210cf', '66.249.93.5', 1762176180, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363138303b72656469726563745f746f7c733a35333a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7265717565737473223b),
('88n3sf8edddokanis11nob07jd5roe06', '66.249.93.5', 1762176182, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363138323b),
('7qs3n53vspql1t8dghlkkhf5gq36cpjc', '66.249.93.5', 1762176183, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363138333b63617074636861436f64657c733a363a226e3469334d6d223b),
('l0jv7r9krljo4b7ode9cb5docfnmqi25', '66.249.93.13', 1762176195, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363139353b72656469726563745f746f7c733a35363a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572636f6e747269627574696f6e73223b),
('6f7q7a6kav27i6qkbg3h54tiebl0o4ai', '66.249.93.14', 1762176197, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363139373b),
('hjs0plh1tvk96egj7bas1ds6ulceoetd', '66.249.93.14', 1762176204, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363230343b63617074636861436f64657c733a363a22773848506659223b),
('d7mqnp4on9uaannbb8q8pn0g9la14djo', '59.103.32.168', 1762176289, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363233363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b72656469726563745f746f5f757365727c733a34393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f757365722f757365722f64617368626f617264223b63617074636861436f64657c733a363a22364d49345348223b),
('r5if0tuoastur0t4p23cuohrv3pfimh4', '142.250.32.2', 1762176255, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363235343b72656469726563745f746f7c733a34363a2268747470733a2f2f7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e65727265706f727473223b),
('s16fuv6n5qkar0f95sqtim9lv4ce9abc', '142.250.32.3', 1762176256, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363235363b),
('sohml8pdknipdfa0vrags3l71cvg98hs', '142.250.32.2', 1762176257, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363235373b63617074636861436f64657c733a363a2277374f437637223b),
('hbgo0lup75m7r2jqe5pm86cqhl9s99p4', '59.103.32.168', 1762177163, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373136333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d63617074636861436f64657c733a363a2268623234366a223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a363a22496e636f6d65223b7375625f6d656e757c733a31323a22696e636f6d652f696e646578223b),
('mldjfebdamt5f38a232q0j39pi4uo5jo', '216.234.213.27', 1762176330, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363238333b63617074636861436f64657c733a363a22536e77735142223b73747564656e747c613a32323a7b733a323a226964223b733a313a2231223b733a31343a226c6f67696e5f757365726e616d65223b733a343a2273746431223b733a31303a2273747564656e745f6964223b733a313a2231223b733a343a22726f6c65223b733a373a2273747564656e74223b733a383a22757365726e616d65223b733a31353a22416d79204b616d7564796172697761223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31333a2263757272656e63795f6e616d65223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a31303a2273746172745f7765656b223b733a313a2231223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a353a227468656d65223b733a373a227265642e6a7067223b733a353a22696d616765223b733a32383a2275706c6f6164732f73747564656e745f696d616765732f312e6a7067223b733a363a2267656e646572223b733a363a2246656d616c65223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a363a2269735f72746c223b733a383a2264697361626c6564223b7d73657373696f6e5f61727261797c613a323a7b733a31303a2273657373696f6e5f6964223b733a323a223236223b733a373a2273657373696f6e223b733a393a22323032352d32303236223b7d63757272656e745f636c6173737c613a343a7b733a31303a2273657373696f6e5f6964223b733a323a223236223b733a383a22636c6173735f6964223b733a313a2231223b733a31303a2273656374696f6e5f6964223b733a313a2232223b733a31383a2273747564656e745f73657373696f6e5f6964223b733a313a2234223b7d746f705f6d656e757c733a31333a22757365725f706172746e657273223b),
('sisujoca2t0lju4s1atv2m5gmq8ratt4', '59.103.32.168', 1762177191, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373139313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('85kl4eget7fp5si0p7k34avoi5u1n3k8', '142.250.32.3', 1762176313, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363331333b746f705f6d656e757c733a31333a22757365725f706172746e657273223b66726f6e745f736974657c613a313a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b7d),
('un3nquhmjmdv9q4q6hgof69t3sjah4m0', '142.250.32.6', 1762176314, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363331343b746f705f6d656e757c733a31333a22757365725f706172746e657273223b66726f6e745f736974657c613a313a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b7d),
('tq588q11gpgg6sfivnr1pikeui7bt4aj', '66.102.8.101', 1762176314, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363331343b746f705f6d656e757c733a31333a22757365725f706172746e657273223b66726f6e745f736974657c613a313a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b7d),
('8gg9p31kg88hmfs4753j42gu4q6t436k', '2a06:98c0:3600::103', 1762176345, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363334353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('sfjnukc9u0st3d3jp303a645671shk6f', '213.188.66.75', 1762176450, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363435303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('oqade9fl8iu8m8v73j7cn0462ce7s9l7', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762176861, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363836313b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d746f705f6d656e757c733a31333a22757365725f706172746e657273223b7375625f6d656e757c733a31343a2261646d696e2f706172746e657273223b63617074636861436f64657c733a363a226f614d416d65223b706172746e65725f69647c733a323a223332223b706172746e65725f636f64657c733a31333a225054522d323032352d36303039223b706172746e65725f6e616d657c733a383a22417661204b616d75223b706172746e65725f656d61696c7c733a31373a226176616b616d7540676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b6572726f727c733a34323a22506c65617365206c6f67696e20746f2061636365737320706172746e6572206d616e6167656d656e742e223b5f5f63695f766172737c613a313a7b733a353a226572726f72223b733a333a226e6577223b7d),
('1lqo130nuo55qlku71kotulqr94khd81', '41.173.245.72', 1762176584, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363538343b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('5kst3cn190idcv3e5ba467bm7aakldf7', '3.237.84.11', 1762176589, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137363538393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('2htepjdvn4ta7mgomv0q8obmj7811d17', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762177492, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373439323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('rqj2ffh5mda3r0h5bsoc5hhkcr7k6522', '59.103.32.168', 1762181677, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313637373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d63617074636861436f64657c733a363a2268623234366a223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a31343a2261646d696e2f706172746e657273223b),
('71jlt9632ne1cid1jbpqktc79nhc23qi', '59.103.32.168', 1762177498, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373439383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('sjoabhi42ug45or66cfrpg615u3nvvd9', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762178755, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383735353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223332223b706172746e65725f636f64657c733a31333a225054522d323032352d36303039223b706172746e65725f6e616d657c733a383a22417661204b616d75223b706172746e65725f656d61696c7c733a31373a226176616b616d7540676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('kufvfhn3lage0rmvb6clmhq9e51vdmqp', '59.103.32.168', 1762181461, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313436313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('clttolna3vn5734qt9vher9cpbunoo74', '34.91.212.219', 1762177502, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373530323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('ue6l3lv5s7cti3pphan42l94aph3jer5', '3.237.84.11', 1762177563, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373536333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('cisejhm2fv95les9f55nekf0i135nr6j', '2a06:98c0:3600::103', 1762177648, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373634383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('9s0bve3po155gg68m3dv01gnfkveqanh', '2a06:98c0:3600::103', 1762177672, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373637323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d);
INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('fooetnjbbppjmjacv8a5hj3equ0dag7s', '2a06:98c0:3600::103', 1762177791, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373739313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('opavu6g2760tdv9cq0m4ce4lkmlfuje2', '2a06:98c0:3600::103', 1762177800, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373830303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('eq16kfiikboh2pcd3k7ukr8m0786s8v3', '172.82.65.38', 1762177859, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137373835393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('fkohuasm1r0m764geiaonut00it9n0mv', '3.235.60.156', 1762178570, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383536393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('gc05anest9d4k5f83rm65udnqtqrlgo8', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762178973, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383735353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223332223b706172746e65725f636f64657c733a31333a225054522d323032352d36303039223b706172746e65725f6e616d657c733a383a22417661204b616d75223b706172746e65725f656d61696c7c733a31373a226176616b616d7540676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b63617074636861436f64657c733a363a224f4236477831223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a31313a22436f6d6d756e6963617465223b7375625f6d656e757c733a32373a22436f6d6d756e69636174652f6d61696c736d732f636f6d706f7365223b),
('o9u3i60o62osknjlsecqa9t3379jbp0k', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 1762178790, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383736383b706172746e65725f72656469726563745f75726c7c733a34323a2268747470733a2f2f7268656d617a696d62616277652e636f6d2f706172746e657264617368626f617264223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d63617074636861436f64657c733a363a22323436497556223b),
('fugjqeqbftbsikcblpv9phhmeoahooko', '2a06:98c0:3600::103', 1762178804, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383830343b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('4tvjvj06jl8p9hu6ekhpp04mebk3do0b', '2a06:98c0:3600::103', 1762178829, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383832393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('04dujtc5eeg20djpib10ts5mn6c1vrvs', '2a06:98c0:3600::103', 1762178928, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383932383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('64k6rd96c69tsbgfr07oqbvrrt6cbidh', '2a06:98c0:3600::103', 1762178996, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323137383939363b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('t79g94ejcnr3k9649m2tfn5ebibo8f95', '2a06:98c0:3600::103', 1762180015, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138303031353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('mk185epalc2mjomjm0218btg05vv8stn', '2a06:98c0:3600::103', 1762180071, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138303037313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('dju7lu6nm9s1ce5lfncoh4h5cvv9qdni', '2a06:98c0:3600::103', 1762180156, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138303135353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('4qe8g3ebjcq6ic45501q8stbdonsrgi1', '2a06:98c0:3600::103', 1762180170, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138303137303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('2d8j9mrl6v22gtl079bj43k7ena32vnd', '66.249.65.64', 1762181087, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313038373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('p68bsrs4bpkis2b89alhbaoei76682ce', '2a06:98c0:3600::103', 1762181393, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313339333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('r7e6bnd7tju6h2jc0mccpcidinctras6', '2a06:98c0:3600::103', 1762181439, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313433393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('83flisukfuf3brvba0n16himd2g03dps', '59.103.32.168', 1762181819, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313831393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('v878gbi3h4ab1vdv78s2477dspttr8rm', '2a06:98c0:3600::103', 1762181497, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313439373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('q6jcc6vii832gl18kd2io23cl2u3hq2g', '2a06:98c0:3600::103', 1762181582, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313538323b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('18ih6or9r63vl1jdcmdue6jb98aq8ts4', '59.103.32.168', 1762181705, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313637373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d63617074636861436f64657c733a363a2268623234366a223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a31353a2253797374656d2053657474696e6773223b7375625f6d656e757c733a31373a2273636873657474696e67732f696e646578223b7375627375625f6d656e757c733a31373a2273636873657474696e67732f696e646578223b),
('4pm0dfdpa4nb9lvaqilgqdjh7od8fd1c', '59.103.32.168', 1762182648, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323634383b72656469726563745f746f7c733a34313a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f73636873657474696e6773223b63617074636861436f64657c733a363a226b6863464d70223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a31353a2253797374656d2053657474696e6773223b7375625f6d656e757c733a31373a2273636873657474696e67732f696e646578223b7375627375625f6d656e757c733a31373a2273636873657474696e67732f696e646578223b),
('q5ilevedbi2q28ssf4nofp71bv4g3q0j', '59.103.32.168', 1762182523, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323532333b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b),
('nscf77li1oafj5b7ckqslhgqjis9buoj', '51.195.215.10', 1762181971, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138313937313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('is960ghiqld6fu18grns2g7sld2q95sc', '98.80.6.17', 1762182269, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323236393b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('fes8avmk17acvks9ibotrge0mfpmvtp2', '59.103.32.168', 1762183004, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333030343b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b746f705f6d656e757c733a31333a22757365725f706172746e657273223b),
('n6kq0b9jisgarv1vmsrqdcqgoqldklv0', '3.237.84.11', 1762182615, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323631353b706172746e65725f72656469726563745f75726c7c733a36303a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f706172746e657264617368626f6172642f636f6e747269627574696f6e73223b),
('5v5skjv31sm0sart6876s9t9tiv0hrq1', '3.237.84.11', 1762182617, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323631373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('p7sl1u2sk6lpqhqaqsjgqr4fl9r33cki', '3.237.84.11', 1762182618, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323631383b706172746e65725f72656469726563745f75726c7c733a36323a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f706172746e657264617368626f6172642f676976696e672d73657474696e6773223b),
('sbu7ph7oonf1cg5v163donjjnfqcnn1m', '3.237.84.11', 1762182621, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323632313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('b8igdht4ddvqk4lirtc1chgmvkjac24s', '2a06:98c0:3600::103', 1762182640, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323634303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('hbqsf0i50huemalir14v2barhdvncj0s', '59.103.32.168', 1762182871, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323634383b72656469726563745f746f7c733a34313a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f73636873657474696e6773223b63617074636861436f64657c733a363a226b6863464d70223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a31343a2261646d696e2f706172746e657273223b7375627375625f6d656e757c733a31373a2273636873657474696e67732f696e646578223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('3c7ao6e8o46sa1v4am05dckb4q3t27ut', '2a06:98c0:3600::103', 1762182797, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323739373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('tkfqub1u30s24k5arn02nabmma16bap4', '2a06:98c0:3600::103', 1762182807, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138323830373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('dqlkp042r8207doh5s8upfhiioa73mga', '59.103.32.168', 1762183222, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333232323b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3237223b63617074636861436f64657c733a363a22694b534b6274223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a32323a2261646d696e2f706172746e65725f73657474696e6773223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('73j9i79j7kf3t7ourkog6s4tbkck7ekv', '59.103.32.168', 1762183865, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333836353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b746f705f6d656e757c733a31333a22757365725f706172746e657273223b72656469726563745f746f7c733a35323a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e65725f73657474696e6773223b63617074636861436f64657c733a363a22506e34317748223b),
('1m7aj4fc2guchtepp0qosbthuvkukhr3', '59.103.32.168', 1762183167, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333136363b72656469726563745f746f7c733a34373a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f676976696e677479706573223b63617074636861436f64657c733a363a223653654e7448223b),
('mt1clc1j7fjur4cb61tjch72hng7q0g3', '59.103.32.168', 1762183567, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333536373b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3237223b63617074636861436f64657c733a363a22694b534b6274223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a353a22676d656574223b7375625f6d656e757c733a31393a22676d6565742f676d6565745f73657474696e67223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('db5i6j7btdkuorqvda2e9rtlag0j5mm4', '49.51.196.42', 1762183461, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333436313b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('la71vkngb1o6arphnv75ip9dalv3biar', '59.103.32.168', 1762183871, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333837313b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3237223b63617074636861436f64657c733a363a22694b534b6274223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a32363a2261646d696e2f706172746e6572636f6e747269627574696f6e73223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d6d73677c733a36343a223c64697620636c6173733d22616c65727420616c6572742d64616e676572223e4661696c656420746f2061646420636f6e747269627574696f6e3c2f6469763e223b5f5f63695f766172737c613a313a7b733a333a226d7367223b733a333a226f6c64223b7d),
('ml094tihmjk0392bshpjlflc5nc58096', '59.103.32.168', 1762183866, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333836353b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d706172746e65725f69647c733a323a223237223b706172746e65725f636f64657c733a31333a225054522d323032352d30333533223b706172746e65725f6e616d657c733a31323a224461776f6f64205a61666172223b706172746e65725f656d61696c7c733a32343a226461776f6f642e6469786564616d40676d61696c2e636f6d223b706172746e65725f6c6f676765645f696e7c623a313b746f705f6d656e757c733a31333a22757365725f706172746e657273223b72656469726563745f746f7c733a35323a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e65725f73657474696e6773223b63617074636861436f64657c733a363a22506e34317748223b),
('vug9ce7ur4vqiav9vkcilq0e54868e4j', '59.103.32.168', 1762184044, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333837313b72656469726563745f746f7c733a35393a2268747470733a2f2f7777772e7268656d617a696d62616277652e636f6d2f61646d696e2f706172746e6572732f7065726d697373696f6e732f3237223b63617074636861436f64657c733a363a22694b534b6274223b61646d696e7c613a32373a7b733a323a226964223b733a313a2231223b733a383a22757365726e616d65223b733a31313a2253757065722041646d696e223b733a353a22656d61696c223b733a32313a226b7564614074656e67616e696d616e6a652e636f6d223b733a353a22696d616765223b733a303a22223b733a353a22726f6c6573223b613a313a7b733a31313a2253757065722041646d696e223b733a313a2237223b7d733a31313a22646174655f666f726d6174223b733a353a22642d6d2d59223b733a383a2263757272656e6379223b733a333a22313738223b733a31393a2263757272656e63795f626173655f7072696365223b733a323a223234223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31353a2263757272656e63795f73796d626f6c223b733a333a225a4d57223b733a31343a2263757272656e63795f706c616365223b733a31323a2261667465725f6e756d626572223b733a31313a2273746172745f6d6f6e7468223b733a313a2231223b733a31303a2273746172745f7765656b223b733a313a2231223b733a31313a227363686f6f6c5f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a2274696d657a6f6e65223b733a31333a224166726963612f4c7573616b61223b733a383a227363685f6e616d65223b733a33363a225268656d61204269626c6520547261696e696e672043656e747265205a696d6261627765223b733a383a226c616e6775616765223b613a323a7b733a373a226c616e675f6964223b733a313a2234223b733a383a226c616e6775616765223b733a373a22456e676c697368223b7d733a363a2269735f72746c223b733a383a2264697361626c6564223b733a353a227468656d65223b733a373a227265642e6a7067223b733a363a2267656e646572223b733a343a224d616c65223b733a32323a22737570657261646d696e5f7265737472696374696f6e223b733a373a22656e61626c6564223b733a383a2264625f6172726179223b613a333a7b733a383a22626173655f75726c223b733a32363a2268747470733a2f2f7268656d617a696d62616277652e6f72672f223b733a31313a22666f6c6465725f70617468223b733a33393a222f7661722f7777772f7268656d617a696d62616277652e6f72672f7075626c69635f68746d6c2f223b733a383a2264625f67726f7570223b733a373a2264656661756c74223b7d733a383a22736161735f6b6579223b4e3b733a32303a2261646d696e5f70616e656c5f7768617473617070223b733a313a2231223b733a32373a2261646d696e5f70616e656c5f77686174736170705f6d6f62696c65223b733a31333a222b323630393639373331343737223b733a32353a2261646d696e5f70616e656c5f77686174736170705f66726f6d223b733a383a2230383a30303a3030223b733a32333a2261646d696e5f70616e656c5f77686174736170705f746f223b733a383a2231373a30303a3030223b7d746f705f6d656e757c733a383a22506172746e657273223b7375625f6d656e757c733a32363a2261646d696e2f706172746e6572636f6e747269627574696f6e73223b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('p46nae19q5bcjmf5ufqajmcki9cbdren', '3.237.84.11', 1762183897, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138333839373b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('qolbn0064mf3a775mpn6i51vhe6dehg7', '2a06:98c0:3600::103', 1762184038, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138343033383b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d),
('p0p7jh78cqpr1o1ornsstjp84c3mkl5u', '2a06:98c0:3600::103', 1762184101, 0x5f5f63695f6c6173745f726567656e65726174657c693a313736323138343130303b66726f6e745f736974657c613a353a7b733a31353a2263757272656e63795f73796d626f6c223b733a313a2224223b733a31353a2263757272656e63795f666f726d6174223b733a383a22232c2323232e2323223b733a31393a2263757272656e63795f626173655f7072696365223b733a313a2231223b733a383a2263757272656e6379223b733a333a22313530223b733a31333a2263757272656e63795f6e616d65223b733a333a22555344223b7d);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Second Year', 'no', '2025-05-26 09:20:26', '2025-05-26 09:22:15'),
(2, 'First Year', 'no', '2025-05-26 09:20:35', '2025-05-26 09:22:02'),
(3, 'Test Class', 'yes', '2025-10-10 15:20:29', '2025-10-10 15:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `class_sections`
--

INSERT INTO `class_sections` (`id`, `class_id`, `section_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'no', '2025-05-26 09:20:26', '2025-05-26 09:20:26'),
(2, 1, 3, 'no', '2025-05-26 09:20:26', '2025-05-26 09:20:26'),
(3, 2, 2, 'no', '2025-05-26 09:20:35', '2025-05-26 09:20:35'),
(4, 2, 3, 'no', '2025-05-26 09:20:35', '2025-05-26 09:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `class_section_times`
--

CREATE TABLE `class_section_times` (
  `id` int(11) NOT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `class_section_times`
--

INSERT INTO `class_section_times` (`id`, `class_section_id`, `time`, `created_at`, `updated_at`) VALUES
(1, 2, '18:00:00', '2025-05-27 06:27:27', '2025-05-27 06:27:27'),
(2, 1, '09:00:00', '2025-05-27 06:27:27', '2025-05-27 06:27:27'),
(3, 4, '18:00:00', '2025-05-27 06:27:27', '2025-05-27 06:27:27'),
(4, 3, '09:00:00', '2025-05-27 06:27:27', '2025-05-27 06:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `class_teacher`
--

CREATE TABLE `class_teacher` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `class_teacher`
--

INSERT INTO `class_teacher` (`id`, `session_id`, `class_id`, `section_id`, `staff_id`) VALUES
(1, 21, 2, 2, 2),
(2, 21, 2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `id` int(11) NOT NULL,
  `complaint_type` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `action_taken` varchar(200) NOT NULL,
  `assigned` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`id`, `complaint_type`, `source`, `name`, `contact`, `email`, `date`, `description`, `action_taken`, `assigned`, `note`, `image`, `created_at`, `updated_at`) VALUES
(1, 'General', 'Online', 'ava', '09685155218', 'ava@gmail.com', '2025-07-28', 'testing', '', '', '', NULL, '2025-07-28 16:34:06', '2025-07-28 16:34:06'),
(0, 'General', 'Online', 'Andrewmaf', 'no.reply.Pierre', 'no.reply.PierreMercier@gmail.com', '2025-10-31', 'Hi-ya! rhemazimbabwe.com \r\n \r\nDid you know that it is possible to send letter completely legitimately? \r\nWhen such business offers are sent, no personal data is used, and messages are sent to forms specifically designed to receive messages and appeals safely and securely. The importance of messages sent through Communication Forms reduces the chance of them being treated as spam. \r\nWe gіve уou the chance to test our service for nothing! \r\nWe can deliver up to 50,000 messages to you. \r\n \r\nThe cost of sending one million messages is $59. \r\n \r\nThis message was automatically generated. \r\n \r\nContact us. \r\nTelegram - https://t.me/FeedbackFormEU \r\nWhatsApp - +375259112693 \r\nWhatsApp  https://wa.me/+375259112693 \r\nWe only use chat for communication.', '', '', '', NULL, '2025-10-31 12:04:44', '2025-10-31 12:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_type`
--

CREATE TABLE `complaint_type` (
  `id` int(11) NOT NULL,
  `complaint_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conferences`
--

CREATE TABLE `conferences` (
  `id` int(11) NOT NULL,
  `purpose` varchar(20) NOT NULL DEFAULT 'class',
  `staff_id` int(11) DEFAULT NULL,
  `created_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `host_video` int(11) NOT NULL DEFAULT 1,
  `client_video` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `return_response` text DEFAULT NULL,
  `api_type` varchar(30) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conferences_history`
--

CREATE TABLE `conferences_history` (
  `id` int(11) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `total_hit` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conference_sections`
--

CREATE TABLE `conference_sections` (
  `id` int(11) NOT NULL,
  `conference_id` int(11) DEFAULT NULL,
  `cls_section_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conference_staff`
--

CREATE TABLE `conference_staff` (
  `id` int(11) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_public` varchar(10) DEFAULT 'No',
  `class_id` int(11) DEFAULT NULL,
  `cls_sec_id` int(11) DEFAULT NULL,
  `file` varchar(250) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_for`
--

CREATE TABLE `content_for` (
  `id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_types`
--

CREATE TABLE `content_types` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `content_types`
--

INSERT INTO `content_types` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'CLASS AUDIO RECORDINGS', 'Access is granted to students for their revision and for when they miss class. To be made available a week from the date of recording and valid till the end of the term. Avail both morning and evening recordings.', 1, '2025-05-29 12:37:29', '2025-06-05 07:34:47'),
(2, 'FAITH LIBRARY', 'Audio and pdf books from the Faith Library (Can also be accessed by partners)', 1, '2025-06-04 08:29:08', '2025-06-05 07:32:30'),
(3, 'CHAPEL', 'Partners can access and watch every chapel that is happening on campus', 1, '2025-06-05 07:30:13', '2025-06-05 07:31:27'),
(4, 'PRAYER SCHOOL', 'Partners can access and watch every chapel that is happening on campus', 1, '2025-06-05 07:30:24', '2025-06-05 07:31:50'),
(5, 'Life Of Faith', '', 1, '2025-06-18 08:02:57', '2025-06-18 08:02:57');

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE `course_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`id`, `category_name`, `slug`, `is_active`) VALUES
(1, 'Bible Study', NULL, NULL),
(2, 'The Holy Spirit', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_lesson_quiz_order`
--

CREATE TABLE `course_lesson_quiz_order` (
  `id` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `course_section_id` int(11) DEFAULT NULL,
  `lesson_quiz_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_progress`
--

CREATE TABLE `course_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course_section_id` int(11) DEFAULT NULL,
  `lesson_quiz_id` int(11) DEFAULT NULL,
  `lesson_quiz_type` int(11) DEFAULT NULL COMMENT '1 lesson, 2 quiz, 3 assignment,4 exam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_quiz_answer`
--

CREATE TABLE `course_quiz_answer` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `course_quiz_id` int(11) DEFAULT NULL,
  `course_quiz_question_id` int(11) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_quiz_question`
--

CREATE TABLE `course_quiz_question` (
  `id` int(11) NOT NULL,
  `course_quiz_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option_1` varchar(255) DEFAULT NULL,
  `option_2` varchar(255) DEFAULT NULL,
  `option_3` varchar(255) DEFAULT NULL,
  `option_4` varchar(255) DEFAULT NULL,
  `option_5` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_rating`
--

CREATE TABLE `course_rating` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `rating` varchar(200) NOT NULL,
  `review` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cumulative_fine`
--

CREATE TABLE `cumulative_fine` (
  `id` int(11) NOT NULL,
  `overdue_day` int(11) NOT NULL,
  `fine_amount` float(10,2) NOT NULL,
  `fee_groups_feetype_id` int(11) NOT NULL,
  `fee_session_group_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_name` varchar(100) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `base_price` varchar(10) NOT NULL DEFAULT '1',
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `short_name`, `symbol`, `base_price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'AED', 'AED', 'AEDf', '1', 0, '2022-12-30 06:19:15', '2025-05-10 12:45:59'),
(2, 'AFN', 'AFN', '؋', '1', 0, '2022-12-30 06:19:19', '2025-05-10 12:45:59'),
(3, 'ALL', 'ALL', 'ALL', '1', 0, '2022-12-30 06:19:22', '2025-05-10 12:45:59'),
(4, 'AMD', 'AMD', 'AMD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(5, 'ANG', 'ANG', 'ANG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(6, 'AOA', 'AOA', 'AOA', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(7, 'ARS', 'ARS', 'ARS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(8, 'AUD', 'AUD', 'AUD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(9, 'AWG', 'AWG', 'AWG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(10, 'AZN', 'AZN', 'AZN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(11, 'BAM', 'BAM', 'BAM', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(12, 'BAM', 'BAM', 'BAM', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(13, 'BDT', 'BDT', 'BDT', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(14, 'BGN', 'BGN', 'BGN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(15, 'BHD', 'BHD', 'BHD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(16, 'BIF', 'BIF', 'BIF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(17, 'BMD', 'BMD', 'BMD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(18, 'BND', 'BND', 'BND', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(19, 'BOB', 'BOB', 'BOB', '1', 0, '2022-12-30 06:19:29', '2025-05-10 12:45:59'),
(20, 'BOV', 'BOV', 'BOV', '1', 0, '2022-12-30 06:19:38', '2025-05-10 12:45:59'),
(21, 'BRL', 'BRL', 'BRL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(22, 'BSD', 'BSD', 'BSD', '1', 0, '2022-12-30 06:19:40', '2025-05-10 12:45:59'),
(23, 'BTN', 'BTN', 'BTN', '1', 0, '2022-12-30 06:19:42', '2025-05-10 12:45:59'),
(24, 'BWP', 'BWP', 'BWP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(25, 'BYN', 'BYN', 'BYN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(26, 'BYR', 'BYR', 'BYR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(27, 'BZD', 'BZD', 'BZD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(28, 'CAD', 'CAD', 'CAD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(29, 'CDF', 'CDF', 'CDF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(30, 'CHE', 'CHE', 'CHE', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(31, 'CHF', 'CHF', 'CHF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(32, 'CHW', 'CHW', 'CHW', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(33, 'CLF', 'CLF', 'CLF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(34, 'CLP', 'CLP', 'CLP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(35, 'CNY', 'CNY', 'CNY', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(36, 'COP', 'COP', 'COP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(37, 'COU', 'COU', 'COU', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(38, 'CRC', 'CRC', 'CRC', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(39, 'CUC', 'CUC', 'CUC', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(40, 'CUP', 'CUP', 'CUP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(41, 'CVE', 'CVE', 'CVE', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(42, 'CZK', 'CZK', 'CZK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(43, 'DJF', 'DJF', 'DJF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(44, 'DKK', 'DKK', 'DKK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(45, 'DOP', 'DOP', 'DOP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(46, 'DZD', 'DZD', 'DZD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(47, 'EGP', 'EGP', 'EGP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(48, 'ERN', 'ERN', 'ERN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(49, 'ETB', 'ETB', 'ETB', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(50, 'EUR', 'EUR', '€', '1', 0, '2022-12-30 06:20:25', '2025-05-10 12:45:59'),
(51, 'FJD', 'FJD', 'FJD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(52, 'FKP', 'FKP', 'FKP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(53, 'GBP', 'GBP', '£', '1', 0, '2022-12-30 06:20:29', '2025-05-10 12:45:59'),
(54, 'GEL', 'GEL', 'GEL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(55, 'GHS', 'GHS', 'GHS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(56, 'GIP', 'GIP', 'GIP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(57, 'GMD', 'GMD', 'GMD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(58, 'GNF', 'GNF', 'GNF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(59, 'GTQ', 'GTQ', 'GTQ', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(60, 'GYD', 'GYD', 'GYD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(61, 'HKD', 'HKD', 'HKD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(62, 'HNL', 'HNL', 'HNL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(63, 'HRK', 'HRK', 'HRK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(64, 'HTG', 'HTG', 'HTG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(65, 'HUF', 'HUF', 'HUF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(66, 'IDR', 'IDR', 'IDR', '1', 0, '2022-12-30 06:20:34', '2025-05-10 12:45:59'),
(67, 'ILS', 'ILS', 'ILS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(68, 'INR', 'INR', '₹', '1', 0, '2022-12-30 06:20:37', '2025-05-10 12:45:59'),
(69, 'IQD', 'IQD', 'IQD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(70, 'IRR', 'IRR', 'IRR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(71, 'ISK', 'ISK', 'ISK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(72, 'JMD', 'JMD', 'JMD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(73, 'JOD', 'JOD', 'JOD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(74, 'JPY', 'JPY', 'JPY', '1', 0, '2022-12-30 06:19:56', '2025-05-10 12:45:59'),
(75, 'KES', 'KES', 'KES', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(76, 'KGS', 'KGS', 'KGS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(77, 'KHR', 'KHR', 'KHR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(78, 'KMF', 'KMF', 'KMF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(79, 'KPW', 'KPW', 'KPW', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(80, 'KRW', 'KRW', 'KRW', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(81, 'KWD', 'KWD', 'KWD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(82, 'KYD', 'KYD', 'KYD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(83, 'KZT', 'KZT', 'KZT', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(84, 'LAK', 'LAK', 'LAK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(85, 'LBP', 'LBP', 'LBP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(86, 'LKR', 'LKR', 'LKR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(87, 'LRD', 'LRD', 'LRD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(88, 'LSL', 'LSL', 'LSL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(89, 'LYD', 'LYD', 'LYD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(90, 'MAD', 'MAD', 'MAD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(91, 'MDL', 'MDL', 'MDL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(92, 'MGA', 'MGA', 'MGA', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(93, 'MKD', 'MKD', 'MKD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(94, 'MMK', 'MMK', 'MMK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(95, 'MNT', 'MNT', 'MNT', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(96, 'MOP', 'MOP', 'MOP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(97, 'MRO', 'MRO', 'MRO', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(98, 'MUR', 'MUR', 'MUR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(99, 'MVR', 'MVR', 'MVR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(100, 'MWK', 'MWK', 'MWK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(101, 'MXN', 'MXN', 'MXN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(102, 'MXV', 'MXV', 'MXV', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(103, 'MYR', 'MYR', 'MYR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(104, 'MZN', 'MZN', 'MZN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(105, 'NAD', 'NAD', 'NAD', '1', 0, '2022-07-30 09:32:37', '2025-05-10 12:45:59'),
(106, 'NGN', 'NGN', 'NGN', '1', 0, '2022-12-30 06:20:42', '2025-05-10 12:45:59'),
(107, 'NIO', 'NIO', 'NIO', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(108, 'NOK', 'NOK', 'NOK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(109, 'NPR', 'NPR', 'NPR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(110, 'NZD', 'NZD', 'NZD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(111, 'OMR', 'OMR', 'OMR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(112, 'PAB', 'PAB', 'PAB', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(113, 'PEN', 'PEN', 'PEN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(114, 'PGK', 'PGK', 'PGK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(115, 'PHP', 'PHP', 'PHP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(116, 'PKR', 'PKR', 'PKR', '1', 0, '2022-12-30 06:20:19', '2025-05-10 12:45:59'),
(117, 'PLN', 'PLN', 'PLN', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(118, 'PYG', 'PYG', 'PYG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(119, 'QAR', 'QAR', 'QAR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(120, 'RON', 'RON', 'RON', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(121, 'RSD', 'RSD', 'RSD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(122, 'RUB', 'RUB', 'RUB', '1', 0, '2022-12-30 06:20:16', '2025-05-10 12:45:59'),
(123, 'RWF', 'RWF', 'RWF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(124, 'SAR', 'SAR', 'SAR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(125, 'SBD', 'SBD', 'SBD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(126, 'SCR', 'SCR', 'SCR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(127, 'SDG', 'SDG', 'SDG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(128, 'SEK', 'SEK', 'SEK', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(129, 'SGD', 'SGD', 'SGD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(130, 'SHP', 'SHP', 'SHP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(131, 'SLL', 'SLL', 'SLL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(132, 'SOS', 'SOS', 'SOS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(133, 'SRD', 'SRD', 'SRD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(134, 'SSP', 'SSP', 'SSP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(135, 'STD', 'STD', 'STD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(136, 'SVC', 'SVC', 'SVC', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(137, 'SYP', 'SYP', 'SYP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(138, 'SZL', 'SZL', 'SZL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(139, 'THB', 'THB', 'THB', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(140, 'TJS', 'TJS', 'TJS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(141, 'TMT', 'TMT', 'TMT', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(142, 'TND', 'TND', 'TND', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(143, 'TOP', 'TOP', 'TOP', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(144, 'TRY', 'TRY', 'TRY', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(145, 'TTD', 'TTD', 'TTD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(146, 'TWD', 'TWD', 'TWD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(147, 'TZS', 'TZS', 'TZS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(148, 'UAH', 'UAH', 'UAH', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(149, 'UGX', 'UGX', 'UGX', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(150, 'USD', 'USD', '$', '1', 1, '2022-07-22 10:55:15', '2025-08-05 12:56:11'),
(151, 'USN', 'USN', 'USN', '1', 0, '2022-12-30 06:20:03', '2025-05-10 12:45:59'),
(152, 'UYI', 'UYI', 'UYI', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(153, 'UYU', 'UYU', 'UYU', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(154, 'UZS', 'UZS', 'UZS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(155, 'VEF', 'VEF', 'VEF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(156, 'VND', 'VND', 'VND', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(157, 'VUV', 'VUV', 'VUV', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(158, 'WST', 'WST', 'WST', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(159, 'XAF', 'XAF', 'XAF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(160, 'XAG', 'XAG', 'XAG', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(161, 'XAU', 'XAU', 'XAU', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(162, 'XBA', 'XBA', 'XBA', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(163, 'XBB', 'XBB', 'XBB', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(164, 'XBC', 'XBC', 'XBC', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(165, 'XBD', 'XBD', 'XBD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(166, 'XCD', 'XCD', 'XCD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(167, 'XDR', 'XDR', 'XDR', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(168, 'XOF', 'XOF', 'XOF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(169, 'XPD', 'XPD', 'XPD', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(170, 'XPF', 'XPF', 'XPF', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(171, 'XPT', 'XPT', 'XPT', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(172, 'XSU', 'XSU', 'XSU', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(173, 'XTS', 'XTS', 'XTS', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(174, 'XUA', 'XUA', 'XUA', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(175, 'XXX', 'XXX', 'XXX', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(176, 'YER', 'YER', 'YER', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59'),
(177, 'ZAR', 'ZAR', 'ZAR', '1', 0, '2022-12-30 06:20:07', '2025-05-10 12:45:59'),
(178, 'ZMW', 'ZMW', 'ZMW', '24', 1, '2022-07-30 07:34:00', '2025-08-05 12:56:42'),
(179, 'ZWL', 'ZWL', 'ZWL', '1', 0, '2022-07-22 10:55:15', '2025-05-10 12:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `belong_to` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `bs_column` int(11) DEFAULT NULL,
  `validation` int(11) DEFAULT 0,
  `field_values` text DEFAULT NULL,
  `show_table` varchar(100) DEFAULT NULL,
  `visible_on_table` int(11) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` int(11) NOT NULL,
  `belong_table_id` int(11) DEFAULT NULL,
  `custom_field_id` int(11) DEFAULT NULL,
  `field_value` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_assignment`
--

CREATE TABLE `daily_assignment` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `subject_group_subject_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `evaluated_by` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `remark` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Faculty', 'yes', '2025-06-12 09:14:12', '2025-06-12 09:14:12');

-- --------------------------------------------------------

--
-- Table structure for table `disable_reason`
--

CREATE TABLE `disable_reason` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_receive`
--

CREATE TABLE `dispatch_receive` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `to_title` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `address` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL,
  `from_title` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_attachments`
--

CREATE TABLE `email_attachments` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `attachment_name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `email_type` varchar(100) DEFAULT NULL,
  `smtp_server` varchar(100) DEFAULT NULL,
  `smtp_port` varchar(100) DEFAULT NULL,
  `smtp_email` varchar(255) DEFAULT NULL,
  `smtp_username` varchar(100) DEFAULT NULL,
  `smtp_password` varchar(100) DEFAULT NULL,
  `ssl_tls` varchar(100) DEFAULT NULL,
  `smtp_auth` varchar(10) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `api_secret` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email_type`, `smtp_server`, `smtp_port`, `smtp_email`, `smtp_username`, `smtp_password`, `ssl_tls`, `smtp_auth`, `api_key`, `api_secret`, `region`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'sendmail', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'yes', '2020-02-28 13:46:03', '2025-05-10 12:46:00');

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_template_attachment`
--

CREATE TABLE `email_template_attachment` (
  `id` int(11) NOT NULL,
  `email_template_id` int(11) NOT NULL,
  `attachment` varchar(100) NOT NULL,
  `attachment_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `reference` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `note` text NOT NULL,
  `source` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `no_of_child` varchar(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_type`
--

CREATE TABLE `enquiry_type` (
  `id` int(11) NOT NULL,
  `enquiry_type` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_title` varchar(200) NOT NULL,
  `event_description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_color` varchar(200) NOT NULL,
  `event_for` varchar(100) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `sesion_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_groups`
--

CREATE TABLE `exam_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `exam_type` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_groups`
--

INSERT INTO `exam_groups` (`id`, `name`, `exam_type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FIRST YEAR', 'gpa', '', 0, '2025-06-20 10:20:08', '2025-06-20 10:20:08'),
(2, 'SECOND YEAR', 'gpa', '', 0, '2025-06-25 07:47:39', '2025-06-25 07:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_class_batch_exams`
--

CREATE TABLE `exam_group_class_batch_exams` (
  `id` int(11) NOT NULL,
  `exam` varchar(250) DEFAULT NULL,
  `passing_percentage` float(10,2) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `exam_group_id` int(11) DEFAULT NULL,
  `use_exam_roll_no` int(11) NOT NULL DEFAULT 1,
  `is_publish` int(11) DEFAULT 0,
  `is_rank_generated` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_group_class_batch_exams`
--

INSERT INTO `exam_group_class_batch_exams` (`id`, `exam`, `passing_percentage`, `session_id`, `date_from`, `date_to`, `exam_group_id`, `use_exam_roll_no`, `is_publish`, `is_rank_generated`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'First Term Exams', NULL, 21, NULL, NULL, 1, 0, 1, 0, '', 1, '2025-06-20 10:21:28', '2025-07-01 15:44:09'),
(2, 'First Term Assignments', NULL, 21, NULL, NULL, 1, 1, 1, 0, '', 1, '2025-06-25 08:04:03', '2025-06-25 08:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_class_batch_exam_students`
--

CREATE TABLE `exam_group_class_batch_exam_students` (
  `id` int(11) NOT NULL,
  `exam_group_class_batch_exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `roll_no` int(11) DEFAULT NULL,
  `teacher_remark` text DEFAULT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_group_class_batch_exam_students`
--

INSERT INTO `exam_group_class_batch_exam_students` (`id`, `exam_group_class_batch_exam_id`, `student_id`, `student_session_id`, `roll_no`, `teacher_remark`, `rank`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, NULL, NULL, 0, 0, '2025-06-20 10:22:02', '2025-06-20 10:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_class_batch_exam_subjects`
--

CREATE TABLE `exam_group_class_batch_exam_subjects` (
  `id` int(11) NOT NULL,
  `exam_group_class_batch_exams_id` int(11) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `time_from` time NOT NULL,
  `duration` varchar(50) NOT NULL,
  `room_no` varchar(100) DEFAULT NULL,
  `max_marks` float(10,2) DEFAULT NULL,
  `min_marks` float(10,2) DEFAULT NULL,
  `credit_hours` float(10,2) DEFAULT 0.00,
  `date_to` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_group_class_batch_exam_subjects`
--

INSERT INTO `exam_group_class_batch_exam_subjects` (`id`, `exam_group_class_batch_exams_id`, `subject_id`, `date_from`, `time_from`, `duration`, `room_no`, `max_marks`, `min_marks`, `credit_hours`, `date_to`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-03-30', '10:00:33', '60', '1', 100.00, 40.00, 14.00, NULL, 0, '2025-06-25 07:56:27', '2025-06-25 07:58:27'),
(2, 1, 2, '2025-03-30', '12:00:33', '60', '2', 100.00, 40.00, 14.00, NULL, 0, '2025-06-25 07:58:27', '2025-06-25 07:58:27'),
(3, 1, 3, '2025-03-30', '15:00:33', '60', '3', 100.00, 40.00, 14.00, NULL, 0, '2025-06-25 07:58:27', '2025-06-25 07:58:27'),
(4, 1, 4, '2025-03-30', '08:00:33', '60', '4', 100.00, 40.00, 14.00, NULL, 0, '2025-06-25 07:58:27', '2025-06-25 07:58:27'),
(5, 2, 1, '2025-07-30', '10:05:23', '60', '1', 100.00, 40.00, 0.00, NULL, 0, '2025-06-25 08:06:42', '2025-06-25 08:06:42'),
(6, 2, 2, '2025-07-30', '12:05:29', '60', '1', 100.00, 40.00, 0.00, NULL, 0, '2025-06-25 08:06:42', '2025-06-25 08:06:42'),
(7, 2, 3, '2025-07-30', '08:05:33', '60', '1', 100.00, 40.00, 0.00, NULL, 0, '2025-06-25 08:06:42', '2025-06-25 08:06:42'),
(8, 2, 4, '2025-07-30', '16:05:39', '60', '1', 100.00, 40.00, 0.00, NULL, 0, '2025-06-25 08:06:42', '2025-06-25 08:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_exam_connections`
--

CREATE TABLE `exam_group_exam_connections` (
  `id` int(11) NOT NULL,
  `exam_group_id` int(11) DEFAULT NULL,
  `exam_group_class_batch_exams_id` int(11) DEFAULT NULL,
  `exam_weightage` float(10,2) DEFAULT 0.00,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_group_exam_connections`
--

INSERT INTO `exam_group_exam_connections` (`id`, `exam_group_id`, `exam_group_class_batch_exams_id`, `exam_weightage`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 25.00, 0, '2025-06-25 08:07:28', '2025-06-25 08:07:28'),
(2, 1, 2, 75.00, 0, '2025-06-25 08:07:28', '2025-06-25 08:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_exam_results`
--

CREATE TABLE `exam_group_exam_results` (
  `id` int(11) NOT NULL,
  `exam_group_class_batch_exam_student_id` int(11) NOT NULL,
  `exam_group_class_batch_exam_subject_id` int(11) DEFAULT NULL,
  `exam_group_student_id` int(11) DEFAULT NULL,
  `attendence` varchar(10) DEFAULT NULL,
  `get_marks` float(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `exam_group_exam_results`
--

INSERT INTO `exam_group_exam_results` (`id`, `exam_group_class_batch_exam_student_id`, `exam_group_class_batch_exam_subject_id`, `exam_group_student_id`, `attendence`, `get_marks`, `note`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, 'present', 70.00, '', 0, '2025-06-25 07:59:52', '2025-06-25 07:59:52'),
(2, 1, 1, NULL, 'present', 60.00, '', 0, '2025-06-25 08:00:44', '2025-06-25 08:00:44'),
(3, 1, 2, NULL, 'present', 90.00, '', 0, '2025-06-25 08:01:01', '2025-06-25 08:01:01'),
(4, 1, 3, NULL, 'present', 55.00, '', 0, '2025-06-25 08:01:18', '2025-06-25 08:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `exam_group_students`
--

CREATE TABLE `exam_group_students` (
  `id` int(11) NOT NULL,
  `exam_group_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_schedules`
--

CREATE TABLE `exam_schedules` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `teacher_subject_id` int(11) DEFAULT NULL,
  `date_of_exam` date DEFAULT NULL,
  `start_to` varchar(50) DEFAULT NULL,
  `end_from` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `full_marks` int(11) DEFAULT NULL,
  `passing_marks` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `exp_head_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_head`
--

CREATE TABLE `expense_head` (
  `id` int(11) NOT NULL,
  `exp_category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feemasters`
--

CREATE TABLE `feemasters` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_discounts`
--

CREATE TABLE `fees_discounts` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `nature` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `percentage` float(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `discount_limit` int(11) DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_reminder`
--

CREATE TABLE `fees_reminder` (
  `id` int(11) NOT NULL,
  `reminder_type` varchar(10) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `fees_reminder`
--

INSERT INTO `fees_reminder` (`id`, `reminder_type`, `day`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'before', 2, 0, '2020-02-28 13:38:32', '2025-05-10 12:46:02'),
(2, 'before', 5, 0, '2020-02-28 13:38:36', '2025-05-10 12:46:02'),
(3, 'after', 2, 0, '2020-02-28 13:38:40', '2025-05-10 12:46:02'),
(4, 'after', 5, 0, '2020-02-28 13:38:44', '2025-05-10 12:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `feetype`
--

CREATE TABLE `feetype` (
  `id` int(11) NOT NULL,
  `is_system` int(11) NOT NULL DEFAULT 0,
  `feecategory_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `description` text DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `nature` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `feetype`
--

INSERT INTO `feetype` (`id`, `is_system`, `feecategory_id`, `type`, `code`, `is_active`, `description`, `session_id`, `student_session_id`, `nature`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, 'Registration', 'REG', 'no', '', NULL, NULL, '', '2025-05-27 11:44:54', '2025-05-27 11:44:54'),
(2, 0, NULL, 'Term 1 Tuition', 'T1T', 'no', '', NULL, NULL, '', '2025-05-27 11:47:43', '2025-05-27 11:47:43'),
(3, 0, NULL, 'Social Fee', 'SFEE', 'no', '', NULL, NULL, '', '2025-05-27 11:47:58', '2025-05-27 11:47:58'),
(4, 0, NULL, 'Term 2 Tuition', 'T2T', 'no', '', NULL, NULL, '', '2025-05-27 11:48:12', '2025-05-27 11:48:12'),
(5, 0, NULL, 'Outreach Fee', 'ORF', 'no', '', NULL, NULL, '', '2025-05-27 11:48:38', '2025-05-27 11:48:38'),
(6, 0, NULL, 'Term 3 Tuition', 'T3T', 'no', '', NULL, NULL, '', '2025-05-27 11:48:56', '2025-05-27 11:48:56'),
(7, 0, NULL, 'Graduation Fee', 'GFEE', 'no', '', NULL, NULL, '', '2025-05-27 11:49:19', '2025-05-27 11:49:19'),
(8, 0, NULL, 'Term 4 Tuition', 'T4T', 'no', '', NULL, NULL, '', '2025-05-27 11:49:39', '2025-05-27 11:49:39'),
(9, 0, NULL, 'Late Submission Fee', 'LSF', 'no', '', NULL, NULL, '', '2025-05-29 12:04:04', '2025-05-29 12:04:04'),
(10, 0, NULL, 'Audio Books', 'AB', 'no', '', NULL, NULL, '', '2025-05-29 12:04:22', '2025-05-29 12:04:22'),
(11, 0, NULL, 'Temporal ID Fee', 'TID', 'no', '', NULL, NULL, '', '2025-05-29 12:15:38', '2025-05-29 12:15:38'),
(12, 0, NULL, 'T-Shirt Purchase', 'TSP', 'no', '', NULL, NULL, '', '2025-05-29 12:16:03', '2025-05-29 12:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups`
--

CREATE TABLE `fee_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `is_system` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `nature` varchar(255) NOT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `fee_groups`
--

INSERT INTO `fee_groups` (`id`, `name`, `is_system`, `description`, `nature`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FIRST YEAR MORNING', 0, '', '', 'no', '2025-05-27 11:44:32', '2025-05-27 11:44:32'),
(2, 'FIRST YEAR EVENING', 0, '', '', 'no', '2025-05-27 11:50:08', '2025-05-27 11:50:08'),
(3, 'SECOND YEAR MORNING', 0, '', '', 'no', '2025-05-27 11:50:17', '2025-05-27 11:50:17'),
(4, 'SECOND YEAR EVENING', 0, '', '', 'no', '2025-05-27 11:50:55', '2025-05-27 11:50:55'),
(5, 'TEMPORAL ID', 0, '', '', 'no', '2025-05-29 12:15:12', '2025-05-29 13:05:03');

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups_feetype`
--

CREATE TABLE `fee_groups_feetype` (
  `id` int(11) NOT NULL,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `fee_groups_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `fine_type` varchar(50) NOT NULL DEFAULT 'none',
  `due_date` date DEFAULT NULL,
  `fine_percentage` float(10,2) NOT NULL DEFAULT 0.00,
  `fine_amount` float(10,2) NOT NULL DEFAULT 0.00,
  `fine_per_day` int(11) NOT NULL DEFAULT 0,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `fee_groups_feetype`
--

INSERT INTO `fee_groups_feetype` (`id`, `fee_session_group_id`, `fee_groups_id`, `feetype_id`, `session_id`, `amount`, `fine_type`, `due_date`, `fine_percentage`, `fine_amount`, `fine_per_day`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 21, 50.00, 'none', '2027-01-15', 0.00, 0.00, 0, 'no', '2025-05-27 11:45:40', '2025-09-18 11:30:35'),
(2, 1, 1, 2, 21, 120.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 11:52:04', '2025-09-18 11:19:18'),
(3, 1, 1, 3, 21, 20.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 11:52:47', '2025-09-18 11:19:27'),
(4, 1, 1, 4, 21, 120.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 11:53:59', '2025-09-18 11:19:39'),
(5, 1, 1, 6, 21, 120.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 11:54:26', '2025-09-18 11:19:48'),
(6, 1, 1, 8, 21, 120.00, 'none', '2025-09-08', 0.00, 0.00, 0, 'no', '2025-05-27 11:55:18', '2025-09-18 11:20:21'),
(7, 2, 2, 1, 21, 50.00, 'none', '2027-01-15', 0.00, 0.00, 0, 'no', '2025-05-27 11:56:03', '2025-09-18 11:31:00'),
(8, 2, 2, 2, 21, 120.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 11:56:34', '2025-09-18 11:16:28'),
(9, 2, 2, 3, 21, 20.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 11:57:03', '2025-09-18 11:12:14'),
(10, 2, 2, 4, 21, 120.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 11:57:27', '2025-09-18 11:16:41'),
(11, 2, 2, 6, 21, 120.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 11:57:53', '2025-09-18 11:16:51'),
(12, 2, 2, 8, 21, 120.00, 'none', '2025-09-08', 0.00, 0.00, 0, 'no', '2025-05-27 11:58:50', '2025-09-18 11:17:02'),
(13, 3, 3, 1, 21, 50.00, 'none', '2027-01-15', 0.00, 0.00, 0, 'no', '2025-05-27 11:59:13', '2025-09-18 11:31:33'),
(14, 3, 3, 2, 21, 120.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 11:59:54', '2025-09-18 11:22:45'),
(15, 3, 3, 3, 21, 20.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 12:00:24', '2025-09-18 11:23:01'),
(16, 3, 3, 4, 21, 120.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 12:00:45', '2025-09-18 11:23:14'),
(17, 3, 3, 5, 21, 50.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 12:01:11', '2025-09-18 11:27:21'),
(18, 3, 3, 6, 21, 120.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 12:01:42', '2025-09-18 11:23:28'),
(19, 3, 3, 7, 21, 50.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 12:02:01', '2025-09-18 11:24:03'),
(20, 3, 3, 8, 21, 120.00, 'none', '2025-09-08', 0.00, 0.00, 0, 'no', '2025-05-27 12:02:21', '2025-09-18 11:23:42'),
(21, 4, 4, 1, 21, 50.00, 'none', '2027-01-15', 0.00, 0.00, 0, 'no', '2025-05-27 12:02:46', '2025-09-18 11:31:51'),
(22, 4, 4, 2, 21, 120.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 12:03:14', '2025-09-18 11:24:36'),
(23, 4, 4, 3, 21, 20.00, 'none', '2025-01-13', 0.00, 0.00, 0, 'no', '2025-05-27 12:03:33', '2025-09-18 11:24:53'),
(24, 4, 4, 4, 21, 120.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 12:03:57', '2025-09-18 11:25:06'),
(25, 4, 4, 5, 21, 50.00, 'none', '2025-04-07', 0.00, 0.00, 0, 'no', '2025-05-27 12:04:15', '2025-09-18 11:25:47'),
(26, 4, 4, 6, 21, 120.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 12:04:33', '2025-09-18 11:26:13'),
(27, 4, 4, 7, 21, 50.00, 'none', '2025-06-23', 0.00, 0.00, 0, 'no', '2025-05-27 12:04:58', '2025-09-18 11:26:26'),
(28, 4, 4, 8, 21, 120.00, 'none', '2025-09-08', 0.00, 0.00, 0, 'no', '2025-05-27 12:05:18', '2025-09-18 11:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `fee_receipt_no`
--

CREATE TABLE `fee_receipt_no` (
  `id` int(11) NOT NULL,
  `payment` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_session_groups`
--

CREATE TABLE `fee_session_groups` (
  `id` int(11) NOT NULL,
  `fee_groups_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `fee_session_groups`
--

INSERT INTO `fee_session_groups` (`id`, `fee_groups_id`, `session_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 'no', '2025-05-27 11:45:40', '2025-05-27 11:45:40'),
(2, 2, 21, 'no', '2025-05-27 11:56:03', '2025-05-27 11:56:03'),
(3, 3, 21, 'no', '2025-05-27 11:59:13', '2025-05-27 11:59:13'),
(4, 4, 21, 'no', '2025-05-27 12:02:46', '2025-05-27 12:02:46');

-- --------------------------------------------------------

--
-- Table structure for table `filetypes`
--

CREATE TABLE `filetypes` (
  `id` int(11) NOT NULL,
  `file_extension` text DEFAULT NULL,
  `file_mime` text DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `image_extension` text DEFAULT NULL,
  `image_mime` text DEFAULT NULL,
  `image_size` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `filetypes`
--

INSERT INTO `filetypes` (`id`, `file_extension`, `file_mime`, `file_size`, `image_extension`, `image_mime`, `image_size`, `created_at`, `updated_at`) VALUES
(1, 'pdf, zip, jpg, jpeg, png, txt, 7z, gif, csv, docx, mp3, mp4, accdb, odt, ods, ppt, pptx, xlsx, wmv, jfif, apk, ppt, bmp, jpe, mdb, rar, xls, svg', 'application/pdf, image/zip, image/jpg, image/png, image/jpeg, text/plain, application/x-zip-compressed, application/zip, image/gif, text/csv, application/vnd.openxmlformats-officedocument.wordprocessingml.document, audio/mpeg, application/msaccess, application/vnd.oasis.opendocument.text, application/vnd.oasis.opendocument.spreadsheet, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, video/x-ms-wmv, video/mp4, image/jpeg, application/vnd.android.package-archive, application/x-msdownload, application/vnd.ms-powerpoint, image/bmp, image/jpeg, application/msaccess, application/vnd.ms-excel, image/svg+xml', 100048576, 'jfif, png, jpe, jpeg, jpg, bmp, gif, svg', 'image/jpeg, image/png, image/jpeg, image/jpeg, image/bmp, image/gif, image/x-ms-bmp, image/svg+xml', 10048576, '2021-01-30 13:03:03', '2025-05-10 12:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `follow_up`
--

CREATE TABLE `follow_up` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `next_date` date NOT NULL,
  `response` text NOT NULL,
  `note` text NOT NULL,
  `followup_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_media_gallery`
--

CREATE TABLE `front_cms_media_gallery` (
  `id` int(11) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `thumb_path` varchar(300) DEFAULT NULL,
  `dir_path` varchar(300) DEFAULT NULL,
  `img_name` varchar(300) DEFAULT NULL,
  `thumb_name` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_type` varchar(100) NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `vid_url` text NOT NULL,
  `vid_title` varchar(250) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_media_gallery`
--

INSERT INTO `front_cms_media_gallery` (`id`, `image`, `thumb_path`, `dir_path`, `img_name`, `thumb_name`, `created_at`, `file_type`, `file_size`, `vid_url`, `vid_title`, `updated_at`) VALUES
(1, '1.png', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1747908957-1048339828682ef95d5a77f!1.png', '1747908957-1048339828682ef95d5a77f!thumb_1.png', '2025-05-22 10:15:57', 'image/png', '554705', '', '', '2025-05-22 10:15:57'),
(2, 'find your purpose.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1752578413-5751875656876396d7a478!find your purpose.jpg', '1752578413-5751875656876396d7a478!thumb_find your purpose.jpg', '2025-07-15 13:20:13', 'image/jpeg', '123362', '', '', '2025-07-15 11:20:13'),
(3, 'happynewyear.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1752578425-69585027668763979836e9!happynewyear.jpg', '1752578425-69585027668763979836e9!thumb_happynewyear.jpg', '2025-07-15 13:20:25', 'image/jpeg', '148600', '', '', '2025-07-15 11:20:25'),
(5, 'FB_IMG_1753597840314.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-17423211086885c8301464c!FB_IMG_1753597840314.jpg', '1753598000-17423211086885c8301464c!thumb_FB_IMG_1753597840314.jpg', '2025-07-27 08:33:20', 'image/jpeg', '64382', '', '', '2025-07-27 06:33:20'),
(6, 'FB_IMG_1753597834820.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-13007253516885c83019202!FB_IMG_1753597834820.jpg', '1753598000-13007253516885c83019202!thumb_FB_IMG_1753597834820.jpg', '2025-07-27 08:33:20', 'image/jpeg', '268585', '', '', '2025-07-27 06:33:20'),
(7, 'FB_IMG_1753597829058.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-13373569926885c8301f561!FB_IMG_1753597829058.jpg', '1753598000-13373569926885c8301f561!thumb_FB_IMG_1753597829058.jpg', '2025-07-27 08:33:20', 'image/jpeg', '86091', '', '', '2025-07-27 06:33:20'),
(8, 'FB_IMG_1753597824552.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-6707748196885c83022ca3!FB_IMG_1753597824552.jpg', '1753598000-6707748196885c83022ca3!thumb_FB_IMG_1753597824552.jpg', '2025-07-27 08:33:20', 'image/jpeg', '120767', '', '', '2025-07-27 06:33:20'),
(9, 'FB_IMG_1753597819935.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-20308971026885c83026a29!FB_IMG_1753597819935.jpg', '1753598000-20308971026885c83026a29!thumb_FB_IMG_1753597819935.jpg', '2025-07-27 08:33:20', 'image/jpeg', '61867', '', '', '2025-07-27 06:33:20'),
(10, 'FB_IMG_1753597814136.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753598000-13416933506885c83029cd6!FB_IMG_1753597814136.jpg', '1753598000-13416933506885c83029cd6!thumb_FB_IMG_1753597814136.jpg', '2025-07-27 08:33:20', 'image/jpeg', '97156', '', '', '2025-07-27 06:33:20'),
(11, 'RBTC_HIstory_KEH.jpeg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753717931-38635176168879cabc6f6f!RBTC_HIstory_KEH.jpeg', '1753717931-38635176168879cabc6f6f!thumb_RBTC_HIstory_KEH.jpeg', '2025-07-28 17:52:11', 'image/jpeg', '138579', '', '', '2025-07-28 15:52:11'),
(12, 'Hagins1.jpeg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753717931-74793802068879cabc892b!Hagins1.jpeg', '1753717931-74793802068879cabc892b!thumb_Hagins1.jpeg', '2025-07-28 17:52:11', 'image/jpeg', '447786', '', '', '2025-07-28 15:52:11'),
(13, 'BroHagin.jpeg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1753717931-154252029168879cabcf263!BroHagin.jpeg', '1753717931-154252029168879cabcf263!thumb_BroHagin.jpeg', '2025-07-28 17:52:11', 'image/jpeg', '297691', '', '', '2025-07-28 15:52:11'),
(14, '8_Dec2023_RBTCUpdate-846x508.png', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-18126809066891d03c89368!8_Dec2023_RBTCUpdate-846x508.png', '1754386492-18126809066891d03c89368!thumb_8_Dec2023_RBTCUpdate-846x508.png', '2025-08-05 11:34:52', 'image/png', '469701', '', '', '2025-08-05 09:34:52'),
(15, '1P-Tr.oq1b.2-small-RHEMA-Bible-Training-Colleg.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-5274792876891d03c90251!1P-Tr.oq1b.2-small-RHEMA-Bible-Training-Colleg.jpg', '1754386492-5274792876891d03c90251!thumb_1P-Tr.oq1b.2-small-RHEMA-Bible-Training-Colleg.jpg', '2025-08-05 11:34:52', 'image/jpeg', '37988', '', '', '2025-08-05 09:34:52'),
(16, '4-2247f1a2.jpeg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-5343489386891d03c90e89!4-2247f1a2.jpeg', '1754386492-5343489386891d03c90e89!thumb_4-2247f1a2.jpeg', '2025-08-05 11:34:52', 'image/jpeg', '257219', '', '', '2025-08-05 09:34:52'),
(17, '2-5230e744.jpeg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-13839244156891d03c94d88!2-5230e744.jpeg', '1754386492-13839244156891d03c94d88!thumb_2-5230e744.jpeg', '2025-08-05 11:34:52', 'image/jpeg', '129319', '', '', '2025-08-05 09:34:52'),
(18, 'RhemaNewsEmailArt_April22-846x423.png', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-14447869316891d03c97d32!RhemaNewsEmailArt_April22-846x423.png', '1754386492-14447869316891d03c97d32!thumb_RhemaNewsEmailArt_April22-846x423.png', '2025-08-05 11:34:52', 'image/png', '346170', '', '', '2025-08-05 09:34:52'),
(19, '94d8dcc598364fbcf764c78f7c38af2e.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754386492-11086645926891d03c9b3ce!94d8dcc598364fbcf764c78f7c38af2e.jpg', '1754386492-11086645926891d03c9b3ce!thumb_94d8dcc598364fbcf764c78f7c38af2e.jpg', '2025-08-05 11:34:52', 'image/jpeg', '94980', '', '', '2025-08-05 09:34:52'),
(20, '485674528_18497356678024386_7592009480393652726_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-1036401417689b2bf37ade1!485674528_18497356678024386_7592009480393652726_n.jpg', '1754999795-1036401417689b2bf37ade1!thumb_485674528_18497356678024386_7592009480393652726_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '377765', '', '', '2025-08-12 11:56:35'),
(21, '486039336_18497356660024386_9025222719282615503_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-234354772689b2bf382de2!486039336_18497356660024386_9025222719282615503_n.jpg', '1754999795-234354772689b2bf382de2!thumb_486039336_18497356660024386_9025222719282615503_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '281193', '', '', '2025-08-12 11:56:35'),
(22, '485268608_18497356669024386_7340654204535837945_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-2145446907689b2bf389b70!485268608_18497356669024386_7340654204535837945_n.jpg', '1754999795-2145446907689b2bf389b70!thumb_485268608_18497356669024386_7340654204535837945_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '326029', '', '', '2025-08-12 11:56:35'),
(23, '485476009_18497356651024386_3461066239745389952_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-1283572221689b2bf38fdb4!485476009_18497356651024386_3461066239745389952_n.jpg', '1754999795-1283572221689b2bf38fdb4!thumb_485476009_18497356651024386_3461066239745389952_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '298882', '', '', '2025-08-12 11:56:35'),
(24, '485290558_18497356642024386_2014639514236381655_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-2117578427689b2bf395cb7!485290558_18497356642024386_2014639514236381655_n.jpg', '1754999795-2117578427689b2bf395cb7!thumb_485290558_18497356642024386_2014639514236381655_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '329492', '', '', '2025-08-12 11:56:35'),
(25, '485494622_18497356633024386_6072935305232246535_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-2005849538689b2bf39c4a1!485494622_18497356633024386_6072935305232246535_n.jpg', '1754999795-2005849538689b2bf39c4a1!thumb_485494622_18497356633024386_6072935305232246535_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '345482', '', '', '2025-08-12 11:56:35'),
(26, '485076827_18497356624024386_7090507186612071216_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-375324737689b2bf3a2e9d!485076827_18497356624024386_7090507186612071216_n.jpg', '1754999795-375324737689b2bf3a2e9d!thumb_485076827_18497356624024386_7090507186612071216_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '311648', '', '', '2025-08-12 11:56:35'),
(27, '485706666_18497356606024386_6597251382272440540_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-1288625525689b2bf3a91f6!485706666_18497356606024386_6597251382272440540_n.jpg', '1754999795-1288625525689b2bf3a91f6!thumb_485706666_18497356606024386_6597251382272440540_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '405455', '', '', '2025-08-12 11:56:35'),
(28, '485451826_18497356615024386_5342506070040407235_n.jpg', 'uploads/gallery/media/thumb/', 'uploads/gallery/media/', '1754999795-2059015419689b2bf3b0307!485451826_18497356615024386_5342506070040407235_n.jpg', '1754999795-2059015419689b2bf3b0307!thumb_485451826_18497356615024386_5342506070040407235_n.jpg', '2025-08-12 13:56:35', 'image/jpeg', '653964', '', '', '2025-08-12 11:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_menus`
--

CREATE TABLE `front_cms_menus` (
  `id` int(11) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `open_new_tab` int(11) NOT NULL DEFAULT 0,
  `ext_url` text NOT NULL,
  `ext_url_link` text NOT NULL,
  `publish` int(11) NOT NULL DEFAULT 0,
  `content_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_menus`
--

INSERT INTO `front_cms_menus` (`id`, `menu`, `slug`, `description`, `open_new_tab`, `ext_url`, `ext_url_link`, `publish`, `content_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Menu', 'main-menu', 'Main menu', 0, '', '', 0, 'default', 'no', '2018-04-20 14:54:49', '2025-05-10 12:46:03'),
(2, 'Bottom Menu', 'bottom-menu', 'Bottom Menu', 0, '', '', 0, 'default', 'no', '2018-04-20 14:54:55', '2025-05-10 12:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_menu_items`
--

CREATE TABLE `front_cms_menu_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ext_url` text DEFAULT NULL,
  `open_new_tab` int(11) DEFAULT 0,
  `ext_url_link` text DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `publish` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_menu_items`
--

INSERT INTO `front_cms_menu_items` (`id`, `menu_id`, `menu`, `page_id`, `parent_id`, `ext_url`, `open_new_tab`, `ext_url_link`, `slug`, `weight`, `publish`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Home', 1, 0, NULL, NULL, NULL, 'home', 1, 0, NULL, 'no', '2019-12-02 22:11:50', '2025-08-05 12:48:33'),
(3, 1, 'Contact Us', 2, 0, NULL, NULL, NULL, 'contact-us', 19, 0, NULL, 'no', '2019-12-02 22:11:52', '2025-08-05 13:01:00'),
(4, 1, 'Apply Now', 0, 0, '1', NULL, 'http://rhemazimbabwe.org/online_admission', 'apply-now', 10, 0, NULL, 'no', '2019-12-21 15:33:00', '2025-08-05 13:01:00'),
(6, 1, 'Annual Calendar', 0, 8, '1', NULL, 'http://rhemazimbabwe.org/annual_calendar', 'annual-calendar', 16, 0, NULL, 'no', '2025-07-15 13:22:07', '2025-08-05 13:01:00'),
(7, 1, 'About Us', 8, 0, NULL, NULL, NULL, 'about-us', 2, 0, NULL, 'no', '2025-07-28 16:31:16', '2025-08-05 12:48:33'),
(8, 1, 'News and Events', 17, 0, NULL, NULL, NULL, 'news-and-events', 13, 0, NULL, 'no', '2025-07-30 09:21:00', '2025-08-05 13:01:00'),
(9, 1, 'Students', 0, 0, NULL, NULL, NULL, 'students', 12, 0, NULL, 'no', '2025-07-30 09:21:12', '2025-08-05 13:01:00'),
(10, 1, 'Academics', 0, 0, NULL, NULL, NULL, 'academics', 9, 0, NULL, 'no', '2025-07-30 09:21:22', '2025-08-05 13:01:00'),
(11, 1, 'Partner', 0, 0, '1', NULL, 'https://rhemazimbabwe.com/partnerregistration/', 'partner', 18, 0, NULL, 'no', '2025-07-30 09:24:13', '2025-11-03 10:28:49'),
(12, 2, 'Tuition and Costs', 0, 0, NULL, NULL, NULL, 'tuition-and-costs', NULL, 0, NULL, 'no', '2025-07-30 09:24:50', '2025-07-30 09:24:50'),
(13, 2, 'Instructors', 0, 0, NULL, NULL, NULL, 'instructors', NULL, 0, NULL, 'no', '2025-07-30 09:25:47', '2025-07-30 09:25:47'),
(14, 2, 'Biblical studies', 0, 0, NULL, NULL, NULL, 'biblical-studies', NULL, 0, NULL, 'no', '2025-07-30 09:26:39', '2025-07-30 09:26:39'),
(15, 1, 'Kenneth E Hagin', 9, 7, NULL, NULL, NULL, 'kenneth-e-hagin', 5, 0, NULL, 'no', '2025-08-05 12:16:28', '2025-08-05 12:48:33'),
(16, 1, 'Kenneth & Lynette Hagin', 10, 7, NULL, NULL, NULL, 'kenneth-lynette-hagin', 6, 0, NULL, 'no', '2025-08-05 12:17:00', '2025-08-05 12:48:33'),
(17, 1, 'What We Believe', 11, 7, NULL, NULL, NULL, 'what-we-believe', 4, 0, NULL, 'no', '2025-08-05 12:17:29', '2025-08-05 12:48:33'),
(18, 1, 'Our History', 8, 7, NULL, NULL, NULL, 'our-history', 3, 0, NULL, 'no', '2025-08-05 12:18:08', '2025-08-05 12:48:33'),
(20, 1, 'News', 17, 8, NULL, NULL, NULL, 'news', 14, 0, NULL, 'no', '2025-08-05 12:48:01', '2025-08-05 13:01:00'),
(21, 1, 'Events', 15, 8, NULL, NULL, NULL, 'events', 15, 0, NULL, 'no', '2025-08-05 12:48:13', '2025-08-05 13:01:00'),
(22, 1, 'Gallery', 16, 7, NULL, NULL, NULL, 'gallery', 7, 0, NULL, 'no', '2025-08-05 12:52:33', '2025-08-05 13:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_pages`
--

CREATE TABLE `front_cms_pages` (
  `id` int(11) NOT NULL,
  `page_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_homepage` int(11) DEFAULT 0,
  `title` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `feature_image` varchar(200) NOT NULL,
  `description` longtext DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `publish` int(11) DEFAULT 0,
  `sidebar` int(11) DEFAULT 0,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_pages`
--

INSERT INTO `front_cms_pages` (`id`, `page_type`, `is_homepage`, `title`, `url`, `type`, `slug`, `meta_title`, `meta_description`, `meta_keyword`, `feature_image`, `description`, `publish_date`, `publish`, `sidebar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'default', 1, 'Home', 'page/home', 'page', 'home', '', '', '', '', '.', '0000-00-00', 1, NULL, 'no', '2019-12-02 15:23:47', '2025-08-05 12:31:47'),
(2, 'default', 0, 'Complain', 'page/complain', 'page', 'complain', 'Complain form', '                                                                                                                                                                                    complain form                                                                                                                                                                                                                                ', 'complain form', '', '<p>[form-builder:complain]</p>', '0000-00-00', 1, NULL, 'no', '2019-11-13 10:16:36', '2025-05-10 12:46:03'),
(3, 'default', 0, '404 page', 'page/404-page', 'page', '404-page', '', '                                ', '', '', '<html>\r\n<head>\r\n <title></title>\r\n</head>\r\n<body>\r\n<p>404 page found</p>\r\n</body>\r\n</html>', '0000-00-00', 0, NULL, 'no', '2018-05-18 14:46:04', '2025-05-10 12:46:03'),
(4, 'default', 0, 'Contact us', 'page/contact-us', 'page', 'contact-us', '', '', '', '', '<section class=\"contact\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<h2 class=\"col-md-12 col-sm-12\">Send In Your Query</h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<div class=\"col-md-12 col-sm-12\">[form-builder:contact_us]<!--./row--></div>\r\n<!--./col-md-12--></div>\r\n<!--./row--></div>\r\n<!--./container--></section>\r\n\r\n<div class=\"col-md-4 col-sm-4\">\r\n<div class=\"contact-item\"><img src=\"http://192.168.1.81/repos/smartschool/uploads/gallery/media/pin.svg\" />\r\n<h3>Our Location</h3>\r\n\r\n<p>350 Fifth Avenue, 34th floor New York NY 10118-3299 USA</p>\r\n</div>\r\n<!--./contact-item--></div>\r\n<!--./col-md-4-->\r\n\r\n<div class=\"col-md-4 col-sm-4\">\r\n<div class=\"contact-item\"><img src=\"http://192.168.1.81/repos/smartschool/uploads/gallery/media/phone.svg\" />\r\n<h3>CALL US</h3>\r\n\r\n<p>E-mail : info@abcschool.com</p>\r\n\r\n<p>Mobile : +91-9009987654</p>\r\n</div>\r\n<!--./contact-item--></div>\r\n<!--./col-md-4-->\r\n\r\n<div class=\"col-md-4 col-sm-4\">\r\n<div class=\"contact-item\"><img src=\"http://192.168.1.81/repos/smartschool/uploads/gallery/media/clock.svg\" />\r\n<h3>Working Hours</h3>\r\n\r\n<p>Mon-Fri : 9 am to 5 pm</p>\r\n\r\n<p>Sat : 9 am to 3 pm</p>\r\n</div>\r\n<!--./contact-item--></div>\r\n<!--./col-md-4-->\r\n\r\n<div class=\"col-md-12 col-sm-12\">\r\n<div class=\"mapWrapper fullwidth\"><iframe frameborder=\"0\" height=\"500\" marginheight=\"0\" marginwidth=\"0\" scrolling=\"no\" src=\"http://maps.google.com/maps?f=q&source=s_q&hl=EN&q=time+square&aq=&sll=40.716558,-73.931122&sspn=0.40438,1.056747&ie=UTF8&rq=1&ev=p&split=1&radius=33.22&hq=time+square&hnear=&ll=37.061753,-95.677185&spn=0.438347,0.769043&z=9&output=embed\" width=\"100%\"></iframe></div>\r\n</div>', '0000-00-00', 0, NULL, 'no', '2019-05-04 15:46:41', '2025-05-10 12:46:03'),
(6, 'manual', 0, 'Test Page', 'page/test-page', 'page', 'test-page', '', '', '', '', '<form action=\"test\" enctype=\"multipart/form-data\" id=\"1\" method=\"post\" name=\"test form\" target=\"_parent\"><input name=\"SUBMIT\" type=\"submit\" />&nbsp;</form>\r\n<input name=\"Gender\" type=\"checkbox\" value=\"Male,Female\" /><br />\r\n<br />\r\n<br />\r\n<input name=\"EMAIL\" size=\"30\" type=\"email\" /><br />\r\n<br />\r\n<input name=\"NAME\" type=\"text\" />', NULL, 0, NULL, 'no', '2025-06-02 06:55:29', '2025-06-02 06:55:29'),
(8, 'manual', 0, 'About us', 'page/about-us', 'page', 'about-us', '', '', '', '', '<div class=\"x-container max width\" id=\"\" style=\"box-sizing: border-box; margin: 0px auto 35px; width: 1020px; max-width: 1020px; position: relative; z-index: 1; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255); padding: 0px;\">\r\n<div class=\"x-column x-sm x-1-1\" style=\"box-sizing: border-box; position: relative; z-index: 1; float: left; margin-right: 0px; width: 1020px; padding: 0px;\">\r\n<h1 0px=\"\" center=\"\" class=\"h-custom-headline cs-ta-center man h2 accent\" color:=\"\" font-weight:=\"\" gothic=\"\" letter-spacing:=\"\" margin:=\"\" overflow:=\"\" pathway=\"\" style=\"box-sizing: border-box; text-rendering: optimizelegibility; font-size: 51.426px; line-height: 1.1; font-family: \" text-align:=\"\"><span style=\"box-sizing: border-box; padding-bottom: 2px; display: inline-block; position: relative;\"><span style=\"color:#FF0000;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span style=\"color:#FFA500;\">About RBTC</span><span class=\"period-yellow\" style=\"box-sizing: border-box; font-weight: 700;\">.</span></span></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"x-container max width\" id=\"\" style=\"box-sizing: border-box; margin: 0px auto; width: 1020px; max-width: 1020px; position: relative; z-index: 1; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255); padding: 0px;\">\r\n<div class=\"x-column x-sm x-1-1\" style=\"box-sizing: border-box; position: relative; z-index: 1; float: left; margin-right: 0px; width: 1020px; padding: 0px;\">\r\n<p class=\"man\" style=\"box-sizing: border-box; margin: 0px !important;\"><span class=\"x-dropcap\" style=\"box-sizing: border-box; float: left; display: block; margin: 0.215em 0.215em 0px 0px; padding: 0.105em 0.2em 0.11em; font-size: 3.5em; line-height: 1; color: rgb(255, 255, 255); background-color: rgb(188, 151, 44);\">M</span><span style=\"font-family:lucida sans unicode,lucida grande,sans-serif;\">ore than 50 years since our founding, Rhema Bible Training College remains&nbsp;<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">steadfast in carrying out God\'s purpose of empowering Christians to take the<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">Gospel to the ends of the earth. Since 1974, we have trained over 123,000<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">graduates for the harvest. The sun never sets on Rhema graduates ministering<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">around the world, either in the pulpit, on the mission field, or as active believers&nbsp;<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">supporting God\'s work in local churches. Our alumni have gone forth and together we&nbsp;<br />\r\n<span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">have established 298 Bible schools in 56 nations around the world.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span><span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">&nbsp;</span><span style=\"color: rgb(40, 50, 63); font-family: \"lucida sans unicode\", \"lucida grande\", sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">&nbsp;</span></span></span></span></span></span></span><br />\r\n&nbsp;</p>\r\n\r\n<div class=\"x-container max width\" id=\"\" style=\"box-sizing: border-box; margin: 0px auto 35px; width: 1020px; max-width: 1020px; position: relative; z-index: 1; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; text-align: center; background-color: rgb(255, 255, 255); padding: 0px;\">\r\n<div class=\"x-column x-sm x-1-1\" style=\"box-sizing: border-box; position: relative; z-index: 1; float: left; margin-right: 0px; width: 1020px; padding: 0px;\">\r\n<h2 0px=\"\" class=\"h-custom-headline cs-ta-center man h2 accent\" color:=\"\" font-weight:=\"\" gothic=\"\" letter-spacing:=\"\" margin:=\"\" overflow:=\"\" pathway=\"\" style=\"box-sizing: border-box; text-rendering: optimizelegibility; font-size: 51.426px; line-height: 1.1; font-family: \"><span style=\"box-sizing: border-box; padding-bottom: 2px; display: inline-block; position: relative;\"><span style=\"color:#FFA500;\">RBTC Leadership</span><span class=\"period-yellow\" style=\"box-sizing: border-box; font-weight: 700;\">.</span></span></h2>\r\n\r\n<div style=\"text-align: left;\">\r\n<h1 0px=\"\" center=\"\" class=\"h-custom-headline cs-ta-center man h2 accent\" color:=\"\" font-weight:=\"\" gothic=\"\" letter-spacing:=\"\" margin:=\"\" overflow:=\"\" pathway=\"\" style=\"box-sizing: border-box; text-rendering: optimizelegibility; font-size: 51.426px; line-height: 1.1;\" text-align:=\"\"><span style=\"box-sizing: border-box; padding-bottom: 2px; display: inline-block; position: relative;\"><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1753717931-154252029168879cabcf263!BroHagin.jpeg?1753717967\" style=\"width: 500px; height: 350px;\" /><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1753717931-74793802068879cabc892b!Hagins1.jpeg?1753718131\" style=\"height: 350px; width: 500px;\" /></span></h1>\r\n<span style=\"font-size:20px;\">Founder: Kenneth E Hagin <span style=\"color:#FF0000;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span style=\"color:#000000;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; President Kenneth W Hagin & Director,&nbsp;</span><br />\r\n<a href=\"https://rhemazimbabwe.org/page/brother-hagin\"><span style=\"color:#FF0000;\"><span style=\"font-family: Lato, sans-serif; font-weight: 700; background-color: rgb(255, 255, 255);\">Learn more</span></span></a><span style=\"color:#000000;\"><span style=\"font-family: Lato, sans-serif; font-weight: 700; background-color: rgb(255, 255, 255);\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Lynette Hagin</span></span></span><span style=\"color:#FF0000;\"><span style=\"font-size:20px;\"><span style=\"color: rgb(255, 0, 0); font-family: Lato, sans-serif; font-weight: 700; background-color: rgb(255, 255, 255);\"> <a href=\"https://rhemazimbabwe.org/page/kenneth-lynette-hagin\">Learn more</a></span></span><br />\r\n&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><br />\r\n&nbsp;</div>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n&nbsp;</div>\r\n</div>\r\n\r\n<p class=\"man\" style=\"box-sizing: border-box; margin: 0px !important;\"><span style=\"font-family:lucida sans unicode,lucida grande,sans-serif;\"></span></p>\r\n</div>\r\n</div>', NULL, 0, NULL, 'no', '2025-07-28 15:48:13', '2025-08-05 11:56:24'),
(9, 'manual', 0, 'brother-hagin/', 'page/brother-hagin', 'page', 'brother-hagin', '', '', '', '', '<h1 background-color:=\"\" class=\"h-custom-headline h2 accent\" color:=\"\" font-weight:=\"\" gothic=\"\" letter-spacing:=\"\" overflow:=\"\" pathway=\"\" style=\"box-sizing: border-box; margin: 1.25em 0px 0.2em; text-rendering: optimizelegibility; font-size: 51.426px; line-height: 1.1; font-family: \"><span style=\"box-sizing: border-box; padding-bottom: 2px; display: inline-block; position: relative;\">Kenneth E. Hagin</span></h1>\r\n\r\n<hr class=\"x-gap\" style=\"box-sizing: border-box; height: 0px; margin: 20px 0px 0px; border-width: 2px 0px 0px; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-color: transparent; border-image: initial; border-top-style: solid; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\" />\r\n<div class=\"x-text\" id=\"\" style=\"box-sizing: border-box; min-width: 1px; transition-duration: 0.3s; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-property: color, border-color, background-color, box-shadow, text-shadow, column-rule, opacity, filter, transform; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255);\">\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\"><span style=\"box-sizing: border-box;\">Rev. Kenneth Erwin Hagin</span>&nbsp;was born on Aug. 20, 1917, in McKinney, Texas. Rev. Hagin was sickly as a child, suffering from a deformed heart and an incurable blood disease. He was not expected to live and became bedfast at age 15. In April 1933 during a dramatic conversion experience, he reported dying three times in 10 minutes, each time seeing the horrors of hell and then returning to life.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">In August 1934, Rev. Hagin was miraculously healed, raised off a deathbed by the power of God and the revelation of faith in God’s Word. Jesus appeared to Rev. Hagin eight times over the next several years in visions that changed the course of his ministry. In 1967, he began a regular radio broadcast that continues today as Rhema for Today.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">In 1968 Rev. Hagin published the first issues of The Word of Faith. That magazine, now produced nine times a year, has a circulation of over 200,000. The publishing outreach he founded, Faith Library Publications, has circulated worldwide more than 75 million copies of books by Rev. Hagin, Kenneth W. Hagin, Lynette Hagin, Craig W. Hagin, and several other authors. Faith Library Publications also has produced millions of audio and video teachings.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">Other outreaches of Kenneth Hagin Ministries include Rhema Praise, a weekly television broadcast hosted by Revs. Kenneth and Lynette Hagin; Rhema Correspondence Bible School; Rhema Alumni Association; Rhema Ministerial Association International; the Rhema Prayer and Healing Center; and the Rhema prison ministry.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">In 1974 Rev. Hagin founded what is now Rhema Bible Training College. The school has campuses all over the world and continues to expand.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">Until shortly before his death, Rev. Hagin continued to travel and teach throughout the United States and into Canada conducting All Faiths’ Crusades and other special meetings.  Known as the “father of the modern faith movement,” Rev. Hagin was a dynamic preacher, teacher, and prophet. His teachings and books are filled with vivid stories that show God’s power and truth working in his life and the lives of others. He will be remembered not only as a great minister but as a great family man—for his family was his heart. He was there at every milestone ready to talk, to answer, to pray. He was a man whose belly laugh filled the room at the sight of one of his grandchildren or great-grandchildren.</p>\r\n</div>', NULL, 0, NULL, 'no', '2025-07-28 16:16:45', '2025-07-28 16:17:58'),
(10, 'manual', 0, 'kenneth-lynette-hagin', 'page/kenneth-lynette-hagin', 'page', 'kenneth-lynette-hagin', '', '', '', '', '<div class=\"x-container max width\" id=\"\" style=\"box-sizing: border-box; margin: 0px auto; width: 1005.83px; max-width: 1020px; position: relative; z-index: 1; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255); padding: 0px;\">\r\n<div class=\"x-column x-sm x-1-1\" style=\"box-sizing: border-box; position: relative; z-index: 1; float: left; margin-right: 0px; width: 1005.83px; padding: 0px;\">\r\n<h1 class=\"h-custom-headline h3 accent\" style=\"box-sizing: border-box; margin: 1.25em 0px 0.2em; text-rendering: optimizelegibility; font-size: 41.13px; line-height: 1.1; font-family: \"Pathway Gothic One\", sans-serif; font-weight: 400; letter-spacing: -0.025em; color: rgb(32, 60, 150); overflow: hidden;\"><span style=\"box-sizing: border-box; padding-bottom: 2px; display: inline-block; position: relative;\">Rev. Kenneth and Lynette Hagin</span></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"x-container max width\" id=\"\" style=\"box-sizing: border-box; margin: 0px auto; width: 1005.83px; max-width: 1020px; position: relative; z-index: 1; color: rgb(40, 50, 63); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(255, 255, 255); padding: 0px;\">\r\n<div class=\"x-column x-sm x-1-1\" style=\"box-sizing: border-box; position: relative; z-index: 1; float: left; margin-right: 0px; width: 1005.83px; padding: 0px;\">\r\n<div class=\"x-text\" id=\"\" style=\"box-sizing: border-box; min-width: 1px; transition-duration: 0.3s; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-property: color, border-color, background-color, box-shadow, text-shadow, column-rule, opacity, filter, transform;\">\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">When his father said by the Holy Spirit, “We’re gonna start a Bible school” at Campmeeting 1973, Kenneth and Lynette knew that was why they had left their roles at the church in Garland, Texas, pastored by her father and came to work at Kenneth Hagin Ministries in Oklahoma a year earlier. At his father’s direction, Kenneth put together the curriculum for the new school.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">At Campmeeting 1974, Kenneth E. Hagin announced that the school would open in the fall. But the ministry did not have enough money to start the program. Brother Hagin told the Lord, “This is Your school, not mine. You finance it, because I can’t. Now, I’m not going to worry a bit about it. You take care of it.” Thousands of dollars in offerings came in that year at Campmeeting, providing the funds to begin the school.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">That fall 73 students enrolled. Of those, 58 graduated in May 1975. Since then over 29,000 have graduated from RBTC USA, and more than 117,000 have graduated worldwide. And the number keeps growing! Today RBTC has campuses all over the world.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">Kenneth W. Hagin’s array of responsibilities also includes international director of Rhema Ministerial Association International. With his wife, Lynette, he hosts Rhema for Today, a weekday radio program broadcast throughout the United States, and Rhema Praise, a weekly television broadcast. They also conduct Living Faith Conferences. Together they are spreading the message of faith and healing around the world.</p>\r\n\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 1.5em;\">Rev. Lynette Hagin is an author, teacher, conference speaker and host. She serves as director of Rhema Bible Training College USA and general manager of Kenneth Hagin Ministries. She assists her husband, Kenneth W. Hagin, in pastoring Rhema Bible Church and conducting Living Faith Conferences throughout the United States. She is committed to helping people succeed in life by sharing a life-changing, powerful message: “You can make it!”</p>\r\n</div>\r\n</div>\r\n</div>', NULL, 0, NULL, 'no', '2025-07-28 16:22:14', '2025-07-28 16:22:14'),
(11, 'manual', 0, 'what we believe', 'page/what-we-believe', 'page', 'what-we-believe', '', '', '', '', '<br />\r\n<h3 class=\"h-custom-headline h3\" style=\"box-sizing: border-box; margin: 0.75em 0px; text-rendering: optimizelegibility; font-size: 1.35em; line-height: 1.1; font-family: \"Pathway Gothic One\", sans-serif; font-weight: 400; letter-spacing: 0.1em; color: rgb(223, 196, 109); background-color: rgb(153, 0, 0); text-transform: uppercase;\"><span style=\"box-sizing: border-box;\">The Scriptures</span></h3>\r\n\r\n<div class=\"x-text\" id=\"\" style=\"box-sizing: border-box; min-width: 1px; transition-duration: 0.3s; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-property: color, border-color, background-color, box-shadow, text-shadow, column-rule, opacity, filter, transform; color: rgb(255, 255, 255); font-family: Lato, sans-serif; font-size: 18px; font-weight: 700; background-color: rgb(153, 0, 0);\">\r\n<p class=\"man\" style=\"box-sizing: border-box; margin: 0px !important;\"><span style=\"box-sizing: border-box;\">The Bible is the inspired Word of God</span>, the product of holy men of old who spoke and wrote as they were moved by the Holy Spirit. The New Covenant, as recorded in the New Testament, we accept as our infallible guide in matters pertaining to conduct and doctrine (2 Tim. 3:16; 1 Thess. 2:13; 2 Peter 1:21).</p>\r\n</div>', NULL, 0, NULL, 'no', '2025-07-30 09:15:11', '2025-08-08 08:00:46'),
(12, 'manual', 0, 'instructors', 'page/instructors', 'page', 'instructors', '', '', '', '', '<img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1753717931-38635176168879cabc6f6f!RBTC_HIstory_KEH.jpeg?1753866922\" />', NULL, 0, NULL, 'no', '2025-07-30 09:15:30', '2025-07-30 09:15:30'),
(13, 'manual', 0, 'FAQs', 'page/faqs', 'page', 'faqs', '', '', '', '', '<h1>Frequently Asked Questions</h1>', NULL, 0, NULL, 'no', '2025-07-30 09:17:47', '2025-09-18 09:34:37'),
(14, 'manual', 0, 'tuition and costs', 'page/tuition-and-costs', 'page', 'tuition-and-costs', '', '', '', '', '<img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1753598000-20308971026885c83026a29!FB_IMG_1753597819935.jpg?1753867120\" />', NULL, 0, NULL, 'no', '2025-07-30 09:18:47', '2025-07-30 09:18:47'),
(15, 'manual', 0, 'Events', 'page/events', 'page', 'events', '', '', '', '', '.', NULL, 0, NULL, 'no', '2025-08-05 12:45:56', '2025-08-05 12:45:56'),
(16, 'manual', 0, 'Gallery', 'page/gallery', 'page', 'gallery', '', '', '', '', '.', NULL, 0, NULL, 'no', '2025-08-05 12:46:23', '2025-09-18 09:25:32'),
(17, 'manual', 0, 'News', 'page/news', 'page', 'news', '', '', '', '', '.', NULL, 0, 1, 'no', '2025-08-05 12:46:56', '2025-08-05 12:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_page_contents`
--

CREATE TABLE `front_cms_page_contents` (
  `id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_page_contents`
--

INSERT INTO `front_cms_page_contents` (`id`, `page_id`, `content_type`, `created_at`, `updated_at`) VALUES
(1, 15, 'events', '2025-08-05 12:45:56', '2025-08-05 12:45:56'),
(2, 16, 'gallery', '2025-08-05 12:46:23', '2025-08-05 12:46:23'),
(3, 17, 'notice', '2025-08-05 12:46:56', '2025-08-05 12:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_programs`
--

CREATE TABLE `front_cms_programs` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `event_start` date DEFAULT NULL,
  `event_end` date DEFAULT NULL,
  `event_venue` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `feature_image` text NOT NULL,
  `publish_date` date DEFAULT NULL,
  `publish` varchar(10) DEFAULT '0',
  `sidebar` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_programs`
--

INSERT INTO `front_cms_programs` (`id`, `type`, `slug`, `url`, `title`, `date`, `event_start`, `event_end`, `event_venue`, `description`, `is_active`, `created_at`, `meta_title`, `meta_description`, `meta_keyword`, `feature_image`, `publish_date`, `publish`, `sidebar`, `updated_at`) VALUES
(1, 'banner', NULL, NULL, 'Banner Images', NULL, NULL, NULL, NULL, NULL, 'no', '2025-07-15 11:20:54', '', '', '', '', NULL, '0', 0, '2025-07-15 11:20:54'),
(2, 'notice', 'first-class-opens-in-january-2027', 'read/first-class-opens-in-january-2027', 'First Class opens in January 2027', '2025-07-29', NULL, NULL, NULL, 'We open our doors in January 2027. Register today and secure your spot.&nbsp;', 'no', '2025-07-29 09:45:54', '', '', '', '', NULL, '0', 1, '2025-08-05 13:00:25'),
(3, 'notice', 'registration-closes-on-the-1st-of-december-2026', 'read/registration-closes-on-the-1st-of-december-2026', 'Registration closes on the 1st of December 2026', '2025-10-27', NULL, NULL, NULL, 'Register Today!', 'no', '2025-07-29 10:29:03', '', '', '', '', NULL, '0', NULL, '2025-08-11 14:31:43'),
(4, 'notice', 'changes-that-heal-class-starts-on-the-5th-of-january-2026-register-today', 'read/changes-that-heal-class-starts-on-the-5th-of-january-2026-register-today', 'Changes That Heal Class starts on the 5th of January 2026- Register Today!', '2025-08-05', NULL, NULL, NULL, '.', 'no', '2025-08-05 13:00:15', '', '', '', '', NULL, '0', 1, '2025-08-05 13:00:15'),
(5, 'gallery', 'graduation', 'read/graduation', 'Graduation', NULL, NULL, NULL, NULL, '<img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754386492-18126809066891d03c89368!8_Dec2023_RBTCUpdate-846x508.png?1754399595\" /><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754386492-14447869316891d03c97d32!RhemaNewsEmailArt_April22-846x423.png?1754398943\" />', 'no', '2025-08-05 13:02:42', '', '', '', 'https://rhemazimbabwe.org/uploads/gallery/media/1754386492-11086645926891d03c9b3ce!94d8dcc598364fbcf764c78f7c38af2e.jpg?1754399366', NULL, '0', NULL, '2025-08-05 13:13:36'),
(6, 'gallery', 'sports-day-2025', 'read/sports-day-2025', 'Sports Day 2025', NULL, NULL, NULL, NULL, '<img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754386492-5274792876891d03c90251!1P-Tr.oq1b.2-small-RHEMA-Bible-Training-Colleg.jpg?1754399654\">', 'no', '2025-08-05 13:15:01', '', '', '', 'https://rhemazimbabwe.org/uploads/gallery/media/1754386492-5274792876891d03c90251!1P-Tr.oq1b.2-small-RHEMA-Bible-Training-Colleg.jpg?1754399670', NULL, '0', 1, '2025-08-05 13:15:01'),
(7, 'gallery', 'rhema-around-the-world', 'read/rhema-around-the-world', 'Rhema Around The World', NULL, NULL, NULL, NULL, '<img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754999795-2059015419689b2bf3b0307!485451826_18497356615024386_5342506070040407235_n.jpg?1754999820\"><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754999795-1288625525689b2bf3a91f6!485706666_18497356606024386_6597251382272440540_n.jpg?1754999843\"><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754999795-375324737689b2bf3a2e9d!485076827_18497356624024386_7090507186612071216_n.jpg?1754999848\"><img src=\"https://rhemazimbabwe.org/uploads/gallery/media/1754999795-2005849538689b2bf39c4a1!485494622_18497356633024386_6072935305232246535_n.jpg?1754999852\">', 'no', '2025-08-12 11:57:49', '', '', '', 'https://rhemazimbabwe.org/uploads/gallery/media/1754999795-2059015419689b2bf3b0307!485451826_18497356615024386_5342506070040407235_n.jpg?1754999859', NULL, '0', 1, '2025-08-12 11:57:49'),
(0, 'events', 'fun-day-1', 'read/fun-day-1', 'Fun Day', NULL, '2025-12-24', '2025-12-24', 'Sports Club', '<h2 style=\"font-style:italic;\"><q><var>Its time to have fun<span style=\"font-family:comic sans ms,cursive;\"></span></var></q></h2>', 'no', '2025-11-03 10:45:58', '', '', '', '', NULL, '0', 1, '2025-11-03 10:46:09');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_program_photos`
--

CREATE TABLE `front_cms_program_photos` (
  `id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `media_gallery_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_program_photos`
--

INSERT INTO `front_cms_program_photos` (`id`, `program_id`, `media_gallery_id`, `created_at`, `updated_at`) VALUES
(11, 1, 16, '2025-08-05 09:37:09', '2025-08-05 09:37:09'),
(12, 1, 17, '2025-08-05 09:39:53', '2025-08-05 09:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_settings`
--

CREATE TABLE `front_cms_settings` (
  `id` int(11) NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `is_active_rtl` int(11) DEFAULT 0,
  `is_active_front_cms` int(11) DEFAULT 0,
  `is_active_sidebar` int(11) DEFAULT 0,
  `logo` varchar(200) DEFAULT NULL,
  `contact_us_email` varchar(100) DEFAULT NULL,
  `complain_form_email` varchar(100) DEFAULT NULL,
  `sidebar_options` text NOT NULL,
  `whatsapp_url` varchar(255) NOT NULL,
  `fb_url` varchar(200) NOT NULL,
  `twitter_url` varchar(200) NOT NULL,
  `youtube_url` varchar(200) NOT NULL,
  `google_plus` varchar(200) NOT NULL,
  `instagram_url` varchar(200) NOT NULL,
  `pinterest_url` varchar(200) NOT NULL,
  `linkedin_url` varchar(200) NOT NULL,
  `google_analytics` text DEFAULT NULL,
  `footer_text` varchar(500) DEFAULT NULL,
  `cookie_consent` varchar(255) NOT NULL,
  `fav_icon` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `front_cms_settings`
--

INSERT INTO `front_cms_settings` (`id`, `theme`, `is_active_rtl`, `is_active_front_cms`, `is_active_sidebar`, `logo`, `contact_us_email`, `complain_form_email`, `sidebar_options`, `whatsapp_url`, `fb_url`, `twitter_url`, `youtube_url`, `google_plus`, `instagram_url`, `pinterest_url`, `linkedin_url`, `google_analytics`, `footer_text`, `cookie_consent`, `fav_icon`, `created_at`, `updated_at`) VALUES
(1, 'shadow_white', NULL, 1, 1, './uploads/school_content/logo/1748526735-6684990666838668f23e8a!RBTC_Logo_Web_OL_2_Color.png', '', '', '[\"news\"]', '', '', '', '', '', '', '', '', '', '', '', './uploads/school_content/logo/1748526735-8422087866838668f23fad!RHEMA-SEAL- 170x184.jpg', '2023-01-05 06:42:55', '2025-09-18 09:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_ins`
--

CREATE TABLE `gateway_ins` (
  `id` int(11) NOT NULL,
  `online_admission_id` int(11) DEFAULT NULL,
  `gateway_name` varchar(50) NOT NULL,
  `module_type` varchar(255) NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `parameter_details` mediumtext NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateway_ins_response`
--

CREATE TABLE `gateway_ins_response` (
  `id` int(11) NOT NULL,
  `gateway_ins_id` int(11) DEFAULT NULL,
  `posted_data` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_calls`
--

CREATE TABLE `general_calls` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(12) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `call_duration` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `call_type` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giving_frequencies`
--

CREATE TABLE `giving_frequencies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `days_interval` int(11) DEFAULT NULL COMMENT 'Number of days between contributions',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giving_frequencies`
--

INSERT INTO `giving_frequencies` (`id`, `name`, `code`, `days_interval`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Once-Off', 'once_off', NULL, 'One time contribution', 1, 1, '2025-09-30 18:08:33', '2025-10-01 22:16:15'),
(2, 'Weekly', 'weekly', 7, 'Weekly contributions', 1, 2, '2025-09-30 18:08:33', '2025-10-01 22:16:15'),
(3, 'Monthly', 'monthly', 30, 'Monthly contributions', 1, 3, '2025-09-30 18:08:33', '2025-10-01 22:16:15'),
(4, 'Quarterly', 'quarterly', 90, 'Quarterly contributions', 1, 4, '2025-09-30 18:08:33', '2025-10-01 22:16:15'),
(5, 'Annually', 'annually', 365, 'Annual contributions', 1, 5, '2025-09-30 18:08:33', '2025-10-01 22:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `giving_types`
--

CREATE TABLE `giving_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giving_types`
--

INSERT INTO `giving_types` (`id`, `name`, `code`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Tuition Support', 'tuition', 'Support for student tuition fees', 1, 1, '2025-09-30 18:08:32', '2025-10-01 22:16:15'),
(2, 'Scholarship Fund', 'scholarship', 'Contributions to scholarship fund', 1, 2, '2025-09-30 18:08:32', '2025-10-01 22:16:15'),
(3, 'Building Fund', 'building', 'Support for infrastructure development', 1, 3, '2025-09-30 18:08:32', '2025-10-01 22:16:15'),
(4, 'General Donation', 'general', 'General purpose donations', 1, 4, '2025-09-30 18:08:32', '2025-10-01 22:16:15'),
(5, 'Sponsorship', 'sponsorship', 'Student sponsorship program', 1, 5, '2025-09-30 18:08:32', '2025-10-01 22:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `gmeet`
--

CREATE TABLE `gmeet` (
  `id` int(11) NOT NULL,
  `purpose` varchar(20) NOT NULL DEFAULT 'class',
  `staff_id` int(11) DEFAULT NULL,
  `created_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'manual',
  `api_data` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `url` text NOT NULL,
  `session_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gmeet_history`
--

CREATE TABLE `gmeet_history` (
  `id` int(11) NOT NULL,
  `gmeet_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `total_hit` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gmeet_sections`
--

CREATE TABLE `gmeet_sections` (
  `id` int(11) NOT NULL,
  `gmeet_id` int(11) NOT NULL,
  `cls_section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gmeet_settings`
--

CREATE TABLE `gmeet_settings` (
  `id` int(11) NOT NULL,
  `api_key` varchar(200) DEFAULT NULL,
  `api_secret` varchar(200) DEFAULT NULL,
  `use_api` int(11) DEFAULT 1,
  `parent_live_class` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gmeet_staff`
--

CREATE TABLE `gmeet_staff` (
  `id` int(11) NOT NULL,
  `gmeet_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_authenticator`
--

CREATE TABLE `google_authenticator` (
  `id` int(11) NOT NULL,
  `use_authenticator` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `exam_type` varchar(250) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `point` float(10,1) DEFAULT NULL,
  `mark_from` float(10,2) DEFAULT NULL,
  `mark_upto` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `exam_type`, `name`, `point`, `mark_from`, `mark_upto`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'gpa', 'A+', 4.5, 100.00, 90.00, '', 'no', '2025-06-25 07:26:34', '2025-06-25 07:26:34'),
(2, 'gpa', 'A', 4.0, 90.00, 80.00, '', 'no', '2025-06-25 07:27:07', '2025-06-25 07:27:07'),
(3, 'gpa', 'B+', 3.5, 80.00, 70.00, '', 'no', '2025-06-25 07:27:41', '2025-06-25 07:27:41'),
(4, 'gpa', 'B', 3.0, 70.00, 60.00, '', 'no', '2025-06-25 07:29:26', '2025-06-25 07:29:39'),
(5, 'gpa', 'C+', 2.5, 60.00, 50.00, '', 'no', '2025-06-25 07:30:54', '2025-06-25 07:30:54'),
(6, 'gpa', 'C', 2.0, 50.00, 40.00, '', 'no', '2025-06-25 07:31:37', '2025-06-25 07:31:37'),
(7, 'gpa', 'D', 1.0, 40.00, 0.00, '', 'no', '2025-06-25 07:32:23', '2025-06-25 07:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `id` int(11) NOT NULL,
  `guest_name` varchar(200) NOT NULL,
  `guest_unique_id` varchar(200) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `dob` varchar(200) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `note` text NOT NULL,
  `address` varchar(200) NOT NULL,
  `guest_image` varchar(200) NOT NULL,
  `verification_code` varchar(200) NOT NULL,
  `created_at` date NOT NULL,
  `is_active` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`id`, `guest_name`, `guest_unique_id`, `lang_id`, `currency_id`, `email`, `mobileno`, `password`, `dob`, `gender`, `note`, `address`, `guest_image`, `verification_code`, `created_at`, `is_active`) VALUES
(1, 'Mfuehudwj hiwjswdwidjwidji jdiwjswihdfeufhiwj ijdiwjwihdiwkdoq jiwjdwidjwifjei jwdodkwofjiehiehgiejdiw jifjeifjeifwkfijrghis kwoskowfiejifefefefe rhemazimbabwe.org', 'Guest1', 4, 178, 'nomin.momin+316s3@mail.ru', '', '$2y$10$2N0JMOX4IIq76aWtJRcZReTLVeuPinWiR3q430pNmesEVSkjX3lsa', '', NULL, '', '', '', '', '2025-08-03', 'yes'),
(2, 'Kuda', 'Guest2', 4, 150, 'kkamudyariwa@gmail.com', '', '$2y$10$GvtHuu/PnuUdnTtNeRrQp.YC4ijNI2Urnwv2rL0JVNJf/AZojJA/.', '', NULL, '', '', '', '', '2025-08-09', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `holiday_type`
--

CREATE TABLE `holiday_type` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `holiday_type`
--

INSERT INTO `holiday_type` (`id`, `type`, `is_default`) VALUES
(1, 'Holiday', 1),
(2, 'Vacation', 1),
(3, 'Activity', 1);

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `subject_group_subject_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `homework_date` date NOT NULL,
  `submit_date` date NOT NULL,
  `marks` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `create_date` date NOT NULL,
  `evaluation_date` date DEFAULT NULL,
  `document` varchar(200) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `evaluated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homework_evaluation`
--

CREATE TABLE `homework_evaluation` (
  `id` int(11) NOT NULL,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `marks` float(10,2) DEFAULT NULL,
  `note` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE `hostel` (
  `id` int(11) NOT NULL,
  `hostel_name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `intake` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_rooms`
--

CREATE TABLE `hostel_rooms` (
  `id` int(11) NOT NULL,
  `hostel_id` int(11) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `room_no` varchar(200) DEFAULT NULL,
  `no_of_bed` int(11) DEFAULT NULL,
  `cost_per_bed` float(10,2) DEFAULT 0.00,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `id_card`
--

CREATE TABLE `id_card` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_address` varchar(500) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `sign_image` varchar(100) NOT NULL,
  `enable_vertical_card` int(11) NOT NULL DEFAULT 0,
  `header_color` varchar(100) NOT NULL,
  `enable_admission_no` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_student_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_class` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_fathers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_mothers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_phone` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_blood_group` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_student_barcode` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=disable,1=enable',
  `enable_student_rollno` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_student_house_name` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `id_card`
--

INSERT INTO `id_card` (`id`, `title`, `school_name`, `school_address`, `background`, `logo`, `sign_image`, `enable_vertical_card`, `header_color`, `enable_admission_no`, `enable_student_name`, `enable_class`, `enable_fathers_name`, `enable_mothers_name`, `enable_address`, `enable_phone`, `enable_dob`, `enable_blood_group`, `enable_student_barcode`, `enable_student_rollno`, `enable_student_house_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Second Year', 'Rhema Bible Training Centre Zambia', 'Plot No. 256 Foxdale, Zambezi Road, Chamba Valley, Lusaka.', '1748328837-217781422683561855c7b8!RHEMA-SEAL- 170x184.jpg', '1748334627-629076955683578236b78a!RHEMA SEAL3.png', 'samplesign12.png', 0, '#595959', 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, '2025-05-10 12:46:05', '2025-09-18 12:09:39'),
(2, 'First Year', 'Rhema Bibible Training Centre Zambia', 'Plot No. 256 Foxdale, Zambezi Road, Chamba Valley, Lusaka', '1748329045-19835103526835625566327!RHEMA-SEAL- 170x184.jpg', '1748333658-1212309376835745a9583f!RBTC_Logo_Web_OL_2_Color.png', 'samplesign12.png', 0, '#6969e9', 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, '2025-05-10 12:46:05', '2025-05-27 08:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `income_head_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` float(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `documents` varchar(255) DEFAULT NULL,
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `income_head_id`, `name`, `invoice_no`, `date`, `amount`, `note`, `is_active`, `documents`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 'Partner Contributions-Kuda Kamudyariwa', '', '2025-09-18', 20.00, '', 'yes', NULL, 'no', '2025-09-18 12:01:14', '2025-09-18 12:01:14');

-- --------------------------------------------------------

--
-- Table structure for table `income_head`
--

CREATE TABLE `income_head` (
  `id` int(11) NOT NULL,
  `income_category` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `is_deleted` varchar(255) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `income_head`
--

INSERT INTO `income_head` (`id`, `income_category`, `description`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Donations', '', 'yes', 'no', '2025-05-29 12:09:36', '2025-05-29 12:09:36'),
(3, 'Student Fines', '', 'yes', 'no', '2025-09-18 11:55:25', '2025-09-18 11:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_store_id` int(11) DEFAULT NULL,
  `item_supplier_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `item_photo` varchar(225) DEFAULT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `id` int(11) NOT NULL,
  `item_category` varchar(255) NOT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_issue`
--

CREATE TABLE `item_issue` (
  `id` int(11) NOT NULL,
  `issue_type` varchar(15) DEFAULT NULL,
  `issue_to` int(11) NOT NULL,
  `issue_by` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `note` text NOT NULL,
  `is_returned` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` varchar(10) DEFAULT 'no',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stock`
--

CREATE TABLE `item_stock` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `symbol` varchar(10) NOT NULL DEFAULT '+',
  `quantity` int(11) DEFAULT NULL,
  `purchase_price` float(10,2) NOT NULL,
  `date` date NOT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` varchar(10) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_store`
--

CREATE TABLE `item_store` (
  `id` int(11) NOT NULL,
  `item_store` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_supplier`
--

CREATE TABLE `item_supplier` (
  `id` int(11) NOT NULL,
  `item_supplier` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_phone` varchar(255) NOT NULL,
  `contact_person_email` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `short_code` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `is_rtl` int(11) NOT NULL,
  `is_deleted` varchar(10) NOT NULL DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language`, `short_code`, `country_code`, `is_rtl`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Azerbaijan', 'az', 'az', 0, 'no', 'no', '2019-11-20 11:23:12', '0000-00-00 00:00:00'),
(2, 'Albanian', 'sq', 'al', 0, 'no', 'no', '2019-11-20 11:42:42', '0000-00-00 00:00:00'),
(3, 'Amharic', 'am', 'am', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(4, 'English', 'en', 'us', 0, 'no', 'no', '2019-11-20 11:38:50', '0000-00-00 00:00:00'),
(5, 'Arabic', 'ar', 'sa', 0, 'no', 'no', '2019-11-20 11:47:28', '0000-00-00 00:00:00'),
(7, 'Afrikaans', 'af', 'af', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(8, 'Basque', 'eu', 'es', 0, 'no', 'no', '2019-11-20 11:54:10', '0000-00-00 00:00:00'),
(11, 'Bengali', 'bn', 'in', 0, 'no', 'no', '2019-11-20 11:41:53', '0000-00-00 00:00:00'),
(13, 'Bosnian', 'bs', 'bs', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(14, 'Welsh', 'cy', 'cy', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(15, 'Hungarian', 'hu', 'hu', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(16, 'Vietnamese', 'vi', 'vi', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(17, 'Haitian', 'ht', 'ht', 0, 'no', 'no', '2021-01-23 07:09:32', '0000-00-00 00:00:00'),
(18, 'Galician', 'gl', 'gl', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(19, 'Dutch', 'nl', 'nl', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(21, 'Greek', 'el', 'gr', 0, 'no', 'no', '2019-11-20 12:12:08', '0000-00-00 00:00:00'),
(22, 'Georgian', 'ka', 'ge', 0, 'no', 'no', '2019-11-20 12:11:40', '0000-00-00 00:00:00'),
(23, 'Gujarati', 'gu', 'in', 0, 'no', 'no', '2019-11-20 11:39:16', '0000-00-00 00:00:00'),
(24, 'Danish', 'da', 'dk', 0, 'no', 'no', '2019-11-20 12:03:25', '0000-00-00 00:00:00'),
(25, 'Hebrew', 'he', 'il', 0, 'no', 'no', '2019-11-20 12:13:50', '0000-00-00 00:00:00'),
(26, 'Yiddish', 'yi', 'il', 0, 'no', 'no', '2019-11-20 12:25:33', '0000-00-00 00:00:00'),
(27, 'Indonesian', 'id', 'id', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(28, 'Irish', 'ga', 'ga', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(29, 'Italian', 'it', 'it', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(30, 'Icelandic', 'is', 'is', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(31, 'Spanish', 'es', 'es', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(33, 'Kannada', 'kn', 'kn', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(34, 'Catalan', 'ca', 'ca', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(36, 'Chinese', 'zh', 'cn', 0, 'no', 'no', '2019-11-20 12:01:48', '0000-00-00 00:00:00'),
(37, 'Korean', 'ko', 'kr', 0, 'no', 'no', '2019-11-20 12:19:09', '0000-00-00 00:00:00'),
(38, 'Xhosa', 'xh', 'ls', 0, 'no', 'no', '2019-11-20 12:24:39', '0000-00-00 00:00:00'),
(39, 'Latin', 'la', 'it', 0, 'no', 'no', '2021-01-23 07:09:32', '0000-00-00 00:00:00'),
(40, 'Latvian', 'lv', 'lv', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(41, 'Lithuanian', 'lt', 'lt', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(43, 'Malagasy', 'mg', 'mg', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(44, 'Malay', 'ms', 'ms', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(45, 'Malayalam', 'ml', 'ml', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(46, 'Maltese', 'mt', 'mt', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(47, 'Macedonian', 'mk', 'mk', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(48, 'Maori', 'mi', 'nz', 0, 'no', 'no', '2019-11-20 12:20:27', '0000-00-00 00:00:00'),
(49, 'Marathi', 'mr', 'in', 0, 'no', 'no', '2019-11-20 11:39:51', '0000-00-00 00:00:00'),
(51, 'Mongolian', 'mn', 'mn', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(52, 'German', 'de', 'de', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(53, 'Nepali', 'ne', 'ne', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(54, 'Norwegian', 'no', 'no', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(55, 'Punjabi', 'pa', 'in', 0, 'no', 'no', '2019-11-20 11:40:16', '0000-00-00 00:00:00'),
(57, 'Persian', 'fa', 'ir', 0, 'no', 'no', '2019-11-20 12:21:17', '0000-00-00 00:00:00'),
(59, 'Portuguese', 'pt', 'pt', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(60, 'Romanian', 'ro', 'ro', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(61, 'Russian', 'ru', 'ru', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(62, 'Cebuano', 'ceb', 'ph', 0, 'no', 'no', '2019-11-20 11:59:12', '0000-00-00 00:00:00'),
(64, 'Sinhala', 'si', 'lk ', 0, 'no', 'no', '2021-01-23 07:09:32', '0000-00-00 00:00:00'),
(65, 'Slovakian', 'sk', 'sk', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(66, 'Slovenian', 'sl', 'sl', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(67, 'Swahili', 'sw', 'ke', 0, 'no', 'no', '2019-11-20 12:21:57', '0000-00-00 00:00:00'),
(68, 'Sundanese', 'su', 'sd', 0, 'no', 'no', '2019-12-03 11:06:57', '0000-00-00 00:00:00'),
(70, 'Thai', 'th', 'th', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(71, 'Tagalog', 'tl', 'tl', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(72, 'Tamil', 'ta', 'in', 0, 'no', 'no', '2019-11-20 11:40:53', '0000-00-00 00:00:00'),
(74, 'Telugu', 'te', 'in', 0, 'no', 'no', '2019-11-20 11:41:15', '0000-00-00 00:00:00'),
(75, 'Turkish', 'tr', 'tr', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(77, 'Uzbek', 'uz', 'uz', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(79, 'Urdu', 'ur', 'pk', 0, 'no', 'no', '2019-11-20 12:23:57', '0000-00-00 00:00:00'),
(80, 'Finnish', 'fi', 'fi', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(81, 'French', 'fr', 'fr', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(82, 'Hindi', 'hi', 'in', 0, 'no', 'no', '2019-11-20 11:36:34', '0000-00-00 00:00:00'),
(84, 'Czech', 'cs', 'cz', 0, 'no', 'no', '2019-11-20 12:02:36', '0000-00-00 00:00:00'),
(85, 'Swedish', 'sv', 'sv', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(86, 'Scottish', 'gd', 'gd', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(87, 'Estonian', 'et', 'et', 0, 'no', 'no', '2019-11-20 11:24:23', '0000-00-00 00:00:00'),
(88, 'Esperanto', 'eo', 'br', 0, 'no', 'no', '2019-11-21 04:49:18', '0000-00-00 00:00:00'),
(89, 'Javanese', 'jv', 'id', 0, 'no', 'no', '2019-11-20 12:18:29', '0000-00-00 00:00:00'),
(90, 'Japanese', 'ja', 'jp', 0, 'no', 'no', '2019-11-20 12:14:39', '0000-00-00 00:00:00'),
(91, 'Polish', 'pl', 'pl', 0, 'no', 'no', '2020-06-15 03:25:27', '0000-00-00 00:00:00'),
(92, 'Kurdish', 'ku', 'iq', 0, 'no', 'no', '2020-12-21 00:15:31', '0000-00-00 00:00:00'),
(93, 'Lao', 'lo', 'la', 0, 'no', 'no', '2020-12-21 00:15:36', '0000-00-00 00:00:00'),
(94, 'Croatia', 'hr', 'hr', 0, 'no', 'no', '2022-06-07 11:48:21', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `subject_group_subject_id` int(11) NOT NULL,
  `subject_group_class_sections_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`id`, `session_id`, `subject_group_subject_id`, `subject_group_class_sections_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 21, 12, 3, 'Intro: Living a life of Faith Part 1', '2025-06-18 07:44:19', '2025-06-18 07:53:20'),
(2, 21, 12, 3, 'Intro: Living a life of Faith Part 2', '2025-06-18 07:44:19', '2025-06-18 07:53:20'),
(3, 21, 12, 3, 'Why Faith is necessary Part 1', '2025-06-18 07:44:19', '2025-06-18 07:52:46'),
(4, 21, 12, 3, 'Why Faith is necessary Part 2', '2025-06-18 07:44:19', '2025-06-18 07:52:46'),
(5, 21, 12, 3, 'Faith and the word of God Part 1', '2025-06-18 07:44:19', '2025-06-18 07:52:46'),
(6, 21, 12, 3, 'Faith and the word of God Part 2', '2025-06-18 07:44:19', '2025-06-18 07:52:46'),
(7, 21, 12, 3, 'The Law of Faith Part 1', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(8, 21, 12, 3, 'The Law of Faith Part 2', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(9, 21, 12, 3, 'Overcoming Faith challenges Part 1', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(10, 21, 12, 3, 'Overcoming Faith challenges Part 2', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(11, 21, 12, 3, 'Faith and wisdom Part 1', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(12, 21, 12, 3, 'Faith and wisdom Part 2', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(13, 21, 12, 3, 'Using Faith for victory Part 1', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(14, 21, 12, 3, 'Using Faith for victory Part 2', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(15, 21, 12, 3, 'Faith for ministry and others Part 1', '2025-06-18 07:52:46', '2025-06-18 07:52:46'),
(16, 21, 12, 3, 'Faith for ministry and others Part 2', '2025-06-18 07:52:46', '2025-06-18 07:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_plan_forum`
--

CREATE TABLE `lesson_plan_forum` (
  `id` int(11) NOT NULL,
  `subject_syllabus_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'staff,student',
  `staff_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `libarary_members`
--

CREATE TABLE `libarary_members` (
  `id` int(11) NOT NULL,
  `library_card_no` varchar(50) DEFAULT NULL,
  `member_type` varchar(50) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `record_id` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `agent` varchar(50) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `message`, `record_id`, `user_id`, `action`, `ip_address`, `platform`, `agent`, `time`, `created_at`) VALUES
(1, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.65', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-10 12:21:29', '2025-05-10 12:46:06'),
(2, 'New Record inserted On sections id 1', '1', 1, 'Insert', '1.22.208.65', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-10 12:28:58', '2025-05-10 12:46:06'),
(3, 'Record deleted On sections id 1', '1', 1, 'Delete', '1.22.208.65', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-10 12:29:04', '2025-05-10 12:46:06'),
(4, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.65', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-10 12:29:12', '2025-05-10 12:46:06'),
(5, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.47', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-21 07:25:15', '2025-05-21 07:25:15'),
(6, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.127', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-22 09:52:16', '2025-05-22 09:52:16'),
(7, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.127', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-22 09:52:48', '2025-05-22 09:52:48'),
(8, 'Record updated On settings id 1', '1', 1, 'Update', '1.22.208.127', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-22 09:52:54', '2025-05-22 09:52:54'),
(9, 'Record updated On settings id 1', '1', 1, 'Update', '152.59.47.122', 'Windows 10', 'Firefox 138.0', '2025-05-24 08:04:40', '2025-05-24 08:04:40'),
(10, 'New Record inserted On payment settings id 1', '1', 1, 'Insert', '1.22.208.23', 'Windows 10', 'Firefox 138.0', '2025-05-26 05:20:08', '2025-05-26 05:20:08'),
(11, 'Record updated On payment settings id 1', '1', 1, 'Update', '41.223.119.35', 'Android', 'Chrome 136.0.0.0', '2025-05-26 06:08:12', '2025-05-26 06:08:12'),
(12, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 09:05:34', '2025-05-26 09:05:34'),
(13, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:06:11', '2025-05-26 09:06:11'),
(14, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:06:21', '2025-05-26 09:06:21'),
(15, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:07:24', '2025-05-26 09:07:24'),
(16, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:08:18', '2025-05-26 09:08:18'),
(17, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:09:24', '2025-05-26 09:09:24'),
(18, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:09:58', '2025-05-26 09:09:58'),
(19, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:16:55', '2025-05-26 09:16:55'),
(20, 'Record updated On settings id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:18:27', '2025-05-26 09:18:27'),
(21, 'New Record inserted On sections id 2', '2', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:20:02', '2025-05-26 09:20:02'),
(22, 'New Record inserted On sections id 3', '3', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:20:08', '2025-05-26 09:20:08'),
(23, 'New Record inserted On subject groups id 1', '1', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:20:26', '2025-05-26 09:20:26'),
(24, 'New Record inserted On subject groups id 2', '2', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:20:35', '2025-05-26 09:20:35'),
(25, 'Record updated On sections id 2', '2', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:21:29', '2025-05-26 09:21:29'),
(26, 'Record updated On sections id 3', '3', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:21:40', '2025-05-26 09:21:40'),
(27, 'New Record inserted On students id 1', '1', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:32:28', '2025-05-26 09:32:28'),
(28, 'New Record inserted On  student session id 1', '1', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:32:28', '2025-05-26 09:32:28'),
(29, 'New Record inserted On users id 1', '1', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:32:28', '2025-05-26 09:32:28'),
(30, 'New Record inserted On users id 2', '2', 1, 'Insert', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:32:28', '2025-05-26 09:32:28'),
(31, 'Record updated On students id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:32:28', '2025-05-26 09:32:28'),
(32, 'Record updated On  id card id 1', '1', 1, 'Update', '41.223.119.35', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-26 11:36:31', '2025-05-26 09:36:31'),
(33, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:55:33', '2025-05-27 05:55:33'),
(34, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:55:56', '2025-05-27 05:55:56'),
(35, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:56:31', '2025-05-27 05:56:31'),
(36, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:56:57', '2025-05-27 05:56:57'),
(37, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:58:41', '2025-05-27 05:58:41'),
(38, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 07:59:14', '2025-05-27 05:59:14'),
(39, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:02:02', '2025-05-27 06:02:02'),
(40, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:02:27', '2025-05-27 06:02:27'),
(41, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:03:45', '2025-05-27 06:03:45'),
(42, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:04:17', '2025-05-27 06:04:17'),
(43, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:04:41', '2025-05-27 06:04:41'),
(44, 'Record updated On students id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:49:08', '2025-05-27 06:49:08'),
(45, 'Record updated On  student session id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:49:08', '2025-05-27 06:49:08'),
(46, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:52:47', '2025-05-27 06:52:47'),
(47, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:53:57', '2025-05-27 06:53:57'),
(48, 'Record updated On  id card id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 08:57:25', '2025-05-27 06:57:25'),
(49, 'Record updated On students id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 09:10:33', '2025-05-27 07:10:33'),
(50, 'Record updated On  student session id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 09:10:33', '2025-05-27 07:10:33'),
(51, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:07:03', '2025-05-27 08:07:03'),
(52, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:08:51', '2025-05-27 08:08:51'),
(53, 'Record updated On  id card id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:09:36', '2025-05-27 08:09:36'),
(54, 'Record updated On  id card id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:10:29', '2025-05-27 08:10:29'),
(55, 'Record updated On  id card id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:14:18', '2025-05-27 08:14:18'),
(56, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 10:30:27', '2025-05-27 08:30:27'),
(57, 'New Record inserted On students id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:36:21', '2025-05-27 11:36:21'),
(58, 'New Record inserted On  student session id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:36:21', '2025-05-27 11:36:21'),
(59, 'New Record inserted On users id 3', '3', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:36:21', '2025-05-27 11:36:21'),
(60, 'New Record inserted On users id 4', '4', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:36:21', '2025-05-27 11:36:21'),
(61, 'Record updated On students id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:36:21', '2025-05-27 11:36:21'),
(62, 'New Record inserted On  fee group id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:44:32', '2025-05-27 11:44:32'),
(63, 'New Record inserted On  fee type id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:44:54', '2025-05-27 11:44:54'),
(64, 'New Record inserted On  fee groups feetype id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:45:40', '2025-05-27 11:45:40'),
(65, 'Record updated On staff id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:46:43', '2025-05-27 11:46:43'),
(66, 'New Record inserted On  fee type id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:47:43', '2025-05-27 11:47:43'),
(67, 'New Record inserted On  fee type id 3', '3', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:47:58', '2025-05-27 11:47:58'),
(68, 'New Record inserted On  fee type id 4', '4', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:48:12', '2025-05-27 11:48:12'),
(69, 'New Record inserted On  fee type id 5', '5', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:48:38', '2025-05-27 11:48:38'),
(70, 'New Record inserted On  fee type id 6', '6', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:48:56', '2025-05-27 11:48:56'),
(71, 'New Record inserted On  fee type id 7', '7', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:49:19', '2025-05-27 11:49:19'),
(72, 'New Record inserted On  fee type id 8', '8', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:49:39', '2025-05-27 11:49:39'),
(73, 'New Record inserted On  fee group id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:50:08', '2025-05-27 11:50:08'),
(74, 'New Record inserted On  fee group id 3', '3', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:50:17', '2025-05-27 11:50:17'),
(75, 'New Record inserted On  fee group id 4', '4', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:50:55', '2025-05-27 11:50:55'),
(76, 'New Record inserted On  fee groups feetype id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:52:04', '2025-05-27 11:52:04'),
(77, 'Record updated On  fee groups fee type id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:52:21', '2025-05-27 11:52:21'),
(78, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 1', '1', 1, 'Delete', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:52:21', '2025-05-27 11:52:21'),
(79, 'New Record inserted On  fee groups feetype id 3', '3', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:52:47', '2025-05-27 11:52:47'),
(80, 'New Record inserted On  fee groups feetype id 4', '4', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:53:59', '2025-05-27 11:53:59'),
(81, 'New Record inserted On  fee groups feetype id 5', '5', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:54:26', '2025-05-27 11:54:26'),
(82, 'New Record inserted On  fee groups feetype id 6', '6', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:55:18', '2025-05-27 11:55:18'),
(83, 'New Record inserted On  fee groups feetype id 7', '7', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:56:03', '2025-05-27 11:56:03'),
(84, 'New Record inserted On  fee groups feetype id 8', '8', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:56:34', '2025-05-27 11:56:34'),
(85, 'New Record inserted On  fee groups feetype id 9', '9', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:57:03', '2025-05-27 11:57:03'),
(86, 'New Record inserted On  fee groups feetype id 10', '10', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:57:27', '2025-05-27 11:57:27'),
(87, 'New Record inserted On  fee groups feetype id 11', '11', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:57:53', '2025-05-27 11:57:53'),
(88, 'New Record inserted On  fee groups feetype id 12', '12', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:58:50', '2025-05-27 11:58:50'),
(89, 'New Record inserted On  fee groups feetype id 13', '13', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:59:13', '2025-05-27 11:59:13'),
(90, 'New Record inserted On  fee groups feetype id 14', '14', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 13:59:54', '2025-05-27 11:59:54'),
(91, 'New Record inserted On  fee groups feetype id 15', '15', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:00:24', '2025-05-27 12:00:24'),
(92, 'New Record inserted On  fee groups feetype id 16', '16', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:00:45', '2025-05-27 12:00:45'),
(93, 'New Record inserted On  fee groups feetype id 17', '17', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:01:11', '2025-05-27 12:01:11'),
(94, 'New Record inserted On  fee groups feetype id 18', '18', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:01:42', '2025-05-27 12:01:42'),
(95, 'New Record inserted On  fee groups feetype id 19', '19', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:02:01', '2025-05-27 12:02:01'),
(96, 'New Record inserted On  fee groups feetype id 20', '20', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:02:21', '2025-05-27 12:02:21'),
(97, 'New Record inserted On  fee groups feetype id 21', '21', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:02:46', '2025-05-27 12:02:46'),
(98, 'New Record inserted On  fee groups feetype id 22', '22', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:03:14', '2025-05-27 12:03:14'),
(99, 'New Record inserted On  fee groups feetype id 23', '23', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:03:33', '2025-05-27 12:03:33'),
(100, 'New Record inserted On  fee groups feetype id 24', '24', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:03:57', '2025-05-27 12:03:57'),
(101, 'New Record inserted On  fee groups feetype id 25', '25', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:04:15', '2025-05-27 12:04:15'),
(102, 'New Record inserted On  fee groups feetype id 26', '26', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:04:33', '2025-05-27 12:04:33'),
(103, 'New Record inserted On  fee groups feetype id 27', '27', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:04:58', '2025-05-27 12:04:58'),
(104, 'New Record inserted On  fee groups feetype id 28', '28', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:05:18', '2025-05-27 12:05:18'),
(105, 'Record updated On students id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:06:46', '2025-05-27 12:06:46'),
(106, 'Record updated On  student session id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:06:46', '2025-05-27 12:06:46'),
(107, 'Record updated On students id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:07:21', '2025-05-27 12:07:21'),
(108, 'Record updated On  student session id 2', '2', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:07:21', '2025-05-27 12:07:21'),
(109, 'Record updated On QR Attendance settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:19:37', '2025-05-27 12:19:37'),
(110, 'Record updated On QR Attendance settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:21:43', '2025-05-27 12:21:43'),
(111, 'Record updated On QR Attendance settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:22:21', '2025-05-27 12:22:21'),
(112, 'New Record inserted On  holiday master  id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:25:51', '2025-05-27 12:25:51'),
(113, 'Record updated On  holiday master id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:26:03', '2025-05-27 12:26:03'),
(114, 'New Record inserted On  holiday master  id 2', '2', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:27:05', '2025-05-27 12:27:05'),
(115, 'New Record inserted On  holiday master  id 3', '3', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:27:33', '2025-05-27 12:27:33'),
(116, 'New Record inserted On  holiday master  id 4', '4', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:28:30', '2025-05-27 12:28:30'),
(117, 'New Record inserted On  holiday master  id 5', '5', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:29:14', '2025-05-27 12:29:14'),
(118, 'New Record inserted On  holiday master  id 6', '6', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:29:59', '2025-05-27 12:29:59'),
(119, 'New Record inserted On  holiday master  id 7', '7', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:30:50', '2025-05-27 12:30:50'),
(120, 'Record updated On permission group id 900', '900', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:38:52', '2025-05-27 12:38:52'),
(121, 'Record updated On permission group id 23', '23', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:39:36', '2025-05-27 12:39:36'),
(122, 'Record updated On permission group id 19', '19', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:39:44', '2025-05-27 12:39:44'),
(123, 'Record updated On permission group id 11', '11', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:39:58', '2025-05-27 12:39:58'),
(124, 'Record updated On permission group id 10', '10', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:40:03', '2025-05-27 12:40:03'),
(125, 'Record updated On permission group id 9', '9', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:40:07', '2025-05-27 12:40:07'),
(126, 'Record updated On  permission student id 7', '7', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:40:34', '2025-05-27 12:40:34'),
(127, 'Record updated On permission group id 6', '6', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:40:40', '2025-05-27 12:40:40'),
(128, 'Record updated On permission group id 12', '12', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:40:54', '2025-05-27 12:40:54'),
(129, 'Record updated On permission group id 29', '29', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:41:17', '2025-05-27 12:41:17'),
(130, 'Record updated On  permission student id 24', '24', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:42:40', '2025-05-27 12:42:40'),
(131, 'Record updated On  permission student id 500', '500', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:43:04', '2025-05-27 12:43:04'),
(132, 'Record updated On permission group id 600', '600', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:43:18', '2025-05-27 12:43:18'),
(133, 'Record updated On permission group id 500', '500', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:43:32', '2025-05-27 12:43:32'),
(134, 'Record updated On permission group id 700', '700', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:43:53', '2025-05-27 12:43:53'),
(135, 'Record updated On permission group id 28', '28', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-27 14:44:17', '2025-05-27 12:44:17'),
(136, 'Record updated On  fee groups fee type id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:47:09', '2025-05-29 11:47:09'),
(137, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 1', '1', 1, 'Delete', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:47:09', '2025-05-29 11:47:09'),
(138, 'Record deleted On Cumulative Fine id 0', '0', 1, 'Delete', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:47:56', '2025-05-29 11:47:56'),
(139, 'Record updated On  fee groups fee type id 6', '6', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:48:59', '2025-05-29 11:48:59'),
(140, 'New Record inserted On cumulative_fine id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:48:59', '2025-05-29 11:48:59'),
(141, 'Record updated On  fee groups fee type id 6', '6', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:49:27', '2025-05-29 11:49:27'),
(142, 'Record updated On cumulative_fine id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 13:49:27', '2025-05-29 11:49:27'),
(143, 'New Record inserted On  fee type id 9', '9', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:04:04', '2025-05-29 12:04:04'),
(144, 'New Record inserted On  fee type id 10', '10', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:04:22', '2025-05-29 12:04:22'),
(145, 'New Record inserted On  income head   id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:09:36', '2025-05-29 12:09:36'),
(146, 'New Record inserted On  fee group id 5', '5', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:15:12', '2025-05-29 12:15:12'),
(147, 'New Record inserted On  fee type id 11', '11', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:15:38', '2025-05-29 12:15:38'),
(148, 'New Record inserted On  fee type id 12', '12', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:16:03', '2025-05-29 12:16:03'),
(149, 'New Record inserted On  fee groups feetype id 29', '29', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:17:25', '2025-05-29 12:17:25'),
(150, 'New Record inserted On  fee groups feetype id 30', '30', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:17:55', '2025-05-29 12:17:55'),
(151, 'New Record inserted On  fee groups feetype id 31', '31', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:18:28', '2025-05-29 12:18:28'),
(152, 'Record updated On permission group id 1300', '1300', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:20:00', '2025-05-29 12:20:00'),
(153, 'Record updated On permission group id 1300', '1300', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:20:09', '2025-05-29 12:20:09'),
(154, 'Record deleted On fee session groups id 5', '5', 1, 'Delete', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:25:54', '2025-05-29 12:25:54'),
(155, 'Record deleted On Cumulative Fine id 5', '5', 1, 'Delete', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:25:54', '2025-05-29 12:25:54'),
(156, 'Record updated On permission group id 10', '10', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:35:33', '2025-05-29 12:35:33'),
(157, 'Record updated On permission group id 10', '10', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:36:00', '2025-05-29 12:36:00'),
(158, 'Record updated On permission group id 9', '9', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:36:06', '2025-05-29 12:36:06'),
(159, 'Record updated On permission group id 9', '9', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:36:28', '2025-05-29 12:36:28'),
(160, 'New Record inserted On  content_types id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:37:29', '2025-05-29 12:37:29'),
(161, 'New Record inserted On  upload_contents id 1', '1', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 14:41:27', '2025-05-29 12:41:27'),
(162, 'Record updated On  fee group id 5', '5', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:05:03', '2025-05-29 13:05:03'),
(163, 'New Record inserted On  fee groups feetype id 32', '32', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:06:00', '2025-05-29 13:06:00'),
(164, 'New Record inserted On  fee type id 13', '13', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:14:33', '2025-05-29 13:14:33'),
(165, 'New Record inserted On  fee group id 6', '6', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:14:33', '2025-05-29 13:14:33'),
(166, 'New Record inserted On  fee groups feetype id 33', '33', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:14:33', '2025-05-29 13:14:33'),
(167, 'New Record inserted On Menu id 5', '5', 1, 'Insert', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:50:28', '2025-05-29 13:50:28'),
(168, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:52:15', '2025-05-29 13:52:15'),
(169, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.50', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-29 15:52:15', '2025-05-29 13:52:15'),
(170, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 17:59:43', '2025-05-29 15:59:43'),
(171, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 17:59:43', '2025-05-29 15:59:43'),
(172, 'New Record inserted On student edit fields id 1', '1', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:53:32', '2025-05-29 16:53:32'),
(173, 'New Record inserted On student edit fields id 2', '2', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:53:40', '2025-05-29 16:53:40'),
(174, 'New Record inserted On student edit fields id 3', '3', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:53:41', '2025-05-29 16:53:41'),
(175, 'New Record inserted On student edit fields id 4', '4', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:53:58', '2025-05-29 16:53:58'),
(176, 'New Record inserted On student edit fields id 5', '5', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:54:49', '2025-05-29 16:54:49'),
(177, 'New Record inserted On student edit fields id 6', '6', 1, 'Insert', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:54:51', '2025-05-29 16:54:51'),
(178, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:55:13', '2025-05-29 16:55:13'),
(179, 'Record updated On  student_dashboard_settings id 7', '7', 1, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 18:55:30', '2025-05-29 16:55:30'),
(180, 'Record updated On students id 1', '1', 0, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 19:01:24', '2025-05-29 17:01:24'),
(181, 'Record updated On students id 1', '1', 0, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 19:02:32', '2025-05-29 17:02:32'),
(182, 'Record updated On students id 1', '1', 0, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 19:03:12', '2025-05-29 17:03:12'),
(183, 'Record updated On students id 1', '1', 0, 'Update', '102.212.182.81', 'Android', 'Chrome 136.0.0.0', '2025-05-29 19:03:12', '2025-05-29 17:03:12'),
(184, 'Record updated On permission group id 11', '11', 1, 'Update', '216.234.213.25', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-30 08:04:56', '2025-05-30 06:04:56'),
(185, 'New Record inserted On transport route id 1', '1', 1, 'Insert', '216.234.213.25', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-30 08:10:39', '2025-05-30 06:10:39'),
(186, 'New Record inserted On vehicles id 1', '1', 1, 'Insert', '216.234.213.25', 'Windows 10', 'Chrome 136.0.0.0', '2025-05-30 08:11:34', '2025-05-30 06:11:34'),
(187, 'New Record inserted On Page List id 6', '6', 1, 'Insert', '216.234.213.32', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-02 08:55:29', '2025-06-02 06:55:29'),
(188, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.32', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-02 08:57:13', '2025-06-02 06:57:13'),
(189, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.32', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-02 08:57:13', '2025-06-02 06:57:13'),
(190, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.32', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-02 08:59:20', '2025-06-02 06:59:20'),
(191, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.32', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-02 08:59:20', '2025-06-02 06:59:20'),
(192, 'Record updated On permission group id 9', '9', 1, 'Update', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 10:28:00', '2025-06-04 08:28:00'),
(193, 'New Record inserted On  content_types id 2', '2', 1, 'Insert', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 10:29:08', '2025-06-04 08:29:08'),
(194, 'New Record inserted On  upload_contents id 2', '2', 1, 'Insert', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 10:30:58', '2025-06-04 08:30:58'),
(195, 'New Record inserted On roles id 8', '8', 1, 'Insert', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 11:33:05', '2025-06-04 09:33:05'),
(196, 'Record deleted On roles id 8', '8', 1, 'Delete', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 11:38:16', '2025-06-04 09:38:16'),
(197, 'Record updated On permission group id 700', '700', 1, 'Update', '216.234.213.107', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-04 11:40:06', '2025-06-04 09:40:06'),
(198, 'New Record inserted On  content_types id 3', '3', 1, 'Insert', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:30:13', '2025-06-05 07:30:13'),
(199, 'New Record inserted On  content_types id 4', '4', 1, 'Insert', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:30:24', '2025-06-05 07:30:24'),
(200, 'Record updated On  content_types id 4', '4', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:30:42', '2025-06-05 07:30:42'),
(201, 'Record updated On  content_types id 3', '3', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:31:27', '2025-06-05 07:31:27'),
(202, 'Record updated On  content_types id 4', '4', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:31:50', '2025-06-05 07:31:50'),
(203, 'Record updated On  content_types id 2', '2', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:32:30', '2025-06-05 07:32:30'),
(204, 'Record updated On  content_types id 1', '1', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:33:49', '2025-06-05 07:33:49'),
(205, 'Record updated On  content_types id 1', '1', 1, 'Update', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 09:34:47', '2025-06-05 07:34:47'),
(206, 'New Record inserted On  upload_contents id 3', '3', 1, 'Insert', '216.234.213.140', 'Windows 10', 'Chrome 136.0.0.0', '2025-06-05 10:02:08', '2025-06-05 08:02:08'),
(207, 'Record updated On permission group id 28', '28', 1, 'Update', '41.216.87.13', 'Android', 'Chrome 137.0.0.0', '2025-06-07 19:23:27', '2025-06-07 17:23:27'),
(208, 'Record updated On permission group id 29', '29', 1, 'Update', '41.216.87.13', 'Android', 'Chrome 137.0.0.0', '2025-06-07 19:23:38', '2025-06-07 17:23:38'),
(209, 'Record updated On permission group id 23', '23', 1, 'Update', '41.216.87.13', 'Android', 'Chrome 137.0.0.0', '2025-06-07 19:23:46', '2025-06-07 17:23:46'),
(210, 'Record updated On permission group id 19', '19', 1, 'Update', '41.216.87.13', 'Android', 'Chrome 137.0.0.0', '2025-06-07 19:23:58', '2025-06-07 17:23:58'),
(211, 'Record updated On QR Attendance settings id 1', '1', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:12:52', '2025-06-12 07:12:52'),
(212, 'Record updated On QR Attendance settings id 1', '1', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:19:47', '2025-06-12 07:19:47'),
(213, 'Record updated On permission group id 6', '6', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:23:49', '2025-06-12 07:23:49'),
(214, 'New Record inserted On subjects id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:40:08', '2025-06-12 07:40:08'),
(215, 'New Record inserted On subjects id 2', '2', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:40:28', '2025-06-12 07:40:28'),
(216, 'New Record inserted On subjects id 3', '3', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:40:50', '2025-06-12 07:40:50'),
(217, 'New Record inserted On subjects id 4', '4', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:41:13', '2025-06-12 07:41:13'),
(218, 'New Record inserted On subjects id 5', '5', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:41:45', '2025-06-12 07:41:45'),
(219, 'New Record inserted On subjects id 6', '6', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:42:09', '2025-06-12 07:42:09'),
(220, 'New Record inserted On subjects id 7', '7', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:42:26', '2025-06-12 07:42:26'),
(221, 'New Record inserted On subjects id 8', '8', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:42:47', '2025-06-12 07:42:47'),
(222, 'New Record inserted On subjects id 9', '9', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:43:06', '2025-06-12 07:43:06'),
(223, 'New Record inserted On subjects id 10', '10', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:43:30', '2025-06-12 07:43:30'),
(224, 'New Record inserted On subjects id 11', '11', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:43:52', '2025-06-12 07:43:52'),
(225, 'New Record inserted On subjects id 12', '12', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:44:07', '2025-06-12 07:44:07'),
(226, 'New Record inserted On subjects id 13', '13', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:44:22', '2025-06-12 07:44:22'),
(227, 'New Record inserted On subjects id 14', '14', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:44:45', '2025-06-12 07:44:45'),
(228, 'New Record inserted On subjects id 15', '15', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:45:09', '2025-06-12 07:45:09'),
(229, 'New Record inserted On subjects id 16', '16', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:45:31', '2025-06-12 07:45:31'),
(230, 'New Record inserted On subjects id 17', '17', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:45:49', '2025-06-12 07:45:49'),
(231, 'New Record inserted On subjects id 18', '18', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:47:23', '2025-06-12 07:47:23'),
(232, 'New Record inserted On subjects id 19', '19', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:49:08', '2025-06-12 07:49:08'),
(233, 'New Record inserted On subjects id 20', '20', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:49:41', '2025-06-12 07:49:41'),
(234, 'New Record inserted On subjects id 21', '21', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:50:18', '2025-06-12 07:50:18'),
(235, 'New Record inserted On subjects id 22', '22', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:51:53', '2025-06-12 07:51:53'),
(236, 'New Record inserted On subjects id 23', '23', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:52:40', '2025-06-12 07:52:40'),
(237, 'New Record inserted On subjects id 24', '24', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:53:03', '2025-06-12 07:53:03'),
(238, 'New Record inserted On subjects id 25', '25', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:53:25', '2025-06-12 07:53:25'),
(239, 'New Record inserted On subjects id 26', '26', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:53:40', '2025-06-12 07:53:40'),
(240, 'New Record inserted On subjects id 27', '27', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:54:32', '2025-06-12 07:54:32'),
(241, 'New Record inserted On subjects id 28', '28', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:55:08', '2025-06-12 07:55:08'),
(242, 'New Record inserted On subjects id 29', '29', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:55:26', '2025-06-12 07:55:26'),
(243, 'New Record inserted On subjects id 30', '30', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:55:56', '2025-06-12 07:55:56'),
(244, 'New Record inserted On subjects id 31', '31', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:56:19', '2025-06-12 07:56:19'),
(245, 'New Record inserted On subjects id 32', '32', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:56:35', '2025-06-12 07:56:35'),
(246, 'New Record inserted On subjects id 33', '33', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:57:13', '2025-06-12 07:57:13'),
(247, 'New Record inserted On subjects id 34', '34', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:57:29', '2025-06-12 07:57:29'),
(248, 'New Record inserted On subjects id 35', '35', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:57:51', '2025-06-12 07:57:51'),
(249, 'New Record inserted On subjects id 36', '36', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 09:58:10', '2025-06-12 07:58:10'),
(250, 'New Record inserted On subjects id 37', '37', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:02:09', '2025-06-12 08:02:09'),
(251, 'New Record inserted On subjects id 38', '38', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:02:38', '2025-06-12 08:02:38'),
(252, 'New Record inserted On subjects id 39', '39', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:03:01', '2025-06-12 08:03:01'),
(253, 'New Record inserted On subjects id 40', '40', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:03:14', '2025-06-12 08:03:14'),
(254, 'New Record inserted On subjects id 41', '41', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:03:44', '2025-06-12 08:03:44'),
(255, 'New Record inserted On subjects id 42', '42', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:04:03', '2025-06-12 08:04:03'),
(256, 'New Record inserted On subjects id 43', '43', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:04:46', '2025-06-12 08:04:46'),
(257, 'New Record inserted On subjects id 44', '44', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:05:04', '2025-06-12 08:05:04'),
(258, 'New Record inserted On subjects id 45', '45', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:05:21', '2025-06-12 08:05:21'),
(259, 'New Record inserted On subjects id 46', '46', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:05:46', '2025-06-12 08:05:46'),
(260, 'New Record inserted On subjects id 47', '47', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:06:15', '2025-06-12 08:06:15'),
(261, 'New Record inserted On subjects id 48', '48', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:06:33', '2025-06-12 08:06:33'),
(262, 'Record updated On subjects id 27', '27', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:06:59', '2025-06-12 08:06:59'),
(263, 'New Record inserted On subject groups id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:36:43', '2025-06-12 08:36:43'),
(264, 'New Record inserted On subject groups id 2', '2', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:39:55', '2025-06-12 08:39:55'),
(265, 'Record updated On subjects id 33', '33', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 10:59:54', '2025-06-12 08:59:54'),
(266, 'Record updated On subjects id 44', '44', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:00:40', '2025-06-12 09:00:40'),
(267, 'New Record inserted On subjects id 49', '49', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:01:07', '2025-06-12 09:01:07'),
(268, 'New Record inserted On subjects id 50', '50', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:01:42', '2025-06-12 09:01:42'),
(269, 'New Record inserted On department id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:14:12', '2025-06-12 09:14:12'),
(270, 'New Record inserted On  staff designation id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:15:07', '2025-06-12 09:15:07'),
(271, 'Record updated On staff id 2', '2', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:16:27', '2025-06-12 09:16:27'),
(272, 'Record updated On staff id 2', '2', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:17:39', '2025-06-12 09:17:39'),
(273, 'Record updated On staff id 2', '2', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:17:39', '2025-06-12 09:17:39'),
(274, 'Record updated On staff id 3', '3', 1, 'Update', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:26:49', '2025-06-12 09:26:49'),
(275, 'New Record inserted On class teacher id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:27:36', '2025-06-12 09:27:36'),
(276, 'New Record inserted On class teacher id 2', '2', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:27:36', '2025-06-12 09:27:36'),
(277, 'New Record inserted On  subject timetable id 1', '1', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:31:35', '2025-06-12 09:31:35'),
(278, 'New Record inserted On  subject timetable id 2', '2', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:32:04', '2025-06-12 09:32:04'),
(279, 'New Record inserted On  subject timetable id 3', '3', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:35:04', '2025-06-12 09:35:04'),
(280, 'New Record inserted On  subject timetable id 4', '4', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:03', '2025-06-12 09:36:03'),
(281, 'New Record inserted On  subject timetable id 3', '3', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:03', '2025-06-12 09:36:03'),
(282, 'New Record inserted On  subject timetable id 2', '2', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:03', '2025-06-12 09:36:03'),
(283, 'New Record inserted On  subject timetable id 7', '7', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:28', '2025-06-12 09:36:28'),
(284, 'New Record inserted On  subject timetable id 6', '6', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:28', '2025-06-12 09:36:28'),
(285, 'New Record inserted On  subject timetable id 5', '5', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:36:28', '2025-06-12 09:36:28'),
(286, 'New Record inserted On  subject timetable id 10', '10', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:37:29', '2025-06-12 09:37:29'),
(287, 'New Record inserted On  subject timetable id 9', '9', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:37:29', '2025-06-12 09:37:29'),
(288, 'New Record inserted On  subject timetable id 8', '8', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:37:29', '2025-06-12 09:37:29'),
(289, 'New Record inserted On subjects id 51', '51', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:39:41', '2025-06-12 09:39:41'),
(290, 'New Record inserted On subjects id 52', '52', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:39:57', '2025-06-12 09:39:57'),
(291, 'New Record inserted On  subject timetable id 13', '13', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:45:31', '2025-06-12 09:45:31'),
(292, 'New Record inserted On  subject timetable id 14', '14', 1, 'Insert', '216.234.213.215', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-12 11:46:22', '2025-06-12 09:46:22'),
(293, 'Record updated On students id 2', '2', 1, 'Update', '98.97.160.174', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:16:24', '2025-06-18 07:16:24'),
(294, 'Record updated On  student session id 2', '2', 1, 'Update', '98.97.160.174', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:16:24', '2025-06-18 07:16:24'),
(295, 'New Record inserted On Alumni Event id 1', '1', 1, 'Insert', '98.97.160.174', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:29:31', '2025-06-18 07:29:31'),
(296, 'New Record inserted On Alumni Event id 2', '2', 1, 'Insert', '98.97.160.174', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:32:15', '2025-06-18 07:32:15'),
(297, 'Record updated On subjects id 44', '44', 1, 'Update', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:40:14', '2025-06-18 07:40:14'),
(298, 'New Record inserted On lesson id 1', '1', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(299, 'New Record inserted On lesson id 2', '2', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(300, 'New Record inserted On lesson id 3', '3', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(301, 'New Record inserted On lesson id 4', '4', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(302, 'New Record inserted On lesson id 5', '5', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(303, 'New Record inserted On lesson id 6', '6', 1, 'Insert', '41.216.95.232', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:44:19', '2025-06-18 07:44:19'),
(304, 'Record updated On lesson id 1', '1', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46');
INSERT INTO `logs` (`id`, `message`, `record_id`, `user_id`, `action`, `ip_address`, `platform`, `agent`, `time`, `created_at`) VALUES
(305, 'Record updated On lesson id 2', '2', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(306, 'Record updated On lesson id 3', '3', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(307, 'Record updated On lesson id 4', '4', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(308, 'Record updated On lesson id 5', '5', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(309, 'Record updated On lesson id 6', '6', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(310, 'New Record inserted On lesson id 7', '7', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(311, 'New Record inserted On lesson id 8', '8', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(312, 'New Record inserted On lesson id 9', '9', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(313, 'New Record inserted On lesson id 10', '10', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(314, 'New Record inserted On lesson id 11', '11', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(315, 'New Record inserted On lesson id 12', '12', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(316, 'New Record inserted On lesson id 13', '13', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(317, 'New Record inserted On lesson id 14', '14', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(318, 'New Record inserted On lesson id 15', '15', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(319, 'New Record inserted On lesson id 16', '16', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:52:46', '2025-06-18 07:52:46'),
(320, 'Record updated On lesson id 1', '1', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(321, 'Record updated On lesson id 2', '2', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(322, 'Record updated On lesson id 3', '3', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(323, 'Record updated On lesson id 4', '4', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(324, 'Record updated On lesson id 5', '5', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(325, 'Record updated On lesson id 6', '6', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(326, 'Record updated On lesson id 7', '7', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(327, 'Record updated On lesson id 8', '8', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(328, 'Record updated On lesson id 9', '9', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(329, 'Record updated On lesson id 10', '10', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(330, 'Record updated On lesson id 11', '11', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(331, 'Record updated On lesson id 12', '12', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(332, 'Record updated On lesson id 13', '13', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(333, 'Record updated On lesson id 14', '14', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(334, 'Record updated On lesson id 15', '15', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(335, 'Record updated On lesson id 16', '16', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:53:20', '2025-06-18 07:53:20'),
(336, 'New Record inserted On topic id 1', '1', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:54:59', '2025-06-18 07:54:59'),
(337, 'Record updated On topic id 1', '1', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:57:20', '2025-06-18 07:57:20'),
(338, 'New Record inserted On topic id 2', '2', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:57:20', '2025-06-18 07:57:20'),
(339, 'New Record inserted On topic id 3', '3', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:57:20', '2025-06-18 07:57:20'),
(340, 'New Record inserted On topic id 4', '4', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:23', '2025-06-18 07:59:23'),
(341, 'New Record inserted On topic id 5', '5', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:23', '2025-06-18 07:59:23'),
(342, 'New Record inserted On topic id 6', '6', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:23', '2025-06-18 07:59:23'),
(343, 'Record updated On topic id 4', '4', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:48', '2025-06-18 07:59:48'),
(344, 'Record updated On topic id 5', '5', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:48', '2025-06-18 07:59:48'),
(345, 'Record updated On topic id 6', '6', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 09:59:48', '2025-06-18 07:59:48'),
(346, 'Record updated On topic id 4', '4', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:00:46', '2025-06-18 08:00:46'),
(347, 'Record updated On topic id 5', '5', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:00:46', '2025-06-18 08:00:46'),
(348, 'Record updated On topic id 6', '6', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:00:46', '2025-06-18 08:00:46'),
(349, 'New Record inserted On topic id 7', '7', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:00:46', '2025-06-18 08:00:46'),
(350, 'Record updated On topic id 4', '4', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:01:55', '2025-06-18 08:01:55'),
(351, 'Record updated On topic id 5', '5', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:01:55', '2025-06-18 08:01:55'),
(352, 'Record updated On topic id 6', '6', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:01:55', '2025-06-18 08:01:55'),
(353, 'Record updated On topic id 7', '7', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:01:55', '2025-06-18 08:01:55'),
(354, 'New Record inserted On  content_types id 5', '5', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:02:57', '2025-06-18 08:02:57'),
(355, 'New Record inserted On  upload_contents id 4', '4', 1, 'Insert', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:03:45', '2025-06-18 08:03:45'),
(356, 'Record updated On  student_dashboard_settings id 7', '7', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:11:55', '2025-06-18 08:11:55'),
(357, 'Record updated On  permission student id 13', '13', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:13:10', '2025-06-18 08:13:10'),
(358, 'Record updated On permission group id 12', '12', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:13:23', '2025-06-18 08:13:23'),
(359, 'Record updated On permission group id 500', '500', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:13:34', '2025-06-18 08:13:34'),
(360, 'Record updated On permission group id 600', '600', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:13:39', '2025-06-18 08:13:39'),
(361, 'Record updated On  permission student id 900', '900', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-18 10:15:05', '2025-06-18 08:15:05'),
(362, 'New Record inserted On exam groups id 1', '1', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 12:20:08', '2025-06-20 10:20:08'),
(363, 'New Record inserted On exam group exams name id 1', '1', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 12:21:28', '2025-06-20 10:21:28'),
(364, 'Record updated On permission group id 900', '900', 1, 'Update', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 13:17:27', '2025-06-20 11:17:27'),
(365, 'New Record inserted On cbse terms id 1', '1', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 13:18:33', '2025-06-20 11:18:33'),
(366, 'New Record inserted On cbse terms id 2', '2', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 13:18:44', '2025-06-20 11:18:44'),
(367, 'New Record inserted On cbse terms id 3', '3', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 13:18:56', '2025-06-20 11:18:56'),
(368, 'New Record inserted On cbse terms id 4', '4', 1, 'Insert', '216.234.213.153', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-20 13:19:12', '2025-06-20 11:19:12'),
(369, 'New Record inserted On grades id 1', '1', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:26:34', '2025-06-25 07:26:34'),
(370, 'New Record inserted On grades id 2', '2', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:27:07', '2025-06-25 07:27:07'),
(371, 'New Record inserted On grades id 3', '3', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:27:41', '2025-06-25 07:27:41'),
(372, 'New Record inserted On grades id 4', '4', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:29:26', '2025-06-25 07:29:26'),
(373, 'Record updated On grades id 4', '4', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:29:39', '2025-06-25 07:29:39'),
(374, 'New Record inserted On grades id 5', '5', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:30:54', '2025-06-25 07:30:54'),
(375, 'New Record inserted On grades id 6', '6', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:31:37', '2025-06-25 07:31:37'),
(376, 'New Record inserted On grades id 7', '7', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:32:23', '2025-06-25 07:32:23'),
(377, 'New Record inserted On  marksheets id 2', '2', 1, 'Insert', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:42:44', '2025-06-25 07:42:44'),
(378, 'Record updated On  marksheets id 2', '2', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:43:34', '2025-06-25 07:43:34'),
(379, 'Record updated On  marksheets id 2', '2', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:45:33', '2025-06-25 07:45:33'),
(380, 'Record updated On  marksheets id 2', '2', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:46:05', '2025-06-25 07:46:05'),
(381, 'Record updated On  exam groups id 1', '1', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:47:13', '2025-06-25 07:47:13'),
(382, 'New Record inserted On exam groups id 2', '2', 1, 'Insert', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:47:39', '2025-06-25 07:47:39'),
(383, 'Record updated On  exam group exams name id 1', '1', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:49:50', '2025-06-25 07:49:50'),
(384, 'Record updated On  exam group exams name id 1', '1', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 09:54:59', '2025-06-25 07:54:59'),
(385, 'Record updated On  exam group exams name id 1', '1', 1, 'Update', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 10:03:39', '2025-06-25 08:03:39'),
(386, 'New Record inserted On exam group exams name id 2', '2', 1, 'Insert', '41.216.82.21', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-25 10:04:03', '2025-06-25 08:04:03'),
(387, 'New Record inserted On  holiday master  id 8', '8', 1, 'Insert', '216.234.213.108', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-26 14:03:27', '2025-06-26 12:03:27'),
(388, 'New Record inserted On subjects id 53', '53', 1, 'Insert', '216.234.213.108', 'Windows 10', 'Chrome 137.0.0.0', '2025-06-26 16:47:41', '2025-06-26 14:47:41'),
(389, 'Record updated On  exam group exams name id 1', '1', 1, 'Update', '41.216.95.230', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-01 17:44:09', '2025-07-01 15:44:09'),
(390, 'Record updated On  marksheets id 2', '2', 1, 'Update', '41.216.95.230', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-01 17:55:07', '2025-07-01 15:55:07'),
(391, 'Record updated On  marksheets id 2', '2', 1, 'Update', '41.216.95.230', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-01 17:55:59', '2025-07-01 15:55:59'),
(392, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.16', 'Windows 10', 'Chrome 137.0.0.0', '2025-07-03 14:26:54', '2025-07-03 12:26:54'),
(393, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.16', 'Windows 10', 'Chrome 137.0.0.0', '2025-07-03 14:27:42', '2025-07-03 12:27:42'),
(394, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.16', 'Windows 10', 'Chrome 137.0.0.0', '2025-07-03 14:31:58', '2025-07-03 12:31:58'),
(395, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 13:18:17', '2025-07-15 11:18:17'),
(396, 'New Record inserted On event id 1', '1', 1, 'Insert', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 13:20:54', '2025-07-15 11:20:54'),
(397, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 13:21:24', '2025-07-15 11:21:24'),
(398, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 13:21:24', '2025-07-15 11:21:24'),
(399, 'Record deleted On menu id 5', '5', 1, 'Delete', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 13:23:28', '2025-07-15 11:23:28'),
(400, 'Record updated On Menu id 4', '4', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 14:43:09', '2025-07-15 12:43:09'),
(401, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 14:44:37', '2025-07-15 12:44:37'),
(402, 'Record updated On  online_admission_fields id 23', '23', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:05:19', '2025-07-15 13:05:19'),
(403, 'Record updated On  online_admission_fields id 3', '3', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:05:49', '2025-07-15 13:05:49'),
(404, 'Record updated On  online_admission_fields id 3', '3', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:06:30', '2025-07-15 13:06:30'),
(405, 'New Record inserted On Menu id 6', '6', 1, 'Insert', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:22:07', '2025-07-15 13:22:07'),
(406, 'Record updated On Menu id 6', '6', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:22:54', '2025-07-15 13:22:54'),
(407, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:29:49', '2025-07-15 13:29:49'),
(408, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:38:19', '2025-07-15 13:38:19'),
(409, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:38:19', '2025-07-15 13:38:19'),
(410, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:39:09', '2025-07-15 13:39:09'),
(411, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:39:09', '2025-07-15 13:39:09'),
(412, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:39:32', '2025-07-15 13:39:32'),
(413, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:39:33', '2025-07-15 13:39:33'),
(414, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:40:44', '2025-07-15 13:40:44'),
(415, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:40:44', '2025-07-15 13:40:44'),
(416, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:49:03', '2025-07-15 13:49:03'),
(417, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 15:49:03', '2025-07-15 13:49:03'),
(418, 'Record updated On Menu id 6', '6', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 16:21:04', '2025-07-15 14:21:04'),
(419, 'Record updated On Menu id 6', '6', 1, 'Update', '216.234.213.74', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-15 16:21:21', '2025-07-15 14:21:21'),
(420, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.216.95.232', 'Android', 'Chrome 138.0.0.0', '2025-07-20 11:40:26', '2025-07-20 09:40:26'),
(421, 'Record updated On settings id 1', '1', 1, 'Update', '41.216.95.232', 'Android', 'Chrome 138.0.0.0', '2025-07-20 11:40:26', '2025-07-20 09:40:26'),
(422, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '102.150.51.143', 'Android', 'Chrome 138.0.0.0', '2025-07-27 08:35:48', '2025-07-27 06:35:48'),
(423, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.150.51.143', 'Android', 'Chrome 138.0.0.0', '2025-07-27 08:37:18', '2025-07-27 06:37:18'),
(424, 'Record updated On settings id 1', '1', 1, 'Update', '102.150.51.143', 'Android', 'Chrome 138.0.0.0', '2025-07-27 08:37:18', '2025-07-27 06:37:18'),
(425, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.150.51.143', 'Android', 'Chrome 138.0.0.0', '2025-07-27 08:43:24', '2025-07-27 06:43:24'),
(426, 'Record updated On settings id 1', '1', 1, 'Update', '102.150.51.143', 'Android', 'Chrome 138.0.0.0', '2025-07-27 08:43:24', '2025-07-27 06:43:24'),
(427, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 14:29:05', '2025-07-28 12:29:05'),
(428, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 14:29:05', '2025-07-28 12:29:05'),
(429, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 14:34:03', '2025-07-28 12:34:03'),
(430, 'New Record inserted On Page List id 7', '7', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 16:48:49', '2025-07-28 14:48:49'),
(431, 'Record deleted On Page List id about-us', 'about-us', 1, 'Delete', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 17:22:54', '2025-07-28 15:22:54'),
(432, 'New Record inserted On Page List id 8', '8', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 17:48:13', '2025-07-28 15:48:13'),
(433, 'Record updated On  Page List id 8', '8', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:10:25', '2025-07-28 16:10:25'),
(434, 'New Record inserted On Page List id 9', '9', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:16:45', '2025-07-28 16:16:45'),
(435, 'Record updated On  Page List id 9', '9', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:17:22', '2025-07-28 16:17:22'),
(436, 'Record updated On  Page List id 9', '9', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:17:58', '2025-07-28 16:17:58'),
(437, 'New Record inserted On Page List id 10', '10', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:22:14', '2025-07-28 16:22:14'),
(438, 'Record updated On  Page List id 8', '8', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:27:58', '2025-07-28 16:27:58'),
(439, 'New Record inserted On Menu id 7', '7', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:31:16', '2025-07-28 16:31:16'),
(440, 'New Record inserted On  Complain id 1', '1', 1, 'Insert', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:34:06', '2025-07-28 16:34:06'),
(441, 'Record updated On Menu id 3', '3', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:36:42', '2025-07-28 16:36:42'),
(442, 'Record deleted On menu id 2', '2', 1, 'Delete', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:37:49', '2025-07-28 16:37:49'),
(443, 'Record updated On Menu id 3', '3', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:38:09', '2025-07-28 16:38:09'),
(444, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:42:12', '2025-07-28 16:42:12'),
(445, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:42:12', '2025-07-28 16:42:12'),
(446, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:42:56', '2025-07-28 16:42:56'),
(447, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:42:56', '2025-07-28 16:42:56'),
(448, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:43:35', '2025-07-28 16:43:35'),
(449, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:43:35', '2025-07-28 16:43:35'),
(450, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:45:18', '2025-07-28 16:45:18'),
(451, 'Record updated On settings id 1', '1', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:45:18', '2025-07-28 16:45:18'),
(452, 'Record updated On  Page List id 8', '8', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:48:02', '2025-07-28 16:48:02'),
(453, 'Record updated On  Page List id 8', '8', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:49:47', '2025-07-28 16:49:47'),
(454, 'Record updated On  Page List id 8', '8', 1, 'Update', '102.212.181.106', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-28 18:50:20', '2025-07-28 16:50:20'),
(455, 'New Record inserted On event id 2', '2', 1, 'Insert', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-29 11:45:54', '2025-07-29 09:45:54'),
(456, 'Record updated On  event id 2', '2', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-29 12:26:48', '2025-07-29 10:26:48'),
(457, 'New Record inserted On event id 3', '3', 1, 'Insert', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-29 12:29:03', '2025-07-29 10:29:03'),
(458, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-29 13:06:45', '2025-07-29 11:06:45'),
(459, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-29 13:06:45', '2025-07-29 11:06:45'),
(460, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 10:51:48', '2025-07-30 08:51:48'),
(461, 'Record updated On settings id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 10:51:48', '2025-07-30 08:51:48'),
(462, 'New Record inserted On Page List id 11', '11', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:15:11', '2025-07-30 09:15:11'),
(463, 'New Record inserted On Page List id 12', '12', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:15:30', '2025-07-30 09:15:30'),
(464, 'New Record inserted On Page List id 13', '13', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:17:47', '2025-07-30 09:17:47'),
(465, 'New Record inserted On Page List id 14', '14', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:18:47', '2025-07-30 09:18:47'),
(466, 'New Record inserted On Menu id 8', '8', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:21:00', '2025-07-30 09:21:00'),
(467, 'New Record inserted On Menu id 9', '9', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:21:12', '2025-07-30 09:21:12'),
(468, 'New Record inserted On Menu id 10', '10', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:21:22', '2025-07-30 09:21:22'),
(469, 'New Record inserted On Menu id 11', '11', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:24:13', '2025-07-30 09:24:13'),
(470, 'New Record inserted On Menu id 12', '12', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:24:50', '2025-07-30 09:24:50'),
(471, 'Record updated On Menu id 12', '12', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:25:15', '2025-07-30 09:25:15'),
(472, 'New Record inserted On Menu id 13', '13', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:25:47', '2025-07-30 09:25:47'),
(473, 'New Record inserted On Menu id 14', '14', 1, 'Insert', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:26:39', '2025-07-30 09:26:39'),
(474, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:34:01', '2025-07-30 09:34:01'),
(475, 'Record updated On settings id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-07-30 11:34:01', '2025-07-30 09:34:01'),
(476, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-01 08:54:04', '2025-08-01 06:54:04'),
(477, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.168', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-01 08:54:04', '2025-08-01 06:54:04'),
(478, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:35:40', '2025-08-05 09:35:40'),
(479, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:36:25', '2025-08-05 09:36:25'),
(480, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:36:26', '2025-08-05 09:36:26'),
(481, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:36:28', '2025-08-05 09:36:28'),
(482, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:36:31', '2025-08-05 09:36:31'),
(483, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:36:33', '2025-08-05 09:36:33'),
(484, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:38:17', '2025-08-05 09:38:17'),
(485, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:38:20', '2025-08-05 09:38:20'),
(486, 'Record deleted On banner delete id 1', '1', 1, 'Delete', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:39:41', '2025-08-05 09:39:41'),
(487, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:43:23', '2025-08-05 09:43:23'),
(488, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:43:23', '2025-08-05 09:43:23'),
(489, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:09', '2025-08-05 09:44:09'),
(490, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:09', '2025-08-05 09:44:09'),
(491, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:41', '2025-08-05 09:44:41'),
(492, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:41', '2025-08-05 09:44:41'),
(493, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:56', '2025-08-05 09:44:56'),
(494, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:44:56', '2025-08-05 09:44:56'),
(495, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:45:10', '2025-08-05 09:45:10'),
(496, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:45:10', '2025-08-05 09:45:10'),
(497, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:45:35', '2025-08-05 09:45:35'),
(498, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:45:35', '2025-08-05 09:45:35'),
(499, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:47:29', '2025-08-05 09:47:29'),
(500, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:47:29', '2025-08-05 09:47:29'),
(501, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:47:56', '2025-08-05 09:47:56'),
(502, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:47:56', '2025-08-05 09:47:56'),
(503, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:48:20', '2025-08-05 09:48:20'),
(504, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:48:20', '2025-08-05 09:48:20'),
(505, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:49:10', '2025-08-05 09:49:10'),
(506, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 11:49:10', '2025-08-05 09:49:10'),
(507, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:04:34', '2025-08-05 10:04:34'),
(508, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:04:34', '2025-08-05 10:04:34'),
(509, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:05:02', '2025-08-05 10:05:02'),
(510, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:05:02', '2025-08-05 10:05:02'),
(511, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:18:39', '2025-08-05 10:18:39'),
(512, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 12:22:28', '2025-08-05 10:22:28'),
(513, 'Record updated On  Page List id 8', '8', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 13:56:24', '2025-08-05 11:56:24'),
(514, 'New Record inserted On Menu id 15', '15', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:16:28', '2025-08-05 12:16:28'),
(515, 'New Record inserted On Menu id 16', '16', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:17:00', '2025-08-05 12:17:00'),
(516, 'New Record inserted On Menu id 17', '17', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:17:29', '2025-08-05 12:17:29'),
(517, 'New Record inserted On Menu id 18', '18', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:18:08', '2025-08-05 12:18:08'),
(518, 'Record updated On Menu id 4', '4', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:19:21', '2025-08-05 12:19:21'),
(519, 'Record updated On  Page List id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:31:47', '2025-08-05 12:31:47'),
(520, 'New Record inserted On Menu id 19', '19', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:39:31', '2025-08-05 12:39:31'),
(521, 'Record updated On Menu id 19', '19', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:40:14', '2025-08-05 12:40:14'),
(522, 'New Record inserted On Page List id 15', '15', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:45:56', '2025-08-05 12:45:56'),
(523, 'New Record inserted On Page List id 16', '16', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:46:23', '2025-08-05 12:46:23'),
(524, 'New Record inserted On Page List id 17', '17', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:46:56', '2025-08-05 12:46:56'),
(525, 'New Record inserted On Menu id 20', '20', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:48:01', '2025-08-05 12:48:01'),
(526, 'New Record inserted On Menu id 21', '21', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:48:13', '2025-08-05 12:48:13'),
(527, 'Record updated On Menu id 8', '8', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:48:49', '2025-08-05 12:48:49'),
(528, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:50:48', '2025-08-05 12:50:48'),
(529, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:50:48', '2025-08-05 12:50:48'),
(530, 'New Record inserted On Menu id 22', '22', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 14:52:33', '2025-08-05 12:52:33'),
(531, 'New Record inserted On event id 4', '4', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:00:15', '2025-08-05 13:00:15'),
(532, 'Record updated On  event id 2', '2', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:00:25', '2025-08-05 13:00:25'),
(533, 'New Record inserted On course category id 1', '1', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:32:24', '2025-08-05 13:32:24'),
(534, 'New Record inserted On online courses id 1', '1', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:34:31', '2025-08-05 13:34:31'),
(535, 'New Record inserted On online course class sections id 1', '1', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:34:31', '2025-08-05 13:34:31'),
(536, 'New Record inserted On online course class sections id 2', '2', 1, 'Insert', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:34:31', '2025-08-05 13:34:31'),
(537, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:34:31', '2025-08-05 13:34:31'),
(538, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:37:13', '2025-08-05 13:37:13'),
(539, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:37:13', '2025-08-05 13:37:13'),
(540, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '216.234.213.133', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 15:37:20', '2025-08-05 13:37:20'),
(541, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '102.151.160.72', 'Android', 'Chrome 138.0.0.0', '2025-08-05 17:41:22', '2025-08-05 15:41:22'),
(542, 'Record updated On Menu id 19', '19', 1, 'Update', '102.151.160.72', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-05 18:05:15', '2025-08-05 16:05:15'),
(543, 'Record updated On course category id 1', '1', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:25:31', '2025-08-05 16:25:31'),
(544, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:27:25', '2025-08-05 16:27:25'),
(545, 'Record updated On online c1ourses id 1', '1', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:27:25', '2025-08-05 16:27:25'),
(546, 'New Record inserted On online courses id 2', '2', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:33:26', '2025-08-05 16:33:26'),
(547, 'New Record inserted On online course class sections id 3', '3', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:33:26', '2025-08-05 16:33:26'),
(548, 'New Record inserted On online course class sections id 4', '4', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:33:26', '2025-08-05 16:33:26'),
(549, 'Record updated On online c1ourses id 2', '2', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:33:26', '2025-08-05 16:33:26'),
(550, 'Record updated On online c1ourses id 2', '2', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:33:38', '2025-08-05 16:33:38'),
(551, 'New Record inserted On online courses id 3', '3', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:34:44', '2025-08-05 16:34:44'),
(552, 'New Record inserted On online course class sections id 5', '5', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:34:44', '2025-08-05 16:34:44'),
(553, 'New Record inserted On online course class sections id 6', '6', 1, 'Insert', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:34:44', '2025-08-05 16:34:44'),
(554, 'Record updated On online c1ourses id 3', '3', 1, 'Update', '41.223.117.76', 'Android', 'Chrome 138.0.0.0', '2025-08-05 18:34:44', '2025-08-05 16:34:44'),
(555, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-06 09:10:33', '2025-08-06 07:10:33'),
(556, 'Record updated On settings id 1', '1', 1, 'Update', '41.216.82.26', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-06 09:10:33', '2025-08-06 07:10:33'),
(557, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-08 09:54:32', '2025-08-08 07:54:32'),
(558, 'Record updated On settings id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-08 09:54:32', '2025-08-08 07:54:32'),
(559, 'Record updated On  Page List id 11', '11', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-08 10:00:46', '2025-08-08 08:00:46'),
(560, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-08 10:03:03', '2025-08-08 08:03:03'),
(561, 'Record updated On settings id 1', '1', 1, 'Update', '41.175.23.218', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-08 10:03:03', '2025-08-08 08:03:03'),
(562, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:02:44', '2025-08-09 01:02:44'),
(563, 'Record updated On settings id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:02:44', '2025-08-09 01:02:44'),
(564, 'New Record inserted On course category id 2', '2', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:31:53', '2025-08-09 01:31:53'),
(565, 'New Record inserted On online courses id 4', '4', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:21', '2025-08-09 01:38:21'),
(566, 'New Record inserted On online course class sections id 7', '7', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:21', '2025-08-09 01:38:21'),
(567, 'New Record inserted On online course class sections id 8', '8', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:21', '2025-08-09 01:38:21'),
(568, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:21', '2025-08-09 01:38:21'),
(569, 'New Record inserted On online course section id 1', '1', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:51', '2025-08-09 01:38:51'),
(570, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:38:51', '2025-08-09 01:38:51'),
(571, 'Record updated On online course section id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:56:35', '2025-08-09 01:56:35'),
(572, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 03:56:35', '2025-08-09 01:56:35'),
(573, 'New Record inserted On online course lesson id 1', '1', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:02:04', '2025-08-09 02:02:04'),
(574, 'New Record inserted On lesson quiz id 1', '1', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:02:04', '2025-08-09 02:02:04'),
(575, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:02:04', '2025-08-09 02:02:04'),
(576, 'Record updated On  online course lesson id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:02:04', '2025-08-09 02:02:04'),
(577, 'Record updated On  online course lesson id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:02:04', '2025-08-09 02:02:04'),
(578, 'New Record inserted On online course section id 2', '2', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:04:23', '2025-08-09 02:04:23'),
(579, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:04:23', '2025-08-09 02:04:23'),
(580, 'New Record inserted On online course lesson id 2', '2', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:09:25', '2025-08-09 02:09:25'),
(581, 'New Record inserted On lesson quiz id 2', '2', 1, 'Insert', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:09:25', '2025-08-09 02:09:25'),
(582, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:09:25', '2025-08-09 02:09:25'),
(583, 'Record updated On  online course lesson id 2', '2', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:09:25', '2025-08-09 02:09:25'),
(584, 'Record updated On  online course lesson id 2', '2', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:09:25', '2025-08-09 02:09:25'),
(585, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:11:39', '2025-08-09 02:11:39'),
(586, 'Record updated On online c1ourses id 4', '4', 0, 'Update', '102.144.34.253', 'Android', 'Chrome 138.0.0.0', '2025-08-09 04:13:51', '2025-08-09 02:13:51'),
(587, 'Record updated On online c1ourses id 4', '4', 0, 'Update', '66.249.93.132', 'Android', 'Chrome 138.0.0.0', '2025-08-09 04:13:56', '2025-08-09 02:13:56'),
(588, 'Record updated On online c1ourses id 4', '4', 0, 'Update', '66.249.93.133', 'Android', 'Chrome 138.0.0.0', '2025-08-09 04:13:56', '2025-08-09 02:13:56'),
(589, 'Record updated On online c1ourses id 4', '4', 0, 'Update', '66.249.93.133', 'Android', 'Chrome 138.0.0.0', '2025-08-09 04:13:56', '2025-08-09 02:13:56'),
(590, 'Record updated On online c1ourses id 4', '4', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:16:26', '2025-08-09 02:16:26'),
(591, 'Record deleted On online course lesson quiz id 1', '1', 1, 'Delete', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:16:37', '2025-08-09 02:16:37'),
(592, 'Record deleted On online course lesson id 1', '1', 1, 'Delete', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:16:37', '2025-08-09 02:16:37'),
(593, 'Record deleted On online course lesson quiz id 2', '2', 1, 'Delete', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:16:44', '2025-08-09 02:16:44'),
(594, 'Record deleted On online course lesson id 2', '2', 1, 'Delete', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:16:44', '2025-08-09 02:16:44'),
(595, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '20.171.207.228', 'Unknown Platform', 'Mozilla 5.0', '2025-08-09 04:24:34', '2025-08-09 02:24:34'),
(596, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '20.171.207.228', 'Unknown Platform', 'Mozilla 5.0', '2025-08-09 04:24:36', '2025-08-09 02:24:36'),
(597, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:26:03', '2025-08-09 02:26:03'),
(598, 'Record updated On settings id 1', '1', 1, 'Update', '102.144.34.253', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-09 04:26:03', '2025-08-09 02:26:03'),
(599, 'Record updated On staff id 1', '1', 1, 'Update', '102.212.181.67', 'Android', 'Chrome 138.0.0.0', '2025-08-10 07:16:13', '2025-08-10 05:16:13'),
(600, 'Record updated On  event id 3', '3', 1, 'Update', '41.216.82.26', 'Android', 'Chrome 138.0.0.0', '2025-08-11 16:31:43', '2025-08-11 14:31:43'),
(601, 'Record updated On  event id 3', '3', 1, 'Update', '41.216.82.26', 'Android', 'Chrome 138.0.0.0', '2025-08-11 16:31:58', '2025-08-11 14:31:58'),
(602, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.240', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-12 13:55:06', '2025-08-12 11:55:06'),
(603, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.240', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-12 13:55:06', '2025-08-12 11:55:06'),
(604, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '40.77.167.123', 'Unknown Platform', 'Bing', '2025-08-12 14:14:09', '2025-08-12 12:14:09'),
(605, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '40.77.167.123', 'Unknown Platform', 'Bing', '2025-08-12 14:14:09', '2025-08-12 12:14:09'),
(606, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.240', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-12 16:15:57', '2025-08-12 14:15:57'),
(607, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.240', 'Windows 10', 'Chrome 138.0.0.0', '2025-08-12 16:15:57', '2025-08-12 14:15:57'),
(608, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.91', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-15 10:05:11', '2025-09-15 08:05:11'),
(609, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.91', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-15 10:05:11', '2025-09-15 08:05:11'),
(610, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '20.171.207.146', 'Unknown Platform', 'Mozilla 5.0', '2025-09-17 23:26:05', '2025-09-17 21:26:05'),
(611, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '20.171.207.146', 'Unknown Platform', 'Mozilla 5.0', '2025-09-17 23:26:07', '2025-09-17 21:26:07'),
(612, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 10:12:25', '2025-09-18 08:12:25'),
(613, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 10:12:25', '2025-09-18 08:12:25'),
(614, 'Record updated On Front CMS Setting id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:21:36', '2025-09-18 09:21:36');
INSERT INTO `logs` (`id`, `message`, `record_id`, `user_id`, `action`, `ip_address`, `platform`, `agent`, `time`, `created_at`) VALUES
(615, 'Record updated On settings id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:21:36', '2025-09-18 09:21:36'),
(616, 'Record updated On  Page List id 16', '16', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:24:17', '2025-09-18 09:24:17'),
(617, 'Record updated On  Page List id 16', '16', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:25:32', '2025-09-18 09:25:32'),
(618, 'Record updated On  Page List id 13', '13', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:34:37', '2025-09-18 09:34:37'),
(619, 'Record updated On  online_admission_fields id 31', '31', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 11:38:58', '2025-09-18 09:38:58'),
(620, 'Record updated On  fee groups fee type id 7', '7', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:10:43', '2025-09-18 11:10:43'),
(621, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 7', '7', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:10:43', '2025-09-18 11:10:43'),
(622, 'Record updated On  fee groups fee type id 8', '8', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:11:50', '2025-09-18 11:11:50'),
(623, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 8', '8', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:11:50', '2025-09-18 11:11:50'),
(624, 'Record updated On  fee groups fee type id 9', '9', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:14', '2025-09-18 11:12:14'),
(625, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 9', '9', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:14', '2025-09-18 11:12:14'),
(626, 'Record updated On  fee groups fee type id 8', '8', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:40', '2025-09-18 11:12:40'),
(627, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 8', '8', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:40', '2025-09-18 11:12:40'),
(628, 'Record updated On  fee groups fee type id 10', '10', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:59', '2025-09-18 11:12:59'),
(629, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 10', '10', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:12:59', '2025-09-18 11:12:59'),
(630, 'Record updated On  fee groups fee type id 11', '11', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:13:20', '2025-09-18 11:13:20'),
(631, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 11', '11', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:13:20', '2025-09-18 11:13:20'),
(632, 'Record updated On  fee groups fee type id 12', '12', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:14:40', '2025-09-18 11:14:40'),
(633, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 12', '12', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:14:40', '2025-09-18 11:14:40'),
(634, 'Record updated On  fee groups fee type id 8', '8', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:28', '2025-09-18 11:16:28'),
(635, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 8', '8', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:28', '2025-09-18 11:16:28'),
(636, 'Record updated On  fee groups fee type id 10', '10', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:41', '2025-09-18 11:16:41'),
(637, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 10', '10', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:41', '2025-09-18 11:16:41'),
(638, 'Record updated On  fee groups fee type id 11', '11', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:51', '2025-09-18 11:16:51'),
(639, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 11', '11', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:16:51', '2025-09-18 11:16:51'),
(640, 'Record updated On  fee groups fee type id 12', '12', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:17:02', '2025-09-18 11:17:02'),
(641, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 12', '12', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:17:02', '2025-09-18 11:17:02'),
(642, 'Record updated On  fee groups fee type id 13', '13', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:18:30', '2025-09-18 11:18:30'),
(643, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 13', '13', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:18:30', '2025-09-18 11:18:30'),
(644, 'Record updated On  fee groups fee type id 8', '8', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:18:37', '2025-09-18 11:18:37'),
(645, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 8', '8', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:18:37', '2025-09-18 11:18:37'),
(646, 'Record updated On  fee groups fee type id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:01', '2025-09-18 11:19:01'),
(647, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 1', '1', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:01', '2025-09-18 11:19:01'),
(648, 'Record updated On  fee groups fee type id 2', '2', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:18', '2025-09-18 11:19:18'),
(649, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 2', '2', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:18', '2025-09-18 11:19:18'),
(650, 'Record updated On  fee groups fee type id 3', '3', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:27', '2025-09-18 11:19:27'),
(651, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 3', '3', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:27', '2025-09-18 11:19:27'),
(652, 'Record updated On  fee groups fee type id 4', '4', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:39', '2025-09-18 11:19:39'),
(653, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 4', '4', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:39', '2025-09-18 11:19:39'),
(654, 'Record updated On  fee groups fee type id 5', '5', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:48', '2025-09-18 11:19:48'),
(655, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 5', '5', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:19:48', '2025-09-18 11:19:48'),
(656, 'Record updated On  fee groups fee type id 6', '6', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:20:21', '2025-09-18 11:20:21'),
(657, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 6', '6', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:20:21', '2025-09-18 11:20:21'),
(658, 'Record updated On  fee groups fee type id 14', '14', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:22:45', '2025-09-18 11:22:45'),
(659, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 14', '14', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:22:45', '2025-09-18 11:22:45'),
(660, 'Record updated On  fee groups fee type id 15', '15', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:01', '2025-09-18 11:23:01'),
(661, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 15', '15', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:01', '2025-09-18 11:23:01'),
(662, 'Record updated On  fee groups fee type id 16', '16', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:14', '2025-09-18 11:23:14'),
(663, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 16', '16', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:14', '2025-09-18 11:23:14'),
(664, 'Record updated On  fee groups fee type id 18', '18', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:28', '2025-09-18 11:23:28'),
(665, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 18', '18', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:28', '2025-09-18 11:23:28'),
(666, 'Record updated On  fee groups fee type id 20', '20', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:42', '2025-09-18 11:23:42'),
(667, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 20', '20', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:23:42', '2025-09-18 11:23:42'),
(668, 'Record updated On  fee groups fee type id 19', '19', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:03', '2025-09-18 11:24:03'),
(669, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 19', '19', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:03', '2025-09-18 11:24:03'),
(670, 'Record updated On  fee groups fee type id 21', '21', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:23', '2025-09-18 11:24:23'),
(671, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 21', '21', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:23', '2025-09-18 11:24:23'),
(672, 'Record updated On  fee groups fee type id 22', '22', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:36', '2025-09-18 11:24:36'),
(673, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 22', '22', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:36', '2025-09-18 11:24:36'),
(674, 'Record updated On  fee groups fee type id 23', '23', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:53', '2025-09-18 11:24:53'),
(675, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 23', '23', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:24:53', '2025-09-18 11:24:53'),
(676, 'Record updated On  fee groups fee type id 24', '24', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:25:06', '2025-09-18 11:25:06'),
(677, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 24', '24', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:25:06', '2025-09-18 11:25:06'),
(678, 'Record updated On  fee groups fee type id 25', '25', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:25:47', '2025-09-18 11:25:47'),
(679, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 25', '25', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:25:47', '2025-09-18 11:25:47'),
(680, 'Record updated On  fee groups fee type id 26', '26', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:13', '2025-09-18 11:26:13'),
(681, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 26', '26', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:13', '2025-09-18 11:26:13'),
(682, 'Record updated On  fee groups fee type id 27', '27', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:26', '2025-09-18 11:26:26'),
(683, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 27', '27', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:26', '2025-09-18 11:26:26'),
(684, 'Record updated On  fee groups fee type id 28', '28', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:40', '2025-09-18 11:26:40'),
(685, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 28', '28', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:40', '2025-09-18 11:26:40'),
(686, 'Record deleted On fee groups fee type id 32', '32', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:52', '2025-09-18 11:26:52'),
(687, 'Record deleted On Cumulative Fine id 32', '32', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:26:52', '2025-09-18 11:26:52'),
(688, 'Record updated On  fee groups fee type id 17', '17', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:27:21', '2025-09-18 11:27:21'),
(689, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 17', '17', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:27:21', '2025-09-18 11:27:21'),
(690, 'Record updated On  fee groups fee type id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:30:35', '2025-09-18 11:30:35'),
(691, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 1', '1', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:30:35', '2025-09-18 11:30:35'),
(692, 'Record updated On  fee groups fee type id 7', '7', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:00', '2025-09-18 11:31:00'),
(693, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 7', '7', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:00', '2025-09-18 11:31:00'),
(694, 'Record updated On  fee groups fee type id 13', '13', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:33', '2025-09-18 11:31:33'),
(695, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 13', '13', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:33', '2025-09-18 11:31:33'),
(696, 'Record updated On  fee groups fee type id 21', '21', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:51', '2025-09-18 11:31:51'),
(697, 'Record deleted On Cumulative Fine fee_groups_feetype_id id 21', '21', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:31:51', '2025-09-18 11:31:51'),
(698, 'New Record inserted On  fee type id 14', '14', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(699, 'New Record inserted On  fee type id 15', '15', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(700, 'New Record inserted On  fee type id 16', '16', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(701, 'New Record inserted On  fee type id 17', '17', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(702, 'New Record inserted On  fee type id 18', '18', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(703, 'New Record inserted On  fee type id 19', '19', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(704, 'New Record inserted On  fee type id 20', '20', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(705, 'New Record inserted On  fee type id 21', '21', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(706, 'New Record inserted On  fee type id 22', '22', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(707, 'New Record inserted On  fee type id 23', '23', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(708, 'New Record inserted On  fee type id 24', '24', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(709, 'New Record inserted On  fee group id 7', '7', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(710, 'New Record inserted On  fee groups feetype id 34', '34', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(711, 'New Record inserted On  fee groups feetype id 35', '35', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(712, 'New Record inserted On  fee groups feetype id 36', '36', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(713, 'New Record inserted On  fee groups feetype id 37', '37', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(714, 'New Record inserted On  fee groups feetype id 38', '38', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(715, 'New Record inserted On  fee groups feetype id 39', '39', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(716, 'New Record inserted On  fee groups feetype id 40', '40', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(717, 'New Record inserted On  fee groups feetype id 41', '41', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(718, 'New Record inserted On  fee groups feetype id 42', '42', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(719, 'New Record inserted On  fee groups feetype id 43', '43', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(720, 'New Record inserted On  fee groups feetype id 44', '44', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:44:21', '2025-09-18 11:44:21'),
(721, 'New Record inserted On  income head   id 2', '2', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:55:08', '2025-09-18 11:55:08'),
(722, 'Record deleted On  income head   id 2', '2', 1, 'Delete', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:55:17', '2025-09-18 11:55:17'),
(723, 'New Record inserted On  income head   id 3', '3', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:55:25', '2025-09-18 11:55:25'),
(724, 'Record updated On  income head   id 3', '3', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 13:55:34', '2025-09-18 11:55:34'),
(725, 'New Record inserted On  Income   id 1', '1', 1, 'Insert', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 14:01:14', '2025-09-18 12:01:14'),
(726, 'Record updated On  id card id 1', '1', 1, 'Update', '216.234.213.68', 'Windows 10', 'Chrome 140.0.0.0', '2025-09-18 14:09:39', '2025-09-18 12:09:39'),
(727, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '40.77.167.241', 'Unknown Platform', 'Bing', '2025-09-20 08:45:26', '2025-09-20 06:45:26'),
(728, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.55.81.190', 'Linux', 'Firefox 122.0', '2025-09-21 16:39:46', '2025-09-21 14:39:46'),
(729, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.55.81.190', 'Mac OS X', 'Firefox 122.0', '2025-09-21 16:39:46', '2025-09-21 14:39:46'),
(730, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.55.97.171', 'Windows 10', 'Firefox 122.0', '2025-09-21 17:24:24', '2025-09-21 15:24:24'),
(731, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.55.97.171', 'Android', 'Chrome 121.0.6167.101', '2025-09-21 17:24:24', '2025-09-21 15:24:24'),
(732, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '52.167.144.210', 'Unknown Platform', 'Bing', '2025-09-22 13:57:12', '2025-09-22 11:57:12'),
(733, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '34.234.200.207', 'Unknown Platform', 'Chrome 119.0.6045.214', '2025-09-22 14:19:06', '2025-09-22 12:19:06'),
(734, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '54.88.84.219', 'Unknown Platform', 'Chrome 119.0.6045.214', '2025-09-23 00:37:49', '2025-09-22 22:37:49'),
(735, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '176.31.139.22', 'Unknown Platform', 'Mozilla 5.0', '2025-09-24 00:27:48', '2025-09-23 22:27:48'),
(736, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '92.222.108.100', 'Unknown Platform', 'Mozilla 5.0', '2025-09-24 01:06:40', '2025-09-23 23:06:40'),
(737, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '216.73.216.61', 'Unknown Platform', 'Mozilla 5.0', '2025-09-26 15:37:03', '2025-09-26 13:37:03'),
(738, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '216.73.216.61', 'Unknown Platform', 'Mozilla 5.0', '2025-09-26 15:38:03', '2025-09-26 13:38:03'),
(739, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '69.160.160.55', 'Windows 10', 'Chrome 79.0.3945.130', '2025-09-28 18:05:01', '2025-09-28 16:05:01'),
(740, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '69.160.160.55', 'Windows 10', 'Chrome 79.0.3945.130', '2025-09-28 18:05:03', '2025-09-28 16:05:03'),
(741, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '47.82.11.19', 'Mac OS X', 'Chrome 134.0.0.0', '2025-09-29 07:04:41', '2025-09-29 05:04:41'),
(742, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '47.82.11.138', 'Windows 10', 'Chrome 135.0.0.0', '2025-09-29 08:41:37', '2025-09-29 06:41:37'),
(743, 'Record updated On staff id 1', '1', 1, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-10 10:55:51', '2025-10-10 13:55:51'),
(744, 'Record updated On staff id 1', '1', 1, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-11 16:09:21', '2025-10-11 19:09:21'),
(745, 'Record updated On staff id 1', '1', 1, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-11 16:09:26', '2025-10-11 19:09:26'),
(746, 'Record updated On staff id 1', '1', 1, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-11 17:34:07', '2025-10-11 20:34:07'),
(747, 'Record updated On  users id 1', '1', 0, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-16 15:08:15', '2025-10-16 18:08:15'),
(748, 'Record updated On  users id 1', '1', 0, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-16 15:08:18', '2025-10-16 18:08:18'),
(749, 'Record updated On  users id 1', '1', 0, 'Update', '::1', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-16 15:08:30', '2025-10-16 18:08:30'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 16:38:23', '2025-10-24 14:38:23'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 16:38:24', '2025-10-24 14:38:24'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 16:38:26', '2025-10-24 14:38:26'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 16:38:27', '2025-10-24 14:38:27'),
(0, 'Record updated On  users id 1', '1', 0, 'Update', '59.103.32.168', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-24 17:13:27', '2025-10-24 15:13:27'),
(0, 'Record updated On  users id 1', '1', 0, 'Update', '59.103.32.168', 'Windows 10', 'Chrome 141.0.0.0', '2025-10-24 17:14:04', '2025-10-24 15:14:04'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 17:31:54', '2025-10-24 15:31:54'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 17:32:06', '2025-10-24 15:32:06'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 17:33:17', '2025-10-24 15:33:17'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 17:33:44', '2025-10-24 15:33:44'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:304:200::3bc5', 'Windows 10', 'Spartan 12.246', '2025-10-24 19:31:04', '2025-10-24 17:31:04'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:304:200::3bc5', 'Windows 10', 'Spartan 12.246', '2025-10-24 19:31:05', '2025-10-24 17:31:05'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:304:200::3bc5', 'Windows 10', 'Spartan 12.246', '2025-10-24 19:31:06', '2025-10-24 17:31:06'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:304:200::3bc5', 'Windows 10', 'Spartan 12.246', '2025-10-24 19:31:08', '2025-10-24 17:31:08'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:304:200::3bc5', 'iOS', 'Safari 604', '2025-10-24 20:10:30', '2025-10-24 18:10:30'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:304:200::3bc5', 'iOS', 'Safari 604', '2025-10-24 20:10:58', '2025-10-24 18:10:58'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:304:200::3bc5', 'iOS', 'Safari 604', '2025-10-24 20:12:08', '2025-10-24 18:12:08'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:304:200::3bc5', 'iOS', 'Safari 604', '2025-10-24 20:13:07', '2025-10-24 18:13:07'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.129.15.32', 'Windows 10', 'Spartan 12.246', '2025-10-24 21:44:57', '2025-10-24 19:44:57'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.129.15.32', 'Windows 10', 'Spartan 12.246', '2025-10-24 21:44:58', '2025-10-24 19:44:58'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.129.15.32', 'Windows 10', 'Spartan 12.246', '2025-10-24 21:45:00', '2025-10-24 19:45:00'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.129.15.32', 'Windows 10', 'Spartan 12.246', '2025-10-24 21:45:01', '2025-10-24 19:45:01'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.129.15.32', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 22:44:11', '2025-10-24 20:44:11'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.129.15.32', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 22:44:17', '2025-10-24 20:44:17'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.129.15.32', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 22:47:40', '2025-10-24 20:47:40'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.129.15.32', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 22:54:08', '2025-10-24 20:54:08'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 23:12:49', '2025-10-24 21:12:49'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 23:12:50', '2025-10-24 21:12:50'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 23:12:52', '2025-10-24 21:12:52'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 10', 'Spartan 12.246', '2025-10-24 23:12:53', '2025-10-24 21:12:53'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 23:34:52', '2025-10-24 21:34:52'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 23:35:55', '2025-10-24 21:35:55'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 23:37:45', '2025-10-24 21:37:45'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '162.19.25.136', 'Windows 7', 'Chrome 21.0.1180.83', '2025-10-24 23:39:22', '2025-10-24 21:39:22'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Spartan 12.246', '2025-10-25 09:23:55', '2025-10-25 07:23:55'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Spartan 12.246', '2025-10-25 09:23:55', '2025-10-25 07:23:55'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Spartan 12.246', '2025-10-25 09:23:56', '2025-10-25 07:23:56'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Spartan 12.246', '2025-10-25 09:23:56', '2025-10-25 07:23:56'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '217.182.76.73', 'Windows 10', 'Spartan 12.246', '2025-10-25 10:10:15', '2025-10-25 08:10:15'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '217.182.76.73', 'Windows 10', 'Spartan 12.246', '2025-10-25 10:10:15', '2025-10-25 08:10:15'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '217.182.76.73', 'Windows 10', 'Spartan 12.246', '2025-10-25 10:10:18', '2025-10-25 08:10:18'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '217.182.76.73', 'Windows 10', 'Spartan 12.246', '2025-10-25 10:10:18', '2025-10-25 08:10:18'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Chrome 74.0.3729.169', '2025-10-25 10:13:46', '2025-10-25 08:13:46'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2402:1f00:8000:800::35f2', 'Windows 10', 'Chrome 74.0.3729.169', '2025-10-25 10:14:55', '2025-10-25 08:14:55'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '217.182.76.73', 'Windows 10', 'Chrome 101.0.4951.54', '2025-10-25 10:45:49', '2025-10-25 08:45:49'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '217.182.76.73', 'Windows 10', 'Chrome 101.0.4951.54', '2025-10-25 10:46:05', '2025-10-25 08:46:05'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Spartan 12.246', '2025-10-27 02:13:09', '2025-10-27 00:13:09'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Spartan 12.246', '2025-10-27 02:13:10', '2025-10-27 00:13:10'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Spartan 12.246', '2025-10-27 02:13:12', '2025-10-27 00:13:12'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Spartan 12.246', '2025-10-27 02:13:13', '2025-10-27 00:13:13'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Chrome 86.0.4240.198', '2025-10-27 02:28:38', '2025-10-27 00:28:38'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Chrome 86.0.4240.198', '2025-10-27 02:28:58', '2025-10-27 00:28:58'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Chrome 86.0.4240.198', '2025-10-27 02:29:21', '2025-10-27 00:29:21'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '2001:41d0:701:1100::52d6', 'Windows 10', 'Chrome 86.0.4240.198', '2025-10-27 02:29:23', '2025-10-27 00:29:23'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.128.173.201', 'Windows 10', 'Spartan 12.246', '2025-10-28 01:42:12', '2025-10-27 23:42:12'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.128.173.201', 'Windows 10', 'Spartan 12.246', '2025-10-28 01:42:13', '2025-10-27 23:42:13'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.128.173.201', 'Windows 10', 'Spartan 12.246', '2025-10-28 01:42:15', '2025-10-27 23:42:15'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.128.173.201', 'Windows 10', 'Spartan 12.246', '2025-10-28 01:42:15', '2025-10-27 23:42:15'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.128.173.201', 'Android', 'Chrome 119.0.0.0', '2025-10-28 01:56:42', '2025-10-27 23:56:42'),
(0, 'Record updated On online c1ourses id 2', '2', 0, 'Update', '57.128.173.201', 'Android', 'Chrome 119.0.0.0', '2025-10-28 01:56:44', '2025-10-27 23:56:44'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.128.173.201', 'Android', 'Chrome 119.0.0.0', '2025-10-28 01:57:01', '2025-10-27 23:57:01'),
(0, 'Record updated On online c1ourses id 1', '1', 0, 'Update', '57.128.173.201', 'Android', 'Chrome 119.0.0.0', '2025-10-28 01:57:14', '2025-10-27 23:57:14'),
(0, 'New Record inserted On  Complain id 0', '0', 0, 'Insert', '149.34.243.66', 'Windows 10', 'Chrome 131.0.0.0', '2025-10-31 14:04:44', '2025-10-31 12:04:44'),
(0, 'Record updated On Menu id 11', '11', 1, 'Update', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Windows 10', 'Chrome 141.0.0.0', '2025-11-03 12:28:49', '2025-11-03 10:28:49'),
(0, 'Record deleted On menu id 19', '19', 1, 'Delete', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Windows 10', 'Chrome 141.0.0.0', '2025-11-03 12:36:29', '2025-11-03 10:36:29'),
(0, 'New Record inserted On event id 0', '0', 1, 'Insert', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Windows 10', 'Chrome 141.0.0.0', '2025-11-03 12:45:58', '2025-11-03 10:45:58'),
(0, 'Record updated On  event id 0', '0', 1, 'Update', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Windows 10', 'Chrome 141.0.0.0', '2025-11-03 12:46:09', '2025-11-03 10:46:09');

-- --------------------------------------------------------

--
-- Table structure for table `mark_divisions`
--

CREATE TABLE `mark_divisions` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `percentage_from` float(10,2) DEFAULT NULL,
  `percentage_to` float(10,2) DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `template_id` varchar(100) DEFAULT NULL,
  `email_template_id` int(11) DEFAULT NULL,
  `sms_template_id` int(11) DEFAULT NULL,
  `send_through` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `send_mail` varchar(10) DEFAULT '0',
  `send_sms` varchar(10) DEFAULT '0',
  `is_group` varchar(10) DEFAULT '0',
  `is_individual` varchar(10) DEFAULT '0',
  `is_class` int(11) NOT NULL DEFAULT 0,
  `is_schedule` int(11) NOT NULL,
  `sent` int(11) DEFAULT NULL,
  `schedule_date_time` datetime DEFAULT NULL,
  `group_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `send_to` varchar(255) DEFAULT NULL,
  `schedule_class` int(11) DEFAULT NULL,
  `schedule_section` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `multi_branch`
--

CREATE TABLE `multi_branch` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(200) DEFAULT NULL,
  `branch_url` varchar(500) NOT NULL,
  `hostname` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `database_name` varchar(200) DEFAULT NULL,
  `directory_path` varchar(500) DEFAULT NULL,
  `is_verified` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_roles`
--

CREATE TABLE `notification_roles` (
  `id` int(11) NOT NULL,
  `send_notification_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_setting`
--

CREATE TABLE `notification_setting` (
  `id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `is_mail` varchar(10) DEFAULT '0',
  `is_sms` varchar(10) DEFAULT '0',
  `is_notification` int(11) NOT NULL DEFAULT 0,
  `display_notification` int(11) NOT NULL DEFAULT 0,
  `display_sms` int(11) NOT NULL DEFAULT 1,
  `is_student_recipient` int(11) DEFAULT NULL,
  `is_guardian_recipient` int(11) DEFAULT NULL,
  `is_staff_recipient` int(11) DEFAULT NULL,
  `display_student_recipient` int(11) DEFAULT NULL,
  `display_guardian_recipient` int(11) DEFAULT NULL,
  `display_staff_recipient` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `template_id` varchar(100) NOT NULL,
  `template` longtext NOT NULL,
  `variables` text NOT NULL,
  `notification_order` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `notification_setting`
--

INSERT INTO `notification_setting` (`id`, `type`, `is_mail`, `is_sms`, `is_notification`, `display_notification`, `display_sms`, `is_student_recipient`, `is_guardian_recipient`, `is_staff_recipient`, `display_student_recipient`, `display_guardian_recipient`, `display_staff_recipient`, `subject`, `template_id`, `template`, `variables`, `notification_order`, `created_at`, `updated_at`) VALUES
(1, 'student_admission', '1', '0', 0, 0, 1, 1, 1, NULL, 1, 1, NULL, 'Student Admission', '', 'Dear {{student_name}} your admission is confirm in Class: {{class}} Section:  {{section}} for Session: {{current_session_name}} for more \r\ndetail\r\n contact\r\n System\r\n Admin\r\n {{class}} {{section}} {{admission_no}} {{roll_no}} {{admission_date}} {{mobileno}} {{email}} {{dob}} {{guardian_name}} {{guardian_relation}} {{guardian_phone}} {{father_name}} {{father_phone}} {{blood_group}} {{mother_name}} {{gender}} {{guardian_email}}', '{{student_name}} {{class}}  {{section}}  {{admission_no}}  {{roll_no}}  {{admission_date}}   {{mobileno}}  {{email}}  {{dob}}  {{guardian_name}}  {{guardian_relation}}  {{guardian_phone}}  {{father_name}}  {{father_phone}}  {{blood_group}}  {{mother_name}}  {{gender}} {{guardian_email}} {{current_session_name}} ', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(2, 'exam_result', '1', '0', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Exam Result', '', 'Dear {{student_name}} - {{exam_roll_no}}, your {{exam}} result has been published.', '{{student_name}} {{exam_roll_no}} {{exam}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(3, 'fee_submission', '1', '0', 0, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Fee Submission', '', 'Dear parents, we have received Fees Amount {{fee_amount}} for  {{student_name}}  by Your School Name \r\n{{class}} {{section}} {{fine_type}} {{fine_percentage}} {{fine_amount}} {{fee_group_name}} {{type}} {{code}} {{email}} {{contact_no}} {{invoice_id}} {{sub_invoice_id}} {{due_date}} {{amount}} {{fee_amount}}', '{{student_name}} {{class}} {{section}} {{fine_type}} {{fine_percentage}} {{fine_amount}} {{fee_group_name}} {{type}} {{code}} {{email}} {{contact_no}} {{invoice_id}} {{sub_invoice_id}} {{due_date}} {{amount}} {{fee_amount}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(4, 'student_absent_attendence', '1', '0', 0, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Absent Attendence', '', 'Absent Notice :{{student_name}}  was absent on date {{date}} in period {{subject_name}} {{subject_code}} {{subject_type}} from Your School Name', '{{student_name}} {{mobileno}} {{email}} {{father_name}} {{father_phone}} {{father_occupation}} {{mother_name}} {{mother_phone}} {{guardian_name}} {{guardian_phone}} {{guardian_occupation}} {{guardian_email}} {{date}} {{current_session_name}} {{time_from}} {{time_to}} {{subject_name}} {{subject_code}} {{subject_type}}  ', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(6, 'homework', '1', '0', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Homework', '', 'New Homework has been created for \r\n{{student_name}} at\r\n\r\n\r\n\r\n{{homework_date}} for the class {{class}} {{section}} {{subject}}. kindly submit your\r\n\r\n\r\n homework before {{submit_date}} .Thank you', '{{homework_date}} {{submit_date}} {{class}} {{section}} {{subject}} {{student_name}} {{admission_no}} ', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(7, 'fees_reminder', '1', '0', 0, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Fees Reminder', '', 'Dear parents, please pay fee amount Rs.{{due_amount}} of {{fee_type}} before {{due_date}} for {{student_name}}  from smart school (ignore if you already paid)', '{{fee_type}}{{fee_code}}{{due_date}}{{student_name}}{{school_name}}{{fee_amount}}{{due_amount}}{{deposit_amount}} ', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(8, 'forgot_password', '1', '0', 0, 0, 0, 1, 1, 1, 1, 1, 1, 'Forgot Password', '', 'Dear  {{name}} , \r\n    Recently a request was submitted to reset password for your account. If you didn\'t make the request, just ignore this email. Otherwise you can reset your password using this link <a href=\'{{resetPassLink}}\'>Click here to reset your password</a>,\r\nif you\'re having trouble clicking the password reset button, copy and paste the URL below into your web browser. your username {{username}}\r\n{{resetPassLink}}\r\n Regards,\r\n {{school_name}}', '{{school_name}}{{name}}{{username}}{{resetPassLink}} ', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(9, 'online_examination_publish_exam', '1', '0', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Online Examination Publish Exam', '', 'A new exam {{exam_title}} has been created for  duration: {{time_duration}} min, which will be available from:  {{exam_from}} to  {{exam_to}}.', '{{exam_title}} {{exam_from}} {{exam_to}} {{time_duration}} {{attempt}} {{passing_percentage}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(10, 'online_examination_publish_result', '1', '0', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Online Examination Publish Result', '', 'Exam {{exam_title}} result has been declared which was conducted between  {{exam_from}} to   {{exam_to}}, for more details, please check your student portal.', '{{exam_title}} {{exam_from}} {{exam_to}} {{time_duration}} {{attempt}} {{passing_percentage}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(11, 'online_admission_form_submission', '1', '0', 0, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Online Admission Form Submission', '', 'Dear {{firstname}}  {{lastname}} your online admission form is Submitted successfully  on date {{date}}. Your Reference number is {{reference_no}}. Please remember your reference number for further process.', ' {{firstname}} {{lastname}} {{date}} {{reference_no}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(12, 'online_admission_fees_submission', '0', '0', 0, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Online Admission Fees Submission', '', 'Dear {{firstname}}  {{lastname}} your online admission form is Submitted successfully and the payment of {{paid_amount}} has recieved successfully on date {{date}}. Your Reference number is {{reference_no}}. Please remember your reference number for further process.', ' {{firstname}} {{lastname}} {{date}} {{paid_amount}} {{reference_no}}', NULL, '2022-12-28 09:52:24', '2025-05-10 12:46:06'),
(13, 'student_login_credential', '1', '1', 0, 0, 1, 1, 1, 0, 1, 1, NULL, 'Student Login Credential', '1707163291685208209', 'Hello {{display_name}} your login details for Url: {{url}} Username: {{username}}  Password: {{password}} admission No: {{admission_no}}', '{{url}} {{display_name}} {{username}} {{password}} {{admission_no}}', NULL, '2022-08-06 05:34:41', '2025-05-10 12:46:06'),
(14, 'staff_login_credential', '1', '1', 0, 0, 1, 0, 0, 1, NULL, NULL, 1, 'Staff Login Credential', '1707163291685208209', 'Hello {{first_name}} {{last_name}} your login details for Url: {{url}} Username: {{username}}  Password: {{password}} Employee ID: {{employee_id}}', '{{url}} {{first_name}} {{last_name}} {{username}} {{password}} {{employee_id}}', NULL, '2021-12-23 11:59:13', '2025-05-10 12:46:06'),
(15, 'fee_processing', '1', '1', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Fee processing', '1707163291301326898', 'Dear parents, we have received Fees Amount {{fee_amount}} for  {{student_name}}  by Your School Name \r\n{{class}} {{section}} {{email}} {{contact_no}}\r\n\r\n{{student_name}} {{class}} {{section}} {{email}} {{contact_no}} transaction_id :{{transaction_id}} {{fee_amount}}', '{{student_name}} {{class}} {{section}} {{email}} {{contact_no}} {{transaction_id}} {{fee_amount}}', NULL, '2021-12-22 10:15:42', '2025-05-10 12:46:06'),
(16, 'online_admission_fees_processing', '1', '1', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Online Admission Fees Processing', '', 'Dear {{firstname}}  {{lastname}} your online admission form is Submitted successfully and the payment of {{paid_amount}} has processing on date {{date}}. Your Reference number is {{reference_no}} and your transaction id {{transaction_id}}. Please remember your reference number for further process.', ' {{firstname}} {{lastname}} {{date}} {{paid_amount}} {{reference_no}} {{transaction_id}}', NULL, '2022-08-06 11:09:47', '2025-05-10 12:46:06'),
(17, 'student_apply_leave', '1', '1', 0, 0, 1, 0, 1, 1, NULL, 1, 1, 'Student Apply Leave ( {{student_name}} - {{admission_no}} )', '', 'My Name is {{student_name}} Class {{class}} section {{section}}. I have to apply leave on {{apply_date}}and from {{from_date}} to {{to_date}}. {{message}} please provide.', '{{message}} {{apply_date}} {{from_date}} {{to_date}} {{student_name}} {{class}} {{section}}', NULL, '2022-03-12 11:58:37', '2025-05-10 12:46:06'),
(18, 'email_pdf_exam_marksheet', '1', '0', 0, 0, 0, 1, 1, 0, 1, 1, NULL, 'Email PDF Exam Marksheet ( {{student_name}} - {{admission_no}} )', '', 'Dear {{student_name}} ({{admission_no}}) {{class}} Section {{section}}. We have mailed you the marksheet of Exam {{exam}} Roll no.{{roll_no}}', '{{student_name}} {{class}}  {{section}}  {{admission_no}}  {{roll_no}} {{exam}} {{admit_card_roll_no}} ', NULL, '2022-03-12 12:24:42', '2025-05-10 12:46:06'),
(19, 'homework_evaluation', '1', '0', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Homework Evaluation', '', 'Homework Evaluation\r\nHomework Assign Date:  {{homework_date}}  \r\nLast Submit Date: {{submit_date}}\r\nStudent Name: {{student_name}} .\r\nAdmission No {{admission_no}}\r\n{{class}}\r\nsection: {{section}}\r\nsubject : {{subject}} \r\nMarks: {{marks}}/{{max_marks}}\r\nDate:{{evaluation_date}}\r\nThank you', '{{homework_date}} {{submit_date}} {{class}} {{section}} {{subject}} {{student_name}} {{admission_no}} {{max_marks}} {{marks}} {{evaluation_date}}', 120, '2025-01-15 08:00:34', '2025-05-10 12:46:06'),
(20, 'student_present_attendence', '1', '0', 0, 1, 1, 1, 1, 0, 1, 1, NULL, 'Present Attendence', '', 'Present Notice :{{student_name}} {{admission_no}} was present on date {{date}} in in_time {{in_time}} period\r\nsubject-{{subject_name}} <br> \r\nsubject_code - {{subject_code}} <br> \r\nsubject_type-{{subject_type}} <br>\r\nperiod_time_from-  {{period_time_from}}  <br>\r\nperiod_time_to-  {{period_time_to}}  <br>\r\nfrom Your School Name or more detail contact System Admin  <br>\r\nmobile no - {{mobileno}} <br>\r\nemail -  {{email}}<br>\r\nfather name -  {{father_name}} <br>\r\nfather phone - {{father_phone}}<br>\r\nfather occupation -  {{father_occupation}}<br>\r\nmother name -  {{mother_name}} <br>\r\nmother phone - {{mother_phone}}<br>\r\nguardian name -  {{guardian_name}}<br>\r\nguardian phone -  {{guardian_phone}} <br>\r\nguardian occupation - {{guardian_occupation}}<br>\r\nguardian email - {{guardian_email}}<br>', '{{student_name}} {{mobileno}} {{email}} {{father_name}} {{father_phone}} {{father_occupation}} {{mother_name}} {{mother_phone}} {{guardian_name}} {{guardian_phone}} {{guardian_occupation}} {{guardian_email}} {{date}} {{in_time}}  ({{admission_no}}) {{period_time_from}} {{period_time_to}} {{subject_name}} {{subject_code}} {{subject_type}}', 15, '2025-01-13 05:55:46', '2025-05-10 12:46:06'),
(21, 'staff_present_attendence', '1', '0', 0, 1, 1, 0, 0, 1, NULL, NULL, 1, 'staff Present Attendence', '', 'Present Notice: Staff Name {{staff_name}} ({{employee_id}}) is Present on Date : {{date}} at Time : {{in_time}}\r\n<br>\r\nstaff contact no:{{contact_no}}\r\n<br>\r\nstaff mail id : {{email}}', '{{date}} {{in_time}} {{staff_name}} {{employee_id}} {{contact_no}} {{email}}\n', 190, '2025-02-07 11:43:28', '2025-05-10 12:46:06'),
(22, 'staff_absent_attendence', '1', '0', 0, 1, 1, 0, 0, 1, NULL, NULL, 1, 'staff Absent Attendence', '', 'Absent Notice: Staff Name {{staff_name}} ({{employee_id}}) is Absent on Date : {{date}} \r\n<br>\r\nstaff contact no:{{contact_no}}\r\n<br>\r\nstaff mail id : {{email}}', '{{date}} {{staff_name}} {{employee_id}} {{contact_no}} {{email}}\n', 200, '2025-02-07 11:43:28', '2025-05-10 12:46:06'),
(23, 'online_classes', '1', '1', 1, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Zoom Online Classes', '', 'Dear student, your live class {{title}} has been scheduled on {{date}} for the duration of {{duration}} minute, please do not share the link to any body.', '{{title}} {{date}} {{duration}}', NULL, '2022-07-13 08:01:48', '2025-05-22 11:46:33'),
(24, 'online_meeting', '1', '1', 0, 0, 1, NULL, NULL, 1, NULL, NULL, 1, 'Zoom Online Meeting', '', 'Dear staff, your live meeting {{title}} has been scheduled on {{date}} for the duration of {{duration}} minute, please do not share the link to any body.', '{{title}} {{date}} {{duration}} {{employee_id}} {{department}} {{designation}} {{name}} {{contact_no}} {{email}}', NULL, '2022-07-13 07:46:54', '2025-05-22 11:46:33'),
(25, 'zoom_online_classes_start', '1', '1', 1, 1, 1, 1, 1, NULL, 1, 1, NULL, 'Zoom Online  Classes Start', '', 'Dear student, your live class {{title}} has been started  for the duration of {{duration}} minute.', '{{title}} {{date}} {{duration}}', NULL, '2022-07-13 08:04:02', '2025-05-22 11:46:33'),
(26, 'zoom_online_meeting_start', '1', '1', 0, 0, 1, NULL, NULL, 1, NULL, NULL, 1, 'Zoom Live Meeting Start', '', 'Dear {{name}},  your live meeting {{title}}  has been started  for the duration of {{duration}} minute.', '{{title}} {{date}} {{duration}} {{employee_id}} {{department}} {{designation}} {{name}} {{contact_no}} {{email}}', NULL, '2022-07-11 07:39:06', '2025-05-22 11:46:33'),
(27, 'gmeet_online_classes', '1', '1', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Gmeet Live Classes', '', 'Dear student, your live class {{title}} has been scheduled on {{date}} for the duration of {{duration}} minute, please do not share the link to any body.', '{{title}} {{date}} {{duration}}', NULL, '2022-07-13 05:25:28', '2025-05-22 11:46:36'),
(28, 'gmeet_online_meeting', '1', '1', 0, 0, 1, 0, 0, 1, NULL, NULL, 1, 'Gmeet Live Meeting', '', 'Dear staff, your live meeting {{title}} has been scheduled on {{date}} for the duration of {{duration}} minute, please do not share the link to any body.', '{{title}} {{date}} {{duration}} {{employee_id}} {{department}} {{designation}} {{name}} {{contact_no}} {{email}}', NULL, '2022-07-13 05:25:28', '2025-05-22 11:46:36'),
(29, 'gmeet_online_classes_start', '1', '1', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Gmeet  Live Classes Start', '', 'Dear student, your live class {{title}} has been started  for the duration of {{duration}} minute.', '{{title}} {{date}} {{duration}}', NULL, '2022-07-13 05:25:28', '2025-05-22 11:46:36'),
(30, 'gmeet_online_meeting_start', '1', '1', 0, 0, 1, 0, 0, 1, NULL, NULL, 1, 'Gmeet Live Meeting Start', '', 'Dear {{name}},  your live meeting {{title}} has been started  for the duration of {{duration}} minute.', '{{title}} {{date}} {{duration}} {{employee_id}} {{department}} {{designation}} {{name}} {{contact_no}} {{email}}', NULL, '2022-07-13 05:25:28', '2025-05-22 11:46:36'),
(31, 'online_course_publish', '1', '1', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Online Course Publish', '', 'Dear student, a new online course {{title}} and price {{price}} with discount {{discount}}% for {{class}} {{section}} is {{paid_free}} now available and assign to {{assign_teacher}}.', '{{title}} {{class}} {{section}} {{price}} {{discount}} {{paid_free}} {{assign_teacher}}\r\n ', NULL, '2022-11-15 06:09:13', '2025-05-22 11:46:41'),
(32, 'online_course_purchase', '1', '1', 0, 1, 1, 1, NULL, NULL, 1, 1, NULL, 'Online Course Purchase', '', 'Thanks for purchasing course {{title}} amount {{price}} purchase date {{purchase_date}} class {{class}} section {{section}} and assign for {{assign_teacher}}  discount {{discount}}%', '{{title}} {{class}} {{section}} {{price}} {{discount}}  \r\n{{purchase_date}}', NULL, '2022-11-19 10:09:29', '2025-05-22 11:46:41'),
(33, 'online_course_purchase_for_guest_user', '1', '1', 0, 0, 1, 1, NULL, NULL, 1, NULL, NULL, 'Online Course Purchase For Guest', '', 'Thanks for purchasing course {{title}} discount {{discount}} amount {{price}} purchase date {{purchase_date}}', '{{title}} {{price}} {{discount}} {{purchase_date}}', NULL, '2022-07-15 05:53:52', '2025-05-22 11:46:41'),
(34, 'online_course_guest_user_sign_up', '1', '0', 0, 0, 0, 1, NULL, NULL, 1, NULL, NULL, 'Online Course Guest User Sign Up', '', 'Dear {{guest_user_name}} you have successfully sign up with Email: {{email}} Url {{url}}', '{{guest_user_name}} {{email}} {{url}}', NULL, '2022-07-15 05:56:31', '2025-05-22 11:46:41'),
(35, 'behaviour_incident_assigned', '1', '1', 1, 1, 1, 1, 1, 0, 1, 1, NULL, 'Behaviour Incident Assigned', '', 'A new {{incident_title}}  behaviour incident with {{incident_point}} point is assigned on you. {{student_name}} {{class}} {{section}} {{admission_no}} {{mobileno}} {{email}} {{guardian_name}} {{guardian_phone}} {{guardian_email}}', '{{incident_title}} {{incident_point}} {{student_name}} {{class}} {{section}} {{admission_no}} {{mobileno}} {{email}} {{guardian_name}} {{guardian_phone}} {{guardian_email}}', NULL, '2023-01-02 06:35:44', '2025-05-22 11:46:52'),
(36, 'cbse_email_pdf_exam_marksheet', '1', '1', 1, 1, 1, 1, 1, NULL, 1, 1, NULL, 'CBSE Exam Marksheet PDF ( {{student_name}} - {{admission_no}} )', '', 'Dear {{student_name}} ({{admission_no}}) {{class}} Section {{section}}. We have mailed you the marksheet with Roll no.{{roll_no}}', '{{student_name}} {{class}} {{section}} {{admission_no}} {{roll_no}}', NULL, '2023-06-21 07:59:44', '2025-05-22 11:47:06'),
(37, 'cbse_exam_result', '1', '1', 1, 1, 1, 1, 1, NULL, 1, 1, NULL, 'CBSE Exam Result', '', 'Dear {{student_name}} - {{roll_no}}, your {{exam}} result has been published.', '{{student_name}} {{roll_no}} {{exam}}', NULL, '2023-06-21 07:59:47', '2025-05-22 11:47:06');

-- --------------------------------------------------------

--
-- Table structure for table `offline_fees_payments`
--

CREATE TABLE `offline_fees_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(50) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `student_fees_master_id` int(11) DEFAULT NULL,
  `fee_groups_feetype_id` int(11) DEFAULT NULL,
  `student_transport_fee_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `bank_from` varchar(200) DEFAULT NULL,
  `bank_account_transferred` varchar(200) DEFAULT NULL,
  `reference` varchar(200) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `submit_date` datetime DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `is_active` varchar(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlineexam`
--

CREATE TABLE `onlineexam` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `exam` text DEFAULT NULL,
  `attempt` int(11) NOT NULL,
  `exam_from` datetime DEFAULT NULL,
  `exam_to` datetime DEFAULT NULL,
  `is_quiz` int(11) NOT NULL DEFAULT 0,
  `auto_publish_date` datetime DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `duration` time NOT NULL,
  `passing_percentage` float NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `publish_result` int(11) NOT NULL DEFAULT 0,
  `answer_word_count` int(11) NOT NULL DEFAULT -1,
  `is_active` varchar(1) DEFAULT '0',
  `is_marks_display` int(11) NOT NULL DEFAULT 0,
  `is_neg_marking` int(11) NOT NULL DEFAULT 0,
  `is_random_question` int(11) NOT NULL DEFAULT 0,
  `is_rank_generated` int(11) NOT NULL DEFAULT 0,
  `publish_exam_notification` int(11) NOT NULL,
  `publish_result_notification` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlineexam_attempts`
--

CREATE TABLE `onlineexam_attempts` (
  `id` int(11) NOT NULL,
  `onlineexam_student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlineexam_questions`
--

CREATE TABLE `onlineexam_questions` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `onlineexam_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `marks` float(10,2) NOT NULL DEFAULT 0.00,
  `neg_marks` float(10,2) DEFAULT 0.00,
  `is_active` varchar(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlineexam_students`
--

CREATE TABLE `onlineexam_students` (
  `id` int(11) NOT NULL,
  `onlineexam_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `is_attempted` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) DEFAULT 0,
  `quiz_attempted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlineexam_student_results`
--

CREATE TABLE `onlineexam_student_results` (
  `id` int(11) NOT NULL,
  `onlineexam_student_id` int(11) NOT NULL,
  `onlineexam_question_id` int(11) NOT NULL,
  `select_option` longtext DEFAULT NULL,
  `marks` float(10,2) NOT NULL DEFAULT 0.00,
  `remark` text DEFAULT NULL,
  `attachment_name` text DEFAULT NULL,
  `attachment_upload_name` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_admissions`
--

CREATE TABLE `online_admissions` (
  `id` int(11) NOT NULL,
  `admission_no` varchar(100) DEFAULT NULL,
  `roll_no` varchar(100) DEFAULT NULL,
  `reference_no` varchar(50) NOT NULL,
  `admission_date` date DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `rte` varchar(20) NOT NULL DEFAULT 'No',
  `image` varchar(255) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `cast` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `route_id` int(11) NOT NULL,
  `school_house_id` int(11) DEFAULT NULL,
  `blood_group` varchar(200) NOT NULL,
  `vehroute_id` int(11) NOT NULL,
  `hostel_room_id` int(11) DEFAULT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `samagra_id` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `guardian_is` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(150) NOT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_email` varchar(100) NOT NULL,
  `father_pic` varchar(255) NOT NULL,
  `mother_pic` varchar(255) NOT NULL,
  `guardian_pic` varchar(255) NOT NULL,
  `is_enroll` int(11) DEFAULT 0,
  `previous_school` text DEFAULT NULL,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `form_status` int(11) NOT NULL,
  `paid_status` int(11) NOT NULL,
  `measurement_date` date DEFAULT NULL,
  `app_key` text DEFAULT NULL,
  `document` text DEFAULT NULL,
  `submit_date` date DEFAULT NULL,
  `disable_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_admissions`
--

INSERT INTO `online_admissions` (`id`, `admission_no`, `roll_no`, `reference_no`, `admission_date`, `firstname`, `middlename`, `lastname`, `rte`, `image`, `mobileno`, `email`, `state`, `city`, `pincode`, `religion`, `cast`, `dob`, `gender`, `current_address`, `permanent_address`, `category_id`, `class_section_id`, `route_id`, `school_house_id`, `blood_group`, `vehroute_id`, `hostel_room_id`, `adhar_no`, `samagra_id`, `bank_account_no`, `bank_name`, `ifsc_code`, `guardian_is`, `father_name`, `father_phone`, `father_occupation`, `mother_name`, `mother_phone`, `mother_occupation`, `guardian_name`, `guardian_relation`, `guardian_phone`, `guardian_occupation`, `guardian_address`, `guardian_email`, `father_pic`, `mother_pic`, `guardian_pic`, `is_enroll`, `previous_school`, `height`, `weight`, `note`, `form_status`, `paid_status`, `measurement_date`, `app_key`, `document`, `submit_date`, `disable_at`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, '590877', NULL, 'Kudakwashe', '', 'Kamudyariwa', 'No', NULL, '0969731477', 'kkamudyariwa@gmail.com', NULL, NULL, NULL, NULL, '', '1985-10-31', 'Male', NULL, NULL, NULL, 3, 0, NULL, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', '', '', 0, NULL, '', '', '', 1, 0, NULL, NULL, NULL, '2025-07-15', NULL, '2025-07-15 13:08:58', '2025-07-15 13:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `online_admission_custom_field_value`
--

CREATE TABLE `online_admission_custom_field_value` (
  `id` int(11) NOT NULL,
  `belong_table_id` int(11) DEFAULT NULL,
  `custom_field_id` int(11) DEFAULT NULL,
  `field_value` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_admission_fields`
--

CREATE TABLE `online_admission_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_admission_fields`
--

INSERT INTO `online_admission_fields` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'middlename', 0, '2021-05-28 10:29:23', '2025-05-10 12:46:07'),
(2, 'lastname', 1, '2021-06-02 04:49:19', '2025-05-10 12:46:07'),
(3, 'category', 0, '2021-06-02 04:48:35', '2025-07-15 13:06:30'),
(4, 'religion', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(5, 'cast', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(6, 'mobile_no', 1, '2021-06-02 04:50:24', '2025-05-10 12:46:07'),
(7, 'admission_date', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(8, 'student_photo', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(9, 'is_student_house', 0, '2021-05-29 13:22:53', '2025-05-10 12:46:07'),
(10, 'is_blood_group', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(11, 'student_height', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(12, 'student_weight', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(13, 'father_name', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(14, 'father_phone', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(15, 'father_occupation', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(16, 'father_pic', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(17, 'mother_name', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(18, 'mother_phone', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(19, 'mother_occupation', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(20, 'mother_pic', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(21, 'guardian_name', 0, '2021-06-02 04:50:54', '2025-07-15 13:05:19'),
(22, 'guardian_phone', 0, '2021-06-02 04:50:54', '2025-07-15 13:05:19'),
(23, 'if_guardian_is', 0, '2021-06-02 04:50:54', '2025-07-15 13:05:19'),
(24, 'guardian_relation', 0, '2021-06-02 04:50:54', '2025-07-15 13:05:19'),
(25, 'guardian_email', 0, '2021-06-02 04:51:35', '2025-07-15 13:05:19'),
(26, 'guardian_occupation', 0, '2021-06-02 04:51:26', '2025-07-15 13:05:19'),
(27, 'guardian_address', 0, '2021-06-02 04:51:31', '2025-07-15 13:05:19'),
(28, 'bank_account_no', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(29, 'bank_name', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(30, 'ifsc_code', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(31, 'national_identification_no', 1, '2021-06-02 04:48:35', '2025-09-18 09:38:58'),
(32, 'local_identification_no', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(33, 'rte', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(34, 'previous_school_details', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(35, 'guardian_photo', 0, '2021-06-02 04:51:29', '2025-07-15 13:05:19'),
(36, 'student_note', 0, '2021-06-02 04:55:08', '2025-05-10 12:46:07'),
(37, 'measurement_date', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(38, 'student_email', 1, '2021-06-02 04:49:38', '2025-05-10 12:46:07'),
(39, 'current_address', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(40, 'permanent_address', 0, '2021-06-02 04:48:35', '2025-05-10 12:46:07'),
(41, 'upload_documents', 1, '2022-09-20 08:00:32', '2025-05-10 12:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `online_admission_payment`
--

CREATE TABLE `online_admission_payment` (
  `id` int(11) NOT NULL,
  `online_admission_id` int(11) NOT NULL,
  `paid_amount` float(10,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `note` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `processing_charge_type` varchar(255) DEFAULT NULL,
  `processing_charge_value` float(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_courses`
--

CREATE TABLE `online_courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `outcomes` text DEFAULT NULL,
  `course_thumbnail` varchar(100) DEFAULT NULL,
  `course_provider` varchar(100) DEFAULT NULL,
  `course_url` varchar(255) DEFAULT NULL,
  `video_id` text DEFAULT NULL,
  `price` float(10,2) NOT NULL DEFAULT 0.00,
  `discount` float(10,2) NOT NULL DEFAULT 0.00,
  `free_course` tinyint(1) DEFAULT NULL COMMENT '0=paid,1=free',
  `view_count` int(11) DEFAULT NULL,
  `front_side_visibility` varchar(10) NOT NULL DEFAULT 'yes',
  `status` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_courses`
--

INSERT INTO `online_courses` (`id`, `title`, `slug`, `url`, `description`, `teacher_id`, `category_id`, `outcomes`, `course_thumbnail`, `course_provider`, `course_url`, `video_id`, `price`, `discount`, `free_course`, `view_count`, `front_side_visibility`, `status`, `created_by`, `created_date`, `updated_date`) VALUES
(1, 'Bible Faith Study Course', 'bible-faith-study-course', 'course/coursedetail/bible-faith-study-course', '.', 2, 1, '[\"\"]', 'edit_course_thumbnail1.png', 'youtube', '', '', 0.00, 0.00, 1, 40, 'yes', 1, 1, '2025-08-05 03:34:31', '2025-08-05 06:27:25'),
(2, 'Bible Prayer Study Course', 'bible-prayer-study-course', 'course/coursedetail/bible-prayer-study-course', '<p>.</p>', 2, 1, '[\"\"]', 'course_thumbnail2.png', 'youtube', '', '', 0.00, 0.00, 1, 43, 'yes', 1, 1, '2025-08-05 06:33:26', '2025-08-05 06:33:26'),
(3, 'Bible Healing Study Course', 'bible-healing-study-course', 'course/coursedetail/bible-healing-study-course', '<p>.</p>', 2, 1, '[\"\"]', 'course_thumbnail3.png', 'youtube', '', '', 0.00, 0.00, 1, NULL, 'yes', 0, 1, '2025-08-05 06:34:44', '2025-08-05 06:34:44'),
(4, 'The Holy Spirit', 'the-holy-spirit', 'course/coursedetail/the-holy-spirit', '<p>In this course you will learn about the Holy Spirit.</p>', 2, 2, '[\"\"]', 'course_thumbnail4.jpg', 'youtube', '', '', 0.00, 0.00, 1, 4, 'yes', 0, 1, '2025-08-09 03:38:21', '2025-08-09 04:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `online_course_assignment`
--

CREATE TABLE `online_course_assignment` (
  `id` int(11) NOT NULL,
  `course_section_id` int(11) NOT NULL,
  `assignment_title` varchar(255) DEFAULT NULL,
  `assignment_date` date DEFAULT NULL,
  `submit_date` date DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `marks` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `evaluated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_assignment_evaluation`
--

CREATE TABLE `online_course_assignment_evaluation` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `marks` float(10,2) DEFAULT NULL,
  `note` varchar(255) NOT NULL,
  `date` date NOT NULL COMMENT 'evaluation date',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_assignment_submit`
--

CREATE TABLE `online_course_assignment_submit` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `docs` varchar(225) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_class_sections`
--

CREATE TABLE `online_course_class_sections` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_course_class_sections`
--

INSERT INTO `online_course_class_sections` (`id`, `course_id`, `class_section_id`, `created_date`) VALUES
(1, 1, 3, '2025-08-05 03:34:31'),
(2, 1, 4, '2025-08-05 03:34:31'),
(3, 2, 3, '2025-08-05 06:33:26'),
(4, 2, 4, '2025-08-05 06:33:26'),
(5, 3, 3, '2025-08-05 06:34:44'),
(6, 3, 4, '2025-08-05 06:34:44'),
(7, 4, 3, '2025-08-09 03:38:21'),
(8, 4, 4, '2025-08-09 03:38:21');

-- --------------------------------------------------------

--
-- Table structure for table `online_course_exam`
--

CREATE TABLE `online_course_exam` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course_section_id` int(11) DEFAULT NULL,
  `exam` text DEFAULT NULL,
  `attempt` int(11) NOT NULL,
  `exam_from` datetime DEFAULT NULL,
  `exam_to` datetime DEFAULT NULL,
  `is_quiz` int(11) NOT NULL DEFAULT 0,
  `auto_publish_date` datetime DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `duration` time NOT NULL,
  `passing_percentage` float NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `publish_result` int(11) NOT NULL DEFAULT 0,
  `answer_word_count` int(11) NOT NULL DEFAULT -1,
  `is_active` varchar(1) DEFAULT '0',
  `is_marks_display` int(11) NOT NULL DEFAULT 0,
  `is_neg_marking` int(11) NOT NULL DEFAULT 0,
  `is_random_question` int(11) NOT NULL DEFAULT 0,
  `is_rank_generated` int(11) NOT NULL DEFAULT 0,
  `publish_exam_notification` int(11) NOT NULL,
  `publish_result_notification` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_exam_attempts`
--

CREATE TABLE `online_course_exam_attempts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_exam_marks`
--

CREATE TABLE `online_course_exam_marks` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `online_course_exam_id` int(11) DEFAULT NULL,
  `marks` float(10,2) NOT NULL DEFAULT 0.00,
  `neg_marks` float(10,2) NOT NULL DEFAULT 0.00,
  `is_active` varchar(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_exam_question`
--

CREATE TABLE `online_course_exam_question` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `question_type` varchar(100) NOT NULL,
  `level` varchar(10) NOT NULL,
  `question` text NOT NULL,
  `opt_a` text NOT NULL,
  `opt_b` text NOT NULL,
  `opt_c` text NOT NULL,
  `opt_d` text NOT NULL,
  `opt_e` text NOT NULL,
  `correct` text NOT NULL,
  `descriptive_word_limit` int(11) NOT NULL,
  `question_tag` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_exam_result`
--

CREATE TABLE `online_course_exam_result` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `select_option` longtext DEFAULT NULL,
  `marks` float(10,2) NOT NULL,
  `remark` text DEFAULT NULL,
  `attachment_name` text DEFAULT NULL,
  `attachment_upload_name` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_lesson`
--

CREATE TABLE `online_course_lesson` (
  `id` int(11) NOT NULL,
  `course_section_id` int(11) DEFAULT NULL,
  `lesson_title` varchar(255) DEFAULT NULL,
  `lesson_type` varchar(20) DEFAULT NULL,
  `thumbnail` varchar(100) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `video_provider` varchar(20) DEFAULT NULL,
  `video_url` text DEFAULT NULL,
  `video_id` varchar(50) DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_lesson_attachment`
--

CREATE TABLE `online_course_lesson_attachment` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `attachment` text NOT NULL,
  `attachment_name` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_payment`
--

CREATE TABLE `online_course_payment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `online_courses_id` int(11) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `actual_price` float(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` float(10,2) NOT NULL DEFAULT 0.00,
  `payment_mode` varchar(50) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `processing_charge_type` varchar(255) DEFAULT NULL,
  `processing_charge_amount` float(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_processing_payment`
--

CREATE TABLE `online_course_processing_payment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `online_courses_id` int(11) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `actual_price` float(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` float(10,2) NOT NULL DEFAULT 0.00,
  `payment_mode` varchar(50) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `processing_charge_type` varchar(255) DEFAULT NULL,
  `processing_charge_amount` float(10,2) DEFAULT 0.00,
  `gateway_ins_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_quiz`
--

CREATE TABLE `online_course_quiz` (
  `id` int(11) NOT NULL,
  `course_section_id` int(11) DEFAULT NULL,
  `quiz_title` varchar(255) DEFAULT NULL,
  `quiz_instruction` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_course_section`
--

CREATE TABLE `online_course_section` (
  `id` int(11) NOT NULL,
  `online_course_id` int(11) DEFAULT NULL,
  `section_title` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_course_section`
--

INSERT INTO `online_course_section` (`id`, `online_course_id`, `section_title`, `order`, `is_active`) VALUES
(1, 4, 'Discerning of spirits', NULL, NULL),
(2, 4, 'MAN IS A SPIRIT BEING', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `online_course_settings`
--

CREATE TABLE `online_course_settings` (
  `id` int(11) NOT NULL,
  `guest_prefix` varchar(50) NOT NULL,
  `guest_id_start_from` int(11) NOT NULL,
  `guest_login` int(11) DEFAULT 0,
  `course_curriculum_settings` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `online_course_settings`
--

INSERT INTO `online_course_settings` (`id`, `guest_prefix`, `guest_id_start_from`, `guest_login`, `course_curriculum_settings`) VALUES
(1, 'Guest', 1, 0, '[\"online_course_quiz\",\"online_course_exam\",\"online_course_assignment\"]');

-- --------------------------------------------------------

--
-- Table structure for table `online_course_tag`
--

CREATE TABLE `online_course_tag` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `partner_code` varchar(50) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `account_type` enum('individual','organization') NOT NULL DEFAULT 'individual',
  `organization_name` varchar(255) DEFAULT NULL,
  `organization_type` varchar(100) DEFAULT NULL COMMENT 'Church, Company, Foundation, NGO, Other',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `mobileno` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Zimbabwe',
  `zip_code` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_amount` decimal(10,2) DEFAULT NULL,
  `total_contribution_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL COMMENT 'Link to students table',
  `staff_id` int(11) DEFAULT NULL COMMENT 'Link to staff table',
  `notes` text DEFAULT NULL,
  `status` enum('pending','active','inactive','suspended') NOT NULL DEFAULT 'pending',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `partner_code`, `username`, `password`, `account_type`, `organization_name`, `organization_type`, `firstname`, `lastname`, `email`, `is_email_verified`, `email_verification_token`, `password_reset_token`, `password_reset_expires`, `last_login`, `login_attempts`, `locked_until`, `mobileno`, `address`, `city`, `state`, `country`, `zip_code`, `photo`, `giving_type_id`, `giving_frequency_id`, `contribution_amount`, `total_contribution_amount`, `currency`, `start_date`, `end_date`, `student_id`, `staff_id`, `notes`, `status`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PTR-2025-0001', 'john.doe', '$2y$10$VapL7z7HYPYdH4y4iCwe3.py.Jigx0IVOAOVv0qmTIaWqWpongxMi', 'individual', NULL, NULL, 'John', 'Doe', 'john.doe@example.com', 0, NULL, NULL, NULL, '2025-10-16 15:13:11', 0, NULL, '+263771234567', '123 Main Street', 'Harare', NULL, 'Zimbabwe', NULL, NULL, 1, 3, 1000.00, 100.00, 'USD', NULL, NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-09-30 18:08:33', '2025-10-16 18:13:11'),
(2, 'PTR-2025-0002', 'mary', '$2y$10$VapL7z7HYPYdH4y4iCwe3.py.Jigx0IVOAOVv0qmTIaWqWpongxMi', 'organization', 'Grace Foundation', 'Foundation', 'Mary', 'Smith', 'mary@gracefoundation.org', 0, NULL, NULL, NULL, NULL, 0, NULL, '+263777654321', '456 Church Road', 'Bulawayo', NULL, 'Zimbabwe', NULL, NULL, 2, 3, 500.00, 500.00, 'USD', NULL, NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-09-30 18:08:33', '2025-10-02 19:05:07'),
(5, 'PTR-2025-3820', NULL, NULL, 'individual', NULL, NULL, 'Dawood', 'Zafar', 'dawood.dixeam@gmail.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '03063301114', 'Corrupti asperiores', 'Hic eum autem ipsa', 'Voluptatem cum fuga', 'South Africa', '69418', NULL, NULL, 2, 60.00, 0.00, 'USD', '2025-10-06', NULL, NULL, NULL, 'Corporis illo volupt', 'inactive', 1, NULL, '2025-10-06 11:53:37', NULL),
(6, 'PTR-2025-6438', 'sixut', '$2y$10$jXkZ8GtqD.oFzYcg5/.PJ.iR3Aq5BGrl6gHVbX4YvkDhZr5EkBG5i', 'individual', NULL, NULL, 'Bruno', 'Ballard', 'sixut@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Deleniti iure obcaec', 'Quo officia fugiat', 'Dolor est esse dolo', 'Perferendis voluptas', 'South Africa', '13446', NULL, NULL, 2, 100.00, 0.00, 'USD', '2025-10-06', NULL, NULL, NULL, 'Qui blanditiis dolor', 'inactive', 1, NULL, '2025-10-06 16:05:02', NULL),
(7, 'PTR-2025-3599', 'pypa', '$2y$10$fhace6HNtO2tP2lVrFXhDuOWobMSwWslLFYrzD1rInq2KTjFqpLMy', 'individual', NULL, NULL, 'Upton', 'Noel', 'pypa@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Quia et nostrum amet', 'Voluptatem Nam maxi', 'In dolor qui dolorib', 'Cupidatat quae duis ', 'Botswana', '78345', NULL, NULL, 5, 120.00, 0.00, 'ZWL', '2025-10-06', NULL, NULL, NULL, 'Fuga Dolores quidem', 'inactive', 1, NULL, '2025-10-06 16:21:34', NULL),
(8, 'PTR-2025-7806', 'loro', '$2y$10$PiQv8lwNQGWrfF2aaMeR1O1ZX6DdDkLYArH0gzjz7Mj3h5q1OuGl.', 'individual', NULL, NULL, 'Wylie', 'Hendrix', 'loro@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Eveniet beatae sed', 'Quia nisi ut enim vi', 'Nostrum dolor aliqua', 'Incididunt reprehend', 'United Kingdom', '40066', NULL, NULL, 1, 1200.00, 0.00, 'USD', '2025-10-06', NULL, NULL, NULL, 'Ad harum voluptatem', 'inactive', 1, NULL, '2025-10-06 16:22:47', NULL),
(9, 'PTR-2025-3158', 'july', '$2y$10$P01DIJZ/7KWmYsUOeNOP..LTcWyN4Lg2KfK0wTs/3Z3zVjEIQGW5G', 'organization', 'Guzman Berg Inc', 'foundation', 'Haviva', 'Vasquez', 'july@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Adipisicing recusand', 'Maiores iusto corrup', 'Suscipit delectus c', 'Dolor necessitatibus', 'Zambia', '62193', NULL, NULL, 4, 120.00, 0.00, 'USD', '2025-10-06', NULL, NULL, NULL, 'Doloremque veniam a', 'inactive', 1, NULL, '2025-10-06 16:23:39', NULL),
(10, 'PTR-TEST-0001', NULL, NULL, 'individual', NULL, NULL, 'Amaya', 'White', 'xelofot@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Fugiat nisi minim do', 'Quaerat rerum dolore', 'Voluptatum expedita ', 'Ut omnis esse modi q', 'Modi dolore qui fugi', '64682', NULL, 3, 3, 60.00, 0.00, 'ZMW', '0000-00-00', '0000-00-00', 1, NULL, 'Esse adipisci sit u', 'inactive', 1, NULL, '2025-10-10 15:14:45', '2025-10-11 19:05:35'),
(11, 'PTR-TEST-0002', NULL, NULL, 'individual', NULL, NULL, 'Mary', 'Smith', 'kuda@virtual.co.zw', 0, NULL, NULL, NULL, NULL, 0, NULL, '0776633097', '456 Church Road', 'Bulawayo', NULL, 'Zimbabwe', NULL, NULL, NULL, 3, 200.00, 0.00, 'USD', NULL, NULL, 1, NULL, NULL, 'active', 1, NULL, '2025-10-10 15:14:45', '2025-10-10 15:14:53'),
(12, 'PTR-TEST-0003', NULL, NULL, 'organization', 'Grace Foundation', 'Foundation', 'David', 'Wilson', 'kuda@virtual.co.zw', 0, NULL, NULL, NULL, NULL, 0, NULL, '0776633097', '789 Charity Lane', 'Harare', NULL, 'Zimbabwe', NULL, NULL, NULL, 3, 375.00, 0.00, 'USD', NULL, NULL, 1, NULL, NULL, 'active', 1, NULL, '2025-10-10 15:14:45', '2025-10-10 15:14:53'),
(15, 'P-20251010-2282', NULL, NULL, 'organization', 'Cervantes and Boyer Trading', 'NGO', 'Jack', 'Day', 'bumuj@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Iusto non perspiciat', 'Ea culpa in nemo per', 'Et expedita et ut re', 'Sequi rerum expedita', 'Et ad et commodo aut', '41640', NULL, 2, 1, 14.00, 0.00, 'ZAR', NULL, NULL, 1, NULL, 'Blanditiis incidunt', 'inactive', 1, NULL, '2025-10-10 12:39:37', NULL),
(16, 'P-20251010-0652', NULL, NULL, 'organization', 'Weeks Perez Trading', 'Foundation', 'Fallon', 'Malone', 'hevexipoq@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Pariatur Non commod', 'Autem eum reprehende', 'Minus ab dolores vol', 'Eum et officia conse', 'Aperiam cumque digni', '96786', NULL, 3, 1, 9.00, 0.00, 'ZWL', NULL, NULL, 1, NULL, 'Dolor iure eius anim', 'inactive', 1, NULL, '2025-10-10 12:40:29', NULL),
(17, 'P-20251010-8157', NULL, NULL, 'organization', 'Chaney Bauer Plc', 'Company', 'Debra', 'Nash', 'nibopape@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Veniam rem dolor om', 'Irure optio amet h', 'Nemo repudiandae und', 'Cum earum tempora se', 'Ad sequi non enim bl', '50586', 'uploads/partner_images/partner_1761360633.jpg', 5, 5, 91.00, 0.00, 'USD', '0000-00-00', '0000-00-00', 1, NULL, 'Sed officia soluta e', 'inactive', 1, NULL, '2025-10-10 12:41:35', '2025-10-25 02:50:33'),
(18, 'PTR-2025-8822', NULL, NULL, 'individual', NULL, NULL, 'Philip', 'Lopez', 'vusi@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Vitae incididunt ut', 'Unde officia exercit', 'Eos perferendis maxi', 'Eum aliquam et esse ', 'Aut ut amet amet t', '68845', NULL, 3, 2, 1.00, 0.00, 'ZMW', '0000-00-00', NULL, NULL, NULL, 'Adipisci vel dolorem', 'active', 1, 1, '2025-10-11 20:33:59', NULL),
(19, 'PTR-2025-9600', NULL, NULL, 'individual', NULL, NULL, 'Roary', 'Booker', 'hyla@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Nisi eius labore ill', 'At commodi qui commo', 'Omnis non cillum lab', 'Id sunt distinctio', 'Qui numquam est ut ', '26436', NULL, 4, 5, 3.00, 0.00, 'ZMW', '2010-07-24', '1998-08-10', NULL, NULL, 'Corrupti quas earum', 'active', 1, NULL, '2025-10-12 21:52:11', NULL),
(20, 'PTR-2025-9816', NULL, NULL, 'individual', NULL, NULL, 'Rowan', 'Barton', 'xonuq@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Qui non est ea conse', 'Qui illum ut dolore', 'Sint deserunt archi', 'Recusandae Pariatur', 'Magnam neque volupta', '61993', NULL, 1, 3, 97.00, 0.00, 'USD', '1977-06-15', '1996-09-04', NULL, NULL, 'Rerum assumenda libe', 'active', 1, NULL, '2025-10-16 13:17:16', NULL),
(21, 'PTR-2025-1555', 'zefefe', '$2y$10$.lxo2ym16clHXkiwKd45vuqOJV1gDw/iCz4/N4BoNP706EDLK13Ra', 'individual', NULL, NULL, 'Zoe', 'Barr', 'zefefe@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Aliqua Quasi veniam', 'Elit dolores iure l', 'Impedit et aliqua', 'Facilis et aut minus', 'United Kingdom', '43943', NULL, NULL, 5, 120.00, 0.00, 'USD', '2025-10-24', NULL, NULL, NULL, 'Ducimus labore cupi', 'pending', 1, NULL, '2025-10-24 16:18:53', NULL),
(22, 'PTR-2025-0121', 'dawoodzafar', '$2y$10$v30OKCU9VgwndW2sUd1JCOsOBvB8v5f79HA6vzDruLYhb311y.Yq.', 'individual', NULL, NULL, 'Devin', 'Huber', 'dawoodzafar@dixeam.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '123456789', 'house 109 Eagle City Sargodha', 'sargodha', NULL, 'Zimbabwe', '40100', NULL, NULL, 3, 1000.00, 0.00, 'USD', '2025-10-25', NULL, NULL, NULL, 'sdsdf', 'pending', 1, NULL, '2025-10-24 19:22:22', NULL),
(23, 'PTR-2025-1340', 'bomuzyladu', '$2y$10$ijrwuiHn3IdYvC.75LqfxuhAp.sEVO098V2ljGYVUXa1zyY9.ok9C', 'individual', NULL, NULL, 'Hanae', 'Peterson', 'bomuzyladu@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Commodo non temporib', 'Quis dolore est ut c', 'Veritatis iusto aspe', 'Optio qui delectus', 'United States', '50403', NULL, NULL, 4, 100.00, 0.00, 'ZWL', '2025-10-25', NULL, NULL, NULL, 'Placeat magni ea od', 'pending', 1, NULL, '2025-10-24 19:23:56', NULL),
(24, 'PTR-2025-9960', NULL, NULL, 'individual', NULL, NULL, 'Jada', 'Holder', 'paced@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, 'Dolore et in perfere', 'Ex ut corrupti odio', 'Reiciendis consectet', 'Nam ullam explicabo', 'Zambia', '41960', NULL, NULL, 1, 10.00, 0.00, 'ZWL', '2025-10-24', NULL, NULL, NULL, 'Aliqua Quod fugit ', 'pending', 1, NULL, '2025-10-24 22:43:16', NULL),
(26, 'PTR-2025-6649', 'pybozahoto', '$2y$10$PrCwm2xOi0nzuCuubejD/.gdYnYscbcwjrW9u5YdjKNQsneE.j002', 'individual', NULL, NULL, 'Finn', 'Ortiz', 'pybozahoto@mailinator.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '213456789', 'Esse accusantium po', 'Vel qui laboriosam', 'A deleniti veritatis', 'United States', '93593', NULL, NULL, 3, 100.00, 0.00, 'USD', '2025-10-24', NULL, NULL, NULL, 'kjjk', 'active', 1, NULL, '2025-10-24 22:48:28', '2025-10-28 06:32:59'),
(27, 'PTR-2025-0353', 'dawood.dixedam', '$2y$10$RFo.F5t/teC2JkktM9yoQ.lYOhBKro9Rpe9UhVCEC2o2GW2LD3BAK', 'individual', NULL, NULL, 'Dawood', 'Zafar', 'dawood.dixedam@gmail.com', 0, NULL, NULL, NULL, '2025-11-03 13:40:04', 0, NULL, '03063301114', 'house 109 Eagle City Sargodha', 'sargodha', '40100', 'Zimbabwe', '40100', NULL, NULL, 2, 122.00, 0.00, 'ZAR', '2025-10-25', NULL, NULL, NULL, 'dsfsdf', 'active', 1, NULL, '2025-10-25 00:23:59', '2025-11-03 13:40:04'),
(28, 'PTR-2025-0960', NULL, NULL, 'individual', NULL, NULL, 'Test', 'Person', 'hello@tenganimanje.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '0969731477', 'Plot 11293 Nchoncho road\r\nLight industrial area', 'Lusaka', 'Lusaka', 'Zambia', '10100', NULL, 1, 3, 1000.00, 0.00, 'USD', '2025-10-28', '2026-03-31', 1, NULL, '', 'active', 1, 1, '2025-10-28 07:52:18', NULL),
(29, 'PTR-2025-8362', NULL, NULL, 'individual', NULL, NULL, 'james', 'tendai', 'chiedza.kamudyariwa@gmail.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '0977465566', 'acacia drive', 'lusaka', NULL, 'Zimbabwe', NULL, NULL, NULL, 3, 300.00, 0.00, 'USD', '2025-10-28', NULL, NULL, NULL, NULL, 'pending', 1, NULL, '2025-10-28 13:35:11', NULL),
(30, 'PTR-2025-4152', 'test', '$2y$10$zqbGQ0.QLljii0eTMp7T8OnuvrNoIIHeQW0Ly68ovQBoq8YK2bC6q', 'individual', NULL, NULL, 'test', 'test', 'test@gmail.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '89567347809', NULL, NULL, NULL, 'Zimbabwe', NULL, NULL, NULL, 1, 100.00, 0.00, 'USD', '2025-10-28', NULL, NULL, NULL, NULL, 'pending', 1, NULL, '2025-10-28 13:40:58', NULL),
(31, 'PTR-2025-0196', 'nathan', '$2y$10$PteFjqv1cabNq4voOdj./uoueLml5WCAta6XmESEgB0MwhnNrOzjm', 'individual', NULL, NULL, 'Nathan', 'Kamudyariwa', 'nathan@gmail.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '0976563677', '54 acacia drive\r\nLilayi', 'Lusaka', NULL, 'Zambia', NULL, NULL, NULL, 4, 250.00, 0.00, 'USD', '2025-11-02', NULL, NULL, NULL, NULL, 'pending', 1, NULL, '2025-11-02 09:49:41', NULL),
(32, 'PTR-2025-6009', 'avakamu', '$2y$10$gBVWI1xP.1tY44IjNNryo.QGE/PhahP5ycNpqR.dqYE8bifw03xFK', 'individual', NULL, NULL, 'Ava', 'Kamu', 'avakamu@gmail.com', 0, NULL, NULL, NULL, '2025-11-03 13:45:17', 0, NULL, '0977462656', '11293 nchoncho road,\r\nLight industrial area\r\nLusaka', 'Lusaka', 'lusaka', 'Zambia', NULL, NULL, NULL, 3, 115.00, 0.00, 'USD', '2025-11-03', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-11-03 05:51:01', '2025-11-03 14:07:26'),
(33, 'PTR-2025-3573', 'komozo', '$2y$10$.cdWDTIcytN85mZrg3TDBO7qpmMqsammtSFhXyClUctysPUzNc6xe', 'individual', NULL, NULL, 'Christian', 'Nichols', 'komozo@mailinator.com', 0, NULL, NULL, NULL, '2025-11-03 06:22:17', 0, NULL, 'Nulla adipisicing qu', 'Vel vel reprehenderi', 'Eveniet eu dolor pr', 'Voluptates doloribus', 'United Kingdom', '58641', NULL, NULL, 1, 124.00, 0.00, 'ZWL', '2025-11-03', NULL, NULL, NULL, 'Sed vel numquam tene', 'pending', 1, NULL, '2025-11-03 06:22:00', '2025-11-03 06:22:17'),
(34, 'PTR-2025-0700', 'kiwod', '$2y$10$P2HCSQAJtrQtiwwCxvvhQel/o.jCbb4GhZF4PWxhvvF0eo8a2s97G', 'individual', NULL, NULL, 'Ursula', 'Serrano', 'kiwod@mailinator.com', 0, NULL, NULL, NULL, '2025-11-03 06:30:00', 0, NULL, 'Quidem sit vitae dol', 'Laborum ullamco dolo', 'Ullam vel molestiae', 'Cupiditate ut delect', 'Botswana', '20347', NULL, NULL, 2, 145.00, 0.00, 'USD', '2025-11-03', NULL, NULL, NULL, 'Pariatur Magnam rei', 'active', 1, NULL, '2025-11-03 06:29:49', '2025-11-03 06:30:00'),
(35, 'PTR-2025-9877', 'amyfrank', '$2y$10$gTflKn3NDJMNhQ6jPLOr9.JMFdAMXa5QgRdblYWMJq0xCCy59VeMW', 'individual', NULL, NULL, 'Amy', 'Frank', 'amyfrank@gmail.com', 0, NULL, NULL, NULL, '2025-11-03 11:33:14', 0, NULL, '095762145473', 'plot 4545', 'lusaka', 'lusaka', 'Zimbabwe', NULL, NULL, NULL, 1, 1.00, 0.00, 'USD', '2025-11-03', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-11-03 06:32:35', '2025-11-03 11:33:14'),
(36, 'P-20251103-21051', NULL, '$2y$10$4hcbgA8PDzsW1MVDVRTYq.B5dgC9ExHRLvGEsTbbTAFxp4pS8ZLqS', 'individual', NULL, NULL, 'James', 'Shambare', 'jamestendai2000@gmail.com', 0, NULL, NULL, NULL, NULL, 0, NULL, '0966982652', '', '', '', 'Zimbabwe', '', NULL, NULL, 3, NULL, 0.00, 'USD', NULL, NULL, 2, NULL, '', 'active', 1, 2, '2025-11-03 09:47:46', NULL),
(37, 'PTR-2025-7393', 'tshambare', '$2y$10$EQ2A2ZX3mOUSo6Q5oDP7u.SkZvjX3OpvMpyJVaMdrfaNcKaghqOlK', 'individual', NULL, NULL, 'James', 'Tendai', 'tshambare@gmail.com', 0, NULL, NULL, NULL, '2025-11-03 10:38:37', 0, NULL, '0966982652', 'Nchonchi Road', 'Lusaka', 'Lusaka', 'Zimbabwe', '10101', NULL, NULL, 3, 5.00, 0.00, 'USD', '2025-11-03', NULL, 2, NULL, NULL, 'active', 1, NULL, '2025-11-03 10:36:47', '2025-11-03 10:38:37');

--
-- Triggers `partners`
--
DELIMITER $$
CREATE TRIGGER `trg_partner_created` AFTER INSERT ON `partners` FOR EACH ROW BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.id, 'created', CONCAT('Partner registered: ', NEW.partner_code), NEW.created_by);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_partner_updated` AFTER UPDATE ON `partners` FOR EACH ROW BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.id, 'updated', 'Partner record updated', NULL);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `partner_activity_log`
--

CREATE TABLE `partner_activity_log` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL COMMENT 'created, updated, contribution_added, etc.',
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_activity_log`
--

INSERT INTO `partner_activity_log` (`id`, `partner_id`, `activity_type`, `description`, `ip_address`, `user_agent`, `performed_by`, `created_at`) VALUES
(1, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-09-30 18:36:28'),
(2, 2, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-09-30 18:36:28'),
(3, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-01 14:26:44'),
(4, 2, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-01 14:26:44'),
(5, 2, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-02 19:05:07'),
(6, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-02 19:05:10'),
(7, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-02 19:05:18'),
(8, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-10-02 19:05:18'),
(9, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-06 13:48:44'),
(10, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-06 13:48:49'),
(11, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-10-06 13:48:49'),
(12, 5, 'created', 'Partner registered: PTR-2025-3820', NULL, NULL, NULL, '2025-10-06 14:53:37'),
(13, 6, 'created', 'Partner registered: PTR-2025-6438', NULL, NULL, NULL, '2025-10-06 19:05:02'),
(14, 7, 'created', 'Partner registered: PTR-2025-3599', NULL, NULL, NULL, '2025-10-06 19:21:34'),
(15, 8, 'created', 'Partner registered: PTR-2025-7806', NULL, NULL, NULL, '2025-10-06 19:22:47'),
(16, 9, 'created', 'Partner registered: PTR-2025-3158', NULL, NULL, NULL, '2025-10-06 19:23:39'),
(17, 9, 'contribution_added', 'Contribution added: EUR 99.00', NULL, NULL, NULL, '2025-10-10 14:11:36'),
(18, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:07:43'),
(19, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 15:07:43'),
(20, 10, 'created', 'Partner registered: PTR-TEST-0001', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(21, 11, 'created', 'Partner registered: PTR-TEST-0002', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(22, 12, 'created', 'Partner registered: PTR-TEST-0003', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(23, 10, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(24, 11, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(25, 12, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:45'),
(26, 10, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:53'),
(27, 11, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:53'),
(28, 12, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-10 15:14:53'),
(29, 15, 'created', 'Partner registered: P-20251010-2282', NULL, NULL, NULL, '2025-10-10 15:39:37'),
(30, 16, 'created', 'Partner registered: P-20251010-0652', NULL, NULL, NULL, '2025-10-10 15:40:29'),
(31, 17, 'created', 'Partner registered: P-20251010-8157', NULL, NULL, NULL, '2025-10-10 15:41:35'),
(32, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-11 18:22:46'),
(33, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-11 18:22:46'),
(34, 1, 'contribution_added', 'Contribution added: ZWL 43.00', NULL, NULL, NULL, '2025-10-11 18:41:56'),
(35, 10, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-11 19:05:35'),
(36, 10, 'contribution_added', 'Contribution added: GBP 31.00', NULL, NULL, NULL, '2025-10-11 19:09:28'),
(37, 1, 'contribution_added', 'Contribution added: USD 69.00', NULL, NULL, NULL, '2025-10-11 20:33:22'),
(38, 18, 'created', 'Partner registered: PTR-2025-8822', NULL, NULL, 1, '2025-10-11 20:33:59'),
(39, 7, 'contribution_added', 'Contribution added: USD 54.00', NULL, NULL, NULL, '2025-10-11 20:34:09'),
(40, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-11 22:31:01'),
(41, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-11 22:31:01'),
(42, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-12 15:18:03'),
(43, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-12 15:18:03'),
(44, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-13 00:51:26'),
(45, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-13 00:51:26'),
(46, 19, 'created', 'Partner registered: PTR-2025-9600', NULL, NULL, NULL, '2025-10-13 00:52:11'),
(47, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-16 16:17:00'),
(48, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 16:17:00'),
(49, 20, 'created', 'Partner registered: PTR-2025-9816', NULL, NULL, NULL, '2025-10-16 16:17:16'),
(50, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-16 16:24:09'),
(51, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 16:24:09'),
(52, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-16 17:42:24'),
(53, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 17:42:24'),
(54, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-16 17:54:41'),
(55, 1, 'logout', 'Partner logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 18:04:56'),
(56, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-16 18:13:11'),
(57, 1, 'login', 'Partner logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 18:13:11'),
(0, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-24 13:55:28'),
(0, 1, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 13:55:28'),
(0, 1, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 13:55:52'),
(0, 0, 'created', 'Partner registered: PTR-2025-9586', NULL, NULL, NULL, '2025-10-24 15:09:42'),
(0, 0, 'created', 'Partner registered: PTR-2025-0119', NULL, NULL, NULL, '2025-10-24 15:10:03'),
(0, 0, 'created', 'Partner registered: PTR-2025-3467', NULL, NULL, NULL, '2025-10-24 15:10:48'),
(0, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-24 15:11:08'),
(0, 1, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 15:11:08'),
(0, 1, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-24 15:12:15'),
(0, 1, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 15:12:26'),
(0, 0, 'created', 'Partner registered: PTR-2025-9722', NULL, NULL, 1, '2025-10-24 15:13:58'),
(0, 0, 'created', 'Partner registered: PTR-2025-0896', NULL, NULL, NULL, '2025-10-24 15:20:14'),
(0, 0, 'created', 'Partner registered: PTR-2025-0235', NULL, NULL, NULL, '2025-10-24 19:21:58'),
(0, 0, 'created', 'Partner registered: PTR-2025-7629', NULL, NULL, NULL, '2025-10-24 19:24:28'),
(0, 0, 'created', 'Partner registered: PTR-2025-8129', NULL, NULL, NULL, '2025-10-24 19:29:47'),
(0, 0, 'created', 'Partner registered: PTR-2025-6912', NULL, NULL, NULL, '2025-10-24 19:35:18'),
(0, 0, 'created', 'Partner registered: PTR-2025-4097', NULL, NULL, NULL, '2025-10-24 19:35:56'),
(0, 0, 'created', 'Partner registered: PTR-2025-TEST1', NULL, NULL, NULL, '2025-10-24 19:36:55'),
(0, 0, 'created', 'Partner registered: PTR-2025-TEST1', NULL, NULL, NULL, '2025-10-24 19:37:07'),
(0, 0, 'created', 'Partner registered: PTR-2025-2043', NULL, NULL, NULL, '2025-10-24 19:42:57'),
(0, 0, 'created', 'Partner registered: PTR-2025-1093', NULL, NULL, NULL, '2025-10-24 19:43:47'),
(0, 0, 'created', 'Partner registered: PTR-2025-2700', NULL, NULL, NULL, '2025-10-24 22:17:47'),
(0, 0, 'created', 'Partner registered: PTR-2025-8252', NULL, NULL, NULL, '2025-10-24 22:30:56'),
(0, 0, 'created', 'Partner registered: PTR-2025-1438', NULL, NULL, NULL, '2025-10-24 22:31:21'),
(0, 0, 'created', 'Partner registered: PTR-2025-8960', NULL, NULL, NULL, '2025-10-24 22:37:22'),
(0, 0, 'created', 'Partner registered: PTR-2025-4169', NULL, NULL, NULL, '2025-10-24 22:37:51'),
(0, 24, 'created', 'Partner registered: PTR-2025-9960', NULL, NULL, NULL, '2025-10-24 22:43:16'),
(0, 25, 'created', 'Partner registered: PTR-2025-6884', NULL, NULL, NULL, '2025-10-24 22:46:07'),
(0, 26, 'created', 'Partner registered: PTR-2025-6649', NULL, NULL, NULL, '2025-10-24 22:48:28'),
(0, 27, 'created', 'Partner registered: PTR-2025-0353', NULL, NULL, NULL, '2025-10-25 00:24:00'),
(0, 17, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-25 02:50:33'),
(0, 1, 'contribution_added', 'Contribution added: USD 500.00', NULL, NULL, NULL, '2025-10-25 02:57:35'),
(0, 28, 'created', 'Partner registered: PTR-2025-0960', NULL, NULL, 1, '2025-10-28 05:52:18'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-28 06:32:47'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-28 06:32:47'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-28 06:32:47'),
(0, 26, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-10-28 06:32:59'),
(0, 29, 'created', 'Partner registered: PTR-2025-8362', NULL, NULL, NULL, '2025-10-28 13:35:11'),
(0, 30, 'created', 'Partner registered: PTR-2025-4152', NULL, NULL, NULL, '2025-10-28 13:40:58'),
(0, 31, 'created', 'Partner registered: PTR-2025-0196', NULL, NULL, NULL, '2025-11-02 09:49:41'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-02 12:41:19'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-02 12:41:19'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-02 12:41:39'),
(0, 32, 'created', 'Partner registered: PTR-2025-6009', NULL, NULL, NULL, '2025-11-03 05:51:01'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 05:52:05'),
(0, 32, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 05:52:05'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 05:54:00'),
(0, 32, 'login', 'Partner logged in', '102.212.181.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-11-03 05:54:00'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:12:34'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:12:34'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:13:50'),
(0, 32, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-11-03 06:13:50'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:14:34'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:14:34'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:14:56'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:15:14'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:15:14'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:16:02'),
(0, 32, 'login', 'Partner logged in', '216.234.213.27', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-11-03 06:16:02'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:16:19'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-11-03 06:16:19'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:16:42'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:17:46'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:18:04'),
(0, 32, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:18:04'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:19:01'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:19:41'),
(0, 27, 'login', 'Partner logged in', '102.212.181.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-11-03 06:19:41'),
(0, 33, 'created', 'Partner registered: PTR-2025-3573', NULL, NULL, NULL, '2025-11-03 06:22:00'),
(0, 33, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:22:17'),
(0, 33, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:22:17'),
(0, 33, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:29:26'),
(0, 34, 'created', 'Partner registered: PTR-2025-0700', NULL, NULL, NULL, '2025-11-03 06:29:49'),
(0, 34, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:30:00'),
(0, 34, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 06:30:00'),
(0, 32, 'logout', 'Partner logged out', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 06:30:51'),
(0, 35, 'created', 'Partner registered: PTR-2025-9877', NULL, NULL, NULL, '2025-11-03 06:32:35'),
(0, 35, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:33:00'),
(0, 35, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 06:33:00'),
(0, 35, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 06:37:06'),
(0, 36, 'created', 'Partner registered: P-20251103-21051', NULL, NULL, 2, '2025-11-03 07:47:46'),
(0, 37, 'created', 'Partner registered: PTR-2025-7393', NULL, NULL, NULL, '2025-11-03 10:36:47'),
(0, 37, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 10:38:17'),
(0, 37, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 10:38:37'),
(0, 37, 'login', 'Partner logged in', '41.216.87.8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-11-03 10:38:37'),
(0, 35, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 11:33:14'),
(0, 35, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-11-03 11:33:14'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 12:49:34'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 12:49:34'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 12:50:21'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 12:53:33'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 12:53:33'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 12:53:49'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 12:58:08'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:25:29'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 13:25:29'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:29:42'),
(0, 32, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 13:29:42'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:30:35'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:32:05'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:32:38'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:33:33'),
(0, 32, 'logout', 'Partner logged out', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 13:36:11'),
(0, 27, 'logout', 'Partner logged out', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 13:39:51'),
(0, 27, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:40:04'),
(0, 27, 'login', 'Partner logged in', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-03 13:40:04'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 13:45:17'),
(0, 32, 'login', 'Partner logged in', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-03 13:45:17'),
(0, 32, 'updated', 'Partner record updated', NULL, NULL, NULL, '2025-11-03 14:07:26'),
(0, 20, 'contribution_added', 'Contribution added: GBP 99.00', NULL, NULL, NULL, '2025-11-03 15:24:26'),
(0, 20, 'contribution_added', 'Contribution added: ZWL 98.00', NULL, NULL, NULL, '2025-11-03 15:24:39'),
(0, 36, 'contribution_added', 'Contribution added: USD 123.00', NULL, NULL, NULL, '2025-11-03 15:26:39'),
(0, 1, 'contribution_added', 'Contribution added: USD 100.00', NULL, NULL, NULL, '2025-11-03 15:32:11'),
(0, 1, 'contribution_added', 'Contribution added: USD 100.00', NULL, NULL, NULL, '2025-11-03 15:33:38'),
(0, 26, 'contribution_added', 'Contribution added: GBP 17.00', NULL, NULL, NULL, '2025-11-03 15:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `partner_contributions`
--

CREATE TABLE `partner_contributions` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) DEFAULT NULL,
  `giving_frequency_id` int(11) DEFAULT NULL,
  `contribution_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Cash, Bank Transfer, Mobile Money, etc.',
  `transaction_id` varchar(100) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL COMMENT 'Reference number for the contribution',
  `receipt_no` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `attachment` varchar(500) DEFAULT NULL COMMENT 'File path for contribution receipt/proof',
  `status` enum('pending','completed','cancelled','failed','refunded') NOT NULL DEFAULT 'completed',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL COMMENT 'Staff ID who recorded the contribution',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_contributions`
--

INSERT INTO `partner_contributions` (`id`, `partner_id`, `giving_type_id`, `giving_frequency_id`, `contribution_date`, `amount`, `currency`, `payment_method`, `transaction_id`, `reference_no`, `receipt_no`, `notes`, `attachment`, `status`, `approved_by`, `approved_at`, `recorded_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2025-01-15', 100.00, 'USD', 'Bank Transfer', NULL, NULL, 'RCT-20250115-0001', NULL, NULL, 'completed', NULL, NULL, NULL, NULL, '2025-09-30 18:08:33', NULL),
(3, 2, 2, NULL, '2025-01-10', 500.00, 'USD', 'Bank Transfer', NULL, NULL, 'RCT-20250110-0003', NULL, NULL, 'completed', NULL, NULL, NULL, NULL, '2025-09-30 18:08:33', NULL),
(4, 9, 2, 2, '1979-12-14', 99.00, 'EUR', 'cheque', 'Eos omnis quos pari', '600', 'RCT-20251010-2137', 'Reiciendis ipsam vol', NULL, 'cancelled', NULL, NULL, 1, NULL, '2025-10-10 14:11:36', NULL),
(5, 1, 4, NULL, '1979-11-07', 43.00, 'ZWL', 'bank_transfer', 'Molestiae nulla non ', NULL, 'RCT-20251011-8685', 'Et vero sit volupta', NULL, 'pending', NULL, NULL, NULL, NULL, '2025-10-11 15:41:56', NULL),
(6, 10, 5, 3, '2023-08-03', 31.00, 'GBP', 'mobile_money', 'Eu et cumque sint cu', '777', 'RCT-20251011-8841', 'Molestiae quos ad ha', NULL, 'completed', NULL, NULL, 1, NULL, '2025-10-11 19:09:28', NULL),
(7, 1, 2, NULL, '2007-01-25', 69.00, 'USD', 'cash', 'Libero porro veniam', NULL, 'RCT-20251011-2260', 'Voluptas saepe quisq', NULL, 'pending', NULL, NULL, NULL, NULL, '2025-10-11 17:33:22', NULL),
(8, 7, 1, 1, '2004-09-29', 54.00, 'USD', 'online', 'Placeat ut et maxim', '806', 'RCT-20251011-0824', 'Ullamco cillum error', NULL, 'completed', NULL, NULL, 1, NULL, '2025-10-11 20:34:09', NULL),
(0, 26, 4, 2, '1974-12-17', 17.00, 'GBP', 'bank_transfer', 'Sunt adipisicing ali', '916', 'RCT-20251103-1270', 'Sequi fugiat quam r', NULL, 'cancelled', NULL, NULL, 1, NULL, '2025-11-03 15:33:56', NULL);

--
-- Triggers `partner_contributions`
--
DELIMITER $$
CREATE TRIGGER `trg_contribution_added` AFTER INSERT ON `partner_contributions` FOR EACH ROW BEGIN
    INSERT INTO partner_activity_log (partner_id, activity_type, description, performed_by)
    VALUES (NEW.partner_id, 'contribution_added',
            CONCAT('Contribution added: ', NEW.currency, ' ', NEW.amount),
            NEW.created_by);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `partner_documents`
--

CREATE TABLE `partner_documents` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_type` varchar(100) DEFAULT NULL COMMENT 'Agreement, ID, Certificate, etc.',
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partner_giving_settings`
--

CREATE TABLE `partner_giving_settings` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_giving_settings`
--

INSERT INTO `partner_giving_settings` (`id`, `partner_id`, `giving_type_id`, `amount`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 100.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(2, 10, 3, 50.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(3, 11, 2, 200.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(4, 12, 1, 150.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(5, 12, 2, 100.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(6, 12, 3, 75.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(7, 12, 4, 50.00, 'USD', 1, '2025-10-10 15:14:45', NULL),
(0, 1, 1, 1100.00, 'USD', 1, '2025-10-24 17:12:15', NULL),
(0, 35, 2, 1.00, 'USD', 1, '2025-11-03 08:37:06', NULL),
(0, 36, 1, 100.00, 'USD', 1, '2025-11-03 09:47:46', NULL),
(0, 27, 4, 64.00, 'ZAR', 1, '2025-11-03 14:58:08', NULL),
(0, 27, 5, 58.00, 'ZAR', 1, '2025-11-03 14:58:08', NULL),
(0, 32, 1, 20.00, 'USD', 1, '2025-11-03 15:33:33', NULL),
(0, 32, 2, 10.00, 'USD', 1, '2025-11-03 15:33:33', NULL),
(0, 32, 3, 5.00, 'USD', 1, '2025-11-03 15:33:33', NULL),
(0, 32, 4, 30.00, 'USD', 1, '2025-11-03 15:33:33', NULL),
(0, 32, 5, 50.00, 'USD', 1, '2025-11-03 15:33:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_giving_types`
--

CREATE TABLE `partner_giving_types` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `giving_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `partner_giving_types`
--

INSERT INTO `partner_giving_types` (`id`, `partner_id`, `giving_type_id`, `amount`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100.00, 'USD', 1, '2025-10-01 14:26:44', NULL),
(2, 2, 2, 500.00, 'USD', 1, '2025-10-01 14:26:44', NULL),
(3, 5, 1, 20.00, 'USD', 1, '2025-10-06 11:53:37', NULL),
(4, 5, 2, 40.00, 'USD', 1, '2025-10-06 11:53:37', NULL),
(5, 6, 1, 100.00, 'USD', 1, '2025-10-06 16:05:02', NULL),
(6, 7, 1, 120.00, 'ZWL', 1, '2025-10-06 16:21:34', NULL),
(7, 8, 1, 1200.00, 'USD', 1, '2025-10-06 16:22:47', NULL),
(8, 9, 1, 120.00, 'USD', 1, '2025-10-06 16:23:39', NULL),
(0, 24, 1, 10.00, 'ZWL', 1, '2025-10-24 22:43:16', NULL),
(0, 26, 1, 100.00, 'USD', 1, '2025-10-24 22:48:28', NULL),
(0, 27, 1, 100.00, 'ZWL', 1, '2025-10-25 00:24:00', NULL),
(0, 29, 1, 200.00, 'USD', 1, '2025-10-28 13:35:11', NULL),
(0, 29, 2, 100.00, 'USD', 1, '2025-10-28 13:35:11', NULL),
(0, 30, 1, 100.00, 'USD', 1, '2025-10-28 13:40:58', NULL),
(0, 31, 1, 150.00, 'USD', 1, '2025-11-02 09:49:41', NULL),
(0, 31, 3, 100.00, 'USD', 1, '2025-11-02 09:49:41', NULL),
(0, 32, 1, 10.00, 'USD', 1, '2025-11-03 05:51:01', NULL),
(0, 32, 4, 5.00, 'USD', 1, '2025-11-03 05:51:01', NULL),
(0, 33, 2, 98.00, 'ZWL', 1, '2025-11-03 06:22:00', NULL),
(0, 33, 3, 26.00, 'ZWL', 1, '2025-11-03 06:22:00', NULL),
(0, 34, 1, 60.00, 'USD', 1, '2025-11-03 06:29:49', NULL),
(0, 34, 2, 67.00, 'USD', 1, '2025-11-03 06:29:49', NULL),
(0, 34, 3, 9.00, 'USD', 1, '2025-11-03 06:29:49', NULL),
(0, 34, 5, 9.00, 'USD', 1, '2025-11-03 06:29:49', NULL),
(0, 35, 1, 10.00, 'USD', 1, '2025-11-03 06:32:35', NULL),
(0, 37, 1, 5.00, 'USD', 1, '2025-11-03 10:36:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_notes`
--

CREATE TABLE `partner_notes` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `note` text NOT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_notes`
--

INSERT INTO `partner_notes` (`id`, `partner_id`, `title`, `note`, `priority`, `is_pinned`, `is_private`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 10, 'Ullam laborum Aut p', 'Rerum doloribus magn', 'normal', 1, 0, NULL, '2025-10-11 16:14:11', NULL),
(3, 10, 'Ut dolores cupidatat', 'Aut in soluta facili', 'high', 1, 0, NULL, '2025-10-11 16:19:59', NULL),
(4, 7, 'Quia et quis velit c', 'Porro voluptatem dol', 'normal', 1, 0, NULL, '2025-10-11 17:34:21', NULL),
(0, 27, 'Registration Approved', 'Partner registration has been approved by admin.', 'normal', 1, 0, 1, '2025-10-28 06:32:47', NULL),
(0, 27, 'Registration Approved', 'Partner registration has been approved by admin.', 'normal', 1, 0, 1, '2025-10-28 06:32:47', NULL),
(0, 27, 'Registration Approved', 'Partner registration has been approved by admin.', 'normal', 1, 0, 1, '2025-10-28 06:32:47', NULL),
(0, 26, 'Registration Approved', 'Partner registration has been approved by admin.', 'normal', 1, 0, 1, '2025-10-28 06:32:59', NULL),
(0, 35, 'Permissions Updated', 'Partner permissions have been updated by admin. Granted: 6 permissions.', 'normal', 0, 0, 1, '2025-11-03 11:32:25', NULL),
(0, 27, 'Permissions Updated', 'Partner permissions have been updated by admin. Granted: 3 permissions.', 'normal', 0, 0, 1, '2025-11-03 13:07:44', NULL),
(0, 27, 'Permissions Updated', 'Partner permissions have been updated by admin. Granted: 6 permissions.', 'normal', 0, 0, 1, '2025-11-03 13:24:39', NULL),
(0, 32, 'Registration Approved', 'Partner registration has been approved by admin.', 'normal', 1, 0, 1, '2025-11-03 14:07:26', NULL),
(0, 27, 'Permissions Updated', 'Partner permissions have been updated by admin. Granted: 8 permissions.', 'normal', 0, 0, 1, '2025-11-03 15:13:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_permissions`
--

CREATE TABLE `partner_permissions` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `permission_code` varchar(50) NOT NULL,
  `is_granted` tinyint(1) NOT NULL DEFAULT 0,
  `granted_by` int(11) DEFAULT NULL,
  `granted_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_permissions`
--

INSERT INTO `partner_permissions` (`id`, `partner_id`, `permission_code`, `is_granted`, `granted_by`, `granted_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(0, 0, 'library', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 0, 'online_courses', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 0, 'download_centre', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 0, 'gmeet', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 0, 'zoom', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 0, 'events_access', 1, 1, '2025-11-03 14:52:51', NULL, '2025-11-03 14:52:51', NULL),
(0, 27, 'download_centre', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'events_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'events_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'events_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'gmeet_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'library_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'online_courses', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL),
(0, 27, 'zoom_access', 1, 1, '2025-11-03 17:13:14', NULL, '2025-11-03 15:13:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_permission_types`
--

CREATE TABLE `partner_permission_types` (
  `id` int(11) NOT NULL,
  `permission_code` varchar(50) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_permission_types`
--

INSERT INTO `partner_permission_types` (`id`, `permission_code`, `permission_name`, `description`, `is_active`, `created_at`) VALUES
(1, 'library_access', 'Library Access', 'Access to school library', 1, '2025-09-30 18:08:33'),
(2, 'online_courses', 'Online Courses', 'Access to online learning platform', 1, '2025-09-30 18:08:33'),
(3, 'download_centre', 'Download Centre', 'Access to download resources', 1, '2025-09-30 18:08:33'),
(4, 'events_access', 'Events Access', 'Access to school events', 1, '2025-09-30 18:08:33'),
(5, 'gmeet_access', 'GMeet Access', 'Access to Google Meet sessions', 1, '2025-09-30 18:08:33'),
(6, 'zoom_access', 'Zoom Access', 'Access to Zoom sessions', 1, '2025-09-30 18:08:33'),
(0, 'events_access', 'Events Access', 'Access to school events and calendar', 1, '2025-11-03 13:38:49'),
(0, 'events_access', 'Events Access', 'Access to school events and calendar', 1, '2025-11-03 14:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `partner_reminders`
--

CREATE TABLE `partner_reminders` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `reminder_date` date NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `reminder_type` enum('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due',
  `send_via` enum('email','sms','both') DEFAULT 'email',
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `is_active` tinyint(1) DEFAULT 1,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_reminders`
--

INSERT INTO `partner_reminders` (`id`, `partner_id`, `title`, `message`, `reminder_date`, `reminder_time`, `reminder_type`, `send_via`, `status`, `is_active`, `sent_at`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 7, '', 'Non magna eligendi e', '1992-04-20', '14:35:00', 'meeting', 'email', 'pending', 1, NULL, NULL, '2025-10-11 17:34:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_reminder_templates`
--

CREATE TABLE `partner_reminder_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `reminder_type` enum('contribution','follow_up','renewal','other') NOT NULL DEFAULT 'contribution',
  `timing` enum('before','after') NOT NULL DEFAULT 'before',
  `days_before` int(11) DEFAULT NULL,
  `days_after` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `partner_reminder_templates`
--

INSERT INTO `partner_reminder_templates` (`id`, `template_name`, `reminder_type`, `timing`, `days_before`, `days_after`, `subject`, `message`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Contribution Due - 7 Days Before', 'contribution', 'before', 7, NULL, 'Upcoming Contribution Due', 'Dear Partner,\n\nThis is a friendly reminder that your contribution is due in 7 days.\n\nThank you for your continued support.', 1, '2025-11-03 15:21:41', NULL),
(2, 'Contribution Due - 3 Days Before', 'contribution', 'before', 3, NULL, 'Contribution Due Soon', 'Dear Partner,\n\nYour contribution is due in 3 days. Please ensure payment is made on time.', 1, '2025-11-03 15:21:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partner_sessions`
--

CREATE TABLE `partner_sessions` (
  `id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_sessions`
--

INSERT INTO `partner_sessions` (`id`, `partner_id`, `session_id`, `ip_address`, `user_agent`, `last_activity`, `created_at`) VALUES
(1, 1, 'erjrcbinbqa4mcvlp2b9j3dl41bh37sg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 19:05:18', '2025-10-02 19:05:18'),
(2, 1, 'jbtjq62iu6brcpmqe0dq0d6jf044pfhf', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-06 13:48:49', '2025-10-06 13:48:49'),
(3, 1, 'narua7eupkd3o9uvqjoe00v0rs6nrc9o', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 15:07:43', '2025-10-10 15:07:43'),
(4, 1, '5n8tk5oslj5tcbmtueb1p7u0hla713gr', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 18:22:46', '2025-10-11 18:22:46'),
(5, 1, 'vgro4hkhib3pog055v9dtv0cgie2poob', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 22:31:01', '2025-10-11 22:31:01'),
(6, 1, 'fppe1c2h7qh3r0j8kuk7adgvjd4q73sh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-12 15:18:03', '2025-10-12 15:18:03'),
(7, 1, 'r5glsb9gadg7kkq1030aliovspm6q757', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-13 00:51:26', '2025-10-13 00:51:26'),
(8, 1, 'p4u6s9tgca10bb9u5m10ug5j9id3bcuk', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-16 16:17:00', '2025-10-16 16:17:00'),
(9, 1, '1l0bq344u789qgf0rrdt0j8km0s47tsi', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-16 16:24:09', '2025-10-16 16:24:09'),
(10, 1, '1ntusl27qh1c6cgbbb5a1ocbvjofufrn', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-16 17:42:24', '2025-10-16 17:42:24'),
(11, 1, 'paqe3moetqrp8d4j3gn26p6e7q4ulfnh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-16 18:13:11', '2025-10-16 18:13:11'),
(0, 32, 'nbgfl2f78vg75c1tt8igkdk9k8i400m2', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 05:52:05', '2025-11-03 05:52:05'),
(0, 32, 'pn5vjh43of0j0jvq0m4rjotkhqeb26hh', '102.212.181.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 05:54:00', '2025-11-03 05:54:00'),
(0, 27, '2ebdej55ogdfnlh0dr73s569apghflv4', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 06:12:34', '2025-11-03 06:12:34'),
(0, 32, 'dj5mop0pekrssj3aof0g7c0dh2ac8dls', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 06:13:50', '2025-11-03 06:13:50'),
(0, 32, 'p89gp5i2pavc6sheuelh77lsnv04esak', '216.234.213.27', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 06:16:02', '2025-11-03 06:16:02'),
(0, 27, 'tufrc7qiii2h2m4gpgb9b4te40jcupgd', '59.103.32.168', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 06:16:19', '2025-11-03 06:16:19'),
(0, 32, 'soqur5k56h8pr0gfu94dc17pv11vk8ld', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 06:18:04', '2025-11-03 06:18:04'),
(0, 27, 'puer6bjlkmvjl37bdp9ehd2r7borb19b', '102.212.181.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 06:19:41', '2025-11-03 06:19:41'),
(0, 33, 'qtg8nhtefvsbjtk23ukoefi654afk2mp', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 06:22:17', '2025-11-03 06:22:17'),
(0, 34, 'ehklqn3rsjum8rpfaa9sdk2e0e80590r', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 06:30:00', '2025-11-03 06:30:00'),
(0, 35, 'h822tl8clpducdiphvok416pse2ulrf8', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 06:33:00', '2025-11-03 06:33:00'),
(0, 37, 'hh8q4d14t5dgg72qs8ll34rv9t4qk687', '41.216.87.8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 10:38:37', '2025-11-03 10:38:37'),
(0, 35, 'dgbtk97t9prsk9lsl8ctsk2i7q2fdhkp', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 11:33:14', '2025-11-03 11:33:14'),
(0, 27, 'b5048bvjue7f219qo9k6tj1o57chgpha', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:53:33', '2025-11-03 12:53:33'),
(0, 27, 'sisujoca2t0lju4s1atv2m5gmq8ratt4', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 13:25:29', '2025-11-03 13:25:29'),
(0, 32, 'oqade9fl8iu8m8v73j7cn0462ce7s9l7', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 13:29:42', '2025-11-03 13:29:42'),
(0, 27, '71jlt9632ne1cid1jbpqktc79nhc23qi', '59.103.32.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 13:40:04', '2025-11-03 13:40:04'),
(0, 32, 'sjoabhi42ug45or66cfrpg615u3nvvd9', '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-03 13:45:17', '2025-11-03 13:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `api_username` varchar(200) DEFAULT NULL,
  `api_secret_key` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL,
  `api_publishable_key` varchar(200) NOT NULL,
  `api_password` varchar(200) DEFAULT NULL,
  `api_signature` varchar(200) DEFAULT NULL,
  `api_email` varchar(200) DEFAULT NULL,
  `paypal_demo` varchar(100) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `gateway_mode` int(11) NOT NULL COMMENT '0 Testing, 1 live',
  `paytm_website` varchar(255) NOT NULL,
  `paytm_industrytype` varchar(255) NOT NULL,
  `charge_type` varchar(255) DEFAULT NULL,
  `charge_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `payment_type`, `api_username`, `api_secret_key`, `salt`, `api_publishable_key`, `api_password`, `api_signature`, `api_email`, `paypal_demo`, `account_no`, `is_active`, `gateway_mode`, `paytm_website`, `paytm_industrytype`, `charge_type`, `charge_value`, `created_at`, `updated_at`) VALUES
(1, 'dpopay', NULL, 'f', '', '', NULL, NULL, NULL, '', '', 'yes', 0, '', '', 'none', '', '2025-05-26 05:20:08', '2025-05-26 06:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_allowance`
--

CREATE TABLE `payslip_allowance` (
  `id` int(11) NOT NULL,
  `payslip_id` int(11) NOT NULL,
  `allowance_type` varchar(200) NOT NULL,
  `amount` float NOT NULL,
  `staff_id` int(11) NOT NULL,
  `cal_type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_category`
--

CREATE TABLE `permission_category` (
  `id` int(11) NOT NULL,
  `perm_group_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) DEFAULT NULL,
  `enable_view` int(11) DEFAULT 0,
  `enable_add` int(11) DEFAULT 0,
  `enable_edit` int(11) DEFAULT 0,
  `enable_delete` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `permission_category`
--

INSERT INTO `permission_category` (`id`, `perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'Student', 'student', 1, 1, 1, 1, '2019-10-24 05:42:03', '2025-05-10 12:46:08'),
(2, 1, 'Import Student', 'import_student', 1, 0, 0, 0, '2018-06-22 10:17:19', '2025-05-10 12:46:08'),
(3, 1, 'Student Categories', 'student_categories', 1, 1, 1, 1, '2018-06-22 10:17:36', '2025-05-10 12:46:08'),
(4, 1, 'Student Houses', 'student_houses', 1, 1, 1, 1, '2018-06-22 10:17:53', '2025-05-10 12:46:08'),
(5, 2, 'Collect Fees', 'collect_fees', 1, 1, 0, 1, '2018-06-22 10:21:03', '2025-05-10 12:46:08'),
(6, 2, 'Fees Carry Forward', 'fees_carry_forward', 1, 0, 0, 0, '2018-06-27 00:18:15', '2025-05-10 12:46:08'),
(7, 2, 'Fees Master', 'fees_master', 1, 1, 1, 1, '2018-06-27 00:18:57', '2025-05-10 12:46:08'),
(8, 2, 'Fees Group', 'fees_group', 1, 1, 1, 1, '2018-06-22 10:21:46', '2025-05-10 12:46:08'),
(9, 3, 'Income', 'income', 1, 1, 1, 1, '2018-06-22 10:23:21', '2025-05-10 12:46:08'),
(10, 3, 'Income Head', 'income_head', 1, 1, 1, 1, '2018-06-22 10:22:44', '2025-05-10 12:46:08'),
(11, 3, 'Search Income', 'search_income', 1, 0, 0, 0, '2018-06-22 10:23:00', '2025-05-10 12:46:08'),
(12, 4, 'Expense', 'expense', 1, 1, 1, 1, '2018-06-22 10:24:06', '2025-05-10 12:46:08'),
(13, 4, 'Expense Head', 'expense_head', 1, 1, 1, 1, '2018-06-22 10:23:47', '2025-05-10 12:46:08'),
(14, 4, 'Search Expense', 'search_expense', 1, 0, 0, 0, '2018-06-22 10:24:13', '2025-05-10 12:46:08'),
(15, 5, 'Student / Period Attendance', 'student_attendance', 1, 1, 1, 0, '2019-11-29 01:19:05', '2025-05-10 12:46:08'),
(20, 6, 'Marks Grade', 'marks_grade', 1, 1, 1, 1, '2018-06-22 10:25:25', '2025-05-10 12:46:08'),
(21, 7, 'Class Timetable', 'class_timetable', 1, 0, 1, 0, '2019-11-24 03:05:17', '2025-05-10 12:46:08'),
(23, 7, 'Subject', 'subject', 1, 1, 1, 1, '2018-06-22 10:32:17', '2025-05-10 12:46:08'),
(24, 7, 'Class', 'class', 1, 1, 1, 1, '2018-06-22 10:32:35', '2025-05-10 12:46:08'),
(25, 7, 'Section', 'section', 1, 1, 1, 1, '2018-06-22 10:31:10', '2025-05-10 12:46:08'),
(26, 7, 'Promote Student', 'promote_student', 1, 0, 0, 0, '2018-06-22 10:32:47', '2025-05-10 12:46:08'),
(27, 8, 'Upload Content', 'upload_content', 1, 1, 0, 1, '2018-06-22 10:33:19', '2025-05-10 12:46:08'),
(28, 9, 'Books List', 'books', 1, 1, 1, 1, '2019-11-24 00:37:12', '2025-05-10 12:46:08'),
(29, 9, 'Issue Return', 'issue_return', 1, 0, 0, 0, '2019-11-24 00:37:18', '2025-05-10 12:46:08'),
(30, 9, 'Add Staff Member', 'add_staff_member', 1, 0, 0, 0, '2018-07-02 11:37:00', '2025-05-10 12:46:08'),
(31, 10, 'Issue Item', 'issue_item', 1, 1, 1, 1, '2019-11-29 06:39:27', '2025-05-10 12:46:08'),
(32, 10, 'Add Item Stock', 'item_stock', 1, 1, 1, 1, '2019-11-24 00:39:17', '2025-05-10 12:46:08'),
(33, 10, 'Add Item', 'item', 1, 1, 1, 1, '2019-11-24 00:39:39', '2025-05-10 12:46:08'),
(34, 10, 'Item Store', 'store', 1, 1, 1, 1, '2019-11-24 00:40:41', '2025-05-10 12:46:08'),
(35, 10, 'Item Supplier', 'supplier', 1, 1, 1, 1, '2019-11-24 00:40:49', '2025-05-10 12:46:08'),
(37, 11, 'Routes', 'routes', 1, 1, 1, 1, '2018-06-22 10:39:17', '2025-05-10 12:46:08'),
(38, 11, 'Vehicle', 'vehicle', 1, 1, 1, 1, '2018-06-22 10:39:36', '2025-05-10 12:46:08'),
(39, 11, 'Assign Vehicle', 'assign_vehicle', 1, 1, 1, 1, '2018-06-27 04:39:20', '2025-05-10 12:46:08'),
(40, 12, 'Hostel', 'hostel', 1, 1, 1, 1, '2018-06-22 10:40:49', '2025-05-10 12:46:08'),
(41, 12, 'Room Type', 'room_type', 1, 1, 1, 1, '2018-06-22 10:40:27', '2025-05-10 12:46:08'),
(42, 12, 'Hostel Rooms', 'hostel_rooms', 1, 1, 1, 1, '2018-06-25 06:23:03', '2025-05-10 12:46:08'),
(43, 13, 'Notice Board', 'notice_board', 1, 1, 1, 1, '2018-06-22 10:41:17', '2025-05-10 12:46:08'),
(44, 13, 'Email', 'email', 1, 0, 0, 0, '2019-11-26 05:20:37', '2025-05-10 12:46:08'),
(46, 13, 'Email / SMS Log', 'email_sms_log', 1, 0, 0, 0, '2018-06-22 10:41:23', '2025-05-10 12:46:08'),
(53, 15, 'Languages', 'languages', 0, 1, 0, 1, '2021-01-23 07:09:32', '2025-05-10 12:46:08'),
(54, 15, 'General Setting', 'general_setting', 1, 0, 1, 0, '2018-07-05 09:08:35', '2025-05-10 12:46:08'),
(55, 15, 'Session Setting', 'session_setting', 1, 1, 1, 1, '2018-06-22 10:44:15', '2025-05-10 12:46:08'),
(56, 15, 'Notification Setting', 'notification_setting', 1, 0, 1, 0, '2018-07-05 09:08:41', '2025-05-10 12:46:08'),
(57, 15, 'SMS Setting', 'sms_setting', 1, 0, 1, 0, '2018-07-05 09:08:47', '2025-05-10 12:46:08'),
(58, 15, 'Email Setting', 'email_setting', 1, 0, 1, 0, '2018-07-05 09:08:51', '2025-05-10 12:46:08'),
(59, 15, 'Front CMS Setting', 'front_cms_setting', 1, 0, 1, 0, '2018-07-05 09:08:55', '2025-05-10 12:46:08'),
(60, 15, 'Payment Methods', 'payment_methods', 1, 0, 1, 0, '2018-07-05 09:08:59', '2025-05-10 12:46:08'),
(61, 16, 'Menus', 'menus', 1, 1, 0, 1, '2018-07-09 03:50:06', '2025-05-10 12:46:08'),
(62, 16, 'Media Manager', 'media_manager', 1, 1, 0, 1, '2018-07-09 03:50:26', '2025-05-10 12:46:08'),
(63, 16, 'Banner Images', 'banner_images', 1, 1, 0, 1, '2018-06-22 10:46:02', '2025-05-10 12:46:08'),
(64, 16, 'Pages', 'pages', 1, 1, 1, 1, '2018-06-22 10:46:21', '2025-05-10 12:46:08'),
(65, 16, 'Gallery', 'gallery', 1, 1, 1, 1, '2018-06-22 10:47:02', '2025-05-10 12:46:08'),
(66, 16, 'Event', 'event', 1, 1, 1, 1, '2018-06-22 10:47:20', '2025-05-10 12:46:08'),
(67, 16, 'News', 'notice', 1, 1, 1, 1, '2018-07-03 08:39:34', '2025-05-10 12:46:08'),
(68, 2, 'Fees Group Assign', 'fees_group_assign', 1, 0, 0, 0, '2018-06-22 10:20:42', '2025-05-10 12:46:08'),
(69, 2, 'Fees Type', 'fees_type', 1, 1, 1, 1, '2018-06-22 10:19:34', '2025-05-10 12:46:08'),
(70, 2, 'Fees Discount', 'fees_discount', 1, 1, 1, 1, '2018-06-22 10:20:10', '2025-05-10 12:46:08'),
(71, 2, 'Fees Discount Assign', 'fees_discount_assign', 1, 0, 0, 0, '2018-06-22 10:20:17', '2025-05-10 12:46:08'),
(73, 2, 'Search Fees Payment', 'search_fees_payment', 1, 0, 0, 0, '2018-06-22 10:20:27', '2025-05-10 12:46:08'),
(74, 2, 'Search Due Fees', 'search_due_fees', 1, 0, 0, 0, '2018-06-22 10:20:35', '2025-05-10 12:46:08'),
(77, 7, 'Assign Class Teacher', 'assign_class_teacher', 1, 1, 1, 1, '2018-06-22 10:30:52', '2025-05-10 12:46:08'),
(78, 17, 'Admission Enquiry', 'admission_enquiry', 1, 1, 1, 1, '2018-06-22 10:51:24', '2025-05-10 12:46:08'),
(79, 17, 'Follow Up Admission Enquiry', 'follow_up_admission_enquiry', 1, 1, 0, 1, '2018-06-22 10:51:39', '2025-05-10 12:46:08'),
(80, 17, 'Visitor Book', 'visitor_book', 1, 1, 1, 1, '2018-06-22 10:48:58', '2025-05-10 12:46:08'),
(81, 17, 'Phone Call Log', 'phone_call_log', 1, 1, 1, 1, '2018-06-22 10:50:57', '2025-05-10 12:46:08'),
(82, 17, 'Postal Dispatch', 'postal_dispatch', 1, 1, 1, 1, '2018-06-22 10:50:21', '2025-05-10 12:46:08'),
(83, 17, 'Postal Receive', 'postal_receive', 1, 1, 1, 1, '2018-06-22 10:50:04', '2025-05-10 12:46:08'),
(84, 17, 'Complain', 'complaint', 1, 1, 1, 1, '2018-07-03 08:40:55', '2025-05-10 12:46:08'),
(85, 17, 'Setup Front Office', 'setup_font_office', 1, 1, 1, 1, '2025-05-10 12:46:08', '2025-05-10 12:46:08'),
(86, 18, 'Staff', 'staff', 1, 1, 1, 1, '2018-06-22 10:53:31', '2025-05-10 12:46:08'),
(87, 18, 'Disable Staff', 'disable_staff', 1, 0, 0, 0, '2018-06-22 10:53:12', '2025-05-10 12:46:08'),
(88, 18, 'Staff Attendance', 'staff_attendance', 1, 1, 1, 0, '2018-06-22 10:53:10', '2025-05-10 12:46:08'),
(90, 18, 'Staff Payroll', 'staff_payroll', 1, 1, 0, 1, '2018-06-22 10:52:51', '2025-05-10 12:46:08'),
(93, 19, 'Homework', 'homework', 1, 1, 1, 1, '2018-06-22 10:53:50', '2025-05-10 12:46:08'),
(94, 19, 'Homework Evaluation', 'homework_evaluation', 1, 1, 0, 0, '2018-06-27 03:07:21', '2025-05-10 12:46:08'),
(96, 20, 'Student Certificate', 'student_certificate', 1, 1, 1, 1, '2018-07-06 10:41:07', '2025-05-10 12:46:08'),
(97, 20, 'Generate Certificate', 'generate_certificate', 1, 0, 0, 0, '2018-07-06 10:37:16', '2025-05-10 12:46:08'),
(98, 20, 'Student ID Card', 'student_id_card', 1, 1, 1, 1, '2018-07-06 10:41:28', '2025-05-10 12:46:08'),
(99, 20, 'Generate ID Card', 'generate_id_card', 1, 0, 0, 0, '2018-07-06 10:41:49', '2025-05-10 12:46:08'),
(102, 21, 'Calendar To Do List', 'calendar_to_do_list', 1, 1, 1, 1, '2018-06-22 10:54:41', '2025-05-10 12:46:08'),
(104, 10, 'Item Category', 'item_category', 1, 1, 1, 1, '2018-06-22 10:34:33', '2025-05-10 12:46:08'),
(106, 22, 'Quick Session Change', 'quick_session_change', 1, 0, 0, 0, '2018-06-22 10:54:45', '2025-05-10 12:46:08'),
(107, 1, 'Disable Student', 'disable_student', 1, 0, 0, 0, '2018-06-25 06:21:34', '2025-05-10 12:46:08'),
(108, 18, ' Approve Leave Request', 'approve_leave_request', 1, 0, 1, 1, '2020-10-05 08:56:27', '2025-05-10 12:46:08'),
(109, 18, 'Apply Leave', 'apply_leave', 1, 1, 0, 0, '2019-11-28 23:47:46', '2025-05-10 12:46:08'),
(110, 18, 'Leave Types ', 'leave_types', 1, 1, 1, 1, '2018-07-02 10:17:56', '2025-05-10 12:46:08'),
(111, 18, 'Department', 'department', 1, 1, 1, 1, '2018-06-26 03:57:07', '2025-05-10 12:46:08'),
(112, 18, 'Designation', 'designation', 1, 1, 1, 1, '2018-06-26 03:57:07', '2025-05-10 12:46:08'),
(113, 22, 'Fees Collection And Expense Monthly Chart', 'fees_collection_and_expense_monthly_chart', 1, 0, 0, 0, '2018-07-03 07:08:15', '2025-05-10 12:46:08'),
(114, 22, 'Fees Collection And Expense Yearly Chart', 'fees_collection_and_expense_yearly_chart', 1, 0, 0, 0, '2018-07-03 07:08:15', '2025-05-10 12:46:08'),
(115, 22, 'Monthly Fees Collection Widget', 'Monthly fees_collection_widget', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(116, 22, 'Monthly Expense Widget', 'monthly_expense_widget', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(117, 22, 'Student Count Widget', 'student_count_widget', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(118, 22, 'Staff Role Count Widget', 'staff_role_count_widget', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(122, 5, 'Attendance By Date', 'attendance_by_date', 1, 0, 0, 0, '2018-07-03 08:42:29', '2025-05-10 12:46:08'),
(123, 9, 'Add Student', 'add_student', 1, 0, 0, 0, '2018-07-03 08:42:29', '2025-05-10 12:46:08'),
(126, 15, 'User Status', 'user_status', 1, 0, 0, 0, '2018-07-03 08:42:29', '2025-05-10 12:46:08'),
(127, 18, 'Can See Other Users Profile', 'can_see_other_users_profile', 1, 0, 0, 0, '2018-07-03 08:42:29', '2025-05-10 12:46:08'),
(128, 1, 'Student Timeline', 'student_timeline', 1, 1, 1, 1, '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(129, 18, 'Staff Timeline', 'staff_timeline', 1, 1, 1, 1, '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(130, 15, 'Backup', 'backup', 1, 1, 0, 1, '2018-07-09 04:17:17', '2025-05-10 12:46:08'),
(131, 15, 'Restore', 'restore', 1, 0, 0, 0, '2018-07-09 04:17:17', '2025-05-10 12:46:08'),
(134, 1, 'Disable Reason', 'disable_reason', 1, 1, 1, 1, '2019-11-27 06:39:21', '2025-05-10 12:46:08'),
(135, 2, 'Fees Reminder', 'fees_reminder', 1, 0, 1, 0, '2019-10-25 00:39:49', '2025-05-10 12:46:08'),
(136, 5, 'Approve Leave', 'approve_leave', 1, 1, 1, 1, '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(137, 6, 'Exam Group', 'exam_group', 1, 1, 1, 1, '2019-10-25 01:02:34', '2025-05-10 12:46:08'),
(141, 6, 'Design Admit Card', 'design_admit_card', 1, 1, 1, 1, '2019-10-25 01:06:59', '2025-05-10 12:46:08'),
(142, 6, 'Print Admit Card', 'print_admit_card', 1, 0, 0, 0, '2019-11-23 23:57:51', '2025-05-10 12:46:08'),
(143, 6, 'Design Marksheet', 'design_marksheet', 1, 1, 1, 1, '2019-10-25 01:10:25', '2025-05-10 12:46:08'),
(144, 6, 'Print Marksheet', 'print_marksheet', 1, 0, 0, 0, '2019-10-25 01:11:02', '2025-05-10 12:46:08'),
(145, 7, 'Teachers Timetable', 'teachers_time_table', 1, 0, 0, 0, '2019-11-30 02:52:21', '2025-05-10 12:46:08'),
(146, 14, 'Student Report', 'student_report', 1, 0, 0, 0, '2019-10-25 01:27:00', '2025-05-10 12:46:08'),
(147, 14, 'Guardian Report', 'guardian_report', 1, 0, 0, 0, '2019-10-25 01:30:27', '2025-05-10 12:46:08'),
(148, 14, 'Student History', 'student_history', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(149, 14, 'Student Login Credential Report', 'student_login_credential_report', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(150, 14, 'Class Subject Report', 'class_subject_report', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(151, 14, 'Admission Report', 'admission_report', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(152, 14, 'Sibling Report', 'sibling_report', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(153, 14, 'Homework Evaluation Report', 'homehork_evaluation_report', 1, 0, 0, 0, '2019-11-24 01:04:24', '2025-05-10 12:46:08'),
(154, 14, 'Student Profile', 'student_profile', 1, 0, 0, 0, '2019-10-25 01:39:07', '2025-05-10 12:46:08'),
(155, 14, 'Fees Statement', 'fees_statement', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(156, 14, 'Balance Fees Report', 'balance_fees_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(157, 14, 'Fees Collection Report', 'fees_collection_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(158, 14, 'Online Fees Collection Report', 'online_fees_collection_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(159, 14, 'Income Report', 'income_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(160, 14, 'Expense Report', 'expense_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(161, 14, 'PayRoll Report', 'payroll_report', 1, 0, 0, 0, '2019-10-31 00:23:22', '2025-05-10 12:46:08'),
(162, 14, 'Income Group Report', 'income_group_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(163, 14, 'Expense Group Report', 'expense_group_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(164, 14, 'Attendance Report', 'attendance_report', 1, 0, 0, 0, '2019-10-25 02:08:06', '2025-05-10 12:46:08'),
(165, 14, 'Staff Attendance Report', 'staff_attendance_report', 1, 0, 0, 0, '2019-10-25 02:08:06', '2025-05-10 12:46:08'),
(174, 14, 'Transport Report', 'transport_report', 1, 0, 0, 0, '2019-10-25 02:13:56', '2025-05-10 12:46:08'),
(175, 14, 'Hostel Report', 'hostel_report', 1, 0, 0, 0, '2019-11-27 06:51:53', '2025-05-10 12:46:08'),
(176, 14, 'Audit Trail Report', 'audit_trail_report', 1, 0, 0, 0, '2019-10-25 02:16:39', '2025-05-10 12:46:08'),
(177, 14, 'User Log', 'user_log', 1, 0, 0, 0, '2019-10-25 02:19:27', '2025-05-10 12:46:08'),
(178, 14, 'Book Issue Report', 'book_issue_report', 1, 0, 0, 0, '2019-10-25 02:29:04', '2025-05-10 12:46:08'),
(179, 14, 'Book Due Report', 'book_due_report', 1, 0, 0, 0, '2019-10-25 02:29:04', '2025-05-10 12:46:08'),
(180, 14, 'Book Inventory Report', 'book_inventory_report', 1, 0, 0, 0, '2019-10-25 02:29:04', '2025-05-10 12:46:08'),
(181, 14, 'Stock Report', 'stock_report', 1, 0, 0, 0, '2019-10-25 02:31:28', '2025-05-10 12:46:08'),
(182, 14, 'Add Item Report', 'add_item_report', 1, 0, 0, 0, '2019-10-25 02:31:28', '2025-05-10 12:46:08'),
(183, 14, 'Issue Item Report', 'issue_item_report', 1, 0, 0, 0, '2019-11-29 03:48:06', '2025-05-10 12:46:08'),
(185, 23, 'Online Examination', 'online_examination', 1, 1, 1, 1, '2019-11-23 23:54:50', '2025-05-10 12:46:08'),
(186, 23, 'Question Bank', 'question_bank', 1, 1, 1, 1, '2019-11-23 23:55:18', '2025-05-10 12:46:08'),
(187, 6, 'Exam Result', 'exam_result', 1, 0, 0, 0, '2019-11-23 23:58:50', '2025-05-10 12:46:08'),
(188, 7, 'Subject Group', 'subject_group', 1, 1, 1, 1, '2019-11-24 00:34:32', '2025-05-10 12:46:08'),
(189, 18, 'Teachers Rating', 'teachers_rating', 1, 0, 1, 1, '2019-11-24 03:12:54', '2025-05-10 12:46:08'),
(190, 22, 'Fees Awaiting Payment Widegts', 'fees_awaiting_payment_widegts', 1, 0, 0, 0, '2019-11-24 00:52:51', '2025-05-10 12:46:08'),
(191, 22, 'Converted Leads Widegts', 'conveted_leads_widegts', 1, 0, 0, 0, '2025-05-10 12:46:08', '2025-05-10 12:46:08'),
(192, 22, 'Fees Overview Widegts', 'fees_overview_widegts', 1, 0, 0, 0, '2019-11-24 00:57:41', '2025-05-10 12:46:08'),
(193, 22, 'Enquiry Overview Widegts', 'enquiry_overview_widegts', 1, 0, 0, 0, '2019-12-02 05:06:09', '2025-05-10 12:46:08'),
(194, 22, 'Library Overview Widegts', 'book_overview_widegts', 1, 0, 0, 0, '2019-12-01 01:13:04', '2025-05-10 12:46:08'),
(195, 22, 'Student Today Attendance Widegts', 'today_attendance_widegts', 1, 0, 0, 0, '2019-12-03 04:57:45', '2025-05-10 12:46:08'),
(196, 6, 'Marks Import', 'marks_import', 1, 0, 0, 0, '2019-11-24 01:02:11', '2025-05-10 12:46:08'),
(197, 14, 'Student Attendance Type Report', 'student_attendance_type_report', 1, 0, 0, 0, '2019-11-24 01:06:32', '2025-05-10 12:46:08'),
(198, 14, 'Exam Marks Report', 'exam_marks_report', 1, 0, 0, 0, '2019-11-24 01:11:15', '2025-05-10 12:46:08'),
(200, 14, 'Online Exam Wise Report', 'online_exam_wise_report', 1, 0, 0, 0, '2019-11-24 01:18:14', '2025-05-10 12:46:08'),
(201, 14, 'Online Exams Report', 'online_exams_report', 1, 0, 0, 0, '2019-11-29 02:48:05', '2025-05-10 12:46:08'),
(202, 14, 'Online Exams Attempt Report', 'online_exams_attempt_report', 1, 0, 0, 0, '2019-11-29 02:46:24', '2025-05-10 12:46:08'),
(203, 14, 'Online Exams Rank Report', 'online_exams_rank_report', 1, 0, 0, 0, '2019-11-24 01:22:25', '2025-05-10 12:46:08'),
(204, 14, 'Staff Report', 'staff_report', 1, 0, 0, 0, '2019-11-24 01:25:27', '2025-05-10 12:46:08'),
(205, 6, 'Exam', 'exam', 1, 1, 1, 1, '2019-11-24 04:55:48', '2025-05-10 12:46:08'),
(207, 6, 'Exam Publish', 'exam_publish', 1, 0, 0, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(208, 6, 'Link Exam', 'link_exam', 1, 0, 1, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(210, 6, 'Assign / View student', 'exam_assign_view_student', 1, 0, 1, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(211, 6, 'Exam Subject', 'exam_subject', 1, 0, 1, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(212, 6, 'Exam Marks', 'exam_marks', 1, 0, 1, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(213, 15, 'Language Switcher', 'language_switcher', 1, 0, 0, 0, '2019-11-24 05:17:11', '2025-05-10 12:46:08'),
(214, 23, 'Add Questions in Exam ', 'add_questions_in_exam', 1, 0, 1, 0, '2019-11-28 01:38:57', '2025-05-10 12:46:08'),
(215, 15, 'Custom Fields', 'custom_fields', 1, 0, 0, 0, '2019-11-29 04:08:35', '2025-05-10 12:46:08'),
(216, 15, 'System Fields', 'system_fields', 1, 0, 0, 0, '2019-11-25 00:15:01', '2025-05-10 12:46:08'),
(217, 13, 'SMS', 'sms', 1, 0, 0, 0, '2018-06-22 10:40:54', '2025-05-10 12:46:08'),
(219, 14, 'Student / Period Attendance Report', 'student_period_attendance_report', 1, 0, 0, 0, '2019-11-29 02:19:31', '2025-05-10 12:46:08'),
(220, 14, 'Biometric Attendance Log', 'biometric_attendance_log', 1, 0, 0, 0, '2019-11-27 05:59:16', '2025-05-10 12:46:08'),
(221, 14, 'Book Issue Return Report', 'book_issue_return_report', 1, 0, 0, 0, '2019-11-27 06:30:23', '2025-05-10 12:46:08'),
(222, 23, 'Assign / View Student', 'online_assign_view_student', 1, 0, 1, 0, '2019-11-28 04:20:22', '2025-05-10 12:46:08'),
(223, 14, 'Rank Report', 'rank_report', 1, 0, 0, 0, '2019-11-29 02:30:21', '2025-05-10 12:46:08'),
(224, 25, 'Chat', 'chat', 1, 0, 0, 0, '2019-11-29 04:10:28', '2025-05-10 12:46:08'),
(226, 22, 'Income Donut Graph', 'income_donut_graph', 1, 0, 0, 0, '2019-11-29 05:00:33', '2025-05-10 12:46:08'),
(227, 22, 'Expense Donut Graph', 'expense_donut_graph', 1, 0, 0, 0, '2019-11-29 05:01:10', '2025-05-10 12:46:08'),
(228, 9, 'Import Book', 'import_book', 1, 0, 0, 0, '2019-11-29 06:21:01', '2025-05-10 12:46:08'),
(229, 22, 'Staff Present Today Widegts', 'staff_present_today_widegts', 1, 0, 0, 0, '2019-11-29 06:48:00', '2025-05-10 12:46:08'),
(230, 22, 'Student Present Today Widegts', 'student_present_today_widegts', 1, 0, 0, 0, '2019-11-29 06:47:42', '2025-05-10 12:46:08'),
(231, 26, 'Multi Class Student', 'multi_class_student', 1, 1, 1, 1, '2020-10-05 08:56:27', '2025-05-10 12:46:08'),
(232, 27, 'Online Admission', 'online_admission', 1, 0, 1, 1, '2019-12-02 06:11:10', '2025-05-10 12:46:08'),
(233, 15, 'Print Header Footer', 'print_header_footer', 1, 0, 0, 0, '2020-02-12 02:02:02', '2025-05-10 12:46:08'),
(234, 28, 'Manage Alumni', 'manage_alumni', 1, 1, 1, 1, '2020-06-02 03:15:46', '2025-05-10 12:46:08'),
(235, 28, 'Events', 'events', 1, 1, 1, 1, '2020-05-28 21:48:52', '2025-05-10 12:46:08'),
(236, 29, 'Manage Lesson Plan', 'manage_lesson_plan', 1, 1, 1, 0, '2020-05-28 22:17:37', '2025-05-10 12:46:08'),
(237, 29, 'Manage Syllabus Status', 'manage_syllabus_status', 1, 0, 1, 0, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(238, 29, 'Lesson', 'lesson', 1, 1, 1, 1, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(239, 29, 'Topic', 'topic', 1, 1, 1, 1, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(240, 14, 'Syllabus Status Report', 'syllabus_status_report', 1, 0, 0, 0, '2020-05-28 23:17:54', '2025-05-10 12:46:08'),
(241, 14, 'Teacher Syllabus Status Report', 'teacher_syllabus_status_report', 1, 0, 0, 0, '2020-05-28 23:17:54', '2025-05-10 12:46:08'),
(242, 14, 'Alumni Report', 'alumni_report', 1, 0, 0, 0, '2020-06-07 23:59:54', '2025-05-10 12:46:08'),
(243, 15, 'Student Profile Update', 'student_profile_update', 1, 0, 0, 0, '2020-08-21 05:36:33', '2025-05-10 12:46:08'),
(244, 14, 'Student Gender Ratio Report', 'student_gender_ratio_report', 1, 0, 0, 0, '2020-08-22 12:37:51', '2025-05-10 12:46:08'),
(245, 14, 'Student Teacher Ratio Report', 'student_teacher_ratio_report', 1, 0, 0, 0, '2020-08-22 12:42:27', '2025-05-10 12:46:08'),
(246, 14, 'Daily Attendance Report', 'daily_attendance_report', 1, 0, 0, 0, '2020-08-22 12:43:16', '2025-05-10 12:46:08'),
(247, 23, 'Import Question', 'import_question', 1, 0, 0, 0, '2019-11-23 18:25:18', '2025-05-10 12:46:08'),
(248, 20, 'Staff ID Card', 'staff_id_card', 1, 1, 1, 1, '2018-07-06 10:41:28', '2025-05-10 12:46:08'),
(249, 20, 'Generate Staff ID Card', 'generate_staff_id_card', 1, 0, 0, 0, '2018-07-06 10:41:49', '2025-05-10 12:46:08'),
(250, 19, 'Daily Assignment', 'daily_assignment', 1, 0, 0, 0, '2022-03-02 07:28:23', '2025-05-10 12:46:08'),
(251, 6, 'Marks Division', 'marks_division', 1, 1, 1, 1, '2022-07-01 15:24:16', '2025-05-10 12:46:08'),
(252, 13, 'Schedule Email SMS Log', 'schedule_email_sms_log', 1, 0, 1, 0, '2022-07-09 11:25:16', '2025-05-10 12:46:08'),
(253, 13, 'Login Credentials Send', 'login_credentials_send', 1, 0, 0, 0, '2022-07-01 15:46:10', '2025-05-10 12:46:08'),
(254, 13, 'Email Template', 'email_template', 1, 1, 1, 1, '2022-07-01 15:46:10', '2025-05-10 12:46:08'),
(255, 13, 'SMS Template', 'sms_template', 1, 1, 1, 1, '2022-07-01 15:46:10', '2025-05-10 12:46:08'),
(256, 14, 'Balance Fees Report With Remark', 'balance_fees_report_with_remark', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(257, 14, 'Balance Fees Statement', 'balance_fees_statement', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(258, 14, 'Daily Collection Report', 'daily_collection_report', 1, 0, 0, 0, '2019-10-25 01:55:52', '2025-05-10 12:46:08'),
(259, 11, 'Fees Master', 'transport_fees_master', 1, 0, 1, 0, '2022-07-05 09:29:19', '2025-05-10 12:46:08'),
(260, 11, 'Pickup Point', 'pickup_point', 1, 1, 1, 1, '2022-07-04 09:50:08', '2025-05-10 12:46:08'),
(261, 11, 'Route Pickup Point', 'route_pickup_point', 1, 1, 1, 1, '2022-07-04 09:50:08', '2025-05-10 12:46:08'),
(262, 11, 'Student Transport Fees', 'student_transport_fees', 1, 1, 1, 0, '2022-07-05 10:15:55', '2025-05-10 12:46:08'),
(263, 29, 'Comments', 'lesson_plan_comments', 1, 1, 0, 1, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(264, 15, 'Sidebar Menu', 'sidebar_menu', 1, 0, 0, 0, '2022-07-11 12:01:17', '2025-05-10 12:46:08'),
(265, 15, 'Currency', 'currency', 1, 0, 0, 0, '2020-08-21 05:36:33', '2025-05-10 12:46:08'),
(266, 6, 'Exam Schedule', 'exam_schedule', 1, 0, 0, 0, '2019-11-23 23:58:50', '2025-05-10 12:46:08'),
(267, 6, 'Generate Rank', 'generate_rank', 1, 0, 0, 0, '2019-11-24 05:15:04', '2025-05-10 12:46:08'),
(268, 8, 'Content Type', 'content_type', 1, 1, 1, 1, '2022-07-08 05:18:54', '2025-05-10 12:46:08'),
(269, 8, 'Content Share List', 'content_share_list', 1, 0, 0, 1, '2022-07-08 05:18:58', '2025-05-10 12:46:08'),
(270, 8, 'Video Tutorial', 'video_tutorial', 1, 1, 1, 1, '2022-07-08 05:19:01', '2025-05-10 12:46:08'),
(271, 15, 'Currency Switcher', 'currency_switcher', 1, 0, 0, 0, '2019-11-24 05:17:11', '2025-05-10 12:46:08'),
(272, 2, 'Offline Bank Payments', 'offline_bank_payments', 1, 0, 0, 0, '2018-06-27 00:18:15', '2025-05-10 12:46:08'),
(273, 29, 'Copy Old Lessons', 'copy_old_lesson', 1, 0, 0, 0, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(274, 30, 'Annual Calendar', 'annual_calendar', 1, 1, 1, 1, '2020-05-28 22:20:11', '2025-05-10 12:46:08'),
(275, 30, 'Holiday Type', 'holiday_type', 1, 1, 1, 1, '2024-10-14 12:31:14', '2025-05-10 12:46:08'),
(276, 14, 'Online Admission Report', 'online_admission_report', 1, 0, 0, 0, '2020-08-22 12:42:27', '2025-05-10 12:46:08'),
(277, 31, 'Download CV', 'download_cv', 1, 0, 0, 0, '2024-12-10 11:06:30', '2025-05-10 12:46:08'),
(278, 31, 'Build CV', 'build_cv', 1, 1, 0, 1, '2024-12-13 07:05:10', '2025-05-10 12:46:08'),
(279, 31, 'Setting', 'download_cv_setting', 1, 0, 0, 0, '2024-12-10 11:06:30', '2025-05-10 12:46:08'),
(280, 22, 'Student Head Count Widget', 'student_head_count_widget', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(281, 22, 'Staff Approved Leave Widegts', 'staff_approved_leave_widegts', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(282, 22, 'Student Approved Leave Widegts', 'student_approved_leave_widegts', 1, 0, 0, 0, '2018-07-03 07:13:35', '2025-05-10 12:46:08'),
(5001, 500, 'Setting', 'setting', 1, 0, 1, 0, '2020-06-10 13:39:04', '2025-05-22 11:46:33'),
(5002, 500, 'Live Classes', 'live_classes', 1, 1, 0, 1, '2020-05-31 15:41:32', '2025-05-22 11:46:33'),
(5003, 500, 'Live Meeting', 'live_meeting', 1, 1, 0, 1, '2020-06-01 12:41:41', '2025-05-22 11:46:33'),
(5004, 500, 'Live Meeting Report', 'live_meeting_report', 1, 0, 0, 0, '2020-06-10 05:07:40', '2025-05-22 11:46:33'),
(5005, 500, 'Live Classes Report', 'live_classes_report', 1, 0, 0, 0, '2020-06-10 06:29:53', '2025-05-22 11:46:33'),
(6001, 600, 'Live Classes', 'gmeet_live_classes', 1, 1, 0, 1, '2020-09-22 10:03:29', '2025-05-22 11:46:36'),
(6002, 600, 'Live Meeting', 'gmeet_live_meeting', 1, 1, 0, 1, '2020-09-22 10:03:44', '2025-05-22 11:46:36'),
(6003, 600, 'Live Meeting Report', 'gmeet_live_meeting_report', 1, 0, 0, 0, '2020-09-22 10:03:57', '2025-05-22 11:46:36'),
(6004, 600, 'Live Classes Report', 'gmeet_live_classes_report', 1, 0, 0, 0, '2020-09-22 10:04:08', '2025-05-22 11:46:36'),
(6005, 600, 'Setting', 'gmeet_setting', 1, 0, 1, 0, '2020-09-22 10:04:08', '2025-05-22 11:46:36'),
(7001, 700, 'Online Course', 'online_course', 1, 1, 1, 1, '2019-11-23 18:25:18', '2025-05-22 11:46:41'),
(7002, 700, 'Course Publish', 'course_publish', 1, 0, 0, 0, '2019-11-23 18:25:18', '2025-05-22 11:46:41'),
(7003, 700, 'Course Section', 'online_course_section', 1, 1, 1, 1, '2021-05-17 05:26:33', '2025-05-22 11:46:41'),
(7004, 700, 'Course Lesson', 'online_course_lesson', 1, 1, 1, 1, '2021-05-17 05:26:24', '2025-05-22 11:46:41'),
(7005, 700, 'Course Quiz', 'online_course_quiz', 1, 1, 1, 1, '2021-05-17 05:26:20', '2025-05-22 11:46:41'),
(7006, 700, 'Offline Payment', 'online_course_offline_payment', 1, 1, 0, 0, '2021-05-17 05:26:17', '2025-05-22 11:46:41'),
(7007, 700, 'Student Course Purchase Report', 'student_course_purchase_report', 1, 0, 0, 0, '2021-05-17 05:25:56', '2025-05-22 11:46:41'),
(7008, 700, 'Course Sell Count Report', 'course_sell_count_report', 1, 0, 0, 0, '2021-05-17 05:25:52', '2025-05-22 11:46:41'),
(7009, 700, 'Course Trending Report', 'course_trending_report', 1, 0, 0, 0, '2021-05-17 05:25:49', '2025-05-22 11:46:41'),
(7010, 700, 'Course Complete Report', 'course_complete_report', 1, 0, 0, 0, '2021-05-17 05:25:42', '2025-05-22 11:46:41'),
(7011, 700, 'Setting', 'online_course_setting', 1, 0, 0, 0, '2021-05-17 05:25:37', '2025-05-22 11:46:41'),
(7012, 700, 'Course Rating Report', 'course_rating_report', 1, 0, 0, 1, '2022-06-14 11:24:57', '2025-05-22 11:46:41'),
(7013, 700, 'Guest Report', 'guest_report', 1, 0, 1, 1, '2022-06-14 11:33:09', '2025-05-22 11:46:41'),
(7014, 700, 'Course Category', 'course_category', 1, 1, 1, 1, '2019-11-23 18:25:18', '2025-05-22 11:46:41'),
(7015, 700, 'Question Bank', 'online_course_question_bank', 1, 1, 1, 1, '2024-12-24 06:47:52', '2025-05-22 11:46:49'),
(7016, 700, 'Question Tag', 'online_course_question_tag', 1, 1, 1, 1, '2024-12-24 06:47:59', '2025-05-22 11:46:49'),
(7017, 700, 'Course Exam', 'online_course_exam', 1, 1, 1, 1, '2021-05-17 05:26:24', '2025-05-22 11:46:49'),
(7018, 700, 'Course Assignment', 'online_course_assignment', 1, 1, 1, 1, '2021-05-17 05:26:33', '2025-05-22 11:46:49'),
(7019, 700, 'Course Add Questions in Exam ', 'online_course_add_questions_in_exam', 1, 0, 1, 1, '2024-12-25 11:48:07', '2025-05-22 11:46:49'),
(7020, 700, 'Course Evalute Assignment', 'online_course_evalute_assignment', 1, 1, 0, 0, '2024-12-25 10:16:20', '2025-05-22 11:46:49'),
(7021, 700, 'Course Assignment Report', 'course_assignment_report', 1, 0, 0, 0, '2025-01-06 11:16:36', '2025-05-22 11:46:49'),
(7022, 700, 'Course Exam Result Report', 'course_exam_result_report', 1, 0, 0, 0, '2025-01-06 11:16:33', '2025-05-22 11:46:49'),
(7023, 700, 'Course Exam Report', 'course_exam_report', 1, 0, 0, 0, '2025-01-06 11:16:27', '2025-05-22 11:46:49'),
(7024, 700, 'Course Exam Attempt Report', 'course_exam_attempt_report', 1, 0, 0, 0, '2025-01-06 11:16:24', '2025-05-22 11:46:49'),
(8001, 800, 'Behaviour Records Assign Incident', 'behaviour_records_assign_incident', 1, 1, 0, 1, '2022-05-03 08:56:15', '2025-05-22 11:46:53'),
(8002, 800, 'Behaviour Records Incident', 'behaviour_records_incident', 1, 1, 1, 1, '2022-05-03 07:25:10', '2025-05-22 11:46:53'),
(8003, 800, 'Student Incident Report', 'student_incident_report', 1, 0, 0, 0, '2022-05-03 07:24:48', '2025-05-22 11:46:53'),
(8004, 800, 'Student Behaviour Rank Report', 'student_behaviour_rank_report', 1, 0, 0, 0, '2022-05-03 07:24:45', '2025-05-22 11:46:53'),
(8005, 800, 'Class Wise Rank Report', 'class_wise_rank_report', 1, 0, 0, 0, '2022-05-03 07:24:37', '2025-05-22 11:46:53'),
(8006, 800, 'Class Section Wise Rank Report', 'class_section_wise_rank_report', 1, 0, 0, 0, '2022-05-03 07:24:33', '2025-05-22 11:46:53'),
(8007, 800, 'House Wise Rank Report', 'house_wise_rank_report', 1, 0, 0, 0, '2022-05-03 07:24:30', '2025-05-22 11:46:53'),
(8008, 800, 'Incident Wise Report', 'incident_wise_report', 1, 0, 0, 0, '2022-05-03 07:24:26', '2025-05-22 11:46:53'),
(8009, 800, 'Behaviour Records Setting', 'behaviour_records_setting', 1, 0, 1, 0, '2022-05-03 07:24:05', '2025-05-22 11:46:53'),
(9001, 900, 'CBSE Exam', 'cbse_exam', 1, 1, 1, 1, '2022-11-03 08:58:30', '2025-05-22 11:47:06'),
(9002, 900, 'CBSE Exam Schedule', 'cbse_exam_schedule', 1, 0, 0, 0, '2023-05-09 11:01:34', '2025-05-22 11:47:06'),
(9003, 900, 'CBSE Exam Assign / View Student', 'cbse_exam_assign_view_student', 1, 0, 1, 0, '2022-11-03 09:18:15', '2025-05-22 11:47:06'),
(9004, 900, 'CBSE Exam Subjects', 'cbse_exam_subjects', 1, 0, 1, 0, '2022-11-04 08:01:41', '2025-05-22 11:47:06'),
(9005, 900, 'CBSE Exam Marks', 'cbse_exam_marks', 1, 0, 1, 0, '2022-11-03 09:18:24', '2025-05-22 11:47:06'),
(9006, 900, 'CBSE Exam Attendance', 'cbse_exam_attendance', 1, 0, 1, 0, '2022-11-03 09:18:28', '2025-05-22 11:47:06'),
(9007, 900, 'CBSE Exam Teacher Remark', 'cbse_exam_teacher_remark', 1, 0, 1, 0, '2022-11-03 09:18:32', '2025-05-22 11:47:06'),
(9008, 900, 'CBSE Exam Print Marksheet', 'cbse_exam_print_marksheet', 1, 0, 0, 0, '2022-11-03 09:18:43', '2025-05-22 11:47:06'),
(9009, 900, 'CBSE Exam Grade', 'cbse_exam_grade', 1, 1, 1, 1, '2022-11-03 09:18:46', '2025-05-22 11:47:06'),
(9010, 900, 'CBSE Exam Assign Observation', 'cbse_exam_assign_observation', 1, 1, 1, 1, '2023-05-08 12:33:23', '2025-05-22 11:47:06'),
(9011, 900, 'CBSE Exam Observation', 'cbse_exam_observation', 1, 1, 1, 1, '2023-05-09 10:57:16', '2025-05-22 11:47:06'),
(9012, 900, 'CBSE Exam Observation Parameter', 'cbse_exam_observation_parameter', 1, 1, 1, 1, '2023-05-09 11:01:54', '2025-05-22 11:47:06'),
(9013, 900, 'CBSE Exam Assessment', 'cbse_exam_assessment', 1, 1, 1, 1, '2023-05-09 11:01:51', '2025-05-22 11:47:06'),
(9014, 900, 'CBSE Exam Term', 'cbse_exam_term', 1, 1, 1, 1, '2023-05-09 11:01:47', '2025-05-22 11:47:06'),
(9015, 900, 'CBSE Exam Template', 'cbse_exam_template', 1, 1, 1, 1, '2023-05-09 11:01:43', '2025-05-22 11:47:06'),
(9016, 900, 'CBSE Exam Link Exam', 'cbse_exam_link_exam', 1, 0, 0, 0, '2023-05-09 11:01:40', '2025-05-22 11:47:06'),
(9017, 900, 'CBSE Exam Subject Marks Report', 'subject_marks_report', 1, 0, 0, 0, '2023-05-09 11:01:38', '2025-05-22 11:47:06'),
(9018, 900, 'CBSE Exam Template Marks Report', 'template_marks_report', 1, 0, 0, 0, '2023-05-09 11:01:34', '2025-05-22 11:47:06'),
(9019, 900, 'CBSE Exam Setting', 'cbse_exam_setting', 1, 0, 0, 0, '2023-07-03 05:24:57', '2025-05-22 11:47:06'),
(9020, 900, 'CBSE Exam Generate Rank', 'cbse_exam_generate_rank', 1, 0, 0, 0, '2023-07-03 05:24:57', '2025-05-22 11:47:06'),
(10001, 1000, 'Overview', 'multi_branch_overview', 1, 0, 0, 0, '2022-11-15 05:07:36', '2025-05-22 11:46:56'),
(10002, 1000, 'Daily Collection Report', 'multi_branch_daily_collection_report', 1, 0, 0, 0, '2022-11-15 04:57:02', '2025-05-22 11:46:56'),
(10003, 1000, 'Payroll Report', 'multi_branch_payroll', 1, 0, 0, 0, '2022-11-16 11:19:48', '2025-05-22 11:46:56'),
(10004, 1000, 'Income Report', 'multi_branch_income_report', 1, 0, 0, 0, '2022-11-15 05:07:36', '2025-05-22 11:46:56'),
(10005, 1000, 'Expense Report', 'multi_branch_expense_report', 1, 0, 0, 0, '2022-11-15 05:02:27', '2025-05-22 11:46:56'),
(10006, 1000, 'User Log Report', 'multi_branch_user_log_report', 1, 0, 0, 0, '2022-11-15 05:02:27', '2025-05-22 11:46:56'),
(10007, 1000, 'Setting', 'multi_branch_setting', 1, 0, 0, 0, '2022-11-15 05:07:36', '2025-05-22 11:46:56'),
(11001, 1100, 'Setting', 'google_authenticate_setting', 1, 0, 0, 0, '2018-07-06 10:41:49', '2025-05-22 11:47:02'),
(11002, 1100, 'Setup 2FA', 'google_authenticate_setup_two_fa', 1, 0, 0, 0, '2018-07-06 10:41:49', '2025-05-22 11:47:02'),
(12001, 1200, 'Attendance', 'qr_code_attendance', 1, 0, 0, 0, '2023-12-11 12:34:11', '2025-05-22 11:47:17'),
(12002, 1200, 'Setting', 'qr_code_setting', 1, 0, 0, 0, '2023-12-11 12:34:25', '2025-05-22 11:47:17'),
(13001, 1300, 'Quick Fees Create', 'quick_fees', 1, 1, 0, 1, '2024-12-18 08:12:35', '2025-05-22 11:47:24'),
(14001, 1400, 'Thermal Print', 'thermal_print', 1, 0, 0, 0, '2024-12-18 08:12:35', '2025-05-22 11:47:33'),
(14002, 1401, 'Partners', 'partners', 1, 1, 1, 1, '2025-10-10 11:31:11', '2025-10-10 11:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `permission_group`
--

CREATE TABLE `permission_group` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) NOT NULL,
  `is_active` int(11) DEFAULT 0,
  `system` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `permission_group`
--

INSERT INTO `permission_group` (`id`, `name`, `short_code`, `is_active`, `system`, `created_at`, `updated_at`) VALUES
(1, 'Student Information', 'student_information', 1, 1, '2019-03-15 09:30:22', '2025-05-10 12:46:08'),
(2, 'Fees Collection', 'fees_collection', 1, 0, '2020-06-11 00:51:35', '2025-05-10 12:46:08'),
(3, 'Income', 'income', 1, 0, '2020-06-01 01:57:39', '2025-05-10 12:46:08'),
(4, 'Expense', 'expense', 1, 0, '2019-03-15 09:06:22', '2025-05-10 12:46:08'),
(5, 'Student Attendance', 'student_attendance', 1, 0, '2018-07-02 07:48:08', '2025-05-10 12:46:08'),
(6, 'Examination', 'examination', 1, 0, '2018-07-11 02:49:08', '2025-06-12 07:23:49'),
(7, 'Academics', 'academics', 1, 1, '2018-07-02 07:25:43', '2025-05-10 12:46:08'),
(8, 'Download Center', 'download_center', 1, 0, '2018-07-02 07:49:29', '2025-05-10 12:46:08'),
(9, 'Library', 'library', 1, 0, '2018-06-28 11:13:14', '2025-06-04 08:28:00'),
(10, 'Inventory', 'inventory', 0, 0, '2018-06-27 00:48:58', '2025-05-29 12:36:00'),
(11, 'Transport', 'transport', 1, 0, '2018-06-27 07:51:26', '2025-05-30 06:04:56'),
(12, 'Hostel', 'hostel', 1, 0, '2018-07-02 07:49:32', '2025-06-18 08:13:23'),
(13, 'Communicate', 'communicate', 1, 0, '2018-07-02 07:50:00', '2025-05-10 12:46:08'),
(14, 'Reports', 'reports', 1, 1, '2018-06-27 03:40:22', '2025-05-10 12:46:08'),
(15, 'System Settings', 'system_settings', 1, 1, '2018-06-27 03:40:28', '2025-05-10 12:46:08'),
(16, 'Front CMS', 'front_cms', 1, 0, '2018-07-10 05:16:54', '2025-05-10 12:46:08'),
(17, 'Front Office', 'front_office', 1, 0, '2018-06-27 03:45:30', '2025-05-10 12:46:08'),
(18, 'Human Resource', 'human_resource', 1, 1, '2018-06-27 03:41:02', '2025-05-10 12:46:08'),
(19, 'Homework', 'homework', 1, 0, '2018-06-27 00:49:38', '2025-06-07 17:23:58'),
(20, 'Certificate', 'certificate', 1, 0, '2018-06-27 07:51:29', '2025-05-10 12:46:08'),
(21, 'Calendar To Do List', 'calendar_to_do_list', 1, 0, '2019-03-15 09:06:25', '2025-05-10 12:46:08'),
(22, 'Dashboard and Widgets', 'dashboard_and_widgets', 1, 1, '2018-06-27 03:41:17', '2025-05-10 12:46:08'),
(23, 'Online Examination', 'online_examination', 1, 0, '2020-06-01 02:25:36', '2025-06-07 17:23:46'),
(25, 'Chat', 'chat', 1, 0, '2019-11-23 23:54:04', '2025-05-10 12:46:08'),
(26, 'Multi Class', 'multi_class', 1, 0, '2019-11-27 12:14:14', '2025-05-10 12:46:08'),
(27, 'Online Admission', 'online_admission', 1, 0, '2019-11-27 02:42:13', '2025-05-10 12:46:08'),
(28, 'Alumni', 'alumni', 1, 0, '2020-05-29 00:26:38', '2025-06-07 17:23:27'),
(29, 'Lesson Plan', 'lesson_plan', 1, 0, '2020-06-07 05:38:30', '2025-06-07 17:23:38'),
(30, 'Annual Calendar', 'annual_calendar', 1, 0, '2024-10-22 10:45:56', '2025-05-10 12:46:08'),
(31, 'Student CV', 'student_cv', 1, 0, '2024-12-13 11:54:57', '2025-05-10 12:46:08'),
(500, 'Zoom Live Classes', 'zoom_live_classes', 1, 0, '2020-06-10 13:37:23', '2025-06-18 08:13:34'),
(600, 'Gmeet Live Classes', 'gmeet_live_classes', 1, 0, '2020-11-12 13:37:03', '2025-06-18 08:13:39'),
(700, 'Online Course', 'online_course', 1, 0, '2021-05-15 11:35:53', '2025-06-04 09:40:06'),
(800, 'Behaviour Records', 'behaviour_records', 1, 0, '2022-05-03 10:20:33', '2025-05-22 11:46:52'),
(900, 'CBSE Examination', 'cbseexam', 1, 0, '2023-05-25 12:04:56', '2025-06-20 11:17:27'),
(1000, 'Multi Branch', 'multi_branch', 1, 0, '2022-11-17 10:53:36', '2025-05-22 11:46:56'),
(1100, 'Two Factor Authenticator', 'google_authenticator', 1, 0, '2022-11-17 10:53:36', '2025-05-22 11:47:02'),
(1200, 'QR Code Attendence', 'qr_code_attendence', 1, 0, '2023-12-13 09:21:57', '2025-05-22 11:47:17'),
(1300, 'Quick Fees Create', 'quick_fees', 1, 0, '2024-12-18 08:12:58', '2025-05-29 12:20:09'),
(1400, 'Thermal Print', 'thermal_print', 1, 0, '2025-01-10 05:36:34', '2025-05-22 11:47:33'),
(1401, 'Partners', 'partners', 1, 0, '2025-10-10 11:21:47', '2025-10-10 11:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `permission_student`
--

CREATE TABLE `permission_student` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) NOT NULL,
  `system` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `permission_student`
--

INSERT INTO `permission_student` (`id`, `name`, `short_code`, `system`, `student`, `parent`, `group_id`, `created_at`, `updated_at`) VALUES
(1, 'Fees', 'fees', 0, 1, 1, 2, '2020-06-11 00:51:35', '2025-05-10 12:46:08'),
(2, 'Class Timetable', 'class_timetable', 1, 1, 1, 7, '2020-05-30 19:57:50', '2025-05-10 12:46:08'),
(3, 'Homework', 'homework', 0, 1, 1, 19, '2020-06-01 02:49:14', '2025-06-07 17:23:58'),
(4, 'Download Center', 'download_center', 0, 1, 1, 8, '2020-06-01 02:52:49', '2025-05-10 12:46:08'),
(5, 'Attendance', 'attendance', 0, 1, 1, 5, '2020-06-01 02:57:18', '2025-05-10 12:46:08'),
(7, 'Examinations', 'examinations', 0, 1, 1, 6, '2020-06-01 02:59:50', '2025-06-12 07:23:49'),
(8, 'Notice Board', 'notice_board', 0, 1, 1, 13, '2020-06-01 03:00:35', '2025-05-10 12:46:08'),
(11, 'Library', 'library', 0, 1, 1, 9, '2020-06-01 03:02:37', '2025-06-04 08:28:00'),
(12, 'Transport Routes', 'transport_routes', 0, 1, 1, 11, '2020-06-01 03:51:30', '2025-05-30 06:04:56'),
(13, 'Hostel Rooms', 'hostel_rooms', 0, 1, 1, 12, '2020-06-01 03:52:27', '2025-06-18 08:13:23'),
(14, 'Calendar To Do List', 'calendar_to_do_list', 0, 1, 1, 21, '2020-06-01 03:53:18', '2025-05-10 12:46:08'),
(15, 'Online Examination', 'online_examination', 0, 1, 1, 23, '2020-06-11 05:20:01', '2025-06-07 17:23:46'),
(16, 'Teachers Rating', 'teachers_rating', 0, 1, 1, NULL, '2022-12-28 09:52:28', '2025-05-10 12:46:08'),
(17, 'Chat', 'chat', 0, 1, 1, 25, '2020-06-01 04:53:06', '2025-05-10 12:46:08'),
(18, 'Multi Class', 'multi_class', 1, 1, 1, 26, '2020-05-30 19:56:52', '2025-05-10 12:46:08'),
(19, 'Lesson Plan', 'lesson_plan', 0, 1, 1, 29, '2020-06-07 05:38:30', '2025-06-07 17:23:38'),
(20, 'Syllabus Status', 'syllabus_status', 0, 1, 1, 29, '2020-06-07 05:38:30', '2025-06-07 17:23:38'),
(23, 'Apply Leave', 'apply_leave', 0, 1, 1, NULL, '2022-12-28 09:52:28', '2025-05-10 12:46:08'),
(24, 'Visitor Book', 'visitor_book', 0, 0, 1, NULL, '2022-10-10 11:45:18', '2025-05-27 12:42:40'),
(25, 'Student Timeline', 'student_timeline', 0, 1, 1, NULL, '2022-10-11 04:50:29', '2025-05-10 12:46:08'),
(500, 'Zoom Live Classes', 'live_classes', 0, 1, 1, 500, '2025-05-22 11:46:33', '2025-06-18 08:13:34'),
(600, 'Gmeet Live Classes ', 'gmeet_live_classes', 0, 1, 1, 600, '2020-11-12 13:37:03', '2025-06-18 08:13:39'),
(700, 'Online Course', 'online_course', 0, 1, 1, 700, '2021-05-15 11:35:53', '2025-06-04 09:40:06'),
(800, 'Behaviour Records', 'behaviour_records', 0, 1, 1, 800, '2022-05-03 10:20:33', '2025-05-22 11:46:53'),
(900, 'CBSE Examination', 'cbseexam', 0, 1, 1, 900, '2023-05-25 12:07:15', '2025-06-20 11:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_point`
--

CREATE TABLE `pickup_point` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `print_headerfooter`
--

CREATE TABLE `print_headerfooter` (
  `id` int(11) NOT NULL,
  `print_type` varchar(255) NOT NULL,
  `header_image` varchar(255) NOT NULL,
  `footer_content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `print_headerfooter`
--

INSERT INTO `print_headerfooter` (`id`, `print_type`, `header_image`, `footer_content`, `created_by`, `entry_date`, `created_at`, `updated_at`) VALUES
(1, 'staff_payslip', 'header_image.jpg', 'This payslip is computer generated hence no signature is required.', 1, '2020-02-28 15:41:08', '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(2, 'student_receipt', 'header_image.jpg', 'This receipt is computer generated hence no signature is required.', 1, '2020-02-28 15:40:58', '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(3, 'online_admission_receipt', 'header_image.jpg', 'This receipt is for online admission  computer ffffffff generated hence no signature is required.', 1, '2021-05-27 12:50:24', '2022-12-28 09:52:24', '2025-05-10 12:46:08'),
(4, 'online_exam', '1655913577-198504634062b33c698fde1!online-exam.jpg', 'This receipt is for online exam computer  generated hence no signature is required.', 1, '2022-08-30 12:58:46', '2022-09-08 17:28:34', '2025-05-10 12:46:08'),
(5, 'general_purpose', 'header_image.jpg', '<h1>\r\n\r\n</h1><p>footer text example ....</p>', 1, '2025-02-05 07:26:06', '2022-09-08 17:28:34', '2025-05-10 12:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_settings`
--

CREATE TABLE `qr_code_settings` (
  `id` int(11) NOT NULL,
  `camera_type` varchar(15) DEFAULT NULL,
  `auto_attendance` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `qr_code_settings`
--

INSERT INTO `qr_code_settings` (`id`, `camera_type`, `auto_attendance`, `created_at`) VALUES
(1, 'environment', 1, '2025-06-12 07:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `question_type` varchar(100) NOT NULL,
  `level` varchar(10) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `opt_a` text DEFAULT NULL,
  `opt_b` text DEFAULT NULL,
  `opt_c` text DEFAULT NULL,
  `opt_d` text DEFAULT NULL,
  `opt_e` text DEFAULT NULL,
  `correct` text DEFAULT NULL,
  `descriptive_word_limit` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `read_notification`
--

CREATE TABLE `read_notification` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resume_additional_fields_settings`
--

CREATE TABLE `resume_additional_fields_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `resume_additional_fields_settings`
--

INSERT INTO `resume_additional_fields_settings` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'work_experience', 1, '2024-12-06 06:17:04', '2025-05-10 12:46:09'),
(2, 'education_qalification', 1, '2024-12-06 06:17:04', '2025-05-10 12:46:09'),
(3, 'technical_skills', 1, '2024-12-06 06:17:04', '2025-05-10 12:46:09'),
(4, 'reference', 1, '2024-12-06 06:17:04', '2025-05-10 12:46:09'),
(5, 'other_details', 1, '2024-12-06 06:17:04', '2025-05-10 12:46:09');

-- --------------------------------------------------------

--
-- Table structure for table `resume_settings_fields`
--

CREATE TABLE `resume_settings_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `resume_settings_fields`
--

INSERT INTO `resume_settings_fields` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'middlename', 1, '2025-02-14 06:40:33', '2025-02-14 06:40:33'),
(2, 'lastname', 1, '2025-02-14 06:40:34', '2025-02-14 06:40:34'),
(3, 'gender', 1, '2025-02-14 06:40:35', '2025-02-14 06:40:35'),
(4, 'dob', 1, '2025-02-14 06:40:36', '2025-02-14 06:40:36'),
(5, 'category', 1, '2025-02-14 06:40:37', '2025-02-14 06:40:37'),
(6, 'religion', 1, '2025-02-14 06:40:38', '2025-02-14 06:40:38'),
(7, 'cast', 1, '2025-02-14 06:40:40', '2025-02-14 06:40:40'),
(8, 'mobile_no', 1, '2025-02-14 06:40:41', '2025-02-14 06:40:41'),
(9, 'student_email', 1, '2025-02-14 06:40:41', '2025-02-14 06:40:41'),
(10, 'student_photo', 1, '2025-02-14 06:40:42', '2025-02-14 06:40:42'),
(11, 'is_blood_group', 1, '2025-02-14 06:40:43', '2025-02-14 06:40:43'),
(12, 'height', 1, '2025-02-14 06:40:44', '2025-02-14 06:40:44'),
(13, 'weight', 1, '2025-02-14 06:40:46', '2025-02-14 06:40:46'),
(14, 'father_name', 1, '2025-02-14 06:40:47', '2025-02-14 06:40:47'),
(15, 'father_phone', 1, '2025-02-14 06:40:48', '2025-02-14 06:40:48'),
(16, 'father_occupation', 1, '2025-02-14 06:40:49', '2025-02-14 06:40:49'),
(17, 'father_pic', 1, '2025-02-14 06:40:49', '2025-02-14 06:40:49'),
(18, 'mother_name', 1, '2025-02-14 06:40:50', '2025-02-14 06:40:50'),
(19, 'mother_phone', 1, '2025-02-14 06:40:51', '2025-02-14 06:40:51'),
(20, 'mother_occupation', 1, '2025-02-14 06:40:52', '2025-02-14 06:40:52'),
(21, 'mother_pic', 1, '2025-02-14 06:40:52', '2025-02-14 06:40:52'),
(22, 'if_guardian_is', 1, '2025-02-14 06:40:53', '2025-02-14 06:42:17'),
(23, 'guardian_name', 1, '2025-02-14 06:41:31', '2025-02-14 06:42:17'),
(24, 'guardian_relation', 1, '2025-02-14 06:41:31', '2025-02-14 06:42:17'),
(25, 'guardian_email', 1, '2025-02-14 06:41:32', '2025-02-14 06:42:17'),
(26, 'guardian_photo', 1, '2025-02-14 06:41:33', '2025-02-14 06:42:17'),
(27, 'guardian_phone', 1, '2025-02-14 06:41:34', '2025-02-14 06:42:17'),
(28, 'guardian_occupation', 1, '2025-02-14 06:41:34', '2025-02-14 06:42:17'),
(29, 'guardian_address', 1, '2025-02-14 06:41:35', '2025-02-14 06:42:17'),
(30, 'current_address', 1, '2025-02-14 06:41:36', '2025-02-14 06:41:36'),
(31, 'permanent_address', 1, '2025-02-14 06:41:37', '2025-02-14 06:41:37'),
(32, 'national_identification_no', 1, '2025-02-14 06:41:37', '2025-02-14 06:41:37'),
(33, 'local_identification_no', 1, '2025-02-14 06:41:38', '2025-02-14 06:41:38'),
(34, 'personal_details', 1, '2025-02-14 06:41:39', '2025-02-14 06:41:39'),
(35, 'parent_guardian_detail', 1, '2025-02-14 06:41:41', '2025-02-14 06:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `is_system` int(11) NOT NULL DEFAULT 0,
  `is_superadmin` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `is_system`, `is_superadmin`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, 0, 1, 0, '2018-06-30 15:39:11', '0000-00-00 00:00:00'),
(2, 'Teacher', NULL, 0, 1, 0, '2018-06-30 15:39:14', '0000-00-00 00:00:00'),
(3, 'Accountant', NULL, 0, 1, 0, '2018-06-30 15:39:17', '0000-00-00 00:00:00'),
(4, 'Librarian', NULL, 0, 1, 0, '2018-06-30 15:39:21', '0000-00-00 00:00:00'),
(6, 'Receptionist', NULL, 0, 1, 0, '2018-07-02 05:39:03', '0000-00-00 00:00:00'),
(7, 'Super Admin', NULL, 0, 1, 1, '2018-07-11 14:11:29', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `perm_cat_id` int(11) DEFAULT NULL,
  `can_view` int(11) DEFAULT NULL,
  `can_add` int(11) DEFAULT NULL,
  `can_edit` int(11) DEFAULT NULL,
  `can_delete` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`id`, `role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
(11, 1, 78, 1, 1, 1, 1, '2018-07-03 00:49:43', '2025-05-10 12:46:09'),
(23, 1, 12, 1, 1, 1, 1, '2018-07-06 09:45:38', '2025-05-10 12:46:09'),
(24, 1, 13, 1, 1, 1, 1, '2018-07-06 09:48:28', '2025-05-10 12:46:09'),
(26, 1, 15, 1, 1, 1, 0, '2019-11-27 23:47:28', '2025-05-10 12:46:09'),
(31, 1, 21, 1, 0, 1, 0, '2019-11-26 04:51:15', '2025-05-10 12:46:09'),
(34, 1, 24, 1, 1, 1, 1, '2019-11-28 06:35:20', '2025-05-10 12:46:09'),
(43, 1, 32, 1, 1, 1, 1, '2018-07-06 10:22:05', '2025-05-10 12:46:09'),
(44, 1, 33, 1, 1, 1, 1, '2018-07-06 10:22:29', '2025-05-10 12:46:09'),
(45, 1, 34, 1, 1, 1, 1, '2018-07-06 10:23:59', '2025-05-10 12:46:09'),
(46, 1, 35, 1, 1, 1, 1, '2018-07-06 10:24:34', '2025-05-10 12:46:09'),
(47, 1, 104, 1, 1, 1, 1, '2018-07-06 10:23:08', '2025-05-10 12:46:09'),
(48, 1, 37, 1, 1, 1, 1, '2018-07-06 10:25:30', '2025-05-10 12:46:09'),
(49, 1, 38, 1, 1, 1, 1, '2018-07-09 05:15:27', '2025-05-10 12:46:09'),
(61, 1, 55, 1, 1, 1, 1, '2018-07-02 09:24:16', '2025-05-10 12:46:09'),
(67, 1, 61, 1, 1, 0, 1, '2018-07-09 05:59:19', '2025-05-10 12:46:09'),
(68, 1, 62, 1, 1, 0, 1, '2018-07-09 05:59:19', '2025-05-10 12:46:09'),
(69, 1, 63, 1, 1, 0, 1, '2018-07-09 03:51:38', '2025-05-10 12:46:09'),
(70, 1, 64, 1, 1, 1, 1, '2018-07-09 03:02:19', '2025-05-10 12:46:09'),
(71, 1, 65, 1, 1, 1, 1, '2018-07-09 03:11:21', '2025-05-10 12:46:09'),
(72, 1, 66, 1, 1, 1, 1, '2018-07-09 03:13:09', '2025-05-10 12:46:09'),
(73, 1, 67, 1, 1, 1, 1, '2018-07-09 03:14:47', '2025-05-10 12:46:09'),
(74, 1, 79, 1, 1, 0, 1, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(75, 1, 80, 1, 1, 1, 1, '2018-07-06 09:41:23', '2025-05-10 12:46:09'),
(76, 1, 81, 1, 1, 1, 1, '2018-07-06 09:41:23', '2025-05-10 12:46:09'),
(78, 1, 83, 1, 1, 1, 1, '2018-07-06 09:41:23', '2025-05-10 12:46:09'),
(79, 1, 84, 1, 1, 1, 1, '2018-07-06 09:41:23', '2025-05-10 12:46:09'),
(80, 1, 85, 1, 1, 1, 1, '2018-07-12 00:16:00', '2025-05-10 12:46:09'),
(94, 1, 82, 1, 1, 1, 1, '2018-07-06 09:41:23', '2025-05-10 12:46:09'),
(120, 1, 39, 1, 1, 1, 1, '2018-07-06 10:26:28', '2025-05-10 12:46:09'),
(156, 1, 9, 1, 1, 1, 1, '2019-11-27 23:45:46', '2025-05-10 12:46:09'),
(157, 1, 10, 1, 1, 1, 1, '2019-11-27 23:45:46', '2025-05-10 12:46:09'),
(159, 1, 40, 1, 1, 1, 1, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(160, 1, 41, 1, 1, 1, 1, '2019-12-02 05:43:41', '2025-05-10 12:46:09'),
(161, 1, 42, 1, 1, 1, 1, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(169, 1, 27, 1, 1, 0, 1, '2019-11-29 06:15:37', '2025-05-10 12:46:09'),
(178, 1, 54, 1, 0, 1, 0, '2018-07-05 09:09:22', '2025-05-10 12:46:09'),
(179, 1, 56, 1, 0, 1, 0, '2019-11-30 00:49:54', '2025-05-10 12:46:09'),
(180, 1, 57, 1, 0, 1, 0, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(181, 1, 58, 1, 0, 1, 0, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(182, 1, 59, 1, 0, 1, 0, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(183, 1, 60, 1, 0, 1, 0, '2019-11-30 00:59:57', '2025-05-10 12:46:09'),
(201, 1, 14, 1, 0, 0, 0, '2018-07-02 11:22:03', '2025-05-10 12:46:09'),
(204, 1, 26, 1, 0, 0, 0, '2018-07-02 11:32:05', '2025-05-10 12:46:09'),
(206, 1, 29, 1, 0, 0, 0, '2018-07-02 11:43:54', '2025-05-10 12:46:09'),
(207, 1, 30, 1, 0, 0, 0, '2018-07-02 11:43:54', '2025-05-10 12:46:09'),
(208, 1, 31, 1, 1, 1, 1, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(222, 1, 1, 1, 1, 1, 1, '2019-11-27 22:55:06', '2025-05-10 12:46:09'),
(307, 1, 126, 1, 0, 0, 0, '2018-07-03 09:26:13', '2025-05-10 12:46:09'),
(315, 1, 123, 1, 0, 0, 0, '2018-07-03 10:27:03', '2025-05-10 12:46:09'),
(369, 1, 102, 1, 1, 1, 1, '2019-12-02 05:02:15', '2025-05-10 12:46:09'),
(435, 1, 96, 1, 1, 1, 1, '2018-07-09 01:03:54', '2025-05-10 12:46:09'),
(461, 1, 97, 1, 0, 0, 0, '2018-07-09 01:00:16', '2025-05-10 12:46:09'),
(464, 1, 86, 1, 1, 1, 1, '2019-11-28 06:39:19', '2025-05-10 12:46:09'),
(474, 1, 130, 1, 1, 0, 1, '2018-07-09 10:56:36', '2025-05-10 12:46:09'),
(476, 1, 131, 1, 0, 0, 0, '2018-07-09 04:53:32', '2025-05-10 12:46:09'),
(557, 6, 82, 1, 1, 1, 1, '2019-12-01 01:48:28', '2025-05-10 12:46:09'),
(558, 6, 83, 1, 1, 1, 1, '2019-12-01 01:49:08', '2025-05-10 12:46:09'),
(559, 6, 84, 1, 1, 1, 1, '2019-12-01 01:49:59', '2025-05-10 12:46:09'),
(575, 6, 44, 1, 0, 0, 0, '2018-07-10 07:35:33', '2025-05-10 12:46:09'),
(576, 6, 46, 1, 0, 0, 0, '2018-07-10 07:35:33', '2025-05-10 12:46:09'),
(578, 6, 102, 1, 1, 1, 1, '2019-12-01 01:52:27', '2025-05-10 12:46:09'),
(625, 1, 28, 1, 1, 1, 1, '2019-11-29 06:19:18', '2025-05-10 12:46:09'),
(634, 4, 102, 1, 1, 1, 1, '2019-12-01 01:03:00', '2025-05-10 12:46:09'),
(669, 1, 145, 1, 0, 0, 0, '2019-11-26 04:51:15', '2025-05-10 12:46:09'),
(677, 1, 153, 1, 0, 0, 0, '2019-11-01 02:28:24', '2025-05-10 12:46:09'),
(720, 1, 216, 1, 0, 0, 0, '2019-11-26 05:24:12', '2025-05-10 12:46:09'),
(728, 1, 185, 1, 1, 1, 1, '2019-11-28 02:50:33', '2025-05-10 12:46:09'),
(729, 1, 186, 1, 1, 1, 1, '2019-11-28 02:49:07', '2025-05-10 12:46:09'),
(730, 1, 214, 1, 0, 1, 0, '2019-11-28 01:47:53', '2025-05-10 12:46:09'),
(732, 1, 198, 1, 0, 0, 0, '2019-11-26 05:24:30', '2025-05-10 12:46:09'),
(734, 1, 200, 1, 0, 0, 0, '2019-11-26 05:24:30', '2025-05-10 12:46:09'),
(735, 1, 201, 1, 0, 0, 0, '2019-11-26 05:24:30', '2025-05-10 12:46:09'),
(736, 1, 202, 1, 0, 0, 0, '2019-11-26 05:24:30', '2025-05-10 12:46:09'),
(737, 1, 203, 1, 0, 0, 0, '2019-11-26 05:24:30', '2025-05-10 12:46:09'),
(747, 1, 2, 1, 0, 0, 0, '2019-11-27 22:56:08', '2025-05-10 12:46:09'),
(748, 1, 3, 1, 1, 1, 1, '2019-11-27 22:56:32', '2025-05-10 12:46:09'),
(749, 1, 4, 1, 1, 1, 1, '2019-11-27 22:56:48', '2025-05-10 12:46:09'),
(751, 1, 128, 0, 1, 0, 1, '2019-11-27 22:57:01', '2025-05-10 12:46:09'),
(754, 1, 134, 1, 1, 1, 1, '2019-11-27 23:18:21', '2025-05-10 12:46:09'),
(755, 1, 5, 1, 1, 0, 1, '2019-11-27 23:35:07', '2025-05-10 12:46:09'),
(756, 1, 6, 1, 0, 0, 0, '2019-11-27 23:35:25', '2025-05-10 12:46:09'),
(757, 1, 7, 1, 1, 1, 1, '2019-11-27 23:36:35', '2025-05-10 12:46:09'),
(758, 1, 8, 1, 1, 1, 1, '2019-11-27 23:37:27', '2025-05-10 12:46:09'),
(760, 1, 68, 1, 0, 0, 0, '2019-11-27 23:38:06', '2025-05-10 12:46:09'),
(761, 1, 69, 1, 1, 1, 1, '2019-11-27 23:39:06', '2025-05-10 12:46:09'),
(762, 1, 70, 1, 1, 1, 1, '2019-11-27 23:39:41', '2025-05-10 12:46:09'),
(763, 1, 71, 1, 0, 0, 0, '2019-11-27 23:39:59', '2025-05-10 12:46:09'),
(765, 1, 73, 1, 0, 0, 0, '2019-11-27 23:43:15', '2025-05-10 12:46:09'),
(766, 1, 74, 1, 0, 0, 0, '2019-11-27 23:43:55', '2025-05-10 12:46:09'),
(768, 1, 11, 1, 0, 0, 0, '2019-11-27 23:45:46', '2025-05-10 12:46:09'),
(769, 1, 122, 1, 0, 0, 0, '2019-11-27 23:52:43', '2025-05-10 12:46:09'),
(771, 1, 136, 1, 0, 0, 0, '2019-11-27 23:55:36', '2025-05-10 12:46:09'),
(772, 1, 20, 1, 1, 1, 1, '2019-11-28 04:06:44', '2025-05-10 12:46:09'),
(773, 1, 137, 1, 1, 1, 1, '2019-11-28 00:46:14', '2025-05-10 12:46:09'),
(774, 1, 141, 1, 1, 1, 1, '2019-11-28 00:59:42', '2025-05-10 12:46:09'),
(775, 1, 142, 1, 0, 0, 0, '2019-11-27 23:56:12', '2025-05-10 12:46:09'),
(776, 1, 143, 1, 1, 1, 1, '2019-11-28 00:59:42', '2025-05-10 12:46:09'),
(777, 1, 144, 1, 0, 0, 0, '2019-11-27 23:56:12', '2025-05-10 12:46:09'),
(778, 1, 187, 1, 0, 0, 0, '2019-11-27 23:56:12', '2025-05-10 12:46:09'),
(779, 1, 196, 1, 0, 0, 0, '2019-11-27 23:56:12', '2025-05-10 12:46:09'),
(781, 1, 207, 1, 0, 0, 0, '2019-11-27 23:56:12', '2025-05-10 12:46:09'),
(782, 1, 208, 1, 0, 1, 0, '2019-11-28 00:10:22', '2025-05-10 12:46:09'),
(783, 1, 210, 1, 0, 1, 0, '2019-11-28 00:34:40', '2025-05-10 12:46:09'),
(784, 1, 211, 1, 0, 1, 0, '2019-11-28 00:38:23', '2025-05-10 12:46:09'),
(785, 1, 212, 1, 0, 1, 0, '2019-11-28 00:42:15', '2025-05-10 12:46:09'),
(786, 1, 205, 1, 1, 1, 1, '2019-11-28 00:42:15', '2025-05-10 12:46:09'),
(787, 1, 222, 1, 0, 1, 0, '2019-11-28 01:36:36', '2025-05-10 12:46:09'),
(788, 1, 77, 1, 1, 1, 1, '2019-11-28 06:22:10', '2025-05-10 12:46:09'),
(789, 1, 188, 1, 1, 1, 1, '2019-11-28 06:26:16', '2025-05-10 12:46:09'),
(790, 1, 23, 1, 1, 1, 1, '2019-11-28 06:34:20', '2025-05-10 12:46:09'),
(791, 1, 25, 1, 1, 1, 1, '2019-11-28 06:36:20', '2025-05-10 12:46:09'),
(792, 1, 127, 1, 0, 0, 0, '2019-11-28 06:41:25', '2025-05-10 12:46:09'),
(794, 1, 88, 1, 1, 1, 0, '2019-11-28 06:43:04', '2025-05-10 12:46:09'),
(795, 1, 90, 1, 1, 0, 1, '2019-11-28 06:46:22', '2025-05-10 12:46:09'),
(796, 1, 108, 1, 0, 1, 1, '2021-01-23 07:09:32', '2025-05-10 12:46:09'),
(797, 1, 109, 1, 1, 0, 0, '2019-11-28 23:38:11', '2025-05-10 12:46:09'),
(798, 1, 110, 1, 1, 1, 1, '2019-11-28 23:49:29', '2025-05-10 12:46:09'),
(799, 1, 111, 1, 1, 1, 1, '2019-11-28 23:49:57', '2025-05-10 12:46:09'),
(800, 1, 112, 1, 1, 1, 1, '2019-11-28 23:49:57', '2025-05-10 12:46:09'),
(801, 1, 129, 0, 1, 0, 1, '2019-11-28 23:49:57', '2025-05-10 12:46:09'),
(802, 1, 189, 1, 0, 1, 1, '2019-11-28 23:59:22', '2025-05-10 12:46:09'),
(810, 2, 1, 1, 1, 1, 1, '2019-11-30 02:54:16', '2025-05-10 12:46:09'),
(817, 1, 93, 1, 1, 1, 1, '2019-11-29 00:56:14', '2025-05-10 12:46:09'),
(825, 1, 87, 1, 0, 0, 0, '2019-11-29 00:56:14', '2025-05-10 12:46:09'),
(829, 1, 94, 1, 1, 0, 0, '2019-11-29 00:57:57', '2025-05-10 12:46:09'),
(836, 1, 146, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(837, 1, 147, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(838, 1, 148, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(839, 1, 149, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(840, 1, 150, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(841, 1, 151, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(842, 1, 152, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(843, 1, 154, 1, 0, 0, 0, '2019-11-29 01:13:28', '2025-05-10 12:46:09'),
(862, 1, 155, 1, 0, 0, 0, '2019-11-29 02:07:30', '2025-05-10 12:46:09'),
(863, 1, 156, 1, 0, 0, 0, '2019-11-29 02:07:52', '2025-05-10 12:46:09'),
(864, 1, 157, 1, 0, 0, 0, '2019-11-29 02:08:05', '2025-05-10 12:46:09'),
(874, 1, 158, 1, 0, 0, 0, '2019-11-29 02:14:03', '2025-05-10 12:46:09'),
(875, 1, 159, 1, 0, 0, 0, '2019-11-29 02:14:31', '2025-05-10 12:46:09'),
(876, 1, 160, 1, 0, 0, 0, '2019-11-29 02:14:44', '2025-05-10 12:46:09'),
(878, 1, 162, 1, 0, 0, 0, '2019-11-29 02:15:58', '2025-05-10 12:46:09'),
(879, 1, 163, 1, 0, 0, 0, '2019-11-29 02:16:19', '2025-05-10 12:46:09'),
(882, 1, 164, 1, 0, 0, 0, '2019-11-29 02:25:17', '2025-05-10 12:46:09'),
(884, 1, 165, 1, 0, 0, 0, '2019-11-29 02:25:30', '2025-05-10 12:46:09'),
(886, 1, 197, 1, 0, 0, 0, '2019-11-29 02:25:48', '2025-05-10 12:46:09'),
(887, 1, 219, 1, 0, 0, 0, '2019-11-29 02:26:05', '2025-05-10 12:46:09'),
(889, 1, 220, 1, 0, 0, 0, '2019-11-29 02:26:22', '2025-05-10 12:46:09'),
(932, 1, 204, 1, 0, 0, 0, '2019-11-29 03:43:27', '2025-05-10 12:46:09'),
(933, 1, 221, 1, 0, 0, 0, '2019-11-29 03:45:04', '2025-05-10 12:46:09'),
(934, 1, 178, 1, 0, 0, 0, '2019-11-29 03:45:16', '2025-05-10 12:46:09'),
(935, 1, 179, 1, 0, 0, 0, '2019-11-29 03:45:33', '2025-05-10 12:46:09'),
(936, 1, 161, 1, 0, 0, 0, '2019-11-29 03:45:48', '2025-05-10 12:46:09'),
(937, 1, 180, 1, 0, 0, 0, '2019-11-29 03:45:48', '2025-05-10 12:46:09'),
(938, 1, 181, 1, 0, 0, 0, '2019-11-29 03:49:33', '2025-05-10 12:46:09'),
(939, 1, 182, 1, 0, 0, 0, '2019-11-29 03:49:45', '2025-05-10 12:46:09'),
(940, 1, 183, 1, 0, 0, 0, '2019-11-29 03:49:56', '2025-05-10 12:46:09'),
(941, 1, 174, 1, 0, 0, 0, '2019-11-29 03:50:53', '2025-05-10 12:46:09'),
(943, 1, 176, 1, 0, 0, 0, '2019-11-29 03:52:10', '2025-05-10 12:46:09'),
(944, 1, 177, 1, 0, 0, 0, '2019-11-29 03:52:22', '2025-05-10 12:46:09'),
(945, 1, 53, 0, 1, 0, 1, '2021-01-23 07:09:32', '2025-05-10 12:46:09'),
(946, 1, 215, 1, 0, 0, 0, '2019-11-29 04:01:37', '2025-05-10 12:46:09'),
(947, 1, 213, 1, 0, 0, 0, '2019-11-29 04:07:45', '2025-05-10 12:46:09'),
(974, 1, 224, 1, 0, 0, 0, '2019-11-29 04:32:52', '2025-05-10 12:46:09'),
(1026, 1, 135, 1, 0, 1, 0, '2019-11-29 06:02:12', '2025-05-10 12:46:09'),
(1031, 1, 228, 1, 0, 0, 0, '2019-11-29 06:21:16', '2025-05-10 12:46:09'),
(1083, 1, 175, 1, 0, 0, 0, '2019-11-30 00:37:24', '2025-05-10 12:46:09'),
(1086, 1, 43, 1, 1, 1, 1, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(1087, 1, 44, 1, 0, 0, 0, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(1088, 1, 46, 1, 0, 0, 0, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(1089, 1, 217, 1, 0, 0, 0, '2019-11-30 00:49:39', '2025-05-10 12:46:09'),
(1090, 1, 98, 1, 1, 1, 1, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(1091, 1, 99, 1, 0, 0, 0, '2019-11-30 01:30:18', '2025-05-10 12:46:09'),
(1092, 1, 223, 1, 0, 0, 0, '2019-11-30 01:32:51', '2025-05-10 12:46:09'),
(1103, 2, 205, 1, 1, 1, 1, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1105, 2, 23, 1, 0, 0, 0, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1106, 2, 24, 1, 0, 0, 0, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1107, 2, 25, 1, 0, 0, 0, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1108, 2, 77, 1, 0, 0, 0, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1119, 2, 117, 1, 0, 0, 0, '2019-11-30 01:56:04', '2025-05-10 12:46:09'),
(1123, 3, 8, 1, 1, 1, 1, '2019-11-30 06:46:18', '2025-05-10 12:46:09'),
(1125, 3, 69, 1, 1, 1, 1, '2019-11-30 07:00:49', '2025-05-10 12:46:09'),
(1126, 3, 70, 1, 1, 1, 1, '2019-11-30 07:04:46', '2025-05-10 12:46:09'),
(1130, 3, 9, 1, 1, 1, 1, '2019-11-30 07:14:54', '2025-05-10 12:46:09'),
(1131, 3, 10, 1, 1, 1, 1, '2019-11-30 07:16:02', '2025-05-10 12:46:09'),
(1134, 3, 35, 1, 1, 1, 1, '2019-11-30 07:25:04', '2025-05-10 12:46:09'),
(1135, 3, 104, 1, 1, 1, 1, '2019-11-30 07:25:53', '2025-05-10 12:46:09'),
(1140, 3, 41, 1, 1, 1, 1, '2019-11-30 07:37:13', '2025-05-10 12:46:09'),
(1141, 3, 42, 1, 1, 1, 1, '2019-11-30 07:37:46', '2025-05-10 12:46:09'),
(1142, 3, 43, 1, 1, 1, 1, '2019-11-30 07:42:06', '2025-05-10 12:46:09'),
(1151, 3, 87, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1152, 3, 88, 1, 1, 1, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1153, 3, 90, 1, 1, 0, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1154, 3, 108, 1, 0, 1, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1155, 3, 109, 1, 1, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1156, 3, 110, 1, 1, 1, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1157, 3, 111, 1, 1, 1, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1158, 3, 112, 1, 1, 1, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1159, 3, 127, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1160, 3, 129, 0, 1, 0, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1161, 3, 102, 1, 1, 1, 1, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1162, 3, 106, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1163, 3, 113, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1164, 3, 114, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1165, 3, 115, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1166, 3, 116, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1167, 3, 117, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1168, 3, 118, 1, 0, 0, 0, '2019-11-30 02:23:13', '2025-05-10 12:46:09'),
(1171, 2, 142, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1172, 2, 144, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1179, 2, 212, 1, 0, 1, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1183, 2, 148, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1184, 2, 149, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1185, 2, 150, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1186, 2, 151, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1187, 2, 152, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1188, 2, 153, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1189, 2, 154, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1190, 2, 197, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1191, 2, 198, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1193, 2, 200, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1194, 2, 201, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1195, 2, 202, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1196, 2, 203, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1197, 2, 219, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1198, 2, 223, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1199, 2, 213, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1201, 2, 230, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1204, 2, 214, 1, 0, 1, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1206, 2, 224, 1, 0, 0, 0, '2019-11-30 02:36:17', '2025-05-10 12:46:09'),
(1208, 2, 2, 1, 0, 0, 0, '2019-11-30 02:55:45', '2025-05-10 12:46:09'),
(1210, 2, 143, 1, 1, 1, 1, '2019-11-30 02:57:28', '2025-05-10 12:46:09'),
(1211, 2, 145, 1, 0, 0, 0, '2019-11-30 02:57:28', '2025-05-10 12:46:09'),
(1214, 2, 3, 1, 1, 1, 1, '2019-11-30 03:03:18', '2025-05-10 12:46:09'),
(1216, 2, 4, 1, 1, 1, 1, '2019-11-30 03:32:56', '2025-05-10 12:46:09'),
(1218, 2, 128, 0, 1, 0, 1, '2019-11-30 03:37:44', '2025-05-10 12:46:09'),
(1220, 3, 135, 1, 0, 1, 0, '2019-11-30 07:08:56', '2025-05-10 12:46:09'),
(1231, 3, 190, 1, 0, 0, 0, '2019-11-30 03:44:02', '2025-05-10 12:46:09'),
(1232, 3, 192, 1, 0, 0, 0, '2019-11-30 03:44:02', '2025-05-10 12:46:09'),
(1233, 3, 226, 1, 0, 0, 0, '2019-11-30 03:44:02', '2025-05-10 12:46:09'),
(1234, 3, 227, 1, 0, 0, 0, '2019-11-30 03:44:02', '2025-05-10 12:46:09'),
(1235, 3, 224, 1, 0, 0, 0, '2019-11-30 03:44:02', '2025-05-10 12:46:09'),
(1236, 2, 15, 1, 1, 1, 0, '2019-11-30 03:54:25', '2025-05-10 12:46:09'),
(1239, 2, 122, 1, 0, 0, 0, '2019-11-30 03:57:48', '2025-05-10 12:46:09'),
(1240, 2, 136, 1, 0, 0, 0, '2019-11-30 03:57:48', '2025-05-10 12:46:09'),
(1242, 6, 217, 1, 0, 0, 0, '2019-11-30 04:00:13', '2025-05-10 12:46:09'),
(1243, 6, 224, 1, 0, 0, 0, '2019-11-30 04:00:13', '2025-05-10 12:46:09'),
(1245, 2, 20, 1, 1, 1, 1, '2019-11-30 04:01:28', '2025-05-10 12:46:09'),
(1246, 2, 137, 1, 1, 1, 1, '2019-11-30 04:02:40', '2025-05-10 12:46:09'),
(1248, 2, 141, 1, 1, 1, 1, '2019-11-30 04:04:04', '2025-05-10 12:46:09'),
(1250, 2, 187, 1, 0, 0, 0, '2019-11-30 04:11:19', '2025-05-10 12:46:09'),
(1252, 2, 207, 1, 0, 0, 0, '2019-11-30 04:21:21', '2025-05-10 12:46:09'),
(1253, 2, 208, 1, 0, 1, 0, '2019-11-30 04:22:00', '2025-05-10 12:46:09'),
(1255, 2, 210, 1, 0, 1, 0, '2019-11-30 04:22:58', '2025-05-10 12:46:09'),
(1256, 2, 211, 1, 0, 1, 0, '2019-11-30 04:24:03', '2025-05-10 12:46:09'),
(1257, 2, 21, 1, 0, 0, 0, '2019-11-30 04:32:59', '2025-05-10 12:46:09'),
(1259, 2, 188, 1, 0, 0, 0, '2019-11-30 04:34:35', '2025-05-10 12:46:09'),
(1260, 2, 27, 1, 0, 0, 0, '2019-11-30 04:36:13', '2025-05-10 12:46:09'),
(1262, 2, 43, 1, 1, 1, 1, '2019-11-30 04:39:42', '2025-05-10 12:46:09'),
(1263, 2, 44, 1, 0, 0, 0, '2019-11-30 04:41:43', '2025-05-10 12:46:09'),
(1264, 2, 46, 1, 0, 0, 0, '2019-11-30 04:41:43', '2025-05-10 12:46:09'),
(1265, 2, 217, 1, 0, 0, 0, '2019-11-30 04:41:43', '2025-05-10 12:46:09'),
(1266, 2, 146, 1, 0, 0, 0, '2019-11-30 04:46:35', '2025-05-10 12:46:09'),
(1267, 2, 147, 1, 0, 0, 0, '2019-11-30 04:47:37', '2025-05-10 12:46:09'),
(1269, 2, 164, 1, 0, 0, 0, '2019-11-30 04:51:04', '2025-05-10 12:46:09'),
(1271, 2, 109, 1, 1, 0, 0, '2019-11-30 05:03:37', '2025-05-10 12:46:09'),
(1272, 2, 93, 1, 1, 1, 1, '2019-11-30 05:07:25', '2025-05-10 12:46:09'),
(1273, 2, 94, 1, 1, 0, 0, '2019-11-30 05:07:42', '2025-05-10 12:46:09'),
(1275, 2, 102, 1, 1, 1, 1, '2019-11-30 05:11:22', '2025-05-10 12:46:09'),
(1277, 2, 196, 1, 0, 0, 0, '2019-11-30 05:15:01', '2025-05-10 12:46:09'),
(1278, 2, 195, 1, 0, 0, 0, '2019-11-30 05:19:08', '2025-05-10 12:46:09'),
(1279, 2, 185, 1, 1, 1, 1, '2019-11-30 05:21:44', '2025-05-10 12:46:09'),
(1280, 2, 186, 1, 1, 1, 1, '2019-11-30 05:22:43', '2025-05-10 12:46:09'),
(1281, 2, 222, 1, 0, 1, 0, '2019-11-30 05:24:30', '2025-05-10 12:46:09'),
(1283, 3, 5, 1, 1, 0, 1, '2019-11-30 06:43:04', '2025-05-10 12:46:09'),
(1284, 3, 6, 1, 0, 0, 0, '2019-11-30 06:43:29', '2025-05-10 12:46:09'),
(1285, 3, 7, 1, 1, 1, 1, '2019-11-30 06:44:39', '2025-05-10 12:46:09'),
(1286, 3, 68, 1, 0, 0, 0, '2019-11-30 06:46:58', '2025-05-10 12:46:09'),
(1287, 3, 71, 1, 0, 0, 0, '2019-11-30 07:05:41', '2025-05-10 12:46:09'),
(1288, 3, 73, 1, 0, 0, 0, '2019-11-30 07:05:59', '2025-05-10 12:46:09'),
(1289, 3, 74, 1, 0, 0, 0, '2019-11-30 07:06:08', '2025-05-10 12:46:09'),
(1290, 3, 11, 1, 0, 0, 0, '2019-11-30 07:16:37', '2025-05-10 12:46:09'),
(1291, 3, 12, 1, 1, 1, 1, '2019-11-30 07:19:29', '2025-05-10 12:46:09'),
(1292, 3, 13, 1, 1, 1, 1, '2019-11-30 07:22:27', '2025-05-10 12:46:09'),
(1294, 3, 14, 1, 0, 0, 0, '2019-11-30 07:22:55', '2025-05-10 12:46:09'),
(1295, 3, 31, 1, 1, 1, 1, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1297, 3, 37, 1, 1, 1, 1, '2019-11-30 07:28:09', '2025-05-10 12:46:09'),
(1298, 3, 38, 1, 1, 1, 1, '2019-11-30 07:29:02', '2025-05-10 12:46:09'),
(1299, 3, 39, 1, 1, 1, 1, '2019-11-30 07:30:07', '2025-05-10 12:46:09'),
(1300, 3, 40, 1, 1, 1, 1, '2019-11-30 07:32:43', '2025-05-10 12:46:09'),
(1301, 3, 44, 1, 0, 0, 0, '2019-11-30 07:44:09', '2025-05-10 12:46:09'),
(1302, 3, 46, 1, 0, 0, 0, '2019-11-30 07:44:09', '2025-05-10 12:46:09'),
(1303, 3, 217, 1, 0, 0, 0, '2019-11-30 07:44:09', '2025-05-10 12:46:09'),
(1304, 3, 155, 1, 0, 0, 0, '2019-11-30 07:44:32', '2025-05-10 12:46:09'),
(1305, 3, 156, 1, 0, 0, 0, '2019-11-30 07:45:18', '2025-05-10 12:46:09'),
(1306, 3, 157, 1, 0, 0, 0, '2019-11-30 07:45:42', '2025-05-10 12:46:09'),
(1307, 3, 158, 1, 0, 0, 0, '2019-11-30 07:46:07', '2025-05-10 12:46:09'),
(1308, 3, 159, 1, 0, 0, 0, '2019-11-30 07:46:21', '2025-05-10 12:46:09'),
(1309, 3, 160, 1, 0, 0, 0, '2019-11-30 07:46:33', '2025-05-10 12:46:09'),
(1313, 3, 161, 1, 0, 0, 0, '2019-11-30 07:48:26', '2025-05-10 12:46:09'),
(1314, 3, 162, 1, 0, 0, 0, '2019-11-30 07:48:48', '2025-05-10 12:46:09'),
(1315, 3, 163, 1, 0, 0, 0, '2019-11-30 07:48:48', '2025-05-10 12:46:09'),
(1316, 3, 164, 1, 0, 0, 0, '2019-11-30 07:49:47', '2025-05-10 12:46:09'),
(1317, 3, 165, 1, 0, 0, 0, '2019-11-30 07:49:47', '2025-05-10 12:46:09'),
(1318, 3, 174, 1, 0, 0, 0, '2019-11-30 07:49:47', '2025-05-10 12:46:09'),
(1319, 3, 175, 1, 0, 0, 0, '2019-11-30 07:49:59', '2025-05-10 12:46:09'),
(1320, 3, 181, 1, 0, 0, 0, '2019-11-30 07:50:08', '2025-05-10 12:46:09'),
(1321, 3, 86, 1, 1, 1, 1, '2019-11-30 07:54:08', '2025-05-10 12:46:09'),
(1322, 4, 28, 1, 1, 1, 1, '2019-12-01 00:52:39', '2025-05-10 12:46:09'),
(1324, 4, 29, 1, 0, 0, 0, '2019-12-01 00:53:46', '2025-05-10 12:46:09'),
(1325, 4, 30, 1, 0, 0, 0, '2019-12-01 00:53:59', '2025-05-10 12:46:09'),
(1326, 4, 123, 1, 0, 0, 0, '2019-12-01 00:54:26', '2025-05-10 12:46:09'),
(1327, 4, 228, 1, 0, 0, 0, '2019-12-01 00:54:39', '2025-05-10 12:46:09'),
(1328, 4, 43, 1, 1, 1, 1, '2019-12-01 00:58:05', '2025-05-10 12:46:09'),
(1332, 4, 44, 1, 0, 0, 0, '2019-12-01 00:59:16', '2025-05-10 12:46:09'),
(1333, 4, 46, 1, 0, 0, 0, '2019-12-01 00:59:16', '2025-05-10 12:46:09'),
(1334, 4, 217, 1, 0, 0, 0, '2019-12-01 00:59:16', '2025-05-10 12:46:09'),
(1335, 4, 178, 1, 0, 0, 0, '2019-12-01 00:59:59', '2025-05-10 12:46:09'),
(1336, 4, 179, 1, 0, 0, 0, '2019-12-01 01:00:11', '2025-05-10 12:46:09'),
(1337, 4, 180, 1, 0, 0, 0, '2019-12-01 01:00:29', '2025-05-10 12:46:09'),
(1338, 4, 221, 1, 0, 0, 0, '2019-12-01 01:00:46', '2025-05-10 12:46:09'),
(1339, 4, 86, 1, 0, 0, 0, '2019-12-01 01:01:02', '2025-05-10 12:46:09'),
(1341, 4, 106, 1, 0, 0, 0, '2019-12-01 01:05:21', '2025-05-10 12:46:09'),
(1342, 1, 107, 1, 0, 0, 0, '2019-12-01 01:06:44', '2025-05-10 12:46:09'),
(1343, 4, 117, 1, 0, 0, 0, '2019-12-01 01:10:20', '2025-05-10 12:46:09'),
(1344, 4, 194, 1, 0, 0, 0, '2019-12-01 01:11:35', '2025-05-10 12:46:09'),
(1348, 4, 230, 1, 0, 0, 0, '2019-12-01 01:19:15', '2025-05-10 12:46:09'),
(1350, 6, 1, 1, 0, 0, 0, '2019-12-01 01:35:32', '2025-05-10 12:46:09'),
(1351, 6, 21, 1, 0, 0, 0, '2019-12-01 01:36:29', '2025-05-10 12:46:09'),
(1352, 6, 23, 1, 0, 0, 0, '2019-12-01 01:36:45', '2025-05-10 12:46:09'),
(1353, 6, 24, 1, 0, 0, 0, '2019-12-01 01:37:05', '2025-05-10 12:46:09'),
(1354, 6, 25, 1, 0, 0, 0, '2019-12-01 01:37:34', '2025-05-10 12:46:09'),
(1355, 6, 77, 1, 0, 0, 0, '2019-12-01 01:38:08', '2025-05-10 12:46:09'),
(1356, 6, 188, 1, 0, 0, 0, '2019-12-01 01:38:45', '2025-05-10 12:46:09'),
(1357, 6, 43, 1, 1, 1, 1, '2019-12-01 01:40:44', '2025-05-10 12:46:09'),
(1358, 6, 78, 1, 1, 1, 1, '2019-12-01 01:43:04', '2025-05-10 12:46:09'),
(1360, 6, 79, 1, 1, 0, 1, '2019-12-01 01:44:39', '2025-05-10 12:46:09'),
(1361, 6, 80, 1, 1, 1, 1, '2019-12-01 01:45:08', '2025-05-10 12:46:09'),
(1362, 6, 81, 1, 1, 1, 1, '2019-12-01 01:47:50', '2025-05-10 12:46:09'),
(1363, 6, 85, 1, 1, 1, 1, '2019-12-01 01:50:43', '2025-05-10 12:46:09'),
(1364, 6, 86, 1, 0, 0, 0, '2019-12-01 01:51:10', '2025-05-10 12:46:09'),
(1365, 6, 106, 1, 0, 0, 0, '2019-12-01 01:52:55', '2025-05-10 12:46:09'),
(1366, 6, 117, 1, 0, 0, 0, '2019-12-01 01:53:08', '2025-05-10 12:46:09'),
(1394, 1, 106, 1, 0, 0, 0, '2019-12-02 05:20:33', '2025-05-10 12:46:09'),
(1395, 1, 113, 1, 0, 0, 0, '2019-12-02 05:20:59', '2025-05-10 12:46:09'),
(1396, 1, 114, 1, 0, 0, 0, '2019-12-02 05:21:34', '2025-05-10 12:46:09'),
(1397, 1, 115, 1, 0, 0, 0, '2019-12-02 05:21:34', '2025-05-10 12:46:09'),
(1398, 1, 116, 1, 0, 0, 0, '2019-12-02 05:21:54', '2025-05-10 12:46:09'),
(1399, 1, 117, 1, 0, 0, 0, '2019-12-02 05:22:04', '2025-05-10 12:46:09'),
(1400, 1, 118, 1, 0, 0, 0, '2019-12-02 05:22:20', '2025-05-10 12:46:09'),
(1402, 1, 191, 1, 0, 0, 0, '2019-12-02 05:23:34', '2025-05-10 12:46:09'),
(1403, 1, 192, 1, 0, 0, 0, '2019-12-02 05:23:47', '2025-05-10 12:46:09'),
(1404, 1, 193, 1, 0, 0, 0, '2019-12-02 05:23:58', '2025-05-10 12:46:09'),
(1405, 1, 194, 1, 0, 0, 0, '2019-12-02 05:24:11', '2025-05-10 12:46:09'),
(1406, 1, 195, 1, 0, 0, 0, '2019-12-02 05:24:20', '2025-05-10 12:46:09'),
(1408, 1, 227, 1, 0, 0, 0, '2019-12-02 05:25:47', '2025-05-10 12:46:09'),
(1410, 1, 226, 1, 0, 0, 0, '2019-12-02 05:31:41', '2025-05-10 12:46:09'),
(1411, 1, 229, 1, 0, 0, 0, '2019-12-02 05:32:57', '2025-05-10 12:46:09'),
(1412, 1, 230, 1, 0, 0, 0, '2019-12-02 05:32:57', '2025-05-10 12:46:09'),
(1413, 1, 190, 1, 0, 0, 0, '2019-12-02 05:43:41', '2025-05-10 12:46:09'),
(1414, 2, 174, 1, 0, 0, 0, '2019-12-02 05:54:37', '2025-05-10 12:46:09'),
(1415, 2, 175, 1, 0, 0, 0, '2019-12-02 05:54:37', '2025-05-10 12:46:09'),
(1418, 2, 232, 1, 0, 1, 1, '2019-12-02 06:11:27', '2025-05-10 12:46:09'),
(1419, 2, 231, 1, 0, 0, 0, '2019-12-02 06:12:28', '2025-05-10 12:46:09'),
(1420, 1, 231, 1, 1, 1, 1, '2021-01-23 07:09:32', '2025-05-10 12:46:09'),
(1421, 1, 232, 1, 0, 1, 1, '2019-12-02 06:19:32', '2025-05-10 12:46:09'),
(1422, 3, 32, 1, 1, 1, 1, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1423, 3, 33, 1, 1, 1, 1, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1424, 3, 34, 1, 1, 1, 1, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1425, 3, 182, 1, 0, 0, 0, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1426, 3, 183, 1, 0, 0, 0, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1427, 3, 189, 1, 0, 1, 1, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1428, 3, 229, 1, 0, 0, 0, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1429, 3, 230, 1, 0, 0, 0, '2019-12-02 06:30:37', '2025-05-10 12:46:09'),
(1430, 4, 213, 1, 0, 0, 0, '2019-12-02 06:32:14', '2025-05-10 12:46:09'),
(1432, 4, 224, 1, 0, 0, 0, '2019-12-02 06:32:14', '2025-05-10 12:46:09'),
(1433, 4, 195, 1, 0, 0, 0, '2019-12-03 04:57:53', '2025-05-10 12:46:09'),
(1434, 4, 229, 1, 0, 0, 0, '2019-12-03 04:58:19', '2025-05-10 12:46:09'),
(1436, 6, 213, 1, 0, 0, 0, '2019-12-03 05:10:11', '2025-05-10 12:46:09'),
(1437, 6, 191, 1, 0, 0, 0, '2019-12-03 05:10:11', '2025-05-10 12:46:09'),
(1438, 6, 193, 1, 0, 0, 0, '2019-12-03 05:10:11', '2025-05-10 12:46:09'),
(1439, 6, 230, 1, 0, 0, 0, '2019-12-03 05:10:11', '2025-05-10 12:46:09'),
(1440, 2, 106, 1, 0, 0, 0, '2020-01-25 04:21:36', '2025-05-10 12:46:09'),
(1441, 2, 107, 1, 0, 0, 0, '2020-02-12 02:10:13', '2025-05-10 12:46:09'),
(1442, 2, 134, 1, 1, 1, 1, '2020-02-12 02:12:36', '2025-05-10 12:46:09'),
(1443, 1, 233, 1, 0, 0, 0, '2020-02-12 02:21:57', '2025-05-10 12:46:09'),
(1444, 2, 86, 1, 0, 0, 0, '2020-02-12 02:22:33', '2025-05-10 12:46:09'),
(1445, 3, 233, 1, 0, 0, 0, '2020-02-12 03:51:17', '2025-05-10 12:46:09'),
(1446, 1, 234, 1, 1, 1, 1, '2020-06-01 21:51:09', '2025-05-10 12:46:09'),
(1447, 1, 235, 1, 1, 1, 1, '2020-05-29 23:17:01', '2025-05-10 12:46:09'),
(1448, 1, 236, 1, 1, 1, 0, '2020-05-29 23:17:52', '2025-05-10 12:46:09'),
(1449, 1, 237, 1, 0, 1, 0, '2020-05-29 23:18:18', '2025-05-10 12:46:09'),
(1450, 1, 238, 1, 1, 1, 1, '2020-05-29 23:19:52', '2025-05-10 12:46:09'),
(1451, 1, 239, 1, 1, 1, 1, '2020-05-29 23:22:10', '2025-05-10 12:46:09'),
(1452, 2, 236, 1, 1, 1, 0, '2020-05-29 23:40:33', '2025-05-10 12:46:09'),
(1453, 2, 237, 1, 0, 1, 0, '2020-05-29 23:40:33', '2025-05-10 12:46:09'),
(1454, 2, 238, 1, 1, 1, 1, '2020-05-29 23:40:33', '2025-05-10 12:46:09'),
(1455, 2, 239, 1, 1, 1, 1, '2020-05-29 23:40:33', '2025-05-10 12:46:09'),
(1456, 2, 240, 1, 0, 0, 0, '2020-05-28 20:51:18', '2025-05-10 12:46:09'),
(1457, 2, 241, 1, 0, 0, 0, '2020-05-28 20:51:18', '2025-05-10 12:46:09'),
(1458, 1, 240, 1, 0, 0, 0, '2020-06-07 18:30:42', '2025-05-10 12:46:09'),
(1459, 1, 241, 1, 0, 0, 0, '2020-06-07 18:30:42', '2025-05-10 12:46:09'),
(1460, 1, 242, 1, 0, 0, 0, '2020-06-07 18:30:42', '2025-05-10 12:46:09'),
(1461, 2, 242, 1, 0, 0, 0, '2020-06-11 22:45:24', '2025-05-10 12:46:09'),
(1462, 3, 242, 1, 0, 0, 0, '2020-06-14 22:46:54', '2025-05-10 12:46:09'),
(1463, 6, 242, 1, 0, 0, 0, '2020-06-14 22:48:14', '2025-05-10 12:46:09'),
(1464, 1, 243, 1, 0, 0, 0, '2020-09-12 06:05:45', '2025-05-10 12:46:09'),
(1465, 1, 109, 1, 1, 0, 0, '2020-09-21 06:33:50', '2025-05-10 12:46:09'),
(1466, 1, 108, 1, 0, 1, 1, '2023-11-04 12:52:08', '2025-05-10 12:46:09'),
(1467, 1, 244, 1, 0, 0, 0, '2020-09-21 06:59:54', '2025-05-10 12:46:09'),
(1468, 1, 245, 1, 0, 0, 0, '2020-09-21 06:59:54', '2025-05-10 12:46:09'),
(1469, 1, 246, 1, 0, 0, 0, '2020-09-21 06:59:54', '2025-05-10 12:46:09'),
(1470, 1, 247, 1, 0, 0, 0, '2021-01-07 06:12:14', '2025-05-10 12:46:09'),
(1472, 2, 247, 1, 0, 0, 0, '2021-01-21 12:46:40', '2025-05-10 12:46:09'),
(1473, 1, 248, 1, 1, 1, 1, '2021-05-19 12:52:49', '2025-05-10 12:46:09'),
(1474, 1, 249, 1, 0, 0, 0, '2021-05-19 12:52:49', '2025-05-10 12:46:09'),
(1475, 2, 248, 1, 1, 1, 1, '2021-05-28 13:11:52', '2025-05-10 12:46:09'),
(1476, 3, 248, 1, 1, 1, 1, '2021-05-28 09:36:16', '2025-05-10 12:46:09'),
(1477, 3, 249, 1, 0, 0, 0, '2021-05-28 09:36:16', '2025-05-10 12:46:09'),
(1478, 6, 248, 1, 0, 0, 0, '2021-05-28 09:56:14', '2025-05-10 12:46:09'),
(1479, 6, 249, 1, 0, 0, 0, '2021-05-28 09:56:14', '2025-05-10 12:46:09'),
(1480, 2, 249, 1, 0, 0, 0, '2021-05-28 13:11:52', '2025-05-10 12:46:09'),
(1481, 1, 269, 1, 0, 0, 1, '2023-11-04 12:52:08', '2025-05-10 12:46:09'),
(1482, 2, 269, 1, 0, 0, 1, '2023-11-04 12:52:28', '2025-05-10 12:46:09'),
(1483, 3, 269, 1, 0, 0, 1, '2023-11-04 12:53:22', '2025-05-10 12:46:09'),
(1484, 4, 269, 1, 0, 0, 1, '2023-11-04 12:53:34', '2025-05-10 12:46:09'),
(1485, 6, 269, 1, 0, 0, 1, '2023-11-04 12:53:52', '2025-05-10 12:46:09'),
(1486, 3, 5005, 1, 0, 0, 0, '2022-07-13 09:54:15', '2025-05-22 11:46:33'),
(1487, 2, 5005, 1, 0, 0, 0, '2022-07-13 10:20:33', '2025-05-22 11:46:33'),
(1488, 6, 5005, 1, 0, 0, 0, '2022-07-13 10:29:49', '2025-05-22 11:46:33'),
(1489, 1, 5005, 1, 0, 0, 0, '2022-07-13 10:57:22', '2025-05-22 11:46:33'),
(1490, 4, 5005, 1, 0, 0, 0, '2022-07-13 11:23:43', '2025-05-22 11:46:33'),
(1491, 3, 5004, 1, 0, 0, 0, '2020-06-14 13:03:50', '2025-05-22 11:46:33'),
(1492, 6, 5004, 1, 0, 0, 0, '2022-07-13 10:29:49', '2025-05-22 11:46:33'),
(1493, 2, 5004, 1, 0, 0, 0, '2022-07-13 10:20:19', '2025-05-22 11:46:33'),
(1494, 1, 5004, 1, 0, 0, 0, '2022-07-13 10:57:22', '2025-05-22 11:46:33'),
(1495, 4, 5004, 1, 0, 0, 0, '2022-07-13 11:23:43', '2025-05-22 11:46:33'),
(1496, 2, 5003, 1, 1, 0, 0, '2022-07-13 10:21:13', '2025-05-22 11:46:33'),
(1497, 6, 5003, 1, 0, 0, 1, '2022-07-13 10:33:55', '2025-05-22 11:46:33'),
(1498, 1, 5003, 1, 1, 0, 1, '2022-07-13 11:00:28', '2025-05-22 11:46:33'),
(1499, 4, 5003, 1, 1, 0, 0, '2022-07-13 11:38:07', '2025-05-22 11:46:33'),
(1500, 3, 5003, 1, 1, 0, 1, '2020-06-14 13:03:50', '2025-05-22 11:46:33'),
(1501, 2, 5002, 1, 1, 0, 0, '2022-07-13 10:25:20', '2025-05-22 11:46:33'),
(1502, 6, 5002, 1, 1, 0, 1, '2022-07-13 10:52:36', '2025-05-22 11:46:33'),
(1503, 1, 5002, 1, 1, 0, 1, '2022-07-13 11:00:28', '2025-05-22 11:46:33'),
(1504, 4, 5002, 1, 1, 0, 0, '2022-07-13 11:38:07', '2025-05-22 11:46:33'),
(1505, 2, 5001, 1, 0, 1, 0, '2022-07-13 10:28:26', '2025-05-22 11:46:33'),
(1506, 1, 5001, 1, 0, 1, 0, '2022-07-13 11:00:36', '2025-05-22 11:46:33'),
(1507, 6, 5001, 1, 0, 1, 0, '2022-07-13 10:52:36', '2025-05-22 11:46:33'),
(1508, 4, 5001, 1, 0, 0, 0, '2022-07-13 11:49:49', '2025-05-22 11:46:33'),
(1509, 2, 6001, 1, 1, 0, 1, '2021-01-29 09:31:16', '2025-05-22 11:46:36'),
(1510, 1, 6001, 1, 1, 0, 1, '2020-09-23 12:59:22', '2025-05-22 11:46:36'),
(1511, 1, 6002, 1, 1, 0, 1, '2020-09-23 13:01:52', '2025-05-22 11:46:36'),
(1512, 1, 6003, 1, 0, 0, 0, '2020-09-23 13:02:06', '2025-05-22 11:46:36'),
(1513, 1, 6004, 1, 0, 0, 0, '2020-09-23 13:02:33', '2025-05-22 11:46:36'),
(1514, 1, 6005, 1, 0, 1, 0, '2021-01-29 07:45:05', '2025-05-22 11:46:36'),
(1515, 2, 6005, 1, 0, 1, 0, '2021-01-29 11:08:20', '2025-05-22 11:46:36'),
(1516, 2, 6002, 1, 1, 0, 1, '2021-01-29 07:58:48', '2025-05-22 11:46:36'),
(1517, 3, 6002, 1, 1, 0, 1, '2021-01-29 07:26:08', '2025-05-22 11:46:36'),
(1518, 3, 6003, 1, 0, 0, 0, '2021-01-29 07:26:08', '2025-05-22 11:46:36'),
(1519, 3, 6005, 1, 0, 1, 0, '2021-01-29 07:26:08', '2025-05-22 11:46:36'),
(1520, 4, 6001, 1, 1, 0, 1, '2021-01-29 07:26:53', '2025-05-22 11:46:36'),
(1521, 4, 6002, 1, 1, 0, 1, '2021-01-29 07:26:53', '2025-05-22 11:46:36'),
(1522, 6, 6001, 1, 1, 0, 1, '2021-01-29 07:27:32', '2025-05-22 11:46:36'),
(1523, 6, 6002, 1, 1, 0, 1, '2021-01-29 07:27:32', '2025-05-22 11:46:36'),
(1524, 6, 6005, 1, 0, 1, 0, '2021-01-29 07:27:32', '2025-05-22 11:46:36'),
(1525, 2, 6003, 1, 0, 0, 0, '2021-01-29 09:31:16', '2025-05-22 11:46:36'),
(1526, 2, 6004, 1, 0, 0, 0, '2021-01-29 09:31:16', '2025-05-22 11:46:36'),
(1527, 4, 6005, 1, 0, 1, 0, '2022-07-13 04:28:08', '2025-05-22 11:46:36'),
(1528, 1, 7001, 1, 1, 1, 1, '2021-05-11 07:21:33', '2025-05-22 11:46:41'),
(1529, 1, 7002, 1, 0, 0, 0, '2021-05-17 05:28:47', '2025-05-22 11:46:41'),
(1530, 1, 7003, 1, 1, 1, 1, '2021-05-11 08:29:37', '2025-05-22 11:46:41'),
(1531, 1, 7005, 1, 1, 1, 1, '2021-05-17 05:28:47', '2025-05-22 11:46:41'),
(1532, 1, 7004, 1, 1, 1, 1, '2021-05-11 10:00:50', '2025-05-22 11:46:41'),
(1533, 1, 7006, 1, 1, 0, 0, '2021-05-17 05:28:47', '2025-05-22 11:46:41'),
(1534, 1, 7007, 1, 0, 0, 0, '2021-05-11 10:00:50', '2025-05-22 11:46:41'),
(1535, 1, 7008, 1, 0, 0, 0, '2021-05-11 10:00:50', '2025-05-22 11:46:41'),
(1536, 1, 7009, 1, 0, 0, 0, '2021-05-11 10:00:50', '2025-05-22 11:46:41'),
(1537, 1, 7010, 1, 0, 0, 0, '2021-05-11 10:00:50', '2025-05-22 11:46:41'),
(1538, 2, 7001, 1, 1, 1, 1, '2021-05-15 11:07:28', '2025-05-22 11:46:41'),
(1539, 2, 7002, 1, 0, 0, 0, '2021-05-17 10:51:44', '2025-05-22 11:46:41'),
(1540, 2, 7003, 1, 1, 1, 1, '2021-05-15 10:28:38', '2025-05-22 11:46:41'),
(1541, 2, 7004, 1, 1, 1, 1, '2021-05-15 10:28:38', '2025-05-22 11:46:41'),
(1542, 3, 7006, 1, 1, 0, 0, '2021-05-17 10:52:19', '2025-05-22 11:46:41'),
(1543, 3, 7007, 1, 0, 0, 0, '2021-05-17 04:32:06', '2025-05-22 11:46:41'),
(1544, 1, 7011, 1, 0, 0, 0, '2021-05-17 05:28:47', '2025-05-22 11:46:41'),
(1545, 2, 7005, 1, 1, 1, 1, '2021-05-17 10:51:44', '2025-05-22 11:46:41'),
(1546, 2, 7010, 1, 0, 0, 0, '2021-05-17 10:51:44', '2025-05-22 11:46:41'),
(1547, 1, 7012, 1, 0, 0, 1, '2022-06-20 04:20:01', '2025-05-22 11:46:41'),
(1548, 1, 7013, 1, 0, 1, 1, '2022-06-18 09:52:16', '2025-05-22 11:46:41'),
(1549, 1, 7014, 1, 1, 1, 1, '2021-05-15 10:28:38', '2025-05-22 11:46:41'),
(1550, 6, 7014, 1, 1, 1, 1, '2022-06-18 09:09:15', '2025-05-22 11:46:41'),
(1551, 6, 7013, 1, 0, 0, 0, '2022-06-18 09:13:07', '2025-05-22 11:46:41'),
(1552, 4, 7012, 1, 0, 0, 0, '2022-07-15 04:22:20', '2025-05-22 11:46:41'),
(1553, 4, 7013, 1, 0, 1, 1, '2022-07-15 04:26:29', '2025-05-22 11:46:41'),
(1554, 4, 7014, 1, 1, 1, 1, '2022-07-15 04:24:48', '2025-05-22 11:46:41'),
(1555, 2, 7012, 1, 0, 0, 1, '2022-07-15 05:01:12', '2025-05-22 11:46:41'),
(1556, 2, 7013, 1, 0, 0, 1, '2022-07-15 05:01:12', '2025-05-22 11:46:41'),
(1557, 2, 7014, 1, 0, 0, 1, '2022-07-15 05:01:12', '2025-05-22 11:46:41'),
(1558, 3, 7012, 1, 0, 0, 0, '2022-07-15 05:31:18', '2025-05-22 11:46:41'),
(1559, 3, 7013, 1, 0, 0, 0, '2022-07-15 05:31:18', '2025-05-22 11:46:41'),
(1560, 3, 7014, 1, 0, 0, 0, '2022-07-15 05:31:18', '2025-05-22 11:46:41'),
(1561, 1, 7015, 1, 1, 1, 1, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1562, 1, 7016, 1, 1, 1, 1, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1563, 1, 7017, 1, 1, 1, 1, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1564, 1, 7018, 1, 1, 1, 1, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1565, 1, 7019, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1566, 1, 7020, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1567, 1, 7021, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1568, 1, 7022, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1569, 1, 7023, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1570, 1, 7024, 1, 0, 0, 0, '2021-05-11 07:21:33', '2025-05-22 11:46:49'),
(1571, 1, 8001, 1, 1, 0, 1, '2022-05-05 07:00:06', '2025-05-22 11:46:53'),
(1572, 2, 8003, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:53'),
(1573, 2, 8004, 1, 0, 0, 0, '2022-05-05 06:50:42', '2025-05-22 11:46:53'),
(1574, 2, 8005, 1, 0, 0, 0, '2022-05-05 06:50:59', '2025-05-22 11:46:53'),
(1575, 2, 8006, 1, 0, 0, 0, '2022-05-05 06:51:17', '2025-05-22 11:46:53'),
(1576, 1, 8002, 1, 1, 1, 1, '2022-05-05 07:01:15', '2025-05-22 11:46:53'),
(1577, 1, 8003, 1, 0, 0, 0, '2022-05-05 07:01:49', '2025-05-22 11:46:53'),
(1578, 1, 8004, 1, 0, 0, 0, '2022-05-05 07:05:12', '2025-05-22 11:46:53'),
(1579, 1, 8005, 1, 0, 0, 0, '2022-05-05 07:08:53', '2025-05-22 11:46:53'),
(1580, 1, 8006, 1, 0, 0, 0, '2022-05-05 07:10:42', '2025-05-22 11:46:53'),
(1581, 2, 8008, 1, 0, 0, 0, '2022-05-05 07:11:34', '2025-05-22 11:46:53'),
(1582, 1, 8007, 1, 0, 0, 0, '2022-05-05 07:12:37', '2025-05-22 11:46:53'),
(1583, 1, 8008, 1, 0, 0, 0, '2022-05-05 07:15:07', '2025-05-22 11:46:53'),
(1584, 1, 8009, 1, 0, 1, 0, '2022-05-05 08:19:18', '2025-05-22 11:46:53'),
(1585, 2, 8007, 1, 0, 0, 0, '2022-05-05 07:26:02', '2025-05-22 11:46:53'),
(1586, 2, 8002, 1, 1, 1, 1, '2022-05-05 09:47:39', '2025-05-22 11:46:53'),
(1587, 2, 8009, 1, 0, 1, 0, '2022-05-05 08:25:01', '2025-05-22 11:46:53'),
(1588, 2, 8001, 1, 1, 0, 1, '2022-05-05 09:47:39', '2025-05-22 11:46:53'),
(1589, 4, 8001, 1, 1, 0, 1, '2022-05-05 08:37:50', '2025-05-22 11:46:53'),
(1590, 4, 8002, 1, 1, 1, 1, '2022-05-05 08:37:50', '2025-05-22 11:46:53'),
(1591, 4, 8003, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1592, 4, 8004, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1593, 4, 8005, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1594, 4, 8006, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1595, 4, 8007, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1596, 4, 8008, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1597, 4, 8009, 1, 0, 0, 0, '2022-05-05 08:32:25', '2025-05-22 11:46:53'),
(1598, 3, 8001, 1, 1, 0, 1, '2022-05-05 08:36:56', '2025-05-22 11:46:53'),
(1599, 3, 8002, 1, 1, 1, 1, '2022-05-05 08:42:21', '2025-05-22 11:46:53'),
(1600, 3, 8003, 1, 0, 0, 0, '2022-05-05 08:44:16', '2025-05-22 11:46:53'),
(1601, 3, 8004, 1, 0, 0, 0, '2022-05-05 08:49:05', '2025-05-22 11:46:53'),
(1602, 3, 8005, 1, 0, 0, 0, '2022-05-05 08:54:09', '2025-05-22 11:46:53'),
(1603, 3, 8006, 1, 0, 0, 0, '2022-05-05 08:57:04', '2025-05-22 11:46:53'),
(1604, 3, 8007, 1, 0, 0, 0, '2022-05-05 09:00:02', '2025-05-22 11:46:53'),
(1605, 3, 8008, 1, 0, 0, 0, '2022-05-05 09:02:30', '2025-05-22 11:46:53'),
(1606, 3, 8009, 1, 0, 1, 0, '2022-05-05 09:09:34', '2025-05-22 11:46:53'),
(1607, 1, 10001, 1, 0, 0, 0, '2022-05-05 07:00:06', '2025-05-22 11:46:56'),
(1608, 1, 10002, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1609, 1, 10003, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1610, 1, 10004, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1611, 1, 10005, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1612, 1, 10006, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1613, 1, 10007, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:46:56'),
(1614, 1, 11001, 1, 0, 0, 0, '2022-05-05 07:00:06', '2025-05-22 11:47:02'),
(1615, 1, 11002, 1, 0, 0, 0, '2022-05-05 06:50:12', '2025-05-22 11:47:02'),
(1616, 1, 13001, 1, 0, 0, 0, '2025-01-17 06:26:55', '2025-05-22 11:47:24'),
(1617, 1, 14001, 1, 0, 0, 0, '2025-01-17 06:26:55', '2025-05-22 11:47:33'),
(1618, 1, 14002, 1, 1, 1, 1, '2025-10-10 11:34:33', '2025-10-10 11:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `room_type` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route_pickup_point`
--

CREATE TABLE `route_pickup_point` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `transport_route_id` int(11) NOT NULL,
  `pickup_point_id` int(11) NOT NULL,
  `fees` float(10,2) DEFAULT 0.00,
  `destination_distance` float(10,1) DEFAULT 0.0,
  `pickup_time` time DEFAULT NULL,
  `order_number` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_houses`
--

CREATE TABLE `school_houses` (
  `id` int(11) NOT NULL,
  `house_name` varchar(200) NOT NULL,
  `description` varchar(400) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sch_settings`
--

CREATE TABLE `sch_settings` (
  `id` int(11) NOT NULL,
  `base_url` varchar(500) DEFAULT NULL,
  `folder_path` text DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `biometric` int(11) DEFAULT 0,
  `biometric_device` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `languages` varchar(500) NOT NULL,
  `dise_code` varchar(50) DEFAULT NULL,
  `date_format` varchar(50) NOT NULL,
  `time_format` varchar(255) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `currency_symbol` varchar(50) NOT NULL,
  `is_rtl` varchar(10) DEFAULT 'disabled',
  `is_duplicate_fees_invoice` varchar(100) DEFAULT '0',
  `collect_back_date_fees` int(11) NOT NULL,
  `single_page_print` int(11) DEFAULT 0,
  `timezone` varchar(30) DEFAULT 'UTC',
  `session_id` int(11) DEFAULT NULL,
  `cron_secret_key` varchar(100) NOT NULL,
  `currency_place` varchar(50) NOT NULL DEFAULT 'before_number',
  `currency_format` varchar(20) DEFAULT NULL,
  `class_teacher` varchar(100) NOT NULL,
  `start_month` varchar(40) NOT NULL,
  `attendence_type` int(11) NOT NULL DEFAULT 0,
  `low_attendance_limit` float(10,2) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `admin_logo` varchar(255) NOT NULL,
  `admin_small_logo` varchar(255) NOT NULL,
  `admin_login_page_background` varchar(255) NOT NULL,
  `user_login_page_background` varchar(255) NOT NULL,
  `theme` varchar(200) NOT NULL DEFAULT 'default.jpg',
  `fee_due_days` int(11) DEFAULT 0,
  `adm_auto_insert` int(11) NOT NULL DEFAULT 1,
  `adm_prefix` varchar(50) NOT NULL DEFAULT 'ssadm19/20',
  `adm_start_from` varchar(11) NOT NULL,
  `adm_no_digit` int(11) NOT NULL DEFAULT 6,
  `adm_update_status` int(11) NOT NULL DEFAULT 0,
  `staffid_auto_insert` int(11) NOT NULL DEFAULT 1,
  `staffid_prefix` varchar(100) NOT NULL DEFAULT 'staffss/19/20',
  `staffid_start_from` varchar(50) NOT NULL,
  `staffid_no_digit` int(11) NOT NULL DEFAULT 6,
  `staffid_update_status` int(11) NOT NULL DEFAULT 0,
  `is_active` varchar(255) DEFAULT 'no',
  `online_admission` int(11) DEFAULT 0,
  `online_admission_payment` varchar(50) NOT NULL,
  `online_admission_amount` float NOT NULL,
  `online_admission_instruction` text NOT NULL,
  `online_admission_conditions` text NOT NULL,
  `online_admission_application_form` varchar(255) DEFAULT NULL,
  `exam_result` int(11) NOT NULL,
  `is_blood_group` int(11) NOT NULL DEFAULT 1,
  `is_student_house` int(11) NOT NULL DEFAULT 1,
  `roll_no` int(11) NOT NULL DEFAULT 1,
  `category` int(11) NOT NULL,
  `religion` int(11) NOT NULL DEFAULT 1,
  `cast` int(11) NOT NULL DEFAULT 1,
  `mobile_no` int(11) NOT NULL DEFAULT 1,
  `student_email` int(11) NOT NULL DEFAULT 1,
  `admission_date` int(11) NOT NULL DEFAULT 1,
  `lastname` int(11) NOT NULL,
  `middlename` int(11) NOT NULL DEFAULT 1,
  `student_photo` int(11) NOT NULL DEFAULT 1,
  `student_height` int(11) NOT NULL DEFAULT 1,
  `student_weight` int(11) NOT NULL DEFAULT 1,
  `measurement_date` int(11) NOT NULL DEFAULT 1,
  `father_name` int(11) NOT NULL DEFAULT 1,
  `father_phone` int(11) NOT NULL DEFAULT 1,
  `father_occupation` int(11) NOT NULL DEFAULT 1,
  `father_pic` int(11) NOT NULL DEFAULT 1,
  `mother_name` int(11) NOT NULL DEFAULT 1,
  `mother_phone` int(11) NOT NULL DEFAULT 1,
  `mother_occupation` int(11) NOT NULL DEFAULT 1,
  `mother_pic` int(11) NOT NULL DEFAULT 1,
  `guardian_name` int(11) NOT NULL,
  `guardian_relation` int(11) NOT NULL DEFAULT 1,
  `guardian_phone` int(11) NOT NULL,
  `guardian_email` int(11) NOT NULL DEFAULT 1,
  `guardian_pic` int(11) NOT NULL DEFAULT 1,
  `guardian_occupation` int(11) NOT NULL,
  `guardian_address` int(11) NOT NULL DEFAULT 1,
  `current_address` int(11) NOT NULL DEFAULT 1,
  `permanent_address` int(11) NOT NULL DEFAULT 1,
  `route_list` int(11) NOT NULL DEFAULT 1,
  `hostel_id` int(11) NOT NULL DEFAULT 1,
  `bank_account_no` int(11) NOT NULL DEFAULT 1,
  `ifsc_code` int(11) NOT NULL,
  `bank_name` int(11) NOT NULL,
  `national_identification_no` int(11) NOT NULL DEFAULT 1,
  `local_identification_no` int(11) NOT NULL DEFAULT 1,
  `rte` int(11) NOT NULL DEFAULT 1,
  `previous_school_details` int(11) NOT NULL DEFAULT 1,
  `student_note` int(11) NOT NULL DEFAULT 1,
  `upload_documents` int(11) NOT NULL DEFAULT 1,
  `student_barcode` int(11) NOT NULL DEFAULT 1,
  `staff_designation` int(11) NOT NULL DEFAULT 1,
  `staff_department` int(11) NOT NULL DEFAULT 1,
  `staff_last_name` int(11) NOT NULL DEFAULT 1,
  `staff_father_name` int(11) NOT NULL DEFAULT 1,
  `staff_mother_name` int(11) NOT NULL DEFAULT 1,
  `staff_date_of_joining` int(11) NOT NULL DEFAULT 1,
  `staff_phone` int(11) NOT NULL DEFAULT 1,
  `staff_emergency_contact` int(11) NOT NULL DEFAULT 1,
  `staff_marital_status` int(11) NOT NULL DEFAULT 1,
  `staff_photo` int(11) NOT NULL DEFAULT 1,
  `staff_current_address` int(11) NOT NULL DEFAULT 1,
  `staff_permanent_address` int(11) NOT NULL DEFAULT 1,
  `staff_qualification` int(11) NOT NULL DEFAULT 1,
  `staff_work_experience` int(11) NOT NULL DEFAULT 1,
  `staff_note` int(11) NOT NULL DEFAULT 1,
  `staff_epf_no` int(11) NOT NULL DEFAULT 1,
  `staff_basic_salary` int(11) NOT NULL DEFAULT 1,
  `staff_contract_type` int(11) NOT NULL DEFAULT 1,
  `staff_work_shift` int(11) NOT NULL DEFAULT 1,
  `staff_work_location` int(11) NOT NULL DEFAULT 1,
  `staff_leaves` int(11) NOT NULL DEFAULT 1,
  `staff_account_details` int(11) NOT NULL DEFAULT 1,
  `staff_social_media` int(11) NOT NULL DEFAULT 1,
  `staff_upload_documents` int(11) NOT NULL DEFAULT 1,
  `staff_barcode` int(11) NOT NULL DEFAULT 1,
  `staff_notification_email` varchar(50) NOT NULL,
  `mobile_api_url` tinytext NOT NULL,
  `app_primary_color_code` varchar(20) DEFAULT NULL,
  `app_secondary_color_code` varchar(20) DEFAULT NULL,
  `admin_mobile_api_url` tinytext NOT NULL,
  `admin_app_primary_color_code` varchar(20) NOT NULL,
  `admin_app_secondary_color_code` varchar(20) NOT NULL,
  `app_logo` varchar(250) DEFAULT NULL,
  `zoom_api_key` varchar(100) DEFAULT NULL,
  `zoom_api_secret` varchar(100) DEFAULT NULL,
  `student_profile_edit` int(11) NOT NULL DEFAULT 0,
  `start_week` varchar(10) NOT NULL,
  `my_question` int(11) NOT NULL,
  `superadmin_restriction` varchar(20) NOT NULL,
  `student_timeline` varchar(20) NOT NULL,
  `calendar_event_reminder` int(11) DEFAULT NULL,
  `event_reminder` varchar(20) NOT NULL,
  `student_login` varchar(100) DEFAULT NULL,
  `parent_login` varchar(100) DEFAULT NULL,
  `student_panel_login` int(11) NOT NULL DEFAULT 1,
  `parent_panel_login` int(11) NOT NULL DEFAULT 1,
  `is_student_feature_lock` int(11) NOT NULL DEFAULT 0,
  `maintenance_mode` int(11) NOT NULL DEFAULT 0,
  `lock_grace_period` int(11) NOT NULL DEFAULT 0,
  `is_offline_fee_payment` int(11) NOT NULL DEFAULT 0,
  `offline_bank_payment_instruction` text NOT NULL,
  `scan_code_type` varchar(50) NOT NULL DEFAULT 'barcode',
  `student_resume_download` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `download_admit_card` int(11) NOT NULL DEFAULT 0,
  `fees_discount` int(11) NOT NULL,
  `front_side_whatsapp` int(11) NOT NULL DEFAULT 0,
  `front_side_whatsapp_mobile` varchar(50) DEFAULT NULL,
  `front_side_whatsapp_from` time DEFAULT NULL,
  `front_side_whatsapp_to` time DEFAULT NULL,
  `admin_panel_whatsapp` int(11) NOT NULL DEFAULT 0,
  `admin_panel_whatsapp_mobile` varchar(50) DEFAULT NULL,
  `admin_panel_whatsapp_from` time DEFAULT NULL,
  `admin_panel_whatsapp_to` time DEFAULT NULL,
  `student_panel_whatsapp` int(11) NOT NULL DEFAULT 0,
  `student_panel_whatsapp_mobile` varchar(50) DEFAULT NULL,
  `student_panel_whatsapp_from` time DEFAULT NULL,
  `student_panel_whatsapp_to` time DEFAULT NULL,
  `saas_key` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sch_settings`
--

INSERT INTO `sch_settings` (`id`, `base_url`, `folder_path`, `name`, `biometric`, `biometric_device`, `email`, `phone`, `address`, `lang_id`, `languages`, `dise_code`, `date_format`, `time_format`, `currency`, `currency_symbol`, `is_rtl`, `is_duplicate_fees_invoice`, `collect_back_date_fees`, `single_page_print`, `timezone`, `session_id`, `cron_secret_key`, `currency_place`, `currency_format`, `class_teacher`, `start_month`, `attendence_type`, `low_attendance_limit`, `image`, `admin_logo`, `admin_small_logo`, `admin_login_page_background`, `user_login_page_background`, `theme`, `fee_due_days`, `adm_auto_insert`, `adm_prefix`, `adm_start_from`, `adm_no_digit`, `adm_update_status`, `staffid_auto_insert`, `staffid_prefix`, `staffid_start_from`, `staffid_no_digit`, `staffid_update_status`, `is_active`, `online_admission`, `online_admission_payment`, `online_admission_amount`, `online_admission_instruction`, `online_admission_conditions`, `online_admission_application_form`, `exam_result`, `is_blood_group`, `is_student_house`, `roll_no`, `category`, `religion`, `cast`, `mobile_no`, `student_email`, `admission_date`, `lastname`, `middlename`, `student_photo`, `student_height`, `student_weight`, `measurement_date`, `father_name`, `father_phone`, `father_occupation`, `father_pic`, `mother_name`, `mother_phone`, `mother_occupation`, `mother_pic`, `guardian_name`, `guardian_relation`, `guardian_phone`, `guardian_email`, `guardian_pic`, `guardian_occupation`, `guardian_address`, `current_address`, `permanent_address`, `route_list`, `hostel_id`, `bank_account_no`, `ifsc_code`, `bank_name`, `national_identification_no`, `local_identification_no`, `rte`, `previous_school_details`, `student_note`, `upload_documents`, `student_barcode`, `staff_designation`, `staff_department`, `staff_last_name`, `staff_father_name`, `staff_mother_name`, `staff_date_of_joining`, `staff_phone`, `staff_emergency_contact`, `staff_marital_status`, `staff_photo`, `staff_current_address`, `staff_permanent_address`, `staff_qualification`, `staff_work_experience`, `staff_note`, `staff_epf_no`, `staff_basic_salary`, `staff_contract_type`, `staff_work_shift`, `staff_work_location`, `staff_leaves`, `staff_account_details`, `staff_social_media`, `staff_upload_documents`, `staff_barcode`, `staff_notification_email`, `mobile_api_url`, `app_primary_color_code`, `app_secondary_color_code`, `admin_mobile_api_url`, `admin_app_primary_color_code`, `admin_app_secondary_color_code`, `app_logo`, `zoom_api_key`, `zoom_api_secret`, `student_profile_edit`, `start_week`, `my_question`, `superadmin_restriction`, `student_timeline`, `calendar_event_reminder`, `event_reminder`, `student_login`, `parent_login`, `student_panel_login`, `parent_panel_login`, `is_student_feature_lock`, `maintenance_mode`, `lock_grace_period`, `is_offline_fee_payment`, `offline_bank_payment_instruction`, `scan_code_type`, `student_resume_download`, `created_at`, `updated_at`, `download_admit_card`, `fees_discount`, `front_side_whatsapp`, `front_side_whatsapp_mobile`, `front_side_whatsapp_from`, `front_side_whatsapp_to`, `admin_panel_whatsapp`, `admin_panel_whatsapp_mobile`, `admin_panel_whatsapp_from`, `admin_panel_whatsapp_to`, `student_panel_whatsapp`, `student_panel_whatsapp_mobile`, `student_panel_whatsapp_from`, `student_panel_whatsapp_to`, `saas_key`) VALUES
(1, 'https://rhemazimbabwe.org/', '/var/www/rhemazimbabwe.org/public_html/', 'Rhema Bible Training Centre Zimbabwe', 1, '', 'admin@rhemazimbabwe.org', '+ 260 969 731 477', 'Bulawayo, Zimbabwe', 4, '[\"4\"]', 'RBTCZ', 'd-m-Y', '12-hour', '150', '$', 'disabled', '0', 1, 1, 'Africa/Lusaka', 21, '', 'after_number', '#,###.##', 'no', '1', 0, 80.00, '1748325333-939599944683553d575afd!RHEMA-SEAL- 170x184.jpg', '1751545918-13255683606866783ebb242!Rhema logo.png', '1751545662-11961205376866773e3c24b!RHEMA-SEAL-290x290.jpg', '1748325722-3024168786835555a688cb!9_Aug2021_FEATURE_RBTCGraduation_Graphic-846x508.png', '1752578297-973597546687638f9b7a69!find your purpose.jpg', 'red.jpg', 60, 0, '0000', '0000', 4, 1, 0, '', '00000', 5, 1, 'no', 1, 'no', 0, '<span style=\"color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\">General Instruction:-&nbsp;These instructions pertain to online application for admission to Rhema Bible Training Centre Zimbabwe for the academic year 2027-28.</span><br style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\" />\r\n<span style=\"color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\">1.F</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">ill online admission form,&nbsp;<span style=\"box-sizing: border-box; font-weight: 700;\">Basic Details like&nbsp;</span><span style=\"box-sizing: border-box;\">( Class, First Name, Last Name, Gender, Date of Birth, Mobile Number, Email) etc.</span></span><br style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\" />\r\n<span style=\"color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\">2. Upload required documents which are your ID and educational qualifications&nbsp;</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">then click on the&nbsp;</span><span style=\"box-sizing: border-box; font-weight: 700; color: rgb(51, 51, 51); font-size: 14px; -webkit-tap-highlight-color: transparent; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">Submit</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">&nbsp;button.</span><br style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\" />\r\n<span style=\"color: rgb(51, 51, 51); font-family: Roboto, sans-serif; font-size: 14px; background-color: rgb(255, 255, 255);\">3.&nbsp;</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">After submitting form, this will redirect you in&nbsp;</span><span style=\"box-sizing: border-box; font-weight: 700; color: rgb(51, 51, 51); font-size: 14px; -webkit-tap-highlight-color: transparent; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">Online Admission Review Details</span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; font-family: Roboto, Helvetica, Arial, sans-serif; background-color: rgb(255, 255, 255);\">&nbsp;page where you can check your details what you have filled previously then submit form.</span>', '<p>&nbsp;Please enter your institution online admission terms &amp; conditions here.</p>\r\n', NULL, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'kkamudyariwa@gmail.com', '', '#424242', '#eeeeee', '', '', '', '1748325521-73787071168355491effd3!RBTC_Logo_Web_OL_2_Color.png', NULL, NULL, 1, 'Monday', 1, 'enabled', 'disabled', 3, 'enabled', '[\"admission_no\",\"mobile_number\",\"email\"]', 'null', 1, 0, 0, 0, 0, 0, '', 'barcode', 1, '2025-05-10 12:21:29', '2025-08-05 12:56:34', 0, 0, 1, '+260969731477', '08:30:00', '17:00:00', 1, '+260969731477', '08:00:00', '17:00:00', 1, '+260969731477', '08:00:00', '17:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Morning', 'no', '2025-05-26 09:20:02', '2025-05-26 09:21:29'),
(3, 'Evening', 'no', '2025-05-26 09:20:08', '2025-05-26 09:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `send_notification`
--

CREATE TABLE `send_notification` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `attachment` varchar(500) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `visible_student` varchar(10) NOT NULL DEFAULT 'no',
  `visible_staff` varchar(10) NOT NULL DEFAULT 'no',
  `visible_parent` varchar(10) NOT NULL DEFAULT 'no',
  `created_by` varchar(60) DEFAULT NULL,
  `created_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session`, `is_active`, `created_at`, `updated_at`) VALUES
(7, '2016-17', 'no', '2017-04-20 06:42:19', '0000-00-00 00:00:00'),
(11, '2017-18', 'no', '2017-04-20 06:41:37', '0000-00-00 00:00:00'),
(13, '2018-19', 'no', '2016-08-24 19:26:44', '0000-00-00 00:00:00'),
(14, '2019-20', 'no', '2016-08-24 19:26:55', '0000-00-00 00:00:00'),
(15, '2020-21', 'no', '2016-10-01 05:28:08', '0000-00-00 00:00:00'),
(16, '2021-22', 'no', '2016-10-01 05:28:20', '0000-00-00 00:00:00'),
(18, '2022-23', 'no', '2016-10-01 05:29:02', '0000-00-00 00:00:00'),
(19, '2023-24', 'no', '2016-10-01 05:29:10', '0000-00-00 00:00:00'),
(20, '2024-25', 'no', '2016-10-01 05:29:18', '0000-00-00 00:00:00'),
(21, '2025-26', 'no', '2016-10-01 05:30:10', '0000-00-00 00:00:00'),
(22, '2026-27', 'no', '2016-10-01 05:30:18', '0000-00-00 00:00:00'),
(23, '2027-28', 'no', '2016-10-01 05:30:24', '0000-00-00 00:00:00'),
(24, '2028-29', 'no', '2016-10-01 05:30:30', '0000-00-00 00:00:00'),
(25, '2029-30', 'no', '2016-10-01 05:30:37', '0000-00-00 00:00:00'),
(26, '2025-2026', 'yes', '2025-10-10 15:21:31', '2025-10-10 15:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `share_contents`
--

CREATE TABLE `share_contents` (
  `id` int(11) NOT NULL,
  `send_to` varchar(50) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `share_date` date DEFAULT NULL,
  `valid_upto` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `share_contents`
--

INSERT INTO `share_contents` (`id`, `send_to`, `title`, `share_date`, `valid_upto`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'individual', 'Life Of Faith 27052025', '2025-05-29', '2025-12-31', '', 1, '2025-05-29 12:44:43', '2025-05-29 12:44:43'),
(2, 'class', 'The Believer\'s Authority- Kenneth E Hagin', '2025-06-04', '2025-06-30', '', 1, '2025-06-04 08:33:46', '2025-06-04 08:33:46'),
(3, 'group', 'Believers Authority', '2025-06-04', '2025-06-07', '', 1, '2025-06-04 08:35:04', '2025-06-04 08:35:04'),
(4, 'group', 'Ephesians (Morning Class) 05052025', '2025-06-05', '2025-12-31', 'Ephesians class', 1, '2025-06-05 08:03:58', '2025-06-05 08:03:58'),
(5, 'class', 'Life Of Faith Notes', '2025-06-18', '2025-07-02', '', 1, '2025-06-18 08:04:39', '2025-06-18 08:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `share_content_for`
--

CREATE TABLE `share_content_for` (
  `id` int(11) NOT NULL,
  `group_id` varchar(20) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `user_parent_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `share_content_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `share_content_for`
--

INSERT INTO `share_content_for` (`id`, `group_id`, `student_id`, `user_parent_id`, `staff_id`, `class_section_id`, `share_content_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, NULL, NULL, NULL, 1, '2025-05-29 12:44:43', '2025-05-29 12:44:43'),
(2, NULL, NULL, NULL, NULL, 3, 2, '2025-06-04 08:33:46', '2025-06-04 08:33:46'),
(3, NULL, NULL, NULL, NULL, 4, 2, '2025-06-04 08:33:46', '2025-06-04 08:33:46'),
(4, 'student', NULL, NULL, NULL, NULL, 3, '2025-06-04 08:35:04', '2025-06-04 08:35:04'),
(5, 'student', NULL, NULL, NULL, NULL, 4, '2025-06-05 08:03:58', '2025-06-05 08:03:58'),
(6, NULL, NULL, NULL, NULL, 1, 5, '2025-06-18 08:04:39', '2025-06-18 08:04:39'),
(7, NULL, NULL, NULL, NULL, 2, 5, '2025-06-18 08:04:39', '2025-06-18 08:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `share_upload_contents`
--

CREATE TABLE `share_upload_contents` (
  `id` int(11) NOT NULL,
  `upload_content_id` int(11) DEFAULT NULL,
  `share_content_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `share_upload_contents`
--

INSERT INTO `share_upload_contents` (`id`, `upload_content_id`, `share_content_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-05-29 12:44:43', '2025-05-29 12:44:43'),
(2, 2, 2, '2025-06-04 08:33:46', '2025-06-04 08:33:46'),
(3, 2, 3, '2025-06-04 08:35:04', '2025-06-04 08:35:04'),
(4, 3, 4, '2025-06-05 08:03:58', '2025-06-05 08:03:58'),
(5, 4, 5, '2025-06-18 08:04:39', '2025-06-18 08:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar_menus`
--

CREATE TABLE `sidebar_menus` (
  `id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `permission_group_id` int(11) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `menu` varchar(500) DEFAULT NULL,
  `activate_menu` varchar(100) DEFAULT NULL,
  `lang_key` varchar(250) NOT NULL,
  `system_level` int(11) DEFAULT 0,
  `level` int(11) DEFAULT NULL,
  `sidebar_display` int(11) DEFAULT 0,
  `access_permissions` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sidebar_menus`
--

INSERT INTO `sidebar_menus` (`id`, `product_name`, `permission_group_id`, `icon`, `menu`, `activate_menu`, `lang_key`, `system_level`, `level`, `sidebar_display`, `access_permissions`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '', 17, 'fa fa-ioxhost ftlayer', 'Front Office', 'front_office', 'front_office', 10, 1, 1, '(\'admission_enquiry\', \'can_view\') || (\'visitor_book\', \'can_view\') ||       (\'phon_call_log\', \'can_view\') ||  (\'postal_dispatch\', \'can_view\') ||       (\'postal_receive\', \'can_view\') || (\'complaint\', \'can_view\') ||(\'setup_font_office\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-10 12:46:10'),
(2, '', 1, 'fa fa-user-plus ftlayer', 'Student Information', 'student_information', 'student_information', 20, 2, 1, '(\'student\', \'can_view\') || (\'student\', \'can_add\') || (\'student_history\', \'can_view\') || (\'student_categories\', \'can_view\') || (\'student_houses\', \'can_view\') || (\'disable_student\', \'can_view\') || (\'disable_reason\', \'can_view\') || (\'online_admission\', \'can_view\') || (\'multiclass_student\', \'can_view\') || (\'disable_reason\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-10 12:46:10'),
(3, '', 2, 'fa fa-money ftlayer', 'Fees Collection', 'fees_collection', 'fees_collection', 30, 3, 1, '(\'collect_fees\', \'can_view\') || (\'search_fees_payment\', \'can_view\') || (\'search_due_fees\', \'can_view\') || (\'fees_statement\', \'can_view\') || (\'fees_carry_forward\', \'can_view\') || (\'fees_master\', \'can_view\') || (\'fees_group\', \'can_view\') || (\'fees_type\', \'can_view\') || (\'fees_discount\', \'can_view\') || (\'accountants\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-10 12:46:10'),
(4, '', 3, 'fa fa-usd ftlayer', 'Income', 'income', 'income', 40, 11, 1, '(\'income\', \'can_view\') || (\'search_income\', \'can_view\') || (\'income_head\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(7, '', 4, 'fa fa-credit-card ftlayer', 'Expense', 'expense', 'expenses', 50, 12, 1, '(\'expense\', \'can_view\') || (\'search_expense\', \'can_view\') || (\'expense_head\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(10, '', 5, 'fa fa-calendar-check-o ftlayer', 'Attendance', 'attendance', 'attendance', 60, 15, 1, '(\'student_attendance\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(11, '', 6, 'fa fa-map-o ftlayer', 'Examinations', 'examinations', 'examinations', 70, 14, 1, '(\'exam_group\', \'can_view\') || (\'exam_result\', \'can_view\') || (\'design_admit_card\', \'can_view\') || (\'print_admit_card\', \'can_view\') || (\'design_marksheet\', \'can_view\') || (\'print_marksheet\', \'can_view\') || (\'marks_grade\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(12, '', 23, 'fa fa-rss ftlayer', 'Online Examinations', 'online_examinations', 'online_examinations', 80, 17, 1, '(\'online_examination\', \'can_view\') ||  (\'question_bank\', \'can_view\'', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(13, '', 29, 'fa fa-list-alt ftlayer', 'Lesson Plan', 'lesson_plan', 'lesson_plan', 90, 19, 1, '(\'manage_lesson_plan\', \'can_view\') || (\'manage_syllabus_status\', \'can_view\') || (\'lesson\', \'can_view\') ||  (\'topic\', \'can_view\')||  (\'copy_old_lesson\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(14, '', 7, 'fa fa-mortar-board ftlayer', 'Academics', 'academics', 'academics', 100, 18, 1, '(\'class_timetable\', \'can_view\') || (\'teachers_timetable\', \'can_view\') || (\'assign_class_teacher\', \'can_view\') || (\'promote_student\', \'can_view\') || (\'subject_group\', \'can_view\') || (\'section\', \'can_view\') || (\'subject\', \'can_view\') || (\'class\', \'can_view\') || (\'section\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(15, '', 18, 'fa fa-sitemap ftlayer', 'Human Resource', 'human_resource', 'human_resource', 110, 21, 1, '(\'staff\', \'can_view\') || (\'approve_leave_request\', \'can_view\') || (\'apply_leave\', \'can_view\') || (\'leave_types\', \'can_view\') || (\'teachers_rating\', \'can_view\') || (\'department\', \'can_view\') || (\'designation\', \'can_view\') || (\'disable_staff\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(16, '', 13, 'fa fa-bullhorn ftlayer', 'Communicate', 'communicate', 'communicate', 120, 22, 1, '(\'notice_board\', \'can_view\') || (\'email\', \'can_view\') || (\'sms\', \'can_view\') || (\'email_sms_log\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(17, '', 8, 'fa fa-download ftlayer', 'Download Center', 'download_center', 'download_center', 130, 23, 1, '(\'upload_content\', \'can_view\') || (\'video_tutorial\', \'can_view\') || (\'content_type\', \'can_view\') || (\'content_share_list\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(18, '', 19, 'fa fa-flask ftlayer', 'Homework', 'homework', 'homework', 140, 24, 1, '(\'homework\', \'can_view\') || (\'homework\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(19, '', 9, 'fa fa-book ftlayer', 'Library', 'library', 'library', 150, 25, 1, '(\'books\', \'can_view\') || (\'issue_return\', \'can_view\') || (\'add_staff_member\', \'can_view\') || (\'add_student\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(20, '', 10, 'fa fa-object-group ftlayer', 'Inventory', 'inventory', 'inventory', 160, 26, 1, '(\'issue_item\', \'can_view\') || (\'item_stock\', \'can_view\') || (\'item\', \'can_view\') || (\'item_category\', \'can_view\') || (\'item_category\', \'can_view\') || (\'store\', \'can_view\') || (\'supplier\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(21, '', 11, 'fa fa-bus ftlayer', 'Transport', 'transport', 'transport', 170, 28, 1, '(\'routes\', \'can_view\') || (\'vehicle\', \'can_view\') || (\'assign_vehicle\', \'can_view\') || (\'transport_fees_master\', \'can_view\') || (\'pickup_point\', \'can_view\') || (\'route_pickup_point\', \'can_view\') || (\'student_transport_fees\', \'can_view\')      ', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(22, '', 12, 'fa fa-building-o ftlayer', 'Hostel', 'hostel', 'hostel', 180, 29, 1, '(\'hostel_rooms\', \'can_view\') || (\'room_type\', \'can_view\') || (\'hostel\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(23, '', 20, 'fa fa-newspaper-o ftlayer', 'Certificate', 'certificate', 'certificate', 190, 30, 1, '(\'student_certificate\', \'can_view\') || (\'generate_certificate\', \'can_view\') || (\'student_id_card\', \'can_view\') || (\'generate_id_card\', \'can_view\') || (\'staff_id_card\', \'can_view\') || (\'generate_staff_id_card\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(24, '', 16, 'fa fa-empire ftlayer', 'Front CMS', 'front_cms', 'front_cms', 200, 31, 1, '(\'event\', \'can_view\') || (\'gallery\', \'can_view\') || (\'notice\', \'can_view\') || (\'media_manager\', \'can_view\') || (\'pages\', \'can_view\') || (\'menus\', \'can_view\') || (\'banner_images\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(25, '', 28, 'fa fa-universal-access ftlayer', 'Alumni', 'alumni', 'alumni', 210, 32, 1, '(\'manage_alumni\', \'can_view\') || (\'events\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(26, '', 14, 'fa fa-line-chart ftlayer', 'Reports', 'reports', 'reports', 220, 33, 1, '(\'student_report\', \'can_view\') || (\'guardian_report\', \'can_view\') || (\'student_history\', \'can_view\') || (\'student_login_credential_report\', \'can_view\') || (\'class_subject_report\', \'can_view\') || (\'admission_report\', \'can_view\') || (\'sibling_report\', \'can_view\') || (\'evaluation_report\', \'can_view\') || (\'student_profile\', \'can_view\') || (\'fees_statement\', \'can_view\') || (\'balance_fees_report\', \'can_view\') || (\'fees_collection_report\', \'can_view\') || (\'online_fees_collection_report\', \'can_view\') || (\'income_report\', \'can_view\') || (\'expense_report\', \'can_view\') || (\'payroll_report\', \'can_view\') || (\'income_group_report\', \'can_view\') || (\'expense_group_report\', \'can_view\') || (\'attendance_report\', \'can_view\') || (\'staff_attendance_report\', \'can_view\') || (\'exam_marks_report\', \'can_view\') ||        (\'online_exam_wise_report\', \'can_view\') || (\'online_exams_report\', \'can_view\') || (\'online_exams_attempt_report\', \'can_view\') || (\'online_exams_rank_report\', \'can_view\') || (\'payroll_report\', \'can_view\') || (\'transport_report\', \'can_view\') || (\'hostel_report\', \'can_view\') || (\'audit_trail_report\', \'can_view\') || (\'user_log\', \'can_view\') || (\'book_issue_report\', \'can_view\') || (\'book_due_report\', \'can_view\') || (\'book_inventory_report\', \'can_view\') || (\'stock_report\', \'can_view\') ||      (\'add_item_report\', \'can_view\') || (\'issue_inventory_report\', \'can_view\') || (\'syllabus_status_report\', \'can_view\') ||    (\'teacher_syllabus_status_report\', \'can_view\') || (\'daily_collection_report\', \'can_view\') || (\'balance_fees_statement\', \'can_view\') || (\'balance_fees_report_with_remark\', \'can_view\')', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(27, '', 15, 'fa fa-gears ftlayer', 'System Settings', 'system_settings', 'system_setting', 230, 34, 1, '(\'general_setting\', \'can_view\') || (\'session_setting\', \'can_view\') || (\'notification_setting\', \'can_view\') || (\'sms_setting\', \'can_view\') || (\'email_setting\', \'can_view\') || (\'payment_methods\', \'can_view\') || (\'languages\', \'can_view\') || (\'user_status\', \'can_view\') || (\'backup_restore\', \'can_view\') || (\'print_header_footer\', \'can_view\') || (\'backup\', \'can_view\') || (\'front_cms_setting\', \'can_view\') || (\'custom_fields\', \'can_view\') || (\'system_fields\', \'can_view\') || (\'student_profile_update\', \'can_view\') || (\'currency\', \'can_view\') || (\'language_switcher\', \'can_view\') || (\'sidebar_menu\', \'can_view\') || (\'online_admission\', \'can_view\')\r\n', 1, '2023-01-10 12:49:37', '2025-05-29 12:19:13'),
(28, 'ssoclc', 700, 'fa fa-file-video-o ftlayer', 'Online Course', 'online_course', 'online_course', 0, 4, 1, '(\'online_course\', \'can_view\') || (\'online_course_offline_payment\', \'can_view\') || (\'student_course_purchase_report\', \'can_view\') || (\'course_sell_count_report\', \'can_view\') || (\'online_course_setting\', \'can_view\')|| (\'course_category\', \'can_view\') || (\'guest_report\', \'can_view\') || (\'course_rating_report\', \'can_view\') || (\'course_assignment_report\', \'can_view\') || (\'course_exam_result_report\', \'can_view\') || (\'course_exam_report\', \'can_view\') || (\'course_exam_attempt_report\', \'can_view\') || (\'online_course_question_bank\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-22 11:48:42'),
(29, 'ssglc', 600, 'fa fa-video-camera ftlayer', 'Gmeet Live Classes', 'gmeet_live_classes', 'gmeet_live_classes', 0, 9, 1, '(\'gmeet_live_classes\', \'can_view\')) || (\'gmeet_live_meeting\', \'can_view\') || (\'gmeet_live_meeting_report\', \'can_view\') || (\'gmeet_live_classes_report\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-29 12:19:13'),
(30, 'sszlc', 500, 'fa fa-video-camera ftlayer', 'Zoom Live Classes', 'zoom_live_classes', 'zoom_live_classes', 0, 10, 1, '(\'setting\', \'can_view\') || (\'live_classes\', \'can_view\') || (\'live_meeting\', \'can_view\') || (\'live_classes_report\', \'can_view\') || (\'live_meeting_report\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-29 12:19:13'),
(31, 'ssbr', 800, 'fa fa-map-signs ftlayer', 'Behaviour Records', 'behaviour_records', 'behaviour_records', 0, 5, 1, '(\'student_incident_report\', \'can_view\') || (\'student_behaviour_rank_report\', \'can_view\') || (\'class_wise_rank_report\', \'can_view\') || (\'class_section_wise_rank_report\', \'can_view\') || (\'house_wise_rank_report\', \'can_view\') || (\'incident_wise_report\', \'can_view\') || (\'behaviour_records_assign_incident\', \'can_view\') || (\'behaviour_records_incident\', \'can_view\') || (\'behaviour_records_setting\', \'can_view\')', 1, '2023-01-10 12:55:29', '2025-05-29 12:19:13'),
(32, 'sstfa', 1100, 'fa fa-lock ftlayer', 'Two Factor Authenticator', 'two_factor_authentication', 'two_factor_authentication', 0, 7, 1, '(\'google_authenticate_setting\', \'can_view\') || (\'google_authenticate_setup_two_fa\', \'can_view\')', 1, '2023-01-10 12:55:03', '2025-05-29 12:19:13'),
(33, 'ssmb', 1000, 'fa fa-sitemap ftlayer', 'Multi Branch', 'multi_branch', 'multi_branch', 0, 8, 1, '(\'multi_branch_overview\', \'can_view\') || (\'multi_branch_daily_collection_report\', \'can_view\') || (\'multi_branch_payroll\', \'can_view\') || (\'multi_branch_income_report\', \'can_view\') || (\'multi_branch_expense_report\', \'can_view\') || (\'multi_branch_user_log_report\', \'can_view\') || (\'multi_branch_setting\', \'can_view\')', 1, '2023-01-10 12:49:51', '2025-05-29 12:19:13'),
(34, 'sscbse', 900, 'fa fa-file-text-o', 'CBSE Examination', 'cbse_exam', 'cbse_exam', 69, 13, 1, '(\'subject_marks_report\', \'can_view\') || (\'template_marks_report\', \'can_view\') || (\'cbse_exam\', \'can_view\') ||  (\'cbse_exam_print_marksheet\', \'can_view\') ||  (\'cbse_exam_grade\', \'can_view\') ||  (\'cbse_exam_assign_observation\', \'can_view\') || (\'cbse_exam_observation\', \'can_view\') || (\'cbse_exam_observation_parameter\', \'can_view\') || (\'cbse_exam_assessment\', \'can_view\') || (\'cbse_exam_term\', \'can_view\') || (\'cbse_exam_template\', \'can_view\') || (\'cbse_exam_schedule\', \'can_view\') ', 1, '2023-07-04 13:03:29', '2025-05-29 12:19:13'),
(35, 'ssqra', 1200, 'fa fa-qrcode', 'QR Code Attendance', 'qr_code_attendance', 'qr_code_attendance', 230, 16, 1, '(\'qr_code_attendance\', \'can_view\') || (\'qr_code_setting\', \'can_view\')\n\n', 1, '2023-12-11 12:28:29', '2025-05-29 12:19:13'),
(36, '', 30, 'fa fa-calendar', 'Annual Calendar', 'holiday', 'annual_calendar', 240, 20, 1, '(\'annual_calendar\', \'can_view\')\r\n', 1, '2025-01-18 09:15:03', '2025-05-29 12:19:13'),
(37, '', 31, 'fa fa-ioxhost ftlayer', 'Student CV', 'student_cv', 'student_cv', 1, 27, 1, '(\'download_cv\', \'can_view\') || (\'build_cv\', \'can_view\') || (\'resume_setting\', \'can_view\') || (\'student_resume_details\', \'can_view\')', 1, '2025-01-18 09:15:07', '2025-05-29 12:19:13'),
(38, 'ssqfc', 1300, 'fa fa-usd', 'Quick Fees Create', 'quick_fees', 'quick_fees', 300, 6, 1, '(\'quick_fees\', \'can_view\')\n', 1, '2025-01-28 16:53:51', '2025-05-29 12:19:13'),
(39, 'sstpa', 1400, 'fa fa-usd', 'Thermal Print', 'thermal_print', 'thermal_print', 300, 1, 0, '(\'thermal_print\', \'can_view\')\r\n', 1, '2025-01-29 06:12:04', '2025-05-22 11:49:10'),
(40, 'Partners', 1401, 'fa fa-handshake-o', 'Partners', 'admin/partners', 'partners', 0, 40, 1, 'partners,can_view', 1, '2025-10-10 11:21:47', '2025-10-10 11:28:25'),
(41, '', NULL, 'fa fa-handshake-o ftlayer', 'Partners', 'user_partners', 'partners', 36, 36, 1, '(\'student\', \'can_view\') || (\'parent\', \'can_view\') || (\'staff\', \'can_view\')', 1, '2025-10-11 18:18:21', '2025-10-11 18:18:21');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar_sub_menus`
--

CREATE TABLE `sidebar_sub_menus` (
  `id` int(11) NOT NULL,
  `sidebar_menu_id` int(11) DEFAULT NULL,
  `menu` varchar(500) DEFAULT NULL,
  `key` varchar(500) DEFAULT NULL,
  `lang_key` varchar(250) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `access_permissions` varchar(500) DEFAULT NULL,
  `permission_group_id` int(11) DEFAULT NULL,
  `activate_controller` varchar(100) DEFAULT NULL COMMENT 'income',
  `activate_methods` varchar(500) DEFAULT NULL COMMENT 'index,edit',
  `addon_permission` varchar(100) DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sidebar_sub_menus`
--

INSERT INTO `sidebar_sub_menus` (`id`, `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'admission_enquiry', NULL, 'admission_enquiry', 'admin/enquiry', 1, '(\'admission_enquiry\', \'can_view\')', NULL, 'enquiry', 'index', NULL, 1, '2022-07-11 12:04:46', '2025-05-10 12:46:10'),
(2, 1, 'visitor_book', NULL, 'visitor_book', 'admin/visitors', 2, '(\'visitor_book\', \'can_view\')', NULL, 'visitors', 'index', NULL, 1, '2022-07-11 12:04:46', '2025-05-10 12:46:10'),
(3, 1, 'phone_call_log', NULL, 'phone_call_log', 'admin/generalcall', 3, '(\'phone_call_log\', \'can_view\')', NULL, 'generalcall', 'index,edit', NULL, 1, '2022-05-10 11:40:07', '2025-05-10 12:46:10'),
(4, 1, 'postal_dispatch', NULL, 'postal_dispatch', 'admin/dispatch', 4, '(\'postal_dispatch\', \'can_view\')', NULL, 'dispatch', 'index,editdispatch', NULL, 1, '2022-05-10 11:40:09', '2025-05-10 12:46:10'),
(5, 1, 'postal_receive', NULL, 'postal_receive', 'admin/receive', 5, '(\'postal_receive\', \'can_view\')', NULL, 'receive', 'index,editreceive', NULL, 1, '2022-05-10 11:40:09', '2025-05-10 12:46:10'),
(6, 1, 'complain', NULL, 'complain', 'admin/complaint', 6, '(\'complaint\', \'can_view\')', NULL, 'complaint', 'index,edit', NULL, 1, '2022-05-10 11:40:00', '2025-05-10 12:46:10'),
(7, 1, 'setup_front_office', NULL, 'setup_front_office', 'admin/visitorspurpose', 7, '(\'setup_font_office\', \'can_view\')', NULL, 'visitorspurpose', 'index,edit', NULL, 1, '2022-04-18 15:43:15', '2025-05-10 12:46:10'),
(9, 2, 'student_admission', NULL, 'student_admission', 'student/create', 2, '(\'student\', \'can_add\')', NULL, 'student', 'create,import', NULL, 1, '2022-08-30 07:51:02', '2025-05-10 12:46:10'),
(10, 2, 'online_admission', NULL, 'online_admission', 'admin/onlinestudent', 3, '(\'online_admission\', \'can_view\')', 27, 'onlinestudent', 'index,edit', NULL, 1, '2022-05-10 11:40:30', '2025-05-10 12:46:10'),
(11, 2, 'disable_student', NULL, 'disabled_students', 'student/disablestudentslist', 4, '(\'disable_student\', \'can_view\')', NULL, 'student', 'disablestudentslist', '', 1, '2022-07-23 06:49:00', '2025-05-10 12:46:10'),
(12, 3, 'collect_fees', NULL, 'collect_fees', 'studentfee', 1, '(\'collect_fees\', \'can_view\')', NULL, 'studentfee', 'index,addfee', NULL, 1, '2022-07-23 06:53:34', '2025-05-10 12:46:10'),
(13, 3, 'search_fees_payment', NULL, 'search_fees_payment', 'studentfee/searchpayment', 3, '(\'search_fees_payment\', \'can_view\')', NULL, 'studentfee', 'searchpayment', NULL, 1, '2022-08-08 06:03:40', '2025-05-10 12:46:10'),
(14, 3, 'search_due_fees', NULL, 'search_due_fees', 'studentfee/feesearch', 4, '(\'search_due_fees\', \'can_view\')', NULL, 'studentfee', 'feesearch', NULL, 1, '2022-08-08 06:03:38', '2025-05-10 12:46:10'),
(15, 3, 'fees_master', NULL, 'fees_master', 'admin/feemaster', 5, '(\'fees_master\', \'can_view\')', NULL, 'feemaster', 'index,assign,edit', NULL, 1, '2022-09-24 07:35:55', '2025-05-10 12:46:10'),
(16, 3, 'fees_group', NULL, 'fees_group', 'admin/feegroup', 6, '(\'fees_group\', \'can_view\')', NULL, 'feegroup', 'index,edit', NULL, 1, '2022-08-08 06:03:32', '2025-05-10 12:46:10'),
(17, 4, 'add_income', NULL, 'add_income', 'admin/income', 1, '(\'income\', \'can_view\')', NULL, 'income', 'index,edit', NULL, 1, '2022-07-23 07:03:40', '2025-05-10 12:46:10'),
(18, 4, 'search_income', NULL, 'search_income', 'admin/income/incomesearch', 2, '(\'search_income\', \'can_view\')', NULL, 'income', 'incomesearch', NULL, 1, '2022-07-23 07:10:13', '2025-05-10 12:46:10'),
(19, 4, 'income_head', NULL, 'income_head', 'admin/incomehead', 3, '(\'income_head\', \'can_view\')', NULL, 'incomehead', 'index,edit', NULL, 1, '2022-07-23 07:11:11', '2025-05-10 12:46:10'),
(20, 2, 'student_details', NULL, 'student_details', 'student/search', 1, '(\'student\', \'can_view\')', NULL, 'student', 'search,view,edit', NULL, 1, '2022-08-30 07:51:05', '2025-05-10 12:46:10'),
(21, 2, 'multi_class_student', NULL, 'multi_class_student', 'student/multiclass', 5, '(\'multi_class_student\', \'can_view\')', 26, 'student', 'multiclass', NULL, 1, '2022-07-23 06:48:37', '2025-05-10 12:46:10'),
(22, 2, 'bulk_delete', NULL, 'bulk_delete', 'student/bulkdelete', 6, '(\'student\', \'can_delete\')', NULL, 'student', 'bulkdelete', NULL, 1, '2022-07-23 06:48:11', '2025-05-10 12:46:10'),
(23, 2, 'student_categories', NULL, 'student_categories', 'category', 7, '(\'student_categories\', \'can_view\')', NULL, 'category', 'index,edit', NULL, 1, '2022-07-23 06:47:24', '2025-05-10 12:46:10'),
(24, 2, 'student_house', NULL, 'student_house', 'admin/schoolhouse', 8, '(\'student_houses\', \'can_view\')', NULL, 'schoolhouse', 'index,edit', NULL, 1, '2022-07-23 06:49:59', '2025-05-10 12:46:10'),
(25, 2, 'disable_reason', NULL, 'disable_reason', 'admin/disable_reason', 9, '(\'disable_reason\', \'can_view\')', NULL, 'disable_reason', 'index,edit', NULL, 1, '2022-07-23 06:50:41', '2025-05-10 12:46:10'),
(29, 7, 'add_expense', NULL, 'add_expense', 'admin/expense', 1, '(\'expense\', \'can_view\')', NULL, 'expense', 'index,edit', '', 1, '2022-07-23 07:12:25', '2025-05-10 12:46:10'),
(32, 3, 'fees_type', NULL, 'fees_type', 'admin/feetype', 7, '(\'fees_type\', \'can_view\')', NULL, 'feetype', 'index,edit', '', 1, '2022-08-08 06:03:29', '2025-05-10 12:46:10'),
(33, 10, 'attendance_by_date', 'attendance_by_date', 'attendance_by_date', 'admin/stuattendence/attendencereport', 3, '(\'attendance_by_date\', \'can_view\')', NULL, 'stuattendence', 'attendencereport', '', 1, '2022-10-20 05:50:25', '2025-05-10 12:46:10'),
(34, 10, 'student_attendance', 'student_attendance', 'student_attendance', 'admin/stuattendence', 1, '(\'student_attendance\', \'can_view\')', NULL, 'stuattendence', 'index', '', 1, '2022-10-20 05:50:25', '2025-05-10 12:46:10'),
(35, 10, 'approve_leave', 'approve_leave', 'approve_leave', 'admin/approve_leave', 2, '(\'approve_leave\', \'can_view\')', NULL, 'approve_leave', 'index', '', 1, '2022-10-14 16:16:44', '2025-05-10 12:46:10'),
(36, 11, 'exam_group', NULL, 'exam_group', 'admin/examgroup', 1, '(\'exam_group\', \'can_view\')', NULL, 'examgroup', 'index,addexam,edit', '', 1, '2022-07-23 07:23:01', '2025-05-10 12:46:10'),
(37, 11, 'exam_schedule', NULL, 'exam_schedule', 'admin/exam_schedule', 2, '(\'student_attendance\', \'can_view\')', NULL, 'exam_schedule', 'index', '', 1, '2022-05-16 07:01:34', '2025-05-10 12:46:10'),
(38, 11, 'exam_result', NULL, 'exam_result', 'admin/examresult', 3, '(\'exam_result\', \'can_view\')', NULL, 'examresult', 'index', '', 1, '2022-05-16 07:01:34', '2025-05-10 12:46:10'),
(39, 11, 'design_admit_card', NULL, 'design_admit_card', 'admin/admitcard', 4, '(\'design_admit_card\', \'can_view\')', NULL, 'admitcard', 'index,edit', '', 1, '2022-07-23 07:28:02', '2025-05-10 12:46:10'),
(40, 11, 'print_admit_card', NULL, 'print_admit_card', 'admin/examresult/admitcard', 5, '(\'print_admit_card\', \'can_view\')', NULL, 'examresult', 'admitcard', '', 1, '2022-05-16 07:01:34', '2025-05-10 12:46:10'),
(41, 11, 'design_marksheet', NULL, 'design_marksheet', 'admin/marksheet', 6, '(\'design_marksheet\', \'can_view\')', NULL, 'marksheet', 'index,edit', '', 1, '2022-07-23 07:35:35', '2025-05-10 12:46:10'),
(42, 11, 'print_marksheet', NULL, 'print_marksheet', 'admin/examresult/marksheet', 7, '(\'print_marksheet\', \'can_view\')', NULL, 'examresult', 'marksheet', '', 1, '2022-05-16 07:01:38', '2025-05-10 12:46:10'),
(43, 11, 'marks_grade', NULL, 'marks_grade', 'admin/grade', 8, '(\'marks_grade\', \'can_view\')', NULL, 'grade', 'index,edit', '', 1, '2022-07-23 07:37:15', '2025-05-10 12:46:10'),
(44, 11, 'marks_division', NULL, 'marks_division', 'admin/marksdivision', 9, '(\'marks_division\', \'can_view\')', NULL, 'marksdivision', 'index,edit', '', 1, '2022-08-25 06:04:26', '2025-05-10 12:46:10'),
(45, 12, 'online_exam', NULL, 'online_exam', 'admin/onlineexam', 1, '(\'online_examination\', \'can_view\')', NULL, 'onlineexam', 'index,evalution,assign', '', 1, '2022-08-30 13:03:45', '2025-05-10 12:46:10'),
(46, 12, 'question_bank', NULL, 'question_bank', 'admin/question', 1, '(\'question_bank\', \'can_view\')', NULL, 'question', 'index,read', '', 1, '2022-08-30 11:03:13', '2025-05-10 12:46:10'),
(47, 13, 'manage_lesson_plan', NULL, 'manage_lesson_plan', 'admin/syllabus', 2, '(\'manage_lesson_plan\', \'can_view\')', NULL, 'syllabus', 'index', '', 1, '2022-09-03 16:59:31', '2025-05-10 12:46:10'),
(48, 13, 'manage_syllabus_status', NULL, 'manage_syllabus_status', 'admin/syllabus/status', 3, '(\'manage_syllabus_status\', \'can_view\')', NULL, 'syllabus', 'status', '', 1, '2022-09-03 16:59:35', '2025-05-10 12:46:10'),
(49, 13, 'lesson', NULL, 'lesson', 'admin/lessonplan/lesson', 4, '(\'lesson\', \'can_view\')', NULL, 'lessonplan', 'lesson,editlesson', '', 1, '2022-09-15 11:30:55', '2025-05-10 12:46:10'),
(50, 13, 'topic', NULL, 'topic', 'admin/lessonplan/topic', 5, '(\'topic\', \'can_view\')', NULL, 'lessonplan', 'topic,edittopic', '', 1, '2022-09-15 11:30:24', '2025-05-10 12:46:10'),
(51, 14, 'class_timetable', NULL, 'class_timetable', 'admin/timetable/classreport', 1, '(\'class_timetable\', \'can_view\')', NULL, 'timetable', 'classreport,create', '', 1, '2022-07-23 09:01:22', '2025-05-10 12:46:10'),
(52, 14, 'teachers_timetable', NULL, 'teachers_timetable', 'admin/timetable/mytimetable', 2, '(\'teachers_time_table\', \'can_view\')', NULL, 'timetable', 'mytimetable', '', 1, '2022-07-20 12:22:59', '2025-05-10 12:46:10'),
(53, 14, 'assign_class_teacher', NULL, 'assign_class_teacher', 'admin/teacher/assign_class_teacher', 3, '(\'assign_class_teacher\', \'can_view\')', NULL, 'teacher', 'assign_class_teacher,update_class_teacher', '', 1, '2022-07-23 09:00:19', '2025-05-10 12:46:10'),
(54, 14, 'promote_students', NULL, 'promote_students', 'admin/stdtransfer', 4, '(\'promote_student\', \'can_view\')', NULL, 'stdtransfer', 'index', '', 1, '2022-07-20 12:22:54', '2025-05-10 12:46:10'),
(55, 14, 'subject_group', NULL, 'subject_group', 'admin/subjectgroup', 5, '(\'subject_group\', \'can_view\')', NULL, 'subjectgroup', 'index,edit', '', 1, '2022-07-23 08:59:42', '2025-05-10 12:46:10'),
(56, 14, 'subjects', NULL, 'subjects', 'admin/subject', 6, '(\'subject\', \'can_view\')', NULL, 'subject', 'index,edit', '', 1, '2022-07-23 08:59:20', '2025-05-10 12:46:10'),
(57, 14, 'class', NULL, 'class', 'classes', 7, '(\'class\', \'can_view\')', NULL, 'classes', 'index,edit', '', 1, '2022-07-23 08:58:49', '2025-05-10 12:46:10'),
(58, 14, 'sections', NULL, 'sections', 'sections', 8, '(\'section\', \'can_view\')', NULL, 'sections', 'index,edit', '', 1, '2022-07-23 08:58:21', '2025-05-10 12:46:10'),
(59, 15, 'staff_directory', NULL, 'staff_directory', 'admin/staff', 1, '(\'staff\', \'can_view\')', NULL, 'staff', 'index,edit,profile,create', '', 1, '2022-10-12 09:13:24', '2025-05-10 12:46:10'),
(60, 15, 'staff_attendance', NULL, 'staff_attendance', 'admin/staffattendance', 1, '(\'staff_attendance\', \'can_view\')', NULL, 'staffattendance', 'index', '', 1, '2022-09-07 12:04:15', '2025-05-10 12:46:10'),
(61, 15, 'payroll', NULL, 'payroll', 'admin/payroll', 1, '(\'staff_payroll\', \'can_view\')', NULL, 'payroll', 'index,edit,create', '', 1, '2022-08-16 11:58:44', '2025-05-10 12:46:10'),
(62, 15, 'approve_leave_request', NULL, 'approve_leave_request', 'admin/leaverequest/leaverequest', 1, '(\'approve_leave_request\', \'can_view\')', NULL, 'leaverequest', 'leaverequest', '', 1, '2022-05-16 09:04:33', '2025-05-10 12:46:10'),
(74, 15, 'apply_leave', NULL, 'apply_leave', 'admin/staff/leaverequest', 1, '(\'apply_leave\', \'can_view\')', NULL, 'staff', 'leaverequest', '', 1, '2022-05-16 09:11:41', '2025-05-10 12:46:10'),
(75, 15, 'leave_type', NULL, 'leave_type', 'admin/leavetypes', 1, '(\'leave_types\', \'can_view\')', NULL, 'leavetypes', 'index,leaveedit,createleavetype', '', 1, '2022-10-18 11:19:22', '2025-05-10 12:46:10'),
(76, 15, 'teachers_rating', NULL, 'teachers_rating', 'admin/staff/rating', 1, '(\'teachers_rating\', \'can_view\')', NULL, 'staff', 'rating', '', 1, '2022-05-16 09:15:31', '2025-05-10 12:46:10'),
(77, 15, 'department', NULL, 'department', 'admin/department/department', 1, '(\'department\', \'can_view\')', NULL, 'department', 'department,departmentedit', '', 1, '2022-07-23 09:14:20', '2025-05-10 12:46:10'),
(78, 15, 'designation', NULL, 'designation', 'admin/designation/designation', 1, '(\'designation\', \'can_view\')', NULL, 'designation', 'designation,designationedit', '', 1, '2022-07-23 09:15:04', '2025-05-10 12:46:10'),
(79, 15, 'disabled_staff', NULL, 'disabled_staff', 'admin/staff/disablestafflist', 1, '(\'disable_staff\', \'can_view\')', NULL, 'staff', 'disablestafflist', '', 1, '2022-09-13 07:46:56', '2025-05-10 12:46:10'),
(80, 16, 'notice_board', NULL, 'notice_board', 'admin/notification', 1, '(\'notice_board\', \'can_view\')', NULL, 'notification', 'index,edit,add', '', 1, '2022-07-23 09:17:24', '2025-05-10 12:46:10'),
(81, 16, 'send_email', NULL, 'send_email', 'admin/mailsms/compose', 2, '(\'email\', \'can_view\')', NULL, 'mailsms', 'compose', '', 1, '2022-09-02 16:52:46', '2025-05-10 12:46:10'),
(82, 16, 'send_sms', NULL, 'send_sms', 'admin/mailsms/compose_sms', 3, '(\'sms\', \'can_view\')', NULL, 'mailsms', 'compose_sms', '', 1, '2022-09-02 16:52:46', '2025-05-10 12:46:10'),
(83, 16, 'email_sms_log', NULL, 'email_sms_log', 'admin/mailsms/index', 4, '(\'email_sms_log\', \'can_view\')', NULL, 'mailsms', 'index', '', 1, '2022-09-02 16:52:50', '2025-05-10 12:46:10'),
(84, 16, 'schedule_email_sms_log', NULL, 'schedule_email_sms_log', 'admin/mailsms/schedule', 5, '(\'schedule_email_sms_log\', \'can_view\')', NULL, 'mailsms', 'schedule,edit_schedule', '', 1, '2022-09-13 07:07:38', '2025-05-10 12:46:10'),
(85, 16, 'login_credentials_send', NULL, 'login_credentials_send', 'student/bulkmail', 6, '(\'login_credentials_send\', \'can_view\')', NULL, 'student', 'bulkmail', '', 1, '2022-09-02 16:52:46', '2025-05-10 12:46:10'),
(86, 16, 'email_template', NULL, 'email_template', 'admin/mailsms/email_template', 7, '(\'email_template\', \'can_view\')', NULL, 'mailsms', 'email_template', '', 1, '2022-09-02 16:52:46', '2025-05-10 12:46:10'),
(87, 16, 'sms_template', NULL, 'sms_template', 'admin/mailsms/sms_template', 8, '(\'sms_template\', \'can_view\')', NULL, 'mailsms', 'sms_template', '', 1, '2022-09-02 16:52:46', '2025-05-10 12:46:10'),
(88, 17, 'content_type', NULL, 'content_type', 'admin/contenttype', 1, '(\'content_type\', \'can_view\')', NULL, 'contenttype', 'index,edit', '', 1, '2022-07-23 09:24:45', '2025-05-10 12:46:10'),
(89, 17, 'content_share_list', NULL, 'content_share_list', 'admin/content/list', 1, '(\'content_share_list\', \'can_view\')', NULL, 'content', 'list', '', 1, '2022-07-22 10:07:17', '2025-05-10 12:46:10'),
(90, 17, 'upload_content', NULL, 'upload_content', 'admin/content/upload', 1, '(\'upload_content\', \'can_view\')', NULL, 'content', 'upload', '', 1, '2022-07-22 10:07:17', '2025-05-10 12:46:10'),
(91, 17, 'video_tutorial', NULL, 'video_tutorial', 'admin/video_tutorial', 1, '(\'video_tutorial\', \'can_view\')', NULL, 'video_tutorial', 'index', '', 1, '2022-07-22 10:07:17', '2025-05-10 12:46:10'),
(92, 18, 'add_homework', NULL, 'add_homework', 'homework', 1, '(\'homework\', \'can_view\')', NULL, 'homework', 'index', '', 1, '2022-06-25 09:50:01', '2025-05-10 12:46:10'),
(93, 18, 'daily_assignment', NULL, 'daily_assignment', 'homework/dailyassignment', 2, '(\'daily_assignment\', \'can_view\')', NULL, 'homework', 'dailyassignment', '', 1, '2022-07-23 09:27:23', '2025-05-10 12:46:10'),
(94, 19, 'book_list', NULL, 'book_list', 'admin/book/getall', 1, '(\'books\', \'can_view\')', NULL, 'book', 'getall,index,edit,import,issue_returnreport', '', 1, '2022-09-07 11:45:50', '2025-05-10 12:46:10'),
(95, 19, 'issue_return', NULL, 'issue_return', 'admin/member', 1, '(\'issue_return\', \'can_view\')', NULL, 'member', 'index,issue', '', 1, '2022-07-23 09:32:48', '2025-05-10 12:46:10'),
(96, 19, 'add_student', NULL, 'add_student', 'admin/member/student', 1, '(\'add_student\', \'can_view\')', NULL, 'member', 'student', '', 1, '2022-05-16 11:22:54', '2025-05-10 12:46:10'),
(97, 19, 'add_staff_member', NULL, 'add_staff_member', 'admin/member/teacher', 1, '(\'add_staff_member\', \'can_view\')', NULL, 'member', 'teacher', '', 1, '2022-05-16 11:31:43', '2025-05-10 12:46:10'),
(98, 7, 'search_expense', NULL, 'search_expense', 'admin/expense/expensesearch', 1, '(\'search_expense\', \'can_view\')', NULL, 'expense', 'expensesearch', '', 1, '2022-05-16 11:36:09', '2025-05-10 12:46:10'),
(99, 7, 'expense_head', NULL, 'expense_head', 'admin/expensehead', 1, '(\'expense_head\', \'can_view\')', NULL, 'expensehead', 'index,edit', '', 1, '2022-07-23 07:16:17', '2025-05-10 12:46:10'),
(100, 20, 'issue_item', NULL, 'issue_item', 'admin/issueitem', 1, '(\'issue_item\', \'can_view\')', NULL, 'issueitem', 'index,create', '', 1, '2022-07-23 09:35:03', '2025-05-10 12:46:10'),
(101, 20, 'add_item_stock', NULL, 'add_item_stock', 'admin/itemstock', 1, '(\'item_stock\', \'can_view\')', NULL, 'itemstock', 'index,edit', '', 1, '2022-07-23 09:36:17', '2025-05-10 12:46:10'),
(102, 20, 'add_item', NULL, 'add_item', 'admin/item', 1, '(\'item\', \'can_view\')', NULL, 'item', 'index,edit', '', 1, '2022-07-23 09:36:56', '2025-05-10 12:46:10'),
(103, 20, 'item_category', NULL, 'item_category', 'admin/itemcategory', 1, '(\'item_category\', \'can_view\')', NULL, 'itemcategory', 'index,edit', '', 1, '2022-07-23 09:37:12', '2025-05-10 12:46:10'),
(104, 20, 'item_store', NULL, 'item_store', 'admin/itemstore', 1, '(\'store\', \'can_view\')', NULL, 'itemstore', 'index,edit,create', '', 1, '2022-09-16 11:49:03', '2025-05-10 12:46:10'),
(105, 20, 'item_supplier', NULL, 'item_supplier', 'admin/itemsupplier', 1, '(\'supplier\', \'can_view\')', NULL, 'itemsupplier', 'index,edit,create', '', 1, '2022-07-23 09:38:22', '2025-05-10 12:46:10'),
(106, 21, 'fees_master', NULL, 'fees_master', 'admin/transport/feemaster', 1, '(\'transport_fees_master\', \'can_view\')', NULL, 'transport', 'feemaster', '', 1, '2023-03-31 05:33:14', '2025-05-10 12:46:10'),
(107, 21, 'pickup_point', NULL, 'pickup_point', 'admin/pickuppoint', 1, '(\'pickup_point\', \'can_view\')', NULL, 'pickuppoint', 'index', '', 1, '2023-03-31 05:24:24', '2025-05-10 12:46:10'),
(108, 21, 'routes', NULL, 'routes', 'admin/route', 1, '(\'routes\', \'can_view\')', NULL, 'route', 'index,edit', '', 1, '2022-09-17 06:21:23', '2025-05-10 12:46:10'),
(109, 21, 'vehicles', NULL, 'vehicles', 'admin/vehicle', 1, '(\'vehicle\', \'can_view\')', NULL, 'vehicle', 'index', '', 1, '2022-05-16 12:29:35', '2025-05-10 12:46:10'),
(110, 21, 'assign_vehicle', NULL, 'assign_vehicle', 'admin/vehroute', 1, '(\'assign_vehicle\',\'can_view\')', NULL, 'vehroute', 'index,edit', '', 1, '2022-10-19 07:06:08', '2025-05-10 12:46:10'),
(111, 21, 'route_pickup_point', NULL, 'route_pickup_point', 'admin/pickuppoint/assign', 1, '(\'route_pickup_point\', \'can_view\')', NULL, 'pickuppoint', 'assign', '', 1, '2023-03-31 05:25:08', '2025-05-10 12:46:10'),
(112, 21, 'student_transport_fees', NULL, 'student_transport_fees', 'admin/pickuppoint/student_fees', 1, '(\'student_transport_fees\', \'can_view\')', NULL, 'pickuppoint', 'student_fees', '', 1, '2023-03-31 05:25:43', '2025-05-10 12:46:10'),
(113, 22, 'hostel_rooms', NULL, 'hostel_rooms', 'admin/hostelroom', 1, '(\'hostel_rooms\', \'can_view\')', NULL, 'hostelroom', 'index,edit', '', 1, '2022-07-23 10:27:48', '2025-05-10 12:46:10'),
(114, 22, 'room_type', NULL, 'room_type', 'admin/roomtype', 2, '(\'room_type\', \'can_view\')', NULL, 'roomtype', 'index,edit', '', 1, '2022-07-23 10:32:14', '2025-05-10 12:46:10'),
(115, 22, 'hostel', NULL, 'hostel', 'admin/hostel', 3, '(\'hostel\', \'can_view\')', NULL, 'hostel', 'index,edit', '', 1, '2022-07-23 10:32:39', '2025-05-10 12:46:10'),
(116, 23, 'student_certificate', NULL, 'student_certificate', 'admin/certificate', 1, '(\'student_certificate\', \'can_view\')', NULL, 'certificate', 'index,edit', '', 1, '2022-07-23 10:44:30', '2025-05-10 12:46:10'),
(117, 23, 'generate_certificate', NULL, 'generate_certificate', 'admin/generatecertificate', 1, '(\'generate_certificate\', \'can_view\')', NULL, 'generatecertificate', 'index,search', '', 1, '2022-07-23 10:46:16', '2025-05-10 12:46:10'),
(118, 23, 'student_id_card', NULL, 'student_id_card', 'admin/studentidcard', 1, '(\'student_id_card\', \'can_view\')', NULL, 'studentidcard', 'index,edit', '', 1, '2022-07-23 10:47:01', '2025-05-10 12:46:10'),
(119, 23, 'generate_id_card', NULL, 'generate_id_card', 'admin/generateidcard/search', 1, '(\'generate_id_card\', \'can_view\')', NULL, 'generateidcard', 'search', '', 1, '2022-05-18 05:35:13', '2025-05-10 12:46:10'),
(120, 23, 'staff_id_card', NULL, 'staff_id_card', 'admin/staffidcard', 1, '(\'staff_id_card\', \'can_view\')', NULL, 'staffidcard', 'index,edit', '', 1, '2022-07-23 10:48:13', '2025-05-10 12:46:10'),
(121, 23, 'generate_staff_id_card', NULL, 'generate_staff_id_card', 'admin/generatestaffidcard', 1, '(\'generate_staff_id_card\', \'can_view\')', NULL, 'generatestaffidcard', 'index,search', '', 1, '2022-07-23 10:49:06', '2025-05-10 12:46:10'),
(122, 24, 'event', NULL, 'event', 'admin/front/events', 1, '(\'event\', \'can_view\')', NULL, 'events', 'index,edit,create', '', 1, '2022-07-23 10:51:51', '2025-05-10 12:46:10'),
(123, 24, 'gallery', NULL, 'gallery', 'admin/front/gallery', 1, '(\'gallery\', \'can_view\')', NULL, 'gallery', 'index,edit,create', '', 1, '2022-07-23 10:52:22', '2025-05-10 12:46:10'),
(124, 24, 'news', NULL, 'news', 'admin/front/notice', 1, '(\'notice\', \'can_view\')', NULL, 'notice', 'index,edit,create', '', 1, '2022-07-23 10:54:23', '2025-05-10 12:46:10'),
(125, 24, 'media_manager', NULL, 'media_manager', 'admin/front/media', 1, '(\'media_manager\', \'can_view\')', NULL, 'media', 'index', '', 1, '2022-05-18 06:03:32', '2025-05-10 12:46:10'),
(126, 24, 'pages', NULL, 'pages', 'admin/front/page', 1, '(\'pages\', \'can_view\')', NULL, 'page', 'index,edit,create', '', 1, '2022-07-23 10:55:28', '2025-05-10 12:46:10'),
(127, 24, 'menus', NULL, 'menus', 'admin/front/menus', 1, '(\'menus\', \'can_view\')', NULL, 'menus', 'index,additem', '', 1, '2022-07-23 10:56:31', '2025-05-10 12:46:10'),
(128, 24, 'banner_images', NULL, 'banner_images', 'admin/front/banner', 1, '(\'banner_images\', \'can_view\')', NULL, 'banner', 'index', '', 1, '2022-05-18 06:10:53', '2025-05-10 12:46:10'),
(129, 25, 'manage_alumini', NULL, 'manage_alumini', 'admin/alumni/alumnilist', 1, '(\'manage_alumni\', \'can_view\')', NULL, 'alumni', 'alumnilist', '', 1, '2022-07-23 10:58:36', '2025-05-10 12:46:10'),
(130, 25, 'events', NULL, 'events', 'admin/alumni/events', 1, '(\'events\', \'can_view\')', NULL, 'alumni', 'events', '', 1, '2022-07-23 10:59:09', '2025-05-10 12:46:10'),
(131, 26, 'student_information', NULL, 'student_information', 'report/studentinformation', 1, '(\'student_report\', \'can_view\') || (\'guardian_report\', \'can_view\') || (\'student_history\', \'can_view\') || (\'student_login_credential_report\', \'can_view\') || (\'class_subject_report\', \'can_view\') || (\'admission_report\', \'can_view\') || (\'sibling_report\', \'can_view\') || (\'homehork_evaluation_report\', \'can_view\') || (\'student_profile\', \'can_view\') || (\'student_gender_ratio_report\', \'can_view\') || (\'student_teacher_ratio_report\', \'can_view\')', NULL, 'report', 'studentinformation,studentreport,online_admission_report,student_teacher_ratio,boys_girls_ratio,student_profile,sibling_report,admission_report,class_subject,classsectionreport,guardianreport,admissionreport,logindetailreport,parentlogindetailreport', '', 1, '2022-09-26 05:26:53', '2025-05-10 12:46:10'),
(132, 26, 'finance', NULL, 'finance', 'financereports/finance', 2, '(\'fees_statement\', \'can_view\') || (\'balance_fees_report\', \'can_view\') || (\'fees_collection_report\', \'can_view\') || (\'online_fees_collection_report\', \'can_view\') || (\'income_report\', \'can_view\') || (\'expense_report\', \'can_view\') || (\'payroll_report\', \'can_view\') || (\'income_group_report\', \'can_view\') || (\'expense_group_report\', \'can_view\') || (\'online_admission\', \'can_view\')', NULL, 'financereports', 'finance,reportduefees,reportdailycollection,reportbyname,studentacademicreport,collection_report,onlinefees_report,duefeesremark,income,expense,payroll,incomegroup,expensegroup,onlineadmission', '', 1, '2022-09-24 12:20:32', '2025-05-10 12:46:10'),
(133, 26, 'attendance', NULL, 'attendance', 'attendencereports/attendance', 3, '(\'attendance_report\', \'can_view\') || (\'student_attendance_type_report\', \'can_view\') || (\'daily_attendance_report\', \'can_view\') || (\'staff_attendance_report\', \'can_view\')', NULL, 'attendencereports', 'attendance,classattendencereport,attendancereport,daily_attendance_report,staffattendancereport,biometric_attlog,reportbymonthstudent,reportbymonth', '', 1, '2022-09-26 11:36:08', '2025-05-10 12:46:10'),
(134, 26, 'examinations', NULL, 'examinations', 'admin/examresult/examinations', 4, '(\'rank_report\', \'can_view\')', NULL, 'examresult', 'rankreport,examinations', '', 1, '2022-09-20 08:34:13', '2025-05-10 12:46:10'),
(135, 26, 'lesson_plan', NULL, 'lesson_plan', 'report/lesson_plan', 6, '(\'syllabus_status_report\', \'can_view\') || (\'teacher_syllabus_status_report\', \'can_view\')', NULL, 'report', 'lesson_plan,teachersyllabusstatus', '', 1, '2022-07-25 11:39:17', '2025-05-10 12:46:10'),
(136, 26, 'human_resource', NULL, 'human_resource', 'report/human_resource', 7, '(\'staff_report\', \'can_view\') || (\'payroll_report\', \'can_view\')', NULL, 'report', 'human_resource,staff_report,payrollreport', '', 1, '2022-07-25 11:38:20', '2025-05-10 12:46:10'),
(137, 26, 'library', NULL, 'library', 'report/library', 9, '(\'book_issue_report\', \'can_view\') || (\'book_due_report\', \'can_view\') || (\'book_issue_return_report\', \'can_view\') || (\'book_inventory_report\', \'can_view\')', NULL, 'report', 'library,studentbookissuereport,bookduereport,bookinventory', '', 1, '2022-09-07 11:53:15', '2025-05-10 12:46:10'),
(138, 26, 'inventory', NULL, 'inventory', 'report/inventory', 10, '(\'stock_report\', \'can_view\') || (\'add_item_report\', \'can_view\') || (\'issue_item_report\', \'can_view\')', NULL, 'report', 'inventory,inventorystock,additem,issueinventory', '', 1, '2022-07-25 11:30:57', '2025-05-10 12:46:10'),
(139, 26, 'hostel', NULL, 'hostel', 'admin/hostelroom/studenthosteldetails', 12, '(\'hostel_report\', \'can_view\')', NULL, 'hostelroom', 'studenthosteldetails', '', 1, '2022-07-20 12:30:07', '2025-05-10 12:46:10'),
(140, 26, 'alumni', NULL, 'alumni', 'report/alumnireport', 13, '(\'alumni_report\', \'can_view\')', NULL, 'report', 'alumnireport', '', 1, '2022-07-20 12:30:07', '2025-05-10 12:46:10'),
(141, 26, 'user_log', NULL, 'user_log', 'admin/userlog', 14, '(\'user_log\', \'can_view\')', NULL, 'userlog', 'index', '', 1, '2022-07-20 12:30:07', '2025-05-10 12:46:10'),
(142, 26, 'audit_trail_report', NULL, 'audit_trail_report', 'admin/audit', 15, '(\'audit_trail_report\', \'can_view\')', NULL, 'audit', 'index', '', 1, '2022-07-20 12:30:07', '2025-05-10 12:46:10'),
(143, 26, 'online_examinations', NULL, 'online_examinations', 'admin/onlineexam/report', 5, '(\'online_exam_wise_report\', \'can_view\') || (\'online_exams_report\', \'can_view\') || (\'online_exams_attempt_report\', \'can_view\') || (\'online_exams_rank_report\', \'can_view\')', NULL, 'onlineexam', 'report,onlineexams', '', 1, '2022-07-25 11:48:23', '2025-05-10 12:46:10'),
(144, 26, 'homework', NULL, 'homework', 'homework/homeworkordailyassignmentreport', 8, '(\'homework\', \'can_view\') || (\'daily_assignment\', \'can_view\')', NULL, 'homework', 'homeworkordailyassignmentreport,homeworkreport,evaluation_report,dailyassignmentreport', '', 1, '2022-09-21 09:28:47', '2025-05-10 12:46:10'),
(145, 26, 'transport', NULL, 'transport', 'admin/route/studenttransportdetails', 11, '(\'transport_report\', \'can_view\')', NULL, 'route', 'studenttransportdetails', '', 1, '2022-07-20 12:30:07', '2025-05-10 12:46:10'),
(146, 27, 'general_setting', NULL, 'general_setting', 'schsettings', 1, '(\'general_setting\', \'can_view\')', NULL, 'schsettings', 'index,logo,miscellaneous,backendtheme,mobileapp,studentguardianpanel,fees,idautogeneration,attendancetype,maintenance,whatsappsettings', '', 1, '2025-05-10 12:46:10', '2025-05-10 12:46:10'),
(147, 27, 'session_setting', NULL, 'session_setting', 'sessions', 2, '(\'session_setting\', \'can_view\')', NULL, 'sessions', 'index,edit', '', 1, '2022-07-23 11:57:16', '2025-05-10 12:46:10'),
(148, 27, 'notification_setting', NULL, 'notification_setting', 'admin/notification/setting', 3, '(\'notification_setting\', \'can_view\')', NULL, 'notification', 'setting', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(149, 27, 'sms_setting', NULL, 'sms_setting', 'smsconfig', 4, '(\'sms_setting\', \'can_view\')', NULL, 'smsconfig', 'index', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(150, 27, 'email_setting', NULL, 'email_setting', 'emailconfig', 5, '(\'email_setting\', \'can_view\')', NULL, 'emailconfig', 'index', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(151, 27, 'payment_methods', NULL, 'payment_methods', 'admin/paymentsettings', 6, '(\'payment_methods\', \'can_view\')', NULL, 'paymentsettings', 'index', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(152, 27, 'print_headerfooter', NULL, 'print_headerfooter', 'admin/print_headerfooter', 7, '(\'print_header_footer\', \'can_view\')', NULL, 'print_headerfooter', 'index', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(153, 27, 'front_cms_setting', NULL, 'front_cms_setting', 'admin/frontcms', 8, '(\'front_cms_setting\', \'can_view\')', NULL, 'frontcms', 'index', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(154, 27, 'roles_permissions', NULL, 'roles_permissions', 'admin/roles', 9, '(\'superadmin\', \'can_view\')', NULL, 'roles', 'index,permission', '', 1, '2022-09-09 11:03:34', '2025-05-10 12:46:10'),
(155, 27, 'backup_restore', NULL, 'backup_restore', 'admin/admin/backup', 10, '(\'backup\', \'can_view\')', NULL, 'admin', 'backup', '', 1, '2022-07-08 08:12:28', '2025-05-10 12:46:10'),
(156, 27, 'users', NULL, 'users', 'admin/users', 13, '(\'user_status\', \'can_view\')', NULL, 'users', 'index', '', 1, '2022-07-20 12:34:09', '2025-05-10 12:46:10'),
(157, 27, 'languages', NULL, 'languages', 'admin/language', 11, '(\'languages\', \'can_view\')', NULL, 'language', 'index,create', '', 1, '2022-09-10 09:14:52', '2025-05-10 12:46:10'),
(158, 27, 'modules', NULL, 'modules', 'admin/module', 14, '(\'superadmin\', \'can_view\')', NULL, 'module', 'index', '', 1, '2022-07-20 12:34:06', '2025-05-10 12:46:10'),
(159, 27, 'custom_fields', NULL, 'custom_fields', 'admin/customfield', 15, '(\'custom_fields\', \'can_view\')', NULL, 'customfield', 'index,edit', '', 1, '2022-07-23 12:02:14', '2025-05-10 12:46:10'),
(160, 27, 'captcha_setting', NULL, 'captcha_setting', 'admin/captcha', 16, '(\'superadmin\', \'can_view\')', NULL, 'captcha', 'index', '', 1, '2022-07-20 12:34:06', '2025-05-10 12:46:10'),
(161, 27, 'system_fields', NULL, 'system_fields', 'admin/systemfield', 17, '(\'system_fields\', \'can_view\')', NULL, 'systemfield', 'index', '', 1, '2022-07-22 06:07:38', '2025-05-10 12:46:10'),
(162, 27, 'student_profile_update', NULL, 'student_profile_update', 'student/profilesetting', 18, '(\'student_profile_update\', \'can_view\')', NULL, 'student', 'profilesetting', '', 1, '2022-07-20 12:34:06', '2025-05-10 12:46:10'),
(163, 27, 'online_admission', NULL, 'online_admission', 'admin/onlineadmission/admissionsetting', 19, '(\'online_admission\', \'can_view\')', NULL, 'onlineadmission', 'admissionsetting', '', 1, '2022-07-20 12:34:06', '2025-05-10 12:46:10'),
(164, 27, 'file_types', NULL, 'file_types', 'admin/admin/filetype', 20, '(\'superadmin\', \'can_view\')', NULL, 'admin', 'filetype', '', 1, '2022-07-20 12:34:30', '2025-05-10 12:46:10'),
(165, 27, 'system_update', NULL, 'system_update', 'admin/updater', 22, '(\'superadmin\', \'can_view\')', NULL, 'updater', 'index', '', 1, '2022-10-13 11:49:51', '2025-05-10 12:46:10'),
(166, 27, 'sidebar_menu', NULL, 'sidebar_menu', 'admin/sidemenu', 21, '(\'sidebar_menu\', \'can_view\')', NULL, 'sidemenu', 'index', '', 1, '2022-10-13 11:49:51', '2025-05-10 12:46:10'),
(167, 28, 'online_course', NULL, 'online_course', 'onlinecourse/course/index', 1, '(\'online_course\', \'can_view\')', NULL, 'course', 'index', 'ssoclc', 1, '2022-05-18 07:50:11', '2025-05-22 11:46:41'),
(168, 28, 'offline_payment', NULL, 'offline_payment', 'onlinecourse/offlinepayment/payment', 2, '(\'online_course_offline_payment\', \'can_view\')', NULL, 'offlinepayment', 'payment', 'ssoclc', 1, '2022-12-04 10:26:40', '2025-05-22 11:46:41'),
(169, 28, 'course_category', NULL, 'course_category', 'onlinecourse/coursecategory/categoryadd', 3, '(\'course_category\', \'can_view\') || (\'course_category\', \'can_add\') || (\'course_category\', \'can_edit\') || (\'course_category\', \'can_delete\')', NULL, 'coursecategory', 'categoryadd,categoryedit', 'ssoclc', 1, '2023-01-02 09:23:54', '2025-05-22 11:46:41'),
(170, 29, 'live_class', NULL, 'live_class', 'admin/gmeet/timetable', 1, '(\'gmeet_live_classes\', \'can_view\')', NULL, 'gmeet', 'timetable', 'ssglc', 1, '2022-07-22 04:11:10', '2025-05-22 11:46:36'),
(171, 29, 'live_meeting', NULL, 'live_meeting', 'admin/gmeet/meeting', 2, '(\'gmeet_live_meeting\', \'can_view\')', NULL, 'gmeet', 'meeting', 'ssglc', 1, '2022-07-22 04:11:10', '2025-05-22 11:46:36'),
(172, 29, 'live_class_report', NULL, 'live_class_report', 'admin/gmeet/class_report', 3, '(\'gmeet_live_classes_report\', \'can_view\')', NULL, 'gmeet', 'class_report', 'ssglc', 1, '2022-07-21 11:28:45', '2025-05-22 11:46:36'),
(173, 29, 'live_meeting_report', NULL, 'live_meeting_report', 'admin/gmeet/meeting_report', 4, '(\'gmeet_live_meeting_report\', \'can_view\')', NULL, 'gmeet', 'meeting_report', 'ssglc', 1, '2022-07-21 11:28:45', '2025-05-22 11:46:36'),
(174, 29, 'setting', NULL, 'setting', 'admin/gmeet/index', 5, '(\'gmeet_setting\', \'can_view\')', NULL, 'gmeet', 'index', '', 1, '2022-07-21 11:28:45', '2025-05-22 11:46:36'),
(175, 30, 'live_class', NULL, 'live_class', 'admin/conference/timetable', 2, '(\'live_classes\', \'can_view\')', NULL, 'conference', 'timetable', 'sszlc', 1, '2022-07-22 05:55:48', '2025-05-22 11:46:33'),
(176, 30, 'live_meeting', NULL, 'live_meeting', 'admin/conference/meeting', 1, '(\'live_meeting\', \'can_view\')', NULL, 'conference', 'meeting', 'sszlc', 1, '2022-07-22 05:55:48', '2025-05-22 11:46:33'),
(177, 30, 'live_class_report', NULL, 'live_class_report', 'admin/conference/class_report', 3, '(\'live_classes_report\', \'can_view\')', NULL, 'conference', 'class_report', 'sszlc', 1, '2022-07-11 11:38:41', '2025-05-22 11:46:33'),
(178, 30, 'live_meeting_report', NULL, 'live_meeting_report', 'admin/conference/meeting_report', 4, '(\'live_meeting_report\', \'can_view\')', NULL, 'conference', 'meeting_report', 'sszlc', 1, '2022-07-11 11:38:41', '2025-05-22 11:46:33'),
(179, 30, 'setting', NULL, 'setting', 'admin/conference', 5, '(\'setting\', \'can_view\')', NULL, 'conference', 'index', '', 1, '2022-07-11 11:38:41', '2025-05-22 11:46:33'),
(180, 28, 'online_course_report', NULL, 'online_course_report', 'onlinecourse/coursereport/report', 4, '(\'student_course_purchase_report\', \'can_view\') || (\'course_sell_count_report\', \'can_view\') || (\'course_trending_report\', \'can_view\') || (\'course_complete_report\', \'can_view\')  || (\'course_rating_report\', \'can_view\')  || (\'guest_report\', \'can_view\')', NULL, 'coursereport', 'report,coursepurchase,coursesellreport,trendingreport,completereport,courseratingreport,guestlist,quizperformance', 'ssoclc', 1, '2022-12-09 05:00:31', '2025-05-22 11:46:41'),
(181, 3, 'fees_discount', NULL, 'fees_discount', 'admin/feediscount', 8, '(\'fees_discount\', \'can_view\')', NULL, 'feediscount', 'index,edit,assign', '', 1, '2022-08-08 06:03:27', '2025-05-10 12:46:10'),
(182, 3, 'fees_carry_forward', NULL, 'fees_carry_forward', 'admin/feesforward', 9, '(\'fees_carry_forward\', \'can_view\')', NULL, 'feesforward', 'index', '', 1, '2022-08-08 06:03:24', '2025-05-10 12:46:10'),
(183, 3, 'fees_reminder', NULL, 'fees_reminder', 'admin/feereminder/setting', 10, '(\'fees_reminder\', \'can_view\')', NULL, 'feereminder', 'setting', '', 1, '2022-08-08 06:03:21', '2025-05-10 12:46:10'),
(184, 27, 'currency', NULL, 'currency', 'admin/currency', 12, '(\'currency\', \'can_view\')', NULL, 'currency', 'index', '', 1, '2022-07-20 12:34:09', '2025-05-10 12:46:10'),
(186, 31, 'assign_incident', NULL, 'assign_incident', 'behaviour/studentincidents', 1, '(\'behaviour_records_assign_incident\', \'can_view\')', NULL, 'studentincidents', 'index', 'ssbr', 1, '2022-07-27 04:55:37', '2025-05-22 11:46:54'),
(187, 31, 'incidents', NULL, 'incidents', 'behaviour/incidents', 1, '(\'behaviour_records_incident\', \'can_view\')', NULL, 'incidents', 'index', 'ssbr', 1, '2022-09-06 13:02:04', '2025-05-22 11:46:54'),
(188, 31, 'reports', NULL, 'reports', 'behaviour/report', 1, '(\'student_incident_report\', \'can_view\') || (\'student_behaviour_rank_report\', \'can_view\') || (\'class_wise_rank_report\', \'can_view\') || (\'class_section_wise_rank_report\', \'can_view\') || (\'house_wise_rank_report\', \'can_view\') || (\'incident_wise_report\', \'can_view\')', NULL, 'report', 'index,studentincidentreport,studentbehaviorsrankreport,classwiserankreport,classsectionwiserank,housewiserank,incidentwisereport', 'ssbr', 1, '2022-09-06 13:25:28', '2025-05-22 11:46:54'),
(189, 31, 'setting', NULL, 'setting', 'behaviour/setting', 1, '(\'behaviour_records_setting\', \'can_view\')', NULL, 'setting', 'index', '', 1, '2022-09-06 13:02:20', '2025-05-22 11:46:54'),
(190, 3, 'offline_bank_payments', NULL, 'offline_bank_payments', 'admin/offlinepayment', 2, '(\'offline_bank_payments\', \'can_view\')', NULL, 'offlinepayment', 'index', '', 1, '2022-08-08 06:05:29', '2025-05-10 12:46:10'),
(191, 13, 'Copy Old Lessons', NULL, 'copy_old_lesson', 'admin/lessonplan/copylesson', 1, '(\'copy_old_lesson\', \'can_view\')', NULL, 'lessonplan', 'copylesson', NULL, 1, '2022-09-09 10:20:37', '2025-05-10 12:46:10'),
(192, 10, 'Period Attendance', 'period_attendance', 'period_attendance', 'admin/subjectattendence/index', 4, '(\'student_attendance\',\'can_view\')', NULL, 'subjectattendence', 'index', NULL, 0, '2022-10-20 05:50:25', '2025-05-10 12:46:10'),
(193, 10, 'Period Attendance By Date', 'period_attendance_by_date', 'period_attendance_by_date', 'admin/subjectattendence/reportbydate', 5, '(\'attendance_by_date\', \'can_view\')', NULL, 'subjectattendence', 'reportbydate', NULL, 0, '2022-10-20 05:50:25', '2025-05-10 12:46:10'),
(194, 32, 'setup_two_fa', NULL, 'setup_two_fa', 'admin/gauthenticate/admin/setup', 1, '(\'google_authenticate_setup_two_fa\', \'can_view\')', NULL, 'admin', 'setup', 'sstfa', 1, '2022-11-21 09:00:15', '2025-05-22 11:47:02'),
(195, 32, 'setting', NULL, 'setting', 'admin/gauthenticate/admin', 1, '(\'google_authenticate_setting\', \'can_view\')', NULL, 'admin', 'index', '', 1, '2022-11-21 09:01:34', '2025-05-22 11:47:02'),
(197, 28, 'setting', NULL, 'setting', 'onlinecourse/course/setting', 5, '(\'online_course_setting\', \'can_view\')', NULL, 'course', 'setting', '', 1, '2022-07-22 01:13:30', '2025-05-22 11:46:41'),
(198, 33, 'overview', NULL, 'overview', 'admin/multibranch/branch/overview', 1, '(\'multi_branch_overview\', \'can_view\')', NULL, 'branch', 'overview', 'ssmb', 1, '2022-11-15 06:05:27', '2025-05-22 11:46:56'),
(199, 33, 'report', NULL, 'report', 'admin/multibranch/finance/index', 1, '(\'multi_branch_daily_collection_report\', \'can_view\') || (\'multi_branch_payroll\', \'can_view\') || (\'multi_branch_income_report\', \'can_view\') || (\'multi_branch_expense_report\', \'can_view\') || (\'multi_branch_user_log_report\', \'can_view\')', NULL, 'finance', 'dailycollectionreport,payroll,incomelist,expenselist,incomereport,expensereport,userlogreport,index', 'ssmb', 1, '2022-12-22 05:59:38', '2025-05-22 11:46:56'),
(200, 33, 'setting', NULL, 'setting', 'admin/multibranch/branch', 1, '(\'multi_branch_setting\', \'can_view\')', NULL, 'branch', 'index', '', 1, '2022-11-15 05:45:32', '2025-05-22 11:46:56'),
(201, 34, 'exam', NULL, 'exam', 'cbseexam/exam', 1, '(\'cbse_exam\', \'can_view\')', NULL, 'exam', 'index,examwiserank', 'sscbse', 1, '2023-07-04 09:57:01', '2025-05-22 11:47:06'),
(202, 34, 'exam_schedule', NULL, 'exam_schedule', 'cbseexam/exam/examtimetable', 1, '(\'cbse_exam_schedule\', \'can_view\')', NULL, 'exam', 'examtimetable', 'sscbse', 1, '2023-07-04 13:01:15', '2025-05-22 11:47:06'),
(203, 34, 'print_marksheet', NULL, 'print_marksheet', 'cbseexam/result/marksheet', 1, '(\'cbse_exam_print_marksheet\', \'can_view\')', NULL, 'result', 'marksheet', 'sscbse', 1, '2023-05-25 05:23:50', '2025-05-22 11:47:06'),
(204, 34, 'exam_grade', NULL, 'exam_grade', 'cbseexam/grade/gradelist', 1, '(\'cbse_exam_grade\', \'can_view\')', NULL, 'grade', 'gradelist', 'sscbse', 1, '2023-07-05 07:17:24', '2025-05-22 11:47:06'),
(205, 34, 'assign_observation', NULL, 'assign_observation', 'cbseexam/observation/assign', 1, '(\'cbse_exam_assign_observation\', \'can_view\')', NULL, 'observation', 'assign', 'sscbse', 1, '2023-05-25 05:31:49', '2025-05-22 11:47:06'),
(206, 34, 'observation', NULL, 'observation', 'cbseexam/observation', 1, '(\'cbse_exam_observation\', \'can_view\')', NULL, 'observation', 'index', 'sscbse', 1, '2023-07-05 07:17:37', '2025-05-22 11:47:06'),
(207, 34, 'observation_parameter', NULL, 'observation_parameter', 'cbseexam/observationparameter', 1, '(\'cbse_exam_observation_parameter\', \'can_view\')', NULL, 'observationparameter', 'index,edit', 'sscbse', 1, '2023-06-30 07:39:42', '2025-05-22 11:47:06'),
(208, 34, 'assessment', NULL, 'assessment', 'cbseexam/assessment', 1, '(\'cbse_exam_assessment\', \'can_view\')', NULL, 'assessment', 'index', 'sscbse', 1, '2023-05-25 05:34:19', '2025-05-22 11:47:06'),
(209, 34, 'term', NULL, 'term', 'cbseexam/term', 1, '(\'cbse_exam_term\', \'can_view\')', NULL, 'term', 'index', 'sscbse', 1, '2023-05-25 05:34:53', '2025-05-22 11:47:06'),
(210, 34, 'template', NULL, 'template', 'cbseexam/template', 1, '(\'cbse_exam_template\', \'can_view\')', NULL, 'template', 'index,templatewiserank', 'sscbse', 1, '2023-07-04 09:57:06', '2025-05-22 11:47:06'),
(211, 34, 'reports', NULL, 'reports', 'cbseexam/report/index', 1, '(\'subject_marks_report\', \'can_view\') || (\'template_marks_report\', \'can_view\')', NULL, 'report', 'index,templatewise,examsubject', 'sscbse', 1, '2023-07-01 05:22:34', '2025-05-22 11:47:06'),
(212, 34, 'setting', NULL, 'setting', 'cbseexam/setting/index', 1, '(\'cbse_exam_setting\', \'can_view\')', NULL, 'setting', 'index', '', 1, '2023-07-03 05:26:03', '2025-05-22 11:47:06'),
(213, 35, 'attendance', NULL, 'attendance', 'admin/qrattendance/attendance/index', 1, '(\'qr_code_attendance\', \'can_view\')', NULL, 'attendance', 'index', 'ssqra', 1, '2023-12-11 12:38:24', '2025-05-22 11:47:17'),
(214, 35, 'setting', NULL, 'setting', 'admin/qrattendance/setting/index', 2, '(\'qr_code_setting\', \'can_view\')', NULL, 'setting', 'index', NULL, 1, '2023-12-11 12:38:27', '2025-05-22 11:47:17'),
(215, 36, 'annual_calendar', NULL, 'annual_calendar', 'admin/holiday/index', 1, '(\'annual_calendar\', \'can_view\')', NULL, 'holiday', 'index', '', 1, '2024-10-14 12:07:58', '2025-05-10 12:46:10'),
(216, 36, 'holiday_type', NULL, 'holiday_type', 'admin/holiday/holidaytype', 1, '(\'holiday_type\', \'can_view\')', NULL, 'holiday', 'holidaytype,editholidaytype', '', 1, '2024-10-14 12:06:02', '2025-05-10 12:46:10'),
(217, 37, 'download_cv', NULL, 'download_cv', 'admin/resume/download', 2, '(\'download_cv\', \'can_view\')', NULL, 'resume', 'download', NULL, 1, '2025-01-09 08:05:11', '2025-05-10 12:46:10'),
(218, 37, 'build_cv', NULL, 'build_cv', 'admin/resume/index', 1, '(\'build_cv\', \'can_view\')', NULL, 'resume', 'index,resume_setting,student_resume_details', NULL, 1, '2024-12-06 11:42:02', '2025-05-10 12:46:10'),
(219, 27, 'addons', NULL, 'addons', 'admin/addons', 13, '(\'superadmin\', \'can_view\')', NULL, 'addons', 'index', '', 1, '2024-12-21 11:43:48', '2025-05-10 12:46:10'),
(220, 3, 'quick_fees', NULL, 'quick_fees', 'admin/customfeesmaster/index', 5, '(\'quick_fees\', \'can_view\')', 1300, 'customfeesmaster', 'index', 'ssqfc', 1, '2025-01-29 05:40:13', '2025-05-22 11:47:24'),
(221, 27, 'thermal_print', NULL, 'thermal_print', 'admin/thermalprint/index', 7, '(\'thermal_print\', \'can_view\')', NULL, 'thermalprint', 'index', 'sstpa', 1, '2025-01-10 12:08:46', '2025-05-22 11:47:33'),
(222, 28, 'online_course_question_bank', NULL, 'online_course_question_bank', 'onlinecourse/courseexamquestion/index', 3, '(\'online_course_question_bank\', \'can_view\') || (\'online_course_question_bank\', \'can_add\') || (\'online_course_question_bank\', \'can_edit\') || (\'online_course_question_bank\', \'can_delete\')', NULL, 'courseexamquestion', 'index', NULL, 1, '2024-12-24 07:02:50', '2025-05-22 11:46:49'),
(223, 40, 'Partner List', 'partners_partner_list', 'partner_list', 'admin/partners', 1, 'partners,can_view', 1401, 'partners', 'index,show,add,edit,delete,getlist', NULL, 1, '2025-10-10 11:21:47', '2025-10-10 11:29:07'),
(224, 40, 'Partner Requests', 'partners_requests', 'partner_requests', 'admin/partners/requests', 2, 'partners,can_view', 1401, 'partners', 'requests,approve,reject,getrequestslist', NULL, 1, '2025-10-10 11:21:47', '2025-10-10 11:29:07'),
(225, 40, 'Contributions', 'partners_contributions', 'contributions', 'admin/partnercontributions', 3, 'partners,can_view', 1401, 'partnercontributions', 'index,add,edit,delete', NULL, 1, '2025-10-10 11:21:47', '2025-10-10 11:29:07'),
(226, 40, 'Partner Reports', 'partners_reports', 'partner_reports', 'admin/partnerreports', 4, 'partners,can_view', 1401, 'partnerreports', 'index', NULL, 1, '2025-10-10 11:21:47', '2025-10-10 11:29:07'),
(227, 41, 'My Partners', 'user_partner_list', 'my_partners', 'user/partner_management', 1, '(\'student\', \'can_view\') || (\'parent\', \'can_view\') || (\'staff\', \'can_view\')', NULL, 'partner_management', 'index', '', 1, '2025-10-11 21:18:21', '2025-10-11 21:18:21'),
(228, 41, 'Add Partner', 'user_add_partner', 'add_partner', 'user/partner_management/add', 2, '(\'student\', \'can_view\') || (\'parent\', \'can_view\') || (\'staff\', \'can_view\')', NULL, 'partner_management', 'add', '', 1, '2025-10-11 21:18:21', '2025-10-11 21:18:21'),
(229, 41, 'Register as Partner', 'user_register_partner', 'register_as_partner', 'user/partner/register', 3, '(\'student\', \'can_view\') || (\'parent\', \'can_view\') || (\'staff\', \'can_view\')', NULL, 'partner', 'register', '', 1, '2025-10-11 21:18:21', '2025-10-11 21:18:21'),
(0, 40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 5, '(\'partners\', \'can_view\')', 1401, 'givingtypes', 'index,add,edit,delete,show', NULL, 1, '2025-11-03 15:23:48', '2025-11-03 15:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `sms_config`
--

CREATE TABLE `sms_config` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `api_id` varchar(100) NOT NULL,
  `authkey` varchar(100) NOT NULL,
  `senderid` varchar(100) NOT NULL,
  `contact` text DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'disabled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

CREATE TABLE `sms_template` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
  `id` int(11) NOT NULL,
  `source` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(200) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `currency_id` int(11) DEFAULT 0,
  `department` int(11) DEFAULT NULL,
  `designation` int(11) DEFAULT NULL,
  `qualification` varchar(200) NOT NULL,
  `work_exp` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `father_name` varchar(200) NOT NULL,
  `mother_name` varchar(200) NOT NULL,
  `contact_no` varchar(200) NOT NULL,
  `emergency_contact_no` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `date_of_joining` date DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `local_address` varchar(300) NOT NULL,
  `permanent_address` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `account_title` varchar(200) NOT NULL,
  `bank_account_no` varchar(200) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `ifsc_code` varchar(200) NOT NULL,
  `bank_branch` varchar(100) NOT NULL,
  `payscale` varchar(200) NOT NULL,
  `basic_salary` int(11) DEFAULT NULL,
  `epf_no` varchar(200) NOT NULL,
  `contract_type` varchar(100) NOT NULL,
  `shift` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `resume` varchar(200) NOT NULL,
  `joining_letter` varchar(200) NOT NULL,
  `resignation_letter` varchar(200) NOT NULL,
  `other_document_name` varchar(200) NOT NULL,
  `other_document_file` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `verification_code` varchar(100) NOT NULL,
  `zoom_api_key` varchar(100) DEFAULT NULL,
  `zoom_api_secret` varchar(100) DEFAULT NULL,
  `disable_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `employee_id`, `lang_id`, `currency_id`, `department`, `designation`, `qualification`, `work_exp`, `name`, `surname`, `father_name`, `mother_name`, `contact_no`, `emergency_contact_no`, `email`, `dob`, `marital_status`, `date_of_joining`, `date_of_leaving`, `local_address`, `permanent_address`, `note`, `image`, `password`, `gender`, `account_title`, `bank_account_no`, `bank_name`, `ifsc_code`, `bank_branch`, `payscale`, `basic_salary`, `epf_no`, `contract_type`, `shift`, `location`, `facebook`, `twitter`, `linkedin`, `instagram`, `resume`, `joining_letter`, `resignation_letter`, `other_document_name`, `other_document_file`, `user_id`, `is_active`, `verification_code`, `zoom_api_key`, `zoom_api_secret`, `disable_at`, `created_at`, `updated_at`) VALUES
(1, '9000', 4, 178, NULL, NULL, '', '', 'Super Admin', '', '', '', '', '', 'kuda@tenganimanje.com', '2020-01-01', '', NULL, NULL, '', '', '', '', '$2y$10$Co1wogBO4/Jzy5wzHX1uKuNrm3SjaCKVH9.RRchBaY1DSvvCWvzYS', 'Male', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 1, '', NULL, NULL, NULL, '2025-05-10 12:46:10', '2025-10-11 20:34:07'),
(2, '0000', 0, 0, 1, 1, '', '', 'LecturerA', '', '', '', '', '', 'kkamudyariwa@gmail.com', '1993-05-11', '', NULL, NULL, '', '', '', '', '$2y$10$9q/rUCeb/uHjdR1Qm6p6JOM1Ixn1I2Zkh38XpXHV8gPwpkXTSXp5C', 'Male', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', 'Other Document', '', 0, 1, '', NULL, NULL, NULL, '2025-06-12 09:16:27', '2025-06-12 09:17:39'),
(3, '0001', 0, 0, 1, 1, '', '', 'LecturerB', '', '', '', '', '', 'kuda@virtual.co.zw', '1985-10-31', '', NULL, NULL, '', '', '', '', '$2y$10$CzZ8LryqE4ljhLRXRj91E.cbryqVouPnxKirKSORprJiWzcsf/m8q', 'Male', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 1, '', NULL, NULL, NULL, '2025-06-12 09:26:49', '2025-06-12 09:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance`
--

CREATE TABLE `staff_attendance` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_attendance_type_id` int(11) NOT NULL,
  `biometric_attendence` int(11) DEFAULT 0,
  `qrcode_attendance` int(11) NOT NULL DEFAULT 0,
  `biometric_device_data` text DEFAULT NULL,
  `user_agent` varchar(250) DEFAULT NULL,
  `remark` varchar(200) NOT NULL,
  `is_active` int(11) NOT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance_type`
--

CREATE TABLE `staff_attendance_type` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `key_value` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `for_qr_attendance` int(11) NOT NULL DEFAULT 1,
  `long_lang_name` varchar(250) DEFAULT NULL,
  `long_name_style` varchar(250) DEFAULT NULL,
  `for_schedule` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff_attendance_type`
--

INSERT INTO `staff_attendance_type` (`id`, `type`, `key_value`, `is_active`, `for_qr_attendance`, `long_lang_name`, `long_name_style`, `for_schedule`, `created_at`, `updated_at`) VALUES
(1, 'Present', '<b class=\"text text-success\">P</b>', 'yes', 1, 'present', 'label label-success', 1, '2023-12-13 10:15:57', '2025-05-10 12:46:11'),
(2, 'Late', '<b class=\"text text-warning\">L</b>', 'yes', 1, 'late', 'label label-warning', 1, '2023-12-13 10:16:01', '2025-05-10 12:46:11'),
(3, 'Absent', '<b class=\"text text-danger\">A</b>', 'yes', 0, 'absent', 'label label-danger', 0, '2023-12-13 10:16:06', '2023-11-10 00:00:00'),
(4, 'Half Day', '<b class=\"text text-warning\">F</b>', 'yes', 1, 'half_day', 'label label-info', 1, '2023-12-14 12:57:07', '2025-05-10 12:46:11'),
(5, 'Holiday', 'H', 'yes', 0, 'holiday', 'label label-warning text-dark', 0, '2023-12-13 10:16:17', '2023-11-10 00:00:00'),
(6, 'Half Day Second Shift', '<b class=\"text text-warning\">SH</b>', 'yes', 1, 'half_day_second_shift', 'label label-info', 1, '2024-09-24 12:28:42', '2024-09-24 12:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendence_schedules`
--

CREATE TABLE `staff_attendence_schedules` (
  `id` int(11) NOT NULL,
  `staff_attendence_type_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `entry_time_from` time DEFAULT NULL,
  `entry_time_to` time DEFAULT NULL,
  `total_institute_hour` time DEFAULT '00:00:00',
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_designation`
--

CREATE TABLE `staff_designation` (
  `id` int(11) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff_designation`
--

INSERT INTO `staff_designation` (`id`, `designation`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lecturer', 'yes', '2025-06-12 09:15:07', '2025-06-12 09:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `staff_id_card`
--

CREATE TABLE `staff_id_card` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `school_address` varchar(255) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `sign_image` varchar(100) NOT NULL,
  `header_color` varchar(100) NOT NULL,
  `enable_vertical_card` int(11) NOT NULL DEFAULT 0,
  `enable_staff_role` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_staff_id` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_staff_department` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_designation` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_fathers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_mothers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_date_of_joining` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_permanent_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_staff_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_staff_phone` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_staff_barcode` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff_id_card`
--

INSERT INTO `staff_id_card` (`id`, `title`, `school_name`, `school_address`, `background`, `logo`, `sign_image`, `header_color`, `enable_vertical_card`, `enable_staff_role`, `enable_staff_id`, `enable_staff_department`, `enable_designation`, `enable_name`, `enable_fathers_name`, `enable_mothers_name`, `enable_date_of_joining`, `enable_permanent_address`, `enable_staff_dob`, `enable_staff_phone`, `enable_staff_barcode`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sample Staff ID Card Horizontal', 'Mount Carmel School', '110 Kings Street, CA', 'background1.png', 'logo1.png', 'sign1.png', '#9b1818', 0, 0, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, '2025-05-10 12:46:11', '2025-05-10 12:46:11'),
(2, 'Sample Staff ID Card Vertical', 'Mount Carmel School', '110 Kings Street, CA', 'background1.png', 'logo1.png', 'sign1.png', '#9b1818', 1, 0, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, '2025-05-10 12:46:11', '2025-05-10 12:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `staff_leave_details`
--

CREATE TABLE `staff_leave_details` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `alloted_leave` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_leave_request`
--

CREATE TABLE `staff_leave_request` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `leave_days` int(11) NOT NULL,
  `employee_remark` varchar(200) NOT NULL,
  `admin_remark` varchar(200) NOT NULL,
  `approve_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `applied_by` int(11) DEFAULT NULL,
  `document_file` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payroll`
--

CREATE TABLE `staff_payroll` (
  `id` int(11) NOT NULL,
  `basic_salary` int(11) NOT NULL,
  `pay_scale` varchar(200) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payslip`
--

CREATE TABLE `staff_payslip` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `basic` float(10,2) NOT NULL,
  `total_allowance` float(10,2) NOT NULL,
  `total_deduction` float(10,2) NOT NULL,
  `leave_deduction` int(11) NOT NULL,
  `tax` varchar(200) NOT NULL,
  `net_salary` float(10,2) NOT NULL,
  `status` varchar(100) NOT NULL,
  `month` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `payment_mode` varchar(200) NOT NULL,
  `payment_date` date NOT NULL,
  `remark` varchar(200) NOT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_rating`
--

CREATE TABLE `staff_rating` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rate` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 decline, 1 Approve',
  `entrydt` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff_rating`
--

INSERT INTO `staff_rating` (`id`, `staff_id`, `comment`, `rate`, `user_id`, `role`, `status`, `entrydt`, `created_at`, `updated_at`) VALUES
(0, 2, 'Excellent teaching', 5, 3, 'student', 0, '2025-11-03 07:51:03', '2025-11-03 07:51:03', '2025-11-03 07:51:03'),
(0, 3, 'The material delivered was not in line with the objectives of the course. Instead we spent a lot of time discussing predefined ideologies which l felt were not relevant to the course', 3, 3, 'student', 0, '2025-11-03 07:52:16', '2025-11-03 07:52:16', '2025-11-03 07:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `role_id`, `staff_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 0, '2025-05-10 12:15:30', '2025-05-10 12:46:11'),
(2, 2, 2, 0, '2025-06-12 09:16:27', '2025-06-12 09:16:27'),
(3, 2, 3, 0, '2025-06-12 09:26:49', '2025-06-12 09:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `staff_timeline`
--

CREATE TABLE `staff_timeline` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` varchar(300) NOT NULL,
  `document` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `admission_no` varchar(100) DEFAULT NULL,
  `roll_no` varchar(100) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `rte` varchar(20) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `cast` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `school_house_id` int(11) DEFAULT NULL,
  `blood_group` varchar(200) NOT NULL,
  `hostel_room_id` int(11) DEFAULT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `samagra_id` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `guardian_is` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(150) NOT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_email` varchar(100) DEFAULT NULL,
  `father_pic` varchar(200) NOT NULL,
  `mother_pic` varchar(200) NOT NULL,
  `guardian_pic` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `previous_school` text DEFAULT NULL,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `measurement_date` date DEFAULT NULL,
  `dis_reason` int(11) NOT NULL,
  `note` varchar(200) DEFAULT NULL,
  `dis_note` text NOT NULL,
  `about` text DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `app_key` text DEFAULT NULL,
  `parent_app_key` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `disable_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `parent_id`, `admission_no`, `roll_no`, `admission_date`, `firstname`, `middlename`, `lastname`, `rte`, `image`, `mobileno`, `email`, `state`, `city`, `pincode`, `religion`, `cast`, `dob`, `gender`, `current_address`, `permanent_address`, `category_id`, `school_house_id`, `blood_group`, `hostel_room_id`, `adhar_no`, `samagra_id`, `bank_account_no`, `bank_name`, `ifsc_code`, `guardian_is`, `father_name`, `father_phone`, `father_occupation`, `mother_name`, `mother_phone`, `mother_occupation`, `guardian_name`, `guardian_relation`, `guardian_phone`, `guardian_occupation`, `guardian_address`, `guardian_email`, `father_pic`, `mother_pic`, `guardian_pic`, `is_active`, `previous_school`, `height`, `weight`, `measurement_date`, `dis_reason`, `note`, `dis_note`, `about`, `designation`, `app_key`, `parent_app_key`, `created_by`, `disable_at`, `created_at`, `updated_at`) VALUES
(1, 2, '1992', 'Expires 31 October 2025 ', '2025-05-26', 'Amy', NULL, 'Kamudyariwa', 'No', 'uploads/student_images/1.jpg', '0776633097', 'kuda@virtual.co.zw', NULL, NULL, NULL, '', '', '1998-05-25', 'Female', '', '', '', 0, '', 0, '', '', '', '', '', 'other', '', '', '', '', '', '', 'Kuda Kamudyariwa', '', '0969731477', '', '', '', '', '', '', 'yes', '', '171', '79', '2025-05-26', 0, '', '', NULL, NULL, NULL, NULL, 1, NULL, '2025-05-26 09:32:28', '2025-05-29 17:03:12'),
(2, 4, '2370', '', '2025-01-01', 'James', NULL, 'Shambare', 'No', NULL, '0966982652', 'jamestendai2000@gmail.com', NULL, NULL, NULL, '', '', '1977-11-19', 'Male', '', '', '', 0, '', 0, '', '', '', '', '', 'other', '', '', '', '', '', '', 'James Tendai', '', '0984746325', '', '', '', '', '', '', 'yes', '', '', '', '2025-05-27', 0, '', '', NULL, NULL, NULL, NULL, 1, NULL, '2025-05-27 11:36:21', '2025-05-27 11:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `student_applied_discounts`
--

CREATE TABLE `student_applied_discounts` (
  `id` int(11) NOT NULL,
  `student_fees_deposite_id` int(11) DEFAULT NULL,
  `student_fees_discount_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `sub_invoice_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_applyleave`
--

CREATE TABLE `student_applyleave` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `apply_date` date NOT NULL,
  `status` int(11) NOT NULL,
  `docs` varchar(200) DEFAULT NULL,
  `reason` text NOT NULL,
  `approve_by` int(11) DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `request_type` int(11) NOT NULL COMMENT '0 student,1 staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendences`
--

CREATE TABLE `student_attendences` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `biometric_attendence` int(11) NOT NULL DEFAULT 0,
  `qrcode_attendance` int(11) NOT NULL DEFAULT 0,
  `date` date DEFAULT NULL,
  `attendence_type_id` int(11) DEFAULT NULL,
  `remark` varchar(200) NOT NULL,
  `biometric_device_data` text DEFAULT NULL,
  `user_agent` varchar(250) DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_attendences`
--

INSERT INTO `student_attendences` (`id`, `student_session_id`, `biometric_attendence`, `qrcode_attendance`, `date`, `attendence_type_id`, `remark`, `biometric_device_data`, `user_agent`, `in_time`, `out_time`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 0, 1, '2025-05-27', 1, '', '{\"uid\":\"\",\"user_id\":\"\",\"t\":\"\",\"ip\":\"216.234.213.50\",\"serial_number\":\"\",\"country\":\"Zambia\",\"city\":\"Lusaka\",\"region\":\"Lusaka Province\",\"latitude\":-15.3875259,\"longitude\":28.3228165}', 'Desktop | Windows 10 | Chrome 136.0.0.0', '14:20:04', '14:22:38', 'no', '2025-05-27 14:20:04', '2025-05-27 12:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendence_schedules`
--

CREATE TABLE `student_attendence_schedules` (
  `id` int(11) NOT NULL,
  `attendence_type_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `entry_time_from` time DEFAULT NULL,
  `entry_time_to` time DEFAULT NULL,
  `total_institute_hour` time DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_attendence_schedules`
--

INSERT INTO `student_attendence_schedules` (`id`, `attendence_type_id`, `class_section_id`, `entry_time_from`, `entry_time_to`, `total_institute_hour`, `is_active`, `created_at`, `updated_at`) VALUES
(13, 1, 1, '08:30:00', '09:05:00', '02:55:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(14, 3, 1, '09:05:01', '09:30:00', '00:30:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(15, 6, 1, '09:30:01', '10:00:00', '00:30:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(16, 1, 2, '12:30:00', '18:05:00', '02:30:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(17, 3, 2, '18:05:01', '18:30:00', '00:30:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(18, 6, 2, '18:30:01', '19:00:00', '00:30:00', 0, '2025-05-27 12:13:21', '2025-05-27 12:13:21'),
(25, 1, 3, '08:00:00', '09:05:00', '02:55:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10'),
(26, 3, 3, '09:05:01', '12:00:00', '00:30:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10'),
(27, 6, 3, '09:30:01', '10:00:00', '00:30:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10'),
(28, 1, 4, '12:30:00', '18:05:00', '02:30:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10'),
(29, 3, 4, '18:05:01', '18:30:00', '00:30:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10'),
(30, 6, 4, '18:30:01', '19:00:00', '00:30:00', 0, '2025-05-27 12:18:10', '2025-05-27 12:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `student_behaviour`
--

CREATE TABLE `student_behaviour` (
  `id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `description` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_dashboard_settings`
--

CREATE TABLE `student_dashboard_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `short_code` varchar(255) NOT NULL,
  `is_student` int(11) DEFAULT NULL,
  `is_parent` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_dashboard_settings`
--

INSERT INTO `student_dashboard_settings` (`id`, `name`, `short_code`, `is_student`, `is_parent`, `created_at`, `updated_at`) VALUES
(1, 'welcome_student', '', 1, 1, '2024-10-15 12:14:22', '2025-05-10 12:46:12'),
(2, 'notice_board', 'communicate', 1, 1, '2024-10-15 12:14:25', '2025-05-10 12:46:12'),
(3, 'subject_progress', 'lesson_plan', 1, 1, '2024-10-15 12:14:27', '2025-05-10 12:46:12'),
(4, 'upcomming_class', 'academics', 1, 1, '2024-10-15 12:14:55', '2025-05-10 12:46:12'),
(5, 'homework', 'homework', 1, 1, '2024-10-15 12:14:56', '2025-05-10 12:46:12'),
(6, 'teacher_list', 'human_resource', 1, 1, '2024-10-15 12:14:57', '2025-05-10 12:46:12'),
(7, 'visitor_list', 'front_office', 1, 1, '2024-10-15 12:14:58', '2025-06-18 08:11:55'),
(8, 'library', 'library', 1, 1, '2024-10-15 12:14:59', '2025-05-10 12:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `student_doc`
--

CREATE TABLE `student_doc` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `doc` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_doc`
--

INSERT INTO `student_doc` (`id`, `student_id`, `title`, `doc`, `created_at`, `updated_at`) VALUES
(1, 2, 'Fee', '1748349946-12758366126835b3fad1119!FEE STRUCTURE.JPG', '2025-05-27 12:45:46', '2025-05-27 12:45:46'),
(2, 2, 'Test', '1748349973-17616374646835b41540a0f!James Shambare_2370.pdf', '2025-05-27 12:46:13', '2025-05-27 12:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `student_edit_fields`
--

CREATE TABLE `student_edit_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_edit_fields`
--

INSERT INTO `student_edit_fields` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'student_photo', 1, '2025-05-29 16:53:32', '2025-05-29 16:53:32'),
(2, 'mobile_no', 1, '2025-05-29 16:53:40', '2025-05-29 16:53:40'),
(3, 'student_email', 1, '2025-05-29 16:53:41', '2025-05-29 16:53:41'),
(4, 'previous_school_details', 1, '2025-05-29 16:53:58', '2025-05-29 16:53:58'),
(5, 'student_weight', 1, '2025-05-29 16:54:49', '2025-05-29 16:54:49'),
(6, 'student_height', 1, '2025-05-29 16:54:51', '2025-05-29 16:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `student_educational_details`
--

CREATE TABLE `student_educational_details` (
  `id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `education_year` varchar(255) NOT NULL,
  `education_detail` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees`
--

CREATE TABLE `student_fees` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `feemaster_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `amount_discount` float(10,2) NOT NULL,
  `amount_fine` float(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_deposite`
--

CREATE TABLE `student_fees_deposite` (
  `id` int(11) NOT NULL,
  `student_fees_master_id` int(11) DEFAULT NULL,
  `fee_groups_feetype_id` int(11) DEFAULT NULL,
  `student_transport_fee_id` int(11) DEFAULT NULL,
  `amount_detail` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_fees_deposite`
--

INSERT INTO `student_fees_deposite` (`id`, `student_fees_master_id`, `fee_groups_feetype_id`, `student_transport_fee_id`, `amount_detail`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 4, 1, NULL, '{\"1\":{\"amount\":50,\"amount_discount\":0,\"amount_fine\":0,\"date\":\"2025-09-18\",\"description\":\"\",\"collected_by\":\"Super Admin(9000)\",\"payment_mode\":\"Cash\",\"received_by\":\"1\",\"inv_no\":1}}', 'no', '2025-09-18 11:56:10', '2025-09-18 11:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_discounts`
--

CREATE TABLE `student_fees_discounts` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `fees_discount_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'assigned',
  `payment_id` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_master`
--

CREATE TABLE `student_fees_master` (
  `id` int(11) NOT NULL,
  `is_system` int(11) NOT NULL DEFAULT 0,
  `student_session_id` int(11) DEFAULT NULL,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT 0.00,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_fees_master`
--

INSERT INTO `student_fees_master` (`id`, `is_system`, `student_session_id`, `fee_session_group_id`, `amount`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 0, 2, 1, 0.00, 'no', '2025-06-18 07:16:24', '2025-06-18 07:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_processing`
--

CREATE TABLE `student_fees_processing` (
  `id` int(11) NOT NULL,
  `gateway_ins_id` int(11) NOT NULL,
  `fee_category` varchar(255) NOT NULL,
  `student_fees_master_id` int(11) DEFAULT NULL,
  `fee_groups_feetype_id` int(11) DEFAULT NULL,
  `student_transport_fee_id` int(11) DEFAULT NULL,
  `amount_detail` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_incidents`
--

CREATE TABLE `student_incidents` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `assign_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_incident_comments`
--

CREATE TABLE `student_incident_comments` (
  `id` int(11) NOT NULL,
  `student_incident_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_quiz_status`
--

CREATE TABLE `student_quiz_status` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `course_quiz_id` int(11) DEFAULT NULL,
  `total_question` int(11) DEFAULT NULL,
  `correct_answer` int(11) DEFAULT NULL,
  `wrong_answer` int(11) DEFAULT NULL,
  `not_answer` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1-completed,0-incomplete	',
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_refrence`
--

CREATE TABLE `student_refrence` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_session`
--

CREATE TABLE `student_session` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `hostel_room_id` int(11) DEFAULT NULL,
  `vehroute_id` int(11) DEFAULT NULL,
  `route_pickup_point_id` int(11) DEFAULT NULL,
  `transport_fees` float(10,2) NOT NULL DEFAULT 0.00,
  `fees_discount` float(10,2) NOT NULL DEFAULT 0.00,
  `is_leave` int(11) NOT NULL DEFAULT 0,
  `is_active` varchar(255) DEFAULT 'no',
  `is_alumni` int(11) NOT NULL,
  `default_login` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `student_session`
--

INSERT INTO `student_session` (`id`, `session_id`, `student_id`, `class_id`, `section_id`, `hostel_room_id`, `vehroute_id`, `route_pickup_point_id`, `transport_fees`, `fees_discount`, `is_leave`, `is_active`, `is_alumni`, `default_login`, `created_at`, `updated_at`) VALUES
(2, 21, 2, 2, 2, NULL, NULL, NULL, 0.00, 0.00, 0, 'no', 0, 1, '2025-05-27 11:36:21', '2025-06-18 07:16:24'),
(4, 26, 1, 1, 2, NULL, NULL, NULL, 0.00, 0.00, 0, 'no', 0, 1, '2025-10-10 15:24:39', '2025-10-10 15:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `student_skills_detail`
--

CREATE TABLE `student_skills_detail` (
  `id` int(11) NOT NULL,
  `skill_category` varchar(255) NOT NULL,
  `skill_detail` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_attendances`
--

CREATE TABLE `student_subject_attendances` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `subject_timetable_id` int(11) DEFAULT NULL,
  `attendence_type_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_timeline`
--

CREATE TABLE `student_timeline` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` text NOT NULL,
  `document` varchar(200) DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `created_student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_transport_fees`
--

CREATE TABLE `student_transport_fees` (
  `id` int(11) NOT NULL,
  `transport_feemaster_id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `route_pickup_point_id` int(11) NOT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_work_experience`
--

CREATE TABLE `student_work_experience` (
  `id` int(11) NOT NULL,
  `institute` text NOT NULL,
  `designation` text NOT NULL,
  `year` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `detail` text NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bible Doctrines', 'BID', 'theory', 'no', '2025-06-12 07:40:08', '2025-06-12 07:40:08'),
(2, 'Righteousness', 'RIG', 'theory', 'no', '2025-06-12 07:40:28', '2025-06-12 07:40:28'),
(3, 'Search For Significance', 'SFS', 'theory', 'no', '2025-06-12 07:40:50', '2025-06-12 07:40:50'),
(4, 'Understanding Grace', 'UGR', 'theory', 'no', '2025-06-12 07:41:13', '2025-06-12 07:41:13'),
(5, 'Biblical Finances', 'BF', 'theory', 'no', '2025-06-12 07:41:45', '2025-06-12 07:41:45'),
(6, 'Blood Covenant', 'BLC', 'theory', 'no', '2025-06-12 07:42:09', '2025-06-12 07:42:09'),
(7, 'Character Of God', 'COG', 'theory', 'no', '2025-06-12 07:42:26', '2025-06-12 07:42:26'),
(8, 'Faith Foundations', 'FAF', 'theory', 'no', '2025-06-12 07:42:47', '2025-06-12 07:42:47'),
(9, 'Old Testament Survey', 'OTS', 'theory', 'no', '2025-06-12 07:43:06', '2025-06-12 07:43:06'),
(10, 'Personal Growth and Development', 'PGD', 'theory', 'no', '2025-06-12 07:43:30', '2025-06-12 07:43:30'),
(11, 'Pneumatology', 'PNT', 'theory', 'no', '2025-06-12 07:43:52', '2025-06-12 07:43:52'),
(12, 'Christ The Healer', 'CTH', 'theory', 'no', '2025-06-12 07:44:07', '2025-06-12 07:44:07'),
(13, 'Church History', 'CHH', 'theory', 'no', '2025-06-12 07:44:22', '2025-06-12 07:44:22'),
(14, 'Effective Prayer', 'EFP', 'theory', 'no', '2025-06-12 07:44:45', '2025-06-12 07:44:45'),
(15, 'Hermeneutics', 'HER', 'theory', 'no', '2025-06-12 07:45:09', '2025-06-12 07:45:09'),
(16, 'Life and Ministry of Jesus', 'LMJ', 'theory', 'no', '2025-06-12 07:45:31', '2025-06-12 07:45:31'),
(17, 'New Testament Survey', 'NTS', 'theory', 'no', '2025-06-12 07:45:49', '2025-06-12 07:45:49'),
(18, 'Old Testament Survey II', 'OTS II', 'theory', 'no', '2025-06-12 07:47:23', '2025-06-12 07:47:23'),
(19, 'Introduction to Pastoral Ministry', 'IPM', 'theory', 'no', '2025-06-12 07:49:08', '2025-06-12 07:49:08'),
(20, 'Children and Youth', 'CHY', 'theory', 'no', '2025-06-12 07:49:41', '2025-06-12 07:49:41'),
(21, 'Developing Leaders Around You', 'DLA', 'theory', 'no', '2025-06-12 07:50:18', '2025-06-12 07:50:18'),
(22, 'Homiletics I', 'HOM I', 'theory', 'no', '2025-06-12 07:51:53', '2025-06-12 07:51:53'),
(23, 'Marriage Family and Ministry', 'MFM', 'theory', 'no', '2025-06-12 07:52:40', '2025-06-12 07:52:40'),
(24, 'Principles of Teaching', 'POT', 'theory', 'no', '2025-06-12 07:53:03', '2025-06-12 07:53:03'),
(25, 'Triumphant Church', 'TRC', 'theory', 'no', '2025-06-12 07:53:25', '2025-06-12 07:53:25'),
(26, 'Boundaries', 'BND', 'theory', 'no', '2025-06-12 07:53:40', '2025-06-12 07:53:40'),
(27, 'Homiletics II', 'HOM II', 'practical', 'no', '2025-06-12 07:54:32', '2025-06-12 08:06:59'),
(28, 'Introduction to Counselling', 'ITC', 'theory', 'no', '2025-06-12 07:55:08', '2025-06-12 07:55:08'),
(29, 'Life of Worship', 'LOW', 'theory', 'no', '2025-06-12 07:55:26', '2025-06-12 07:55:26'),
(30, 'Ministry Finances', 'MNF', 'theory', 'no', '2025-06-12 07:55:56', '2025-06-12 07:55:56'),
(31, 'The Ministry Gifts', 'TMG', 'theory', 'no', '2025-06-12 07:56:19', '2025-06-12 07:56:19'),
(32, 'Eschatology', 'ESC', 'theory', 'no', '2025-06-12 07:56:35', '2025-06-12 07:56:35'),
(33, 'High Impact Leadership', 'HIL', 'theory', 'no', '2025-06-12 07:57:13', '2025-06-12 08:59:54'),
(34, 'Master Planning', 'MPL', 'theory', 'no', '2025-06-12 07:57:29', '2025-06-12 07:57:29'),
(35, 'Practical Ministry Symposium', 'PMS', 'theory', 'no', '2025-06-12 07:57:51', '2025-06-12 07:57:51'),
(36, 'Supportive Ministry II', 'SUM II', 'theory', 'no', '2025-06-12 07:58:10', '2025-06-12 07:58:10'),
(37, 'Supportive Ministry', 'SUM', 'theory', 'no', '2025-06-12 08:02:09', '2025-06-12 08:02:09'),
(38, 'The Authority of the Believer', 'TAB', 'theory', 'no', '2025-06-12 08:02:38', '2025-06-12 08:02:38'),
(39, 'Developing the Leader Within', 'DLW', 'theory', 'no', '2025-06-12 08:03:01', '2025-06-12 08:03:01'),
(40, 'Ephesians', 'EPH', 'theory', 'no', '2025-06-12 08:03:14', '2025-06-12 08:03:14'),
(41, 'Introduction to World Missions', 'IWM', 'theory', 'no', '2025-06-12 08:03:44', '2025-06-12 08:03:44'),
(42, 'Personal Evangelism', 'PEV', 'theory', 'no', '2025-06-12 08:04:03', '2025-06-12 08:04:03'),
(43, 'Effective Ministry', 'EFM', 'theory', 'no', '2025-06-12 08:04:46', '2025-06-12 08:04:46'),
(44, 'Life Of Faith', 'LOF', 'theory', 'no', '2025-06-12 08:05:04', '2025-06-18 07:40:14'),
(45, 'Ministerial Ethics', 'MNE', 'theory', 'no', '2025-06-12 08:05:21', '2025-06-12 08:05:21'),
(46, 'Team Leadership Lab I', 'TLL', 'practical', 'no', '2025-06-12 08:05:46', '2025-06-12 08:05:46'),
(47, 'Team Leadership Lab II', 'TL2', 'practical', 'no', '2025-06-12 08:06:15', '2025-06-12 08:06:15'),
(48, 'Truth and Error', 'TAE', 'theory', 'no', '2025-06-12 08:06:33', '2025-06-12 08:06:33'),
(49, 'Church Planting', 'CPL', 'theory', 'no', '2025-06-12 09:01:07', '2025-06-12 09:01:07'),
(50, 'Itinerant Missionary Evangelist', 'IME', 'theory', 'no', '2025-06-12 09:01:42', '2025-06-12 09:01:42'),
(51, 'Chapel', 'CHA', 'theory', 'no', '2025-06-12 09:39:41', '2025-06-12 09:39:41'),
(52, 'Prayer School', 'PRS', 'theory', 'no', '2025-06-12 09:39:57', '2025-06-12 09:39:57'),
(53, 'Changes That Heal', 'CNH', 'theory', 'no', '2025-06-26 14:47:41', '2025-06-26 14:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `subject_groups`
--

CREATE TABLE `subject_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subject_groups`
--

INSERT INTO `subject_groups` (`id`, `name`, `description`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 'First Year', '', 21, '2025-06-12 08:36:43', '2025-06-12 09:09:31'),
(2, 'Second Year', '', 21, '2025-06-12 08:39:55', '2025-06-12 08:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `subject_group_class_sections`
--

CREATE TABLE `subject_group_class_sections` (
  `id` int(11) NOT NULL,
  `subject_group_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subject_group_class_sections`
--

INSERT INTO `subject_group_class_sections` (`id`, `subject_group_id`, `class_section_id`, `session_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 21, NULL, 0, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(2, 1, 4, 21, NULL, 0, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(3, 2, 1, 21, NULL, 0, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(4, 2, 2, 21, NULL, 0, '2025-06-12 08:39:55', '2025-06-12 08:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `subject_group_subjects`
--

CREATE TABLE `subject_group_subjects` (
  `id` int(11) NOT NULL,
  `subject_group_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subject_group_subjects`
--

INSERT INTO `subject_group_subjects` (`id`, `subject_group_id`, `session_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 1, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(2, 1, 21, 2, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(3, 1, 21, 3, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(4, 1, 21, 6, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(5, 1, 21, 7, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(6, 1, 21, 8, '2025-06-12 08:36:43', '2025-06-12 08:36:43'),
(7, 2, 21, 21, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(8, 2, 21, 22, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(9, 2, 21, 24, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(10, 2, 21, 29, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(11, 2, 21, 34, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(12, 2, 21, 44, '2025-06-12 08:39:55', '2025-06-12 08:39:55'),
(13, 2, 21, 19, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(14, 2, 21, 20, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(15, 2, 21, 23, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(16, 2, 21, 26, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(17, 2, 21, 27, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(18, 2, 21, 28, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(19, 2, 21, 30, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(20, 2, 21, 31, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(21, 2, 21, 32, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(22, 2, 21, 33, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(23, 2, 21, 35, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(24, 2, 21, 36, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(25, 2, 21, 43, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(26, 2, 21, 45, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(27, 2, 21, 46, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(28, 2, 21, 47, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(29, 2, 21, 48, '2025-06-12 08:52:54', '2025-06-12 08:52:54'),
(30, 1, 21, 4, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(31, 1, 21, 5, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(32, 1, 21, 9, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(33, 1, 21, 10, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(34, 1, 21, 11, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(35, 1, 21, 12, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(36, 1, 21, 13, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(37, 1, 21, 14, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(38, 1, 21, 15, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(39, 1, 21, 16, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(40, 1, 21, 17, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(41, 1, 21, 18, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(42, 1, 21, 37, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(43, 1, 21, 38, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(44, 1, 21, 39, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(45, 1, 21, 40, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(46, 1, 21, 41, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(47, 1, 21, 42, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(48, 1, 21, 44, '2025-06-12 08:58:46', '2025-06-12 08:58:46'),
(49, 2, 21, 49, '2025-06-12 09:02:16', '2025-06-12 09:02:16'),
(50, 2, 21, 50, '2025-06-12 09:02:16', '2025-06-12 09:02:16'),
(51, 1, 21, 51, '2025-06-12 09:41:49', '2025-06-12 09:41:49'),
(52, 1, 21, 52, '2025-06-12 09:41:49', '2025-06-12 09:41:49'),
(53, 2, 21, 51, '2025-06-12 09:42:12', '2025-06-12 09:42:12'),
(54, 2, 21, 52, '2025-06-12 09:42:12', '2025-06-12 09:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `subject_syllabus`
--

CREATE TABLE `subject_syllabus` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_for` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_from` varchar(255) NOT NULL,
  `time_to` varchar(255) NOT NULL,
  `presentation` text NOT NULL,
  `attachment` text NOT NULL,
  `lacture_youtube_url` varchar(255) NOT NULL,
  `lacture_video` varchar(255) NOT NULL,
  `sub_topic` text NOT NULL,
  `teaching_method` text NOT NULL,
  `general_objectives` text NOT NULL,
  `previous_knowledge` text NOT NULL,
  `comprehensive_questions` text NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_timetable`
--

CREATE TABLE `subject_timetable` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `subject_group_id` int(11) DEFAULT NULL,
  `subject_group_subject_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `time_from` varchar(20) DEFAULT NULL,
  `time_to` varchar(20) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `room_no` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `subject_timetable`
--

INSERT INTO `subject_timetable` (`id`, `session_id`, `class_id`, `section_id`, `subject_group_id`, `subject_group_subject_id`, `staff_id`, `day`, `time_from`, `time_to`, `start_time`, `end_time`, `room_no`, `created_at`, `updated_at`) VALUES
(1, 21, 2, 2, 1, 2, 3, 'Monday', '9:00 AM', '09:50 AM', '09:00:00', '09:50:00', 'Classroom 1', '2025-06-12 09:31:35', '2025-06-12 09:35:04'),
(2, 21, 2, 2, 1, 1, 2, 'Monday', '10:00 AM', '10:50 AM', '10:00:00', '10:50:00', 'Classroom 1', '2025-06-12 09:32:04', '2025-06-12 09:35:04'),
(3, 21, 2, 2, 1, 1, 2, 'Monday', '11:00 AM', '11:50 AM', '11:00:00', '11:50:00', 'Classroom 1', '2025-06-12 09:35:04', '2025-06-12 09:35:04'),
(4, 21, 2, 2, 1, 2, 3, 'Tuesday', '9:00 AM', '09:50 AM', '09:00:00', '09:50:00', 'Classroom 1', '2025-06-12 09:36:03', '2025-06-12 09:36:03'),
(5, 21, 2, 2, 1, 1, 2, 'Tuesday', '10:00 AM', '10:50 AM', '10:00:00', '10:50:00', 'Classroom 1', '2025-06-12 09:36:03', '2025-06-12 09:36:03'),
(6, 21, 2, 2, 1, 1, 2, 'Tuesday', '11:00 AM', '11:50 AM', '11:00:00', '11:50:00', 'Classroom 1', '2025-06-12 09:36:03', '2025-06-12 09:36:03'),
(7, 21, 2, 2, 1, 2, 3, 'Wednesday', '9:00 AM', '09:50 AM', '09:00:00', '09:50:00', 'Classroom 1', '2025-06-12 09:36:28', '2025-06-12 09:36:28'),
(8, 21, 2, 2, 1, 1, 2, 'Wednesday', '10:00 AM', '10:50 AM', '10:00:00', '10:50:00', 'Classroom 1', '2025-06-12 09:36:28', '2025-06-12 09:36:28'),
(9, 21, 2, 2, 1, 1, 2, 'Wednesday', '11:00 AM', '11:50 AM', '11:00:00', '11:50:00', 'Classroom 1', '2025-06-12 09:36:28', '2025-06-12 09:36:28'),
(10, 21, 2, 2, 1, 2, 3, 'Thursday', '9:00 AM', '09:50 AM', '09:00:00', '09:50:00', 'Classroom 1', '2025-06-12 09:37:29', '2025-06-12 09:37:29'),
(11, 21, 2, 2, 1, 1, 2, 'Thursday', '10:00 AM', '10:50 AM', '10:00:00', '10:50:00', 'Classroom 1', '2025-06-12 09:37:29', '2025-06-12 09:37:29'),
(12, 21, 2, 2, 1, 1, 2, 'Thursday', '11:00 AM', '11:50 AM', '11:00:00', '11:50:00', 'Classroom 1', '2025-06-12 09:37:29', '2025-06-12 09:37:29'),
(13, 21, 2, 2, 1, 52, 3, 'Tuesday', '12:00 PM', '12:50 PM', '12:00:00', '12:50:00', 'Classroom 1', '2025-06-12 09:45:31', '2025-06-12 09:45:31'),
(14, 21, 2, 2, 1, 51, 2, 'Thursday', '12:00 PM', '12:50 PM', '12:00:00', '12:50:00', 'Classroom 1', '2025-06-12 09:46:22', '2025-06-12 09:46:22');

-- --------------------------------------------------------

--
-- Table structure for table `submit_assignment`
--

CREATE TABLE `submit_assignment` (
  `id` int(11) NOT NULL,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `docs` varchar(225) NOT NULL,
  `file_name` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_admitcards`
--

CREATE TABLE `template_admitcards` (
  `id` int(11) NOT NULL,
  `template` varchar(250) DEFAULT NULL,
  `heading` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `left_logo` varchar(200) DEFAULT NULL,
  `right_logo` varchar(200) DEFAULT NULL,
  `exam_name` varchar(200) DEFAULT NULL,
  `school_name` varchar(200) DEFAULT NULL,
  `exam_center` varchar(200) DEFAULT NULL,
  `sign` varchar(200) DEFAULT NULL,
  `background_img` varchar(200) DEFAULT NULL,
  `is_name` int(11) NOT NULL DEFAULT 1,
  `is_father_name` int(11) NOT NULL DEFAULT 1,
  `is_mother_name` int(11) NOT NULL DEFAULT 1,
  `is_dob` int(11) NOT NULL DEFAULT 1,
  `is_admission_no` int(11) NOT NULL DEFAULT 1,
  `is_roll_no` int(11) NOT NULL DEFAULT 1,
  `is_address` int(11) NOT NULL DEFAULT 1,
  `is_gender` int(11) NOT NULL DEFAULT 1,
  `is_photo` int(11) NOT NULL,
  `is_class` int(11) NOT NULL DEFAULT 0,
  `is_section` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) DEFAULT 0,
  `content_footer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `template_admitcards`
--

INSERT INTO `template_admitcards` (`id`, `template`, `heading`, `title`, `left_logo`, `right_logo`, `exam_name`, `school_name`, `exam_center`, `sign`, `background_img`, `is_name`, `is_father_name`, `is_mother_name`, `is_dob`, `is_admission_no`, `is_roll_no`, `is_address`, `is_gender`, `is_photo`, `is_class`, `is_section`, `is_active`, `content_footer`, `created_at`, `updated_at`) VALUES
(1, 'Sample Admit Card', '', '', 'ab12c4b65f53ee621dcf84370a7c5be4.png', '0910482bf79df5fd103e8383d61b387a.png', 'Test', 'Mount Carmel School', 'test dmit card2', 'aa9c7087e68c5af1d2c04946de1d3bd3.png', '782a71f53ea6bca213012d49e9d46d98.jpg', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, '2020-02-28 14:26:15', '2025-05-10 12:46:14');

-- --------------------------------------------------------

--
-- Table structure for table `template_marksheets`
--

CREATE TABLE `template_marksheets` (
  `id` int(11) NOT NULL,
  `header_image` varchar(200) DEFAULT NULL,
  `template` varchar(200) DEFAULT NULL,
  `heading` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `left_logo` varchar(200) DEFAULT NULL,
  `right_logo` varchar(200) DEFAULT NULL,
  `exam_name` varchar(200) DEFAULT NULL,
  `school_name` varchar(200) DEFAULT NULL,
  `exam_center` varchar(200) DEFAULT NULL,
  `left_sign` varchar(200) DEFAULT NULL,
  `middle_sign` varchar(200) DEFAULT NULL,
  `right_sign` varchar(200) DEFAULT NULL,
  `exam_session` int(11) DEFAULT 1,
  `is_name` int(11) DEFAULT 1,
  `is_father_name` int(11) DEFAULT 1,
  `is_mother_name` int(11) DEFAULT 1,
  `is_dob` int(11) DEFAULT 1,
  `is_admission_no` int(11) DEFAULT 1,
  `is_roll_no` int(11) DEFAULT 1,
  `is_photo` int(11) DEFAULT 1,
  `is_division` int(11) NOT NULL DEFAULT 1,
  `is_rank` int(11) NOT NULL DEFAULT 0,
  `is_customfield` int(11) NOT NULL,
  `background_img` varchar(200) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `is_class` int(11) NOT NULL DEFAULT 0,
  `is_teacher_remark` int(11) NOT NULL DEFAULT 1,
  `is_section` int(11) NOT NULL DEFAULT 0,
  `content` text DEFAULT NULL,
  `content_footer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `template_marksheets`
--

INSERT INTO `template_marksheets` (`id`, `header_image`, `template`, `heading`, `title`, `left_logo`, `right_logo`, `exam_name`, `school_name`, `exam_center`, `left_sign`, `middle_sign`, `right_sign`, `exam_session`, `is_name`, `is_father_name`, `is_mother_name`, `is_dob`, `is_admission_no`, `is_roll_no`, `is_photo`, `is_division`, `is_rank`, `is_customfield`, `background_img`, `date`, `is_class`, `is_teacher_remark`, `is_section`, `content`, `content_footer`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Sample Marksheet', 'BOARD OF SECONDARY EDUCATION, MADHYA PRADESH, BHOPAL', 'BOARD OF SECONDARY EDUCATION, MADHYA PRADESH, BHOPAL', 'f314cec3f688771ccaeddbcee6e52f7c.png', 'e824b2df53266266be2dbfd2001168b8.png', 'HIGHER SECONDARY SCHOOL CERTIFICATE EXAMINATION', 'Mount Carmel School', 'GOVT GIRLS H S SCHOOL', '331e0690e50f8c6b7a219a0a2b9667f7.png', '351f513d79ee5c0f642c2d36514a1ff4.png', 'fb79d2c0d163357d1706b78550a05e2c.png', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, '', NULL, 0, 1, 0, NULL, NULL, '2020-02-28 14:26:06', '2025-05-10 12:46:14'),
(2, '1751385307-894586804686404dbe6512!RBTC_Logo_Web_OL_2_Color.png', 'Transcript First Year', NULL, NULL, '', '', 'Advanced Training/ First Year Program', 'Rhema Bible Training Centre Zimbabwe', 'Bulawayo, Zimbabwe', '', '', '', 1, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, '', '30-06-2025', 1, 0, 1, 'Test boy text', 'Footer text', '2025-06-25 07:42:44', '2025-07-01 15:55:59');

-- --------------------------------------------------------

--
-- Table structure for table `thermal_print_settings`
--

CREATE TABLE `thermal_print_settings` (
  `id` int(11) NOT NULL,
  `is_print` int(11) NOT NULL,
  `school_name` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `thermal_print_settings`
--

INSERT INTO `thermal_print_settings` (`id`, `is_print`, `school_name`, `address`, `footer_text`, `created_at`) VALUES
(1, 0, 'Mount/ Carmel /School', '25 Kings Street, CA <br> 89562423934 <br> mountcarmelmailtest@gmail.com', 'This receipt is computer generated hence no signature is required.', '2025-01-10 17:02:48');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `complete_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `session_id`, `lesson_id`, `name`, `status`, `complete_date`, `created_at`, `updated_at`) VALUES
(1, 21, 1, '•	Understanding faith as a daily walk (Hebrews 10:38 NKJV - ', 0, NULL, '2025-06-18 07:54:59', '2025-06-18 07:57:20'),
(2, 21, 1, '•	Faith is not a crisis response but a lifestyle (Romans 1:17 AMP - \"For in the gospel the righteousness of God is revealed, both springing from faith and leading to faith; as it is written and forever remains written, ‘The just and upright shall live by ', 0, NULL, '2025-06-18 07:57:20', '2025-06-18 07:57:20'),
(3, 21, 1, '•	Example from Charles Capps (Faith and Confession): Capps emphasizes that our words shape our reality. He explains how speaking negatively creates barriers, while faith-filled words create breakthroughs.', 0, NULL, '2025-06-18 07:57:20', '2025-06-18 07:57:20'),
(4, 21, 2, 'How faith influences decisions and character (2 Corinthians 5:7 NKJV - ', 0, NULL, '2025-06-18 07:59:23', '2025-06-18 07:59:48'),
(5, 21, 2, 'Personal testimonies of faith in action', 0, NULL, '2025-06-18 07:59:23', '2025-06-18 07:59:23'),
(6, 21, 2, 'Group discussion: “What does walking by faith look like?”', 0, NULL, '2025-06-18 07:59:23', '2025-06-18 07:59:48'),
(7, 21, 2, 'Reflection: What areas of life do we struggle to apply faith in?', 0, NULL, '2025-06-18 08:00:46', '2025-06-18 08:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `transport_feemaster`
--

CREATE TABLE `transport_feemaster` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `month` varchar(50) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `fine_amount` float(10,2) DEFAULT 0.00,
  `fine_type` varchar(50) DEFAULT NULL,
  `fine_percentage` float(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `transport_feemaster`
--

INSERT INTO `transport_feemaster` (`id`, `session_id`, `month`, `due_date`, `fine_amount`, `fine_type`, `fine_percentage`, `created_at`, `updated_at`) VALUES
(1, 21, 'January', '2025-01-31', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(2, 21, 'February', '2025-02-28', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(3, 21, 'March', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(4, 21, 'April', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(5, 21, 'May', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(6, 21, 'June', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(7, 21, 'July', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(8, 21, 'August', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(9, 21, 'September', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(10, 21, 'October', '2025-05-29', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(11, 21, 'November', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45'),
(12, 21, 'December', '2025-05-30', 1000.00, 'fix', NULL, '2025-05-30 06:08:45', '2025-05-30 06:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `transport_route`
--

CREATE TABLE `transport_route` (
  `id` int(11) NOT NULL,
  `route_title` varchar(100) DEFAULT NULL,
  `no_of_vehicle` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `transport_route`
--

INSERT INTO `transport_route` (`id`, `route_title`, `no_of_vehicle`, `note`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Makeni', NULL, NULL, 'no', '2025-05-30 06:10:39', '2025-05-30 06:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `upload_contents`
--

CREATE TABLE `upload_contents` (
  `id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `thumb_path` varchar(300) DEFAULT NULL,
  `dir_path` varchar(300) DEFAULT NULL,
  `real_name` text NOT NULL,
  `img_name` varchar(300) DEFAULT NULL,
  `thumb_name` varchar(300) DEFAULT NULL,
  `file_type` varchar(100) NOT NULL,
  `mime_type` text NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `vid_url` text NOT NULL,
  `vid_title` varchar(250) NOT NULL,
  `upload_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `upload_contents`
--

INSERT INTO `upload_contents` (`id`, `content_type_id`, `image`, `thumb_path`, `dir_path`, `real_name`, `img_name`, `thumb_name`, `file_type`, `mime_type`, `file_size`, `vid_url`, `vid_title`, `upload_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'uploads/school_content/material/media/thumb/', 'uploads/school_content/material/media/', 'Worthy / You Are Holy / We Fall Down (Live) - @JohnWilds , Bethel Music', '1748522487-1368135870683855f74cfad!1748522487.jpg', '1748522487-1368135870683855f74cfad!thumb_1748522487.jpg', 'video', '', '0', 'https://www.youtube.com/watch?v=L0u0hKWbmX8&list=RDL0u0hKWbmX8&start_radio=1', 'Worthy / You Are Holy / We Fall Down (Live) - @JohnWilds , Bethel Music', 1, '2025-05-29 14:41:27', '2025-05-29 12:41:27'),
(2, 2, NULL, 'uploads/school_content/material/media/thumb/', 'uploads/school_content/material/media/', 'Kenneth-E-Hagin-The-Believers-Authority.pdf', '1749025858-63148221768400442672c2!Kenneth-E-Hagin-The-Believers-Authority.pdf', '', 'pdf', 'application/pdf', '917714', '', '', 1, '2025-06-04 10:30:58', '2025-06-04 08:30:58'),
(3, 1, NULL, 'uploads/school_content/material/media/thumb/', 'uploads/school_content/material/media/', 'Rhema Bible Training College: Class Audio', '1749110528-111368712268414f003bcd8!1749110528.jpg', '1749110528-111368712268414f003bcd8!thumb_1749110528.jpg', 'video', '', '0', 'https://youtu.be/RLaBwMASArk?si=Ni9jDmcR7R-mPtXd', 'Rhema Bible Training College: Class Audio', 1, '2025-06-05 10:02:08', '2025-06-05 08:02:08'),
(4, 5, NULL, 'uploads/school_content/material/media/thumb/', 'uploads/school_content/material/media/', 'Life of Faith Rhema Plan.docx', '1750233825-1730688908685272e13a4c0!Life of Faith Rhema Plan.docx', '', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '25687', '', '', 1, '2025-06-18 10:03:45', '2025-06-18 08:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `ipaddress` varchar(100) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `login_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `user`, `role`, `class_section_id`, `ipaddress`, `user_agent`, `login_datetime`) VALUES
(1, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.65', 'Chrome 136.0.0.0, Windows 10', '2025-05-10 12:18:58'),
(2, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.65', 'Chrome 136.0.0.0, Windows 10', '2025-05-10 12:22:54'),
(3, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.236', 'Chrome 136.0.0.0, Windows 10', '2025-05-12 05:28:25'),
(4, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.236', 'Chrome 136.0.0.0, Windows 10', '2025-05-12 09:30:21'),
(5, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.236', 'Chrome 136.0.0.0, Windows 10', '2025-05-12 10:26:57'),
(6, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.236', 'Chrome 136.0.0.0, Windows 10', '2025-05-12 10:35:49'),
(7, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.236', 'Chrome 136.0.0.0, Windows 10', '2025-05-12 11:50:10'),
(8, 'kuda@tenganimanje.com', 'Super Admin', NULL, '152.58.41.95', 'Chrome 136.0.0.0, Windows 10', '2025-05-13 10:18:15'),
(9, 'kuda@tenganimanje.com', 'Super Admin', NULL, '152.58.41.95', 'Chrome 136.0.0.0, Windows 10', '2025-05-13 10:26:41'),
(10, 'kuda@tenganimanje.com', 'Super Admin', NULL, '152.58.41.95', 'Chrome 136.0.0.0, Windows 10', '2025-05-13 10:41:39'),
(11, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 136.0.0.0, Windows 10', '2025-05-13 12:05:10'),
(12, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.250', 'Chrome 136.0.0.0, Windows 10', '2025-05-15 05:37:51'),
(13, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.199', 'Chrome 136.0.0.0, Windows 10', '2025-05-16 06:02:00'),
(14, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.47', 'Chrome 136.0.0.0, Windows 10', '2025-05-21 07:15:17'),
(15, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.47', 'Chrome 136.0.0.0, Windows 10', '2025-05-21 07:24:54'),
(16, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.44', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 06:37:56'),
(17, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.127', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 09:29:51'),
(18, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.127', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 09:50:18'),
(19, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.127', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 09:53:07'),
(20, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.127', 'Firefox 138.0, Windows 10', '2025-05-22 10:10:23'),
(21, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.71', 'Chrome 136.0.0.0, Android', '2025-05-22 11:17:38'),
(22, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.127', 'Firefox 138.0, Windows 10', '2025-05-22 11:43:13'),
(23, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.44', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 12:12:04'),
(24, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.44', 'Chrome 136.0.0.0, Windows 10', '2025-05-22 13:14:57'),
(25, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.71', 'Chrome 136.0.0.0, Android', '2025-05-22 14:58:07'),
(26, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.196', 'Chrome 136.0.0.0, Android', '2025-05-23 12:49:47'),
(27, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.235', 'Chrome 136.0.0.0, Android', '2025-05-23 18:58:34'),
(28, 'kuda@tenganimanje.com', 'Super Admin', NULL, '152.59.47.122', 'Firefox 138.0, Windows 10', '2025-05-24 08:04:12'),
(29, 'kuda@tenganimanje.com', 'Super Admin', NULL, '152.59.47.122', 'Firefox 138.0, Windows 10', '2025-05-24 08:04:52'),
(30, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.76', 'Chrome 136.0.0.0, Android', '2025-05-24 17:17:38'),
(31, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.69', 'Chrome 136.0.0.0, Android', '2025-05-25 15:02:58'),
(32, 'kuda@tenganimanje.com', 'Super Admin', NULL, '1.22.208.23', 'Firefox 138.0, Windows 10', '2025-05-26 04:35:48'),
(33, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.119.35', 'Chrome 136.0.0.0, Android', '2025-05-26 06:07:36'),
(34, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.119.35', 'Chrome 136.0.0.0, Windows 10', '2025-05-26 08:56:52'),
(35, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-27 07:50:47'),
(36, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-27 08:02:58'),
(37, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-27 08:05:06'),
(38, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-27 13:16:17'),
(39, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-27 13:46:38'),
(40, 'std2', 'student', 4, '216.234.213.50', 'Chrome 136.0.0.0, Android', '2025-05-27 14:08:19'),
(41, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.45', 'Chrome 136.0.0.0, Android', '2025-05-28 10:32:56'),
(42, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-29 08:33:48'),
(43, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.45', 'Chrome 136.0.0.0, Android', '2025-05-29 08:38:16'),
(44, 'std2', 'student', 4, '102.212.181.45', 'Chrome 136.0.0.0, Android', '2025-05-29 08:38:54'),
(45, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 136.0.0.0, Windows 10', '2025-05-29 13:14:56'),
(46, 'std1', 'student', 1, '216.234.213.50', 'Chrome 136.0.0.0, Android', '2025-05-29 14:45:34'),
(47, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.81', 'Chrome 136.0.0.0, Android', '2025-05-29 17:58:51'),
(48, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.81', 'Chrome 136.0.0.0, Android', '2025-05-29 18:56:15'),
(49, 'std1', 'student', 1, '102.212.182.81', 'Chrome 136.0.0.0, Android', '2025-05-29 18:57:21'),
(50, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.81', 'Chrome 136.0.0.0, Android', '2025-05-29 19:09:34'),
(51, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.25', 'Chrome 136.0.0.0, Windows 10', '2025-05-30 08:02:17'),
(52, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.117.73', 'Chrome 136.0.0.0, Android', '2025-05-31 17:03:02'),
(53, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.99', 'Chrome 136.0.0.0, Android', '2025-06-01 08:43:52'),
(54, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.32', 'Chrome 136.0.0.0, Windows 10', '2025-06-02 08:47:50'),
(55, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.107', 'Chrome 136.0.0.0, Windows 10', '2025-06-04 10:27:40'),
(56, 'std1', 'student', 1, '41.216.87.2', 'Chrome 136.0.0.0, Android', '2025-06-04 10:34:15'),
(57, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.140', 'Chrome 136.0.0.0, Windows 10', '2025-06-05 09:29:48'),
(58, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.140', 'Chrome 136.0.0.0, Windows 10', '2025-06-05 15:14:52'),
(59, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.124', 'Chrome 137.0.0.0, Windows 10', '2025-06-05 17:28:58'),
(60, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.87.13', 'Chrome 137.0.0.0, Android', '2025-06-07 19:23:00'),
(61, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.87.13', 'Chrome 137.0.0.0, Android', '2025-06-09 18:32:50'),
(62, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.215', 'Chrome 137.0.0.0, Windows 10', '2025-06-12 08:59:21'),
(63, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.60.184.226', 'Chrome 137.0.0.0, Windows 10', '2025-06-13 12:57:29'),
(64, 'kuda@tenganimanje.com', 'Super Admin', NULL, '98.97.160.174', 'Chrome 137.0.0.0, Windows 10', '2025-06-18 09:07:39'),
(65, 'std2', 'student', 3, '41.223.117.34', 'Chrome 137.0.0.0, Android', '2025-06-18 09:17:21'),
(66, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.232', 'Chrome 137.0.0.0, Windows 10', '2025-06-18 09:36:57'),
(67, 'std2', 'student', 3, '41.216.82.26', 'Chrome 137.0.0.0, Android', '2025-06-18 10:16:07'),
(68, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.153', 'Chrome 137.0.0.0, Windows 10', '2025-06-20 12:09:54'),
(69, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.86', 'Chrome 137.0.0.0, Android', '2025-06-22 12:05:27'),
(70, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.82.21', 'Chrome 137.0.0.0, Windows 10', '2025-06-25 08:52:40'),
(71, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.108', 'Chrome 137.0.0.0, Windows 10', '2025-06-26 13:50:11'),
(72, 'std1', 'student', 1, '216.234.213.108', 'Chrome 137.0.0.0, Android', '2025-06-26 14:23:33'),
(73, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.108', 'Chrome 137.0.0.0, Windows 10', '2025-06-26 16:35:27'),
(74, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.50', 'Chrome 137.0.0.0, Windows 10', '2025-06-27 14:53:48'),
(75, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-01 08:11:27'),
(76, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-01 15:19:45'),
(77, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.230', 'Chrome 138.0.0.0, Windows 10', '2025-07-01 17:25:51'),
(78, 'std2', 'student', 3, '41.216.95.230', 'Chrome 137.0.0.0, Android', '2025-07-01 18:01:53'),
(79, 'kuda@tenganimanje.com', 'Super Admin', NULL, '98.97.160.174', 'Chrome 138.0.0.0, Windows 10', '2025-07-02 09:23:07'),
(80, 'kuda@tenganimanje.com', 'Super Admin', NULL, '98.97.160.174', 'Chrome 138.0.0.0, Windows 10', '2025-07-02 09:26:33'),
(81, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.119.34', 'Chrome 137.0.0.0, Android', '2025-07-02 10:17:27'),
(82, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-03 14:25:17'),
(83, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-03 14:31:33'),
(84, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-03 14:32:44'),
(85, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.16', 'Chrome 137.0.0.0, Windows 10', '2025-07-04 11:42:52'),
(86, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.226', 'Chrome 137.0.0.0, Android', '2025-07-05 14:44:19'),
(87, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.226', 'Chrome 137.0.0.0, Android', '2025-07-06 06:53:37'),
(88, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.74', 'Chrome 138.0.0.0, Windows 10', '2025-07-15 07:44:24'),
(89, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.74', 'Chrome 138.0.0.0, Windows 10', '2025-07-15 13:16:04'),
(90, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.74', 'Chrome 138.0.0.0, Windows 10', '2025-07-15 13:19:25'),
(91, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.74', 'Chrome 138.0.0.0, Windows 10', '2025-07-15 13:22:46'),
(92, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.232', 'Chrome 138.0.0.0, Android', '2025-07-20 11:39:35'),
(93, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.116.252', 'Chrome 138.0.0.0, Android', '2025-07-24 21:09:13'),
(94, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.150.51.143', 'Chrome 138.0.0.0, Android', '2025-07-27 08:31:17'),
(95, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.150.51.143', 'Chrome 138.0.0.0, Android', '2025-07-27 08:42:24'),
(96, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.168', 'Chrome 138.0.0.0, Windows 10', '2025-07-28 14:28:31'),
(97, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.168', 'Chrome 138.0.0.0, Windows 10', '2025-07-28 14:30:28'),
(98, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.106', 'Chrome 138.0.0.0, Windows 10', '2025-07-28 16:29:59'),
(99, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.106', 'Chrome 138.0.0.0, Windows 10', '2025-07-28 17:20:01'),
(100, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.168', 'Chrome 138.0.0.0, Windows 10', '2025-07-29 11:39:14'),
(101, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.175.23.218', 'Chrome 138.0.0.0, Windows 10', '2025-07-30 10:51:02'),
(102, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.175.23.218', 'Chrome 138.0.0.0, Windows 10', '2025-07-30 11:33:41'),
(103, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.168', 'Chrome 138.0.0.0, Windows 10', '2025-08-01 08:52:39'),
(104, 'kuda@tenganimanje.com', 'Super Admin', NULL, '45.215.249.82', 'Chrome 138.0.0.0, Android', '2025-08-01 16:31:15'),
(105, 'kuda@tenganimanje.com', 'Super Admin', NULL, '45.215.249.82', 'Chrome 138.0.0.0, Android', '2025-08-01 16:31:19'),
(106, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.133', 'Chrome 138.0.0.0, Windows 10', '2025-08-05 11:33:51'),
(107, 'std2', 'student', 3, '45.215.255.225', 'Chrome 138.0.0.0, Android', '2025-08-05 16:54:48'),
(108, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.151.160.72', 'Chrome 138.0.0.0, Windows 10', '2025-08-05 17:58:19'),
(109, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.117.76', 'Chrome 138.0.0.0, Android', '2025-08-05 18:24:49'),
(110, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.82.26', 'Chrome 138.0.0.0, Windows 10', '2025-08-06 09:10:17'),
(111, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.12', 'Chrome 138.0.0.0, Windows 10', '2025-08-07 13:34:45'),
(112, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.82.26', 'Chrome 138.0.0.0, Android', '2025-08-07 17:13:43'),
(113, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.175.23.218', 'Chrome 138.0.0.0, Windows 10', '2025-08-08 09:42:50'),
(114, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.144.34.253', 'Chrome 138.0.0.0, Windows 10', '2025-08-09 03:02:15'),
(115, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.181.67', 'Chrome 138.0.0.0, Android', '2025-08-10 07:15:00'),
(116, 'kuda@tenganimanje.com', 'Super Admin', NULL, '45.215.255.174', 'Chrome 138.0.0.0, Android', '2025-08-10 19:49:03'),
(117, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.82.26', 'Chrome 138.0.0.0, Android', '2025-08-11 16:30:01'),
(118, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.240', 'Chrome 138.0.0.0, Windows 10', '2025-08-12 13:54:46'),
(119, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.196', 'Chrome 138.0.0.0, Windows 10', '2025-09-08 14:03:21'),
(120, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.223.116.250', 'Chrome 138.0.0.0, Windows 10', '2025-09-12 07:11:36'),
(121, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.91', 'Chrome 140.0.0.0, Windows 10', '2025-09-15 10:04:50'),
(122, 'kuda@tenganimanje.com', 'Super Admin', NULL, '49.43.34.30', 'Chrome 140.0.0.0, Mac OS X', '2025-09-15 12:36:14'),
(123, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.91', 'Chrome 140.0.0.0, Windows 10', '2025-09-15 13:35:08'),
(124, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.91', 'Chrome 140.0.0.0, Windows 10', '2025-09-15 14:14:35'),
(125, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.68', 'Chrome 140.0.0.0, Windows 10', '2025-09-18 10:11:16'),
(126, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.157', 'Chrome 140.0.0.0, Android', '2025-09-29 20:23:00'),
(127, 'kuda@tenganimanje.com', 'Super Admin', NULL, '102.212.182.157', 'Chrome 140.0.0.0, Android', '2025-09-29 20:24:06'),
(128, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.36.138', 'Chrome 140.0.0.0, Windows 10', '2025-09-29 20:26:13'),
(129, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-01 09:37:50'),
(130, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-01 11:38:29'),
(131, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-01 11:41:02'),
(132, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-01 19:03:37'),
(133, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-02 11:29:09'),
(134, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-06 10:56:12'),
(135, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 140.0.0.0, Windows 10', '2025-10-06 16:24:45'),
(136, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-10 08:14:55'),
(137, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-10 12:17:42'),
(138, 'std2', 'student', 3, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-10 12:19:14'),
(139, 'std2', 'student', 3, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-10 12:25:53'),
(140, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-10 12:29:06'),
(141, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-11 15:59:53'),
(142, 'std2', 'student', 3, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-11 17:35:52'),
(143, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-11 17:36:13'),
(144, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-11 17:40:40'),
(145, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-11 18:17:36'),
(146, 'kuda@tenganimanje.com', 'Super Admin', NULL, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-16 13:14:26'),
(147, 'std1', 'student', 1, '::1', 'Chrome 141.0.0.0, Windows 10', '2025-10-16 15:05:20'),
(0, 'std1', 'student', 1, '59.103.32.168', 'Chrome 141.0.0.0, Windows 10', '2025-10-24 15:54:01'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 141.0.0.0, Windows 10', '2025-10-24 15:59:28'),
(0, 'std1', 'student', 1, '59.103.32.168', 'Chrome 141.0.0.0, Windows 10', '2025-10-24 17:13:06'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 141.0.0.0, Windows 10', '2025-10-24 17:15:07'),
(0, 'std1', 'student', 1, '41.216.95.226', 'Chrome 141.0.0.0, Android', '2025-10-24 17:52:37'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.226', 'Chrome 141.0.0.0, Windows 10', '2025-10-25 04:44:41'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.95.226', 'Chrome 141.0.0.0, Windows 10', '2025-10-25 05:00:44'),
(0, 'std1', 'student', 1, '41.216.95.226', 'Chrome 141.0.0.0, Windows 10', '2025-10-25 05:04:52'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 07:44:58'),
(0, 'std1', 'student', 1, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 07:47:19'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 08:30:54'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 12:48:38'),
(0, 'std1', 'student', 1, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 15:44:09'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d7b:3710:ecb5:c9bd:1781:f4f5', 'Chrome 141.0.0.0, Windows 10', '2025-10-28 15:45:00'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '41.216.87.10', 'Chrome 141.0.0.0, Android', '2025-11-02 09:17:32'),
(0, 'std1', 'student', 1, '59.103.32.168', 'Chrome 142.0.0.0, Windows 10', '2025-11-02 14:43:24'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 09:17:21'),
(0, 'std1', 'student', 1, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 09:33:34'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 09:37:33'),
(0, 'std2', 'student', 3, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 09:46:31'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 10:23:33'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 142.0.0.0, Windows 10', '2025-11-03 14:52:09'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 142.0.0.0, Windows 10', '2025-11-03 15:06:52'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.27', 'Chrome 141.0.0.0, Android', '2025-11-03 15:21:35'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '216.234.213.27', 'Chrome 141.0.0.0, Android', '2025-11-03 15:23:53'),
(0, 'std1', 'student', 1, '216.234.213.27', 'Chrome 141.0.0.0, Android', '2025-11-03 15:25:00'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '2605:59c0:5d53:cf10:3937:de42:e3af:42d1', 'Chrome 141.0.0.0, Windows 10', '2025-11-03 16:07:01'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 142.0.0.0, Windows 10', '2025-11-03 16:56:49'),
(0, 'kuda@tenganimanje.com', 'Super Admin', NULL, '59.103.32.168', 'Chrome 142.0.0.0, Windows 10', '2025-11-03 17:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `childs` text NOT NULL,
  `role` varchar(30) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `currency_id` int(11) DEFAULT 0,
  `verification_code` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `username`, `password`, `childs`, `role`, `lang_id`, `currency_id`, `verification_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'std1', 'zj6a68', '', 'student', 4, 178, '', 'yes', '2025-05-26 09:32:28', '2025-10-24 15:14:04'),
(2, 0, 'parent1', '88dgua', '1', 'parent', 0, 0, '', 'yes', '2025-05-26 09:32:28', '2025-05-26 09:32:28'),
(3, 2, 'std2', '35rk87', '', 'student', 4, 0, '', 'yes', '2025-05-27 11:36:21', '2025-05-27 11:36:21'),
(4, 0, 'parent2', '4kml0d', '2', 'parent', 0, 0, '', 'yes', '2025-05-27 11:36:21', '2025-05-27 11:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `users_authentication`
--

CREATE TABLE `users_authentication` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `expired_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_google_authenticate_codes`
--

CREATE TABLE `user_google_authenticate_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `secret_code` varchar(100) DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_no` varchar(20) DEFAULT NULL,
  `vehicle_model` varchar(100) NOT NULL DEFAULT 'None',
  `vehicle_photo` varchar(255) DEFAULT NULL,
  `manufacture_year` varchar(4) DEFAULT NULL,
  `registration_number` varchar(50) NOT NULL,
  `chasis_number` varchar(100) NOT NULL,
  `max_seating_capacity` varchar(255) NOT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `driver_licence` varchar(50) NOT NULL DEFAULT 'None',
  `driver_contact` varchar(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_no`, `vehicle_model`, `vehicle_photo`, `manufacture_year`, `registration_number`, `chasis_number`, `max_seating_capacity`, `driver_name`, `driver_licence`, `driver_contact`, `note`, `created_at`, `updated_at`) VALUES
(1, '001', 'FORD', NULL, '2017', 'BAZ5907', '', '3', 'Kuda', '', '', '', '2025-05-30 06:11:34', '2025-05-30 06:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_routes`
--

CREATE TABLE `vehicle_routes` (
  `id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_tutorial`
--

CREATE TABLE `video_tutorial` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `vid_title` text DEFAULT NULL,
  `description` text NOT NULL,
  `thumb_path` varchar(500) DEFAULT NULL,
  `dir_path` varchar(500) DEFAULT NULL,
  `img_name` varchar(300) NOT NULL,
  `thumb_name` varchar(300) NOT NULL,
  `video_link` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_tutorial_class_sections`
--

CREATE TABLE `video_tutorial_class_sections` (
  `id` int(11) NOT NULL,
  `video_tutorial_id` int(11) NOT NULL,
  `class_section_id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors_book`
--

CREATE TABLE `visitors_book` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(12) NOT NULL,
  `id_proof` varchar(50) NOT NULL,
  `no_of_people` int(11) NOT NULL,
  `date` date NOT NULL,
  `in_time` varchar(20) NOT NULL,
  `out_time` varchar(20) NOT NULL,
  `note` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `meeting_with` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors_purpose`
--

CREATE TABLE `visitors_purpose` (
  `id` int(11) NOT NULL,
  `visitors_purpose` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_monthly_contributions`
-- (See below for the actual view)
--
CREATE TABLE `vw_monthly_contributions` (
`month` varchar(7)
,`contribution_count` bigint(21)
,`total_amount` decimal(32,2)
,`currency` varchar(10)
,`unique_partners` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `vw_partner_summary`
--

CREATE TABLE `vw_partner_summary` (
  `id` int(11) DEFAULT NULL,
  `partner_code` varchar(50) DEFAULT NULL,
  `account_type` enum('individual','organization') DEFAULT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `partner_name` varchar(201) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobileno` varchar(20) DEFAULT NULL,
  `status` enum('pending','active','inactive','suspended') DEFAULT NULL,
  `giving_type` varchar(100) DEFAULT NULL,
  `giving_frequency` varchar(100) DEFAULT NULL,
  `contribution_amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `total_contributions` bigint(21) DEFAULT NULL,
  `total_contributed` decimal(32,2) DEFAULT NULL,
  `last_contribution_date` date DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zoom_settings`
--

CREATE TABLE `zoom_settings` (
  `id` int(11) NOT NULL,
  `zoom_api_key` varchar(200) DEFAULT NULL,
  `zoom_api_secret` varchar(200) DEFAULT NULL,
  `use_teacher_api` int(11) DEFAULT 0,
  `use_zoom_app` int(11) DEFAULT 1,
  `use_zoom_app_user` int(11) DEFAULT 1,
  `parent_live_class` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partner_code` (`partner_code`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `giving_type_id` (`giving_type_id`),
  ADD KEY `giving_frequency_id` (`giving_frequency_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `status` (`status`),
  ADD KEY `email` (`email`),
  ADD KEY `mobileno` (`mobileno`),
  ADD KEY `idx_partner_status_active` (`status`,`is_active`),
  ADD KEY `idx_partner_created` (`created_at`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email_verified` (`is_email_verified`);

--
-- Indexes for table `partner_reminder_templates`
--
ALTER TABLE `partner_reminder_templates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `partner_reminder_templates`
--
ALTER TABLE `partner_reminder_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Structure for view `vw_monthly_contributions`
--
DROP TABLE IF EXISTS `vw_monthly_contributions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u226341643_ssdb1`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_monthly_contributions`  AS SELECT date_format(`pc`.`contribution_date`,'%Y-%m') AS `month`, count(0) AS `contribution_count`, sum(`pc`.`amount`) AS `total_amount`, `pc`.`currency` AS `currency`, count(distinct `pc`.`partner_id`) AS `unique_partners` FROM `partner_contributions` AS `pc` WHERE `pc`.`status` = 'completed' GROUP BY date_format(`pc`.`contribution_date`,'%Y-%m'), `pc`.`currency` ORDER BY date_format(`pc`.`contribution_date`,'%Y-%m') DESC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
