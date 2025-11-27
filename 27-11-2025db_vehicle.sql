-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 01:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_vehicle`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_code` varchar(255) NOT NULL,
  `department_short_name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `unit_id`, `department_name`, `department_code`, `department_short_name`, `location`, `description`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'IT', 'IT', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-11-01 10:55:38', '2025-11-01 10:55:38'),
(2, 1, 'HR', 'HR', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-11-01 10:55:50', '2025-11-01 10:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `nid` varchar(255) DEFAULT NULL,
  `employee_nid` varchar(255) DEFAULT NULL,
  `license_type` varchar(255) DEFAULT NULL,
  `license_issue_date` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `working_time_slot` varchar(255) DEFAULT NULL,
  `leave_status` tinyint(1) NOT NULL DEFAULT 0,
  `photograph` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `license_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `availability_status` enum('available','busy','Assigned','on_leave') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `company_id`, `department_id`, `unit_id`, `driver_name`, `license_number`, `nid`, `employee_nid`, `license_type`, `license_issue_date`, `date_of_birth`, `joining_date`, `present_address`, `permanent_address`, `mobile`, `working_time_slot`, `leave_status`, `photograph`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`, `license_type_id`, `availability_status`) VALUES
(1, NULL, 2, 1, 'Imran rahman', '155466', NULL, NULL, 'Crane License', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 1, NULL, NULL, '2025-11-02 09:15:12', '2025-11-26 09:23:06', 1, 'Assigned'),
(2, NULL, 1, 1, 'Driver 02', '1554666', NULL, NULL, 'Forklift License', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 1, NULL, NULL, '2025-11-02 10:14:44', '2025-11-26 09:22:30', 2, 'Assigned'),
(3, NULL, 1, 1, 'Atatur', '1554669', NULL, NULL, 'Aircraft License', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 1, NULL, NULL, '2025-11-26 11:06:52', '2025-11-27 11:57:42', 3, 'busy');

-- --------------------------------------------------------

--
-- Table structure for table `driver_documents`
--

CREATE TABLE `driver_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `status` enum('Pending','Sent','Failed') NOT NULL DEFAULT 'Pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_order` int(11) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `employee_type` enum('Permanent','Contract','Intern') DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `nid` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_order`, `company_id`, `unit_id`, `department_id`, `employee_code`, `name`, `email`, `phone`, `employee_type`, `designation`, `blood_group`, `nid`, `photo`, `present_address`, `permanent_address`, `join_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, 1, 2, '002085', 'Imran rahman', 'md.imran1200@gmail.com', '+8801918329829', 'Permanent', 'Ass Manager', 'B+', NULL, NULL, 'dhaka', 'sdffdfd', NULL, 'Active', '2025-11-01 10:56:48', '2025-11-01 10:56:48'),
(2, 0, NULL, 1, 1, '002086', 'Sorowar Bhai', 'sorowar@gmail.com', '+8801918329829', 'Permanent', 'Manager', 'AB+', '4637191737', NULL, 'dhaka', NULL, '2025-11-06', 'Active', '2025-11-18 09:03:22', '2025-11-18 09:03:22'),
(3, 0, NULL, 1, 1, '002099', 'Siddik', 'siddik@yahoo.com', '+8801918329829', 'Permanent', 'Executive', 'B+', '4637191737', NULL, 'Badda', 'Badda', '2025-10-29', 'Active', '2025-11-18 11:51:26', '2025-11-18 11:51:26'),
(4, 0, NULL, 1, 1, '002088', 'Atatur', 'ataur@yahoo.com', '+8801918329829', 'Permanent', 'Executive', 'B+', '4637191737', NULL, 'dhaka', NULL, '2025-11-18', 'Active', '2025-11-26 11:06:01', '2025-11-26 11:06:01'),
(5, 0, NULL, 1, 1, '002089', 'AbdusSattar', 'sattar@gmail.com', '+8801918329829', 'Permanent', 'Manager', 'AB-', '4637191737', NULL, 'dhaka', 'Postogola Bridge', '2025-11-07', 'Active', '2025-11-27 04:45:15', '2025-11-27 04:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `licnese_types`
--

CREATE TABLE `licnese_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `licnese_types`
--

INSERT INTO `licnese_types` (`id`, `type_name`, `description`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Crane License', NULL, 1, 1, NULL, NULL, '2025-11-01 10:16:08', '2025-11-01 10:16:08'),
(2, 'Forklift License', NULL, 1, 1, NULL, NULL, '2025-11-01 10:16:24', '2025-11-01 10:16:24'),
(3, 'Aircraft License', NULL, 1, 1, NULL, NULL, '2025-11-01 10:16:30', '2025-11-01 10:16:30');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_code` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Bangladesh',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `unit_id`, `location_name`, `location_code`, `address`, `city`, `district`, `country`, `latitude`, `longitude`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'UG Head Office', 'Head Office', 'Borak Mehnur Banani', NULL, NULL, 'Bangladesh', NULL, NULL, NULL, 1, 1, NULL, NULL, '2025-11-01 10:54:33', '2025-11-01 10:54:33');

-- --------------------------------------------------------

--
-- Table structure for table `log_histories`
--

CREATE TABLE `log_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_parent` int(11) NOT NULL DEFAULT 0,
  `menu_name` varchar(150) NOT NULL,
  `menu_slug` varchar(100) NOT NULL,
  `menu_type` varchar(100) DEFAULT NULL,
  `menu_location` varchar(100) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `menu_icon` varchar(250) DEFAULT NULL,
  `menu_url` varchar(250) DEFAULT NULL,
  `menu_permission` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_oder` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_parent`, `menu_name`, `menu_slug`, `menu_type`, `menu_location`, `remarks`, `menu_icon`, `menu_url`, `menu_permission`, `parent_id`, `menu_oder`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Dashboard', 'dashboard', 'backend', 'Header', NULL, 'fa-home', 'home', 'dashboard', NULL, 10, 1, 1, NULL, NULL, '2025-11-01 09:40:53', '2025-11-01 09:40:53'),
(2, 0, 'Settings', 'settings', 'backend', 'Header', NULL, 'fa-gear', 'settings', 'Settings', NULL, 11, 1, 1, NULL, NULL, '2025-11-01 09:41:47', '2025-11-01 09:41:47'),
(3, 0, 'Menu Builder', 'menu-builder', 'backend', 'Header', NULL, 'fa-link', NULL, 'menu-builder', NULL, 18, 1, 1, NULL, NULL, '2025-11-01 09:45:48', '2025-11-01 09:45:48'),
(4, 3, 'Add Menu', 'add-menu', 'backend', 'Header', NULL, 'fa-plus', 'menus.create', 'menu-create', NULL, 20, 1, 1, NULL, NULL, '2025-11-01 09:51:48', '2025-11-01 09:51:48'),
(5, 3, 'Menu List', 'menu-list', 'backend', 'Header', NULL, 'fa-list', 'menus.index', 'menu-index', NULL, 21, 1, 1, NULL, NULL, '2025-11-01 09:52:16', '2025-11-01 09:52:16'),
(6, 0, 'Employee Configure', 'employee-configure', 'backend', 'Header', NULL, 'fa-user', NULL, 'employee-managemant', NULL, 22, 1, 1, NULL, NULL, '2025-11-01 09:53:24', '2025-11-01 09:53:24'),
(7, 6, 'Employee Managemant', 'employee-managemant', 'backend', 'Header', NULL, 'fa-list', 'employees.index', 'employee-managemant', NULL, 24, 1, 1, NULL, NULL, '2025-11-01 09:54:06', '2025-11-01 09:54:06'),
(8, 6, 'Unit Manage', 'unit-manage', 'backend', 'Header', NULL, 'fa-list', 'units.index', '0', NULL, 25, 1, 1, NULL, NULL, '2025-11-01 09:54:55', '2025-11-01 09:54:55'),
(9, 6, 'Location Manage', 'location-manage', 'backend', 'Header', NULL, 'fa-lock', 'locations.index', '0', NULL, 26, 1, 1, NULL, NULL, '2025-11-01 09:55:33', '2025-11-01 09:55:33'),
(10, 6, 'Department Manage', 'department-manage', 'backend', 'Header', NULL, 'fa-user', 'departments.index', 'department-manage', NULL, 27, 1, 1, NULL, NULL, '2025-11-01 09:57:51', '2025-11-01 09:57:51'),
(11, 6, 'Driver Manage', 'driver-manage', 'backend', 'Header', NULL, 'fa-list', 'drivers.index', 'Driver Manage', NULL, 28, 1, 1, NULL, NULL, '2025-11-01 09:59:20', '2025-11-01 09:59:20'),
(12, 0, 'Role Manage', 'role-manage', 'backend', 'Header', NULL, 'fa-solid fa-wrench', NULL, 'Roles Manage', NULL, 19, 1, 1, NULL, NULL, '2025-11-01 10:02:05', '2025-11-01 10:02:05'),
(13, 12, 'Add Role', 'add-role', 'backend', 'Header', NULL, 'fa-plus', 'roles.create', '0', NULL, 16, 1, 1, NULL, NULL, '2025-11-01 10:05:48', '2025-11-01 10:05:48'),
(14, 12, 'Roles and Permission', 'roles-and-permission', 'backend', 'Header', NULL, 'fa-list', 'roles.index', '0', NULL, 17, 1, 1, NULL, NULL, '2025-11-01 10:06:22', '2025-11-01 10:06:22'),
(15, 0, 'Permission Manage', 'permission-manage', 'backend', 'Header', NULL, 'fa-lock', NULL, 'Permission Manage', NULL, 15, 1, 1, NULL, NULL, '2025-11-01 10:07:28', '2025-11-01 10:07:28'),
(16, 15, 'Add Permission', 'add-permission', 'backend', 'Header', NULL, 'fa-plus', 'permissions.create', '0', NULL, 13, 1, 1, NULL, NULL, '2025-11-01 10:09:11', '2025-11-01 10:09:11'),
(17, 15, 'Permission List', 'permission-list', 'backend', 'Header', NULL, 'fa-list', 'permissions.index', '0', NULL, 14, 1, 1, NULL, NULL, '2025-11-01 10:09:44', '2025-11-01 10:09:44'),
(18, 6, 'License Type Manage', 'license-type-manage', 'backend', 'Header', NULL, 'fa-star', 'license-types.index', 'license-type-manage', NULL, 9, 1, 1, NULL, NULL, '2025-11-01 10:15:54', '2025-11-01 10:24:39'),
(19, 0, 'User Manage', 'user-manage', 'backend', 'Header', NULL, 'fa-user', 'users.index', 'user-manage', NULL, 12, 1, 1, NULL, NULL, '2025-11-01 10:18:43', '2025-11-01 10:23:40'),
(20, 0, 'Vehicle Maintenance', 'vehicle-maintenance', 'backend', 'Header', NULL, 'fa-cog', NULL, 'vehicle-maintanence', NULL, 23, 1, 1, NULL, NULL, '2025-11-01 10:25:29', '2025-11-01 10:25:29'),
(21, 0, 'Vehicle Managemant', 'vehicle-managemant', 'backend', 'Header', NULL, 'fa-car', NULL, 'vehicle-managemant', NULL, 29, 1, 1, NULL, NULL, '2025-11-01 10:26:57', '2025-11-01 10:26:57'),
(22, 0, 'Vehicle Requisition', 'vehicle-requisition', 'backend', 'Header', NULL, 'fa-file', NULL, 'vehicle-requization', NULL, 30, 1, 1, NULL, NULL, '2025-11-01 10:31:16', '2025-11-01 10:32:22'),
(23, 21, 'Add Vehicle', 'add-vehicle', 'backend', 'Header', NULL, 'fa-cog', 'vehicles.create', '0', NULL, 3, 1, 1, NULL, NULL, '2025-11-02 11:20:10', '2025-11-02 11:20:10'),
(24, 21, 'Vehicle List', 'vehicle-list', 'backend', 'Header', NULL, 'fa-list', 'vehicles.index', '0', NULL, 4, 1, 1, NULL, NULL, '2025-11-02 11:20:57', '2025-11-02 11:20:57'),
(25, 21, 'Vendor Manage', 'vendor-manage', 'backend', 'Header', NULL, 'fa-gear', 'vendors.index', 'vendor-setup', NULL, 5, 1, 1, NULL, NULL, '2025-11-09 10:04:25', '2025-11-09 10:04:25'),
(26, 21, 'Vehicle Type Manage', 'vehicle-type-manage', 'backend', 'Header', NULL, 'fa-car', 'vehicle-type.index', 'vehicle-type-manage', NULL, 6, 1, 1, NULL, NULL, '2025-11-09 11:22:55', '2025-11-09 11:22:55'),
(27, 22, 'Add Requisition', 'add-requisition', 'backend', 'Header', NULL, 'fa-plus', 'requisitions.create', '0', NULL, 7, 1, 1, NULL, NULL, '2025-11-12 03:52:35', '2025-11-12 03:52:35'),
(28, 22, 'Requisition List', 'requisition-list', 'backend', 'Header', NULL, 'fa-list', 'requisitions.index', '0', NULL, 8, 1, 1, NULL, NULL, '2025-11-16 10:33:09', '2025-11-16 10:33:09'),
(29, 0, 'Approvals', 'approvals', 'backend', 'Header', NULL, 'fa-lock', NULL, 'approvals', NULL, 31, 1, 1, NULL, NULL, '2025-11-23 11:24:01', '2025-11-23 11:24:01'),
(30, 29, 'Department Head Approval', 'department-head-approval', 'backend', 'Header', NULL, 'fa-list', 'department.approvals.index', '0', NULL, 1, 1, 1, NULL, NULL, '2025-11-23 11:26:44', '2025-11-23 11:26:44'),
(31, 29, 'Transport Approvals', 'transport-approvals', 'backend', 'Header', NULL, 'fa-list', 'transport.approvals.index', '0', NULL, 2, 1, 1, NULL, NULL, '2025-11-25 10:11:04', '2025-11-25 10:11:04'),
(32, 0, 'Trip Sheet', 'trip-sheet', 'backend', 'Header', NULL, 'fa-list', 'trip-sheets.index', 'department-manage', NULL, 32, 1, 1, NULL, NULL, '2025-11-27 11:02:44', '2025-11-27 11:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_02_05_044607_create_permission_tables', 1),
(7, '2022_02_26_053944_create_galleries_table', 1),
(8, '2022_02_26_054246_create_services_table', 1),
(9, '2022_02_26_054323_create_pages_table', 1),
(10, '2022_02_26_054357_create_posts_table', 1),
(12, '2022_03_01_044037_create_categories_column_table', 1),
(13, '2022_03_01_070934_create_sliders_table', 1),
(14, '2022_10_17_051821_create_frontends_table', 1),
(15, '2022_10_19_050519_create_offers_table', 1),
(16, '2022_11_12_065523_create_events_table', 1),
(17, '2022_11_17_052635_create_contacts_table', 1),
(18, '2023_01_14_044427_create_units_table', 1),
(19, '2023_01_14_044719_create_projects_table', 1),
(20, '2023_01_14_044801_create_support_types_table', 1),
(21, '2023_01_14_044815_create_issue_types_table', 1),
(22, '2023_01_14_044833_create_solved_processes_table', 1),
(23, '2023_01_14_044944_create_purchases_table', 1),
(24, '2023_01_18_031644_create_supports_table', 1),
(25, '2023_03_04_075336_create_issueregister_logs_table', 1),
(26, '2023_03_06_051535_create_supportdetails_table', 1),
(27, '2023_03_09_032410_create_assigntasks_table', 1),
(28, '2023_03_16_111948_add_fcm_token_column_to_users_table', 1),
(29, '2023_03_29_043120_create_servicedetails_table', 1),
(30, '2024_03_15_add_approval_fields_to_documents', 1),
(31, '2024_03_16_create_jobs_table', 1),
(32, '2025_02_20_111002_create_vlands_table', 1),
(33, '2025_02_24_125304_create_notifications_table', 1),
(34, '2025_02_24_145232_create_jobs_table', 2),
(41, '2025_10_21_155417_create_departments_table', 3),
(42, '2025_10_22_144933_create_drivers_table', 4),
(44, '2025_10_22_154006_create_driver_documents_table', 5),
(45, '2025_10_22_154646_create_vehicle_maintenences_table', 5),
(46, '2025_10_22_155226_create_transport_notifications_table', 6),
(47, '2025_10_22_155445_create_log_histories_table', 7),
(48, '2025_10_22_161111_create_locations_table', 7),
(49, '2025_10_22_163307_create_licnese_types_table', 7),
(51, '2025_10_22_163711_create_requisition_passengers_table', 9),
(52, '2025_10_22_163813_create_requisition_approvals_table', 9),
(54, '2025_10_22_164213_create_email_logs_table', 9),
(55, '2025_10_22_164621_create_companies_table', 9),
(56, '2025_10_22_172446_create_vehicle_types_table', 9),
(57, '2025_10_26_102909_create_travel_requests_table', 9),
(58, '2025_10_26_103700_create_travel_request_logs_table', 9),
(59, '2025_10_26_112450_create_employees_table', 9),
(60, '2025_10_26_122807_add_employee_order_to_employees_table', 9),
(61, '2025_11_01_000001_add_license_type_id_to_drivers_table', 9),
(62, '2025_11_01_000002_add_user_fk_to_transport_notifications_table', 9),
(63, '2025_11_01_000003_add_user_fk_to_log_histories_table', 9),
(64, '2025_11_01_000004_add_requested_by_fk_to_requisitions_table', 9),
(65, '2025_11_01_000005_add_employee_fks_to_requisition_tables', 10),
(66, '2014_10_12_000000_create_users_table', 11),
(68, '2022_02_26_054411_create_settings_table', 13),
(70, '2022_02_06_062951_create_menus_table', 14),
(71, '2025_11_02_164132_create_vehicle_managemants_table', 15),
(72, '2025_11_09_152634_create_vendors_table', 15),
(73, '2025_10_22_150946_create_vehicles_table', 16),
(74, '2025_11_15_163607_add_missing_fields_to_requisitions_table', 17),
(75, '2025_11_18_115948_create_workflow_logs_table', 18),
(76, '2025_11_18_121922_add_role_to_users_table', 19),
(77, '2025_10_22_163859_create_requisition_loghistories_table', 20),
(80, '2025_10_22_163513_create_requisitions_table', 22),
(81, '2025_11_20_103302_add_columns_to_requisitions_table', 23),
(82, '2025_11_23_174110_add_number_of_passenger_to_requisitions_table', 24),
(83, '2025_11_25_161515_add_status__to_requisitions_table', 25),
(84, '2025_11_26_125445_add_availability_status__to_vehicles_table', 26),
(85, '2025_11_26_125455_add_availability_status__to_drivers_table', 26),
(86, '2025_11_27_165050_create_trip_sheets_table', 27);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `key`, `table_name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', NULL, NULL, 'web', '2025-11-01 08:37:46', '2025-11-01 08:37:46'),
(3, 'Settings', NULL, NULL, 'web', '2025-11-01 09:41:27', '2025-11-01 09:41:27'),
(4, 'employee-managemant', NULL, 'employees', 'web', '2025-11-01 09:44:34', '2025-11-01 09:44:34'),
(5, 'menu-builder', NULL, 'menus', 'web', '2025-11-01 09:45:09', '2025-11-01 09:45:09'),
(6, 'menu-create', NULL, 'menus', 'web', '2025-11-01 09:50:53', '2025-11-01 09:50:53'),
(7, 'menu-index', NULL, NULL, 'web', '2025-11-01 09:51:01', '2025-11-01 09:51:01'),
(8, 'menu-edit', NULL, NULL, 'web', '2025-11-01 09:51:09', '2025-11-01 09:51:09'),
(9, 'menu-delete', NULL, NULL, 'web', '2025-11-01 09:51:15', '2025-11-01 09:51:15'),
(10, 'department-manage', NULL, 'departments', 'web', '2025-11-01 09:57:20', '2025-11-01 09:57:20'),
(11, 'Driver Manage', NULL, 'drivers', 'web', '2025-11-01 09:58:53', '2025-11-01 09:58:53'),
(12, 'Roles Manage', NULL, 'roles', 'web', '2025-11-01 10:00:32', '2025-11-01 10:00:32'),
(14, 'Permission Manage', NULL, 'permissions', 'web', '2025-11-01 10:06:46', '2025-11-01 10:06:46'),
(15, 'license-type-manage', NULL, NULL, 'web', '2025-11-01 10:14:59', '2025-11-01 10:14:59'),
(16, 'vehicle-maintanence', NULL, NULL, 'web', '2025-11-01 10:17:59', '2025-11-01 10:17:59'),
(17, 'user-manage', NULL, NULL, 'web', '2025-11-01 10:18:13', '2025-11-01 10:18:13'),
(18, 'vehicle-managemant', NULL, NULL, 'web', '2025-11-01 10:26:35', '2025-11-01 10:26:35'),
(19, 'vehicle-requization', NULL, NULL, 'web', '2025-11-01 10:30:26', '2025-11-01 10:30:26'),
(20, 'unit-create', NULL, 'units', 'web', '2025-11-01 10:50:27', '2025-11-01 10:50:27'),
(21, 'unit-edit', NULL, NULL, 'web', '2025-11-01 10:50:34', '2025-11-01 10:50:34'),
(22, 'unit-delete', NULL, NULL, 'web', '2025-11-01 10:50:40', '2025-11-01 10:50:40'),
(23, 'department-create', NULL, 'departments', 'web', '2025-11-01 10:55:14', '2025-11-01 10:55:14'),
(24, 'employee-create', NULL, 'employees', 'web', '2025-11-01 10:56:08', '2025-11-01 10:56:08'),
(25, 'vendor-setup', NULL, NULL, 'web', '2025-11-09 10:03:48', '2025-11-09 10:03:48'),
(26, 'vehicle-type-manage', NULL, NULL, 'web', '2025-11-09 11:21:50', '2025-11-09 11:21:50'),
(27, 'approvals', NULL, NULL, 'web', '2025-11-23 11:23:19', '2025-11-23 11:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requisition_number` varchar(255) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by_department` bigint(20) UNSIGNED DEFAULT NULL,
  `department_approved_at` timestamp NULL DEFAULT NULL,
  `transport_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transport_approved_at` timestamp NULL DEFAULT NULL,
  `vehicle_type` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number_of_passenger` int(11) NOT NULL,
  `from_location` varchar(255) NOT NULL,
  `to_location` varchar(255) NOT NULL,
  `travel_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `requisition_date` date DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `status_flag` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_status` enum('Pending','Assigned','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `transport_status` enum('Pending','Assigned','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `assigned_vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transport_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`id`, `department_id`, `unit_id`, `description`, `requisition_number`, `requested_by`, `approved_by_department`, `department_approved_at`, `transport_admin_id`, `transport_approved_at`, `vehicle_type`, `vehicle_id`, `driver_id`, `number_of_passenger`, `from_location`, `to_location`, `travel_date`, `return_date`, `requisition_date`, `purpose`, `status`, `status_flag`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`, `department_status`, `transport_status`, `assigned_vehicle_id`, `assigned_driver_id`, `transport_remarks`) VALUES
(11, 1, '1', NULL, 'REQ-00000', 4, NULL, NULL, 1, '2025-11-27 04:11:48', 9, NULL, NULL, 8, 'Head Office', 'Gulshan Clinic', '2025-11-26 21:08:00', '2025-11-26 17:13:00', '2025-11-11', 'Software Visit', 'Pending', 1, 1, NULL, NULL, '2025-11-26 11:09:27', '2025-11-27 04:11:48', 'Approved', 'Approved', 3, 3, 'Approved'),
(15, 1, '1', NULL, 'REQ-000-2', 5, NULL, NULL, 1, '2025-11-27 09:56:18', NULL, NULL, NULL, 4, 'Head Office', 'Patira', '2025-11-27 00:00:00', '2025-11-27 10:49:00', '2025-11-26', 'To Visit SOftware Training', 'Pending', 1, 1, NULL, NULL, '2025-11-27 04:54:16', '2025-11-27 09:56:18', 'Approved', 'Approved', 4, 3, 'Approved'),
(16, 2, '1', NULL, 'REQ-00001', 1, NULL, NULL, 1, '2025-11-27 11:57:42', 7, NULL, NULL, 4, 'Head Office', 'Gulshan Clinic', '2025-11-27 21:58:00', '2025-11-27 20:58:00', '2025-11-27', 'To Visit Software Setup and Network Setup', 'Pending', 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 11:57:42', 'Approved', 'Approved', 4, 3, 'sdsd');

-- --------------------------------------------------------

--
-- Table structure for table `requisition_loghistories`
--

CREATE TABLE `requisition_loghistories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL,
  `action_by` bigint(20) UNSIGNED NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `remarks` text DEFAULT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisition_passengers`
--

CREATE TABLE `requisition_passengers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requisition_passengers`
--

INSERT INTO `requisition_passengers` (`id`, `requisition_id`, `employee_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(6, 4, 2, 1, 1, NULL, '2025-11-20 11:01:25', '2025-11-18 11:36:47', '2025-11-20 11:01:25'),
(7, 5, 1, 1, 1, NULL, '2025-11-20 09:59:36', '2025-11-18 11:52:50', '2025-11-20 09:59:36'),
(8, 5, 2, 1, 1, NULL, '2025-11-20 09:59:36', '2025-11-18 11:52:50', '2025-11-20 09:59:36'),
(9, 5, 1, 1, 1, NULL, '2025-11-20 11:00:45', '2025-11-20 10:16:55', '2025-11-20 11:00:45'),
(10, 5, 1, 1, 1, NULL, NULL, '2025-11-20 11:00:45', '2025-11-20 11:00:45'),
(11, 4, 2, 1, 1, NULL, NULL, '2025-11-20 11:01:25', '2025-11-20 11:01:25'),
(28, 11, 1, 1, 1, NULL, NULL, '2025-11-26 11:09:27', '2025-11-26 11:09:27'),
(29, 11, 2, 1, 1, NULL, NULL, '2025-11-26 11:09:27', '2025-11-26 11:09:27'),
(38, 15, 1, 1, 1, NULL, '2025-11-27 05:43:10', '2025-11-27 04:54:16', '2025-11-27 05:43:10'),
(39, 15, 2, 1, 1, NULL, '2025-11-27 05:43:10', '2025-11-27 04:54:16', '2025-11-27 05:43:10'),
(40, 15, 3, 1, 1, NULL, '2025-11-27 05:43:10', '2025-11-27 04:54:16', '2025-11-27 05:43:10'),
(41, 15, 5, 1, 1, NULL, '2025-11-27 05:43:10', '2025-11-27 04:54:16', '2025-11-27 05:43:10'),
(42, 15, 1, 1, 1, NULL, NULL, '2025-11-27 05:43:10', '2025-11-27 05:43:10'),
(43, 15, 2, 1, 1, NULL, NULL, '2025-11-27 05:43:10', '2025-11-27 05:43:10'),
(44, 15, 3, 1, 1, NULL, NULL, '2025-11-27 05:43:10', '2025-11-27 05:43:10'),
(45, 15, 5, 1, 1, NULL, NULL, '2025-11-27 05:43:11', '2025-11-27 05:43:11'),
(46, 16, 1, 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 09:59:00'),
(47, 16, 2, 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 09:59:00'),
(48, 16, 3, 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 09:59:00'),
(49, 16, 4, 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 09:59:00'),
(50, 16, 5, 1, 1, NULL, NULL, '2025-11-27 09:59:00', '2025-11-27 09:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-11-01 08:39:27', '2025-11-01 08:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_title` varchar(250) DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `admin_title` varchar(250) DEFAULT NULL,
  `admin_description` text DEFAULT NULL,
  `site_logo` varchar(255) DEFAULT NULL,
  `site_copyright_text` varchar(255) DEFAULT NULL,
  `admin_logo` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `site_description`, `admin_title`, `admin_description`, `site_logo`, `site_copyright_text`, `admin_logo`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'VMS', 'VMS', 'VMS - Vehicle System', 'VMS', NULL, NULL, '1761990255.png', 1, 1, NULL, NULL, NULL, '2025-11-01 09:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `transport_notifications`
--

CREATE TABLE `transport_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'system',
  `channel` varchar(255) DEFAULT NULL,
  `reference_table` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_requests`
--

CREATE TABLE `travel_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_request_logs`
--

CREATE TABLE `travel_request_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_sheets`
--

CREATE TABLE `trip_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `trip_start_time` datetime DEFAULT NULL,
  `trip_end_time` datetime DEFAULT NULL,
  `start_location` varchar(255) DEFAULT NULL,
  `end_location` varchar(255) DEFAULT NULL,
  `start_km` int(11) DEFAULT NULL,
  `end_km` int(11) DEFAULT NULL,
  `total_km` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_name` varchar(255) NOT NULL,
  `unit_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `location_id`, `department_id`, `unit_name`, `unit_code`, `description`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 2, 'Head Office', 'UG', NULL, 1, 1, NULL, NULL, '2025-11-01 10:54:16', '2025-11-01 10:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'employee',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$10$FiBdxzOiAhJezBgz/GjoyewByzxfndl2o9MV5SGPK/DrghlZjsrW.', 'employee', NULL, '2025-11-01 09:10:04', '2025-11-01 09:35:24'),
(2, 'Transport', 'transport@example.com', NULL, '$2y$10$Zkou5MkfAnDxLXHNIrX4Be.ZVU269wwNisyEz1shvfh1tF.22dvtS', 'employee', NULL, '2025-11-18 06:20:34', '2025-11-18 06:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `license_plate` varchar(50) NOT NULL,
  `alert_cell_number` varchar(20) DEFAULT NULL,
  `ownership` enum('Owned','Rented','Leased') DEFAULT NULL,
  `vehicle_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seat_capacity` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `availability_status` enum('available','busy','Assigned','on_leave') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_name`, `department_id`, `registration_date`, `license_plate`, `alert_cell_number`, `ownership`, `vehicle_type_id`, `driver_id`, `vendor_id`, `seat_capacity`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`, `availability_status`) VALUES
(1, 'Mazda-88814', 1, '2025-11-11', 'dfdfdf', '1165464', 'Owned', 7, 1, 1, 2, 1, 1, NULL, NULL, '2025-11-10 11:23:33', '2025-11-26 09:23:06', 'Assigned'),
(2, 'Toyota Corolla', 1, '2025-11-11', '6565989', NULL, NULL, 7, 2, 1, 6, 1, 1, NULL, NULL, '2025-11-18 09:05:18', '2025-11-26 09:22:30', 'Assigned'),
(3, 'Cadillac-95769', 1, '2023-02-01', '595985', NULL, NULL, 7, 2, 1, 6, 1, 1, NULL, NULL, '2025-11-26 10:59:08', '2025-11-27 04:11:48', 'busy'),
(4, 'Lexus-49180', 1, '2025-11-04', 'sdsds566589', NULL, NULL, 9, 3, 1, 9, 1, 1, NULL, NULL, '2025-11-26 11:07:56', '2025-11-27 11:57:42', 'busy');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_maintenences`
--

CREATE TABLE `vehicle_maintenences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_date` date NOT NULL,
  `maintenance_type` varchar(255) NOT NULL,
  `service_provider` varchar(255) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_managemants`
--

CREATE TABLE `vehicle_managemants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `name`, `description`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Car', 'Car', 1, 1, NULL, '2025-11-10 10:09:44', '2025-11-10 08:35:33', '2025-11-10 10:09:44'),
(2, 'Car', 'Car', 1, 1, NULL, '2025-11-10 10:10:12', '2025-11-10 08:35:41', '2025-11-10 10:10:12'),
(3, 'Car', 'Car', 1, 1, NULL, '2025-11-10 10:10:32', '2025-11-10 08:36:55', '2025-11-10 10:10:32'),
(4, 'Car', 'Car', 1, 1, NULL, '2025-11-10 10:10:59', '2025-11-10 08:38:17', '2025-11-10 10:10:59'),
(5, 'sdsd', NULL, 1, 1, NULL, '2025-11-10 10:11:21', '2025-11-10 09:51:08', '2025-11-10 10:11:21'),
(6, 'Admin', NULL, 1, 1, NULL, '2025-11-10 10:11:17', '2025-11-10 10:11:08', '2025-11-10 10:11:17'),
(7, 'Car', 'Car', 1, 1, NULL, NULL, '2025-11-10 10:11:32', '2025-11-10 10:11:32'),
(8, 'Bike', 'Bike', 1, 1, NULL, NULL, '2025-11-10 10:11:42', '2025-11-10 10:11:42'),
(9, 'Suv/Microbus', 'Suv/Microbus', 1, 1, NULL, NULL, '2025-11-10 10:11:53', '2025-11-10 10:11:53'),
(10, 'Toyota Corolla', NULL, 1, 1, NULL, '2025-11-18 09:05:42', '2025-11-18 09:04:13', '2025-11-18 09:05:42'),
(11, 'Track', NULL, 1, 1, NULL, NULL, '2025-11-23 09:06:55', '2025-11-23 09:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `contact_person`, `contact_number`, `email`, `address`, `city`, `country`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Uttara Motor', 'Imran rahman', '01918329829', 'md.imran1200@gmail.com', 'dhaka', 'Dhaka, Bangladesh', 'Bangladesh', 'Active', '2025-11-09 10:12:14', '2025-11-09 10:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_logs`
--

CREATE TABLE `workflow_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` bigint(20) UNSIGNED NOT NULL,
  `changed_by` bigint(20) UNSIGNED NOT NULL,
  `old_status` tinyint(4) DEFAULT NULL,
  `new_status` tinyint(4) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_department_code_unique` (`department_code`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_license_number_unique` (`license_number`),
  ADD KEY `drivers_license_type_id_foreign` (`license_type_id`);

--
-- Indexes for table `driver_documents`
--
ALTER TABLE `driver_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_documents_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_logs_requisition_id_foreign` (`requisition_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD KEY `employees_company_id_foreign` (`company_id`),
  ADD KEY `employees_unit_id_foreign` (`unit_id`),
  ADD KEY `employees_department_id_foreign` (`department_id`);

--
-- Indexes for table `licnese_types`
--
ALTER TABLE `licnese_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_location_code_unique` (`location_code`),
  ADD KEY `locations_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `log_histories`
--
ALTER TABLE `log_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requisitions_requisition_number_unique` (`requisition_number`);

--
-- Indexes for table `requisition_loghistories`
--
ALTER TABLE `requisition_loghistories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_loghistories_requisition_id_foreign` (`requisition_id`);

--
-- Indexes for table `requisition_passengers`
--
ALTER TABLE `requisition_passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisition_passengers_requisition_id_foreign` (`requisition_id`),
  ADD KEY `requisition_passengers_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_id_index` (`id`);

--
-- Indexes for table `transport_notifications`
--
ALTER TABLE `transport_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport_notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `travel_requests`
--
ALTER TABLE `travel_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `travel_request_logs`
--
ALTER TABLE `travel_request_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_sheets`
--
ALTER TABLE `trip_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_license_plate_unique` (`license_plate`),
  ADD KEY `vehicles_department_id_foreign` (`department_id`),
  ADD KEY `vehicles_vehicle_type_id_foreign` (`vehicle_type_id`),
  ADD KEY `vehicles_driver_id_foreign` (`driver_id`),
  ADD KEY `vehicles_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vehicle_maintenences`
--
ALTER TABLE `vehicle_maintenences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_maintenences_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `vehicle_managemants`
--
ALTER TABLE `vehicle_managemants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_logs`
--
ALTER TABLE `workflow_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workflow_logs_requisition_id_foreign` (`requisition_id`),
  ADD KEY `workflow_logs_changed_by_foreign` (`changed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driver_documents`
--
ALTER TABLE `driver_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `licnese_types`
--
ALTER TABLE `licnese_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_histories`
--
ALTER TABLE `log_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `requisition_loghistories`
--
ALTER TABLE `requisition_loghistories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requisition_passengers`
--
ALTER TABLE `requisition_passengers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transport_notifications`
--
ALTER TABLE `transport_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_requests`
--
ALTER TABLE `travel_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_request_logs`
--
ALTER TABLE `travel_request_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_sheets`
--
ALTER TABLE `trip_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicle_maintenences`
--
ALTER TABLE `vehicle_maintenences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_managemants`
--
ALTER TABLE `vehicle_managemants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workflow_logs`
--
ALTER TABLE `workflow_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_license_type_id_foreign` FOREIGN KEY (`license_type_id`) REFERENCES `licnese_types` (`id`);

--
-- Constraints for table `driver_documents`
--
ALTER TABLE `driver_documents`
  ADD CONSTRAINT `driver_documents_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD CONSTRAINT `email_logs_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_histories`
--
ALTER TABLE `log_histories`
  ADD CONSTRAINT `log_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requisition_loghistories`
--
ALTER TABLE `requisition_loghistories`
  ADD CONSTRAINT `requisition_loghistories_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requisition_passengers`
--
ALTER TABLE `requisition_passengers`
  ADD CONSTRAINT `requisition_passengers_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requisition_passengers_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transport_notifications`
--
ALTER TABLE `transport_notifications`
  ADD CONSTRAINT `transport_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `vehicles_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `vehicles_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`),
  ADD CONSTRAINT `vehicles_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vehicle_maintenences`
--
ALTER TABLE `vehicle_maintenences`
  ADD CONSTRAINT `vehicle_maintenences_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workflow_logs`
--
ALTER TABLE `workflow_logs`
  ADD CONSTRAINT `workflow_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `workflow_logs_requisition_id_foreign` FOREIGN KEY (`requisition_id`) REFERENCES `requisitions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
