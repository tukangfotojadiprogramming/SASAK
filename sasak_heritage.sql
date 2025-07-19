-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 12:10 PM
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
  `items` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `departure_date` date DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `booking_date` datetime NOT NULL,
  `status` enum('pending','paid','completed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `bus_route` varchar(100) NOT NULL,
  `departure_date` date NOT NULL,
  `passengers` int(11) NOT NULL,
  `bus_type` varchar(50) NOT NULL,
  `booking_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
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
(3, 'Gunung Rinjani', 'Gunung sakral setinggi 3.726 m.', 'Pendakian dimulai dengan upacara Begawe memohon restu. Danau Segara Anak di kawah dipercaya membersihkan jiwa pendaki.', 'https://media.suara.com/pictures/970x544/2025/06/26/40338-ilustrasi-gunung-rinjani.jpg', 'https://youtu.be/379EEoRIbIs?si=eQPb5hjXwEfkFfEB', '2025-07-16 01:44:42'),
(4, 'Pantai Mandalika', 'Pantai Mandalika adalah destinasi pantai eksotis di Lombok Tengah yang terkenal dengan pasir putihnya yang halus, ombak sempurna untuk berselancar, dan pemandangan matahari terbenam yang memukau. ', 'Pantai Mandalika, terletak di Lombok Tengah, Nusa Tenggara Barat, adalah salah satu destinasi pantai terindah di Indonesia. Dengan hamparan pasir putih yang lembut dan air laut biru jernih, pantai ini menawarkan pengalaman wisata yang memesona. Mandalika terkenal dengan ombaknya yang konsisten, menjadikannya surga bagi para peselancar dari berbagai tingkatan.', 'https://firstlomboktour.com/wp-content/uploads/2018/03/Tanjung-Aan-di-Lombok-pantai-dekat-bandara-sumber-ig-megadestination.jpg.webp', 'https://youtu.be/VCPH5XSb7l8?si=hGBaUPkPUChIz2fw', '2025-07-16 01:44:42'),
(5, 'Bukit Merese', 'Bukit savana dengan panorama laut selatan Lombok.', 'Bukit Merese adalah spot populer untuk menyaksikan matahari terbit dan terbenam. Dari puncak bukit, pengunjung dapat menikmati pemandangan Tanjung Aan dan laut biru yang membentang.', 'https://asset.kompas.com/crops/C0KroT8WupyYWmg2ONUvcYO6Emc=/0x0:740x370/1200x800/data/photo/2021/03/19/6054685c1e47e.jpg', 'https://youtu.be/WCgaO66GBOk?si=1woGInw1_CPfAknf', '2025-07-16 01:44:42'),
(6, 'Desa Adat Ende', 'Replika desa Sasak kuno dengan budaya masih lestari.', 'Desa Ende merupakan desa adat yang mempertahankan rumah tradisional Sasak dari anyaman bambu dan alang-alang. Wisatawan bisa menyaksikan langsung tarian perang \"Peresean\" dan kehidupan lokal warga.', 'https://i0.wp.com/labirutour.com/wp-content/uploads/2023/10/image6.png?w=601&ssl=1', 'https://youtu.be/Gc6i6I7U9No?si=4b0OFwVMnEROgDFK', '2025-07-16 01:44:42'),
(7, 'Ayam Taliwang', 'Ayam kampung muda yang dibakar dengan sambal khas Taliwang berbahan dasar cabai merah, bawang putih, terasi, dan tomat.', 'Ayam Taliwang biasa disajikan dengan plecing kangkung dan nasi hangat. Makanan ini berasal dari Kampung Taliwang dan kini telah menjadi ikon kuliner Lombok.', 'https://basfood.id/wp-content/uploads/2023/02/Ayam-Taliwang-Khas-Lombok.jpg', 'https://www.youtube.com/watch?si=dEVMI1n2VTiUe-db&embeds_referring_euri=https%3A%2F%2Fid.video.search.yahoo.com%2F&embeds_referring_origin=https%3A%2F%2Fid.video.search.yahoo.com&source_ve_path=MTY0NTA2&v=Iw_KY_HiQWo&feature=youtu.be', '2025-07-18 13:23:08'),
(8, 'Beberuk Terong', 'Beberuk Terong adalah lalapan segar dari terong mentah dan sambal mentah khas Sasak.', 'Salad khas Lombok yang terdiri dari terong ungu mentah yang diiris tipis lalu disiram sambal tomat mentah. Segar, pedas, dan menambah selera makan.', 'https://i.ytimg.com/vi/9RRZ1_3S4Hc/maxresdefault.jpg', 'https://youtu.be/Fx5YXfSp1G8?si=H9Cx0G7gmpiVrTiw', '2025-07-18 13:23:08'),
(9, 'Plecing Kangkung', 'Plecing Kangkung adalah sayuran khas Lombok yang disiram sambal tomat pedas. ', 'Kangkung rebus disiram sambal tomat khas Lombok dengan taburan kacang tanah goreng dan perasan jeruk limau. Cocok jadi lauk pendamping utama.', 'https://img-global.cpcdn.com/recipes/603d5133a1c5873c/1200x630cq70/photo.jpg', 'https://youtu.be/CvShDN4CdsU?si=lb9-igPP9ftFh7Yr', '2025-07-18 13:23:08'),
(10, 'Sate Rembiga', 'Sate Rembiga berasal dari daerah Rembiga, Kota Mataram.', 'Sate daging sapi khas Rembiga, Lombok, yang dibumbui cabai rawit, bawang putih, ketumbar, dan gula merah. Rasanya manis pedas dan sangat juicy.', 'https://cdn0-production-images-kly.akamaized.net/EAbi53ClPFWop0gOwkZQS0J9Rjc=/1x135:1000x698/1200x675/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/4703886/original/010040500_1704118595-shutterstock_2290464261.jpg', 'https://youtu.be/6tFOm4dOBZY?si=Je0mXNjetlS_5EFP', '2025-07-18 13:23:08'),
(11, ' Sate Pusut', 'Cita rasa gurih dari kelapa parut dan bumbu khas membuat sate ini berbeda.', 'Sate khas Sasak dari daging sapi atau ayam yang dicampur kelapa parut, bumbu rempah, lalu dipusuti (dibungkus) ke tusuk sate sebelum dibakar.', 'https://cdn0-production-images-kly.akamaized.net/mmup8UVh94NKgpK0uybv68PA5tA=/0x0:5184x2922/1200x675/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/3652549/original/060993000_1638588819-shutterstock_1729690198.jpg', 'https://youtu.be/uHTzfK94T00?si=jmf-CYmDKQdsdMhn', '2025-07-18 13:23:08'),
(12, 'Ares', 'Ares adalah sayur khas Sasak yang terbuat dari batang pisang muda', 'Sayur khas Sasak yang terbuat dari batang pisang muda dimasak dengan santan dan bumbu khas. Rasanya gurih dan teksturnya lembut.', 'https://cdn.idntimes.com/content-images/community/2022/03/sayur-ares-3-1dfec209c12f3cdc32ed90758fc75423-00cbaa7e23e18bbd7d2fa4c42d8ad3bb.jpg', 'https://youtu.be/TPDjT7WAh7A?si=J_j9lTjLJAqF5Bei', '2025-07-18 13:23:08'),
(13, 'Tari Rudat', 'Tari Rudat ini sangat kental akan nuansa Islami baik dari segi kostum, lagu maupun pengiring pertunjukan.', 'Tarian ini digunakan para Ulama terdahulu sebagai media penyebaran agama Islam. Banyak yang mengatakan pula bahwa, Tari Rudat ini merupakan perkembangan dari Dzikir Saman dan Budrah.', 'https://insidelombok.id/wp-content/uploads/2021/03/5-9.jpg', 'https://youtu.be/4fHFfeaTXAA?si=o0DUlF8MqKLL5W9C', '2025-07-18 13:23:08'),
(14, 'Tarian Peresean', 'Pertarungan tradisional antara dua pepadu (petarung) menggunakan rotan dan perisai.', 'Peresean bukan sekadar tarian, tetapi ritual keberanian dan ajang penguatan mental masyarakat Sasak. Tradisi Peresean bukan hanya untuk ritual dan acara kerajaan, melainkan juga menjadi daya tarik luar biasa untuk menyambut para wisatawan yang berkunjung.', 'https://helloindonesia.id/wp-content/uploads/2019/03/BicVLwGCQAAvElP.jpglarge.jpeg', 'https://youtu.be/WP-wQAxF5uA?si=XmAOEpuBbsWfBRrT', '2025-07-18 13:23:08'),
(15, 'Tarian Gandrung Sasak', 'Tarian ini menggambarkan keanggunan perempuan Sasak serta keramahan dalam menyambut tamu dan merayakan hasil panen.', 'Tari pergaulan yang dibawakan oleh penari wanita sebagai ungkapan rasa syukur dan suka cita, biasanya diiringi gamelan Sasak. Tari ini sangat berbeda dari tarian lainnya, dapat ditemukan pada gerakan, kostum maupun penyajian pertunjukannya.', 'https://1.bp.blogspot.com/-2JFrlGf2k7w/YGfy_MJtqSI/AAAAAAAABwU/6MWbTP8_aZY7Yy4RSX4VQ6KyGTkAWAlKgCLcBGAsYHQ/s1024/Tari%2BGandrung%2BLombok.jpg', 'https://youtu.be/5f9pobX1fPQ?si=zZk4gkrWiDWqqbwY', '2025-07-18 13:23:08'),
(16, 'Gendang Beleq ', 'Gendang Beleq terdiri dari dua jenis: gendang mama (jantan) dan gendang nina (betina).', 'Gendang raksasa yang dimainkan secara berkelompok, biasanya dalam upacara adat seperti nyongkolan atau penyambutan tamu agung.', 'https://pelopor.id/wp-content/uploads/2021/12/Gendang-Beleq.jpg', 'https://youtu.be/gW7aQRl8c0c?si=mMMTBAPtPlAZ1ZNx', '2025-07-18 13:23:08'),
(17, 'Suling Sasak', 'Alat musik tiup tradisional yang menghasilkan melodi lembut dan menyayat.', 'Suling dibuat dari bambu dengan lubang nada yang disesuaikan dengan tangga nada khas Lombok. Ditiup dengan teknik khusus agar harmonis.', 'https://i.ytimg.com/vi/mx3B_MARm0g/maxresdefault.jpg', 'https://youtu.be/KCbYF1lHdqk?si=keqeL29sl37f-wrr', '2025-07-18 13:23:08'),
(18, 'Rebab Sasak', 'Alat musik gesek dua dawai dari kayu dan kulit binatang.', 'Permainan rebab membutuhkan kehalusan dan perasaan, karena setiap nada mengekspresikan emosi mendalam dari kebudayaan Sasak.', 'https://gomandalika.com/wp-content/uploads/2022/10/PENTING-SASAK-scaled.jpg', 'https://youtu.be/NyFFWaJf5J4?si=Tjz3oY9o8IuynZ0v', '2025-07-18 13:23:08'),
(19, 'Baju Adat Pria', 'Pria Sasak mengenakan pelet kain songket yang dililitkan di pinggang, kemeja lengan panjang, dan ikat kepala sapuq.', 'Pakaian adat pria Sasak, yang disebut Pegon, adalah busana tradisional yang digunakan dalam upacara adat seperti Nyongkolan dan pernikahan. Pakaian ini merupakan perpaduan pengaruh budaya Jawa dan Eropa, mencerminkan keagungan dan kesopanan', 'https://mobillombok.com/wp-content/uploads/2019/03/1-e1552375700422.jpg', 'https://youtu.be/aZdqrm_MV68?si=j5sEaaGffM34Kpe9', '2025-07-18 13:23:08'),
(20, 'Baju Adat Wanita', 'Wanita mengenakan kebaya khas Lombok dan kain songket yang ditenun tangan dengan motif simbolik, serta hiasan kepala dan selendang.', 'Pakaian ini biasanya dikenakan saat acara pernikahan atau menyambut tamu. Lambung didesain sederhana, seringkali berwarna hitam, dan memiliki makna filosofis yang mendalam, melambangkan keanggunan, kekuatan, dan keteguhan hati wanita Sasak', 'https://mobillombok.com/wp-content/uploads/2019/01/LombokSumbawa201371392714_preview.jpg', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(21, 'Rumah Adat Bale', 'Bale merupakan rumah tradisional suku Sasak yang digunakan sebagai tempat tinggal sehari-hari dan memiliki struktur panggung.', 'Rumah Bale memiliki lantai dari tanah liat yang dicampur kotoran kerbau untuk kekuatan. Atapnya terbuat dari alang-alang dengan struktur bambu. Rumah ini biasanya tidak memiliki jendela dan hanya satu pintu sebagai simbol kesederhanaan.', 'https://akcdn.detik.net.id/community/media/visual/2018/09/24/82338968-8540-45a7-ad20-b68f7186315f_169.jpeg?w=620', 'https://youtu.be/YED6iZaDZ8k?si=wem5WFqwRbQLuDZl', '2025-07-18 13:23:08'),
(22, 'Sapu\'', 'Sapu\' juga terbagi menjadi dua jenis yakni sapu\' nganjeng dan sapu\' lepek, cara pemakaian dan fungsinya juga berbeda.', 'Sapuk\' bukan sekadar hiasan, melainkan memiliki makna mendalam terkait identitas, kedudukan sosial, dan penghormatan kepada Tuhan serta menjaga pikiran. Ada berbagai jenis sapuk dengan cara pemakaian yang berbeda, yang masing-masing memiliki makna tersendiri. ', 'https://static.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/p1/348/2024/11/30/images-1-2934614.jpg', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(23, 'Keris', 'Keris bagi orang sasak tidak hanya dikenal sebagai hiasan semata, tapi dikenal pula sebagai senjata.', 'Keris Sasak adalah senjata tradisional Suku Sasak di Lombok yang memiliki nilai budaya dan filosofis yang dalam. Selain sebagai senjata, keris juga dianggap sebagai simbol status, benda pusaka, dan memiliki kekuatan magis. Keris Sasak memiliki ciri khas yang mirip dengan keris Bali, yang merupakan hasil akulturasi budaya antara Kerajaan Klungkung dan Lombok setelah runtuhnya Majapahit', 'https://image.idntimes.com/post/20231015/kerismotogp2-c7c8a956c08d5240d23a9803c984bd4e-e19483fc8f6d7cbccb2f5010b5cbded1.jpg?tr=w-1080,f-webp,q-75&width=1080&format=webp&quality=75', 'https://youtube.com/shorts/kR1-TLWSpLs?si=H44gyD7E-QrIPCja', '2025-07-18 13:23:08'),
(24, 'Kelambi Pegon', 'Baju pegon adalah jas tutup yang kerahnya berdiri dengan diberi kancing mulai dari leher terus sampai ke bawah.', 'Baju adat Pegon adalah pakaian tradisional laki-laki Suku Sasak, Lombok. Pakaian ini memiliki beberapa bagian yang masing-masing memiliki makna simbolis. Baju Pegon sendiri terinspirasi dari pakaian adat Jawa dan model jas Eropa, biasanya berwarna gelap', 'https://www.jokembe.com/source/Pakaian%20pegon%20pakaian%20adat%20lombok.jpg', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(25, 'Pangkak Kedebong Malang', 'Pangkak kedebong malang mengandung makna bahwa pemakainya diharapkan mempunyai ketetapan hati yang kokoh.', 'Bentuk pangkak kedebong malang ini menyerupai angka delapan dengan ukuran lebar kurang lebih 20 cm dan tinggi 8 cm.', 'https://www.jokembe.com/source/Pangkak%20kedebong%20malang%20beserta%20hiasan-hiasan%20yang%20ada%20di%20pangkak%20kedebong%20malang.jpg', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(26, 'Lenteran Suku', 'Mempunyai makna simbolik akan kesuburan. Wanita yang subur bagi orang sasak, karena dapat memberikan keturunan yang banyak.', 'Mempunyai makna simbolik akan kesuburan, sama dengan lenteran suku- suku. Wanita yang subur bagi orang sasak terutama pada zaman dahulu dianggap sebagai wanita ideal.', 'https://www.jokembe.com/source/Berbagai%20Asesoris%20Pakaian%20Pengantin%20Wanita%20Sasak%20Lombok.jpg', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(27, 'Pending/Sabuk Emas', 'Pending merupakan perhiasan yang berharga, biasanya dipergunakan di pinggang sebagai sabuk pengantin.', 'Bagian kepala sabuknya terdapat sebuah permata terbuat dari intan yang berukuran lebih besar terdapat ditengah-tengahnya sehingga menciptakan keserasian dan keindahan tersendiri.', 'https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full//catalog-image/94/MTA-147156322/made_sukawati_sabuk_pending_tembaga_kerajinan_ukiran_ikat_pinggang_pengantin_adat_bali_full02_e6lknfr0.jpg?w=1152', 'https://youtu.be/8cEtoClm1N0?si=g4D_tCgUAjV6eni1', '2025-07-18 13:23:08'),
(28, 'Rumah Adat Bale Jajar', 'Rumah Bale Jajar biasanya terdiri dari dua bale yang diletakkan sejajar dan digunakan oleh keluarga besar di Lombok.', 'Satu bale digunakan untuk tidur dan yang lainnya untuk menerima tamu atau kegiatan keluarga. Antara kedua bale biasanya terdapat halaman kecil yang digunakan untuk kegiatan sehari-hari seperti menjemur hasil panen.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOIY_Ggycfwi3_u5lZ8BPLB172NcWjJqlb4w&s', 'https://youtu.be/YED6iZaDZ8k?si=wem5WFqwRbQLuDZl', '2025-07-18 13:51:36'),
(29, 'Rumah Bale Lumbung', 'Bale Lumbung berfungsi untuk menyimpan padi dan hasil panen. Memiliki bentuk unik dengan atap seperti tanduk.', 'Bentuk atap yang melengkung seperti tanduk kerbau melambangkan kemakmuran. Lumbung ini dibangun tinggi untuk menghindari serangan hama dan memiliki sistem ventilasi alami untuk menjaga kualitas padi.', 'https://akcdn.detik.net.id/community/media/visual/2016/09/25/48aa35ee-b4d5-4101-9caa-38c97666ac12.jpg?w=5616', 'https://youtu.be/rF_3CpgGinU?si=JFXOBdQe_i0f-FSS', '2025-07-18 13:51:36'),
(30, 'Rumah Berugaq Sekenam', 'Berugaq Sekenam adalah bangunan terbuka tradisional dengan enam tiang utama, biasanya digunakan untuk musyawarah atau istirahat.', 'Enam tiang utama melambangkan rukun iman dalam Islam. Bangunan ini tanpa dinding sebagai simbol keterbukaan masyarakat Sasak. Biasanya terletak di depan rumah utama sebagai tempat menerima tamu.', 'https://akcdn.detik.net.id/community/media/visual/2022/11/14/berugaq.jpeg?w=640', 'https://youtu.be/__nxjOY6jJY?si=Ob0jzcJ-j3oMdd4m', '2025-07-18 13:51:36'),
(31, 'Istana Sumbawa', 'Istana Bala Kuning merupakan istana kerajaan Sumbawa yang dibangun pada masa kesultanan, simbol kemegahan arsitektur lokal.', 'Dibangun dengan 99 tiang yang melambangkan 99 nama Allah dalam Islam. Istana ini menggabungkan arsitektur lokal dengan pengaruh Bugis dan Jawa. Kini berfungsi sebagai museum yang menyimpan berbagai artefak kerajaan.', 'https://awsimages.detik.net.id/community/media/visual/2020/11/12/rumah-dalam-loka_169.png?w=1200', 'https://youtu.be/P72viYjqoXQ?si=6FiYBC5GrFe8ZFJ0', '2025-07-18 13:51:36'),
(32, 'Rumah Bale Tani', 'Bale Tani adalah rumah tradisional masyarakat petani Sasak, terbuat dari bahan alami dengan konstruksi sederhana namun kokoh.', 'Memiliki tiga bagian utama: serambi untuk menerima tamu, ruang tengah untuk keluarga, dan dapur di belakang. Atap yang rendah melambangkan kerendahan hati, sementara struktur panggung melindungi dari banjir dan binatang.', 'https://images.genpi.co/resize/1280x860-100/uploads/ntb/arsip/normal/2022/02/27/bale-tani-rumah-tradisi-masyarakat-sasak-foto-majelis-a-o89t.webp', 'https://youtu.be/rF_3CpgGinU?si=JFXOBdQe_i0f-FSS', '2025-07-18 13:51:36'),
(33, 'Motif Subhanale', 'Merupakan bentuk mengagungkan asma Allah.', 'Awalnya berbentuk geometris segi enam yang didalamnya ada dekorasi beragam bunga, seperti bunga tanjung, remawa atau kenanga. Dengan pemilihan warna dasar kain hitam atau merah dengan motif bergaris garis geometris berwarna kuning.', 'https://pbs.twimg.com/media/CVHZC-zU4AE5WbD.jpg', 'https://youtu.be/RvDgs9k8S_I?si=dlVhFvEjzYhsknAS', '2025-07-18 13:51:36'),
(34, 'Motif Ragi Genep', 'Arti Ragi dalam ungkapan bahasa Sasak adalah syarat.', 'Motif ini biasanya ada pada kain sarung sehingga sering digunakan untuk kegiatan sehari-hari, baik oleh pria mau pun wanita.', 'https://www.jokembe.com/source/Kain%20tenun%20Khas%20Desa%20Sade%2C%20Lombok%20Tengah%20Motif%20Ragi%20Genep.jpg', 'https://youtube.com/shorts/lzb7NXWICZU?si=KaadSe264I6smcrT', '2025-07-18 13:51:36'),
(35, 'Motif Rangrang', 'Kain ini banyak dikreasikan dalam bentuk pakaian seperti kebaya tradisional, pakaian ibadah, hingga selendang.', 'Motif Rangrang adalah perpaduan antara kain khas Lombok dengan kain tradisional Bali yang memiliki corak zig-zag serta kombinasi warna mencolok.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAnqbwpH-LHrzobkq7GM9zVM6lFv7xR78BTw&', 'https://youtu.be/mCbJweYnc5M?si=VY_2X9t1XnaOLNqI', '2025-07-18 13:51:36'),
(36, 'Motif Alang/Lumbung', 'Bentuk lumbung yang kokoh dan berfungsi sebagai tempat menyimpan hasil bumi', 'Motif ini terinspirasi dari bentuk lumbung padi, tempat penyimpanan hasil panen yang penting bagi masyarakat agraris.', 'https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20Alang%20atau%20Lumbung%20.jpg', 'https://youtu.be/KWV7UJsk5Rk?si=k06mM4XnTCFNeQFD', '2025-07-18 13:51:36'),
(37, 'Motif Bintang Empat', 'Motif ini terinspirasi dari bintang timur yang menandai terbitnya fajar dan arah mata angin.', 'Biasanya, motif ini digambarkan dalam bentuk kotak-kotak berwarna merah dan hijau muda, atau garis-garis horizontal merah dan hitam.', 'https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20bintang%20empat%20.jpg', 'https://youtu.be/KWV7UJsk5Rk?si=k06mM4XnTCFNeQFD', '2025-07-18 13:51:36'),
(38, 'Motif Keker atau Merak', 'Motif Keker atau Merak pada tenun ikat Lombok memiliki makna kedamaian, kebahagiaan, dan cinta abadi.', 'Motif ini sering digambarkan dengan dua ekor burung merak yang saling berhadapan dan bernaung di bawah pohon. Selain itu, motif ini juga dikenal sebagai motif bulan madu karena melambangkan keabadian cinta dan kesetiaan dalam pernikahan.', 'https://www.jokembe.com/source/kain%20tentun%20songket%20khas%20sukarara%20lombok%20motif%20Keker%20atau%20Merak%20.jpg', 'https://youtu.be/KgKeLCZZ0SA?si=RnHDY4_7azUZLqV-', '2025-07-18 13:51:36');

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
  `hotel_name` varchar(100) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) NOT NULL,
  `booking_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `email`, `phone`, `role`, `created_at`) VALUES
