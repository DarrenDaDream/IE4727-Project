-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 15, 2024 at 01:18 PM
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
-- Database: `Project`
--

-- --------------------------------------------------------

--
-- Table structure for table `credit_cards`
--

CREATE TABLE `credit_cards` (
  `card_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `expiry_date` char(5) NOT NULL,
  `cvv` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `postal` varchar(10) NOT NULL,
  `payment_method` enum('credit_card','paypal') NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','shipped','delivered','canceled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `main_image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `is_featured` enum('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `main_image_url`, `category`, `date_added`, `is_featured`) VALUES
(1, 'Cool Jacket', 'Stylish and comfortable.', 79.99, 'images/products/jacket.jpg', 'Clothing', '2024-11-04', 'yes'),
(2, 'Sneakers', 'Perfect for all-day wear.', 49.99, 'images/products/sneakers.jpg', 'Footwear', '2024-11-04', 'yes'),
(3, 'Classic T-Shirt', 'A comfortable classic tee.', 19.99, 'images/products/tshirt.jpg', 'Clothing', '2024-11-04', 'no'),
(4, 'Denim Jeans', 'Stylish denim jeans for everyday wear.', 49.99, 'images/products/jeans.jpg', 'Clothing', '2024-11-04', 'yes'),
(5, 'Sporty Hoodie', 'A cozy hoodie for workouts or lounging.', 39.99, 'images/products/hoodie.jpg', 'Clothing', '2024-11-04', 'yes'),
(6, 'Running Shoes', 'Lightweight shoes for running.', 69.99, 'images/products/runningshoes.jpg', 'Footwear', '2024-11-04', 'no'),
(7, 'Leather Boots', 'Durable leather boots for any occasion.', 89.99, 'images/products/boots.jpg', 'Footwear', '2024-11-04', 'yes'),
(8, 'Sunglasses', 'Stylish sunglasses for sunny days.', 29.99, 'images/products/sunglasses.jpg', 'Accessories', '2024-11-04', 'yes'),
(9, 'Wool Scarf', 'Keep warm with this fashionable scarf.', 24.99, 'images/products/scarf.jpg', 'Accessories', '2024-11-04', 'no'),
(10, 'Baseball Cap', 'A trendy cap for outdoor activities.', 19.99, 'images/products/cap.jpg', 'Accessories', '2024-11-04', 'no'),
(11, 'Chic Blazer', 'A smart blazer for formal occasions.', 99.99, 'images/products/blazer.jpg', 'Clothing', '2024-11-04', 'no'),
(12, 'Gym Bag', 'Durable bag for all your gym essentials.', 34.99, 'images/products/gymbag.jpg', 'Accessories', '2024-11-04', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `QNTY` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `color`, `size`, `price`, `image_url`, `QNTY`) VALUES
(1, 1, 'Black', 'M', 79.99, 'images/products/jacket_black.jpg', 10),
(2, 1, 'Black', 'L', 79.99, 'images/products/jacket_black.jpg', 100),
(4, 2, 'White', '10', 49.99, 'images/products/sneakers_white.jpg', 22),
(6, 3, 'White', 'S', 19.99, 'images/products/tshirt.jpg', 20),
(9, 4, 'Blue', 'L', 49.99, 'images/products/jeans_blue.jpg', 7),
(10, 5, 'Gray', 'M', 39.99, 'images/products/hoodie_gray.jpg', 11),
(11, 5, 'Gray', 'L', 39.99, 'images/products/hoodie_gray.jpg', 9),
(12, 6, 'Black', '10', 69.99, 'images/products/runningshoes_black.jpg', 10),
(13, 7, 'Brown', '8', 89.99, 'images/products/boots_brown.jpg', 6),
(14, 8, 'Black', 'One Size', 29.99, 'images/products/sunglasses_black.jpg', 25),
(15, 9, 'Gray', 'One Size', 24.99, 'images/products/scarf_gray.jpg', 30),
(16, 10, 'Red', 'One Size', 19.99, 'images/products/cap_red.jpg', 22),
(17, 11, 'Black', 'M', 99.99, 'images/products/blazer_black.jpg', 5),
(19, 12, 'Black', 'One Size', 34.99, 'images/products/gymbag_black.jpg', 15),
(20, 2, 'White', '9', 49.99, 'images/products/sneakers_white.jpg', 22),
(21, 2, 'White', '8', 49.99, 'images/products/sneakers_white.jpg', 22),
(22, 11, 'Black', 'S', 99.99, 'images/products/blazer_black.jpg', 5),
(23, 4, 'Blue', 'M', 49.99, 'images/products/jeans_blue.jpg', 7),
(24, 6, 'Black', '7', 69.99, 'images/products/runningshoes_black.jpg', 10),
(25, 6, 'Black', '9', 69.99, 'images/products/runningshoes_black.jpg', 10),
(26, 3, 'White', 'M', 19.99, 'images/products/tshirt.jpg', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
