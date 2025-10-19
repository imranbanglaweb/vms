-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2023 at 12:32 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `category_name` varchar(150) NOT NULL,
  `category_slug` varchar(100) NOT NULL,
  `category_type` varchar(100) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `category_url` varchar(250) DEFAULT NULL,
  `category_oder` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `category_name`, `category_slug`, `category_type`, `remarks`, `category_url`, `category_oder`, `menu_id`, `page_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Restaurant', 'restaurant', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:39:07', '2022-12-31 22:39:07'),
(2, 0, 'Facility', 'facility', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:45:39', '2022-12-31 22:45:39'),
(3, 0, 'Room', 'room', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:45:54', '2022-12-31 22:45:54'),
(4, 0, 'SWIMMING POOL', 'swimming-pool', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:45:58', '2022-12-31 22:45:58');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_name` varchar(400) NOT NULL,
  `contact_mobile` int(11) DEFAULT NULL,
  `contact_email` text DEFAULT NULL,
  `contact_content` text DEFAULT NULL,
  `ip_address` int(11) DEFAULT NULL,
  `mac_address` int(11) DEFAULT NULL,
  `event_oder` int(11) NOT NULL DEFAULT 1,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `contact_name`, `contact_mobile`, `contact_email`, `contact_content`, `ip_address`, `mac_address`, `event_oder`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 'Imran', 1708800933, 'md.imran1200@gmail.com', 'hi how are you', NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 02:45:45', '2023-01-01 02:45:45'),
