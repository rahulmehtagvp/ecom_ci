-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2020 at 10:26 AM
-- Server version: 5.7.30-0ubuntu0.18.04.1
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clickkart`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `landmark` varchar(100) NOT NULL,
  `zip_code` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `user_id`, `street_address`, `landmark`, `zip_code`, `status`) VALUES
(1, 11, 'MN street', 'MN nagar', '678543', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `display_name`, `profile_image`, `user_type`, `status`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'assets/uploads/services/1592387519_download.jpeg', 1, 1),
(4, 'test1', 'e10adc3949ba59abbe56e057f20f883e', 'test1', 'assets/uploads/services/1592308573_download_(1).jpeg', 2, 1),
(5, 'root1', 'e10adc3949ba59abbe56e057f20f883e', 'hkjhkjhk', 'assets/uploads/services/1592560939_download.jpeg', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `advertisement`
--

CREATE TABLE `advertisement` (
  `add_id` bigint(20) NOT NULL,
  `add_name` varchar(255) NOT NULL,
  `starting_time` date NOT NULL,
  `ending_time` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `advertisement`
--

INSERT INTO `advertisement` (`add_id`, `add_name`, `starting_time`, `ending_time`, `image`, `status`) VALUES
(1, 'test123', '2020-06-17', '2020-06-17', 'assets/uploads/advertisement/1592371825_download.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_table`
--

