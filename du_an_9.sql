-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 19, 2026 at 12:18 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `du_an_9`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `image`, `status`, `sort_order`, `parent_id`) VALUES
(1, 'Áo thun', 'aothun.jpg', 1, 0, NULL),
(2, 'Áo sơ mi', 'aosomi.jpg', 1, 0, NULL),
(3, 'Áo khoác', 'aokhoac.jpg', 1, 0, NULL),
(4, 'Áo len', 'aolen.jpg', 1, 0, NULL),
(5, 'Áo polo', 'aopolo.jpg', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung bình luận',
  `rating` tinyint DEFAULT '5' COMMENT 'Số sao đánh giá (1-5)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint DEFAULT '1' COMMENT '1: Hiện, 0: Ẩn (kiểm duyệt)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `product_id`, `content`, `rating`, `created_at`, `status`) VALUES
(3, 2, 29, 'GGez', 1, '2025-12-17 15:11:10', 1),
(4, 2, 29, 'Rất tốt', 5, '2025-12-17 15:14:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`, `size`, `color`) VALUES
(4, 6, 4, '2025-12-13 10:48:04', '', ''),
(19, 10, 1, '2025-12-17 00:11:44', 'L', 'Đen'),
(28, 8, 1, '2025-12-17 01:38:58', 'FreeSize', 'Đen'),
(29, 8, 1, '2025-12-17 01:42:40', 'M', 'Đen'),
(30, 8, 1, '2025-12-17 01:44:14', 'S', 'Đen'),
(31, 8, 1, '2025-12-17 01:44:16', 'S', 'Trắng'),
(32, 8, 1, '2025-12-17 01:44:21', 'FreeSize', 'Trắng'),
(37, 12, 1, '2025-12-17 14:07:49', 'S', 'Trắng'),
(38, 12, 1, '2025-12-17 14:07:55', 'XXL', 'Trắng'),
(39, 12, 1, '2025-12-17 14:08:19', 'XXL', 'Đen'),
(40, 2, 29, '2025-12-17 15:19:27', 'XXL', 'Trắng');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL,
  `Shipping_fee` decimal(10,2) DEFAULT '0.00',
  `pay_method_id` int DEFAULT '1',
  `payment_status` enum('unpaid','paid','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `status` enum('pending','confirmed','shipping','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `note`, `total_amount`, `Shipping_fee`, `pay_method_id`, `payment_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administrator', 'admin@gmail.com', '0947541167', '3rwe', 'qwrwq', 328000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-18 14:34:48', '2025-11-18 14:34:48'),
(2, 1, 'Administrator', 'admin@gmail.com', '0947541167', 'gege', 'gege', 377000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-28 13:24:40', '2025-11-28 13:24:40'),
(3, 1, 'Administrator', 'admin@gmail.com', '108196651', 'we', 'dwqd', 3010000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-28 13:42:20', '2025-11-28 13:42:20'),
(4, 6, 'Kẹo chôcc', 'dat@gmail.com', '0936715847', 'feafe', 'faeaf', 228000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-28 14:36:07', '2025-11-28 14:36:07'),
(5, 6, 'Kẹo chôcc', 'dat@gmail.com', '0354646513', 'adsadwa', 'dấddawd', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-30 15:57:24', '2025-11-30 15:57:24'),
(6, 1, 'Administrator', 'admin@gmail.com', '0123456789', 'adsda', 'dsadwad', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-30 17:13:25', '2025-11-30 17:13:25'),
(7, 1, 'Administrator', 'admin@gmail.com', '1315465435', 'đă agafaf', 'dagfawd', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-11-30 17:24:57', '2025-11-30 17:24:57'),
(8, 1, 'Administrator', 'admin@gmail.com', '1234567892', 'adsadava', 'adwvwab', 189000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:12:22', '2025-12-01 15:12:22'),
(9, 1, 'Administrator', 'admin@gmail.com', '123456789', 'adwvabw', 'wadbavdbaw', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:15:17', '2025-12-01 15:15:17'),
(10, 2, 'Nguyễn Văn A', 'a@gmail.com', '1234564556', 'teh teha', 'hteatea', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:21:59', '2025-12-01 15:21:59'),
(11, 2, 'Nguyễn Văn A', 'a@gmail.com', '12345612345', 'f qeFGsf', 'gfSG', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:24:08', '2025-12-01 15:24:08'),
(12, 2, 'Nguyễn Văn A', 'a@gmail.com', '123456789', 'wd EF A', 'E AFEFA', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:27:47', '2025-12-01 15:27:47'),
(13, 2, 'Nguyễn Văn A', 'a@gmail.com', '123456789', 'dw  dWDADW', 'ADWDWADA', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:28:44', '2025-12-01 15:28:44'),
(14, 2, 'Nguyễn Văn A', 'a@gmail.com', '1234567892', 'vuvuvg', 'hibihbug', 189000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:33:15', '2025-12-01 15:33:15'),
(15, 2, 'Nguyễn Văn A', 'a@gmail.com', '0936715847', 'guvuvg', 'ygv', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:34:19', '2025-12-01 15:34:19'),
(16, 2, 'Nguyễn Văn A', 'a@gmail.com', '12345612345', 'huhuhu', '', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:35:55', '2025-12-01 15:35:55'),
(17, 2, 'Nguyễn Văn A', 'a@gmail.com', '123', 'kkj', 'kjkj', 179000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:36:58', '2025-12-01 15:36:58'),
(18, 2, 'Nguyễn Văn A', 'a@gmail.com', '123', 'weqwq', 'eqwe', 179000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:39:04', '2025-12-01 15:39:04'),
(19, 2, 'Nguyễn Văn A', 'a@gmail.com', '0936715847', '61515', '', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:42:54', '2025-12-01 15:42:54'),
(20, 2, 'Nguyễn Văn A', 'a@gmail.com', '0936715847', 'adfsgd', '', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-01 15:46:18', '2025-12-01 15:46:18'),
(21, 6, 'Kẹo chôcc', 'dat@gmail.com', '0936715847', 'sadgfshdg', '', 129000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-01 15:48:41', '2025-12-13 11:13:07'),
(22, 6, 'Kẹo chôcc', 'dat@gmail.com', '0936715847', 'dwa', '', 179000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-01 15:49:03', '2025-12-13 11:13:05'),
(23, 6, 'Kẹo chôcc', 'dat@gmail.com', '0936715847', '1651546', '', 189000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-01 15:55:45', '2025-12-13 11:13:04'),
(24, 1, 'Administrator', 'admin@gmail.com', '123123123', 'huy', 'huypros1', 646000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-10 15:20:15', '2025-12-13 11:13:02'),
(25, 6, 'Kẹo chôcc', 'dat@gmail.com', '0935237185', 'duawdawda', 'dawdadw', 228000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-13 06:34:45', '2025-12-13 11:05:02'),
(26, 1, 'Administrator', 'admin@gmail.com', '0935237185', 'duawda', '', 129000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-13 11:13:49', '2025-12-13 11:14:58'),
(27, 6, 'Kẹo chôcc', 'dat@gmail.com', '0935237185', 'adsda', '', 129000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-13 11:24:47', '2025-12-13 11:25:25'),
(28, 6, 'Kẹo chôcc', 'dat@gmail.com', '0935237185', 'dawdaawds', '', 149000.00, 30000.00, 1, 'unpaid', 'completed', '2025-12-13 11:25:05', '2025-12-13 11:25:24'),
(29, 1, 'Administrator', 'admin@gmail.com', '123456789', '123 quận 12', '', 30099.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-13 12:28:37', '2025-12-13 12:28:37'),
(30, 1, 'Administrator', 'admin@gmail.com', '0947541167', '123123', '12312312241', 565000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-17 10:19:50', '2025-12-17 10:19:50'),
(31, 1, 'Administrator', 'admin@gmail.com', '0947541167', 'dqwdwq', 'wdqwdq', 129000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-17 10:20:27', '2025-12-17 10:20:27'),
(32, 1, 'Administrator', 'admin@gmail.com', '0947541167', 'qweqweqwewq', 'qwweqwe', 149000.00, 30000.00, 1, 'unpaid', 'cancelled', '2025-12-17 10:26:53', '2025-12-17 11:27:46'),
(33, 1, 'Administrator', 'admin@gmail.com', '0947541167', '3123', '21321333', 153123.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-17 11:15:43', '2025-12-17 11:15:43'),
(34, 1, 'Administrator', 'admin@gmail.com', '0947541167', '123123', '123213123', 541435.00, 30000.00, 1, 'paid', 'completed', '2025-12-17 11:16:18', '2025-12-17 11:17:02'),
(35, 1, 'Administrator', 'admin@gmail.com', '0359286509', 'eaffae', '', 284000.00, 30000.00, 1, 'unpaid', 'pending', '2025-12-17 14:34:37', '2025-12-17 14:34:37'),
(36, 1, 'Administrator', 'admin@gmail.com', '0359286509', 'q213123', '123333', 284000.00, 30000.00, 1, 'unpaid', 'cancelled', '2025-12-17 14:50:57', '2025-12-17 14:51:22'),
(37, 2, 'Nguyễn Văn A', 'a@gmail.com', '0359286509', 'qưe', 'qưe', 502000.00, 30000.00, 1, 'paid', 'completed', '2025-12-17 15:05:46', '2025-12-17 15:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int NOT NULL,
  `variant_id` int NOT NULL,
  `order_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `variant_id`, `order_id`, `quantity`, `price`, `product_id`) VALUES
(15, 50, 30, 1, 99000, 7),
(18, 410, 30, 1, 99000, 1),
(19, 267, 31, 1, 99000, 29),
(20, 265, 32, 1, 119000, 29),
(22, 269, 34, 1, 149000, 29),
(23, 266, 34, 1, 99000, 29),
(27, 417, 35, 1, 155000, 1),
(28, 49, 35, 1, 99000, 7),
(29, 417, 36, 1, 155000, 1),
(30, 410, 36, 1, 99000, 1),
(31, 417, 37, 1, 155000, 1),
(32, 410, 37, 1, 99000, 1),
(33, 265, 37, 1, 119000, 29),
(34, 266, 37, 1, 99000, 29);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int NOT NULL,
  `name_method` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `name_method`, `description`, `is_active`) VALUES
