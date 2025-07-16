-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 04:31 PM
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
-- Database: `sasak_heritage`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `booking_type` enum('bus','hotel') NOT NULL,
  `destination` varchar(100) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `item_id`, `booking_type`, `destination`, `booking_date`, `status`) VALUES
(1, 1, 1, 'bus', 'Pantai Seger', '2025-07-10 01:15:00', 'confirmed'),
(2, 2, 3, 'bus', 'Gunung Rinjani', '2025-07-11 23:30:00', 'confirmed'),
(3, 3, 2, 'bus', 'Desa Adat Sade', '2025-07-15 02:00:00', 'pending'),
(4, 1, 2, 'hotel', 'Kuta Mandalika', '2025-07-11 06:00:00', 'confirmed'),
(5, 2, 1, 'hotel', 'Senggigi', '2025-07-13 04:00:00', 'cancelled'),
(6, 3, 3, 'hotel', 'Gunung Rinjani', '2025-07-16 08:30:00', 'pending'),
(7, 2, 1, 'bus', 'Pantai Seger', '2025-07-16 02:22:08', 'pending'),
(8, 2, 4, 'hotel', '', '2025-07-16 02:27:22', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `budaya_sasak`
--

CREATE TABLE `budaya_sasak` (
  `id` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budaya_sasak`
--

INSERT INTO `budaya_sasak` (`id`, `kategori`, `judul`, `deskripsi`, `gambar`, `created_at`) VALUES
(1, 'tenun', 'Subahnale', 'Dzikir syukur perempuan Sasak', 'tenun_subahnale.jpg', '2025-07-16 05:56:51'),
(2, 'tenun', 'Ragi Genep', 'Keseimbangan dunia & spiritual', 'tenun_ragi_genep.jpg', '2025-07-16 05:56:51'),
(3, 'tenun', 'Patola', 'Perlindungan dan kebangsawanan', 'tenun_patola.jpg', '2025-07-16 05:56:51'),
(4, 'rumah', 'Bale Tani', 'Rumah inti keluarga petani', 'rumah_bale_tani.jpg', '2025-07-16 05:56:51'),
(5, 'rumah', 'Bale Kodong', 'Hunian khusus lansia', 'rumah_bale_kodong.jpg', '2025-07-16 05:56:51'),
(6, 'rumah', 'Atap Alang-Alang', 'Simbol kesejukan & kesucian', 'rumah_atap_alang.jpg', '2025-07-16 05:56:51'),
(7, 'musik', 'Gendang Beleq', 'Orkestra tradisional dengan gendang besar, dimainkan dalam upacara adat', 'musik_gendang_beleq.jpg', '2025-07-16 05:56:51'),
(8, 'musik', 'Sasando Lombok', 'Alat musik petik berdawai dengan resonator bambu', 'musik_sasando.jpg', '2025-07-16 05:56:51'),
(9, 'musik', 'Tari Gandrung', 'Tari pergaulan yang melambangkan kegembiraan', 'tari_gandrung.jpg', '2025-07-16 05:56:51'),
(10, 'musik', 'Peresean', 'Seni bela diri tradisional dengan rotan dan perisai', 'tari_peresean.jpg', '2025-07-16 05:56:51'),
(11, 'pakaian', 'Pejambon', 'Busana pria bangsawan dengan keris dan destar', 'pakaian_pejambon.jpg', '2025-07-16 05:56:51'),
(12, 'pakaian', 'Lambung', 'Blus hitam wanita dengan songket, simbol keteguhan', 'pakaian_lambung.jpg', '2025-07-16 05:56:51'),
(13, 'pakaian', 'Dodot', 'Kain panjang untuk upacara dan pernikahan', 'pakaian_dodot.jpg', '2025-07-16 05:56:51'),
(14, 'pakaian', 'Sapuq', 'Ikat kepala pria Sasak dengan motif khusus', 'pakaian_sapuq.jpg', '2025-07-16 05:56:51'),
(15, 'bahasa', 'Kance Tuan', 'Sapaan untuk sahabat karib', 'bahasa_kance_tuan.jpg', '2025-07-16 05:56:51'),
(16, 'bahasa', 'Saq Inaq, Saq Amaq', 'Ungkapan menghormati orang tua', 'bahasa_hormat_ortu.jpg', '2025-07-16 05:56:51'),
(17, 'bahasa', 'Lombok', 'Berasal dari kata \"Lomboq\" yang berarti lurus/jujur', 'bahasa_lombok.jpg', '2025-07-16 05:56:51'),
(18, 'bahasa', 'Moriq', 'Ungkapan untuk sesuatu yang indah/menakjubkan', 'bahasa_moriq.jpg', '2025-07-16 05:56:51'),
(19, 'makanan', 'Ayam Taliwang', 'Ayam bakar pedas dengan bumbu rempah khas', 'makanan_taliwang.jpg', '2025-07-16 05:56:51'),
(20, 'makanan', 'Plecing Kangkung', 'Sayur kangkung dengan sambal lombok terasi', 'makanan_plecing.jpg', '2025-07-16 05:56:51'),
(21, 'makanan', 'Sate Bulayak', 'Sate dengan lontong khas dan kuah kacang', 'makanan_bulayak.jpg', '2025-07-16 05:56:51'),
(22, 'makanan', 'Ares', 'Olahan batang pisang dengan santan dan rempah', 'makanan_ares.jpg', '2025-07-16 05:56:51'),
(23, 'makanan', 'Beberuk Terong', 'Lalapan terong dengan sambal tomat segar', 'makanan_beberuk.jpg', '2025-07-16 05:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name`, `capacity`, `price`, `available`) VALUES
(1, 'Express Deluxe', 50, '25.99', 1),
(2, 'City Shuttle', 30, '15.50', 1),
(3, 'Premium Coach', 40, '35.75', 1),
(4, 'Eco Traveler', 45, '20.00', 0),
(5, 'Night Rider', 35, '28.99', 1),
(6, 'Mountain View', 25, '40.25', 1),
(7, 'Coastal Cruiser', 55, '22.50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bus_bookings`
--

CREATE TABLE `bus_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `departure` varchar(50) NOT NULL,
  `destination` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `passengers` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cultural_items`
--

CREATE TABLE `cultural_items` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('tenun','rumah','musik','bahasa','pakaian','makanan') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `culture_views`
--

CREATE TABLE `culture_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `view_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `culture_views`
--

INSERT INTO `culture_views` (`id`, `user_id`, `category`, `view_time`) VALUES
(1, 1, 'Tenun Ikat & Maknanya', '2025-07-01 10:15:00'),
(2, 1, 'Struktur Rumah Adat', '2025-07-01 10:20:00'),
(3, 1, 'Alat Musik & Tarian', '2025-07-01 10:25:00'),
(4, 1, 'Tenun Ikat & Maknanya', '2025-07-02 14:30:00'),
(5, 1, 'Pakaian Adat Sasak', '2025-07-03 09:45:00'),
(6, 2, 'Struktur Rumah Adat', '2025-07-16 05:49:17'),
(7, 2, 'Struktur Rumah Adat', '2025-07-16 05:57:04'),
(8, 2, 'Alat Musik & Tarian', '2025-07-16 05:57:07'),
(9, 2, 'Struktur Rumah Adat', '2025-07-16 06:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `long_description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `short_description`, `long_description`, `image_url`, `video_url`, `created_at`) VALUES
(1, 'Pantai Seger', 'Lokasi Bau Nyale, tradisi tahunan pencarian Putri Mandalika.', 'Saat fajar bulan Februari/Maret, warga berkumpul memungut nyale (cacing laut) yang diyakini jelmaan Putri Mandalika. Ritual ini menggambarkan ketulusan pengorbanan dan persatuan masyarakat Sasak.', 'https://travelspromo.com/wp-content/uploads/2022/02/Keindahan-pantai-seger-lombok-berlatar-bukit.jpeg', 'https://youtu.be/E_3BAFz7vDU?si=BQDBRb3fuWyps5mg', '2025-07-16 01:44:42'),
(2, 'Desa Adat Sade', 'Kampung tradisional dengan rumah bale tani beratap alang.', 'Lantai rumah diolesi kotoran kerbau tiap minggu agar tetap sejuk & suci. Pola tenun \"Songket Subahnale\" terinspirasi dari dzikir syukur.', 'https://cdn.idntimes.com/content-images/community/2020/01/19424765-245953129236714-810761388782780416-n-6fa4cbbd82db936bbc84478e9a40e3fc.jpg', 'https://youtu.be/5pTOQRqSVoQ?si=kIeWZfUImSpEEUpj', '2025-07-16 01:44:42'),
(3, 'Gunung Rinjani', 'Gunung sakral setinggi 3.726 m.', 'Pendakian dimulai dengan upacara Begawe memohon restu. Danau Segara Anak di kawah dipercaya membersihkan jiwa pendaki.', 'https://media.suara.com/pictures/970x544/2025/06/26/40338-ilustrasi-gunung-rinjani.jpg', 'https://youtu.be/379EEoRIbIs?si=eQPb5hjXwEfkFfEB', '2025-07-16 01:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stars` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `stars`, `price`, `available`) VALUES
(1, 'Grand Plaza', 5, '199.99', 1),
(2, 'Seaside Resort', 4, '149.50', 1),
(3, 'Mountain Lodge', 3, '89.75', 1),
(4, 'City Center Inn', 2, '59.99', 0),
(5, 'Royal Gardens', 5, '249.00', 1),
(6, 'Budget Stay', 1, '39.99', 1),
(7, 'Lakeside Retreat', 4, '129.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_bookings`
--

CREATE TABLE `hotel_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `guests` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('tenun','kopi','kuliner','kerajinan') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `gmaps_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourguide`
--

CREATE TABLE `tourguide` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT 'Male',
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','user','technician') DEFAULT 'user',
  `is_suspended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `phone`, `country`, `gender`, `profile_picture`, `role`, `is_suspended`) VALUES
(1, 'Admin Sasak', 'admin', 'admin123', '0811111111', 'Indonesia', 'Male', NULL, 'admin', 0),
(2, 'Warga Lokal', 'lokal01', 'lokal123', '0822222222', 'Indonesia', 'Female', NULL, 'user', 0),
(3, 'Dinas Pariwisata', 'dinaspariwisata', 'dinas123', '0833333333', 'Indonesia', 'Male', NULL, '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `budaya_sasak`
--
ALTER TABLE `budaya_sasak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_bookings`
--
ALTER TABLE `bus_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `cultural_items`
--
ALTER TABLE `cultural_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `culture_views`
--
ALTER TABLE `culture_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tourguide`
--
ALTER TABLE `tourguide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `budaya_sasak`
--
ALTER TABLE `budaya_sasak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bus_bookings`
--
ALTER TABLE `bus_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cultural_items`
--
ALTER TABLE `cultural_items`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `culture_views`
--
ALTER TABLE `culture_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tourguide`
--
ALTER TABLE `tourguide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_bookings`
--
ALTER TABLE `bus_bookings`
  ADD CONSTRAINT `bus_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bus_bookings_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`);

--
-- Constraints for table `culture_views`
--
ALTER TABLE `culture_views`
  ADD CONSTRAINT `culture_views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  ADD CONSTRAINT `hotel_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_bookings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `tourguide`
--
ALTER TABLE `tourguide`
  ADD CONSTRAINT `tourguide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
