-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql104.byetcluster.com
-- Generation Time: Jul 18, 2026 at 09:27 AM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42439251_myadmin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fullname`, `email`, `username`, `password`, `role`) VALUES
(1, 'Migs', 'ctmartin@fit.edu.ph', 'admin', 'admin123', 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `admin_id`, `activity`, `log_date`) VALUES
(1, 1, 'Logged Out', '2026-07-18 03:21:26'),
(2, 1, 'Logged Out', '2026-07-18 03:21:26'),
(5, 1, 'Deleted Product', '2026-07-18 03:25:35'),
(6, 1, 'Added Product', '2026-07-18 03:25:56'),
(7, 1, 'Updated Product', '2026-07-18 03:26:09'),
(8, 1, 'Logged Out', '2026-07-18 03:26:57'),
(9, 1, 'Logged Out', '2026-07-18 03:26:57'),
(12, 1, 'Logged Out', '2026-07-18 03:29:24'),
(13, 1, 'Logged Out', '2026-07-18 03:29:24'),
(14, 1, 'Logged In', '2026-07-18 03:29:33'),
(15, 1, 'Logged In', '2026-07-18 12:11:10'),
(16, 1, 'Logged In', '2026-07-18 12:11:10'),
(17, 1, 'Logged Out', '2026-07-18 12:33:12'),
(18, 1, 'Logged Out', '2026-07-18 12:33:12'),
(19, 1, 'Logged In', '2026-07-18 12:33:18'),
(20, 1, 'Logged Out', '2026-07-18 12:35:47'),
(21, 1, 'Logged Out', '2026-07-18 12:35:47'),
(22, 1, 'Logged In', '2026-07-18 12:35:53'),
(23, 1, 'Logged Out', '2026-07-18 12:36:04'),
(24, 1, 'Logged Out', '2026-07-18 12:36:04'),
(25, 1, 'Logged In', '2026-07-18 12:36:11'),
(26, 1, 'Logged In', '2026-07-18 12:46:56'),
(27, 1, 'Updated Product', '2026-07-18 12:48:15'),
(28, 1, 'Updated Product', '2026-07-18 12:48:26'),
(29, 1, 'Updated Admin', '2026-07-18 12:48:58'),
(30, 1, 'Logged In', '2026-07-18 13:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'String Instruments'),
(2, 'Wind Instruments'),
(3, 'Percussion'),
(4, 'Keyboard Instruments'),
(5, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `address`, `contact_number`, `payment_method`, `total_price`, `status`, `created_at`) VALUES
(1, 3, 'Charles Miguel Martin', 'feu', '09362885720', 'Cash on Delivery', '4500.00', 'Pending', '2026-07-18 12:43:24'),
(2, 3, 'Charles Miguel Martin', 'feu', '09362885720', 'GCash', '37500.00', 'Pending', '2026-07-18 12:43:52'),
(3, 4, 'Vladimir', 'Sampaloc, Manila', '09123456789', 'Cash on Delivery', '6400.00', 'Pending', '2026-07-18 12:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 9, 'Acoustic Guitar', 1, '4500.00'),
(2, 2, 16, '5-Piece Drum Set', 3, '12500.00'),
(3, 3, 13, 'Transverse Flute', 2, '3200.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `description`, `price`, `stock`, `image`, `is_featured`) VALUES
(9, 1, 'Acoustic Guitar', 'Full-size steel-string acoustic guitar, great for beginners.', '4500.00', 14, 'acoustic-guitar.webp', 1),
(10, 1, 'Classical Ukulele', 'Concert-size ukulele with mahogany body.', '1800.00', 25, 'ukulele.webp', 1),
(11, 1, 'Electric Guitar', 'Solid-body electric guitar with humbucker pickups.', '8900.00', 10, 'electric-guitar.webp', 0),
(12, 1, 'Violin 4/4', 'Full-size violin with bow and case included.', '5200.00', 8, 'violin.jpg', 0),
(13, 2, 'Transverse Flute', 'Silver-plated concert flute for students.', '3200.00', 10, 'flute.webp', 1),
(14, 2, 'Bb Trumpet', 'Standard Bb trumpet with mouthpiece and case.', '6500.00', 9, 'trumpet.jpg', 0),
(15, 2, 'Diatonic Harmonica', '10-hole diatonic harmonica, key of C.', '450.00', 40, 'harmonica.webp', 0),
(16, 3, '5-Piece Drum Set', 'Beginner drum set with cymbals and throne.', '12500.00', 2, 'drum-set.jpg', 1),
(17, 3, 'Cajon Box Drum', 'Handcrafted wooden cajon with snare wires.', '3800.00', 14, 'cajon.jpg', 1),
(18, 3, 'Tambourine', 'Handheld tambourine with double jingles.', '350.00', 30, 'tambourine.jpg', 0),
(19, 4, '61-Key Keyboard', 'Portable electronic keyboard with built-in speakers.', '5800.00', 11, 'keyboard.jpg', 1),
(20, 4, 'Melodica', '32-key melodica with mouthpiece and tube.', '1500.00', 18, 'melodica.jpg', 0),
(21, 5, 'Guitar Strings (Set)', 'Steel acoustic guitar string set, light gauge.', '280.00', 60, 'guitar-strings.jpg', 0),
(22, 5, 'Guitar Picks (Pack of 12)', 'Assorted thickness celluloid picks.', '120.00', 100, 'picks.jpg', 0),
(23, 5, 'Instrument Strap', 'Adjustable padded strap for guitar or bass.', '350.00', 45, 'strap.jpg', 0),
(24, 5, 'Guitar Stand', 'Foldable A-frame stand for guitar or ukulele.', '650.00', 20, 'stand.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `address`, `contact_number`, `is_verified`, `verification_token`, `created_at`) VALUES
(1, 'Vladimir Vasquez', 'sampleemail@gmail.com', '$2y$10$3IslbXOAbIhjdAc7k53nTuzA/9dbM483VWbqWLjJrZWoXHXBjyiLa', 'Sampaloc, Manila', '09292777394', 1, NULL, '2026-07-18 10:21:57'),
(3, 'Charles Miguel Martin', 'ctmartin@fit.edu.ph', '$2y$10$Quv5yr3uJE43MWtxCoIAo.YI3cVreODaaFMuUPGCk0mSHnze0R2fG', 'feu', '09362885720', 1, NULL, '2026-07-18 12:42:39'),
(4, 'Vladimir', 'vvasquez@fit.edu.ph', '$2y$10$5rlMVWVecuKWmBC2MDnyw.3.V1tKEaN7T5chlRfGwQ3DqxEnGJMpO', 'Sampaloc, Manila', '09123456789', 1, NULL, '2026-07-18 12:44:40'),
(5, 'samplebuyer1', 'samplebuyer1@gmail.com', '$2y$10$.37RouPZw85laSBdaPMxJOanj1AJCz/7HAvprnPbmS4xwng1Paj1a', 'Sampaloc, Manila', '09123456789', 1, NULL, '2026-07-18 13:03:02'),
(6, 'samplebuyer2', 'samplebuyer2@gmail.com', '$2y$10$bzY.zP52QheCW8HxkrUGoulmfUND37jheHNmjOIqcfHl0KqIYXz32', 'Sampaloc, Manila', '09987654321', 1, NULL, '2026-07-18 13:03:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
