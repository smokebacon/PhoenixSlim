-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2017 at 07:07 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phoenix`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Booking_No` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Customer_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Trip_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Booking_Date` date DEFAULT NULL,
  `Num_Concessions` int(11) NOT NULL,
  `Num_Adults` int(11) NOT NULL,
  `Deposit_Amount` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`Booking_No`, `Customer_Id`, `Trip_Id`, `Booking_Date`, `Num_Concessions`, `Num_Adults`, `Deposit_Amount`) VALUES
('002313', '031642', '004640', '2016-08-15', 0, 25, '2400.00'),
('004564', '031642', '004572', '2016-08-15', 2, 2, '100.00'),
('007214', '001484', '004572', '2016-05-27', 1, 0, '500.00'),
('008050', '001484', '343271', '2016-11-01', 2, 0, '150.00'),
('077897', '001484', '644621', '2016-08-15', 0, 1, '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `First_Name` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `Middle_Initial` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Last_Name` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `Street_No` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `Street_Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Suburb` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `Postcode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `Phone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Auth` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_Id`, `First_Name`, `Middle_Initial`, `Last_Name`, `Street_No`, `Street_Name`, `Suburb`, `Postcode`, `Email`, `Phone`, `Auth`, `Enabled`) VALUES
('001484', 'William', 'B', 'Pitt', '200', 'St. Kilda Road', 'St. Kilda', '3147', 'bill.pitt@gmail.com', '0351806451', '3ad4c7d4e8de515fccb1f2af7e5c6396', 0),
('008099', 'James', NULL, 'Mangold', '646', 'fw street', 'Melbourne', '3000', 'james.mang@gmail.com', NULL, '9f6d7ffa1c8324790802c8c667159f40', 0),
('031642', 'Freddie', NULL, 'Khan', '500', 'Waverly Road', 'Chadstone', '3555', 'fred.khan@holmesglen.edu.au', NULL, '67c13c951b1e10bd9de0949615087f14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_review`
--

CREATE TABLE `customer_review` (
  `Trip_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Customer_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Rating` tinyint(1) NOT NULL,
  `General_Feedback` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Likes` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Dislikes` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_review`
--

INSERT INTO `customer_review` (`Trip_Id`, `Customer_Id`, `Rating`, `General_Feedback`, `Likes`, `Dislikes`) VALUES
('004572', '031642', 5, 'Excellent trip, I will be booking with you guys again next year!', 'The whole trip was very reasonably priced.', 'None!'),
('004640', '001484', 3, 'It was okay, not as good as Kontiki', 'Staff were nice', 'The food was rubbish'),
('525342', '001484', 4, 'Better than the last one', 'Staff were nice like last time and the food was better', 'The tour bus was too noisy');

-- --------------------------------------------------------

--
-- Table structure for table `itinerary`
--

CREATE TABLE `itinerary` (
  `Tour_No` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `Day_No` tinyint(1) NOT NULL,
  `Hotel_Booking_No` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Activities` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Meals` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `itinerary`
--

INSERT INTO `itinerary` (`Tour_No`, `Day_No`, `Hotel_Booking_No`, `Activities`, `Meals`) VALUES
('047', 1, '000599', 'Wine tasting at Pizzini\'s', 'Lunch at Pizzini\'s'),
('047', 2, '000552', 'Rock Climbing at mountain-smarter', 'Dinner at hotel'),
('047', 3, '000293', 'Guided tour around the valley', 'Lunch at SmallVille food'),
('055', 1, '000342', 'Guided tour around the CBD', 'Lunch on Lygon Street');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(344, '2014_10_12_000000_create_users_table', 1),
(345, '2014_10_12_100000_create_password_resets_table', 1),
(346, '2017_04_30_143121_create_booking_table', 1),
(347, '2017_04_30_143121_create_customer_review_table', 1),
(348, '2017_04_30_143121_create_customer_table', 1),
(349, '2017_04_30_143121_create_itinerary_table', 1),
(350, '2017_04_30_143121_create_tour_table', 1),
(351, '2017_04_30_143121_create_trip_table', 1),
(352, '2017_04_30_143121_create_vehicle_table', 1),
(353, '2017_04_30_143122_add_foreign_keys_to_booking_table', 1),
(354, '2017_04_30_143122_add_foreign_keys_to_customer_review_table', 1),
(355, '2017_04_30_143122_add_foreign_keys_to_itinerary_table', 1),
(356, '2017_04_30_143122_add_foreign_keys_to_trip_table', 1),
(357, '2017_05_04_012736_AddEnabledCustomer', 1),
(358, '2017_05_04_044016_ChangeStreetAndPhoneDatatype', 1),
(359, '2017_05_07_142233_LimitCharForStreetPostcodePhone', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `Tour_No` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `Tour_Name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Duration` double DEFAULT NULL,
  `Route_Map` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`Tour_No`, `Tour_Name`, `Description`, `Duration`, `Route_Map`) VALUES
('021', 'Twelve Apostles Drive', 'A drive along the Great Ocean Road to the Twelve Apostles', 28, NULL),
('047', 'Northeast Wineries Tour', 'A tour to various wineries in North East Victoria', 32, NULL),
('055', 'Melbourne Sightseeing', 'A drive along the Great Ocean Road to the Twelve Apostles', 3.5, 'C:\\Documents\\Route_Maps\\Melbourne_Sightseeing.png');

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `Trip_Id` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Tour_No` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `Rego_No` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `Departure_Date` date DEFAULT NULL,
  `Max_Passengers` int(11) NOT NULL,
  `Standard_Amount` decimal(6,2) DEFAULT NULL,
  `Concession_Amount` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`Trip_Id`, `Tour_No`, `Rego_No`, `Departure_Date`, `Max_Passengers`, `Standard_Amount`, `Concession_Amount`) VALUES
('004572', '055', 'EIU112', '2016-05-15', 62, '100.00', '80.00'),
('004640', '055', 'EIU112', '2016-06-23', 62, '200.00', '150.00'),
('020223', '047', 'JDO682', '2017-09-18', 5, '130.00', '90.00'),
('257498', '021', 'JDO682', '2014-12-02', 3, '100.00', '80.00'),
('343271', '021', 'JDO682', '2016-12-04', 3, '400.00', '250.00'),
('445242', '047', 'JDO682', '2017-03-02', 2, '520.00', '400.00'),
('525342', '047', 'TPO652', '2015-10-12', 51, '120.00', '100.00'),
('644261', '047', 'TPO652', '2017-02-05', 15, '420.00', '300.00'),
('644621', '021', 'TPO652', '2015-04-30', 51, '140.00', '120.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `Rego_No` char(6) COLLATE utf8_unicode_ci NOT NULL,
  `VIN` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Make` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Model` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `Year` int(11) NOT NULL,
  `Capacity` smallint(6) NOT NULL,
  `Fuel_Type` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Equipment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `License_Required` char(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`Rego_No`, `VIN`, `Make`, `Model`, `Year`, `Capacity`, `Fuel_Type`, `Equipment`, `License_Required`) VALUES
('AKJ424', '8Y2340JDSNKL9HGS9', 'BCI', 'Fleetmaster 55', 2010, 87, 'Diesel', 'Fire extinguisher, 5 tents, 3 kayaks', 'MR'),
('EIU112', 'SPG4VLEHSDZ98U454', 'Scania', 'K230UB', 2007, 64, 'Diesel', NULL, 'MR'),
('JDO682', '90JERN34F9DF3450F', 'Holden', 'Commodore', 2008, 5, 'Petrol', NULL, 'C'),
('MCN687', 'T3NF8S0D99l9FK6V5', 'BCI', 'Proma', 2011, 35, 'Diesel', 'Fire extinguisher', 'LR'),
('TPO652', '90S8U449S8G9K5N8L', 'Scania', 'K320UB', 2010, 53, 'Diesel', NULL, 'HR');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_No`),
  ADD KEY `B_Customer_Id_fk` (`Customer_Id`),
  ADD KEY `B_Trip_Id_fk` (`Trip_Id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_Id`);

--
-- Indexes for table `customer_review`
--
ALTER TABLE `customer_review`
  ADD PRIMARY KEY (`Trip_Id`,`Customer_Id`),
  ADD KEY `CR_Customer_fk` (`Customer_Id`);

--
-- Indexes for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD PRIMARY KEY (`Tour_No`,`Day_No`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`Tour_No`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`Trip_Id`),
  ADD KEY `T_Tour_fk` (`Tour_No`),
  ADD KEY `T_Vehicle_fk` (`Rego_No`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`Rego_No`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `B_Customer_Id_fk` FOREIGN KEY (`Customer_Id`) REFERENCES `customer` (`Customer_Id`),
  ADD CONSTRAINT `B_Trip_Id_fk` FOREIGN KEY (`Trip_Id`) REFERENCES `trip` (`Trip_Id`);

--
-- Constraints for table `customer_review`
--
ALTER TABLE `customer_review`
  ADD CONSTRAINT `CR_Customer_fk` FOREIGN KEY (`Customer_Id`) REFERENCES `customer` (`Customer_Id`),
  ADD CONSTRAINT `CR_Trip_fk` FOREIGN KEY (`Trip_Id`) REFERENCES `trip` (`Trip_Id`);

--
-- Constraints for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD CONSTRAINT `I_Tour_fk` FOREIGN KEY (`Tour_No`) REFERENCES `tour` (`Tour_No`);

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `T_Tour_fk` FOREIGN KEY (`Tour_No`) REFERENCES `tour` (`Tour_No`),
  ADD CONSTRAINT `T_Vehicle_fk` FOREIGN KEY (`Rego_No`) REFERENCES `vehicle` (`Rego_No`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