(5, 'Imran', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 03:08:58', '2023-01-01 03:08:58'),
(6, 'R', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 03:12:33', '2023-01-01 03:12:33'),
(7, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:05:33', '2023-01-01 04:05:33'),
(8, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:06:29', '2023-01-01 04:06:29'),
(9, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:06:55', '2023-01-01 04:06:55'),
(10, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:07:39', '2023-01-01 04:07:39'),
(11, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:08:24', '2023-01-01 04:08:24'),
(12, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:14:32', '2023-01-01 04:14:32'),
(13, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:15:12', '2023-01-01 04:15:12'),
(14, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:21:49', '2023-01-01 04:21:49'),
(15, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:22:08', '2023-01-01 04:22:08'),
(16, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:22:20', '2023-01-01 04:22:20'),
(17, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:22:31', '2023-01-01 04:22:31'),
(18, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:24:41', '2023-01-01 04:24:41'),
(19, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:25:19', '2023-01-01 04:25:19'),
(20, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:26:16', '2023-01-01 04:26:16'),
(21, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:27:41', '2023-01-01 04:27:41'),
(22, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:28:20', '2023-01-01 04:28:20'),
(23, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:28:43', '2023-01-01 04:28:43'),
(24, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:29:38', '2023-01-01 04:29:38'),
(25, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:33:10', '2023-01-01 04:33:10'),
(26, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:34:15', '2023-01-01 04:34:15'),
(27, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:35:00', '2023-01-01 04:35:00'),
(28, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:37:23', '2023-01-01 04:37:23'),
(29, 'dfgdfdf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:44:39', '2023-01-01 04:44:39'),
(30, 'Yfgfgfgf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:45:29', '2023-01-01 04:45:29'),
(31, 'Yfgfgfgf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:48:09', '2023-01-01 04:48:09'),
(32, 'Yfgfgfgf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:48:16', '2023-01-01 04:48:16'),
(33, 'Yfgfgfgf', 1708800933, 'md.imran1200@gmail.com', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 04:56:48', '2023-01-01 04:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `dinningimages`
--

CREATE TABLE `dinningimages` (
  `id` int(10) UNSIGNED NOT NULL,
  `dinning_id` varchar(300) NOT NULL,
  `dinning_image` text DEFAULT NULL,
  `dinningimages_oder` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinningimages`
--

INSERT INTO `dinningimages` (`id`, `dinning_id`, `dinning_image`, `dinningimages_oder`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1672551636-hotel-near-airport-dhaka.jpg', 1, 1, 1, NULL, NULL, '2022-12-31 23:40:36', '2022-12-31 23:40:36'),
(2, '2', '1672551725-hansa-h-caffe.jpg', 1, 1, 1, NULL, NULL, '2022-12-31 23:42:05', '2022-12-31 23:42:05'),
(3, '3', '1672553625-hotel-near-airport-dhaka.jpg', 1, 1, 1, NULL, NULL, '2023-01-01 00:13:45', '2023-01-01 00:13:45'),
(4, '4', '1672553679-hotel-near-airport-dhaka.jpg', 1, 1, 1, NULL, NULL, '2023-01-01 00:14:39', '2023-01-01 00:14:39'),
(5, '5', '1672553743-hansa-h-caffe.jpg', 1, 1, 1, NULL, NULL, '2023-01-01 00:15:43', '2023-01-01 00:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `dinnings`
--

CREATE TABLE `dinnings` (
  `id` int(10) UNSIGNED NOT NULL,
  `dinning_name` varchar(300) NOT NULL,
  `dinning_category` int(11) DEFAULT NULL,
  `dinning_content` text DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `dinning_main_image` text DEFAULT NULL,
  `dinning_oder` int(11) NOT NULL DEFAULT 1,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinnings`
--

INSERT INTO `dinnings` (`id`, `dinning_name`, `dinning_category`, `dinning_content`, `mobile`, `email`, `dinning_main_image`, `dinning_oder`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 'GOLDBERG RESTAURANT', 0, '<p>GOLDBERG RESTAURANT</p>', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 00:14:39', '2023-01-01 00:14:39'),
(5, 'HANSA H – CAFE', 0, '<h2>HANSA H &ndash; CAFE</h2>', NULL, NULL, NULL, 1, NULL, 1, 1, NULL, NULL, '2023-01-01 00:15:43', '2023-01-01 00:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_name` varchar(300) NOT NULL,
  `event_category` int(11) DEFAULT NULL,
  `event_content` text DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `event_main_image` text DEFAULT NULL,
  `event_oder` int(11) NOT NULL DEFAULT 1,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_category`, `event_content`, `mobile`, `email`, `event_main_image`, `event_oder`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Valentine Offer', NULL, NULL, NULL, NULL, '1672547083.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:24:43', '2022-12-31 22:24:43'),
(2, 'SEAFOOD BUFFET DINNER', NULL, NULL, NULL, NULL, '1672547155.png', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:25:55', '2022-12-31 22:25:55'),
(3, 'DELICIOUS PITHA FEST AT HANSA', NULL, NULL, NULL, NULL, '1672547211.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:26:51', '2022-12-31 22:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `gallery_name` varchar(250) DEFAULT NULL,
  `gallery_description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `gallery_image` text DEFAULT NULL,
  `gallery_oder` int(11) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `gallery_name`, `gallery_description`, `category_id`, `gallery_image`, `gallery_oder`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Food Facility', 'Food Facility', 2, '1672548477.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:47:57', '2022-12-31 22:47:57'),
(2, 'Restaurant Service', 'Restaurant Service', 1, '1672548513.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:48:34', '2022-12-31 22:48:34'),
(3, 'Executive Suite King', 'Executive Suite King', 3, '1672548753.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:52:33', '2022-12-31 22:52:33'),
(4, 'Swimming Pool', 'Swimming Pool', 4, '1672548895.jpg', 1, NULL, 1, 1, NULL, NULL, '2022-12-31 22:54:55', '2022-12-31 22:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_parent` int(11) NOT NULL DEFAULT 0,
  `menu_name` varchar(150) NOT NULL,
  `menu_slug` varchar(100) NOT NULL,
  `menu_type` varchar(100) DEFAULT NULL,
  `menu_location` varchar(100) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `menu_icon` varchar(250) DEFAULT NULL,
  `menu_url` varchar(250) DEFAULT NULL,
  `menu_oder` int(11) DEFAULT NULL,
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

INSERT INTO `menus` (`id`, `menu_parent`, `menu_name`, `menu_slug`, `menu_type`, `menu_location`, `remarks`, `menu_icon`, `menu_url`, `menu_oder`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Menu Builder', 'menu-builder', 'backend', 'Header', NULL, 'fa-lock', NULL, 0, 1, 1, NULL, NULL, '2022-12-30 22:34:07', '2022-12-30 22:34:07'),
(2, 1, 'Add Menu', 'add-menu', 'backend', 'Header', NULL, 'fa-arrow-right', 'menus.create', NULL, 1, 1, NULL, NULL, '2022-12-30 22:34:31', '2022-12-30 22:34:31'),
(3, 1, 'Menu List', 'menu-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'menus.index', NULL, 1, 1, NULL, NULL, '2022-12-30 22:35:03', '2022-12-30 22:35:03'),
(4, 0, 'Roles Manage', 'roles-manage', 'backend', 'Header', NULL, 'fa-lock', NULL, NULL, 1, 1, NULL, NULL, '2022-12-30 23:06:14', '2022-12-30 23:06:14'),
(5, 4, 'Add Role', 'add-role', 'backend', 'Header', NULL, 'fa-arrow-right', 'roles.create', NULL, 1, 1, NULL, NULL, '2022-12-30 23:06:45', '2022-12-30 23:06:45'),
(6, 4, 'Role List', 'role-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'roles.index', NULL, 1, 1, NULL, NULL, '2022-12-30 23:07:20', '2022-12-30 23:07:20'),
(7, 0, 'Permission Manage', 'permission-manage', 'backend', 'Header', NULL, 'fa-copy', NULL, NULL, 1, 1, NULL, NULL, '2022-12-30 23:08:12', '2022-12-30 23:08:12'),
(8, 7, 'Permission List', 'permission-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'permissions.index', NULL, 1, 1, NULL, NULL, '2022-12-30 23:08:38', '2022-12-30 23:08:38'),
(9, 7, 'Add Permission', 'add-permission', 'backend', 'Header', NULL, 'fa-arrow-right', 'permissions.create', NULL, 1, 1, NULL, NULL, '2022-12-30 23:09:01', '2022-12-30 23:09:01'),
(10, 0, 'Settings', 'settings', 'backend', 'Header', NULL, 'fa-cog', 'settings', NULL, 1, 1, NULL, NULL, '2022-12-30 23:42:48', '2022-12-30 23:42:48'),
(11, 0, 'Slider Manage', 'slider-manage', 'backend', 'Header', NULL, 'fa-list', NULL, NULL, 1, 1, NULL, NULL, '2022-12-30 23:43:35', '2022-12-30 23:43:35'),
(12, 11, 'Add Slider', 'add-slider', 'backend', 'Header', NULL, 'fa-arrow-right', 'sliders.create', NULL, 1, 1, NULL, NULL, '2022-12-31 02:26:18', '2022-12-31 02:26:18'),
(13, 11, 'Slider List', 'slider-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'sliders.index', NULL, 1, 1, NULL, NULL, '2022-12-31 02:26:41', '2022-12-31 02:26:41'),
(14, 0, 'Offer', 'offer', 'backend', 'Header', NULL, 'fa-cog', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 02:49:12', '2022-12-31 02:49:12'),
(15, 14, 'Add Offer', 'add-offer', 'backend', 'Header', NULL, 'fa-arrow-right', 'offers.create', NULL, 1, 1, NULL, NULL, '2022-12-31 02:50:49', '2022-12-31 02:51:17'),
(16, 0, 'Room Manage', 'room-manage', 'backend', 'Header', NULL, 'fa-home', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 03:36:08', '2022-12-31 03:36:08'),
(17, 16, 'Add Room', 'add-room', 'backend', 'Header', NULL, 'fa-arrow-right', 'rooms.create', NULL, 1, 1, NULL, NULL, '2022-12-31 03:36:46', '2022-12-31 03:36:46'),
(18, 16, 'Room List', 'room-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'rooms.index', NULL, 1, 1, NULL, NULL, '2022-12-31 03:37:22', '2022-12-31 03:37:22'),
(19, 0, 'Event Manage', 'event-manage', 'backend', 'Header', NULL, 'fa-copy', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:21:35', '2022-12-31 22:21:35'),
(20, 19, 'Add Event', 'add-event', 'backend', 'Header', NULL, 'fa-arrow-right', 'events.create', NULL, 1, 1, NULL, NULL, '2022-12-31 22:22:36', '2022-12-31 22:22:36'),
(21, 19, 'Event List', 'event-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'events.index', NULL, 1, 1, NULL, NULL, '2022-12-31 22:22:59', '2022-12-31 22:22:59'),
(22, 0, 'Gallery Manage', 'gallery-manage', 'backend', 'Header', NULL, 'fa-plus', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 22:28:31', '2022-12-31 22:28:31'),
(23, 22, 'Add Gallery', 'add-gallery', 'backend', 'Header', NULL, 'fa-arrow-right', 'galleries.create', NULL, 1, 1, NULL, NULL, '2022-12-31 22:28:55', '2022-12-31 22:28:55'),
(24, 22, 'Gallery List', 'gallery-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'galleries.index', NULL, 1, 1, NULL, NULL, '2022-12-31 22:29:17', '2022-12-31 22:29:17'),
(25, 0, 'Category Manage', 'dinning-manage', 'backend', 'Header', NULL, 'fa-list', 'dinning-manage.index', NULL, 1, 1, NULL, NULL, '2022-12-31 22:30:53', '2022-12-31 22:30:53'),
(26, 0, 'Dinning Manage', 'dinning-manage', 'backend', 'Header', NULL, 'fa-cutlery', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 23:32:10', '2023-01-01 03:03:54'),
(27, 26, 'Add Dinning', 'add-dinning', 'backend', 'Header', NULL, 'fa-arrow-right', 'dinning-manage.create', NULL, 1, 1, NULL, NULL, '2022-12-31 23:32:32', '2022-12-31 23:32:32'),
(28, 0, 'Room Booking', 'room-booking', 'backend', 'Header', NULL, 'fa-home', NULL, NULL, 1, 1, NULL, NULL, '2023-01-01 01:31:19', '2023-01-01 01:32:00'),
(29, 0, 'Contact List View', 'contact-list-view', 'backend', 'Header', NULL, 'fa-info', 'contactlistview', NULL, 1, 1, NULL, NULL, '2023-01-01 02:33:38', '2023-01-01 02:33:38'),
(30, 0, 'Users Manage', 'users-manage', 'backend', 'Header', NULL, 'fa-users', NULL, NULL, 1, 1, NULL, NULL, '2023-01-01 04:58:54', '2023-01-01 04:58:54'),
(31, 30, 'Add User', 'add-user', 'backend', 'Header', NULL, 'fa-arrow-right', 'users.create', NULL, 1, 1, NULL, NULL, '2023-01-01 04:59:15', '2023-01-01 04:59:15'),
(32, 30, 'User List', 'user-list', 'backend', 'Header', NULL, 'fa-arrow-right', 'users.index', NULL, 1, 1, NULL, NULL, '2023-01-01 04:59:34', '2023-01-01 04:59:34');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_02_05_044607_create_permission_tables', 1),
(6, '2022_02_06_062951_create_menus_table', 1),
(7, '2022_02_26_053944_create_galleries_table', 1),
(8, '2022_02_26_054246_create_services_table', 1),
(9, '2022_02_26_054323_create_pages_table', 1),
(10, '2022_02_26_054357_create_posts_table', 1),
(11, '2022_02_26_054411_create_settings_table', 1),
(12, '2022_03_01_044037_create_categories_column_table', 1),
(13, '2022_03_01_070934_create_sliders_table', 1),
(14, '2022_03_09_042846_create_rooms_table', 1),
(15, '2022_03_12_044323_create_room_images_table', 1),
(16, '2022_03_12_044416_create_roombookings_table', 1),
(17, '2022_03_12_070143_create_room_services_table', 1),
(18, '2022_10_17_051821_create_frontends_table', 1),
(19, '2022_10_19_050519_create_offers_table', 1),
(20, '2022_11_03_102930_create_roomtypes_table', 1),
(21, '2022_11_05_085419_create_roomdetails_table', 1),
(22, '2022_11_07_100933_create_dinnings_table', 1),
(23, '2022_11_07_102113_create_dinningimages_table', 1),
(24, '2022_11_12_065523_create_events_table', 1),
(25, '2022_11_17_052635_create_contacts_table', 1);

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
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(10) UNSIGNED NOT NULL,
  `offer_title` varchar(300) NOT NULL,
  `offer_caption` varchar(250) NOT NULL,
  `offer_content` text DEFAULT NULL,
  `offer_image` text DEFAULT NULL,
  `offer_time` text DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `offer_oder` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `offer_title`, `offer_caption`, `offer_content`, `offer_image`, `offer_time`, `remarks`, `offer_oder`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Weekend Offer', 'Eid-Ul-Adha Package 2022 Weekend Offer Every Thursday and Friday', NULL, '1672478267.jpg', 'Weekend Buffet Dinner BDT 1150 ++ per person', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 03:17:47', '2022-12-31 03:17:47'),
(2, 'Unwind at Hansa Spa', 'Open Everyday', NULL, '1672478985.jpg', '11 To 10 PM', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 03:27:13', '2022-12-31 03:29:45'),
(3, 'Hansa Swimming Pool', 'Weekend Buffet Dinner BDT 1150 ++ per person', 'Open Everyday', '1672478967.jpg', 'Open Everyday', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 03:29:27', '2022-12-31 03:29:27');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_name` varchar(250) DEFAULT NULL,
  `page_slug` varchar(100) DEFAULT NULL,
  `page_description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `page_link` text DEFAULT NULL,
  `page_image` text DEFAULT NULL,
  `page_oder` int(11) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
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
(1, 'role-list', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(2, 'role-create', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(3, 'role-edit', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(4, 'role-delete', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(5, 'product-list', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(6, 'product-create', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(7, 'product-edit', NULL, NULL, 'web', '2022-12-30 22:31:55', '2022-12-30 22:31:55'),
(8, 'product-delete', NULL, NULL, 'web', '2022-12-30 22:31:56', '2022-12-30 22:31:56'),
(9, 'menu-create', NULL, 'menus', 'web', '2022-12-30 23:23:43', '2022-12-30 23:23:43'),
(10, 'menu-edit', NULL, 'menus', 'web', '2022-12-30 23:24:59', '2022-12-30 23:24:59'),
(11, 'menu-delete', NULL, 'menus', 'web', '2022-12-30 23:25:16', '2022-12-30 23:25:16'),
(12, 'slider-create', NULL, 'sliders', 'web', '2022-12-31 02:27:14', '2022-12-31 02:27:14'),
(13, 'slider-delete', NULL, 'sliders', 'web', '2022-12-31 02:27:32', '2022-12-31 02:27:32'),
(14, 'room-create', NULL, 'rooms', 'web', '2022-12-31 03:39:18', '2022-12-31 03:39:18'),
(15, 'room-edit', NULL, 'rooms', 'web', '2022-12-31 03:39:45', '2022-12-31 03:39:45'),
(16, 'room-delete', NULL, 'rooms', 'web', '2022-12-31 03:40:07', '2022-12-31 03:40:07'),
(17, 'category-create', NULL, 'categories', 'web', '2022-12-31 22:42:22', '2022-12-31 22:42:22'),
(18, 'contact-delete', NULL, 'contact', 'web', '2023-01-01 02:53:55', '2023-01-01 02:53:55');

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Admin', 'web', '2022-12-30 22:33:02', '2022-12-30 22:33:02');

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
(2, 1),
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
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roombookings`
--

CREATE TABLE `roombookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `check_in` varchar(255) DEFAULT NULL,
  `check_out` varchar(255) DEFAULT NULL,
  `booking_date` varchar(250) NOT NULL,
  `service_id` text DEFAULT NULL,
  `num_of_night` varchar(255) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roomdetails`
--

CREATE TABLE `roomdetails` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `roomdetails_oder` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_name` varchar(300) NOT NULL,
  `room_type` varchar(250) NOT NULL,
  `room_discription` text DEFAULT NULL,
  `room_available` varchar(255) DEFAULT NULL,
  `room_guests` int(11) DEFAULT NULL,
  `amount` double(13,2) NOT NULL,
  `min_booking` int(11) DEFAULT NULL,
  `room_bed_size` varchar(255) DEFAULT NULL,
  `room_size_sft` varchar(255) DEFAULT NULL,
  `room_reviews` varchar(255) DEFAULT NULL,
  `room_offer` text DEFAULT NULL,
  `arrival_date` varchar(255) DEFAULT NULL,
  `departure_date` varchar(255) DEFAULT NULL,
  `adult` int(11) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `guest` int(11) DEFAULT NULL,
  `room_booking` text DEFAULT NULL,
  `room_main_image` text DEFAULT NULL,
  `room_oder` int(11) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `room_type`, `room_discription`, `room_available`, `room_guests`, `amount`, `min_booking`, `room_bed_size`, `room_size_sft`, `room_reviews`, `room_offer`, `arrival_date`, `departure_date`, `adult`, `children`, `guest`, `room_booking`, `room_main_image`, `room_oder`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Studio Deluxe', 'Studio Deluxe', '<h3>Studio Deluxe</h3>', 'yes', 5, 5000.00, 1, 'King Bed', '220 SFT', NULL, 'Winter Offer - Enjoy 45% discount on room rack rate.', NULL, NULL, NULL, NULL, NULL, NULL, 'studio_deluxe.jpg', NULL, NULL, 1, 1, NULL, NULL, '2022-12-31 04:11:23', '2022-12-31 04:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `roomtypes`
--

CREATE TABLE `roomtypes` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_title` varchar(300) NOT NULL,
  `room_description` text DEFAULT NULL,
  `roomtype_image` text DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `roomtype_oder` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` varchar(300) NOT NULL,
  `room_image` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`id`, `room_id`, `room_image`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1672481483-studio_deluxe.jpg', 1, 1, NULL, NULL, '2022-12-31 04:11:23', '2022-12-31 04:11:23'),
(2, '2', '1672481552-studio_deluxe.jpg', 1, 1, NULL, NULL, '2022-12-31 04:12:32', '2022-12-31 04:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `room_services`
--

CREATE TABLE `room_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `service_type` varchar(150) DEFAULT NULL,
  `service_discription` text DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Hansa Hotel', 'HANSA – A Premium Residence is owned by Unique Hotel & Resorts Limited, the leading Hospitality Management Company and the owner of “The Westin, Dhaka”', 'Hansa Admin Dashboard', 'Hansa Admin Dashboard', '1672469576.jpg', NULL, '1672469533.jpg', 1, 1, NULL, NULL, '2022-12-31 00:52:13', '2022-12-31 00:52:56');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `slider_name` varchar(300) NOT NULL,
  `slider_caption` varchar(250) NOT NULL,
  `slider_content` text DEFAULT NULL,
  `slider_image` text DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `slider_oder` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `slider_name`, `slider_caption`, `slider_content`, `slider_image`, `remarks`, `slider_oder`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Slider One', 'Slider One', 'Slider One', '1672475902.jpg', NULL, 1, 1, 1, NULL, NULL, '2022-12-31 02:38:22', '2022-12-31 02:38:22'),
(2, 'Slider Two', 'Slider Two', 'Slider Two', '1672476149.jpg', NULL, 2, 1, 1, NULL, NULL, '2022-12-31 02:42:29', '2022-12-31 02:42:29'),
(3, 'Slider Three', 'Slider Three', 'Slider Three', '1672476188.jpg', NULL, 3, 1, 1, NULL, NULL, '2022-12-31 02:43:08', '2022-12-31 02:43:08'),
(5, 'Slider Four', 'Slider Four', 'Slider Four', '1672476407.jpg', NULL, 4, 1, 1, NULL, NULL, '2022-12-31 02:46:47', '2022-12-31 02:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_image` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Imran Admin', 'admin@gmail.com', NULL, '$2y$10$3ZGJ5Voe7AIFy2JYv6zyNecC2.18aJcjqa4t8RV084ljssyWiEucK', NULL, NULL, '2022-12-30 22:33:02', '2022-12-31 00:53:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_id_index` (`id`),
  ADD KEY `categories_category_name_index` (`category_name`),
  ADD KEY `categories_category_slug_index` (`category_slug`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_id_index` (`id`),
  ADD KEY `contacts_contact_name_index` (`contact_name`);

--
-- Indexes for table `dinningimages`
--
ALTER TABLE `dinningimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dinningimages_id_index` (`id`),
  ADD KEY `dinningimages_dinning_id_index` (`dinning_id`);

--
-- Indexes for table `dinnings`
--
ALTER TABLE `dinnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dinnings_id_index` (`id`),
  ADD KEY `dinnings_dinning_name_index` (`dinning_name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_id_index` (`id`),
  ADD KEY `events_event_name_index` (`event_name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_id_index` (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_id_index` (`id`),
  ADD KEY `menus_menu_name_index` (`menu_name`),
  ADD KEY `menus_menu_slug_index` (`menu_slug`);

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
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_id_index` (`id`),
  ADD KEY `offers_offer_title_index` (`offer_title`),
  ADD KEY `offers_offer_caption_index` (`offer_caption`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_id_index` (`id`);

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
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `roombookings`
--
ALTER TABLE `roombookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roombookings_id_index` (`id`),
  ADD KEY `roombookings_room_id_index` (`room_id`),
  ADD KEY `roombookings_booking_date_index` (`booking_date`);

--
-- Indexes for table `roomdetails`
--
ALTER TABLE `roomdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomdetails_id_index` (`id`),
  ADD KEY `roomdetails_room_id_index` (`room_id`),
  ADD KEY `roomdetails_service_id_index` (`service_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_id_index` (`id`),
  ADD KEY `rooms_room_name_index` (`room_name`),
  ADD KEY `rooms_room_type_index` (`room_type`);

--
-- Indexes for table `roomtypes`
--
ALTER TABLE `roomtypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomtypes_id_index` (`id`),
  ADD KEY `roomtypes_room_title_index` (`room_title`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_images_id_index` (`id`),
  ADD KEY `room_images_room_id_index` (`room_id`);

--
-- Indexes for table `room_services`
--
ALTER TABLE `room_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_services_id_index` (`id`),
  ADD KEY `room_services_room_id_index` (`room_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_id_index` (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sliders_id_index` (`id`),
  ADD KEY `sliders_slider_name_index` (`slider_name`),
  ADD KEY `sliders_slider_caption_index` (`slider_caption`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `dinningimages`
--
ALTER TABLE `dinningimages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dinnings`
--
ALTER TABLE `dinnings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roombookings`
--
ALTER TABLE `roombookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roomdetails`
--
ALTER TABLE `roomdetails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roomtypes`
--
ALTER TABLE `roomtypes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_services`
--
ALTER TABLE `room_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
