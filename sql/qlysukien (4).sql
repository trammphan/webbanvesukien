-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 11:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlysukien`
--

-- --------------------------------------------------------

--
-- Table structure for table `diadiem`
--

CREATE TABLE `diadiem` (
  `MaDD` char(5) NOT NULL,
  `TenTinh` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diadiem`
--

INSERT INTO `diadiem` (`MaDD`, `TenTinh`) VALUES
('DL', 'Đà Lạt'),
('HCM', 'Thành phố Hồ Chí Minh'),
('HN', 'Hà Nội'),
('HY', 'Hưng Yên');

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`email`, `user_name`, `tel`, `password`) VALUES
('a@ctu.edu.vn', 'Hopi', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b'),
('b@ctu.edu.vn', 'hehe', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b'),
('hehe@ctu.edu.vn', 'hehe', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b'),
('hi@gmail.com', 'hi', '0234365711', '827ccb0eea8a706c4c34a16891f84e7b'),
('hihi@ctu.edu.vn', 'hihi', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `loaisk`
--

CREATE TABLE `loaisk` (
  `MaloaiSK` char(5) NOT NULL,
  `TenLoaiSK` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaisk`
--

INSERT INTO `loaisk` (`MaloaiSK`, `TenLoaiSK`) VALUES
('LSK01', 'Liveshow'),
('LSK02', 'Festival'),
('LSK03', 'Concert');

-- --------------------------------------------------------

--
-- Table structure for table `loaive`
--

CREATE TABLE `loaive` (
  `MaLoai` char(10) NOT NULL,
  `TenLoai` varchar(50) NOT NULL,
  `Gia` float NOT NULL,
  `MaSK` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaive`
--

INSERT INTO `loaive` (`MaLoai`, `TenLoai`, `Gia`, `MaSK`) VALUES
('LV01', 'NHÁ NHEM', 500000, 'SK01'),
('LV02', 'CHẬP CHOẠNG', 700000, 'SK01'),
('LV03', 'CHẠNG VẠNG', 1000000, 'SK01'),
('LV04', 'CHIỀU TÀ', 1300000, 'SK01'),
('LV05', 'HOÀNG HÔN', 1500000, 'SK01'),
('LV06', 'STANDARD 2', 500000, 'SK02'),
('LV07', 'STANDARD 1', 800000, 'SK02'),
('LV08', 'VIP', 1100000, 'SK02'),
('LV09', 'SUPER VIP', 1200000, 'SK02'),
('LV10', 'VIP-A', 7300000, 'SK03'),
('LV100', 'VIP 1.A2', 4000000, 'SK23'),
('LV101', 'VIP 1.C1', 5000000, 'SK23'),
('LV102', 'VIP 1.C2', 5000000, 'SK23'),
('LV103', 'VIP 1.B1', 4000000, 'SK23'),
('LV104', 'Gieo Mầm 1 (Standing)', 3500000, 'SK24'),
('LV105', 'Gieo Mầm 2 (Standing)', 3500000, 'SK24'),
('LV106', 'Nỗ Lực 1 (Standing)', 3000000, 'SK24'),
('LV107', 'Nỗ Lực 2 (Standing)', 3000000, 'SK24'),
('LV108', 'Vượt Chông Gai 1 (Standing)', 3000000, 'SK24'),
('LV109', 'Vượt Chông Gai 2 (Standing)', 3000000, 'SK24'),
('LV11', 'VIP-B', 7300000, 'SK03'),
('LV110', '[SEATING] HẠNH PHÚC 1', 5200000, 'SK25'),
('LV111', '[SEATING] HẠNH PHÚC KD-A', 5200000, 'SK25'),
('LV112', '[SEATING] TỰ HÀO 1', 4500000, 'SK25'),
('LV113', '[SEATING] TỰ HÀO 2', 4500000, 'SK25'),
('LV114', 'Đam Mê (Seating)', 1500000, 'SK26'),
('LV115', 'Tái Sinh (Seating)', 800000, 'SK26'),
('LV116', 'Nhà Hát 1 (Seating)', 800000, 'SK26'),
('LV117', 'Nhà Hát 2 (Seating)', 800000, 'SK26'),
('LV118', 'Ngũ Hành (Seating)', 800000, 'SK26'),
('LV119', 'X-VIP', 8000000, 'SK26'),
('LV12', 'PREMIUM', 6500000, 'SK03'),
('LV120', 'GA', 800000, 'SK27'),
('LV121', 'COOL', 9000000, 'SK13'),
('LV122', 'MODAL', 8000000, 'SK13'),
('LV123', 'BIG BAND (Left)', 6000000, 'SK13'),
('LV124', 'BIG BAND (Right)', 6000000, 'SK13'),
('LV125', 'SWING (Left)', 5000000, 'SK13'),
('LV126', 'SWING (Right)', 5000000, 'SK13'),
('LV13', 'CAT-1A', 6000000, 'SK03'),
('LV14', 'CAT-1B', 6000000, 'SK03'),
('LV15', 'CAT-2A', 5000000, 'SK03'),
('LV16', 'STANDARD 2', 400000, 'SK04'),
('LV17', 'EARLY BIRD - GA', 899000, 'SK05'),
('LV18', 'DAY TIME CHECK-IN (GA)', 1099000, 'SK05'),
('LV19', '01 DAY PASS (NORMAL) - GA', 1169000, 'SK05'),
('LV20', '02 DAY PASS - GA', 2099000, 'SK05'),
('LV21', 'Full Day Access + GA 1', 699000, 'SK06'),
('LV22', 'Full Day Access + GA 2', 699000, 'SK06'),
('LV23', 'Full Day Access + FANZONE 1', 999000, 'SK06'),
('LV24', 'Full Day Access + FANZONE 2', 999000, 'SK06'),
('LV25', 'RVIP Khu R (Seated)', 4550000, 'SK07'),
('LV26', 'RVIP Khu L (Seated)', 4550000, 'SK07'),
('LV27', 'VIP Khu R (Standing)', 3600000, 'SK07'),
('LV28', 'VIP Khu L (Standing)', 3600000, 'SK07'),
('LV29', 'S1 Khu R (Seated)', 2560000, 'SK07'),
('LV30', 'S1 Khu L (Seated)', 2560000, 'SK07'),
('LV31', 'THE HEART 1', 2500000, 'SK08'),
('LV32', 'THE HEART 2', 2500000, 'SK08'),
('LV33', 'THE FACE 1', 2000000, 'SK08'),
('LV34', 'THE FACE 2', 2000000, 'SK08'),
('LV35', 'THE ENERGY 1', 1800000, 'SK08'),
('LV36', 'THE ENERGY 2', 1800000, 'SK08'),
('LV37', 'Regular Ticket', 755000, 'SK09'),
('LV38', 'Combo 1 Regular Ticket + 1 Lightstick NTPMM (-2%)', 1081920, 'SK09'),
('LV39', 'Combo 10 Regular Ticket (-15%)', 641750, 'SK09'),
('LV40', 'Regular Ticket', 755000, 'SK10'),
('LV41', 'Combo 1 Regular Ticket + 1 Lightstick NTPMM (-2%)', 1081920, 'SK10'),
('LV42', 'Combo 10 Regular Ticket (-15%)', 641750, 'SK10'),
('LV43', 'Early Bird (EB)', 400000, 'SK11'),
('LV44', 'General Admission (GA)', 500000, 'SK11'),
('LV45', 'EARLY BOO (Checkin before 10PM)', 450000, 'SK12'),
('LV46', 'General Admission (GA)', 650000, 'SK12'),
('LV53', 'Mộng Mơ 1', 2500000, 'SK14'),
('LV54', 'Mộng Mơ 2', 2500000, 'SK14'),
('LV55', 'Ký Ức 1', 2100000, 'SK14'),
('LV56', 'Ký Ức 2', 2100000, 'SK14'),
('LV57', 'Thanh Xuân 1', 1700000, 'SK14'),
('LV58', 'Thanh Xuân 2', 1700000, 'SK14'),
('LV59', 'PREMIER LOUNGE', 10000000, 'SK15'),
('LV60', 'SVIP A', 4000000, 'SK15'),
('LV61', 'SVIP B', 4000000, 'SK15'),
('LV62', 'VIP A', 3000000, 'SK15'),
('LV63', 'VIP B', 3000000, 'SK15'),
('LV64', 'NHÁ NHEM', 400000, 'SK16'),
('LV65', 'CHẬP CHOẠNG', 500000, 'SK16'),
('LV66', 'CHẠNG VẠNG', 650000, 'SK16'),
('LV67', 'CHIỀU TÀ', 900000, 'SK16'),
('LV68', 'HOÀNG HÔN', 1100000, 'SK16'),
('LV69', 'Early Access (Check-in before 10PM)', 650000, 'SK17'),
('LV70', 'GA (General Admission)', 850000, 'SK17'),
('LV71', 'Red Rose', 3200000, 'SK18'),
('LV72', 'Green Rose', 2600000, 'SK18'),
('LV73', 'Pink Rose', 1900000, 'SK18'),
('LV74', 'Yellow Rose', 1400000, 'SK18'),
('LV75', 'Blue Rose', 800000, 'SK18'),
('LV76', 'NHÁ NHEM', 570000, 'SK19'),
('LV77', 'CHẬP CHOẠNG', 800000, 'SK19'),
('LV78', 'CHANG VẠNG', 1120000, 'SK19'),
('LV79', 'VIP - CHIỀU TÀ', 1420000, 'SK19'),
('LV80', 'VVVIP - HOÀNG HÔN', 1700000, 'SK19'),
('LV81', 'NHÁ NHEM', 570000, 'SK20'),
('LV82', 'CHẬP CHOẠNG', 800000, 'SK20'),
('LV83', 'CHANG VẠNG', 1120000, 'SK20'),
('LV84', 'VIP - CHIỀU TÀ', 1420000, 'SK20'),
('LV85', 'VVVIP - HOÀNG HÔN', 1700000, 'SK20'),
('LV86', 'HOÀNG HÔN', 1450000, 'SK21'),
('LV87', 'CHIỀU TÀ', 1230000, 'SK21'),
('LV88', 'CHẠNG VẠNG', 1050000, 'SK21'),
('LV89', 'CHẬP CHOẠNG', 760000, 'SK21'),
('LV90', 'NHÁ NHEM', 560000, 'SK21'),
('LV91', 'CAT 1 - R', 4000000, 'SK22'),
('LV92', 'CAT 2 - L', 3500000, 'SK22'),
('LV93', 'CAT 2 - R', 3500000, 'SK22'),
('LV94', 'CAT 3 - L', 2500000, 'SK22'),
('LV95', 'CAT 3 - R', 2500000, 'SK22'),
('LV96', 'CAT 4 - L', 2000000, 'SK22'),
('LV97', 'CAT 4 - R', 2000000, 'SK22'),
('LV98', 'VVIP', 10000000, 'SK23'),
('LV99', 'VIP 1.A1', 4000000, 'SK23');

-- --------------------------------------------------------

--
-- Table structure for table `nhanviensoatve`
--

CREATE TABLE `nhanviensoatve` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhanviensoatve`
--

INSERT INTO `nhanviensoatve` (`email`, `user_name`, `gender`, `tel`, `password`) VALUES
('nvsv@ctu.edu.vn', 'nhanviensoatve', 'male', '0123456789', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `nhatochuc`
--

CREATE TABLE `nhatochuc` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `taikhoannganhang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhatochuc`
--

INSERT INTO `nhatochuc` (`email`, `user_name`, `tel`, `address`, `taikhoannganhang`, `password`) VALUES
('ntc@ctu.edu.vn', 'nhatochuc', '0123456789', 'Đại học Cần Thơ', '98765432101234', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `quantrivien`
--

CREATE TABLE `quantrivien` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `quantrivien`
--

INSERT INTO `quantrivien` (`email`, `user_name`, `tel`, `password`) VALUES
('qtv@ctu.edu.vn', 'quantrivien', '0123456789', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `sukien`
--

CREATE TABLE `sukien` (
  `MaSK` char(5) NOT NULL,
  `TenSK` varchar(100) NOT NULL,
  `Tgian` date DEFAULT NULL,
  `img_sukien` varchar(100) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `MaLSK` char(5) DEFAULT NULL,
  `MaDD` char(5) DEFAULT NULL,
  `luot_timkiem` int(11) DEFAULT 0,
  `luot_truycap` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sukien`
--

INSERT INTO `sukien` (`MaSK`, `TenSK`, `Tgian`, `img_sukien`, `mota`, `MaLSK`, `MaDD`, `luot_timkiem`, `luot_truycap`) VALUES
('SK01', 'LULULOLA SHOW VŨ CÁT TƯỜNG | NGÀY NÀY, NGƯỜI CON GÁI NÀY', '2025-10-18', 'https://salt.tkbcdn.com/ts/ds/cb/5a/3b/13e9a9ccf99d586df2a7c6bd59d89369.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL', 125, 95),
('SK02', '[CAT&MOUSE] CA SĨ ĐẠT G - ĐÊM LẶNG TÔ MÀU XÚC CẢM', '2025-10-31', 'https://salt.tkbcdn.com/ts/ds/37/25/63/9a82b897b7f175b5888016f161d0fa1e.png', 'Với không gian được đầu tư hệ thống ánh sáng - âm thanh đẳng cấp quốc tế với sức chứa lên đến 350 người, cùng quầy bar phục vụ cocktail pha chế độc đáo bởi bartender chuyên nghiệp.\n\n20g00 - 31/10/2025 (Thứ 6), một đêm nhạc sâu lắng và chân thành tại Cat&Mouse đã hé lộ. Sự góp mặt của Đạt G với chất giọng trầm ấm, đặc trưng, cùng phong cách âm nhạc giàu cảm xúc, sẽ giúp bạn tìm thấy chính mình trong những khoảnh khắc cô đơn nhưng cũng đầy sự an ủi.\n\nQuý khách tham dự đêm diễn sẽ được tặng 1 phần đồ ăn nhẹ.', 'LSK01', 'HCM', 100, 70),
('SK03', 'G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI, PRESENTED BY VPBANK', '2025-11-08', 'https://salt.tkbcdn.com/ts/ds/2b/62/6d/b72040ac36d256c6c51e4c01797cf879.png', 'Lần đầu tiên, \"Ông hoàng K-pop\" G-DRAGON chính thức tổ chức concert tại Việt Nam, mở màn cho chuỗi World Tour do 8Wonder mang tới. G-DRAGON 2025 WORLD TOUR [Übermensch] hứa hẹn sẽ bùng nổ với sân khấu kì công, âm thanh - ánh sáng mãn nhãn và những khoảnh khắc chạm đến trái tim người hâm mộ. G-DRAGON sẽ mang đến những bản hit từng gắn liền với thanh xuân của hàng triệu người hâm mộ. Một đêm nhạc không chỉ để thưởng thức, mà còn để lưu giữ trong ký ức.', 'LSK03', 'HY', 180, 112),
('SK05', 'Waterbomb Ho Chi Minh City 2025', '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/f3/80/f0/32ee189d7a435daf92b6a138d925381c.png', 'Vào hai ngày 15–16/11/2025, khu đô thị Vạn Phúc City (TP.HCM) sẽ trở thành tâm điểm của giới trẻ khi lễ hội âm nhạc WATERBOMB lần đầu tiên “cập bến” Việt Nam. Với mô hình kết hợp âm nhạc – trình diễn – hiệu ứng phun nước đặc trưng từ Hàn Quốc, sự kiện hứa hẹn mang đến trải nghiệm “ướt sũng” đầy phấn khích cùng dàn nghệ sĩ đình đám như Hwasa, Jay Park, B.I, Sandara Park, Rain, EXID, Shownu x Hyungwon (MONSTA X), cùng các ngôi sao Vpop như HIEUTHUHAI, tlinh, SOOBIN, Tóc Tiên, Chi Pu, MIN và nhiều cái tên hot khác.\n\nKhông chỉ là sân khấu âm nhạc, WATERBOMB còn là đại tiệc cảm xúc với khu vui chơi phun nước liên hoàn, khu check-in phong cách lễ hội, và các hạng vé đa dạng từ GA đến Splash Wave – nơi bạn có thể “quẩy” sát sân khấu cùng thần tượng. Đây là cơ hội hiếm có để fan Kpop và khán giả Việt cùng hòa mình vào không gian lễ hội quốc tế ngay giữa lòng Sài Gòn.\n', 'LSK02', 'HCM', 140, 110),
('SK06', 'GS25 MUSIC FESTIVAL 2025', '2025-11-22', 'https://salt.tkbcdn.com/ts/ds/6e/2f/fa/32d07d9e0b2bd6ff7de8dfe2995619d5.jpg', 'GS25 MUSIC FESTIVAL 2025 sẽ diễn ra vào ngày 22/11 tại Công viên Sáng Tạo, Thủ Thiêm, TP.HCM, từ 10:00 đến 23:00. Đây là lễ hội âm nhạc ngoài trời hoành tráng do GS25 tổ chức, quy tụ nhiều nghệ sĩ nổi tiếng. Khách hàng có thể đổi vé tham dự bằng cách tích điểm khi mua sắm tại GS25 và CAFE25 từ 01/10 đến 15/11. Vé không cho phép hoàn trả và cần đeo vòng tay khi tham gia. Sự kiện hứa hẹn mang đến trải nghiệm âm nhạc sôi động và không gian lễ hội trẻ trung dành cho giới trẻ.', 'LSK02', 'HCM', 135, 105),
('SK07', '2025 K-POP SUPER CONCERT IN HO CHI MINH', '2025-11-22', 'https://salt.tkbcdn.com/ts/ds/bb/96/bd/28394979b702cd9dc934bef42824e6c1.png', 'Vào ngày 22/11/2025, sự kiện K-POP SUPER CONCERT sẽ chính thức diễn ra tại Vạn Phúc City, TP.HCM, do Golden Space Entertainment tổ chức. Đây là một lễ hội âm nhạc hoành tráng quy tụ dàn nghệ sĩ K-pop và Việt Nam, với sự góp mặt của các tên tuổi như XIUMIN, CHEN, DUCPHUC, ARrC, và nhóm nữ Gen Z đa quốc tịch We;Na – lần đầu tiên ra mắt tại Việt Nam.', 'LSK03', 'HCM', 150, 111),
('SK08', 'SOOBIN LIVE CONCERT: ALL-ROUNDER THE FINAL', '2025-11-29', 'https://salt.tkbcdn.com/ts/ds/9c/9e/c1/2edd538cb4df21a0d13f95588cb44dc4.png', 'Các all-rounders chờ đã lâu rồi phải không? Một lần nữa hãy cùng đắm chìm trong trải nghiệm sân khấu \'all around you\', để SOOBIN cùng âm nhạc luôn chuyển động bên bạn mọi lúc - mọi nơi nhé!', 'LSK03', 'HCM', 130, 100),
('SK09', 'Những Thành Phố Mơ Màng Year End 2025', '2025-12-07', 'https://salt.tkbcdn.com/ts/ds/e8/95/f3/2dcfee200f26f1ec0661885b2c816fa6.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến:  07/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại TP.HCM (sẽ cập nhật sau).', 'LSK03', 'HCM', 115, 85),
('SK10', 'Những Thành Phố Mơ Màng Year End 2025', '2022-12-21', 'https://salt.tkbcdn.com/ts/ds/18/8f/59/2d0abe9be901a894cd3b0bf29fd01863.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến: 21/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại Hà Nội (sẽ cập nhật sau).', 'LSK03', 'HN', 70, 20),
('SK11', '1900 Future Hits #75: Thanh Duy', '2025-10-24', 'https://salt.tkbcdn.com/ts/ds/df/d8/ec/9f46a4e587b39ccf5886e6ae6f1b27d0.png', 'Nhắc đến Thanh Duy (Á quân Vietnam Idol 2008) là nhắc đến một nghệ sĩ nhiều màu sắc, một chú \"tắc kè hoa\" của showbiz. Thanh Duy kể những câu chuyện độc đáo, chạm đến tim người nghe bằng âm nhạc. Mỗi bài hát là một mảnh ghép cá tính, không lẫn vào đâu được.\n \nVới style không ngại khác biệt, thời trang \"chơi trội\" và tinh thần sống thật, sống hết mình, Thanh Duy luôn là nguồn năng lượng tích cực, truyền cảm hứng sống vui, sống thật cho giới trẻ. \n \nNgày 24/10 tới đây, 1900 sẽ chào đón Thanh Duy đến với đêm nhạc Future Hits #75. Các bản hit sẽ được vang lên trên sân khấu 1900, hứa hẹn mang đến những moment cực peak.\n \nSave the date!', 'LSK01', 'HN', 75, 45),
('SK12', 'RAVERSE #3: Clowns Du Chaos w/ MIKE WILLIAMS - Oct 31 (HALLOWEEN PARTY)', '2025-10-31', 'https://salt.tkbcdn.com/ts/ds/e0/71/b2/b213ce9427cfc01487c73df2ba849787.jpg', 'Sau những đêm cháy hết mình cùng DubVision và Maddix, RAVERSE đã chính thức quay trở lại và lần này, Raverse sẽ biến APLUS HANOI thành một RẠP XIẾC MA MỊ đúng nghĩa. Cùng chào đón Headliner – MIKE WILLIAMS, DJ/Producer top 72 DJ Mag - Người đứng sau hàng loạt hit Future Bounce tỉ lượt nghe, từng khuấy đảo những sân khấu lớn nhất thế giới Tomorrowland, Ultra Music Festival,... nay sẽ đổ bộ Raverse #3 mang theo năng lượng bùng nổ chưa từng có! ⚡Cánh cửa rạp xiếc sắp mở… Bạn đã sẵn sàng hóa thân, quẩy hết mình và bước vào thế giới hỗn loạn của RAVERSE chưa?', 'LSK02', 'HN', 65, 35),
('SK13', 'Jazz concert: Immersed', '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/43/54/98/924b6491983baf58b00222c9b5b7295b.jpg', 'JAZZ CONCERT – IMMERSED: SỰ KẾT HỢP ĐỈNH CAO TỪ NHỮNG TÊN TUỔI HÀNG ĐẦU\n\n🌿Được khởi xướng bởi GG Corporation, Living Heritage ra đời với sứ mệnh là quy tụ và tôn vinh những giá trị sống đích thực của cộng đồng người Việt trên khắp thế giới – từ trải nghiệm, tri thức đến nhân sinh quan sâu sắc của các thế hệ đi trước để trao truyền lại cho thế hệ tương lai.\n\n🌻Living Heritage là một hệ sinh thái nội dung gồm: trang web chính thức lưu trữ các cuộc trò chuyện ý nghĩa, sách điện tử (được phát phát hành trên Amazon), cùng chuỗi sự kiện nghệ thuật – giáo dục tầm vóc quốc tế thường niên. 🎼Khởi đầu hành trình này là Jazz Concert IMMERSED – đêm nhạc quốc tế với sự tham gia đặc biệt của “Hiệp sĩ” Jazz - Sir Niels Lan Doky, huyền thoại piano Jazz được biết đến như một trong những nghệ sĩ tiên phong của dòng Jazz châu Âu hiện đại. Báo chí Nhật Bản gọi ông là “nghệ sĩ xuất sắc nhất thế hệ”, còn tờ báo El Diario (Tây Ban Nha) gọi ông là “một trong những nghệ sĩ piano quan trọng nhất nửa thế kỷ qua”. Ông sẽ trình diễn cùng bộ đôi nghệ sĩ quốc tế Felix Pastorius (bass) và Jonas Johansen (trống), dưới sự dàn dựng của Tổng đạo diễn Phạm Hoàng Nam, Giám đốc Âm nhạc Quốc Trung, Kĩ sư âm thanh Doãn Chí Nghĩa, Nhà thiết kế Phục trang Tom Trandt, Biên đạo múa Ngọc Anh và Nghệ sĩ nghệ thuật thị giác Tùng Monkey.\n\n⭐️Điểm nhấn đặc biệt là những màn kết hợp giữa Sir Niels Lan Doky và các nghệ sĩ hàng đầu Việt Nam như NSND Thanh Lam, ca sĩ Hà Trần, nghệ sĩ saxophone Quyền Thiện Đắc và một số nghệ sĩ khác – những tên tuổi có dấu ấn rõ nét trong việc vừa gìn giữ nét đẹp bản sắc của âm nhạc Việt, vừa tìm tòi, sáng tạo và đổi mới để hội nhập vào dòng chảy âm nhạc thế giới. Sự hội ngộ này tạo nên một không gian âm nhạc đa chiều, nơi tinh thần Jazz quốc tế gặp gỡ hơi thở dân gian đương đại Việt Nam trong một cuộc đối thoại âm nhạc đỉnh cao, hoà quyện và đầy ngẫu hứng.\n\nChi tiết sự kiện:\n\nChương trình chính: Khách mời đặc biêt Sir Niels Lan Doky, Knight of Jazz cùng \nKhách mời: NSND Thanh Lam, Ca sỹ Hà Trần, Nghệ sỹ Quyền Thiện Đắc.', 'LSK03', 'HCM', 70, 40),
('SK14', '[Dốc Mộng Mơ] Em Đồng Ý - Đức Phúc - Noo Phước Thịnh', '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/6d/9b/da/438a1b16cba1c64f5befce0fdd32682a.jpg', 'Đêm nhạc đánh dấu chặng đường trưởng thành của Đức Phúc với những bản hit được phối mới đầy cảm xúc, sân khấu dàn dựng công phu cùng sự góp mặt của ca sĩ Noo Phước Thịnh.\n\nMột hành trình âm nhạc lãng mạn và bất ngờ, chắc chắn là khoảnh khắc không thể bỏ lỡ!\n\nChi tiết sự kiện \n\n	Chương trình chính: \n \nTrình diễn những ca khúc nổi bật nhất trong sự nghiệp ca hát của Đức Phúc. \n\nCác tiết mục dàn dựng công phu, phối khí mới mẻ.\n\nNhững phần trình diễn đặc biệt lần đầu ra mắt tại liveshow.\n\n	Khách mời: Ca sĩ Noo Phước Thịnh \n\n	Trải nghiệm đặc biệt: Không gian check-in mang concept riêng của “EM ĐỒNG Ý” cũng như khu trải nghiệm và những phần quà đặc biệt dành cho fan.', 'LSK01', 'HN', 110, 80),
('SK15', 'EM XINH \"SAY HI\" CONCERT - ĐÊM 2', '2025-10-12', 'https://salt.tkbcdn.com/ts/ds/90/37/6e/cfa9510b1f648451290e0cf57b6fd548.jpg', 'Em Xinh “Say Hi” Concert – Đêm 2 sẽ diễn ra vào ngày 11/10/2025 tại sân vận động Mỹ Đình, Hà Nội, mang đến đại tiệc âm nhạc Gen Z với sân khấu ánh sáng 360 độ, loạt tiết mục viral như Run, Không đau nữa rồi, Vỗ tay. Lưu ý: Vé không hoàn trả, trẻ em dưới 7 tuổi không được tham gia, người dưới 16 tuổi cần có người lớn đi kèm.', 'LSK03', 'HN', 105, 75),
('SK16', 'LULULOLA SHOW VICKY NHUNG & CHU THÚY QUỲNH | NGÀY MƯA ẤY', '2025-09-20', 'https://salt.tkbcdn.com/ts/ds/ee/86/df/261a5fd2fa0890c25f4c737103bbbe0c.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL', 85, 55),
('SK17', 'ELAN & APLUS present: STEPHAN BODZIN', '2025-09-22', 'https://salt.tkbcdn.com/ts/ds/e3/06/ed/faff7ef36d95334510e51f7d337357d4.jpg', 'Không chỉ đơn thuần là một set nhạc, sự kiện kỷ niệm 2 năm của ELAN sẽ mang đến một “siêu phẩm” của âm thanh, năng lượng và cảm xúc. Hãy sẵn sàng đắm mình trong màn trình diễn live độc nhất vô nhị từ “nhạc trưởng” huyền thoại – Stephan Bodzin! Được mệnh danh là một trong những live performer xuất sắc nhất lịch sử nhạc điện tử, Stephan Bodzin luôn thiết lập những tiêu chuẩn mới cho nghệ thuật trình diễn và để lại dấu ấn sâu đậm trên các sân khấu, lễ hội âm nhạc điện tử lớn nhất thế giới. Suốt nhiều năm, ông vững vàng ở đỉnh cao của giới Techno, sánh vai cùng những huyền thoại như Solomun, Tale of Us, Carl Cox... Biểu diễn cùng Stephan Bodzin lần này còn có những tên tuổi đầy thực lực của làng Techno Việt: THUC, Mya, Heepsy và Tini Space. Từ 9 giờ tối, Chủ Nhật ngày 21 tháng 9, 2025 tại APLUS Hanoi, 78 Yên Phụ, Hà Nội.', 'LSK02', 'HN', 60, 35),
('SK18', 'The Wandering Rose 02.08', '2025-08-02', 'https://salt.tkbcdn.com/ts/ds/c3/26/77/a3320dbc30151eb7de584ebf41a4c71f.jpg', 'The Wandering Rose – một đêm nhạc lãng mạn và đầy mộng mơ giữa thiên nhiên Ba Vì thơ mộng, nơi âm nhạc gặp gỡ cảm xúc, nơi mỗi nốt nhạc là một cánh hoa trôi lạc giữa miền ký ức. Với không gian tổ chức tại The Wandering Rose Villa, sự kiện hứa hẹn mang lại một trải nghiệm nghệ thuật trọn vẹn, tinh tế và khó quên. Điểm đặc sắc nhất của chương trình là sự kết hợp giữa bối cảnh nên thơ của núi rừng Ba Vì và những phần trình diễn đặc biệt đến từ Quang Hùng MasterD, Hà Nhi, Quân AP và Phạm Quỳnh Anh.', 'LSK01', 'HN', 55, 30),
('SK19', 'LULULOLA SHOW TĂNG PHÚC | MONG MANH NỖI ĐAU', '2025-12-13', 'https://salt.tkbcdn.com/ts/ds/0f/f1/68/b57f2a3ecd1a9e516e8d1587c34fcc6e.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL', 90, 60),
('SK20', 'LULULOLA SHOW PHAN MẠNH QUỲNH | TỪ BÀN TAY NÀY', '2025-12-06', 'https://salt.tkbcdn.com/ts/ds/57/04/b1/39315e2c790f67ecc938701754816d15.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL', 120, 90),
('SK21', 'LULULOLA SHOW VĂN MAI HƯƠNG | ƯỚT LÒNG', '2025-09-13', 'https://salt.tkbcdn.com/ts/ds/fb/43/5c/52a43d006d2ec64b1dac74db8a62f72f.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL', 95, 65),
('SK22', 'DAY6 10th Anniversary Tour <The DECADE> in HO CHI MINH CITY', '2025-10-18', 'https://salt.tkbcdn.com/ts/ds/c6/e1/c2/d3d41b377ea3d9a3cd18177d656516d7.jpg', 'Ngày 18/10/2025, ban nhạc Hàn Quốc DAY6 đã tổ chức concert đầu tiên tại Việt Nam – DAY6 10th Anniversary Tour <The DECADE> tại SECC Hall B2, Quận 7, TP.HCM, đánh dấu 10 năm hoạt động âm nhạc. Đây là lần đầu nhóm biểu diễn solo tại Việt Nam, thu hút đông đảo người hâm mộ My Days. Setlist trải dài từ các bản hit như Congratulations, Letting Go, I Loved You, Zombie đến những ca khúc mới trong album kỷ niệm như Dream Bus, Inside Out, Disco Day và Our Season.', 'LSK03', 'HCM', 160, 110),
('SK23', '8Wonder Winter 2025 - SYMPHONY OF STARS - HÒA KHÚC CÁC VÌ SAO', '2025-12-06', 'https://salt.tkbcdn.com/ts/ds/c1/48/74/8c3630d25edf901b843473af6be4dd6a.jpg', '8WONDER WINTER 2025 - SYMPHONY OF STARS - HÒA KHÚC CÁC VÌ SAO\r\n\r\nGiữa mùa đông Hà Nội, 8Wonder thắp sáng bầu trời bằng “Symphony of Stars” – bản hoà khúc nơi những giọng ca đẳng cấp thế giới cất lên, khẳng định vị thế thương hiệu âm nhạc quốc tế tại Việt Nam. Không chỉ là concert, đây là một hành trình lễ hội sống: từ âm nhạc bùng nổ và nghệ thuật giao thoa, đến ẩm thực bốn phương, không gian văn hoá, thể thao, công nghệ và những kết nối cộng đồng. \r\n\r\nTiên phong theo đuổi xu hướng green festival, 8Wonder Winter 2025 mang đến một mùa hội trọn vẹn – nơi ánh sáng sân khấu, nhịp tim khán giả và hơi thở xanh của thời đại hòa làm một. Để mỗi khoảnh khắc ở đây trở thành một vì sao, cùng viết nên dải ngân hà bất tận của yêu thương, hy vọng và sự gắn kết.', 'LSK03', 'HN', 0, 0),
('SK24', 'Y-CONCERT BY YEAH1 - Mình đoàn viên thôi', '2025-12-20', 'https://salt.tkbcdn.com/ts/ds/8e/89/4c/407e32bba0e4d1651175680a2452954e.jpg', 'V Concert “Rạng Rỡ Việt Nam” hứa hẹn sẽ chạm tới đỉnh cao của âm nhạc và cảm xúc, đánh dấu lần đầu tiên một sự kiện nghệ thuật đỉnh cao được tổ chức tại Trung tâm Triển lãm Việt Nam – công trình hiện đại bậc nhất cả nước, nằm trong top 10 khu triển lãm hội chợ lớn nhất thế giới. Vào ngày 9.8.2025, Đài Truyền hình Việt Nam sẽ mang đến một lễ hội âm nhạc rực rỡ và bùng nổ với sự góp mặt của dàn nghệ sĩ “trong mơ” gồm Hà Anh Tuấn, Hồ Ngọc Hà, Noo Phước Thịnh, Đen, Trúc Nhân, Tóc Tiên, Hoàng Thuỳ Linh, Hoà Minzy, Phương Mỹ Chi, RHYDER, Quang Hùng MasterD và 2pillz. Đây sẽ là một đại tiệc kết hợp giữa âm nhạc, ánh sáng và công nghệ, mang đến không gian cảm xúc thăng hoa cho 25.000 khán giả, đồng thời trở thành cột mốc rạng rỡ trong hành trình tôn vinh âm nhạc và văn hóa Việt. Concert dành cho người trên 14 tuổi; riêng khán giả từ 14 đến dưới 18 tuổi cần có người giám hộ trên 21 tuổi đi cùng và chịu trách nhiệm trong suốt chương trình. Đừng bỏ lỡ cơ hội trở thành một phần của sự kiện âm nhạc đáng mong đợi nhất năm 2025!', 'LSK03', 'HY', 0, 0),
('SK25', 'V CONCERT \"RẠNG RỠ VIỆT NAM\" - CHẠM VÀO ĐỈNH CAO CỦA ÂM NHẠC VÀ CẢM XÚC', '2025-12-09', 'https://salt.tkbcdn.com/ts/ds/4d/5d/93/c38fa1bc1f9ca5f95b882b12d45883bc.jpg', 'V Concert “Rạng Rỡ Việt Nam” hứa hẹn sẽ chạm đến đỉnh cao của âm nhạc và cảm xúc, đánh dấu lần đầu tiên một sự kiện nghệ thuật tầm cỡ được tổ chức tại Trung tâm Triển lãm Việt Nam – công trình triển lãm hiện đại bậc nhất cả nước, nằm trong top 10 khu triển lãm hội chợ lớn nhất thế giới. Vào ngày 9.12.2025, Đài Truyền hình Việt Nam sẽ mang đến một lễ hội âm nhạc rực rỡ, bùng nổ cảm xúc với sự góp mặt của dàn nghệ sĩ “trong mơ” lần đầu cùng hội tụ trên một sân khấu lớn: Hà Anh Tuấn, Hồ Ngọc Hà, Noo Phước Thịnh, Đen, Trúc Nhân, Tóc Tiên, Hoàng Thuỳ Linh, Hoà Minzy, Phương Mỹ Chi, RHYDER, Quang Hùng MasterD và 2pillz. Sự kiện hứa hẹn mang đến một đại tiệc kết hợp giữa âm nhạc – ánh sáng – công nghệ, tạo nên không gian cảm xúc thăng hoa cho 25.000 khán giả và trở thành cột mốc rạng rỡ trong hành trình tôn vinh âm nhạc cùng văn hóa Việt. Lưu ý, concert dành cho người trên 14 tuổi; khán giả từ 14 đến dưới 18 tuổi có thể tham gia nếu có người giám hộ trên 21 tuổi đi cùng và đồng hành trong suốt chương trình. Đừng bỏ lỡ cơ hội trở thành một phần của sự kiện âm nhạc đáng mong chờ nhất năm 2025!', 'LSK03', 'HN', 0, 0),
('SK26', '[CONCERT THÁNG 12] ANH TRAI VƯỢT NGÀN CHÔNG GAI', '2025-12-14', 'https://salt.tkbcdn.com/ts/ds/0a/d4/73/9c523642a23c045cfbd374825f5c96fc.jpg', 'Concert “Anh Trai Vượt Ngàn Chông Gai” là sự kiện âm nhạc đặc biệt quy tụ dàn nghệ sĩ nổi tiếng từng tham gia chương trình cùng tên, mang đến những màn trình diễn bùng nổ và đầy cảm xúc. Lấy cảm hứng từ hành trình vượt qua thử thách, concert không chỉ là bữa tiệc âm thanh – ánh sáng hoành tráng mà còn là câu chuyện về tình anh em, nghị lực và đam mê cháy bỏng với âm nhạc. Với sân khấu được đầu tư công phu, hiệu ứng trình diễn hiện đại cùng loạt ca khúc “gây bão”, “Anh Trai Vượt Ngàn Chông Gai” hứa hẹn sẽ mang đến cho khán giả một đêm nhạc thăng hoa, truyền cảm hứng và khó quên.', 'LSK03', 'HY', 0, 0),
('SK27', 'CINÉ FUTURE HITS #12: JUN PHẠM', '2025-06-08', 'https://salt.tkbcdn.com/ts/ds/67/7a/29/48a31568f2bdbce9104ad077f146b560.jpg', '     Tiếp nối hành trình tôn vinh và phát triển văn hoá, nghệ thuật Việt, Ciné Saigon chính thức mang Future Hits quay trở lại với số 12, cùng với đó là màn \"kỉ lục comeback\" đến từ anh chàng nghệ sĩ đa tài Jun Phạm!\r\n \r\n     Với sự trở lại cùng \"chiếc\" mini concert Day 2 đến từ anh tài gia tộc toàn năng, anh tài biến hoá X-Icon, nam diễn viên điện ảnh - truyền hình được yêu thích nhất, tác giả sách quốc gia 2024, số Future Hits #12 hứa hẹn sẽ tiếp tục được phủ kín bởi sự cuồng nhiệt và đầy yêu thương đến từ đại gia đình hâm mộ Jun Phạm! \r\n', 'LSK03', 'HCM', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MaTT` varchar(20) NOT NULL,
  `PhuongThucThanhToan` varchar(255) NOT NULL,
  `SoTien` float NOT NULL,
  `TenNguoiThanhToan` varchar(255) NOT NULL,
  `SDT` char(12) DEFAULT NULL,
  `TrangThai` varchar(255) NOT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp(),
  `Email_KH` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhtoan`
--

INSERT INTO `thanhtoan` (`MaTT`, `PhuongThucThanhToan`, `SoTien`, `TenNguoiThanhToan`, `SDT`, `TrangThai`, `NgayTao`, `Email_KH`) VALUES
('TT_69072b9443695', 'momo', 2099000, 'hi', '0123458436', 'Chờ thanh toán', '2025-11-02 16:59:48', 'hi@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `ve`
--

CREATE TABLE `ve` (
  `MaVe` char(10) NOT NULL,
  `TrangThai` varchar(255) NOT NULL,
  `MaLoai` char(10) NOT NULL,
  `MaTT` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ve`
--

INSERT INTO `ve` (`MaVe`, `TrangThai`, `MaLoai`, `MaTT`) VALUES
('VE001', 'chưa thanh toan', 'LV01', NULL),
('VE002', 'chưa thanh toan', 'LV01', NULL),
('VE003', 'chưa thanh toan', 'LV01', NULL),
('VE004', 'chưa thanh toan', 'LV02', NULL),
('VE005', 'chưa thanh toan', 'LV02', NULL),
('VE006', 'chưa thanh toan', 'LV02', NULL),
('VE007', 'chưa thanh toan', 'LV03', NULL),
('VE008', 'chưa thanh toan', 'LV03', NULL),
('VE009', 'chưa thanh toan', 'LV03', NULL),
('VE010', 'chưa thanh toan', 'LV04', NULL),
('VE011', 'chưa thanh toan', 'LV04', NULL),
('VE012', 'chưa thanh toan', 'LV04', NULL),
('VE013', 'chưa thanh toan', 'LV05', NULL),
('VE014', 'chưa thanh toan', 'LV05', NULL),
('VE015', 'chưa thanh toan', 'LV05', NULL),
('VE016', 'chưa thanh toan', 'LV06', NULL),
('VE017', 'chưa thanh toan', 'LV06', NULL),
('VE018', 'chưa thanh toan', 'LV06', NULL),
('VE019', 'chưa thanh toan', 'LV07', NULL),
('VE020', 'chưa thanh toan', 'LV07', NULL),
('VE021', 'chưa thanh toan', 'LV07', NULL),
('VE022', 'chưa thanh toan', 'LV08', NULL),
('VE023', 'chưa thanh toan', 'LV08', NULL),
('VE024', 'chưa thanh toan', 'LV08', NULL),
('VE025', 'chưa thanh toan', 'LV09', NULL),
('VE026', 'chưa thanh toan', 'LV09', NULL),
('VE027', 'chưa thanh toan', 'LV09', NULL),
('VE028', 'chưa thanh toan', 'LV10', NULL),
('VE029', 'chưa thanh toan', 'LV10', NULL),
('VE030', 'chưa thanh toan', 'LV10', NULL),
('VE031', 'chưa thanh toan', 'LV11', NULL),
('VE032', 'chưa thanh toan', 'LV11', NULL),
('VE033', 'chưa thanh toan', 'LV11', NULL),
('VE034', 'chưa thanh toan', 'LV12', NULL),
('VE035', 'chưa thanh toan', 'LV12', NULL),
('VE036', 'chưa thanh toan', 'LV12', NULL),
('VE037', 'chưa thanh toan', 'LV13', NULL),
('VE038', 'chưa thanh toan', 'LV13', NULL),
('VE039', 'chưa thanh toan', 'LV13', NULL),
('VE040', 'chưa thanh toan', 'LV14', NULL),
('VE041', 'chưa thanh toan', 'LV14', NULL),
('VE042', 'chưa thanh toan', 'LV14', NULL),
('VE043', 'chưa thanh toan', 'LV15', NULL),
('VE044', 'chưa thanh toan', 'LV15', NULL),
('VE045', 'chưa thanh toan', 'LV15', NULL),
('VE046', 'chưa thanh toan', 'LV16', NULL),
('VE047', 'chưa thanh toan', 'LV16', NULL),
('VE048', 'chưa thanh toan', 'LV16', NULL),
('VE049', 'chưa thanh toan', 'LV17', NULL),
('VE050', 'chưa thanh toan', 'LV17', NULL),
('VE051', 'chưa thanh toan', 'LV17', NULL),
('VE052', 'Đã giữ chỗ', 'LV18', NULL),
('VE053', 'chưa thanh toan', 'LV18', NULL),
('VE054', 'chưa thanh toan', 'LV18', NULL),
('VE055', 'Đã giữ chỗ', 'LV19', NULL),
('VE056', 'chưa thanh toan', 'LV19', NULL),
('VE057', 'chưa thanh toan', 'LV19', NULL),
('VE058', 'Đã giữ chỗ', 'LV20', 'TT_69072b9443695'),
('VE059', 'chưa thanh toan', 'LV20', NULL),
('VE060', 'chưa thanh toan', 'LV20', NULL),
('VE061', 'chưa thanh toan', 'LV21', NULL),
('VE062', 'chưa thanh toan', 'LV21', NULL),
('VE063', 'chưa thanh toan', 'LV21', NULL),
('VE064', 'chưa thanh toan', 'LV22', NULL),
('VE065', 'chưa thanh toan', 'LV22', NULL),
('VE066', 'chưa thanh toan', 'LV22', NULL),
('VE067', 'chưa thanh toan', 'LV23', NULL),
('VE068', 'chưa thanh toan', 'LV23', NULL),
('VE069', 'chưa thanh toan', 'LV23', NULL),
('VE070', 'chưa thanh toan', 'LV24', NULL),
('VE071', 'chưa thanh toan', 'LV24', NULL),
('VE072', 'chưa thanh toan', 'LV24', NULL),
('VE073', 'chưa thanh toan', 'LV25', NULL),
('VE074', 'chưa thanh toan', 'LV25', NULL),
('VE075', 'chưa thanh toan', 'LV25', NULL),
('VE076', 'chưa thanh toan', 'LV26', NULL),
('VE077', 'chưa thanh toan', 'LV26', NULL),
('VE078', 'chưa thanh toan', 'LV26', NULL),
('VE079', 'chưa thanh toan', 'LV27', NULL),
('VE080', 'chưa thanh toan', 'LV27', NULL),
('VE081', 'chưa thanh toan', 'LV27', NULL),
('VE082', 'chưa thanh toan', 'LV28', NULL),
('VE083', 'chưa thanh toan', 'LV28', NULL),
('VE084', 'chưa thanh toan', 'LV28', NULL),
('VE085', 'chưa thanh toan', 'LV29', NULL),
('VE086', 'chưa thanh toan', 'LV29', NULL),
('VE087', 'chưa thanh toan', 'LV29', NULL),
('VE088', 'chưa thanh toan', 'LV30', NULL),
('VE089', 'chưa thanh toan', 'LV30', NULL),
('VE090', 'chưa thanh toan', 'LV30', NULL),
('VE091', 'chưa thanh toan', 'LV31', NULL),
('VE092', 'chưa thanh toan', 'LV31', NULL),
('VE093', 'chưa thanh toan', 'LV31', NULL),
('VE094', 'chưa thanh toan', 'LV32', NULL),
('VE095', 'chưa thanh toan', 'LV32', NULL),
('VE096', 'chưa thanh toan', 'LV32', NULL),
('VE097', 'chưa thanh toan', 'LV33', NULL),
('VE098', 'chưa thanh toan', 'LV33', NULL),
('VE099', 'chưa thanh toan', 'LV33', NULL),
('VE100', 'chưa thanh toan', 'LV34', NULL),
('VE101', 'chưa thanh toan', 'LV34', NULL),
('VE102', 'chưa thanh toan', 'LV34', NULL),
('VE103', 'chưa thanh toan', 'LV35', NULL),
('VE104', 'chưa thanh toan', 'LV35', NULL),
('VE105', 'chưa thanh toan', 'LV35', NULL),
('VE106', 'chưa thanh toan', 'LV36', NULL),
('VE107', 'chưa thanh toan', 'LV36', NULL),
('VE108', 'chưa thanh toan', 'LV36', NULL),
('VE109', 'chưa thanh toan', 'LV37', NULL),
('VE110', 'chưa thanh toan', 'LV37', NULL),
('VE111', 'chưa thanh toan', 'LV37', NULL),
('VE112', 'chưa thanh toan', 'LV38', NULL),
('VE113', 'chưa thanh toan', 'LV38', NULL),
('VE114', 'chưa thanh toan', 'LV38', NULL),
('VE115', 'chưa thanh toan', 'LV39', NULL),
('VE116', 'chưa thanh toan', 'LV39', NULL),
('VE117', 'chưa thanh toan', 'LV39', NULL),
('VE118', 'chưa thanh toan', 'LV40', NULL),
('VE119', 'chưa thanh toan', 'LV40', NULL),
('VE120', 'chưa thanh toan', 'LV40', NULL),
('VE121', 'chưa thanh toan', 'LV41', NULL),
('VE122', 'chưa thanh toan', 'LV41', NULL),
('VE123', 'chưa thanh toan', 'LV41', NULL),
('VE124', 'chưa thanh toan', 'LV42', NULL),
('VE125', 'chưa thanh toan', 'LV42', NULL),
('VE126', 'chưa thanh toan', 'LV42', NULL),
('VE127', 'chưa thanh toan', 'LV43', NULL),
('VE128', 'chưa thanh toan', 'LV43', NULL),
('VE129', 'chưa thanh toan', 'LV43', NULL),
('VE130', 'chưa thanh toan', 'LV44', NULL),
('VE131', 'chưa thanh toan', 'LV44', NULL),
('VE132', 'chưa thanh toan', 'LV44', NULL),
('VE133', 'chưa thanh toan', 'LV45', NULL),
('VE134', 'chưa thanh toan', 'LV45', NULL),
('VE135', 'chưa thanh toan', 'LV45', NULL),
('VE136', 'chưa thanh toan', 'LV46', NULL),
('VE137', 'chưa thanh toan', 'LV46', NULL),
('VE138', 'chưa thanh toan', 'LV46', NULL),
('VE157', 'chưa thanh toan', 'LV53', NULL),
('VE158', 'chưa thanh toan', 'LV53', NULL),
('VE159', 'chưa thanh toan', 'LV53', NULL),
('VE160', 'chưa thanh toan', 'LV54', NULL),
('VE161', 'chưa thanh toan', 'LV54', NULL),
('VE162', 'chưa thanh toan', 'LV54', NULL),
('VE163', 'chưa thanh toan', 'LV55', NULL),
('VE164', 'chưa thanh toan', 'LV55', NULL),
('VE165', 'chưa thanh toan', 'LV55', NULL),
('VE166', 'chưa thanh toan', 'LV56', NULL),
('VE167', 'chưa thanh toan', 'LV56', NULL),
('VE168', 'chưa thanh toan', 'LV56', NULL),
('VE169', 'chưa thanh toan', 'LV57', NULL),
('VE170', 'chưa thanh toan', 'LV57', NULL),
('VE171', 'chưa thanh toan', 'LV57', NULL),
('VE172', 'Đã giữ chỗ', 'LV58', NULL),
('VE173', 'chưa thanh toan', 'LV58', NULL),
('VE174', 'chưa thanh toan', 'LV58', NULL),
('VE175', 'chưa thanh toan', 'LV59', NULL),
('VE176', 'chưa thanh toan', 'LV59', NULL),
('VE177', 'chưa thanh toan', 'LV59', NULL),
('VE178', 'chưa thanh toan', 'LV60', NULL),
('VE179', 'chưa thanh toan', 'LV60', NULL),
('VE180', 'chưa thanh toan', 'LV60', NULL),
('VE181', 'chưa thanh toan', 'LV61', NULL),
('VE182', 'chưa thanh toan', 'LV61', NULL),
('VE183', 'chưa thanh toan', 'LV61', NULL),
('VE184', 'chưa thanh toan', 'LV62', NULL),
('VE185', 'chưa thanh toan', 'LV62', NULL),
('VE186', 'chưa thanh toan', 'LV62', NULL),
('VE187', 'chưa thanh toan', 'LV63', NULL),
('VE188', 'chưa thanh toan', 'LV63', NULL),
('VE189', 'chưa thanh toan', 'LV63', NULL),
('VE190', 'chưa thanh toan', 'LV64', NULL),
('VE191', 'chưa thanh toan', 'LV64', NULL),
('VE192', 'chưa thanh toan', 'LV64', NULL),
('VE193', 'chưa thanh toan', 'LV65', NULL),
('VE194', 'chưa thanh toan', 'LV65', NULL),
('VE195', 'chưa thanh toan', 'LV65', NULL),
('VE196', 'chưa thanh toan', 'LV66', NULL),
('VE197', 'chưa thanh toan', 'LV66', NULL),
('VE198', 'chưa thanh toan', 'LV66', NULL),
('VE199', 'chưa thanh toan', 'LV67', NULL),
('VE200', 'chưa thanh toan', 'LV67', NULL),
('VE201', 'chưa thanh toan', 'LV67', NULL),
('VE202', 'chưa thanh toan', 'LV68', NULL),
('VE203', 'chưa thanh toan', 'LV68', NULL),
('VE204', 'chưa thanh toan', 'LV68', NULL),
('VE205', 'chưa thanh toan', 'LV69', NULL),
('VE206', 'chưa thanh toan', 'LV69', NULL),
('VE207', 'chưa thanh toan', 'LV69', NULL),
('VE208', 'chưa thanh toan', 'LV70', NULL),
('VE209', 'chưa thanh toan', 'LV70', NULL),
('VE210', 'chưa thanh toan', 'LV70', NULL),
('VE211', 'chưa thanh toan', 'LV71', NULL),
('VE212', 'chưa thanh toan', 'LV71', NULL),
('VE213', 'chưa thanh toan', 'LV71', NULL),
('VE214', 'chưa thanh toan', 'LV72', NULL),
('VE215', 'chưa thanh toan', 'LV72', NULL),
('VE216', 'chưa thanh toan', 'LV72', NULL),
('VE217', 'chưa thanh toan', 'LV73', NULL),
('VE218', 'chưa thanh toan', 'LV73', NULL),
('VE219', 'chưa thanh toan', 'LV73', NULL),
('VE220', 'chưa thanh toan', 'LV74', NULL),
('VE221', 'chưa thanh toan', 'LV74', NULL),
('VE222', 'chưa thanh toan', 'LV74', NULL),
('VE223', 'chưa thanh toan', 'LV75', NULL),
('VE224', 'chưa thanh toan', 'LV75', NULL),
('VE225', 'chưa thanh toan', 'LV75', NULL),
('VE226', 'chưa thanh toan', 'LV76', NULL),
('VE227', 'chưa thanh toan', 'LV76', NULL),
('VE228', 'chưa thanh toan', 'LV76', NULL),
('VE229', 'chưa thanh toan', 'LV77', NULL),
('VE230', 'chưa thanh toan', 'LV77', NULL),
('VE231', 'chưa thanh toan', 'LV77', NULL),
('VE232', 'chưa thanh toan', 'LV78', NULL),
('VE233', 'chưa thanh toan', 'LV78', NULL),
('VE234', 'chưa thanh toan', 'LV78', NULL),
('VE235', 'chưa thanh toan', 'LV79', NULL),
('VE236', 'chưa thanh toan', 'LV79', NULL),
('VE237', 'chưa thanh toan', 'LV79', NULL),
('VE238', 'chưa thanh toan', 'LV80', NULL),
('VE239', 'chưa thanh toan', 'LV80', NULL),
('VE240', 'chưa thanh toan', 'LV80', NULL),
('VE241', 'chưa thanh toan', 'LV81', NULL),
('VE242', 'chưa thanh toan', 'LV81', NULL),
('VE243', 'chưa thanh toan', 'LV81', NULL),
('VE244', 'chưa thanh toan', 'LV82', NULL),
('VE245', 'chưa thanh toan', 'LV82', NULL),
('VE246', 'chưa thanh toan', 'LV82', NULL),
('VE247', 'chưa thanh toan', 'LV83', NULL),
('VE248', 'chưa thanh toan', 'LV83', NULL),
('VE249', 'chưa thanh toan', 'LV83', NULL),
('VE250', 'chưa thanh toan', 'LV84', NULL),
('VE251', 'chưa thanh toan', 'LV84', NULL),
('VE252', 'chưa thanh toan', 'LV84', NULL),
('VE253', 'chưa thanh toan', 'LV85', NULL),
('VE254', 'chưa thanh toan', 'LV85', NULL),
('VE255', 'chưa thanh toan', 'LV85', NULL),
('VE256', 'chưa thanh toan', 'LV86', NULL),
('VE257', 'chưa thanh toan', 'LV86', NULL),
('VE258', 'chưa thanh toan', 'LV86', NULL),
('VE259', 'chưa thanh toan', 'LV87', NULL),
('VE260', 'chưa thanh toan', 'LV87', NULL),
('VE261', 'chưa thanh toan', 'LV87', NULL),
('VE262', 'chưa thanh toan', 'LV88', NULL),
('VE263', 'chưa thanh toan', 'LV88', NULL),
('VE264', 'chưa thanh toan', 'LV88', NULL),
('VE265', 'chưa thanh toan', 'LV89', NULL),
('VE266', 'chưa thanh toan', 'LV89', NULL),
('VE267', 'chưa thanh toan', 'LV89', NULL),
('VE268', 'chưa thanh toan', 'LV90', NULL),
('VE269', 'chưa thanh toan', 'LV90', NULL),
('VE270', 'chưa thanh toan', 'LV90', NULL),
('VE271', 'chưa thanh toan', 'LV91', NULL),
('VE272', 'chưa thanh toan', 'LV91', NULL),
('VE273', 'chưa thanh toan', 'LV91', NULL),
('VE274', 'chưa thanh toan', 'LV92', NULL),
('VE275', 'chưa thanh toan', 'LV92', NULL),
('VE276', 'chưa thanh toan', 'LV92', NULL),
('VE277', 'chưa thanh toan', 'LV93', NULL),
('VE278', 'chưa thanh toan', 'LV93', NULL),
('VE279', 'chưa thanh toan', 'LV93', NULL),
('VE280', 'chưa thanh toan', 'LV94', NULL),
('VE281', 'chưa thanh toan', 'LV94', NULL),
('VE282', 'chưa thanh toan', 'LV94', NULL),
('VE283', 'chưa thanh toan', 'LV95', NULL),
('VE284', 'chưa thanh toan', 'LV95', NULL),
('VE285', 'chưa thanh toan', 'LV95', NULL),
('VE286', 'chưa thanh toan', 'LV96', NULL),
('VE287', 'chưa thanh toan', 'LV96', NULL),
('VE288', 'chưa thanh toan', 'LV96', NULL),
('VE289', 'Đã giữ chỗ', 'LV97', NULL),
('VE290', 'chưa thanh toan', 'LV97', NULL),
('VE291', 'chưa thanh toan', 'LV97', NULL),
('VE292', 'chưa thanh toan', 'LV98', NULL),
('VE293', 'chưa thanh toan', 'LV98', NULL),
('VE294', 'chưa thanh toan', 'LV98', NULL),
('VE295', 'chưa thanh toan', 'LV98', NULL),
('VE296', 'chưa thanh toan', 'LV98', NULL),
('VE297', 'chưa thanh toan', 'LV99', NULL),
('VE298', 'chưa thanh toan', 'LV99', NULL),
('VE299', 'chưa thanh toan', 'LV99', NULL),
('VE300', 'chưa thanh toan', 'LV99', NULL),
('VE301', 'chưa thanh toan', 'LV99', NULL),
('VE302', 'chưa thanh toan', 'LV100', NULL),
('VE303', 'chưa thanh toan', 'LV100', NULL),
('VE304', 'chưa thanh toan', 'LV100', NULL),
('VE305', 'chưa thanh toan', 'LV100', NULL),
('VE306', 'chưa thanh toan', 'LV100', NULL),
('VE307', 'chưa thanh toan', 'LV101', NULL),
('VE308', 'chưa thanh toan', 'LV101', NULL),
('VE309', 'chưa thanh toan', 'LV101', NULL),
('VE310', 'chưa thanh toan', 'LV101', NULL),
('VE311', 'chưa thanh toan', 'LV101', NULL),
('VE312', 'chưa thanh toan', 'LV102', NULL),
('VE313', 'chưa thanh toan', 'LV102', NULL),
('VE314', 'chưa thanh toan', 'LV102', NULL),
('VE315', 'chưa thanh toan', 'LV102', NULL),
('VE316', 'chưa thanh toan', 'LV102', NULL),
('VE317', 'chưa thanh toan', 'LV103', NULL),
('VE318', 'chưa thanh toan', 'LV103', NULL),
('VE319', 'chưa thanh toan', 'LV103', NULL),
('VE320', 'chưa thanh toan', 'LV103', NULL),
('VE321', 'chưa thanh toan', 'LV103', NULL),
('VE322', 'chưa thanh toan', 'LV104', NULL),
('VE323', 'chưa thanh toan', 'LV104', NULL),
('VE324', 'chưa thanh toan', 'LV104', NULL),
('VE325', 'chưa thanh toan', 'LV104', NULL),
('VE326', 'chưa thanh toan', 'LV104', NULL),
('VE327', 'chưa thanh toan', 'LV105', NULL),
('VE328', 'chưa thanh toan', 'LV105', NULL),
('VE329', 'chưa thanh toan', 'LV105', NULL),
('VE330', 'chưa thanh toan', 'LV105', NULL),
('VE331', 'chưa thanh toan', 'LV105', NULL),
('VE332', 'chưa thanh toan', 'LV106', NULL),
('VE333', 'chưa thanh toan', 'LV106', NULL),
('VE334', 'chưa thanh toan', 'LV106', NULL),
('VE335', 'chưa thanh toan', 'LV106', NULL),
('VE336', 'chưa thanh toan', 'LV106', NULL),
('VE337', 'chưa thanh toan', 'LV107', NULL),
('VE338', 'chưa thanh toan', 'LV107', NULL),
('VE339', 'chưa thanh toan', 'LV107', NULL),
('VE340', 'chưa thanh toan', 'LV107', NULL),
('VE341', 'chưa thanh toan', 'LV107', NULL),
('VE342', 'chưa thanh toan', 'LV108', NULL),
('VE343', 'chưa thanh toan', 'LV108', NULL),
('VE344', 'chưa thanh toan', 'LV108', NULL),
('VE345', 'chưa thanh toan', 'LV108', NULL),
('VE346', 'chưa thanh toan', 'LV108', NULL),
('VE347', 'chưa thanh toan', 'LV109', NULL),
('VE348', 'chưa thanh toan', 'LV109', NULL),
('VE349', 'chưa thanh toan', 'LV109', NULL),
('VE350', 'chưa thanh toan', 'LV109', NULL),
('VE351', 'chưa thanh toan', 'LV109', NULL),
('VE352', 'chưa thanh toan', 'LV110', NULL),
('VE353', 'chưa thanh toan', 'LV110', NULL),
('VE354', 'chưa thanh toan', 'LV110', NULL),
('VE355', 'chưa thanh toan', 'LV110', NULL),
('VE356', 'chưa thanh toan', 'LV110', NULL),
('VE357', 'chưa thanh toan', 'LV111', NULL),
('VE358', 'chưa thanh toan', 'LV111', NULL),
('VE359', 'chưa thanh toan', 'LV111', NULL),
('VE360', 'chưa thanh toan', 'LV111', NULL),
('VE361', 'chưa thanh toan', 'LV111', NULL),
('VE362', 'chưa thanh toan', 'LV112', NULL),
('VE363', 'chưa thanh toan', 'LV112', NULL),
('VE364', 'chưa thanh toan', 'LV112', NULL),
('VE365', 'chưa thanh toan', 'LV112', NULL),
('VE366', 'chưa thanh toan', 'LV112', NULL),
('VE367', 'chưa thanh toan', 'LV113', NULL),
('VE368', 'chưa thanh toan', 'LV113', NULL),
('VE369', 'chưa thanh toan', 'LV113', NULL),
('VE370', 'chưa thanh toan', 'LV113', NULL),
('VE371', 'chưa thanh toan', 'LV113', NULL),
('VE372', 'chưa thanh toan', 'LV114', NULL),
('VE373', 'chưa thanh toan', 'LV114', NULL),
('VE374', 'chưa thanh toan', 'LV114', NULL),
('VE375', 'chưa thanh toan', 'LV114', NULL),
('VE376', 'chưa thanh toan', 'LV114', NULL),
('VE377', 'chưa thanh toan', 'LV115', NULL),
('VE378', 'chưa thanh toan', 'LV115', NULL),
('VE379', 'chưa thanh toan', 'LV115', NULL),
('VE380', 'chưa thanh toan', 'LV115', NULL),
('VE381', 'chưa thanh toan', 'LV115', NULL),
('VE382', 'chưa thanh toan', 'LV116', NULL),
('VE383', 'chưa thanh toan', 'LV116', NULL),
('VE384', 'chưa thanh toan', 'LV116', NULL),
('VE385', 'chưa thanh toan', 'LV116', NULL),
('VE386', 'chưa thanh toan', 'LV116', NULL),
('VE387', 'chưa thanh toan', 'LV117', NULL),
('VE388', 'chưa thanh toan', 'LV117', NULL),
('VE389', 'chưa thanh toan', 'LV117', NULL),
('VE390', 'chưa thanh toan', 'LV117', NULL),
('VE391', 'chưa thanh toan', 'LV117', NULL),
('VE392', 'chưa thanh toan', 'LV118', NULL),
('VE393', 'chưa thanh toan', 'LV118', NULL),
('VE394', 'chưa thanh toan', 'LV118', NULL),
('VE395', 'chưa thanh toan', 'LV118', NULL),
('VE396', 'chưa thanh toan', 'LV118', NULL),
('VE397', 'chưa thanh toan', 'LV119', NULL),
('VE398', 'chưa thanh toan', 'LV119', NULL),
('VE399', 'chưa thanh toan', 'LV119', NULL),
('VE400', 'chưa thanh toan', 'LV119', NULL),
('VE401', 'chưa thanh toan', 'LV119', NULL),
('VE402', 'chưa thanh toan', 'LV120', NULL),
('VE403', 'chưa thanh toan', 'LV120', NULL),
('VE404', 'chưa thanh toan', 'LV120', NULL),
('VE405', 'chưa thanh toan', 'LV120', NULL),
('VE406', 'chưa thanh toan', 'LV120', NULL),
('VE407', 'chưa thanh toan', 'LV121', NULL),
('VE408', 'chưa thanh toan', 'LV121', NULL),
('VE409', 'chưa thanh toan', 'LV121', NULL),
('VE410', 'chưa thanh toan', 'LV122', NULL),
('VE411', 'chưa thanh toan', 'LV122', NULL),
('VE412', 'chưa thanh toan', 'LV122', NULL),
('VE413', 'chưa thanh toan', 'LV123', NULL),
('VE414', 'chưa thanh toan', 'LV123', NULL),
('VE415', 'chưa thanh toan', 'LV123', NULL),
('VE416', 'chưa thanh toan', 'LV124', NULL),
('VE417', 'chưa thanh toan', 'LV124', NULL),
('VE418', 'chưa thanh toan', 'LV124', NULL),
('VE419', 'chưa thanh toan', 'LV125', NULL),
('VE420', 'chưa thanh toan', 'LV125', NULL),
('VE421', 'chưa thanh toan', 'LV125', NULL),
('VE422', 'chưa thanh toan', 'LV126', NULL),
('VE423', 'chưa thanh toan', 'LV126', NULL),
('VE424', 'chưa thanh toan', 'LV126', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diadiem`
--
ALTER TABLE `diadiem`
  ADD PRIMARY KEY (`MaDD`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `loaisk`
--
ALTER TABLE `loaisk`
  ADD PRIMARY KEY (`MaloaiSK`);

--
-- Indexes for table `loaive`
--
ALTER TABLE `loaive`
  ADD PRIMARY KEY (`MaLoai`),
  ADD KEY `FK_LoaiVe_SuKien` (`MaSK`);

--
-- Indexes for table `nhanviensoatve`
--
ALTER TABLE `nhanviensoatve`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `nhatochuc`
--
ALTER TABLE `nhatochuc`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `quantrivien`
--
ALTER TABLE `quantrivien`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sukien`
--
ALTER TABLE `sukien`
  ADD PRIMARY KEY (`MaSK`),
  ADD KEY `fk_maloaisk` (`MaLSK`),
  ADD KEY `fk_madd` (`MaDD`);

--
-- Indexes for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MaTT`),
  ADD KEY `FK_ThanhToan_User` (`Email_KH`);

--
-- Indexes for table `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`MaVe`),
  ADD KEY `FK_Ve_LoaiVe` (`MaLoai`),
  ADD KEY `FK_ThanhToan_Ve` (`MaTT`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sukien`
--
ALTER TABLE `sukien`
  ADD CONSTRAINT `fk_madd` FOREIGN KEY (`MaDD`) REFERENCES `diadiem` (`MaDD`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maloaisk` FOREIGN KEY (`MaLSK`) REFERENCES `loaisk` (`MaloaiSK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `FK_ThanhToan_User` FOREIGN KEY (`Email_KH`) REFERENCES `khachhang` (`email`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ve`
--
ALTER TABLE `ve`
  ADD CONSTRAINT `FK_ThanhToan_Ve` FOREIGN KEY (`MaTT`) REFERENCES `thanhtoan` (`MaTT`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Ve_ThanhToan` FOREIGN KEY (`MaTT`) REFERENCES `thanhtoan` (`MaTT`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
