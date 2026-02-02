<!-- 
# 🛒 Indus Agrii – E-Commerce Web Application

A modern, full-stack eCommerce web application designed for selling agricultural and food products online.  
The platform focuses on performance, clean UI/UX, scalability, and real-world business workflows.

---

## 🚀 Features

### 👤 User Features
- Secure user authentication (login & registration)
- Product browsing with categories
- Product search and filtering
- Wishlist functionality ❤️
- Shopping cart management
- Smooth checkout flow
- Order placement & order history
- Responsive design for mobile, tablet, and desktop

### 🛠 Admin Features
- Admin authentication
- Product management (add / edit / delete)
- Category management
- Order management & order status updates
- Dashboard-style data handling

---

## 🧩 Tech Stack

### Frontend
- HTML5
- Tailwind CSS (utility-first, responsive, performance-optimized)
- Vanilla JavaScript (clean & lightweight interactions)

### Backend
- PHP (server-side logic)
- MySQL (relational database)
- AJAX (asynchronous requests)

### Other
- Session-based authentication
- REST-like request handling
- Clean and modular code structure

---

## 📂 Project Structure





<!-- -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 02, 2026 at 11:40 AM
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
-- Database: `indus_agrii`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`) VALUES
(2, 'admin@indusagrii.com', '$2y$10$uRF.NrA.bHcMYfQRiael8ui9TyAMs70zK5rAsuSTd0RnTV.QzYUt.', '2026-01-19 08:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `status`) VALUES
(1, 'Rice', 'rice', NULL, NULL, 0, 'active'),
(2, 'Millets', 'millets', NULL, NULL, 0, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_enquiries`
--

INSERT INTO `contact_enquiries` (`id`, `name`, `email`, `phone`, `message`, `created_at`, `is_read`, `is_deleted`) VALUES
(1, 'shankar', 'tamju.trading@gmail.com', '77568040', 'ftykretyuiil', '2026-01-28 06:23:29', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notify_requests`
--

CREATE TABLE `notify_requests` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text DEFAULT NULL,
  `is_notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notify_requests`
--

INSERT INTO `notify_requests` (`id`, `product_id`, `product_name`, `phone`, `message`, `is_notified`, `created_at`, `user_id`) VALUES
(1, 3, 'Barnyard Millet', '4345', 'dfg', 1, '2026-01-30 06:12:53', NULL),
(2, 3, 'Barnyard Millet', '4345', 'sdfasg', 1, '2026-01-31 08:53:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `pack_size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_title` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `base_price` decimal(10,2) NOT NULL,
  `pack_sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`pack_sizes`)),
  `category` enum('rice','millets') NOT NULL,
  `variety` varchar(50) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `in_stock` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `short_title`, `short_description`, `long_description`, `seo_title`, `seo_description`, `price`, `base_price`, `pack_sizes`, `category`, `variety`, `main_image`, `gallery_images`, `is_active`, `created_at`, `updated_at`, `in_stock`) VALUES
(2, 'Indrayani Rice', 'Indrayani Rice', 'Indian Aromatic Medium Grain Rice', 'Indrayani Rice is a traditional Indian aromatic rice variety known for its soft texture, rich fragrance, and authentic taste, ideal for daily meals and festive dishes.\r\n', 'Product NameIndrayani Rice is a premium medium-grain rice variety cultivated using organic farming practices. Naturally fragrant and soft when cooked, it is widely used in Maharashtrian and Indian cuisine.\r\n\r\nThis rice is carefully processed in raw and polished forms to preserve its natural aroma and nutritional value. With controlled moisture levels and excellent shelf life, Indrayani Rice is perfect for households, restaurants, and export markets.\r\n\r\nIts balanced texture and pleasant aroma make it suitable for steamed rice, varan-bhaat, and traditional preparations.\r\n\r\n', 'Indrayani Rice | Aromatic Indian Medium Grain Rice', 'Buy premium Indrayani Rice – naturally aromatic medium grain rice cultivated organically, ideal for traditional Indian meals.\r\n', 912.00, 456.00, '[2,5,10,30]', 'rice', 'indrayani', '1769514523_ProductIndrayaniRice.png', '[\"1769514523_ProductIndrayaniRice.png\",\"1769514523_ProductKalanamakRice.jpeg\",\"1770022161_pearl3.jpg\"]', 1, '2026-01-27 11:48:43', '2026-02-02 08:49:21', 1),
(3, 'Barnyard Millet', 'barnyard-millet-sanwa', 'High-Iron Nutritious Millet', 'Barnyard Millet is a healthy small-grain millet rich in iron and ideal for light meals and fasting recipes.\r\n', 'Barnyard Millet, also known as Sanwa, is a nutritious millet with small, round grains and high iron content. It is cultivated organically or conventionally and processed to maintain purity and quality.\r\n\r\nWith low moisture levels and excellent shelf life, Barnyard Millet is commonly used in porridge, khichdi, and healthy snacks, making it suitable for both traditional and modern diets.\r\n', 'Barnyard Millet | High Iron Healthy Millet', 'Premium Barnyard Millet (Sanwa) rich in iron, ideal for porridge, khichdi, and healthy recipes.\r\n', 600.00, 300.00, '[2,5,10,30]', 'millets', NULL, '1769514615_pearl3.jpg', '[\"1769514615_pearl1.png\",\"1769514615_pearl2.webp\",\"1769514615_pearl3.jpg\"]', 1, '2026-01-27 11:50:15', '2026-02-02 09:23:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'mayuresh manoj khamkar', 'mayureshtriune@gmail.com', '$2y$10$P3Luwdq9Vi9M3abyxnjYmel4FIYRexItj.5me7o.cO/7CADA0tI9i', '2026-01-31 07:00:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notify_requests`
--
ALTER TABLE `notify_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_active_created` (`is_active`,`created_at`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_price` (`base_price`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_search` (`name`,`short_description`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notify_requests`
--
ALTER TABLE `notify_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; --> -->
