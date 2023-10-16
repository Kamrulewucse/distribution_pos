-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2023 at 10:34 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aitautom_swarnaly_telecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_head_sub_types`
--

CREATE TABLE `account_head_sub_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_head_type_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_head_sub_types`
--

INSERT INTO `account_head_sub_types` (`id`, `account_head_type_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Supplier Payment', 1, '2020-08-19 04:45:01', '2020-08-19 04:45:01'),
(2, 2, 'Retailer Payment', 1, '2020-08-19 04:47:51', '2020-08-19 04:47:51'),
(3, 3, 'Balance Transfer Debit', 1, '2020-08-19 04:48:20', '2020-08-19 04:48:20'),
(4, 4, 'Balance Transfer Credit\r\n', 1, '2020-08-19 10:12:03', '2020-08-19 10:12:03'),
(5, 5, 'Buying Cost', 1, '2020-08-19 10:12:03', '2020-08-19 10:12:03'),
(6, 6, 'Sales Return', 1, '2020-08-19 10:12:03', '2020-08-19 10:12:03'),
(7, 7, 'Purchase Return', 1, '2020-08-19 10:12:03', '2020-08-19 10:12:03'),
(8, 11, 'Shop Rental', 1, '2022-12-27 04:05:52', '2023-01-05 08:38:26'),
(9, 12, 'Product Price Update', 1, '2023-01-05 10:42:54', '2023-01-05 10:42:54'),
(10, 13, 'Company/Supplier Back Margin Amount', 1, '2023-01-05 10:42:54', '2023-01-05 10:42:54'),
(11, 14, 'Company/Supplier Price Adjustment Amount', 1, '2023-01-05 10:42:54', '2023-01-05 10:42:54'),
(12, 15, 'Retailer Price Adjustment Amount', 1, '2023-01-05 10:42:54', '2023-01-05 10:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `account_head_types`
--

CREATE TABLE `account_head_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=Income; 2=Expense',
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_head_types`
--

INSERT INTO `account_head_types` (`id`, `name`, `transaction_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Supplier Payment', 2, 1, '2020-08-19 04:44:47', '2020-08-19 04:44:47'),
(2, 'Retailer Payment', 1, 1, '2020-08-19 04:47:28', '2020-08-19 04:47:28'),
(3, 'Balance Transfer Debit', 1, 1, '2020-08-19 04:48:05', '2020-08-19 04:48:05'),
(4, 'Balance Transfer Credit', 2, 1, '2020-08-19 10:11:40', '2020-08-19 10:11:40'),
(5, 'Buying Cost', 2, 1, '2020-08-19 10:11:40', '2020-08-19 10:11:40'),
(6, 'Sales Return', 2, 1, '2020-08-19 10:11:40', '2020-08-19 10:11:40'),
(7, 'Purchase Return', 1, 1, '2020-08-19 10:11:40', '2020-08-19 10:11:40'),
(8, 'shope entertainment', 2, 1, '2021-12-07 13:17:26', '2023-01-05 09:52:51'),
(9, 'shop gadget', 2, 1, '2021-12-07 13:17:51', '2021-12-07 13:17:51'),
(10, 'owner personal', 2, 1, '2021-12-07 13:18:11', '2021-12-07 13:18:11'),
(11, 'shop rental', 2, 1, '2021-12-07 13:18:31', '2021-12-07 13:18:31'),
(12, 'Balance Adjustment', 1, 1, '2023-01-05 10:31:05', '2023-01-05 10:31:05'),
(13, 'Company/Supplier Back Margin', 3, 1, '2023-01-05 10:31:05', '2023-01-05 10:31:05'),
(14, 'Company/Supplier Price Adjustment', 3, 1, '2023-01-05 10:31:05', '2023-01-05 10:31:05'),
(15, 'Retailer Price Adjustment', 3, 1, '2023-01-05 10:31:05', '2023-01-05 10:31:05');

-- --------------------------------------------------------

--
-- Table structure for table `balance_transfers`
--

CREATE TABLE `balance_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=Bank To Cash; 2=Cash To Bank; 3=Bank To Bank',
  `source_bank_id` int(10) UNSIGNED DEFAULT NULL,
  `source_branch_id` int(10) UNSIGNED DEFAULT NULL,
  `source_bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `source_cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_bank_id` int(10) UNSIGNED DEFAULT NULL,
  `target_branch_id` int(10) UNSIGNED DEFAULT NULL,
  `target_bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `target_cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Brac Bank', 1, '2022-10-29 11:55:27', '2022-10-29 11:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` double(20,2) NOT NULL,
  `balance` double(20,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_id`, `branch_id`, `account_name`, `account_no`, `account_code`, `description`, `opening_balance`, `balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Swarnaly Telecom', '1539202252026001', '2345', 'Collection', 10000.00, 10000.00, 1, '2022-10-29 11:57:22', '2022-12-30 08:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `bank_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jatrabari', 1, '2022-10-29 11:55:49', '2022-10-29 11:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `cashes`
--

CREATE TABLE `cashes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(20,2) NOT NULL,
  `opening_balance` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashes`
--

INSERT INTO `cashes` (`id`, `amount`, `opening_balance`, `created_at`, `updated_at`) VALUES
(1, 415610.20, 0.00, '2020-08-09 18:00:00', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `mobile_no`, `address`, `shop_name`, `email`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Kamrul', '01583549631', 'Mogbazar', 'S A Telecom', 'kamrul@gmail.com', 4, '2022-12-29 11:33:10', '2022-12-29 11:33:10'),
(2, 'Md Rashid Miazi', '018195624562', 'Dhaka', 'Tanim Telecom', 'fdgfhgj@gmail.com', 5, '2022-12-30 05:18:17', '2022-12-30 08:30:47'),
(3, 'Md Hannan', '01788842222', '87, Taj Super Market, Jatrabari, Dhaka', 'Vai Vai Telecom', 'dfdtd@gmail.com', 9, '2022-12-30 08:29:04', '2022-12-30 08:29:04'),
(4, 'Md Rasel', '01752372890', 'Sonir Akhra, Dhaka', 'Rasel Telecom', 'srdfd@gmail.com', 10, '2022-12-30 08:32:30', '2022-12-30 08:32:30'),
(5, 'md Manik', '01791555553', 'Konapara, Demra, Dhaka', 'Maa Electronics', 'djdf@gmail.com', 11, '2022-12-30 08:34:02', '2022-12-30 08:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `customer_services`
--

CREATE TABLE `customer_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=receive,1=panding,2=delivery	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_services`
--

INSERT INTO `customer_services` (`id`, `name`, `mobile_name`, `mobile_no`, `address`, `date`, `delivery_date`, `note`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Arif Ahmed', 'Itel A4', '0195383548', 'Dhaka,Jatrabari', '2022-12-29', '2023-01-02', 'asdf', 3, '2022-12-31 10:33:08', '2022-12-31 10:33:08'),
(3, 'Adnan Bro', 'Samsung A10', '01336979563', 'Rampura, Mohanagar', '2023-01-02', '2023-01-19', 'Test note', 0, '2023-01-02 09:44:23', '2023-01-02 09:44:23'),
(4, 'rasel', 'Sumsung s4', '01987020306', 'Sonir Akhra, Dhaka', '2023-01-07', '2023-01-07', NULL, 2, '2023-01-07 10:43:04', '2023-01-07 10:43:04'),
(5, 'jashim', 'item M6', '01456832654', 'hg', '2023-05-19', '2023-05-19', 'ok', 3, '2023-05-19 09:08:50', '2023-05-19 09:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_checks`
--

CREATE TABLE `login_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_checks`
--

INSERT INTO `login_checks` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-04-05 09:11:59', '2021-04-05 09:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_06_105916_create_warehouses_table', 2),
(5, '2020_08_06_133014_create_banks_table', 3),
(6, '2020_08_06_133407_create_branches_table', 4),
(7, '2020_08_06_133615_create_bank_accounts_table', 5),
(8, '2020_08_06_134846_add_account_name_column_in_bank_accounts_table', 6),
(9, '2020_08_06_143539_create_suppliers_table', 7),
(12, '2020_08_09_122922_create_purchase_products_table', 8),
(17, '2020_08_09_134131_create_purchase_orders_table', 9),
(18, '2020_08_09_134346_create_purchase_order_purchase_product_table', 9),
(19, '2020_08_09_140508_create_purchase_payments_table', 9),
(20, '2020_08_10_135758_create_cashes_table', 10),
(21, '2020_08_10_140052_create_transaction_logs_table', 11),
(22, '2020_08_10_172405_create_mobile_bankings_table', 12),
(39, '2020_08_10_201620_create_purchase_inventories_table', 13),
(40, '2020_08_10_201848_create_purchase_inventory_logs_table', 13),
(41, '2020_08_11_200012_create_sales_orders_table', 13),
(42, '2020_08_11_200835_create_purchase_product_sales_order_table', 13),
(43, '2020_08_11_225945_add_next_payment_date_column_in_sales_orders', 14),
(44, '2020_08_11_234520_create_sale_payments_table', 15),
(45, '2020_08_11_235319_add_sale_payment_id_colunm_in_transaction_logs', 16),
(46, '2020_08_12_144558_add_sales_order_id_column_in_transaction_logs', 17),
(47, '2020_08_12_145752_add_created_by_column_in_sales_orders', 18),
(48, '2020_08_12_204603_create_account_head_types_table', 19),
(49, '2020_08_12_204957_create_account_head_sub_types_table', 20),
(50, '2020_08_12_205137_create_transactions_table', 21),
(51, '2020_08_12_205408_add_transaction_id_column_in_transaction_logs_table', 22),
(52, '2020_08_12_205547_create_balance_transfers_table', 23),
(53, '2020_08_12_205711_add_balace_transfer_id_in_transaction_logs_table', 24),
(54, '2020_08_14_234208_create_customers_table', 25),
(55, '2020_08_15_000650_change_customer_in_sales_orders', 26),
(56, '2020_08_15_004254_add_received_by_column_in_sales_orders', 27),
(57, '2020_08_16_214745_add_vat_percentage_in_sales_orders', 28),
(58, '2020_08_18_213532_create_permission_tables', 29),
(59, '2020_08_19_160319_add_column_in_transaction_logs', 30),
(60, '2020_08_22_221720_add_services_columns_in_sales_orders', 31),
(61, '2020_08_22_222401_create_services_table', 32),
(62, '2020_08_24_150722_add_refund_column_in_purchase_orders', 33),
(63, '2020_08_24_204041_add_type_column_in_purchase_payments', 34),
(64, '2020_08_25_214933_add_refund_column_in_sales_orders', 35),
(65, '2020_08_26_114900_add_type_column_in_sale_payments', 36),
(66, '2020_09_14_143319_add_received_type_column_in_sale_payments_table', 37),
(67, '2020_12_15_144723_create_purchase_product_categories_table', 38),
(68, '2020_12_15_144751_create_purchase_product_sub_categories_table', 38),
(69, '2020_12_17_095909_create_purchase_product_sub_sub_categories_table', 39),
(70, '2021_01_07_115825_create_wastages_table', 39),
(71, '2021_01_07_150132_create_wastage_products_table', 39),
(72, '2021_04_05_144142_create_login_checks_table', 40),
(73, '2022_10_17_182447_create_product_types_table', 41),
(74, '2022_10_19_125207_create_product_colors_table', 42),
(75, '2022_10_19_142558_create_product_brands_table', 43),
(76, '2022_10_19_144800_create_product_models_table', 44),
(77, '2022_10_23_145939_create_sales_representatives_table', 45),
(78, '2019_12_14_000001_create_personal_access_tokens_table', 46),
(79, '2022_10_24_155255_create_sr_product_assign_orders_table', 46),
(80, '2022_10_24_155541_create_sr_product_assign_order_items_table', 46),
(81, '2022_11_15_171128_create_stock_transfer_orders_table', 47),
(82, '2022_12_04_101527_create_shops_table', 48),
(83, '2022_12_22_174109_create_update_prices_table', 49),
(84, '2022_12_31_124212_create_customer_services_table', 50);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_bankings`
--

CREATE TABLE `mobile_bankings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobile_bankings`
--

INSERT INTO `mobile_bankings` (`id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 0.00, '2020-08-10 11:25:12', '2020-12-19 05:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1),
(12, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 1),
(22, 'App\\Models\\User', 1),
(23, 'App\\Models\\User', 1),
(24, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 1),
(31, 'App\\Models\\User', 1),
(32, 'App\\Models\\User', 1),
(33, 'App\\Models\\User', 1),
(34, 'App\\Models\\User', 1),
(35, 'App\\Models\\User', 1),
(36, 'App\\Models\\User', 1),
(37, 'App\\Models\\User', 1),
(38, 'App\\Models\\User', 1),
(39, 'App\\Models\\User', 1),
(40, 'App\\Models\\User', 1),
(41, 'App\\Models\\User', 1),
(42, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 2),
(15, 'App\\Models\\User', 2),
(17, 'App\\Models\\User', 2),
(18, 'App\\Models\\User', 2),
(28, 'App\\Models\\User', 2),
(14, 'App\\Models\\User', 3),
(15, 'App\\Models\\User', 3),
(17, 'App\\Models\\User', 3),
(18, 'App\\Models\\User', 3),
(28, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 4),
(7, 'App\\Models\\User', 4),
(8, 'App\\Models\\User', 4),
(9, 'App\\Models\\User', 4),
(10, 'App\\Models\\User', 4),
(11, 'App\\Models\\User', 4),
(12, 'App\\Models\\User', 4),
(13, 'App\\Models\\User', 4),
(14, 'App\\Models\\User', 4),
(15, 'App\\Models\\User', 4),
(17, 'App\\Models\\User', 4),
(18, 'App\\Models\\User', 4),
(20, 'App\\Models\\User', 4),
(21, 'App\\Models\\User', 4),
(22, 'App\\Models\\User', 4),
(23, 'App\\Models\\User', 4),
(24, 'App\\Models\\User', 4),
(25, 'App\\Models\\User', 4),
(26, 'App\\Models\\User', 4),
(27, 'App\\Models\\User', 4),
(28, 'App\\Models\\User', 4),
(30, 'App\\Models\\User', 4),
(31, 'App\\Models\\User', 4),
(32, 'App\\Models\\User', 4),
(33, 'App\\Models\\User', 4),
(34, 'App\\Models\\User', 4),
(35, 'App\\Models\\User', 4),
(36, 'App\\Models\\User', 4),
(37, 'App\\Models\\User', 4),
(38, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 5),
(7, 'App\\Models\\User', 5),
(8, 'App\\Models\\User', 5),
(9, 'App\\Models\\User', 5),
(10, 'App\\Models\\User', 5),
(11, 'App\\Models\\User', 5),
(12, 'App\\Models\\User', 5),
(13, 'App\\Models\\User', 5),
(14, 'App\\Models\\User', 5),
(17, 'App\\Models\\User', 5),
(21, 'App\\Models\\User', 5),
(22, 'App\\Models\\User', 5),
(23, 'App\\Models\\User', 5),
(24, 'App\\Models\\User', 5),
(25, 'App\\Models\\User', 5),
(26, 'App\\Models\\User', 5),
(27, 'App\\Models\\User', 5),
(28, 'App\\Models\\User', 5),
(30, 'App\\Models\\User', 5),
(31, 'App\\Models\\User', 5),
(32, 'App\\Models\\User', 5),
(33, 'App\\Models\\User', 5),
(34, 'App\\Models\\User', 5),
(35, 'App\\Models\\User', 5),
(36, 'App\\Models\\User', 5),
(37, 'App\\Models\\User', 5),
(38, 'App\\Models\\User', 5),
(39, 'App\\Models\\User', 5),
(40, 'App\\Models\\User', 5),
(41, 'App\\Models\\User', 5),
(42, 'App\\Models\\User', 5),
(14, 'App\\Models\\User', 6),
(15, 'App\\Models\\User', 6),
(17, 'App\\Models\\User', 6),
(18, 'App\\Models\\User', 6),
(28, 'App\\Models\\User', 6),
(14, 'App\\Models\\User', 7),
(15, 'App\\Models\\User', 7),
(17, 'App\\Models\\User', 7),
(18, 'App\\Models\\User', 7),
(28, 'App\\Models\\User', 7),
(14, 'App\\Models\\User', 8),
(15, 'App\\Models\\User', 8),
(17, 'App\\Models\\User', 8),
(18, 'App\\Models\\User', 8),
(28, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(7, 'App\\Models\\User', 9),
(8, 'App\\Models\\User', 9),
(9, 'App\\Models\\User', 9),
(10, 'App\\Models\\User', 9),
(11, 'App\\Models\\User', 9),
(12, 'App\\Models\\User', 9),
(13, 'App\\Models\\User', 9),
(14, 'App\\Models\\User', 9),
(17, 'App\\Models\\User', 9),
(21, 'App\\Models\\User', 9),
(22, 'App\\Models\\User', 9),
(23, 'App\\Models\\User', 9),
(24, 'App\\Models\\User', 9),
(27, 'App\\Models\\User', 9),
(35, 'App\\Models\\User', 9),
(36, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(7, 'App\\Models\\User', 10),
(8, 'App\\Models\\User', 10),
(9, 'App\\Models\\User', 10),
(10, 'App\\Models\\User', 10),
(11, 'App\\Models\\User', 10),
(12, 'App\\Models\\User', 10),
(13, 'App\\Models\\User', 10),
(14, 'App\\Models\\User', 10),
(17, 'App\\Models\\User', 10),
(27, 'App\\Models\\User', 10),
(28, 'App\\Models\\User', 10),
(35, 'App\\Models\\User', 10),
(36, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 11),
(5, 'App\\Models\\User', 11),
(6, 'App\\Models\\User', 11),
(7, 'App\\Models\\User', 11),
(8, 'App\\Models\\User', 11),
(9, 'App\\Models\\User', 11),
(10, 'App\\Models\\User', 11),
(11, 'App\\Models\\User', 11),
(12, 'App\\Models\\User', 11),
(13, 'App\\Models\\User', 11),
(14, 'App\\Models\\User', 11),
(17, 'App\\Models\\User', 11),
(21, 'App\\Models\\User', 11),
(22, 'App\\Models\\User', 11),
(23, 'App\\Models\\User', 11),
(24, 'App\\Models\\User', 11),
(25, 'App\\Models\\User', 11),
(26, 'App\\Models\\User', 11),
(27, 'App\\Models\\User', 11),
(28, 'App\\Models\\User', 11),
(31, 'App\\Models\\User', 11),
(32, 'App\\Models\\User', 11),
(33, 'App\\Models\\User', 11),
(34, 'App\\Models\\User', 11),
(35, 'App\\Models\\User', 11),
(36, 'App\\Models\\User', 11),
(37, 'App\\Models\\User', 11),
(38, 'App\\Models\\User', 11),
(39, 'App\\Models\\User', 11),
(40, 'App\\Models\\User', 11),
(41, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(7, 'App\\Models\\User', 12),
(8, 'App\\Models\\User', 12),
(9, 'App\\Models\\User', 12),
(10, 'App\\Models\\User', 12),
(11, 'App\\Models\\User', 12),
(12, 'App\\Models\\User', 12),
(14, 'App\\Models\\User', 12),
(17, 'App\\Models\\User', 12),
(28, 'App\\Models\\User', 12),
(35, 'App\\Models\\User', 12),
(36, 'App\\Models\\User', 12),
(1, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 13),
(5, 'App\\Models\\User', 13),
(6, 'App\\Models\\User', 13),
(7, 'App\\Models\\User', 13),
(8, 'App\\Models\\User', 13),
(9, 'App\\Models\\User', 13),
(10, 'App\\Models\\User', 13),
(11, 'App\\Models\\User', 13),
(12, 'App\\Models\\User', 13),
(13, 'App\\Models\\User', 13),
(14, 'App\\Models\\User', 13),
(17, 'App\\Models\\User', 13),
(21, 'App\\Models\\User', 13),
(22, 'App\\Models\\User', 13),
(23, 'App\\Models\\User', 13),
(24, 'App\\Models\\User', 13),
(25, 'App\\Models\\User', 13),
(26, 'App\\Models\\User', 13),
(27, 'App\\Models\\User', 13),
(28, 'App\\Models\\User', 13),
(30, 'App\\Models\\User', 13),
(31, 'App\\Models\\User', 13),
(32, 'App\\Models\\User', 13),
(33, 'App\\Models\\User', 13),
(34, 'App\\Models\\User', 13),
(35, 'App\\Models\\User', 13),
(36, 'App\\Models\\User', 13),
(37, 'App\\Models\\User', 13),
(38, 'App\\Models\\User', 13),
(39, 'App\\Models\\User', 13),
(40, 'App\\Models\\User', 13),
(41, 'App\\Models\\User', 13),
(42, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 14),
(3, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 14),
(5, 'App\\Models\\User', 14),
(6, 'App\\Models\\User', 14),
(7, 'App\\Models\\User', 14),
(8, 'App\\Models\\User', 14),
(9, 'App\\Models\\User', 14),
(10, 'App\\Models\\User', 14),
(11, 'App\\Models\\User', 14),
(12, 'App\\Models\\User', 14),
(13, 'App\\Models\\User', 14),
(14, 'App\\Models\\User', 14),
(17, 'App\\Models\\User', 14),
(21, 'App\\Models\\User', 14),
(22, 'App\\Models\\User', 14),
(23, 'App\\Models\\User', 14),
(24, 'App\\Models\\User', 14),
(25, 'App\\Models\\User', 14),
(26, 'App\\Models\\User', 14),
(27, 'App\\Models\\User', 14),
(28, 'App\\Models\\User', 14),
(31, 'App\\Models\\User', 14),
(32, 'App\\Models\\User', 14),
(33, 'App\\Models\\User', 14),
(34, 'App\\Models\\User', 14),
(35, 'App\\Models\\User', 14),
(36, 'App\\Models\\User', 14),
(37, 'App\\Models\\User', 14),
(38, 'App\\Models\\User', 14),
(42, 'App\\Models\\User', 14),
(1, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 15),
(5, 'App\\Models\\User', 15),
(6, 'App\\Models\\User', 15),
(7, 'App\\Models\\User', 15),
(8, 'App\\Models\\User', 15),
(9, 'App\\Models\\User', 15),
(10, 'App\\Models\\User', 15),
(11, 'App\\Models\\User', 15),
(12, 'App\\Models\\User', 15),
(13, 'App\\Models\\User', 15),
(14, 'App\\Models\\User', 15),
(17, 'App\\Models\\User', 15),
(21, 'App\\Models\\User', 15),
(22, 'App\\Models\\User', 15),
(23, 'App\\Models\\User', 15),
(24, 'App\\Models\\User', 15),
(25, 'App\\Models\\User', 15),
(26, 'App\\Models\\User', 15),
(27, 'App\\Models\\User', 15),
(28, 'App\\Models\\User', 15),
(30, 'App\\Models\\User', 15),
(31, 'App\\Models\\User', 15),
(32, 'App\\Models\\User', 15),
(33, 'App\\Models\\User', 15),
(34, 'App\\Models\\User', 15),
(35, 'App\\Models\\User', 15),
(36, 'App\\Models\\User', 15),
(37, 'App\\Models\\User', 15),
(38, 'App\\Models\\User', 15),
(39, 'App\\Models\\User', 15),
(40, 'App\\Models\\User', 15),
(41, 'App\\Models\\User', 15),
(42, 'App\\Models\\User', 15),
(14, 'App\\Models\\User', 17),
(17, 'App\\Models\\User', 17),
(28, 'App\\Models\\User', 17),
(14, 'App\\Models\\User', 18),
(15, 'App\\Models\\User', 18),
(17, 'App\\Models\\User', 18),
(18, 'App\\Models\\User', 18),
(28, 'App\\Models\\User', 18),
(14, 'App\\Models\\User', 19),
(15, 'App\\Models\\User', 19),
(17, 'App\\Models\\User', 19),
(18, 'App\\Models\\User', 19),
(28, 'App\\Models\\User', 19),
(14, 'App\\Models\\User', 21),
(15, 'App\\Models\\User', 21),
(17, 'App\\Models\\User', 21),
(18, 'App\\Models\\User', 21),
(28, 'App\\Models\\User', 21),
(14, 'App\\Models\\User', 22),
(15, 'App\\Models\\User', 22),
(17, 'App\\Models\\User', 22),
(18, 'App\\Models\\User', 22),
(28, 'App\\Models\\User', 22);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'web', '2020-08-18 15:41:49', '2020-08-18 15:41:49'),
(2, 'warehouse', 'web', '2020-08-18 15:41:59', '2020-08-18 15:41:59'),
(3, 'bank_and_account', 'web', '2020-08-18 15:42:10', '2020-08-18 15:42:10'),
(4, 'bank', 'web', '2020-08-18 15:42:21', '2020-08-18 15:42:21'),
(5, 'branch', 'web', '2020-08-18 15:42:29', '2020-08-18 15:42:29'),
(6, 'account', 'web', '2020-08-18 15:43:01', '2020-08-18 15:43:01'),
(7, 'purchase', 'web', '2020-08-18 15:43:11', '2020-08-18 15:43:11'),
(8, 'supplier', 'web', '2020-08-18 15:43:29', '2020-08-18 15:43:29'),
(9, 'purchase_product', 'web', '2020-08-18 15:43:36', '2020-08-18 15:43:36'),
(10, 'purchase_order', 'web', '2020-08-18 15:43:53', '2020-08-18 15:43:53'),
(11, 'purchase_receipt', 'web', '2020-08-18 15:44:02', '2020-08-18 15:44:02'),
(12, 'purchase_inventory', 'web', '2020-08-18 15:44:11', '2020-08-18 15:44:11'),
(13, 'supplier_payment', 'web', '2020-08-18 15:44:31', '2020-08-18 15:44:31'),
(14, 'sale', 'web', '2020-08-18 15:44:41', '2020-08-18 15:44:41'),
(15, 'customer', 'web', '2020-08-18 15:44:49', '2020-08-18 15:44:49'),
(16, 'sale_product', 'web', '2020-08-18 15:45:05', '2020-08-18 15:45:05'),
(17, 'sales_order', 'web', '2020-08-18 15:45:13', '2020-08-18 15:45:13'),
(18, 'sale_receipt', 'web', '2020-08-18 15:45:24', '2020-08-18 15:45:24'),
(19, 'product_sale_information', 'web', '2020-08-18 15:45:46', '2020-08-18 15:45:46'),
(20, 'customer_payment', 'web', '2020-08-18 15:45:54', '2020-08-18 15:45:54'),
(21, 'accounts', 'web', '2020-08-18 15:46:04', '2020-08-18 15:46:04'),
(22, 'account_head_type', 'web', '2020-08-18 15:46:11', '2020-08-18 15:46:11'),
(23, 'account_head_sub_type', 'web', '2020-08-18 15:46:19', '2020-08-18 15:46:19'),
(24, 'transaction', 'web', '2020-08-18 15:46:27', '2020-08-18 15:46:27'),
(25, 'balance_transfer', 'web', '2020-08-18 15:46:34', '2020-08-18 15:46:34'),
(26, 'report', 'web', '2020-08-18 15:46:42', '2020-08-18 15:46:42'),
(27, 'purchase_report', 'web', '2020-08-18 15:46:50', '2020-08-18 15:46:50'),
(28, 'sale_report', 'web', '2020-08-18 15:46:58', '2020-08-18 15:46:58'),
(29, 'user_management', 'web', '2020-08-18 15:47:17', '2020-08-18 15:47:17'),
(30, 'users', 'web', '2020-08-18 15:47:24', '2020-08-18 15:47:24'),
(31, 'balance_sheet', 'web', '2020-08-19 14:44:58', '2020-08-19 14:44:58'),
(32, 'profit_and_loss', 'web', '2020-08-19 14:45:12', '2020-08-19 14:45:12'),
(33, 'ledger', 'web', '2020-08-19 14:45:24', '2020-08-19 14:45:24'),
(34, 'transaction_report', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(35, 'purchase_product_category', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(36, 'purchase_product_sub_category', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(37, 'receive_and_payment_report', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(38, 'bank_statement_report', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(39, 'wastage', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(40, 'wastage_order', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(41, 'wastage_report', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54'),
(42, 'edit_purchase_order', 'web', '2020-09-02 11:23:54', '2020-09-02 11:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_type_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `product_type_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Itel', 1, NULL, '2022-10-19 08:45:06'),
(2, 1, 'Sprint', 1, '2022-10-19 08:41:26', '2022-10-19 08:41:26'),
(3, 1, 'Samsung', 1, '2022-10-19 08:43:29', '2022-12-15 10:51:14'),
(4, 2, 'Itel', 1, '2022-10-19 10:07:19', '2022-10-19 10:07:19'),
(7, 2, 'Benco', 1, '2022-12-21 11:17:52', '2022-12-21 11:17:52'),
(8, 2, 'Walton', 1, '2022-12-30 08:11:26', '2022-12-30 08:11:26'),
(9, 2, 'GDL', 1, '2022-12-30 08:11:40', '2022-12-30 08:11:40'),
(10, 2, '5 Star', 1, '2022-12-30 08:12:27', '2022-12-30 08:12:27'),
(11, 2, 'Bengal', 1, '2022-12-30 08:12:38', '2022-12-30 08:12:38'),
(12, 1, 'Realme', 1, '2022-12-30 08:23:06', '2022-12-30 08:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_colors`
--

INSERT INTO `product_colors` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'City Blue', 1, '2022-10-19 05:07:09', '2023-01-07 09:26:43'),
(2, 'White', 1, '2022-10-19 05:05:03', '2022-10-19 05:05:03'),
(3, 'Black', 1, '2022-10-19 04:05:03', '2022-10-19 04:05:03'),
(4, 'Green', 1, '2022-10-19 08:05:03', '2022-10-19 08:08:18'),
(5, 'Black & White', 1, '2022-10-19 08:08:59', '2022-10-19 08:08:59'),
(6, 'Black & White', 1, '2022-12-31 09:27:24', '2022-12-31 09:27:38'),
(7, 'Yellow', 1, '2023-01-07 09:25:51', '2023-01-07 09:25:51'),
(8, 'Aurora Green + Black', 1, '2023-01-07 09:26:20', '2023-01-07 09:26:20'),
(9, 'Blue', 1, '2023-01-07 09:26:53', '2023-01-07 09:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `product_models`
--

CREATE TABLE `product_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_type_id` int(10) UNSIGNED NOT NULL,
  `product_brand_id` int(10) UNSIGNED NOT NULL,
  `product_color_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_price` double(20,2) NOT NULL,
  `selling_price` double(20,2) NOT NULL,
  `model_discount` int(11) DEFAULT NULL COMMENT 'amount',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_models`
--

INSERT INTO `product_models` (`id`, `product_type_id`, `product_brand_id`, `product_color_id`, `name`, `purchase_price`, `selling_price`, `model_discount`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 3, 'I-02', 12000.00, 14000.00, 0, '66249586', 1, '2022-10-25 09:40:12', '2022-12-30 05:07:06'),
(2, 1, 1, 1, 'Itel M6', 5000.00, 6000.00, 0, '66249585', 1, '2022-10-19 09:08:38', '2022-12-30 05:08:58'),
(4, 1, 2, 3, 'S-005', 10000.00, 12000.00, 0, '68980849', 1, '2022-10-19 10:01:48', '2022-12-20 11:03:32'),
(5, 1, 2, NULL, 'Sprint S4', 14000.00, 16000.00, 0, '49483180', 1, '2022-11-12 09:01:29', '2022-11-24 04:07:50'),
(7, 2, 4, NULL, 'it5027', 1260.00, 1290.00, 0, '68271789', 1, '2022-11-24 04:06:06', '2022-12-30 08:43:53'),
(8, 2, 4, NULL, 'it 2163', 970.00, 990.00, 0, '67286237', 1, '2022-12-14 09:48:26', '2022-12-30 08:43:03'),
(9, 1, 3, NULL, 'Samsung S4', 18000.00, 20000.00, 0, '99668330', 1, '2022-12-18 04:05:12', '2022-12-20 11:06:29'),
(10, 2, 7, NULL, 'E5B', 750.00, 850.00, 0, '93677463', 1, '2022-12-21 11:18:37', '2022-12-29 12:19:45'),
(11, 2, 8, NULL, 'L28s', 1000.00, 1030.00, 0, '12346748', 1, '2022-12-30 08:13:18', '2022-12-30 08:13:18'),
(12, 2, 9, NULL, 'G301', 1040.00, 1070.00, 0, '77400606', 1, '2022-12-30 08:14:04', '2022-12-30 08:14:04'),
(13, 2, 9, NULL, 'G601', 1300.00, 1330.00, 0, '40201075', 1, '2022-12-30 08:14:26', '2022-12-30 08:14:26'),
(14, 2, 8, NULL, 'i100', 900.00, 930.00, 0, '68481194', 1, '2022-12-30 08:14:50', '2022-12-30 08:14:50'),
(15, 2, 7, NULL, 'P11', 1120.00, 1150.00, 0, '93537743', 1, '2022-12-30 08:15:32', '2022-12-30 08:15:32'),
(16, 2, 4, NULL, 'it2171', 990.00, 1020.00, 0, '46313190', 1, '2022-12-30 08:16:56', '2022-12-30 08:16:56'),
(17, 2, 4, NULL, 'it2173', 1050.00, 1090.00, 0, '29272946', 1, '2022-12-30 08:17:26', '2022-12-30 08:17:26'),
(18, 1, 12, NULL, 'C30 (2+32)', 9300.00, 9500.00, 0, '81096637', 1, '2022-12-30 08:24:03', '2023-09-18 11:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_model_sales_order`
--

CREATE TABLE `product_model_sales_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_brand_id` int(11) DEFAULT NULL,
  `product_model_id` int(11) DEFAULT NULL,
  `product_color_id` int(11) DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double(8,2) NOT NULL,
  `unit_price` double(20,2) NOT NULL,
  `total` double(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_model_sales_order`
--

INSERT INTO `product_model_sales_order` (`id`, `sales_order_id`, `name`, `product_brand_id`, `product_model_id`, `product_color_id`, `serial`, `quantity`, `unit_price`, `total`) VALUES
(1, 1, 'Itel M6', 1, 2, 3, 'Imei723809s', 1.00, 7980.00, 7980.00),
(2, 1, 'S-005', 2, 4, 3, 'Imeisp61289', 1.00, 12000.00, 12000.00),
(3, 2, 'Itel B1', 4, 7, 1, '896543', 1.00, 900.00, 900.00),
(4, 3, 'Itel M6', 1, 2, 3, 'Imei328947s', 1.00, 6000.00, 6000.00),
(5, 4, 'E5B', 7, 10, 2, 'Imei2372955b', 1.00, 850.00, 850.00),
(6, 5, 'Samsung S4', 3, 9, 1, 'Imeis6782339127', 1.00, 20000.00, 20000.00),
(7, 6, 'E5B', 7, 10, 3, '865785052252780', 1.00, 850.00, 850.00),
(8, 7, 'Samsung S4', 3, 9, 1, 'Imeis7826274', 1.00, 20000.00, 20000.00),
(9, 8, 'Itel M6', 1, 2, 1, '12345678', 1.00, 7000.00, 7000.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `imei` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `imei`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Smart Phone', 1, '2022-10-17 13:03:12', '2022-10-24 07:31:51'),
(2, 1, 'Bar Phone', 1, '2022-10-18 03:48:29', '2022-10-18 03:48:29'),
(3, 1, 'ITEL MOBILE-2171', 0, '2023-01-14 09:02:40', '2023-01-14 09:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_inventories`
--

CREATE TABLE `purchase_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_type_id` bigint(20) UNSIGNED NOT NULL,
  `product_brand_id` bigint(20) DEFAULT NULL,
  `product_color_id` bigint(20) DEFAULT NULL,
  `product_model_id` bigint(20) DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `shop_id` int(10) NOT NULL DEFAULT '0',
  `stock_transfer_order_id` bigint(20) NOT NULL DEFAULT '0',
  `quantity` double(20,2) NOT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_unit_price` double(8,2) NOT NULL,
  `selling_price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_inventories`
--

INSERT INTO `purchase_inventories` (`id`, `product_type_id`, `product_brand_id`, `product_color_id`, `product_model_id`, `warehouse_id`, `shop_id`, `stock_transfer_order_id`, `quantity`, `serial_no`, `last_unit_price`, `selling_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 2, 1, 0, 0, 1.00, 'Imei78349s', 5000.00, 6000.00, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(2, 1, 1, 3, 2, 1, 0, 0, 1.00, 'Imei789238s', 5000.00, 6000.00, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(3, 1, 1, 3, 2, 1, 0, 0, 0.00, 'Imei328947s', 6000.00, 8000.00, '2022-12-29 08:20:18', '2022-12-29 10:53:27'),
(4, 1, 1, 3, 2, 1, 0, 0, 1.00, 'Imei006629s', 5000.00, 6000.00, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(5, 1, 1, 3, 2, 1, 0, 0, 0.00, 'Imei723809s', 6000.00, 8000.00, '2022-12-29 08:20:18', '2022-12-29 10:53:27'),
(6, 2, 7, 2, 10, 1, 0, 0, 1.00, 'Imei82390b', 750.00, 850.00, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(7, 2, 7, 2, 10, 1, 0, 0, 1.00, 'Imei628364b', 750.00, 850.00, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(8, 2, 7, 2, 10, 1, 0, 0, 0.00, 'Imei823702b', 750.00, 850.00, '2022-12-29 08:20:18', '2022-12-30 05:21:23'),
(9, 2, 7, 2, 10, 1, 0, 0, 1.00, 'Imei7232922b', 750.00, 850.00, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(10, 2, 7, 2, 10, 1, 0, 0, 0.00, 'Imei2372955b', 800.00, 900.00, '2022-12-29 08:20:18', '2022-12-29 10:53:27'),
(11, 1, 3, 1, 9, 1, 0, 0, 0.00, 'Imeis6782339127', 18000.00, 20000.00, '2022-12-29 08:25:12', '2023-01-05 09:01:28'),
(12, 1, 3, 1, 9, 1, 0, 0, 0.00, 'Imeis7826274', 18000.00, 20000.00, '2022-12-29 08:25:12', '2022-12-29 10:53:27'),
(13, 1, 3, 1, 9, 1, 0, 0, 0.00, 'Imeis78161', 18000.00, 20000.00, '2022-12-29 08:25:12', '2023-01-05 09:01:28'),
(14, 1, 2, 3, 4, 1, 0, 0, 1.00, 'Imeisp76891', 10000.00, 12000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(15, 1, 2, 3, 4, 1, 0, 0, 0.00, 'Imeisp61289', 10000.00, 12000.00, '2022-12-29 08:25:12', '2022-12-29 10:53:27'),
(16, 1, 3, 1, 9, 1, 1, 1, 0.00, 'Imeis7826274', 18000.00, 20000.00, '2022-12-29 10:53:27', '2023-05-19 08:38:17'),
(17, 1, 1, 3, 2, 1, 1, 1, 0.00, 'Imei723809s', 6000.00, 8000.00, '2022-12-29 10:53:27', '2022-12-29 11:34:09'),
(18, 2, 7, 2, 10, 1, 1, 1, 0.00, 'Imei2372955b', 750.00, 850.00, '2022-12-29 10:53:27', '2023-01-04 09:37:44'),
(19, 1, 2, 3, 4, 1, 1, 1, 0.00, 'Imeisp61289', 10000.00, 12000.00, '2022-12-29 10:53:27', '2022-12-29 11:34:09'),
(20, 1, 1, 3, 2, 1, 1, 1, 0.00, 'Imei328947s', 5000.00, 6000.00, '2022-12-29 10:53:27', '2022-12-30 05:53:27'),
(21, 2, 4, 1, 7, 1, 0, 0, 1.00, '789654', 800.00, 900.00, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(22, 2, 4, 1, 7, 1, 0, 0, 1.00, '869303', 800.00, 900.00, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(23, 2, 4, 1, 7, 1, 0, 0, 1.00, '896543', 800.00, 900.00, '2022-12-30 05:12:36', '2022-12-30 05:21:23'),
(25, 2, 7, 2, 10, 1, 1, 2, 1.00, 'Imei823702b', 750.00, 850.00, '2022-12-30 05:21:23', '2022-12-30 05:21:23'),
(26, 2, 4, 1, 8, 1, 0, 0, 1.00, 'Imei007324\r', 970.00, 990.00, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(27, 2, 4, 1, 8, 1, 0, 0, 1.00, '45678923923', 970.00, 990.00, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(28, 1, 3, 1, 9, 1, 1, 3, 1.00, 'Imeis78161', 18000.00, 20000.00, '2023-01-05 09:01:28', '2023-01-05 09:01:28'),
(29, 1, 3, 1, 9, 1, 1, 3, 0.00, 'Imeis6782339127', 18000.00, 20000.00, '2023-01-05 09:01:28', '2023-01-05 09:05:27'),
(30, 1, 12, 6, 18, 1, 0, 0, 1.00, '1234567890765', 9300.00, 9500.00, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(31, 1, 12, 6, 18, 1, 0, 0, 1.00, '76543282345666778987', 9300.00, 9500.00, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(32, 1, 12, 6, 18, 1, 0, 0, 1.00, '12345670987234523', 9300.00, 9500.00, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(33, 1, 12, 6, 18, 1, 0, 0, 1.00, '56789023403777773', 9300.00, 9500.00, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(34, 2, 7, 6, 10, 1, 0, 0, 1.00, '92988888202938', 750.00, 850.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(35, 2, 7, 6, 10, 1, 0, 0, 1.00, '078912091278191893729274928472927', 750.00, 850.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(36, 2, 7, 6, 10, 1, 0, 0, 1.00, '7722284392749202', 750.00, 850.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(37, 2, 7, 6, 10, 1, 0, 0, 1.00, '666666663927402', 750.00, 850.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(38, 2, 7, 9, 15, 1, 0, 0, 0.00, '860823060829789', 1120.00, 1150.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(39, 2, 7, 9, 15, 1, 0, 0, 0.00, '860823060830084', 1120.00, 1150.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(40, 2, 7, 9, 15, 1, 0, 0, 0.00, '860823060820267', 1120.00, 1150.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(41, 2, 7, 3, 10, 1, 0, 0, 0.00, '865785052252640', 750.00, 850.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(42, 2, 7, 3, 10, 1, 0, 0, 0.00, '865785052355765', 750.00, 850.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(43, 2, 7, 3, 10, 1, 0, 0, 0.00, '865785052251923', 750.00, 850.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(44, 2, 7, 3, 10, 1, 0, 0, 0.00, '865785052252780', 750.00, 850.00, '2023-01-07 09:34:37', '2023-01-07 09:54:00'),
(45, 2, 4, 1, 16, 1, 0, 0, 0.00, '354502593481028', 990.00, 1020.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(46, 2, 4, 1, 16, 1, 0, 0, 0.00, '354502593146266', 990.00, 1020.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(47, 2, 4, 1, 16, 1, 0, 0, 0.00, '354502593144881', 990.00, 1020.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(48, 2, 4, 1, 16, 1, 0, 0, 0.00, '354502591890667', 990.00, 1020.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(49, 2, 4, 3, 7, 1, 0, 0, 0.00, '351962930101320', 1260.00, 1290.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(50, 2, 4, 3, 7, 1, 0, 0, 0.00, '351962930099367', 1260.00, 1290.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(51, 2, 4, 3, 7, 1, 0, 0, 0.00, '351962930101346', 1260.00, 1290.00, '2023-01-07 09:36:22', '2023-01-07 09:54:00'),
(52, 2, 9, 3, 12, 1, 0, 0, 0.00, '359945042616568', 1040.00, 1070.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(53, 2, 9, 4, 12, 1, 0, 0, 0.00, '359945043444127', 1040.00, 1070.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(54, 2, 9, 7, 13, 1, 0, 0, 0.00, '359947044378221', 1300.00, 1330.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(55, 2, 9, 7, 13, 1, 0, 0, 0.00, '359947044378569', 1300.00, 1330.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(56, 2, 9, 4, 13, 1, 0, 0, 0.00, '359947042602424', 1300.00, 1330.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(57, 2, 9, 2, 13, 1, 0, 0, 0.00, '359947044706728', 1300.00, 1330.00, '2023-01-07 09:41:33', '2023-01-07 09:54:00'),
(58, 2, 8, 8, 14, 1, 0, 0, 0.00, '351600342760479', 900.00, 930.00, '2023-01-07 09:43:29', '2023-01-07 09:54:00'),
(59, 2, 8, 8, 14, 1, 0, 0, 0.00, '351600342769975', 900.00, 930.00, '2023-01-07 09:43:29', '2023-01-07 09:54:00'),
(60, 2, 8, 8, 14, 1, 0, 0, 0.00, '351600342730670', 900.00, 930.00, '2023-01-07 09:43:29', '2023-01-07 09:54:00'),
(61, 2, 8, 8, 14, 1, 0, 0, 0.00, '351600342758895', 900.00, 930.00, '2023-01-07 09:43:29', '2023-01-07 09:54:00'),
(62, 2, 8, 8, 14, 1, 1, 4, 1.00, '351600342758895', 900.00, 930.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(63, 2, 8, 8, 14, 1, 1, 4, 1.00, '351600342730670', 900.00, 930.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(64, 2, 8, 8, 14, 1, 1, 4, 1.00, '351600342769975', 900.00, 930.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(65, 2, 8, 8, 14, 1, 1, 4, 1.00, '351600342760479', 900.00, 930.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(66, 2, 9, 4, 13, 1, 1, 4, 1.00, '359947042602424', 1300.00, 1330.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(67, 2, 9, 2, 13, 1, 1, 4, 1.00, '359947044706728', 1300.00, 1330.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(68, 2, 9, 7, 13, 1, 1, 4, 1.00, '359947044378221', 1300.00, 1330.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(69, 2, 9, 7, 13, 1, 1, 4, 1.00, '359947044378569', 1300.00, 1330.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(70, 2, 9, 4, 12, 1, 1, 4, 1.00, '359945043444127', 1040.00, 1070.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(71, 2, 9, 3, 12, 1, 1, 4, 1.00, '359945042616568', 1040.00, 1070.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(72, 2, 4, 3, 7, 1, 1, 4, 1.00, '351962930101320', 1260.00, 1290.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(73, 2, 4, 3, 7, 1, 1, 4, 1.00, '351962930099367', 1260.00, 1290.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(74, 2, 4, 3, 7, 1, 1, 4, 1.00, '351962930101346', 1260.00, 1290.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(75, 2, 4, 1, 16, 1, 1, 4, 1.00, '354502591890667', 990.00, 1020.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(76, 2, 4, 1, 16, 1, 1, 4, 1.00, '354502593144881', 990.00, 1020.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(77, 2, 4, 1, 16, 1, 1, 4, 1.00, '354502593146266', 990.00, 1020.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(78, 2, 4, 1, 16, 1, 1, 4, 1.00, '354502593481028', 990.00, 1020.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(79, 2, 7, 3, 10, 1, 1, 4, 0.00, '865785052252780', 750.00, 850.00, '2023-01-07 09:54:00', '2023-01-07 10:37:30'),
(80, 2, 7, 3, 10, 1, 1, 4, 1.00, '865785052251923', 750.00, 850.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(81, 2, 7, 3, 10, 1, 1, 4, 1.00, '865785052355765', 750.00, 850.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(82, 2, 7, 3, 10, 1, 1, 4, 1.00, '865785052252640', 750.00, 850.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(83, 2, 7, 9, 15, 1, 1, 4, 1.00, '860823060820267', 1120.00, 1150.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(84, 2, 7, 9, 15, 1, 1, 4, 1.00, '860823060830084', 1120.00, 1150.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(85, 2, 7, 9, 15, 1, 1, 4, 1.00, '860823060829789', 1120.00, 1150.00, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(86, 1, 1, 1, 2, 1, 0, 0, 1.00, 'Imei789234e', 5000.00, 6000.00, '2023-04-29 08:58:24', '2023-04-29 08:58:24'),
(87, 1, 12, 2, 18, 1, 0, 0, 1.00, 'Imei328943c', 9300.00, 9500.00, '2023-04-29 10:38:11', '2023-09-18 11:35:26'),
(88, 1, 1, 1, 2, 1, 0, 0, 0.00, '12345678', 5000.00, 6000.00, '2023-05-19 08:42:38', '2023-05-19 08:47:36'),
(89, 1, 1, 1, 2, 1, 1, 5, 0.00, '12345678', 5000.00, 6000.00, '2023-05-19 08:47:36', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_inventory_logs`
--

CREATE TABLE `purchase_inventory_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type_id` bigint(20) UNSIGNED NOT NULL,
  `product_brand_id` bigint(1) DEFAULT NULL,
  `product_color_id` bigint(20) DEFAULT NULL,
  `product_model_id` bigint(20) DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=In; 2=Out',
  `date` date NOT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `shop_id` int(10) NOT NULL DEFAULT '0',
  `quantity` double(20,2) NOT NULL,
  `unit_price` double(20,2) DEFAULT NULL,
  `selling_price` double(20,2) DEFAULT NULL,
  `purchase_order_id` bigint(20) DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_order_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `purchase_inventory_id` bigint(20) DEFAULT NULL,
  `sr_product_assign_order_id` int(11) DEFAULT NULL,
  `sr_product_assign_order_item_id` int(11) DEFAULT NULL,
  `stock_transfer_order_id` bigint(20) DEFAULT NULL,
  `stock_transfer_status` tinyint(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_inventory_logs`
--

INSERT INTO `purchase_inventory_logs` (`id`, `serial_no`, `product_type_id`, `product_brand_id`, `product_color_id`, `product_model_id`, `type`, `date`, `warehouse_id`, `shop_id`, `quantity`, `unit_price`, `selling_price`, `purchase_order_id`, `supplier_id`, `sales_order_id`, `customer_id`, `purchase_inventory_id`, `sr_product_assign_order_id`, `sr_product_assign_order_item_id`, `stock_transfer_order_id`, `stock_transfer_status`, `user_id`, `note`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Imei78349s', 1, 1, 3, 2, 1, '2022-12-29', 1, 0, 1.00, 5000.00, 6000.00, 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(2, 'Imei789238s', 1, 1, 3, 2, 1, '2022-12-29', 1, 0, 1.00, 5000.00, 6000.00, 1, 1, NULL, NULL, 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(3, 'Imei328947s', 1, 1, 3, 2, 1, '2022-12-29', 1, 0, 1.00, 5000.00, 6000.00, 1, 1, NULL, NULL, 3, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(4, 'Imei006629s', 1, 1, 3, 2, 1, '2022-12-29', 1, 0, 1.00, 5000.00, 6000.00, 1, 1, NULL, NULL, 4, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(5, 'Imei723809s', 1, 1, 3, 2, 1, '2022-12-29', 1, 0, 1.00, 5000.00, 6000.00, 1, 1, NULL, NULL, 5, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-30 05:08:58'),
(6, 'Imei82390b', 2, 7, 2, 10, 1, '2022-12-29', 1, 0, 1.00, 750.00, 850.00, 1, 1, NULL, NULL, 6, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(7, 'Imei628364b', 2, 7, 2, 10, 1, '2022-12-29', 1, 0, 1.00, 750.00, 850.00, 1, 1, NULL, NULL, 7, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(8, 'Imei823702b', 2, 7, 2, 10, 1, '2022-12-29', 1, 0, 1.00, 750.00, 850.00, 1, 1, NULL, NULL, 8, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(9, 'Imei7232922b', 2, 7, 2, 10, 1, '2022-12-29', 1, 0, 1.00, 750.00, 850.00, 1, 1, NULL, NULL, 9, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(10, 'Imei2372955b', 2, 7, 2, 10, 1, '2022-12-29', 1, 0, 1.00, 750.00, 850.00, 1, 1, NULL, NULL, 10, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:20:18', '2022-12-29 12:19:45'),
(11, 'Imeis6782339127', 1, 3, 1, 9, 1, '2022-12-29', 1, 0, 1.00, 18000.00, 20000.00, 2, 2, NULL, NULL, 11, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(12, 'Imeis7826274', 1, 3, 1, 9, 1, '2022-12-29', 1, 0, 1.00, 18000.00, 20000.00, 2, 2, NULL, NULL, 12, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(13, 'Imeis78161', 1, 3, 1, 9, 1, '2022-12-29', 1, 0, 1.00, 18000.00, 20000.00, 2, 2, NULL, NULL, 13, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(14, 'Imeisp76891', 1, 2, 3, 4, 1, '2022-12-29', 1, 0, 1.00, 10000.00, 12000.00, 2, 2, NULL, NULL, 14, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(15, 'Imeisp61289', 1, 2, 3, 4, 1, '2022-12-29', 1, 0, 1.00, 10000.00, 12000.00, 2, 2, NULL, NULL, 15, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(16, 'Imeis7826274', 1, 3, 1, 9, 1, '2022-12-29', 1, 1, 1.00, NULL, 20000.00, 12, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, '2022-12-29 10:53:27', '2022-12-29 10:53:27'),
(17, 'Imei723809s', 1, 1, 3, 2, 1, '2022-12-29', 1, 1, 1.00, 5000.00, 6000.00, 5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, '2022-12-29 10:53:27', '2022-12-30 05:08:58'),
(18, 'Imei2372955b', 2, 7, 2, 10, 1, '2022-12-29', 1, 1, 1.00, 750.00, 850.00, 10, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, '2022-12-29 10:53:27', '2022-12-29 12:19:45'),
(19, 'Imeisp61289', 1, 2, 3, 4, 1, '2022-12-29', 1, 1, 1.00, NULL, 12000.00, 15, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, '2022-12-29 10:53:27', '2022-12-29 10:53:27'),
(20, 'Imei328947s', 1, 1, 3, 2, 1, '2022-12-29', 1, 1, 1.00, 5000.00, 6000.00, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, '2022-12-29 10:53:27', '2022-12-30 05:08:58'),
(21, 'Imei723809s', 1, 1, 3, 2, 2, '2022-12-29', 1, 1, 1.00, 7980.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2022-12-29 11:34:09', '2022-12-29 11:34:09'),
(22, 'Imeisp61289', 1, 2, 3, 4, 2, '2022-12-29', 1, 1, 1.00, 12000.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2022-12-29 11:34:09', '2022-12-29 11:34:09'),
(23, '789654\r', 2, 4, 1, 7, 1, '2022-12-30', 1, 0, 1.00, 800.00, 900.00, 3, 1, NULL, NULL, 21, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(24, '869303\r', 2, 4, 1, 7, 1, '2022-12-30', 1, 0, 1.00, 800.00, 900.00, 3, 1, NULL, NULL, 22, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(25, '896543', 2, 4, 1, 7, 1, '2022-12-30', 1, 0, 1.00, 800.00, 900.00, 3, 1, NULL, NULL, 23, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(26, '896543', 2, 4, 1, 7, 1, '2022-12-30', 1, 1, 1.00, NULL, 900.00, 23, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 1, NULL, NULL, '2022-12-30 05:21:23', '2022-12-30 05:21:23'),
(27, 'Imei823702b', 2, 7, 2, 10, 1, '2022-12-30', 1, 1, 1.00, NULL, 850.00, 8, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 1, NULL, NULL, '2022-12-30 05:21:23', '2022-12-30 05:21:23'),
(28, '896543', 2, 4, 1, 7, 2, '2022-12-30', 1, 1, 1.00, 900.00, NULL, NULL, NULL, 2, 2, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2022-12-30 05:26:05', '2022-12-30 05:26:05'),
(29, 'Imei328947s', 1, 1, 3, 2, 2, '2022-12-30', 1, 1, 1.00, 6000.00, NULL, NULL, NULL, 3, 2, NULL, 1, 1, NULL, 0, NULL, NULL, 6, '2022-12-30 05:53:27', '2022-12-30 05:53:27'),
(30, 'Imei007324\r', 2, 4, 1, 8, 1, '2022-12-31', 1, 0, 1.00, 970.00, 990.00, 4, 3, NULL, NULL, 26, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(31, '45678923923', 2, 4, 1, 8, 1, '2022-12-31', 1, 0, 1.00, 970.00, 990.00, 4, 3, NULL, NULL, 27, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(32, 'Imei2372955b', 2, 7, 2, 10, 2, '2023-01-04', 1, 1, 1.00, 850.00, NULL, NULL, NULL, 4, 1, NULL, 2, 2, NULL, 0, NULL, NULL, 6, '2023-01-04 09:37:44', '2023-01-04 09:37:44'),
(33, 'Imeis78161', 1, 3, 1, 9, 1, '2023-01-05', 1, 1, 1.00, NULL, 20000.00, 13, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 1, NULL, NULL, '2023-01-05 09:01:28', '2023-01-05 09:01:28'),
(34, 'Imeis6782339127', 1, 3, 1, 9, 1, '2023-01-05', 1, 1, 1.00, NULL, 20000.00, 11, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 1, NULL, NULL, '2023-01-05 09:01:28', '2023-01-05 09:01:28'),
(35, 'Imeis6782339127', 1, 3, 1, 9, 2, '2023-01-05', 1, 1, 1.00, 20000.00, NULL, NULL, NULL, 5, 3, NULL, 3, 3, NULL, 0, NULL, NULL, 3, '2023-01-05 09:05:27', '2023-01-05 09:05:27'),
(36, '1234567890765', 1, 12, 6, 18, 1, '2023-01-05', 1, 0, 1.00, 9300.00, 9500.00, 5, 3, NULL, NULL, 30, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(37, '76543282345666778987', 1, 12, 6, 18, 1, '2023-01-05', 1, 0, 1.00, 9300.00, 9500.00, 5, 3, NULL, NULL, 31, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(38, '12345670987234523', 1, 12, 6, 18, 1, '2023-01-05', 1, 0, 1.00, 9300.00, 9500.00, 5, 3, NULL, NULL, 32, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(39, '56789023403777773', 1, 12, 6, 18, 1, '2023-01-05', 1, 0, 1.00, 9300.00, 9500.00, 5, 3, NULL, NULL, 33, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:21:27', '2023-09-18 11:35:26'),
(40, '92988888202938', 2, 7, 6, 10, 1, '2023-01-05', 1, 0, 1.00, 750.00, 850.00, 6, 4, NULL, NULL, 34, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(41, '078912091278191893729274928472927', 2, 7, 6, 10, 1, '2023-01-05', 1, 0, 1.00, 750.00, 850.00, 6, 4, NULL, NULL, 35, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(42, '7722284392749202', 2, 7, 6, 10, 1, '2023-01-05', 1, 0, 1.00, 750.00, 850.00, 6, 4, NULL, NULL, 36, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(43, '666666663927402', 2, 7, 6, 10, 1, '2023-01-05', 1, 0, 1.00, 750.00, 850.00, 6, 4, NULL, NULL, 37, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(44, '860823060829789', 2, 7, 9, 15, 1, '2023-01-07', 1, 0, 1.00, 1120.00, 1150.00, 7, 2, NULL, NULL, 38, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(45, '860823060830084', 2, 7, 9, 15, 1, '2023-01-07', 1, 0, 1.00, 1120.00, 1150.00, 7, 2, NULL, NULL, 39, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(46, '860823060820267', 2, 7, 9, 15, 1, '2023-01-07', 1, 0, 1.00, 1120.00, 1150.00, 7, 2, NULL, NULL, 40, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(47, '865785052252640', 2, 7, 3, 10, 1, '2023-01-07', 1, 0, 1.00, 750.00, 850.00, 7, 2, NULL, NULL, 41, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(48, '865785052355765', 2, 7, 3, 10, 1, '2023-01-07', 1, 0, 1.00, 750.00, 850.00, 7, 2, NULL, NULL, 42, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(49, '865785052251923', 2, 7, 3, 10, 1, '2023-01-07', 1, 0, 1.00, 750.00, 850.00, 7, 2, NULL, NULL, 43, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(50, '865785052252780', 2, 7, 3, 10, 1, '2023-01-07', 1, 0, 1.00, 750.00, 850.00, 7, 2, NULL, NULL, 44, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(51, '354502593481028', 2, 4, 1, 16, 1, '2023-01-07', 1, 0, 1.00, 990.00, 1020.00, 8, 5, NULL, NULL, 45, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(52, '354502593146266', 2, 4, 1, 16, 1, '2023-01-07', 1, 0, 1.00, 990.00, 1020.00, 8, 5, NULL, NULL, 46, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(53, '354502593144881', 2, 4, 1, 16, 1, '2023-01-07', 1, 0, 1.00, 990.00, 1020.00, 8, 5, NULL, NULL, 47, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(54, '354502591890667', 2, 4, 1, 16, 1, '2023-01-07', 1, 0, 1.00, 990.00, 1020.00, 8, 5, NULL, NULL, 48, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(55, '351962930101320', 2, 4, 3, 7, 1, '2023-01-07', 1, 0, 1.00, 1260.00, 1290.00, 8, 5, NULL, NULL, 49, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(56, '351962930099367', 2, 4, 3, 7, 1, '2023-01-07', 1, 0, 1.00, 1260.00, 1290.00, 8, 5, NULL, NULL, 50, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(57, '351962930101346', 2, 4, 3, 7, 1, '2023-01-07', 1, 0, 1.00, 1260.00, 1290.00, 8, 5, NULL, NULL, 51, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(58, '359945042616568', 2, 9, 3, 12, 1, '2023-01-07', 1, 0, 1.00, 1040.00, 1070.00, 9, 4, NULL, NULL, 52, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(59, '359945043444127', 2, 9, 4, 12, 1, '2023-01-07', 1, 0, 1.00, 1040.00, 1070.00, 9, 4, NULL, NULL, 53, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(60, '359947044378221', 2, 9, 7, 13, 1, '2023-01-07', 1, 0, 1.00, 1300.00, 1330.00, 9, 4, NULL, NULL, 54, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(61, '359947044378569', 2, 9, 7, 13, 1, '2023-01-07', 1, 0, 1.00, 1300.00, 1330.00, 9, 4, NULL, NULL, 55, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(62, '359947042602424', 2, 9, 4, 13, 1, '2023-01-07', 1, 0, 1.00, 1300.00, 1330.00, 9, 4, NULL, NULL, 56, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(63, '359947044706728', 2, 9, 2, 13, 1, '2023-01-07', 1, 0, 1.00, 1300.00, 1330.00, 9, 4, NULL, NULL, 57, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(64, '351600342760479', 2, 8, 8, 14, 1, '2023-01-07', 1, 0, 1.00, 900.00, 930.00, 10, 1, NULL, NULL, 58, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(65, '351600342769975', 2, 8, 8, 14, 1, '2023-01-07', 1, 0, 1.00, 900.00, 930.00, 10, 1, NULL, NULL, 59, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(66, '351600342730670', 2, 8, 8, 14, 1, '2023-01-07', 1, 0, 1.00, 900.00, 930.00, 10, 1, NULL, NULL, 60, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(67, '351600342758895', 2, 8, 8, 14, 1, '2023-01-07', 1, 0, 1.00, 900.00, 930.00, 10, 1, NULL, NULL, 61, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(68, '351600342758895', 2, 8, 8, 14, 1, '2023-01-07', 1, 1, 1.00, NULL, 930.00, 61, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(69, '351600342730670', 2, 8, 8, 14, 1, '2023-01-07', 1, 1, 1.00, NULL, 930.00, 60, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(70, '351600342769975', 2, 8, 8, 14, 1, '2023-01-07', 1, 1, 1.00, NULL, 930.00, 59, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(71, '351600342760479', 2, 8, 8, 14, 1, '2023-01-07', 1, 1, 1.00, NULL, 930.00, 58, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(72, '359947042602424', 2, 9, 4, 13, 1, '2023-01-07', 1, 1, 1.00, NULL, 1330.00, 56, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(73, '359947044706728', 2, 9, 2, 13, 1, '2023-01-07', 1, 1, 1.00, NULL, 1330.00, 57, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(74, '359947044378221', 2, 9, 7, 13, 1, '2023-01-07', 1, 1, 1.00, NULL, 1330.00, 54, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(75, '359947044378569', 2, 9, 7, 13, 1, '2023-01-07', 1, 1, 1.00, NULL, 1330.00, 55, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(76, '359945043444127', 2, 9, 4, 12, 1, '2023-01-07', 1, 1, 1.00, NULL, 1070.00, 53, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(77, '359945042616568', 2, 9, 3, 12, 1, '2023-01-07', 1, 1, 1.00, NULL, 1070.00, 52, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(78, '351962930101320', 2, 4, 3, 7, 1, '2023-01-07', 1, 1, 1.00, NULL, 1290.00, 49, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(79, '351962930099367', 2, 4, 3, 7, 1, '2023-01-07', 1, 1, 1.00, NULL, 1290.00, 50, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(80, '351962930101346', 2, 4, 3, 7, 1, '2023-01-07', 1, 1, 1.00, NULL, 1290.00, 51, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(81, '354502591890667', 2, 4, 1, 16, 1, '2023-01-07', 1, 1, 1.00, NULL, 1020.00, 48, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(82, '354502593144881', 2, 4, 1, 16, 1, '2023-01-07', 1, 1, 1.00, NULL, 1020.00, 47, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(83, '354502593146266', 2, 4, 1, 16, 1, '2023-01-07', 1, 1, 1.00, NULL, 1020.00, 46, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(84, '354502593481028', 2, 4, 1, 16, 1, '2023-01-07', 1, 1, 1.00, NULL, 1020.00, 45, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(85, '865785052252780', 2, 7, 3, 10, 1, '2023-01-07', 1, 1, 1.00, NULL, 850.00, 44, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(86, '865785052251923', 2, 7, 3, 10, 1, '2023-01-07', 1, 1, 1.00, NULL, 850.00, 43, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(87, '865785052355765', 2, 7, 3, 10, 1, '2023-01-07', 1, 1, 1.00, NULL, 850.00, 42, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(88, '865785052252640', 2, 7, 3, 10, 1, '2023-01-07', 1, 1, 1.00, NULL, 850.00, 41, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(89, '860823060820267', 2, 7, 9, 15, 1, '2023-01-07', 1, 1, 1.00, NULL, 1150.00, 40, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(90, '860823060830084', 2, 7, 9, 15, 1, '2023-01-07', 1, 1, 1.00, NULL, 1150.00, 39, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(91, '860823060829789', 2, 7, 9, 15, 1, '2023-01-07', 1, 1, 1.00, NULL, 1150.00, 38, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, NULL, NULL, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(92, '865785052252780', 2, 7, 3, 10, 2, '2023-01-07', 1, 1, 1.00, 850.00, NULL, NULL, NULL, 6, 3, NULL, 6, 9, NULL, 0, NULL, NULL, 3, '2023-01-07 10:37:30', '2023-01-07 10:37:30'),
(93, 'Imei789234e', 1, 1, 1, 2, 1, '2023-04-29', 1, 0, 1.00, 5000.00, 6000.00, 11, 4, NULL, NULL, 86, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-04-29 08:58:24', '2023-04-29 08:58:24'),
(94, 'Imei328943c', 1, 12, 2, 18, 1, '2023-04-29', 1, 0, 1.00, 9300.00, 9500.00, 12, 4, NULL, NULL, 87, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-04-29 10:38:11', '2023-09-18 11:35:26'),
(95, 'Imeis7826274', 1, 3, 1, 9, 2, '2023-05-19', 1, 1, 1.00, 20000.00, NULL, NULL, NULL, 7, 1, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2023-05-19 08:38:17', '2023-05-19 08:38:17'),
(96, '12345678', 1, 1, 1, 2, 1, '2023-05-19', 1, 0, 1.00, 5000.00, 6000.00, 13, 5, NULL, NULL, 88, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2023-05-19 08:42:38', '2023-05-19 08:42:38'),
(97, '12345678', 1, 1, 1, 2, 1, '2023-05-19', 1, 1, 1.00, NULL, 6000.00, 88, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 1, NULL, NULL, '2023-05-19 08:47:36', '2023-05-19 08:47:36'),
(98, '12345678', 1, 1, 1, 2, 2, '2023-05-19', 1, 1, 1.00, 7000.00, NULL, NULL, NULL, 8, 4, NULL, NULL, NULL, NULL, 0, NULL, NULL, 1, '2023-05-19 08:57:23', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `sub_total` double(20,2) NOT NULL,
  `discount` double(20,2) NOT NULL,
  `flat_discount` int(20) DEFAULT NULL,
  `total` double(20,2) NOT NULL,
  `paid` double(20,2) NOT NULL,
  `due` double(20,2) NOT NULL,
  `discount_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=percentage, 2=flat',
  `refund` double(8,2) NOT NULL DEFAULT '0.00',
  `back_margin` double(20,2) NOT NULL DEFAULT '0.00',
  `price_adjustment` double(20,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `order_no`, `supplier_id`, `warehouse_id`, `date`, `sub_total`, `discount`, `flat_discount`, `total`, `paid`, `due`, `discount_type`, `refund`, `back_margin`, `price_adjustment`, `created_at`, `updated_at`) VALUES
(1, '00000001', 1, 1, '2022-12-29', 34000.00, 0.00, 200, 33800.00, 23800.00, 10000.00, 2, 0.00, 0.00, 0.00, '2022-12-29 08:20:18', '2022-12-29 11:55:27'),
(2, '00000002', 2, 1, '2022-12-29', 74000.00, 0.00, 0, 74000.00, 73000.00, 1000.00, 0, 0.00, 0.00, 3000.00, '2022-12-29 08:25:12', '2023-04-30 09:28:18'),
(3, '00000003', 1, 1, '2022-12-30', 2400.00, 0.00, 0, 2400.00, 2000.00, 400.00, 0, 0.00, 0.00, 0.00, '2022-12-30 05:12:36', '2022-12-30 05:14:43'),
(4, '00000004', 3, 1, '2022-12-31', 1940.00, 0.00, 0, 1940.00, 0.00, 1940.00, 0, 0.00, 0.00, 0.00, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(5, '00000005', 3, 1, '2023-01-05', 37200.00, 0.00, 0, 37200.00, 30000.00, 7200.00, 0, 0.00, 0.00, 0.00, '2023-01-05 09:21:27', '2023-01-05 09:21:27'),
(6, '00000006', 4, 1, '2023-01-05', 3000.00, 0.00, 0, 3000.00, 0.00, 3000.00, 0, 0.00, 0.00, 0.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(7, '00000007', 2, 1, '2023-01-07', 6360.00, 0.00, 0, 6360.00, 360.00, 6000.00, 0, 0.00, 0.00, 0.00, '2023-01-07 09:34:37', '2023-04-29 12:17:01'),
(8, '00000008', 5, 1, '2023-01-07', 7740.00, 0.00, 0, 7740.00, 40.00, 7700.00, 0, 0.00, 20.00, 20.00, '2023-01-07 09:36:22', '2023-04-30 07:19:07'),
(9, '00000009', 4, 1, '2023-01-07', 7280.00, 0.00, 0, 7280.00, 280.00, 7000.00, 0, 0.00, 0.00, 0.00, '2023-01-07 09:41:33', '2023-04-29 12:27:43'),
(10, '00000010', 1, 1, '2023-01-07', 3600.00, 0.00, 0, 3600.00, 0.00, 3600.00, 0, 0.00, 0.00, 0.00, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(11, '00000011', 4, 1, '2023-04-29', 5000.00, 0.00, 0, 5000.00, 0.00, 5000.00, 0, 0.00, 0.00, 0.00, '2023-04-29 08:58:24', '2023-04-29 08:58:24'),
(12, '00000012', 4, 1, '2023-04-29', 9300.00, 0.00, 0, 9300.00, 300.00, 9000.00, 0, 0.00, 0.00, 0.00, '2023-04-29 10:38:11', '2023-04-29 10:38:11'),
(13, '00000013', 5, 1, '2023-05-19', 5000.00, 0.00, 0, 5000.00, 0.00, 5000.00, 0, 0.00, 0.00, 0.00, '2023-05-19 08:42:38', '2023-05-19 08:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_purchase_product`
--

CREATE TABLE `purchase_order_purchase_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_product_id` bigint(20) DEFAULT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `product_type_id` bigint(20) UNSIGNED NOT NULL,
  `product_brand_id` bigint(20) DEFAULT NULL,
  `product_color_id` bigint(20) DEFAULT NULL,
  `product_model_id` bigint(20) NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` double(8,2) NOT NULL,
  `unit_price` double(20,2) NOT NULL,
  `selling_price` double(20,2) NOT NULL,
  `total` double(20,2) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_purchase_product`
--

INSERT INTO `purchase_order_purchase_product` (`id`, `purchase_product_id`, `purchase_order_id`, `product_type_id`, `product_brand_id`, `product_color_id`, `product_model_id`, `warehouse_id`, `name`, `serial_no`, `quantity`, `unit_price`, `selling_price`, `total`, `updated_at`, `created_at`) VALUES
(1, NULL, 1, 1, 1, 3, 2, 1, 'Itel M6', 'Imei78349s', 1.00, 5000.00, 6000.00, 6000.00, '2022-12-30 05:08:58', '2022-12-29 08:20:18'),
(2, NULL, 1, 1, 1, 3, 2, 1, 'Itel M6', 'Imei789238s', 1.00, 5000.00, 6000.00, 6000.00, '2022-12-30 05:08:58', '2022-12-29 08:20:18'),
(3, NULL, 1, 1, 1, 3, 2, 1, 'Itel M6', 'Imei328947s', 1.00, 5000.00, 6000.00, 6000.00, '2022-12-30 05:08:58', '2022-12-29 08:20:18'),
(4, NULL, 1, 1, 1, 3, 2, 1, 'Itel M6', 'Imei006629s', 1.00, 5000.00, 6000.00, 6000.00, '2022-12-30 05:08:58', '2022-12-29 08:20:18'),
(5, NULL, 1, 1, 1, 3, 2, 1, 'Itel M6', 'Imei723809s', 1.00, 5000.00, 6000.00, 6000.00, '2022-12-30 05:08:58', '2022-12-29 08:20:18'),
(6, NULL, 1, 2, 7, 2, 10, 1, 'E5B', 'Imei82390b', 1.00, 750.00, 850.00, 800.00, '2022-12-29 12:19:45', '2022-12-29 08:20:18'),
(7, NULL, 1, 2, 7, 2, 10, 1, 'E5B', 'Imei628364b', 1.00, 750.00, 850.00, 800.00, '2022-12-29 12:19:45', '2022-12-29 08:20:18'),
(8, NULL, 1, 2, 7, 2, 10, 1, 'E5B', 'Imei823702b', 1.00, 750.00, 850.00, 800.00, '2022-12-29 12:19:45', '2022-12-29 08:20:18'),
(9, NULL, 1, 2, 7, 2, 10, 1, 'E5B', 'Imei7232922b', 1.00, 750.00, 850.00, 800.00, '2022-12-29 12:19:45', '2022-12-29 08:20:18'),
(10, NULL, 1, 2, 7, 2, 10, 1, 'E5B', 'Imei2372955b', 1.00, 750.00, 850.00, 800.00, '2022-12-29 12:19:45', '2022-12-29 08:20:18'),
(11, NULL, 2, 1, 3, 1, 9, 1, 'Samsung S4', 'Imeis6782339127', 1.00, 18000.00, 20000.00, 18000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(12, NULL, 2, 1, 3, 1, 9, 1, 'Samsung S4', 'Imeis7826274', 1.00, 18000.00, 20000.00, 18000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(13, NULL, 2, 1, 3, 1, 9, 1, 'Samsung S4', 'Imeis78161', 1.00, 18000.00, 20000.00, 18000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(14, NULL, 2, 1, 2, 3, 4, 1, 'S-005', 'Imeisp76891', 1.00, 10000.00, 12000.00, 10000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(15, NULL, 2, 1, 2, 3, 4, 1, 'S-005', 'Imeisp61289', 1.00, 10000.00, 12000.00, 10000.00, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(16, NULL, 3, 2, 4, 1, 7, 1, 'Itel B1', '789654\r', 1.00, 800.00, 900.00, 800.00, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(17, NULL, 3, 2, 4, 1, 7, 1, 'Itel B1', '869303\r', 1.00, 800.00, 900.00, 800.00, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(18, NULL, 3, 2, 4, 1, 7, 1, 'Itel B1', '896543', 1.00, 800.00, 900.00, 800.00, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(19, NULL, 4, 2, 4, 1, 8, 1, 'it 2163', 'Imei007324\r', 1.00, 970.00, 990.00, 970.00, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(20, NULL, 4, 2, 4, 1, 8, 1, 'it 2163', '45678923923', 1.00, 970.00, 990.00, 970.00, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(21, NULL, 5, 1, 12, 6, 18, 1, 'C30 (2+32)', '1234567890765', 1.00, 9300.00, 9500.00, 9300.00, '2023-09-18 11:35:26', '2023-01-05 09:21:27'),
(22, NULL, 5, 1, 12, 6, 18, 1, 'C30 (2+32)', '76543282345666778987', 1.00, 9300.00, 9500.00, 9300.00, '2023-09-18 11:35:26', '2023-01-05 09:21:27'),
(23, NULL, 5, 1, 12, 6, 18, 1, 'C30 (2+32)', '12345670987234523', 1.00, 9300.00, 9500.00, 9300.00, '2023-09-18 11:35:26', '2023-01-05 09:21:27'),
(24, NULL, 5, 1, 12, 6, 18, 1, 'C30 (2+32)', '56789023403777773', 1.00, 9300.00, 9500.00, 9300.00, '2023-09-18 11:35:26', '2023-01-05 09:21:27'),
(25, NULL, 6, 2, 7, 6, 10, 1, 'E5B', '92988888202938', 1.00, 750.00, 850.00, 750.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(26, NULL, 6, 2, 7, 6, 10, 1, 'E5B', '078912091278191893729274928472927', 1.00, 750.00, 850.00, 750.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(27, NULL, 6, 2, 7, 6, 10, 1, 'E5B', '7722284392749202', 1.00, 750.00, 850.00, 750.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(28, NULL, 6, 2, 7, 6, 10, 1, 'E5B', '666666663927402', 1.00, 750.00, 850.00, 750.00, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(29, NULL, 7, 2, 7, 9, 15, 1, 'P11', '860823060829789', 1.00, 1120.00, 1150.00, 1120.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(30, NULL, 7, 2, 7, 9, 15, 1, 'P11', '860823060830084', 1.00, 1120.00, 1150.00, 1120.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(31, NULL, 7, 2, 7, 9, 15, 1, 'P11', '860823060820267', 1.00, 1120.00, 1150.00, 1120.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(32, NULL, 7, 2, 7, 3, 10, 1, 'E5B', '865785052252640', 1.00, 750.00, 850.00, 750.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(33, NULL, 7, 2, 7, 3, 10, 1, 'E5B', '865785052355765', 1.00, 750.00, 850.00, 750.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(34, NULL, 7, 2, 7, 3, 10, 1, 'E5B', '865785052251923', 1.00, 750.00, 850.00, 750.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(35, NULL, 7, 2, 7, 3, 10, 1, 'E5B', '865785052252780', 1.00, 750.00, 850.00, 750.00, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(36, NULL, 8, 2, 4, 1, 16, 1, 'it2171', '354502593481028', 1.00, 990.00, 1020.00, 990.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(37, NULL, 8, 2, 4, 1, 16, 1, 'it2171', '354502593146266', 1.00, 990.00, 1020.00, 990.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(38, NULL, 8, 2, 4, 1, 16, 1, 'it2171', '354502593144881', 1.00, 990.00, 1020.00, 990.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(39, NULL, 8, 2, 4, 1, 16, 1, 'it2171', '354502591890667', 1.00, 990.00, 1020.00, 990.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(40, NULL, 8, 2, 4, 3, 7, 1, 'it5027', '351962930101320', 1.00, 1260.00, 1290.00, 1260.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(41, NULL, 8, 2, 4, 3, 7, 1, 'it5027', '351962930099367', 1.00, 1260.00, 1290.00, 1260.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(42, NULL, 8, 2, 4, 3, 7, 1, 'it5027', '351962930101346', 1.00, 1260.00, 1290.00, 1260.00, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(43, NULL, 9, 2, 9, 3, 12, 1, 'G301', '359945042616568', 1.00, 1040.00, 1070.00, 1040.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(44, NULL, 9, 2, 9, 4, 12, 1, 'G301', '359945043444127', 1.00, 1040.00, 1070.00, 1040.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(45, NULL, 9, 2, 9, 7, 13, 1, 'G601', '359947044378221', 1.00, 1300.00, 1330.00, 1300.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(46, NULL, 9, 2, 9, 7, 13, 1, 'G601', '359947044378569', 1.00, 1300.00, 1330.00, 1300.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(47, NULL, 9, 2, 9, 4, 13, 1, 'G601', '359947042602424', 1.00, 1300.00, 1330.00, 1300.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(48, NULL, 9, 2, 9, 2, 13, 1, 'G601', '359947044706728', 1.00, 1300.00, 1330.00, 1300.00, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(49, NULL, 10, 2, 8, 8, 14, 1, 'i100', '351600342760479', 1.00, 900.00, 930.00, 900.00, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(50, NULL, 10, 2, 8, 8, 14, 1, 'i100', '351600342769975', 1.00, 900.00, 930.00, 900.00, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(51, NULL, 10, 2, 8, 8, 14, 1, 'i100', '351600342730670', 1.00, 900.00, 930.00, 900.00, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(52, NULL, 10, 2, 8, 8, 14, 1, 'i100', '351600342758895', 1.00, 900.00, 930.00, 900.00, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(53, NULL, 11, 1, 1, 1, 2, 1, 'Itel M6', 'Imei789234e', 1.00, 5000.00, 6000.00, 5000.00, '2023-04-29 08:58:24', '2023-04-29 08:58:24'),
(54, NULL, 12, 1, 12, 2, 18, 1, 'C30 (2+32)', 'Imei328943c', 1.00, 9300.00, 9500.00, 9300.00, '2023-09-18 11:35:26', '2023-04-29 10:38:11'),
(55, NULL, 13, 1, 1, 1, 2, 1, 'Itel M6', '12345678', 1.00, 5000.00, 6000.00, 5000.00, '2023-05-19 08:42:38', '2023-05-19 08:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `transaction_method` tinyint(4) NOT NULL COMMENT '1=Cash; 2=Bank, 3=Mobile Banking,4=Back Margin,5=Price Adjustment',
  `payment_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1=Nogod; 2=Due',
  `received_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1=Nogod; 2=Due',
  `bank_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `purchase_order_id`, `type`, `transaction_method`, `payment_type`, `received_type`, `bank_id`, `branch_id`, `bank_account_id`, `cheque_no`, `cheque_image`, `amount`, `date`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, 13800.00, '2022-12-29', NULL, '2022-12-29 08:20:18', '2022-12-29 08:20:18'),
(2, 2, 1, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, 70000.00, '2022-12-29', NULL, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(3, 1, 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 10000.00, '2022-12-29', 'Test note 1', '2022-12-29 11:55:27', '2022-12-29 11:55:27'),
(4, 3, 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 2000.00, '2022-12-30', NULL, '2022-12-30 05:14:43', '2022-12-30 05:14:43'),
(5, 5, 1, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, 30000.00, '2023-01-05', NULL, '2023-01-05 09:21:27', '2023-01-05 09:21:27'),
(6, 12, 1, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, 300.00, '2023-04-29', NULL, '2023-04-29 10:38:11', '2023-04-29 10:38:11'),
(7, 7, 1, 2, 2, 2, NULL, NULL, NULL, NULL, 'img/no_image.png', 360.00, '2023-04-29', 'Note for testing', '2023-04-29 12:10:23', '2023-04-29 12:10:23'),
(8, 7, 1, 4, 2, 2, NULL, NULL, NULL, NULL, NULL, 360.00, '2023-04-29', 'Note for testing', '2023-04-29 12:17:01', '2023-04-29 12:17:01'),
(9, 9, 1, 5, 2, 2, NULL, NULL, NULL, NULL, NULL, 280.00, '2023-04-29', 'Note for testing', '2023-04-29 12:27:43', '2023-04-29 12:27:43'),
(10, 8, 1, 4, 2, 2, NULL, NULL, NULL, NULL, NULL, 20.00, '2023-04-30', 'Note for testing', '2023-04-30 07:18:48', '2023-04-30 07:18:48'),
(11, 8, 1, 5, 2, 2, NULL, NULL, NULL, NULL, NULL, 20.00, '2023-04-30', 'Note for testing', '2023-04-30 07:19:07', '2023-04-30 07:19:07'),
(12, 2, 1, 5, 2, 2, NULL, NULL, NULL, NULL, NULL, 3000.00, '2023-04-30', 'Note for testing', '2023-04-30 09:28:18', '2023-04-30 09:28:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `sr_id` int(11) DEFAULT '0',
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `sub_total` double(20,2) NOT NULL,
  `service_sub_total` double(8,2) DEFAULT NULL,
  `vat_percentage` double(8,2) DEFAULT NULL,
  `service_vat_percentage` double(8,2) DEFAULT NULL,
  `vat` double(20,2) DEFAULT NULL,
  `service_vat` double(8,2) DEFAULT NULL,
  `discount` double(20,2) NOT NULL,
  `flat_discount` int(11) DEFAULT NULL,
  `service_discount` double(8,2) DEFAULT NULL,
  `total` double(20,2) NOT NULL,
  `paid` double(20,2) NOT NULL,
  `received_amount` double(20,2) NOT NULL DEFAULT '0.00',
  `exchange_amount` double(20,2) NOT NULL DEFAULT '0.00',
  `due` double(20,2) NOT NULL,
  `refund` double(8,2) NOT NULL DEFAULT '0.00',
  `retailer_price_adjustment` double(20,2) NOT NULL DEFAULT '0.00',
  `next_payment` date DEFAULT NULL,
  `received_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` int(11) NOT NULL DEFAULT '0' COMMENT '1=percentage, 2=flat',
  `payment_type` int(11) NOT NULL,
  `sale_type` int(11) NOT NULL DEFAULT '1' COMMENT '1=admin, 2=SR, 3=admin approve',
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `order_no`, `customer_id`, `sr_id`, `warehouse_id`, `date`, `sub_total`, `service_sub_total`, `vat_percentage`, `service_vat_percentage`, `vat`, `service_vat`, `discount`, `flat_discount`, `service_discount`, `total`, `paid`, `received_amount`, `exchange_amount`, `due`, `refund`, `retailer_price_adjustment`, `next_payment`, `received_by`, `discount_type`, `payment_type`, `sale_type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '38346835', 1, 0, NULL, '2022-12-29', 19980.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, 19980.00, 15980.00, 0.00, 0.00, 4000.00, 0.00, 0.00, '2023-01-03', NULL, 0, 0, 1, 1, '2022-12-29 11:34:08', '2022-12-30 05:34:54'),
(2, '32426892', 2, 0, NULL, '2022-12-30', 900.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, 900.00, 900.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 0, 0, 1, 1, '2022-12-30 05:26:05', '2022-12-30 05:26:05'),
(3, '52192982', 2, 0, NULL, '2022-12-30', 6000.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, 6000.00, 4000.00, 0.00, 0.00, 2000.00, 0.00, 0.00, NULL, NULL, 0, 0, 1, 6, '2022-12-30 05:53:27', '2022-12-30 05:53:27'),
(4, '33038936', 1, 0, NULL, '2023-01-04', 850.00, NULL, NULL, NULL, NULL, NULL, 0.00, 20, NULL, 830.00, 530.00, 0.00, 0.00, 300.00, 0.00, 0.00, NULL, NULL, 2, 1, 3, 6, '2023-01-04 09:37:44', '2023-01-04 09:37:44'),
(5, '47014767', 3, 3, NULL, '2023-01-05', 20000.00, NULL, NULL, NULL, NULL, NULL, 0.00, 200, NULL, 19800.00, 11800.00, 0.00, 0.00, 8000.00, 0.00, 1000.00, '2023-04-25', NULL, 2, 1, 3, 3, '2023-01-05 09:05:27', '2023-04-30 08:48:18'),
(6, '52506226', 3, 2, NULL, '2023-01-07', 850.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, 850.00, 650.00, 0.00, 0.00, 200.00, 0.00, 0.00, '2023-04-26', NULL, 0, 1, 3, 3, '2023-01-07 10:37:30', '2023-04-30 05:36:13'),
(7, '12499932', 1, 0, NULL, '2023-05-19', 20000.00, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, 20000.00, 0.00, 0.00, 0.00, 20000.00, 0.00, 0.00, NULL, NULL, 0, 1, 1, 1, '2023-05-19 08:38:17', '2023-05-19 08:38:17'),
(8, '74824862', 4, 0, NULL, '2023-05-19', 7000.00, NULL, NULL, NULL, NULL, NULL, 0.00, 1000, NULL, 6000.00, 5000.00, 0.00, 0.00, 1000.00, 0.00, 0.00, NULL, NULL, 2, 1, 1, 1, '2023-05-19 08:57:23', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `sales_representatives`
--

CREATE TABLE `sales_representatives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_representatives`
--

INSERT INTO `sales_representatives` (`id`, `brand`, `name`, `mobile_no`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, '[\"7\",\"8\",\"9\",\"10\",\"11\"]', 'Md Raju', '01987020306', 'asif@gmail.com', 'Sonir Akhra, Dhaka', '2022-12-29 08:31:07', '2022-12-30 08:21:28'),
(2, '[\"3\",\"7\",\"8\",\"9\",\"10\",\"11\"]', 'Md baytul', '01987020305', 'baytul@gmail.com', 'Jurain, Jatrabari, Dhaka', '2022-12-29 08:33:13', '2023-01-05 09:02:53'),
(3, '[\"1\",\"7\",\"8\",\"9\",\"10\",\"11\"]', 'Md Rasel', '01987020307', 'rasel@gmail.com', 'Konapara, Demra, Dhaka', '2022-12-30 05:43:57', '2023-01-07 10:15:44'),
(4, '[\"4\"]', 'Md Raju (Itel)', '01400441107', NULL, 'Sonir Akhra, Dhaka', '2022-12-30 08:22:33', '2022-12-30 08:22:33'),
(5, '[\"12\"]', 'Md Kawsar', '01965511701', 'dfhnh@gmail.com', 'Sonir Akhra, Dhaka', '2022-12-30 08:26:35', '2022-12-30 08:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Pay; 2=Refund',
  `transaction_method` tinyint(4) NOT NULL COMMENT '1=Cash; 2=Bank; 3=Mobile Banking; 6=Price Adjustment',
  `received_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1=Nogod; 2=Due',
  `bank_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_payments`
--

INSERT INTO `sale_payments` (`id`, `sales_order_id`, `type`, `transaction_method`, `received_type`, `bank_id`, `branch_id`, `bank_account_id`, `cheque_no`, `cheque_image`, `amount`, `date`, `note`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 9980.00, '2022-12-29', NULL, 1, '2022-12-29 11:34:09', '2022-12-29 11:34:09'),
(2, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 5000.00, '2022-12-29', 'Test note 2', NULL, '2022-12-29 12:20:46', '2022-12-29 12:20:46'),
(3, 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 900.00, '2022-12-30', NULL, 1, '2022-12-30 05:26:05', '2022-12-30 05:26:05'),
(4, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 1000.00, '2022-12-30', NULL, NULL, '2022-12-30 05:34:54', '2022-12-30 05:34:54'),
(5, 3, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 4000.00, '2022-12-30', NULL, 6, '2022-12-30 05:53:27', '2022-12-30 05:53:27'),
(6, 4, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 530.00, '2023-01-04', NULL, 1, '2023-01-05 08:32:36', '2023-01-05 08:32:36'),
(7, 5, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 10800.00, '2023-01-05', NULL, 1, '2023-01-05 09:17:54', '2023-01-05 09:17:54'),
(8, 6, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 500.00, '2023-01-07', NULL, 1, '2023-01-07 10:39:27', '2023-01-07 10:39:27'),
(9, 6, 1, 6, 2, NULL, NULL, NULL, NULL, NULL, 50.00, '2023-04-30', 'Note for testing', NULL, '2023-04-30 05:33:11', '2023-04-30 05:33:11'),
(10, 6, 1, 6, 2, NULL, NULL, NULL, NULL, NULL, 50.00, '2023-04-30', 'Note for testing', NULL, '2023-04-30 05:36:13', '2023-04-30 05:36:13'),
(11, 5, 1, 6, 2, NULL, NULL, NULL, NULL, NULL, 1000.00, '2023-04-30', NULL, NULL, '2023-04-30 08:48:18', '2023-04-30 08:48:18'),
(12, 8, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 5000.00, '2023-05-19', NULL, 1, '2023-05-19 08:57:23', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double(8,2) NOT NULL,
  `unit_price` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `name`, `mobile_no`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Swarnaly Shop', '01726979563', 'Jatrabari', 1, '2022-12-04 04:35:30', '2022-12-04 06:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `sr_product_assign_orders`
--

CREATE TABLE `sr_product_assign_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_representative_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `total_sale_amount` double(100,2) NOT NULL DEFAULT '0.00',
  `total_paid_amount` double(100,2) NOT NULL DEFAULT '0.00',
  `closing_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sr_product_assign_orders`
--

INSERT INTO `sr_product_assign_orders` (`id`, `order_no`, `sales_representative_id`, `date`, `total_sale_amount`, `total_paid_amount`, `closing_date`, `status`, `note`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '00000001', 3, '2022-12-30', 6000.00, 4000.00, '2022-12-30', 1, NULL, 1, '2022-12-30 05:47:34', '2022-12-30 05:55:30'),
(2, '00000002', 3, '2023-01-04', 830.00, 530.00, '2023-01-05', 1, NULL, 1, '2023-01-04 08:55:21', '2023-01-05 05:28:09'),
(3, '00000003', 2, '2023-01-05', 19800.00, 10800.00, '2023-01-05', 1, NULL, 1, '2023-01-05 09:03:11', '2023-01-05 12:56:15'),
(4, '00000004', 3, '2023-01-07', 0.00, 0.00, NULL, 0, NULL, 1, '2023-01-07 09:56:40', '2023-01-07 09:56:40'),
(5, '00000005', 3, '2023-01-07', 0.00, 0.00, NULL, 0, NULL, 1, '2023-01-07 10:00:06', '2023-01-07 10:00:06'),
(6, '00000006', 2, '2023-01-07', 850.00, 500.00, NULL, 0, NULL, 1, '2023-01-07 10:36:01', '2023-01-07 10:37:30'),
(7, '00000007', 3, '2023-05-19', 0.00, 0.00, NULL, 0, NULL, 1, '2023-05-19 08:54:48', '2023-05-19 08:54:48'),
(8, '00000008', 1, '2023-05-19', 0.00, 0.00, NULL, 0, NULL, 1, '2023-05-19 09:04:10', '2023-05-19 09:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `sr_product_assign_order_items`
--

CREATE TABLE `sr_product_assign_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sr_product_assign_order_id` int(10) UNSIGNED NOT NULL,
  `product_brand_id` int(10) UNSIGNED NOT NULL,
  `product_model_id` int(10) UNSIGNED NOT NULL,
  `assign_quantity` double(8,2) NOT NULL,
  `sale_quantity` double(8,2) NOT NULL,
  `back_quantity` double(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sr_product_assign_order_items`
--

INSERT INTO `sr_product_assign_order_items` (`id`, `sr_product_assign_order_id`, `product_brand_id`, `product_model_id`, `assign_quantity`, `sale_quantity`, `back_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 0.00, 1.00, 1.00, '2022-12-30 05:47:34', '2022-12-30 05:55:30'),
(2, 2, 7, 10, 0.00, 1.00, 1.00, '2023-01-04 08:55:21', '2023-01-05 05:28:09'),
(3, 3, 3, 9, 0.00, 1.00, 1.00, '2023-01-05 09:03:11', '2023-01-05 12:56:15'),
(4, 4, 9, 12, 2.00, 0.00, 0.00, '2023-01-07 09:56:40', '2023-01-07 09:56:40'),
(5, 4, 9, 13, 4.00, 0.00, 0.00, '2023-01-07 09:56:40', '2023-01-07 09:56:40'),
(6, 4, 8, 14, 4.00, 0.00, 0.00, '2023-01-07 09:56:40', '2023-01-07 09:56:40'),
(7, 5, 7, 10, 4.00, 0.00, 0.00, '2023-01-07 10:00:06', '2023-01-07 10:00:06'),
(8, 5, 7, 15, 3.00, 0.00, 0.00, '2023-01-07 10:00:06', '2023-01-07 10:00:06'),
(9, 6, 7, 10, 1.00, 1.00, 0.00, '2023-01-07 10:36:01', '2023-01-07 10:37:30'),
(10, 7, 1, 2, 1.00, 0.00, 0.00, '2023-05-19 08:54:48', '2023-05-19 08:54:48'),
(11, 8, 7, 10, 10.00, 0.00, 0.00, '2023-05-19 09:04:10', '2023-05-19 09:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_orders`
--

CREATE TABLE `stock_transfer_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sourch_warehouse_id` int(10) UNSIGNED NOT NULL,
  `target_shop_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_transfer_orders`
--

INSERT INTO `stock_transfer_orders` (`id`, `order_no`, `sourch_warehouse_id`, `target_shop_id`, `date`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '00001', 1, 1, '2022-12-29', 1, '2022-12-29 10:53:27', '2022-12-29 10:53:27'),
(2, '00002', 1, 1, '2022-12-30', 1, '2022-12-30 05:21:23', '2022-12-30 05:21:23'),
(3, '00003', 1, 1, '2023-01-05', 1, '2023-01-05 09:01:28', '2023-01-05 09:01:28'),
(4, '00004', 1, 1, '2023-01-07', 1, '2023-01-07 09:54:00', '2023-01-07 09:54:00'),
(5, '00005', 1, 1, '2023-05-19', 1, '2023-05-19 08:47:36', '2023-05-19 08:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `opening_due` float(100,2) NOT NULL DEFAULT '0.00',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `opening_due`, `name`, `mobile`, `email`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 0.00, 'Walton Degi-Tech Industries Ltd', '01713449175', 'supplier1@gmail.com', 'Bashundhara, Dhaka', 1, '2022-12-29 08:12:15', '2022-12-30 08:40:04'),
(2, 0.00, 'Benco Grameen Distribution Ltd', '0195383548', 'supplier2@gmail.com', 'Dhaka', 1, '2022-12-29 08:12:48', '2023-01-07 09:30:25'),
(3, 0.00, 'Realme- Daosheng Enterprise Development Company Ltd', '01914882136', NULL, 'Dhaka', 1, '2022-12-30 08:38:27', '2022-12-30 08:38:27'),
(4, 0.00, 'GDL- Grameen Distribution Ltd', '01755598539', NULL, 'Mirpur', 1, '2022-12-30 08:41:13', '2022-12-30 08:41:13'),
(5, 0.00, 'Itel Carlcare Technology Ltd', '01987020306', NULL, 'Dhaka', 1, '2023-01-07 09:31:48', '2023-01-07 09:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=Income; 2=Expense',
  `account_head_type_id` int(10) UNSIGNED NOT NULL,
  `account_head_sub_type_id` int(10) UNSIGNED NOT NULL,
  `transaction_method` tinyint(4) NOT NULL COMMENT '1=Cash; 2=Bank; 3=Mobile Banking',
  `bank_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_type`, `account_head_type_id`, `account_head_sub_type_id`, `transaction_method`, `bank_id`, `branch_id`, `bank_account_id`, `cheque_no`, `cheque_image`, `amount`, `date`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 9, 1, NULL, NULL, NULL, NULL, NULL, 4000.00, '2023-01-05', 'samsung s4', '2023-01-05 10:55:10', '2023-01-05 10:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `particular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=Income; 2=Expense,4=Back Margin,5=Supplier/Company Price Adjustment;6=Retailer Price Adjustment',
  `transaction_method` tinyint(4) NOT NULL COMMENT '1=Cash; 2=Bank,4=Back Margin,5=Price Adjustment;6=Retailer Price Adjustment',
  `account_head_type_id` int(11) NOT NULL,
  `account_head_sub_type_id` int(11) NOT NULL,
  `bank_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_account_id` int(10) UNSIGNED DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(20,2) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `sale_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_order_id` int(10) UNSIGNED DEFAULT NULL,
  `wastage_id` bigint(20) DEFAULT NULL,
  `purchase_product_category_id` int(10) DEFAULT NULL,
  `purchase_product_sub_category_id` int(10) DEFAULT NULL,
  `transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `balance_transfer_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_logs`
--

INSERT INTO `transaction_logs` (`id`, `date`, `particular`, `transaction_type`, `transaction_method`, `account_head_type_id`, `account_head_sub_type_id`, `bank_id`, `branch_id`, `bank_account_id`, `cheque_no`, `cheque_image`, `amount`, `note`, `purchase_payment_id`, `sale_payment_id`, `sales_order_id`, `wastage_id`, `purchase_product_category_id`, `purchase_product_sub_category_id`, `transaction_id`, `balance_transfer_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2022-12-29', 'Payment for 00000001', 2, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 13800.00, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 08:20:18', '2022-12-29 08:20:18'),
(2, '2022-12-29', 'Buying price for 00000001', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 34000.00, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 08:20:18', '2022-12-29 08:20:18'),
(3, '2022-12-29', 'Payment for 00000002', 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 70000.00, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(4, '2022-12-29', 'Buying price for 00000002', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 74000.00, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 08:25:12', '2022-12-29 08:25:12'),
(5, '2022-12-29', 'Payment for 38346835', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 9980.00, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 11:34:09', '2022-12-29 11:34:09'),
(6, '2022-12-29', 'Selling price for 38346835', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 19980.00, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-29 11:34:09', '2022-12-29 11:34:09'),
(7, '2022-12-29', 'Paid to Supplier 1 for 00000001', 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 10000.00, 'Test note 1', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-29 11:55:27', '2022-12-29 11:55:27'),
(8, '2022-12-29', 'Payment from Kamrul for 38346835', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 5000.00, 'Test note 2', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-29 12:20:46', '2022-12-29 12:20:46'),
(9, '2022-12-30', 'Buying price for 00000003', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 2400.00, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-30 05:12:36', '2022-12-30 05:12:36'),
(10, '2022-12-30', 'Paid to Supplier 1 for 00000003', 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 2000.00, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-30 05:14:43', '2022-12-30 05:14:43'),
(11, '2022-12-30', 'Payment for 32426892', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 900.00, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-30 05:26:05', '2022-12-30 05:26:05'),
(12, '2022-12-30', 'Selling price for 32426892', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 900.00, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-30 05:26:05', '2022-12-30 05:26:05'),
(13, '2022-12-30', 'Payment from Kamrul for 38346835', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 1000.00, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-30 05:34:54', '2022-12-30 05:34:54'),
(14, '2022-12-30', 'Payment for 52192982', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 4000.00, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2022-12-30 05:53:27', '2022-12-30 05:53:27'),
(15, '2022-12-30', 'Selling price for 52192982', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 6000.00, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, 6, '2022-12-30 05:53:27', '2022-12-30 05:53:27'),
(16, '2022-12-31', 'Buying price for 00000004', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 1940.00, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-12-31 03:53:20', '2022-12-31 03:53:20'),
(17, '2023-01-04', 'Payment for 33038936', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 530.00, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2023-01-05 08:32:36', '2023-01-05 08:32:36'),
(18, '2023-01-04', 'Selling price for 33038936', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 850.00, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 3, '2023-01-05 08:32:36', '2023-01-05 08:32:36'),
(19, '2023-01-05', 'Payment for 47014767', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 10800.00, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2023-01-05 09:17:54', '2023-01-05 09:17:54'),
(20, '2023-01-05', 'Selling price for 47014767', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 20000.00, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, 2, '2023-01-05 09:17:54', '2023-01-05 09:17:54'),
(21, '2023-01-05', 'Payment for 00000005', 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 30000.00, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-05 09:21:27', '2023-01-05 09:21:27'),
(22, '2023-01-05', 'Buying price for 00000005', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 37200.00, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-05 09:21:27', '2023-01-05 09:21:27'),
(23, '2023-01-05', 'Buying price for 00000006', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 3000.00, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-05 09:36:40', '2023-01-05 09:36:40'),
(24, '2023-01-05', 'Product Price Update', 1, 1, 12, 9, NULL, NULL, NULL, NULL, NULL, 4000.00, 'samsung s4', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2023-01-05 10:55:10', '2023-01-05 10:55:10'),
(25, '2023-01-07', 'Buying price for 00000007', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 6360.00, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-07 09:34:37', '2023-01-07 09:34:37'),
(26, '2023-01-07', 'Buying price for 00000008', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 7740.00, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-07 09:36:22', '2023-01-07 09:36:22'),
(27, '2023-01-07', 'Buying price for 00000009', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 7280.00, NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-07 09:41:33', '2023-01-07 09:41:33'),
(28, '2023-01-07', 'Buying price for 00000010', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 3600.00, NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-01-07 09:43:29', '2023-01-07 09:43:29'),
(29, '2023-01-07', 'Payment for 52506226', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 500.00, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2023-01-07 10:39:27', '2023-01-07 10:39:27'),
(30, '2023-01-07', 'Selling price for 52506226', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 850.00, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, 2, '2023-01-07 10:39:27', '2023-01-07 10:39:27'),
(31, '2023-04-29', 'Buying price for 00000011', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 5000.00, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-04-29 08:58:24', '2023-04-29 08:58:24'),
(32, '2023-04-29', 'Payment for 00000012', 2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, 300.00, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-04-29 10:38:11', '2023-04-29 10:38:11'),
(33, '2023-04-29', 'Buying price for 00000012', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 9300.00, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-04-29 10:38:11', '2023-04-29 10:38:11'),
(34, '2023-04-29', 'Back Margin Benco Grameen Distribution Ltd for 00000007', 4, 4, 13, 10, NULL, NULL, NULL, NULL, NULL, 360.00, 'Note for testing', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-29 12:17:01', '2023-04-29 12:17:01'),
(35, '2023-04-29', 'Price Adjustment  GDL- Grameen Distribution Ltd for 00000009', 5, 5, 14, 11, NULL, NULL, NULL, NULL, NULL, 280.00, 'Note for testing', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-29 12:27:43', '2023-04-29 12:27:43'),
(36, '2023-04-30', 'Price Adjustment from Md Hannan for 52506226', 6, 6, 15, 12, NULL, NULL, NULL, NULL, NULL, 50.00, 'Note for testing', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 05:33:11', '2023-04-30 05:33:11'),
(37, '2023-04-30', 'Price Adjustment from Md Hannan for 52506226', 6, 6, 15, 12, NULL, NULL, NULL, NULL, NULL, 50.00, 'Note for testing', NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 05:36:13', '2023-04-30 05:36:13'),
(38, '2023-04-30', 'Back Margin Itel Carlcare Technology Ltd for 00000008', 4, 4, 13, 10, NULL, NULL, NULL, NULL, NULL, 20.00, 'Note for testing', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 07:18:48', '2023-04-30 07:18:48'),
(39, '2023-04-30', 'Price Adjustment  Itel Carlcare Technology Ltd for 00000008', 5, 5, 14, 11, NULL, NULL, NULL, NULL, NULL, 20.00, 'Note for testing', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 07:19:07', '2023-04-30 07:19:07'),
(40, '2023-04-30', 'Price Adjustment from Md Hannan for 47014767', 6, 6, 15, 12, NULL, NULL, NULL, NULL, NULL, 1000.00, NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 08:48:18', '2023-04-30 08:48:18'),
(41, '2023-04-30', 'Price Adjustment  Benco Grameen Distribution Ltd for 00000002', 5, 5, 14, 11, NULL, NULL, NULL, NULL, NULL, 3000.00, 'Note for testing', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-30 09:28:18', '2023-04-30 09:28:18'),
(42, '2023-05-19', 'Selling price for 12499932', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 20000.00, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, 1, '2023-05-19 08:38:17', '2023-05-19 08:38:17'),
(43, '2023-05-19', 'Buying price for 00000013', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 5000.00, NULL, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-05-19 08:42:38', '2023-05-19 08:42:38'),
(44, '2023-05-19', 'Payment for 74824862', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, 5000.00, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-05-19 08:57:23', '2023-05-19 08:57:23'),
(45, '2023-05-19', 'Selling price for 74824862', 4, 0, 5, 5, NULL, NULL, NULL, NULL, NULL, 6000.00, NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 1, '2023-05-19 08:57:23', '2023-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `update_prices`
--

CREATE TABLE `update_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_model_id` int(10) UNSIGNED NOT NULL,
  `old_purchase_price` double(20,2) NOT NULL,
  `old_selling_price` double(20,2) NOT NULL,
  `updated_purchase_price` double(20,2) NOT NULL,
  `updated_selling_price` double(20,2) NOT NULL,
  `total_quantity` double(20,2) NOT NULL,
  `reduce_price` double(20,2) NOT NULL,
  `total_price` double(20,2) NOT NULL,
  `date` date DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `update_prices`
--

INSERT INTO `update_prices` (`id`, `product_model_id`, `old_purchase_price`, `old_selling_price`, `updated_purchase_price`, `updated_selling_price`, `total_quantity`, `reduce_price`, `total_price`, `date`, `note`, `created_at`, `updated_at`) VALUES
(1, 10, 800.00, 900.00, 750.00, 850.00, 5.00, 50.00, 250.00, '2022-12-29', NULL, '2022-12-29 12:19:45', '2022-12-29 12:19:45'),
(3, 2, 6000.00, 8000.00, 5000.00, 6000.00, 4.00, 1000.00, 4000.00, '2022-12-30', NULL, '2022-12-30 05:08:58', '2022-12-30 05:08:58'),
(5, 18, 9300.00, 9500.00, 9300.00, 9500.00, 0.00, 0.00, 0.00, '2023-01-01', NULL, '2023-01-01 07:02:04', '2023-01-01 07:02:04'),
(6, 18, 9300.00, 9500.00, 9300.00, 9500.00, 5.00, 0.00, 0.00, '2023-09-18', NULL, '2023-09-18 11:35:26', '2023-09-18 11:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_representative_id` int(11) DEFAULT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL COMMENT '1=Admin,2=SR,3=Retailer/Customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `sales_representative_id`, `retailer_id`, `name`, `username`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Admin', 'Admin', 'admin@gmail.com', 1, NULL, '$2a$12$Vg8KMNLir4ZNDFoM7H6WW.30tQIk1w77U2IOBv8auS815KbwGbNOa', 'aOqvE3aciSNZmq79cLGeqoeDJJ9aS5OS3OT2uJsUN5PtvbdPF1trmg7C9Ntp', '2020-12-23 10:43:38', '2020-12-23 10:43:38'),
(2, 1, NULL, 'Md Raju', 'Md Raju', 'asif@gmail.com', 2, NULL, '$2y$10$zBR1mKxoq4C/9ubCaYjsmexbOnksawYqHc1HfKXKHY9VTvv5fc632', NULL, '2022-12-29 08:31:07', '2022-12-30 08:21:28'),
(3, 2, NULL, 'Md baytul', 'baytul', 'baytul@gmail.com', 2, NULL, '$2y$10$2Ynksa3guu2xcynuFlHut.8rVGoicuTyFuLpF4/dLli/RXWHlt8I6', NULL, '2022-12-29 08:33:13', '2023-01-05 08:59:17'),
(4, NULL, 1, 'Kamrul', NULL, 'kamrul@gmail.com', 3, NULL, '$2y$10$Z9pqYN.rWRAs7.TkrQP4u.2Vap4CzC8On2xZtuEsMqVrSsUbrLNDK', NULL, '2022-12-29 11:33:10', '2022-12-29 11:33:10'),
(5, NULL, 2, 'Md Rashid Miazi', NULL, 'fdgfhgj@gmail.com', 3, NULL, '$2y$10$RdzsDwKEgHnSDiQiBM6UC.2dJAwn.y.M.86Htr0TdWa80tbYU63oC', NULL, '2022-12-30 05:18:17', '2022-12-30 08:30:47'),
(6, 3, NULL, 'Md Rasel', 'Rasel', 'rasel@gmail.com', 2, NULL, '$2y$10$wWV/Qg2jo935WvqazjQIZ.iNI5MGw8nyWR.4n4AKimvyh1S8T5HjK', NULL, '2022-12-30 05:43:57', '2022-12-30 08:18:43'),
(7, 4, NULL, 'Md Raju (Itel)', 'admin@gmail.com', NULL, 2, NULL, '$2y$10$oE/Byy6QzGrUWLRFFFmBQ.d2t137rygXrcgAKaj.Xl2zkIjf6U7be', NULL, '2022-12-30 08:22:33', '2022-12-30 08:22:33'),
(8, 5, NULL, 'Md Kawsar', 'Kawsar', 'dfhnh@gmail.com', 2, NULL, '$2y$10$lRATbtUNKecngyr2Zx5LIedopwFNVy4TJ3DKKav5dnNJDIFr/FTxu', NULL, '2022-12-30 08:26:35', '2022-12-30 08:26:35'),
(9, NULL, 3, 'Md Hannan', NULL, 'dfdtd@gmail.com', 3, NULL, '$2y$10$3AynFL4cYK8LIqqQI8b.4OXZ50V7djTNxccERwK/ZQTKI8MKA6eTO', NULL, '2022-12-30 08:29:04', '2022-12-30 08:29:04'),
(10, NULL, 4, 'Md Rasel', NULL, 'srdfd@gmail.com', 3, NULL, '$2y$10$16g1wMV9nHmblFqJtf52wuSuyG.S4cpIjtpt8xZxtyOuBhFEtMTcK', NULL, '2022-12-30 08:32:30', '2022-12-30 08:32:30'),
(11, NULL, 5, 'md Manik', NULL, 'djdf@gmail.com', 3, NULL, '$2y$10$4SFfk8yBTdEyw8K/h6jGYuxfC9JskC6Cf1y6mTr8g3uMl4djStcEG', NULL, '2022-12-30 08:34:02', '2022-12-30 08:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'S H ENTERPRISE', 'BONGAON', 1, NULL, '2023-05-04 08:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `wastages`
--

CREATE TABLE `wastages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `total` double(20,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wastage_products`
--

CREATE TABLE `wastage_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wastage_id` bigint(20) NOT NULL,
  `purchase_product_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_product_category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_product_sub_category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` double(20,2) DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_head_sub_types`
--
ALTER TABLE `account_head_sub_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_head_types`
--
ALTER TABLE `account_head_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashes`
--
ALTER TABLE `cashes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_services`
--
ALTER TABLE `customer_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_checks`
--
ALTER TABLE `login_checks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_bankings`
--
ALTER TABLE `mobile_bankings`
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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_models`
--
ALTER TABLE `product_models`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_model_sales_order`
--
ALTER TABLE `product_model_sales_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_inventories`
--
ALTER TABLE `purchase_inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_inventory_logs`
--
ALTER TABLE `purchase_inventory_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_purchase_product`
--
ALTER TABLE `purchase_order_purchase_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_representatives`
--
ALTER TABLE `sales_representatives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sr_product_assign_orders`
--
ALTER TABLE `sr_product_assign_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sr_product_assign_order_items`
--
ALTER TABLE `sr_product_assign_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transfer_orders`
--
ALTER TABLE `stock_transfer_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_prices`
--
ALTER TABLE `update_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wastages`
--
ALTER TABLE `wastages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wastage_products`
--
ALTER TABLE `wastage_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_head_sub_types`
--
ALTER TABLE `account_head_sub_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `account_head_types`
--
ALTER TABLE `account_head_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cashes`
--
ALTER TABLE `cashes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_services`
--
ALTER TABLE `customer_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_checks`
--
ALTER TABLE `login_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `mobile_bankings`
--
ALTER TABLE `mobile_bankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_brands`
--
ALTER TABLE `product_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_models`
--
ALTER TABLE `product_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_model_sales_order`
--
ALTER TABLE `product_model_sales_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_inventories`
--
ALTER TABLE `purchase_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `purchase_inventory_logs`
--
ALTER TABLE `purchase_inventory_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `purchase_order_purchase_product`
--
ALTER TABLE `purchase_order_purchase_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales_representatives`
--
ALTER TABLE `sales_representatives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sr_product_assign_orders`
--
ALTER TABLE `sr_product_assign_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sr_product_assign_order_items`
--
ALTER TABLE `sr_product_assign_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stock_transfer_orders`
--
ALTER TABLE `stock_transfer_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `update_prices`
--
ALTER TABLE `update_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wastages`
--
ALTER TABLE `wastages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wastage_products`
--
ALTER TABLE `wastage_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