(1, 'john_doe', 'password123', 'john@example.com', '081234567890', 'user', '2025-07-18 02:44:24'),
(2, 'jane_smith', 'securepass', 'jane@example.com', '082345678901', 'user', '2025-07-18 02:44:24'),
(3, 'admin', 'admin123', 'admin@baleqara.com', '083456789012', 'admin', '2025-07-18 02:44:24'),
(4, 'budi_santoso', 'budi1234', 'budi@email.com', '08111222333', 'user', '2025-07-18 02:44:24'),
(5, 'siti_rahayu', 'siti5678', 'siti@email.com', '08222333444', 'user', '2025-07-18 02:44:24');

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
  `country` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT 'Male',
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','user','technician') DEFAULT 'user',
  `is_suspended` tinyint(1) DEFAULT 0,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `country`, `gender`, `profile_picture`, `role`, `is_suspended`, `email`) VALUES
(1, 'Admin Sasak', 'admin', 'admin123', 'Indonesia', 'Male', NULL, 'admin', 0, NULL),
(2, 'Warga Lokal', 'lokal01', 'lokal123', 'Indonesia', 'Female', NULL, 'user', 0, NULL),
(3, 'Dinas Pariwisata', 'dinaspariwisata', 'dinas123', 'Indonesia', 'Male', NULL, '', 0, NULL),
(4, '', 'john_doe', 'password123', 'Indonesia', 'Male', NULL, 'user', 0, NULL),
(5, '', 'jane_smith', 'securepass', 'Indonesia', 'Female', NULL, 'user', 0, NULL);

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
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bus_bookings`
--
ALTER TABLE `bus_bookings`
  ADD CONSTRAINT `bus_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `culture_views`
--
ALTER TABLE `culture_views`
  ADD CONSTRAINT `culture_views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  ADD CONSTRAINT `hotel_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tourguide`
--
ALTER TABLE `tourguide`
  ADD CONSTRAINT `tourguide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