CREATE TABLE `auth_table` (
  `auth_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_table`
--

INSERT INTO `auth_table` (`auth_id`, `user_id`, `unique_id`, `status`) VALUES
(2, 10, '0fea019b436d32f8e469567df3af3c36', 1),
(3, 11, '3966a28f2c2667ba813583caca377223', 1),
(4, 11, 'c2601be88180fb72e2ecd56cce475d96', 1),
(5, 11, 'fd8209168281e06f7a7eeaf022f347c4', 1),
(6, 12, '62fdeb9610093189ebc46deab56ea248', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `quantity`, `user_id`, `status`) VALUES
(1, 3, '6', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` bigint(20) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_image`, `store_id`, `status`) VALUES
(1, 'Carss', 'assets/uploads/services/1592292552_download_(2).jpeg', 0, 2),
(2, 'Cars', 'assets/uploads/services/1592291460_download_(1).jpeg', 0, 1),
(3, 'test', 'assets/uploads/services/1592292725_download.jpeg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` bigint(20) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `zip_code`, `location`, `status`) VALUES
(1, 'fds', 'fdsfds', 2),
(2, 'test', 'test', 2),
(3, 'test', 'test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_data`
--

CREATE TABLE `cms_data` (
  `cms_id` bigint(20) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '	0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_data`
--

INSERT INTO `cms_data` (`cms_id`, `identifier`, `data`, `status`) VALUES
(1, 'about', '<p>about</p>\r\n', 0),
(2, 'abouttest', '<p>abouttest</p>\r\n', 0),
(3, 'about', '<p>about</p>\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `booking_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `address_id` bigint(20) NOT NULL,
  `total_amount` varchar(100) NOT NULL,
  `booking_date` date DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-ordered on 1-order delivered 2-order packed 3-order shipped'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_id`, `booking_id`, `user_id`, `store_id`, `address_id`, `total_amount`, `booking_date`, `scheduled_date`, `status`) VALUES
(1, 1, 0, 11, 1, 0, '146', '2020-06-15', '2020-06-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `op_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `store_id` bigint(20) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `category_id`, `store_id`, `product_image`, `status`) VALUES
(1, 'mercedes 231', '12344', 2, 0, 'assets/uploads/services/1592300563_download_(2).jpeg', 1),
(2, 'mercedes 200', '2345', 2, 0, 'assets/uploads/services/1592301261_download_(2).jpeg', 2),
(3, 'Mercedes 234', '4444', 2, 0, 'assets/uploads/services/1592301300_download_(1).jpeg', 1),
(4, 'test 1', 'test 1', 2, 2, 'assets/uploads/services/1592313917_download.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `promo_id` bigint(20) NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `starting_date` datetime NOT NULL,
  `ending_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`promo_id`, `promo_code`, `starting_date`, `ending_date`, `status`) VALUES
(1, 'dsadsa', '2020-06-19 13:41:00', '2020-06-18 12:40:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shopper`
--

CREATE TABLE `shopper` (
  `shopper_id` bigint(20) NOT NULL,
  `shopper_name` varchar(255) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `shopper_image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shopper`
--

INSERT INTO `shopper` (`shopper_id`, `shopper_name`, `phone_no`, `password`, `shopper_image`, `email`, `store_id`, `type`, `status`) VALUES
(3, 'test1', '1234567891', 'e10adc3949ba59abbe56e057f20f883e', 'assets/uploads/services/1592308573_download_(1).jpeg', 'test1@gmail.com', NULL, 2, 1),
(5, 'hkjhkjhk', '45645656456456456', 'e10adc3949ba59abbe56e057f20f883e', 'assets/uploads/services/1592560939_download.jpeg', '4564564@gmail.com', 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` bigint(20) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_image` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `city_id` bigint(20) NOT NULL,
  `starting_time` varchar(100) NOT NULL,
  `starting_end` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `store_name`, `store_image`, `description`, `city_id`, `starting_time`, `starting_end`, `status`) VALUES
(1, 'uiuoiujfghjjfghjghj', 'assets/uploads/services/1592313185_download_(1).jpeg', '<p>kjlkjljtyjfghjfhjghjjfhj</p>\r\n', 3, '18:43', '20:45', 1),
(2, 'Test', 'assets/uploads/services/1592313525_download_(2).jpeg', '<p>test</p>\r\n', 3, '19:52', '20:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_product`
--

CREATE TABLE `store_product` (
  `store_product_id` bigint(20) NOT NULL,
  `store_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) NOT NULL,
  `delete_status` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store_product`
--

INSERT INTO `store_product` (`store_product_id`, `store_id`, `product_id`, `delete_status`, `status`) VALUES
(1, NULL, 4, 1, 1),
(2, NULL, 1, 1, 1),
(3, NULL, 2, 1, 1),
(4, NULL, 3, 1, 1),
(5, 1, 0, 0, 0),
(6, 2, 0, 0, 1),
(7, 2, 4, 1, 1),
(8, 2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `subcat_id` bigint(20) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`subcat_id`, `subcategory_name`, `category_id`, `image`, `status`) VALUES
(1, 'mercedesss', 2, 'assets/uploads/services/1592293706_download_(1).jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` bigint(20) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `district` varchar(255) NOT NULL,
  `city_id` bigint(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '	0-Inactive 1-active 2-delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_id`, `fullname`, `email`, `password`, `phone_no`, `district`, `city_id`, `image`, `status`) VALUES
(1, 'testdasdasd', 'root1@gmail.com', '123456', '1234567891', 'testdsadsa', 3, 'assets/uploads/services/1592311660_download_(1).jpeg', 1),
(10, 'abc', 'abc1@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '01234567891', 'ernakulam', 0, '', 1),
(11, 'anila', 'anila@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '9087654686', 'ernakulam', 0, 'assets/uploads/upload_files/IMG1592569887', 1),
(12, 'abc', 'abc@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '0123456789', 'ernakulam', 0, 'assets/uploads/services/1592551243_download.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_promo`
--

CREATE TABLE `user_promo` (
  `up_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `promo_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `wallet_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `wallet_balance` decimal(11,2) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`wallet_id`, `user_id`, `wallet_balance`, `status`) VALUES
(1, 11, '-10.00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisement`
--
ALTER TABLE `advertisement`
  ADD PRIMARY KEY (`add_id`);

--
-- Indexes for table `auth_table`
--
ALTER TABLE `auth_table`
  ADD PRIMARY KEY (`auth_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `cms_data`
--
ALTER TABLE `cms_data`
  ADD PRIMARY KEY (`cms_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`op_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `shopper`
--
ALTER TABLE `shopper`
  ADD PRIMARY KEY (`shopper_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `store_product`
--
ALTER TABLE `store_product`
  ADD PRIMARY KEY (`store_product_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`subcat_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_promo`
--
ALTER TABLE `user_promo`
  ADD PRIMARY KEY (`up_id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `advertisement`
--
ALTER TABLE `advertisement`
  MODIFY `add_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `auth_table`
--
ALTER TABLE `auth_table`
  MODIFY `auth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cms_data`
--
ALTER TABLE `cms_data`
  MODIFY `cms_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `op_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `promo_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `shopper`
--
ALTER TABLE `shopper`
  MODIFY `shopper_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `store_product`
--
ALTER TABLE `store_product`
  MODIFY `store_product_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `subcat_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `user_promo`
--
ALTER TABLE `user_promo`
  MODIFY `up_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