(1, 'Thanh toán khi nhận hàng (COD)', 'Khách trả tiền khi nhận hàng', 1),
(2, 'Chuyển khoản ngân hàng', 'Thanh toán qua chuyển khoản', 1),
(3, 'Ví Momo', 'Thanh toán qua ví điện tử Momo', 0),
(4, 'Thẻ tín dụng / Ghi nợ', 'Thanh toán qua cổng VNPAY', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `category_slug` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `sold` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_slug`, `description`, `image`, `category_id`, `status`, `sold`, `created_at`, `quantity`) VALUES
(1, 'Áo thun basic trắng', '', 'Áo thun cotton 100%, thoáng mát, dễ phối đồ', 'aothun_trang.jpg', 1, 1, 60, '2025-11-30 09:21:45', 45),
(2, 'Áo thun đen form rộng', '', 'Phong cách streetwear, chất liệu co giãn', 'aothun_den.jpg', 1, 1, 40, '2025-11-30 09:22:06', 500),
(3, 'Áo thun in hình Tokyo', '', 'Áo thun unisex in hình thành phố Tokyo', 'aothun_tokyo.jpg', 1, 1, 30, '2025-11-30 09:22:16', 500),
(4, 'Áo thun cổ tròn xanh navy', '', 'Thiết kế đơn giản, phù hợp đi học, đi chơi', 'aothun_xanhnavy.jpg', 1, 1, 20, '2025-12-16 04:39:49', 500),
(5, 'Áo thun thể thao', '', 'Áo thun thể thao thấm hút mồ hôi tốt', 'aothun_sport.jpg', 1, 1, 35, '2025-11-30 09:23:14', 500),
(6, 'Áo thun tay lỡ Hàn Quốc', '', 'Phong cách Hàn Quốc, tay lỡ, form rộng', 'aothun_hanquoc.jpg', 1, 1, 45, '2025-11-30 09:24:21', 500),
(7, 'Áo sơ mi trắng công sở', '', 'Áo sơ mi trắng cổ điển, phù hợp đi làm', 'somi_trang.jpg', 2, 1, 52, '2025-11-30 09:24:29', 500),
(8, 'Áo sơ mi caro đỏ', '', 'Áo sơ mi caro phong cách trẻ trung', 'somi_caro.jpg', 2, 1, 40, '2025-11-30 09:24:37', 500),
(9, 'Áo sơ mi denim xanh', '', 'Chất liệu denim bền đẹp, cá tính', 'somi_denim.jpg', 2, 1, 25, '2025-11-30 09:24:46', 500),
(10, 'Áo sơ mi lụa nữ', '', 'Chất liệu lụa mềm mại, sang trọng', 'somi_lua.jpg', 2, 1, 30, '2025-11-30 09:24:55', 500),
(11, 'Áo sơ mi tay ngắn', '', 'Thiết kế tay ngắn, thoải mái mùa hè', 'somi_tayngan.jpg', 2, 1, 35, '2025-11-30 09:25:59', 500),
(12, 'Áo sơ mi họa tiết tropical', '', 'Phong cách biển, họa tiết nổi bật', 'somi_tropical.jpg', 2, 1, 20, '2025-11-30 09:26:09', 500),
(13, 'Áo khoác bomber đen', '', 'Áo khoác bomber chất liệu kaki, cá tính', 'khoac_bomber.jpg', 3, 1, 30, '2025-11-30 09:26:18', 500),
(14, 'Áo khoác jean xanh', '', 'Áo khoác jean cổ điển, unisex', 'khoac_jean.jpg', 3, 1, 25, '2025-11-30 09:26:27', 500),
(15, 'Áo khoác hoodie xám', '', 'Hoodie nỉ ấm áp, có mũ', 'khoac_hoodie.jpg', 3, 1, 40, '2025-11-30 09:26:36', 500),
(16, 'Áo khoác gió thể thao', '', 'Chống gió, nhẹ, phù hợp đi phượt', 'khoac_gio.jpg', 3, 1, 35, '2025-11-30 09:26:44', 500),
(17, 'Áo khoác dạ nữ', '', 'Áo khoác dạ dáng dài, sang trọng', 'khoac_da.jpg', 3, 1, 20, '2025-11-30 09:26:53', 500),
(18, 'Áo khoác parka lót lông', '', 'Giữ ấm tốt, phù hợp mùa đông', 'khoac_parka.jpg', 3, 1, 15, '2025-11-30 09:27:00', 500),
(19, 'Áo len cổ lọ xám', '', 'Chất liệu len mềm, giữ ấm tốt', 'aolen_xam.jpg', 4, 1, 25, '2025-11-30 09:27:08', 500),
(20, 'Áo len cổ tim nữ', '', '', 'aolen_coltim.jpg', 4, 1, 30, '2025-11-30 09:27:17', 500),
(21, 'Áo len oversize', '', 'Phong cách Hàn Quốc, form rộng', 'aolen_oversize.jpg', 4, 1, 20, '2025-11-30 09:27:25', 500),
(22, 'Áo len sọc ngang', '', 'Họa tiết sọc trẻ trung, năng động', 'aolen_soc.jpg', 4, 1, 35, '2025-11-30 09:27:33', 500),
(23, 'Áo len cardigan', '', 'Cardigan len mỏng, dễ phối đồ', 'aolen_cardigan.jpg', 4, 1, 40, '2025-11-30 09:27:41', 500),
(24, 'Áo len cổ tròn basic', '', 'Thiết kế đơn giản, dễ mặc', 'aolen_cotron.jpg', 4, 1, 28, '2025-11-30 09:27:46', 500),
(25, 'Áo polo nam trắng', '', 'Áo polo cổ bẻ, lịch sự', 'polo_trang.jpg', 5, 1, 50, '2025-11-30 09:27:58', 500),
(26, 'Áo polo nữ hồng pastel', '', 'Phong cách trẻ trung, nữ tính', 'polo_hong.jpg', 5, 1, 35, '2025-11-16 11:30:00', 500),
(27, 'Áo polo thể thao', '', 'Chất liệu thun lạnh, thấm hút tốt', 'polo_sport.jpg', 5, 1, 40, '2025-11-16 11:30:00', 500),
(28, 'Áo polo sọc caro', '', 'Họa tiết caro, cá tính', 'polo_caro.jpg', 5, 1, 30, '2025-11-16 11:30:00', 500),
(29, 'Áo polo đen basic', '', 'Thiết kế đơn giản, dễ phối đồ', 'polo_den.jpg', 5, 0, 51, '2025-11-16 11:30:00', 410);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Trắng',
  `price` int NOT NULL,
  `stock` int NOT NULL DEFAULT '100',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('show','hide') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `price`, `stock`, `image`, `status`) VALUES
(24, 2, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(25, 2, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(26, 2, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(27, 2, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(28, 2, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(29, 3, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(30, 3, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(31, 3, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(32, 3, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(33, 3, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(34, 4, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(35, 4, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(36, 4, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(37, 4, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(38, 4, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(39, 5, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(40, 5, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(41, 5, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(42, 5, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(43, 5, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(44, 6, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(45, 6, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(46, 6, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(47, 6, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(48, 6, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(49, 7, 'S', 'Trắng', 99000, 99, NULL, 'show'),
(50, 7, 'M', 'Trắng', 99000, 99, NULL, 'show'),
(51, 7, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(52, 7, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(53, 7, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(54, 8, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(55, 8, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(56, 8, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(57, 8, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(58, 8, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(59, 9, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(60, 9, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(61, 9, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(62, 9, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(63, 9, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(64, 10, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(65, 10, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(66, 10, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(67, 10, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(68, 10, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(69, 11, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(70, 11, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(71, 11, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(72, 11, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(73, 11, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(74, 12, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(75, 12, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(76, 12, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(77, 12, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(78, 12, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(79, 13, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(80, 13, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(81, 13, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(82, 13, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(83, 13, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(84, 14, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(85, 14, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(86, 14, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(87, 14, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(88, 14, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(89, 15, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(90, 15, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(91, 15, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(92, 15, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(93, 15, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(169, 16, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(170, 16, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(171, 16, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(172, 16, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(173, 16, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(174, 17, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(175, 17, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(176, 17, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(177, 17, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(178, 17, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(179, 18, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(180, 18, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(181, 18, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(182, 18, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(183, 18, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(184, 19, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(185, 19, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(186, 19, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(187, 19, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(188, 19, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(189, 20, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(190, 20, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(191, 20, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(192, 20, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(193, 20, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(194, 21, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(195, 21, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(196, 21, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(197, 21, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(198, 21, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(199, 22, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(200, 22, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(201, 22, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(202, 22, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(203, 22, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(204, 23, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(205, 23, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(206, 23, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(207, 23, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(208, 23, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(209, 24, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(210, 24, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(211, 24, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(212, 24, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(213, 24, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(214, 25, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(215, 25, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(216, 25, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(217, 25, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(218, 25, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(219, 26, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(220, 26, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(221, 26, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(222, 26, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(223, 26, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(224, 27, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(225, 27, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(226, 27, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(227, 27, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(228, 27, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(229, 28, 'S', 'Trắng', 99000, 100, NULL, 'show'),
(230, 28, 'M', 'Trắng', 99000, 100, NULL, 'show'),
(231, 28, 'L', 'Trắng', 119000, 100, NULL, 'show'),
(232, 28, 'XL', 'Trắng', 139000, 100, NULL, 'show'),
(233, 28, 'XXL', 'Trắng', 149000, 100, NULL, 'show'),
(265, 29, 'L', 'Trắng', 119000, 98, '', 'show'),
(266, 29, 'M', 'Trắng', 99000, 98, '', 'show'),
(267, 29, 'S', 'Trắng', 99000, 99, '', 'show'),
(268, 29, 'XL', 'Trắng', 139000, 100, '', 'show'),
(269, 29, 'XXL', 'Trắng', 149000, 9, '', 'show'),
(410, 1, 'S', 'Trắng', 99000, 7, '', 'show'),
(417, 1, 'S', 'Đen', 155000, 33, 'image/var_1765955631_aothun_den.jpg', 'show');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Câu trả lời của Admin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `product_id`, `content`, `reply`, `created_at`, `parent_id`) VALUES
(1, 1, 1, 'hêlo', NULL, '2025-12-10 14:41:31', NULL),
(2, 8, 1, 'Hello', NULL, '2025-12-10 15:26:09', NULL),
(3, 10, 1, 'hello\r\n', 'hello', '2025-12-16 22:09:49', NULL),
(4, 10, 1, 'hello', NULL, '2025-12-16 22:10:48', NULL),
(5, 10, 1, 'hello\r\n', NULL, '2025-12-16 22:34:29', 3);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_method`
--

CREATE TABLE `shipping_method` (
  `id` int NOT NULL,
  `name_method` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shipping_method`
--

INSERT INTO `shipping_method` (`id`, `name_method`, `description`, `is_active`) VALUES
(1, 'Giao hàng tiêu chuẩn', 'Giao trong 3-5 ngày', 1),
(2, 'Giao hàng nhanh', 'Giao trong 1-2 ngày', 1),
(3, 'Giao hàng hỏa tốc', 'Giao trong ngày (nội thành)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `size_guide`
--

CREATE TABLE `size_guide` (
  `id` int NOT NULL,
  `size_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên size: S, M, L',
  `weight_min` int NOT NULL COMMENT 'Cân nặng từ (kg)',
  `weight_max` int NOT NULL COMMENT 'Cân nặng đến (kg)',
  `height_min` int NOT NULL COMMENT 'Chiều cao từ (cm)',
  `height_max` int NOT NULL COMMENT 'Chiều cao đến (cm)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `size_guide`
--

INSERT INTO `size_guide` (`id`, `size_name`, `weight_min`, `weight_max`, `height_min`, `height_max`) VALUES
(1, 'S', 40, 50, 150, 160),
(2, 'M', 50, 60, 160, 165),
(3, 'L', 60, 70, 165, 172),
(4, 'XL', 70, 80, 170, 178),
(5, 'XXL', 80, 95, 175, 185);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `birthday` datetime DEFAULT NULL,
  `role` enum('admin','teacher','student','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` enum('nam','nữ','khác') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_exp` datetime DEFAULT NULL,
  `status` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `birthday`, `role`, `created_at`, `updated_at`, `gender`, `reset_token`, `reset_token_exp`, `status`) VALUES
(1, 'Administrator', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0909123456', 'Hà Nội', NULL, 'admin', '2025-11-18 07:25:23', '2025-12-13 08:40:53', 'nam', NULL, NULL, 1),
(2, 'Nguyễn Văn A', 'a@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0909111222', NULL, NULL, 'customer', '2025-11-18 07:25:23', '2025-11-18 07:25:23', 'nam', NULL, NULL, 1),
(3, 'Trần Thị B', 'b@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0909333444', NULL, NULL, 'customer', '2025-11-18 07:25:23', '2025-11-18 07:25:23', 'nam', NULL, NULL, 1),
(4, 'Lê Văn C', 'c@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0909555666', NULL, NULL, 'customer', '2025-11-18 07:25:23', '2025-11-18 07:25:23', 'nam', NULL, NULL, 1),
(5, 'huypro', 'huy@gmail.com', 'b8dc042d8cf7deefb0ec6a264c930b02', NULL, NULL, NULL, 'customer', '2025-11-19 08:16:57', '2025-11-19 08:16:57', 'nam', NULL, NULL, 1),
(6, 'Kẹo chôcc', 'dat@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0935237185', 'F7/31', NULL, 'customer', '2025-11-28 07:35:26', '2025-12-13 03:47:56', 'nam', NULL, NULL, 1),
(7, 'Nguyễn Khổng Đạt', 'vana@gmall.com', 'e10adc3949ba59abbe56e057f20f883e', '0935237185', 'abcxyz', NULL, 'customer', '2025-12-05 04:57:41', '2025-12-13 00:18:25', 'nam', NULL, NULL, 1),
(8, '124124', 'huypro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 'customer', '2025-12-10 08:17:36', '2025-12-10 08:17:36', 'nam', NULL, NULL, 1),
(9, 'Admin Khang', 'khangken226@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 'admin', '2025-12-13 08:41:08', '2025-12-13 08:41:08', 'nam', NULL, NULL, 1),
(10, 'Lê Văn Khang', 'myhang25082006@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0917003574', 'sao hỏa', NULL, 'customer', '2025-12-13 08:50:21', '2025-12-16 16:23:51', 'nam', NULL, NULL, 1),
(11, 'Khang', 'levanken1868@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 'customer', '2025-12-13 09:34:26', '2025-12-13 09:34:26', 'nam', NULL, NULL, 1),
(12, 'huypro', 'huybanthu2017@gmail.com', '4297f44b13955235245b2497399d7a93', NULL, NULL, NULL, 'customer', '2025-12-17 07:00:39', '2025-12-17 07:04:30', 'nam', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_item_variant` (`variant_id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_variant` (`product_id`,`size`,`color`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipping_method`
--
ALTER TABLE `shipping_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `size_guide`
--
ALTER TABLE `size_guide`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping_method`
--
ALTER TABLE `shipping_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `size_guide`
--
ALTER TABLE `size_guide`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
