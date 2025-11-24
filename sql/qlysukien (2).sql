-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 09:41 AM
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
('DN', 'Đà Nẵng'),
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
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`email`, `user_name`, `tel`, `password`, `reset_token`, `reset_token_expiry`) VALUES
('a@ctu.edu.vn', 'Hopi', '0123456789', '$2y$10$2juc2Swf7kVRUGdSAM1EnOdG1sYkKy3WJeyAIsdyAew5cDXltsfZm', NULL, NULL),
('abc@ctu.edu.vn', 'tram', '0123456789', '$2y$10$Ouq95OSwe61uqltLdop1x.kEcWnFm5pordRe7ipxsdXuWJKilQ9CC', NULL, NULL),
('abc@gmail.com', 'acb', '0234365711', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('b@ctu.edu.vn', 'hehe', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('bc@ctu.edu.vn', 'tram', '0123456789', '$2y$10$IKMMui8Ub8xHe5uoKu1xnuCw.L3GdKaO8f5JQtgyAdoNmtkTNBr5K', NULL, NULL),
('hehe@ctu.edu.vn', 'hehe', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('helo@gmail.com', 'helo', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('hi@gmail.com', 'hi', '0234365711', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('hihi@ctu.edu.vn', 'hihi', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('huhu@gmail.com', 'huhu', '0123456789', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
('huynhtram020405@gmail.com', 'tram', '0234365711', '$2y$10$aJpS9VFOhclgCKWBhAbi0ulxPlOnlUzTIpQ64VyTxJZzFfx01GsdK', NULL, NULL),
('ihi@gmail.com', 'ihi', '0123456789', '25f9e794323b453885f5181f1b624d0b', NULL, NULL),
('slpluckysam@gmail.com', 'Ngoc', '0123456789', '$2y$10$HDKxz49QuON1wXRNjHwcKOxa06xURvUpDUmarf2VTnSEwKljuULOm', NULL, NULL),
('test@gmail.com', 'test', '0246747894', 'b0baee9d279d34fa1dfd71aadb908c3f', NULL, NULL),
('tram@gmail.com', 'tram', '0234365711', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL);

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
  `MoTa` text DEFAULT NULL,
  `MaSK` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaive`
--

INSERT INTO `loaive` (`MaLoai`, `TenLoai`, `Gia`, `MoTa`, `MaSK`) VALUES
('LV06', 'STANDARD 2', 500000, 'Bao gồm các ghế Sofa (Ghế W) nằm ở cánh phải của khu vực tầng lửng.|Vị trí xa hơn nhưng mang lại tầm nhìn bao quát toàn bộ sân khấu.', 'SK02'),
('LV07', 'STANDARD 1', 800000, 'Bao gồm các hàng ghế ngồi ngay sau khu SUPER VIP .|Cũng bao gồm các ghế Sofa (Ghế W) nằm ở hai bên cánh khu vực tầng lửng.', 'SK02'),
('LV08', 'VIP', 1100000, 'Nằm ở hai bên cánh, sát cạnh khu SUPER VIP.|Vị trí gần sân khấu với góc nhìn rõ ràng.', 'SK02'),
('LV09', 'SUPER VIP', 1200000, 'Vị trí gần sân khấu nhất, nằm ngay trung tâm.|Mang lại trải nghiệm âm thanh và tầm nhìn cận cảnh tốt nhất.', 'SK02'),
('LV100', 'VIP 1.A2', 4000000, 'Khu vực ghế ngồi SEATING (VIP 1.A2).|Vị trí cánh trái, khu vực phía ngoài.|Quyền lợi bao gồm Lightstick.', 'SK23'),
('LV101', 'VIP 1.C1', 5000000, 'Khu vực ghế ngồi SEATING (VIP 1.C1).|Vị trí trung tâm, phía sau khu VVIP (bên trái).|Quyền lợi bao gồm Lightstick.', 'SK23'),
('LV102', 'VIP 1.C2', 5000000, 'Khu vực ghế ngồi SEATING (VIP 1.C2).|Vị trí trung tâm, phía sau khu VVIP (bên phải).|Quyền lợi bao gồm Lightstick.', 'SK23'),
('LV103', 'VIP 1.B1', 4000000, 'Khu vực ghế ngồi SEATING (VIP 1.B1).|Vị trí cánh phải, khu vực phía trong.|Quyền lợi bao gồm Lightstick.', 'SK23'),
('LV110', '[SEATING] HẠNH PHÚC 1', 5200000, NULL, 'SK25'),
('LV111', '[SEATING] HẠNH PHÚC KD-A', 5200000, NULL, 'SK25'),
('LV112', '[SEATING] TỰ HÀO 1', 4500000, NULL, 'SK25'),
('LV113', '[SEATING] TỰ HÀO 2', 4500000, NULL, 'SK25'),
('LV120', 'GA', 800000, NULL, 'SK27'),
('LV121', 'COOL', 9000000, 'Âm thanh L\'acoustics L2D|Vị trí trung tâm sân khấu', 'SK13'),
('LV122', 'MODAL', 8000000, 'Âm thanh L\'acoustics L2D|Vị trí trung tâm sân khấu', 'SK13'),
('LV123', 'BIG BAND (Left)', 6000000, 'Âm thanh L\'acoustics L2D|Vị trí hai bên gần sân khấu', 'SK13'),
('LV124', 'BIG BAND (Right)', 6000000, 'Âm thanh L\'acoustics L2D|Vị trí hai bên gần sân khấu', 'SK13'),
('LV125', 'SWING (Left)', 5000000, 'Âm thanh L\'acoustics L2D|Vị trí hai bên trên cao sân khấu', 'SK13'),
('LV126', 'SWING (Right)', 5000000, 'Âm thanh L\'acoustics L2D|Vị trí hai bên trên cao sân khấu', 'SK13'),
('LV127', 'VVIP (Send off and Sound Check)', 8000000, 'Khu vực SEATING ZONE.|Bao gồm quyền lợi Send Off và Sound Check.|Vị trí ghế ngồi, màu hồng đậm (VVIP).', 'SK03'),
('LV128', 'CAT 2B', 5000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xanh lá mạ (cánh phải).|Nằm sau khu CAT 1B.', 'SK03'),
('LV129', 'CAT 3A', 4000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu hồng nhạt (cánh trái).|Nằm sau khu GA 4A.', 'SK03'),
('LV130', 'CAT 3B', 4000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu hồng nhạt (cánh phải).|Nằm sau khu GA 4B.', 'SK03'),
('LV131', 'CAT 3A (Restricted View)', 3600000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh trái).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV132', 'CAT 3B (Restricted View)', 3600000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh phải).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV133', 'CAT 4A', 3500000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xanh dương (cánh trái).|Nằm sau khu CAT 3A.', 'SK03'),
('LV134', 'CAT 4B', 3500000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xanh dương (cánh phải).|Nằm sau khu CAT 3B.', 'SK03'),
('LV135', 'CAT 4A (Restricted View)', 3000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh trái).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV136', 'CAT 4B (Restricted View)', 3000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh phải).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV137', 'CAT 5A', 5000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu nâu (cánh trái).|Nằm sau khu GA 4A (Standing).', 'SK03'),
('LV138', 'CAT 5B', 5000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu nâu (cánh phải).|Nằm sau khu GA 4B (Standing).', 'SK03'),
('LV139', 'CAT 6A', 4000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu tím (cánh trái).|Nằm sau khu CAT 5A.', 'SK03'),
('LV140', 'CAT 6B', 4000000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu tím (cánh phải).|Nằm sau khu CAT 5B.', 'SK03'),
('LV141', 'CAT 6A (Restricted View)', 3600000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh trái, phía sau).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV142', 'CAT 6B (Restricted View)', 3600000, 'Khu vực SEATING ZONE.|Vị trí ghế ngồi, màu xám (cánh phải, phía sau).|Tầm nhìn có thể bị hạn chế.', 'SK03'),
('LV143', 'GA 1A (Standing)', 3300000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu xanh ngọc (cánh trái, gần sân khấu).', 'SK03'),
('LV144', 'GA 1B (Standing)', 3300000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu xanh ngọc (cánh phải, gần sân khấu).', 'SK03'),
('LV145', 'GA 2A (Standing)', 3800000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu cam nhạt (trung tâm, bên trái).|Gần sân khấu phụ.', 'SK03'),
('LV146', 'GA 2B (Standing)', 3800000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu cam nhạt (trung tâm, bên phải).|Gần sân khấu phụ.', 'SK03'),
('LV147', 'GA 3A (Standing)', 3300000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu cam đậm (trung tâm, bên trái).|Nằm sau khu GA 2A.', 'SK03'),
('LV148', 'GA 3B (Standing)', 3300000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu cam đậm (trung tâm, bên phải).|Nằm sau khu GA 2B.', 'SK03'),
('LV149', 'GA 4A (Standing)', 2000000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu tím (cánh trái).|Nằm sau khu GA 1A.', 'SK03'),
('LV150', 'GA 4B (Standing)', 2000000, 'Khu vực STANDING ZONE.|Vị trí đứng, màu tím (cánh phải).|Nằm sau khu GA 1B.', 'SK03'),
('LV151', 'Kết Nối 1 (Standing)', 2000000, 'Khu vực ĐỨNG (Standing).|Nằm ở trung tâm, cánh trái, sau khu Đã Đen 1.', 'SK24'),
('LV152', 'Kết Nối 2 (Standing)', 2000000, 'Khu vực ĐỨNG (Standing).|Nằm ở trung tâm, cánh phải, sau khu Đã Đen 2.', 'SK24'),
('LV153', 'Rẽ Sóng 1 (Standing)', 1200000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh trái, sau khu Kết Nối 1.', 'SK24'),
('LV154', 'Rẽ Sóng 2 (Standing)', 1200000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh phải, sau khu Kết Nối 2.', 'SK24'),
('LV155', 'Đạp Gió 1 (Standing)', 1200000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh trái, sau khu Rẽ Sóng 1.', 'SK24'),
('LV156', 'Đạp Gió 2 (Standing)', 1200000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh phải, sau khu Rẽ Sóng 2.', 'SK24'),
('LV157', 'Đã Đen 1 (Standing)', 800000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh trái, sau khu Gieo Mầm 1.', 'SK24'),
('LV158', 'Đã Đen 2 (Standing)', 800000, 'Khu vực ĐỨNG (Standing).|Nằm ở cánh phải, sau khu Gieo Mầm 2.', 'SK24'),
('LV159', 'Toàn Năng 1 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh trái, phía sau S-VIP.', 'SK24'),
('LV160', 'Toàn Năng 2 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh phải, phía sau S-VIP.', 'SK24'),
('LV161', 'HAHA 1 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh trái, sau khu Toàn Năng 1.', 'SK24'),
('LV162', 'HAHA 2 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh phải, sau khu Toàn Năng 2.', 'SK24'),
('LV163', 'Bình Minh 1 (Seated)', 2500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh trái, sát lối đi, cạnh khu Đạp Gió 1.', 'SK24'),
('LV164', 'Bình Minh 2 (Seated)', 2500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh phải, sát lối đi, cạnh khu Đạp Gió 2.', 'SK24'),
('LV165', 'Show Me 1 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh trái, sau khu HAHA 1.', 'SK24'),
('LV166', 'Show Me 2 (Seated)', 4000000, 'Khu vực NGỒI (Seated).|Nằm ở trung tâm, cánh phải, sau khu HAHA 2.', 'SK24'),
('LV167', 'Hóa Cả 1 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh trái, phía ngoài cùng, cạnh khu Bình Minh 1.', 'SK24'),
('LV168', 'Hóa Cả 2 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh phải, phía ngoài cùng, cạnh khu Bình Minh 2.', 'SK24'),
('LV169', 'Bao La 1 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh trái, phía sau khu Thiên Hà 1.', 'SK24'),
('LV17', 'EARLY BIRD - GA', 899000, 'Vé giảm giá dành cho những người mua trước.|Quyền vào khu GA(Standing).|Vòng tay Check-in|Khăn chia đội (Số lượng khăn chia đội có hạn, phát theo thứ tự ưu tiên đến trước).', 'SK05'),
('LV170', 'Bao La 2 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh phải, phía sau khu Thiên Hà 2.', 'SK24'),
('LV171', 'Thiên Hà 1 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh trái, phía sau khu Đã Đen 1.', 'SK24'),
('LV172', 'Thiên Hà 2 (Seated)', 1500000, 'Khu vực NGỒI (Seated).|Nằm ở cánh phải, phía sau khu Đã Đen 2.', 'SK24'),
('LV173', 'S-VIP (VIP LOUNGE)', 10000000, 'Khu vực VIP LOUNGE (S-VIP).|Vị trí trung tâm, gần sân khấu nhất, phía trước FOH.', 'SK24'),
('LV174', 'Wheel Chair (Bình Minh 1)', 2500000, 'Khu vực dành cho xe lăn (Wheel Chair).|Nằm tại khu vực Bình Minh 1.', 'SK24'),
('LV18', 'DAY TIME CHECK-IN (GA)', 1099000, 'Vé giảm giá dành cho khu vực GA|Người sở hữu vé Daytime Check-in phải vào cổng từ 12:00 - 16:00.|Sau thời gian quy định, vé Daytime Check-in sẽ không còn hiệu lực vào cổng.|Quyền vào khu GA(Standing).|Vòng tay Check-in|Khăn chia đội (Số lượng khăn chia đội có hạn, phát theo thứ tự ưu tiên đến trước).', 'SK05'),
('LV19', '01 DAY PASS (NORMAL) - GA', 1169000, 'Vé giá thường.|Quyền vào khu GA(Standing).|Vòng tay Check-in|Khăn chia đội (Số lượng khăn chia đội có hạn, phát theo thứ tự ưu tiên đến trước).', 'SK05'),
('LV193', 'SVIP', 3800000, 'Vị trí: Gần sân khấu nhất (Khu SVIP).|PHOTOGROUP 1:8: Có.|PHOTOGROUP 1:15: Có.|SOUNDCHECK: Có.|HI-TOUCH: Có.|VIDEO MESSAGE: RANDOM 50.|POSTER CHỮ KÝ DIGITAL: Có.|CARD BÓ GÓC SELFIE: 3/3 + RANDOM 20 CHỮ KÝ TAY.|THIỆP VIẾT TAY: Có.|ÁO: Có.|TÚI TOTE: Có.|HUY HIỆU: Có.', 'SK29'),
('LV194', 'VIP', 3000000, 'Vị trí: Ngay sau khu SVIP (Khu VIP).|PHOTOGROUP 1:15: Có.|SOUNDCHECK: Có.|HI-TOUCH: Có.|VIDEO MESSAGE: RANDOM 30.|POSTER CHỮ KÝ DIGITAL: Có.|CARD BÓ GÓC SELFIE: 3/3 + RANDOM 10 CHỮ KÝ TAY.|THIỆP VIẾT TAY: Có.|ÁO: Có.|TÚI TOTE: Có.|HUY HIỆU: Có.', 'SK29'),
('LV195', 'CAT 1', 2200000, 'Vị trí: Phía sau khu VIP (Khu CAT 1).|HI-TOUCH: Có.|VIDEO MESSAGE: RANDOM 20.|POSTER CHỮ KÝ DIGITAL: Có.|CARD BÓ GÓC SELFIE: 3/3 + RANDOM 5 CHỮ KÝ TAY.|THIỆP VIẾT TAY: Có.|TÚI TOTE: Có.|HUY HIỆU: Có.', 'SK29'),
('LV196', 'CAT 2', 1500000, 'Vị trí: Tầng trên (Khu CAT 2).|VIDEO MESSAGE: RANDOM 10.|POSTER CHỮ KÝ DIGITAL: Có.|CARD BÓ GÓC SELFIE: 3/3.|THIỆP VIẾT TAY: Có.|TÚI TOTE: Có.|HUY HIỆU: Có.', 'SK29'),
('LV197', 'The Eternal Soul', 2200000, 'Không gian sang trọng, riêng tư với tầm nhìn hoàn hảo.|Nước uống và ăn nhẹ.|Khu vực Eternal Soul là ghế đôi kèm bàn, vì vậy khách vui lòng đặt vé theo cặp. Trường hợp khách đặt số lượng ghế lẻ (ví dụ: 1 ghế, 3 ghế, 5 ghế), Ban Tổ Chức sẽ sắp xếp ghép chỗ với khách đặt lẻ khác để ngồi chung bàn.', 'SK30'),
('LV198', 'The Dreamer Soul', 1400000, 'Không gian trung tâm, tầm nhìn toàn cảnh sân khấu', 'SK30'),
('LV199', 'The Free Soul', 900000, 'Không gian thưởng thức âm nhạc thoải mái theo hàng ghế đã chọn', 'SK30'),
('LV20', '02 DAY PASS - GA', 2099000, 'Đây là vé giảm giá dành cho những người mua vé trọn gói cả hai ngày.|Vé đảm bảo quyền vào cổng cho cả hai ngày diễn ra sự kiện (15-16.11.2025).|Quyền vào khu GA(Standing).|Vòng tay Check-in|Khăn chia đội (Số lượng khăn chia đội có hạn, phát theo thứ tự ưu tiên đến trước).', 'SK05'),
('LV21', 'Full Day Access + GA 1', 699000, 'Khu vực đứng GA 1.|Nằm ngay sau khu vực FANZONE 1 (bên trái).|Tầm nhìn rõ ràng, không gian rộng rãi.', 'SK06'),
('LV22', 'Full Day Access + GA 2', 699000, 'Khu vực đứng GA 2.|Nằm ngay sau khu vực FANZONE 2 (bên phải).|Tầm nhìn rõ ràng, không gian rộng rãi.', 'SK06'),
('LV23', 'Full Day Access + FANZONE 1', 999000, 'Khu vực đứng FANZONE 1.|Vị trí sát sân khấu nhất (phía bên trái).|Mang lại trải nghiệm cận cảnh và cuồng nhiệt.', 'SK06'),
('LV24', 'Full Day Access + FANZONE 2', 999000, 'Khu vực đứng FANZONE 2.|Vị trí sát sân khấu nhất (phía bên phải).|Mang lại trải nghiệm cận cảnh và cuồng nhiệt.', 'SK06'),
('LV25', 'RVIP Khu R (Seated)', 4550000, 'Khu vực GHẾ NGỒI (Seated) cao cấp.|Nằm ở vị trí trung tâm, ngay sau khu vực kỹ thuật (FOH).|Tầm nhìn thẳng, bao quát và trọn vẹn sân khấu (phía bên phải).', 'SK07'),
('LV26', 'RVIP Khu L (Seated)', 4550000, 'Khu vực GHẾ NGỒI (Seated) cao cấp.|Nằm ở vị trí trung tâm, ngay sau khu vực kỹ thuật (FOH).|Tầm nhìn thẳng, bao quát và trọn vẹn sân khấu (phía bên trái).', 'SK07'),
('LV27', 'VIP Khu R (Standing)', 3600000, 'Khu vực ĐỨNG (Standing) sát sân khấu nhất.|Nằm ở phía BÊN PHẢI sân khấu.|Mang lại trải nghiệm cận cảnh, gần nhất với nghệ sĩ.', 'SK07'),
('LV28', 'VIP Khu L (Standing)', 3600000, 'Khu vực ĐỨNG (Standing) sát sân khấu nhất.|Nằm ở phía BÊN TRÁI sân khấu.|Mang lại trải nghiệm cận cảnh, gần nhất với nghệ sĩ.', 'SK07'),
('LV29', 'S1 Khu R (Seated)', 2560000, 'Khu vực GHẾ NGỒI (Seated) trên lầu.|Nằm ở phía sau khu RVIP, cung cấp tầm nhìn từ trên cao (phía bên phải).', 'SK07'),
('LV30', 'S1 Khu L (Seated)', 2560000, 'Khu vực GHẾ NGỒI (Seated) trên lầu.|Nằm ở phía sau khu RVIP, cung cấp tầm nhìn từ trên cao (phía bên trái).', 'SK07'),
('LV31', 'THE HEART 1', 2500000, 'Khu vực ĐỨNG (Standing).|Vị trí sát sân khấu nhất (bên trái).|LED WRISTBAND.|PHOTOCARD: 2 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 60 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV32', 'THE HEART 2', 2500000, 'Khu vực ĐỨNG (Standing).|Vị trí sát sân khấu nhất (bên phải).|LED WRISTBAND.|PHOTOCARD: 2 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 60 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV33', 'THE FACE 1', 2000000, 'Khu vực ĐỨNG (Standing).|Nằm sau khu THE HEART 1 (bên trái).|LED WRISTBAND.|PHOTOCARD: 2 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 30 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV34', 'THE FACE 2', 2000000, 'Khu vực ĐỨNG (Standing).|Nằm sau khu THE HEART 2 (bên phải).|LED WRISTBAND.|PHOTOCARD: 2 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 30 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV35', 'THE ENERGY 1', 1800000, 'Khu vực ĐỨNG (Standing).|Nằm sau khu THE FACE 1 (bên trái).|LED WRISTBAND.|PHOTOCARD: 1 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 30 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV36', 'THE ENERGY 2', 1800000, 'Khu vực ĐỨNG (Standing).|Nằm sau khu THE FACE 2 (bên phải).|LED WRISTBAND.|PHOTOCARD: 1 (Random).|ECO BAG.|SOUNDCHECK ACCESS: Random 30 người.|EXCLUSIVE CHECK-IN LANE.', 'SK08'),
('LV37', 'Regular Ticket', 755000, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.', 'SK09'),
('LV38', 'Combo 1 Regular Ticket + 1 Lightstick NTPMM (-2%)', 1081920, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Vé không áp dụng ưu đãi/giảm giá từ NTPMM & đối tác.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.|Combo bao gồm 1 Lightstick NTPMM (sẽ được giao hàng tới địa chỉ bạn đăng ký)', 'SK09'),
('LV39', 'Combo 10 Regular Ticket (-15%)', 641750, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Vé không áp dụng ưu đãi/giảm giá từ NTPMM & đối tác.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.', 'SK09'),
('LV40', 'Regular Ticket', 755000, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.', 'SK10'),
('LV41', 'Combo 1 Regular Ticket + 1 Lightstick NTPMM (-2%)', 1081920, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Vé không áp dụng ưu đãi/giảm giá từ NTPMM & đối tác.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.|Combo bao gồm 1 Lightstick NTPMM (sẽ được giao hàng tới địa chỉ bạn đăng ký)', 'SK10'),
('LV42', 'Combo 10 Regular Ticket (-15%)', 641750, 'Vé hạng đứng Miracle Zone, không phân chia khu vực.|Check-in sớm sẽ được ưu tiên vị trí đẹp.|Vé không áp dụng ưu đãi/giảm giá từ NTPMM & đối tác.|Quà tặng: Sticker Pack, Vé giấy, Phong bì lưu niệm.|Hỗ trợ LED Livecam theo dõi sự kiện.', 'SK10'),
('LV43', 'Early Bird (EB)', 400000, 'Coupon đã bao gồm 1 đồ uống', 'SK11'),
('LV44', 'General Admission (GA)', 500000, 'Coupon đã bao gồm 1 đồ uống', 'SK11'),
('LV45', 'EARLY BOO (Checkin before 10PM)', 450000, NULL, 'SK12'),
('LV46', 'General Admission (GA)', 650000, NULL, 'SK12'),
('LV53', 'Mộng Mơ 1', 2500000, 'Khu vực Mộng Mơ 1 (Bên trái).|Vị trí ghế ngồi gần sân khấu nhất.|Tầm nhìn cận cảnh, rõ ràng.', 'SK14'),
('LV54', 'Mộng Mơ 2', 2500000, 'Khu vực Mộng Mơ 2 (Bên phải).|Vị trí ghế ngồi gần sân khấu nhất.|Tầm nhìn cận cảnh, rõ ràng.', 'SK14'),
('LV55', 'Ký Ức 1', 2100000, 'Khu vực Ký Ức 1 (Bên trái).|Nằm ở vị trí trung tâm, ngay sau khu Mộng Mơ.|Tầm nhìn thẳng, rõ nét.', 'SK14'),
('LV56', 'Ký Ức 2', 2100000, 'Khu vực Ký Ức 2 (Bên phải).|Nằm ở vị trí trung tâm, ngay sau khu Mộng Mơ.|Tầm nhìn thẳng, rõ nét.', 'SK14'),
('LV57', 'Thanh Xuân 1', 1700000, 'Khu vực Thanh Xuân 1 (Bên trái).|Nằm ở phía sau khu Ký Ức.|Vị trí trung tâm với tầm nhìn bao quát.', 'SK14'),
('LV58', 'Thanh Xuân 2', 1700000, 'Khu vực Thanh Xuân 2 (Bên phải).|Nằm ở phía sau khu Ký Ức.|Vị trí trung tâm với tầm nhìn bao quát.', 'SK14'),
('LV59', 'PREMIER LOUNGE', 10000000, 'KHU VỰC DÀNH CHO NGƯỜI THAM DỰ TỪ ĐỦ 6 TUỔI|01 Vé vào cổng khu PREMIER LOUNGE (ngồi)|PREMIUM SERVICES (xe Buggy đưa đón, FnB 5*)|01 Dây đeo thẻ Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Thẻ đeo Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Túi tote Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Em Xinh \"Say Hi\" Photocard - random (nhận tại sự kiện)|01 Quà tặng đặc biệt - Tham dự Group Photo (theo sự sắp xếp của BTC)|Cơ hội tham dự SOUNDCHECK (theo sự sắp xếp của BTC) Random 250 khách hàng', 'SK15'),
('LV60', 'SVIP A', 4000000, 'KHU VỰC DÀNH CHO NGƯỜI THAM DỰ TỪ ĐỦ 6 TUỔI|01 Vé vào cổng khu SVIP (ngồi)|01 Vòng tay (nhận tại sự kiện)|01 Quà tặng đặc biệt - Khăn Bandana (nhận tại sự kiện)|01 Dây đeo thẻ Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Thẻ đeo Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Túi tote Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Em Xinh \"Say Hi\" Photocard - random (nhận tại sự kiện)|Cơ hội tham dự SOUNDCHECK (theo sự sắp xếp của BTC) Random 150 khách hàng', 'SK15'),
('LV61', 'SVIP B', 4000000, 'KHU VỰC DÀNH CHO NGƯỜI THAM DỰ TỪ ĐỦ 6 TUỔI|01 Vé vào cổng khu SVIP (ngồi)|01 Vòng tay (nhận tại sự kiện)|01 Quà tặng đặc biệt - Khăn Bandana (nhận tại sự kiện)|01 Dây đeo thẻ Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Thẻ đeo Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Túi tote Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Em Xinh \"Say Hi\" Photocard - random (nhận tại sự kiện)|Cơ hội tham dự SOUNDCHECK (theo sự sắp xếp của BTC) Random 150 khách hàng', 'SK15'),
('LV62', 'VIP A', 3000000, 'KHU VỰC DÀNH CHO NGƯỜI THAM DỰ TỪ ĐỦ 6 TUỔI|01 Vé vào cổng khu VIP (ngồi)|01 Vòng tay (nhận tại sự kiện)|01 Quà tặng đặc biệt - Khăn Bandana (nhận tại sự kiện)|01 Dây đeo thẻ Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Thẻ đeo Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Túi tote Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Em Xinh \"Say Hi\" Photocard - random (nhận tại sự kiện)|Cơ hội tham dự SOUNDCHECK (theo sự sắp xếp của BTC) Random 150 khách hàng', 'SK15'),
('LV63', 'VIP B', 3000000, 'KHU VỰC DÀNH CHO NGƯỜI THAM DỰ TỪ ĐỦ 6 TUỔI|01 Vé vào cổng khu VIP (ngồi)|01 Vòng tay (nhận tại sự kiện)|01 Quà tặng đặc biệt - Khăn Bandana (nhận tại sự kiện)|01 Dây đeo thẻ Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Thẻ đeo Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Túi tote Em Xinh \"Say Hi\" Concert (nhận tại sự kiện)|01 Em Xinh \"Say Hi\" Photocard - random (nhận tại sự kiện)|Cơ hội tham dự SOUNDCHECK (theo sự sắp xếp của BTC) Random 150 khách hàng', 'SK15'),
('LV64', 'NHÁ NHEM', 400000, 'Khu vực C .|Nằm ở tầng lửng.|Vị trí xa sân khấu hơn, giá vé tiết kiệm.', 'SK16'),
('LV65', 'CHẬP CHOẠNG', 500000, 'Khu vực D .|Nằm ở tầng lửng.|Tầm nhìn bao quát toàn cảnh.', 'SK16'),
('LV66', 'CHẠNG VẠNG', 650000, 'Khu vực A (màu cam).|Nằm ở tầng lửng, bao gồm các hàng C+, D+, E+.|Tầm nhìn trung tâm, bao quát.', 'SK16'),
('LV67', 'CHIỀU TÀ', 900000, 'Khu vực VIP .|Vị trí trung tâm bên phải.|Vị trí gần sân khấu nhất.', 'SK16'),
('LV68', 'HOÀNG HÔN', 1100000, 'Khu vực VIP .|Vị trí trung tâm bên trái.|Vị trí gần sân khấu nhất.', 'SK16'),
('LV69', 'Early Access (Check-in before 10PM)', 650000, NULL, 'SK17'),
('LV70', 'GA (General Admission)', 850000, NULL, 'SK17'),
('LV76', 'NHÁ NHEM', 570000, 'Khu vực C.|Nằm ở tầng lửng.|Vị trí xa sân khấu hơn, giá vé tiết kiệm.', 'SK19'),
('LV77', 'CHẬP CHOẠNG', 800000, 'Khu vực B.|Nằm ở tầng lửng.|Tầm nhìn bao quát toàn cảnh.', 'SK19'),
('LV78', 'CHANG VẠNG', 1120000, 'Khu vực A (màu cam).|Nằm ở tầng lửng, bao gồm các hàng C+, D+, E+.|Tầm nhìn trung tâm, bao quát.', 'SK19'),
('LV79', 'VIP - CHIỀU TÀ', 1420000, 'Khu vực VIP.|Vị trí trung tâm bên phải.|Vị trí gần sân khấu nhất.', 'SK19'),
('LV80', 'VVVIP - HOÀNG HÔN', 1700000, 'Khu vực VIP.|Vị trí trung tâm bên trái.|Vị trí gần sân khấu nhất.', 'SK19'),
('LV81', 'NHÁ NHEM', 570000, 'Khu vực C.|Nằm ở tầng lửng.|Vị trí xa sân khấu hơn, giá vé tiết kiệm.', 'SK20'),
('LV82', 'CHẬP CHOẠNG', 800000, 'Khu vực B.|Nằm ở tầng lửng.|Tầm nhìn bao quát toàn cảnh.', 'SK20'),
('LV83', 'CHANG VẠNG', 1120000, 'Khu vực A.|Nằm ở tầng lửng.|Tầm nhìn trung tâm, bao quát.', 'SK20'),
('LV84', 'VIP - CHIỀU TÀ', 1420000, 'Khu vực VIP.|Vị trí trung tâm bên phải.|Vị trí gần sân khấu nhất.', 'SK20'),
('LV85', 'VVVIP - HOÀNG HÔN', 1700000, 'Khu vực WIP .|Vị trí trung tâm bên trái.|Vị trí gần sân khấu nhất.', 'SK20'),
('LV86', 'HOÀNG HÔN', 1450000, 'Khu vực WIP.|Vị trí trung tâm bên trái.|Vị trí gần sân khấu nhất.', 'SK21'),
('LV87', 'CHIỀU TÀ', 1230000, 'Khu vực VIP.|Vị trí trung tâm bên phải.|Vị trí gần sân khấu nhất.', 'SK21'),
('LV88', 'CHẠNG VẠNG', 1050000, 'Khu vực A (màu cam).|Nằm ở tầng lửng, bao gồm các hàng C+, D+, E+.|Tầm nhìn trung tâm, bao quát.', 'SK21'),
('LV89', 'CHẬP CHOẠNG', 760000, 'Khu vực B.|Nằm ở tầng lửng.|Tầm nhìn bao quát toàn cảnh.', 'SK21'),
('LV90', 'NHÁ NHEM', 560000, 'Khu vực C.|Nằm ở tầng lửng.|Vị trí xa sân khấu hơn, giá vé tiết kiệm.', 'SK21'),
('LV91', 'CAT 1 - R', 4000000, 'Khu vực ghế ngồi CAT 1 (Bên phải).|Nằm ở vị trí trung tâm, ngay sau khu vực kỹ thuật (FOH).|Cung cấp tầm nhìn thẳng, không bị che khuất.', 'SK22'),
('LV92', 'CAT 2 - L', 3500000, 'Khu vực ghế ngồi CAT 2 (Bên trái).|Vị trí sát sân khấu nhất, nằm ở phía bên trái.|Mang lại trải nghiệm cận cảnh từ góc chéo.', 'SK22'),
('LV93', 'CAT 2 - R', 3500000, 'Khu vực ghế ngồi CAT 2 (Bên phải).|Vị trí sát sân khấu nhất, nằm ở phía bên phải.|Mang lại trải nghiệm cận cảnh từ góc chéo.', 'SK22'),
('LV94', 'CAT 3 - L', 2500000, 'Khu vực ghế ngồi CAT 3 (Bên trái).|Nằm ở phía sau khu CAT 2, bên trái sân khấu.|Tầm nhìn chéo, rõ ràng.', 'SK22'),
('LV95', 'CAT 3 - R', 2500000, 'Khu vực ghế ngồi CAT 3 (Bên phải).|Nằm ở phía sau khu CAT 2, bên phải sân khấu.|Tầm nhìn chéo, rõ ràng.', 'SK22'),
('LV96', 'CAT 4 - L', 2000000, 'Khu vực ghế ngồi CAT 4 (Bên trái).|Nằm ở phía ngoài cùng, sau khu CAT 3 (bên trái).|Tầm nhìn bao quát toàn cảnh, giá vé tiết kiệm.', 'SK22'),
('LV97', 'CAT 4 - R', 2000000, 'Khu vực ghế ngồi CAT 4 (Bên phải).|Nằm ở phía ngoài cùng, sau khu CAT 3 (bên phải).|Tầm nhìn bao quát toàn cảnh, giá vé tiết kiệm.', 'SK22'),
('LV98', 'VVIP', 10000000, 'Khu vực VIP ZONE.|Vị trí ghế ngồi trung tâm, ngay sau khu SKYBOX.|Quyền lợi bao gồm Lightstick.|Đồ uống không giới hạn.|Ẩm thực 5 sao.', 'SK23'),
('LV99', 'VIP 1.A1', 4000000, 'Khu vực ghế ngồi SEATING (VIP 1.A1).|Vị trí cánh trái, khu vực phía trong.|Quyền lợi bao gồm Lightstick.', 'SK23');

-- --------------------------------------------------------

--
-- Table structure for table `nhanviensoatve`
--

CREATE TABLE `nhanviensoatve` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhanviensoatve`
--

INSERT INTO `nhanviensoatve` (`email`, `user_name`, `gender`, `tel`, `password`) VALUES
('nvsv@ctu.edu.vn', 'nhanviensoatve', 'male', '0123456789', '$2y$10$OVVW5RuaGy2uN6z8uAmRken7l8bNp9/yIVBQXzTfLLT43CS6mIT1m');

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
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nhatochuc`
--

INSERT INTO `nhatochuc` (`email`, `user_name`, `tel`, `address`, `taikhoannganhang`, `password`) VALUES
('ntc@ctu.edu.vn', 'nhatochuc', '0123456789', 'Đại học Cần Thơ', '98765432101234', '$2y$10$EcjrjsKj5G68OTFCDIsgGuaaC7v0pjQoQPPKAOXbLkdTE2/yYCqC.');

-- --------------------------------------------------------

--
-- Table structure for table `quantrivien`
--

CREATE TABLE `quantrivien` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `quantrivien`
--

INSERT INTO `quantrivien` (`email`, `user_name`, `tel`, `password`) VALUES
('qtv@ctu.edu.vn', 'quantrivien', '0123456789', '$2y$10$YxuKkp2yYUtPW5BZVtf8PuHZWyOeDRzFNOX0TdITLeL.YtC616lSO');

-- --------------------------------------------------------

--
-- Table structure for table `sukien`
--

CREATE TABLE `sukien` (
  `MaSK` char(5) NOT NULL,
  `TenSK` varchar(100) NOT NULL,
  `Tgian` datetime DEFAULT NULL,
  `img_sukien` varchar(100) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `img_sodo` varchar(255) DEFAULT NULL,
  `MaLSK` char(5) DEFAULT NULL,
  `MaDD` char(5) DEFAULT NULL,
  `luot_truycap` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sukien`
--

INSERT INTO `sukien` (`MaSK`, `TenSK`, `Tgian`, `img_sukien`, `mota`, `img_sodo`, `MaLSK`, `MaDD`, `luot_truycap`) VALUES
('SK02', '[CAT&MOUSE] CA SĨ ĐẠT G - ĐÊM LẶNG TÔ MÀU XÚC CẢM', '2025-10-18 21:00:00', 'https://salt.tkbcdn.com/ts/ds/37/25/63/9a82b897b7f175b5888016f161d0fa1e.png', 'Với không gian được đầu tư hệ thống ánh sáng - âm thanh đẳng cấp quốc tế với sức chứa lên đến 350 người, cùng quầy bar phục vụ cocktail pha chế độc đáo bởi bartender chuyên nghiệp.\n\n20g00 - 31/10/2025 (Thứ 6), một đêm nhạc sâu lắng và chân thành tại Cat&Mouse đã hé lộ. Sự góp mặt của Đạt G với chất giọng trầm ấm, đặc trưng, cùng phong cách âm nhạc giàu cảm xúc, sẽ giúp bạn tìm thấy chính mình trong những khoảnh khắc cô đơn nhưng cũng đầy sự an ủi.\n\nQuý khách tham dự đêm diễn sẽ được tặng 1 phần đồ ăn nhẹ.', 'https://salt.tkbcdn.com/ts/ds/6c/cf/24/dc9d3e30efe6ec8823fc647d26958e39.png', 'LSK01', 'HCM', 72),
('SK03', 'G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI, PRESENTED BY VPBANK', '2025-11-08 20:00:00', 'https://salt.tkbcdn.com/ts/ds/2b/62/6d/b72040ac36d256c6c51e4c01797cf879.png', 'Lần đầu tiên, \"Ông hoàng K-pop\" G-DRAGON chính thức tổ chức concert tại Việt Nam, mở màn cho chuỗi World Tour do 8Wonder mang tới. G-DRAGON 2025 WORLD TOUR [Übermensch] hứa hẹn sẽ bùng nổ với sân khấu kì công, âm thanh - ánh sáng mãn nhãn và những khoảnh khắc chạm đến trái tim người hâm mộ. G-DRAGON sẽ mang đến những bản hit từng gắn liền với thanh xuân của hàng triệu người hâm mộ. Một đêm nhạc không chỉ để thưởng thức, mà còn để lưu giữ trong ký ức.', 'https://salt.tkbcdn.com/ts/ds/16/36/dd/6e30fc512e2e37417917e4d8fb718262.png', 'LSK03', 'HY', 112),
('SK05', 'Waterbomb Ho Chi Minh City 2025', '2025-11-15 14:00:00', 'https://salt.tkbcdn.com/ts/ds/f3/80/f0/32ee189d7a435daf92b6a138d925381c.png', 'Vào hai ngày 15–16/11/2025, khu đô thị Vạn Phúc City (TP.HCM) sẽ trở thành tâm điểm của giới trẻ khi lễ hội âm nhạc WATERBOMB lần đầu tiên “cập bến” Việt Nam. Với mô hình kết hợp âm nhạc – trình diễn – hiệu ứng phun nước đặc trưng từ Hàn Quốc, sự kiện hứa hẹn mang đến trải nghiệm “ướt sũng” đầy phấn khích cùng dàn nghệ sĩ đình đám như Hwasa, Jay Park, B.I, Sandara Park, Rain, EXID, Shownu x Hyungwon (MONSTA X), cùng các ngôi sao Vpop như HIEUTHUHAI, tlinh, SOOBIN, Tóc Tiên, Chi Pu, MIN và nhiều cái tên hot khác.\n\nKhông chỉ là sân khấu âm nhạc, WATERBOMB còn là đại tiệc cảm xúc với khu vui chơi phun nước liên hoàn, khu check-in phong cách lễ hội, và các hạng vé đa dạng từ GA đến Splash Wave – nơi bạn có thể “quẩy” sát sân khấu cùng thần tượng. Đây là cơ hội hiếm có để fan Kpop và khán giả Việt cùng hòa mình vào không gian lễ hội quốc tế ngay giữa lòng Sài Gòn.\n', NULL, 'LSK02', 'HCM', 110),
('SK06', 'GS25 MUSIC FESTIVAL 2025', '2025-11-22 10:00:00', 'https://salt.tkbcdn.com/ts/ds/6e/2f/fa/32d07d9e0b2bd6ff7de8dfe2995619d5.jpg', 'GS25 MUSIC FESTIVAL 2025 sẽ diễn ra vào ngày 22/11 tại Công viên Sáng Tạo, Thủ Thiêm, TP.HCM, từ 10:00 đến 23:00. Đây là lễ hội âm nhạc ngoài trời hoành tráng do GS25 tổ chức, quy tụ nhiều nghệ sĩ nổi tiếng. Khách hàng có thể đổi vé tham dự bằng cách tích điểm khi mua sắm tại GS25 và CAFE25 từ 01/10 đến 15/11. Vé không cho phép hoàn trả và cần đeo vòng tay khi tham gia. Sự kiện hứa hẹn mang đến trải nghiệm âm nhạc sôi động và không gian lễ hội trẻ trung dành cho giới trẻ.', 'https://salt.tkbcdn.com/ts/ds/42/8c/44/5a155daa8398d44556cf655011a7b50b.png', 'LSK02', 'HCM', 105),
('SK07', '2025 K-POP SUPER CONCERT IN HO CHI MINH', '2025-11-22 18:00:00', 'https://salt.tkbcdn.com/ts/ds/bb/96/bd/28394979b702cd9dc934bef42824e6c1.png', 'Vào ngày 22/11/2025, sự kiện K-POP SUPER CONCERT sẽ chính thức diễn ra tại Vạn Phúc City, TP.HCM, do Golden Space Entertainment tổ chức. Đây là một lễ hội âm nhạc hoành tráng quy tụ dàn nghệ sĩ K-pop và Việt Nam, với sự góp mặt của các tên tuổi như XIUMIN, CHEN, DUCPHUC, ARrC, và nhóm nữ Gen Z đa quốc tịch We;Na – lần đầu tiên ra mắt tại Việt Nam.', 'https://salt.tkbcdn.com/ts/ds/90/2c/7a/a20cdd1dd5199797705582c7651c72c1.jpg', 'LSK03', 'HCM', 112),
('SK08', 'SOOBIN LIVE CONCERT: ALL-ROUNDER THE FINAL', '2025-11-29 20:00:00', 'https://salt.tkbcdn.com/ts/ds/9c/9e/c1/2edd538cb4df21a0d13f95588cb44dc4.png', 'Các all-rounders chờ đã lâu rồi phải không? Một lần nữa hãy cùng đắm chìm trong trải nghiệm sân khấu \'all around you\', để SOOBIN cùng âm nhạc luôn chuyển động bên bạn mọi lúc - mọi nơi nhé!', 'https://salt.tkbcdn.com/ts/ds/bd/22/b1/3a539796934ac26795c6b1c2aba9435f.jpg', 'LSK03', 'HCM', 105),
('SK09', 'Những Thành Phố Mơ Màng Year End 2025', '2025-12-07 16:00:00', 'https://salt.tkbcdn.com/ts/ds/e8/95/f3/2dcfee200f26f1ec0661885b2c816fa6.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến:  07/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại TP.HCM (sẽ cập nhật sau).', NULL, 'LSK03', 'HCM', 86),
('SK10', 'Những Thành Phố Mơ Màng Year End 2025', '2025-12-21 16:00:00', 'https://salt.tkbcdn.com/ts/ds/18/8f/59/2d0abe9be901a894cd3b0bf29fd01863.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến: 21/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại Hà Nội (sẽ cập nhật sau).', NULL, 'LSK03', 'HN', 20),
('SK11', '1900 Future Hits #75: Thanh Duy', '2025-10-24 21:00:00', 'https://salt.tkbcdn.com/ts/ds/df/d8/ec/9f46a4e587b39ccf5886e6ae6f1b27d0.png', 'Nhắc đến Thanh Duy (Á quân Vietnam Idol 2008) là nhắc đến một nghệ sĩ nhiều màu sắc, một chú \"tắc kè hoa\" của showbiz. Thanh Duy kể những câu chuyện độc đáo, chạm đến tim người nghe bằng âm nhạc. Mỗi bài hát là một mảnh ghép cá tính, không lẫn vào đâu được.\n \nVới style không ngại khác biệt, thời trang \"chơi trội\" và tinh thần sống thật, sống hết mình, Thanh Duy luôn là nguồn năng lượng tích cực, truyền cảm hứng sống vui, sống thật cho giới trẻ. \n \nNgày 24/10 tới đây, 1900 sẽ chào đón Thanh Duy đến với đêm nhạc Future Hits #75. Các bản hit sẽ được vang lên trên sân khấu 1900, hứa hẹn mang đến những moment cực peak.\n \nSave the date!', NULL, 'LSK01', 'HN', 45),
('SK12', 'RAVERSE #3: Clowns Du Chaos w/ MIKE WILLIAMS - Oct 31 (HALLOWEEN PARTY)', '2025-10-31 20:00:00', 'https://salt.tkbcdn.com/ts/ds/e0/71/b2/b213ce9427cfc01487c73df2ba849787.jpg', 'Sau những đêm cháy hết mình cùng DubVision và Maddix, RAVERSE đã chính thức quay trở lại và lần này, Raverse sẽ biến APLUS HANOI thành một RẠP XIẾC MA MỊ đúng nghĩa. Cùng chào đón Headliner – MIKE WILLIAMS, DJ/Producer top 72 DJ Mag - Người đứng sau hàng loạt hit Future Bounce tỉ lượt nghe, từng khuấy đảo những sân khấu lớn nhất thế giới Tomorrowland, Ultra Music Festival,... nay sẽ đổ bộ Raverse #3 mang theo năng lượng bùng nổ chưa từng có! ⚡Cánh cửa rạp xiếc sắp mở… Bạn đã sẵn sàng hóa thân, quẩy hết mình và bước vào thế giới hỗn loạn của RAVERSE chưa?', NULL, 'LSK02', 'HN', 41),
('SK13', 'Jazz concert: Immersed', '2025-11-15 19:00:00', 'https://salt.tkbcdn.com/ts/ds/43/54/98/924b6491983baf58b00222c9b5b7295b.jpg', 'JAZZ CONCERT – IMMERSED: SỰ KẾT HỢP ĐỈNH CAO TỪ NHỮNG TÊN TUỔI HÀNG ĐẦU\n\n🌿Được khởi xướng bởi GG Corporation, Living Heritage ra đời với sứ mệnh là quy tụ và tôn vinh những giá trị sống đích thực của cộng đồng người Việt trên khắp thế giới – từ trải nghiệm, tri thức đến nhân sinh quan sâu sắc của các thế hệ đi trước để trao truyền lại cho thế hệ tương lai.\n\n🌻Living Heritage là một hệ sinh thái nội dung gồm: trang web chính thức lưu trữ các cuộc trò chuyện ý nghĩa, sách điện tử (được phát phát hành trên Amazon), cùng chuỗi sự kiện nghệ thuật – giáo dục tầm vóc quốc tế thường niên. 🎼Khởi đầu hành trình này là Jazz Concert IMMERSED – đêm nhạc quốc tế với sự tham gia đặc biệt của “Hiệp sĩ” Jazz - Sir Niels Lan Doky, huyền thoại piano Jazz được biết đến như một trong những nghệ sĩ tiên phong của dòng Jazz châu Âu hiện đại. Báo chí Nhật Bản gọi ông là “nghệ sĩ xuất sắc nhất thế hệ”, còn tờ báo El Diario (Tây Ban Nha) gọi ông là “một trong những nghệ sĩ piano quan trọng nhất nửa thế kỷ qua”. Ông sẽ trình diễn cùng bộ đôi nghệ sĩ quốc tế Felix Pastorius (bass) và Jonas Johansen (trống), dưới sự dàn dựng của Tổng đạo diễn Phạm Hoàng Nam, Giám đốc Âm nhạc Quốc Trung, Kĩ sư âm thanh Doãn Chí Nghĩa, Nhà thiết kế Phục trang Tom Trandt, Biên đạo múa Ngọc Anh và Nghệ sĩ nghệ thuật thị giác Tùng Monkey.\n\n⭐️Điểm nhấn đặc biệt là những màn kết hợp giữa Sir Niels Lan Doky và các nghệ sĩ hàng đầu Việt Nam như NSND Thanh Lam, ca sĩ Hà Trần, nghệ sĩ saxophone Quyền Thiện Đắc và một số nghệ sĩ khác – những tên tuổi có dấu ấn rõ nét trong việc vừa gìn giữ nét đẹp bản sắc của âm nhạc Việt, vừa tìm tòi, sáng tạo và đổi mới để hội nhập vào dòng chảy âm nhạc thế giới. Sự hội ngộ này tạo nên một không gian âm nhạc đa chiều, nơi tinh thần Jazz quốc tế gặp gỡ hơi thở dân gian đương đại Việt Nam trong một cuộc đối thoại âm nhạc đỉnh cao, hoà quyện và đầy ngẫu hứng.\n\nChi tiết sự kiện:\n\nChương trình chính: Khách mời đặc biêt Sir Niels Lan Doky, Knight of Jazz cùng \nKhách mời: NSND Thanh Lam, Ca sỹ Hà Trần, Nghệ sỹ Quyền Thiện Đắc.', NULL, 'LSK03', 'HCM', 40),
('SK14', '[Dốc Mộng Mơ] Em Đồng Ý - Đức Phúc - Noo Phước Thịnh', '2025-11-15 19:30:00', 'https://salt.tkbcdn.com/ts/ds/6d/9b/da/438a1b16cba1c64f5befce0fdd32682a.jpg', 'Đêm nhạc đánh dấu chặng đường trưởng thành của Đức Phúc với những bản hit được phối mới đầy cảm xúc, sân khấu dàn dựng công phu cùng sự góp mặt của ca sĩ Noo Phước Thịnh.\n\nMột hành trình âm nhạc lãng mạn và bất ngờ, chắc chắn là khoảnh khắc không thể bỏ lỡ!\n\nChi tiết sự kiện \n\n	Chương trình chính: \n \nTrình diễn những ca khúc nổi bật nhất trong sự nghiệp ca hát của Đức Phúc. \n\nCác tiết mục dàn dựng công phu, phối khí mới mẻ.\n\nNhững phần trình diễn đặc biệt lần đầu ra mắt tại liveshow.\n\n	Khách mời: Ca sĩ Noo Phước Thịnh \n\n	Trải nghiệm đặc biệt: Không gian check-in mang concept riêng của “EM ĐỒNG Ý” cũng như khu trải nghiệm và những phần quà đặc biệt dành cho fan.', NULL, 'LSK01', 'HN', 81),
('SK15', 'EM XINH \"SAY HI\" CONCERT - ĐÊM 2', '2025-10-11 12:00:00', 'https://salt.tkbcdn.com/ts/ds/90/37/6e/cfa9510b1f648451290e0cf57b6fd548.jpg', 'Em Xinh “Say Hi” Concert – Đêm 2 sẽ diễn ra vào ngày 11/10/2025 tại sân vận động Mỹ Đình, Hà Nội, mang đến đại tiệc âm nhạc Gen Z với sân khấu ánh sáng 360 độ, loạt tiết mục viral như Run, Không đau nữa rồi, Vỗ tay. Lưu ý: Vé không hoàn trả, trẻ em dưới 7 tuổi không được tham gia, người dưới 16 tuổi cần có người lớn đi kèm.', 'https://salt.tkbcdn.com/ts/ds/da/bd/6b/6fa8723674852889664879bd62ead269.png', 'LSK03', 'HN', 79),
('SK16', 'LULULOLA SHOW VICKY NHUNG & CHU THÚY QUỲNH | NGÀY MƯA ẤY', '2025-09-20 17:30:00', 'https://salt.tkbcdn.com/ts/ds/ee/86/df/261a5fd2fa0890c25f4c737103bbbe0c.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'https://salt.tkbcdn.com/Upload/agenda/2022/11/17/C1D231.jpg', 'LSK01', 'DL', 55),
('SK17', 'ELAN & APLUS present: STEPHAN BODZIN', '2025-09-21 20:00:00', 'https://salt.tkbcdn.com/ts/ds/e3/06/ed/faff7ef36d95334510e51f7d337357d4.jpg', 'Không chỉ đơn thuần là một set nhạc, sự kiện kỷ niệm 2 năm của ELAN sẽ mang đến một “siêu phẩm” của âm thanh, năng lượng và cảm xúc. Hãy sẵn sàng đắm mình trong màn trình diễn live độc nhất vô nhị từ “nhạc trưởng” huyền thoại – Stephan Bodzin! Được mệnh danh là một trong những live performer xuất sắc nhất lịch sử nhạc điện tử, Stephan Bodzin luôn thiết lập những tiêu chuẩn mới cho nghệ thuật trình diễn và để lại dấu ấn sâu đậm trên các sân khấu, lễ hội âm nhạc điện tử lớn nhất thế giới. Suốt nhiều năm, ông vững vàng ở đỉnh cao của giới Techno, sánh vai cùng những huyền thoại như Solomun, Tale of Us, Carl Cox... Biểu diễn cùng Stephan Bodzin lần này còn có những tên tuổi đầy thực lực của làng Techno Việt: THUC, Mya, Heepsy và Tini Space. Từ 9 giờ tối, Chủ Nhật ngày 21 tháng 9, 2025 tại APLUS Hanoi, 78 Yên Phụ, Hà Nội.', NULL, 'LSK02', 'HN', 36),
('SK19', 'LULULOLA SHOW TĂNG PHÚC | MONG MANH NỖI ĐAU', '2025-12-13 17:30:00', 'https://salt.tkbcdn.com/ts/ds/0f/f1/68/b57f2a3ecd1a9e516e8d1587c34fcc6e.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'https://salt.tkbcdn.com/ts/ds/38/b0/e6/96448b0b78a4d279a316d8ddfe8dbd88.jpg', 'LSK01', 'DL', 62),
('SK20', 'LULULOLA SHOW PHAN MẠNH QUỲNH | TỪ BÀN TAY NÀY', '2025-12-06 17:30:00', 'https://salt.tkbcdn.com/ts/ds/57/04/b1/39315e2c790f67ecc938701754816d15.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'https://salt.tkbcdn.com/ts/ds/38/b0/e6/0ab5eb7000927cb78117c121d0faea56.jpg', 'LSK01', 'DL', 91),
('SK21', 'LULULOLA SHOW VĂN MAI HƯƠNG | ƯỚT LÒNG', '2025-09-13 17:30:00', 'https://salt.tkbcdn.com/ts/ds/fb/43/5c/52a43d006d2ec64b1dac74db8a62f72f.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'https://salt.tkbcdn.com/ts/ds/38/b0/e6/1c9f48dbdb4ce7e1353a72c3a2d028df.jpg', 'LSK01', 'DL', 65),
('SK22', 'DAY6 10th Anniversary Tour <The DECADE> in HO CHI MINH CITY', '2025-10-18 18:30:00', 'https://salt.tkbcdn.com/ts/ds/c6/e1/c2/d3d41b377ea3d9a3cd18177d656516d7.jpg', 'Ngày 18/10/2025, ban nhạc Hàn Quốc DAY6 đã tổ chức concert đầu tiên tại Việt Nam – DAY6 10th Anniversary Tour <The DECADE> tại SECC Hall B2, Quận 7, TP.HCM, đánh dấu 10 năm hoạt động âm nhạc. Đây là lần đầu nhóm biểu diễn solo tại Việt Nam, thu hút đông đảo người hâm mộ My Days. Setlist trải dài từ các bản hit như Congratulations, Letting Go, I Loved You, Zombie đến những ca khúc mới trong album kỷ niệm như Dream Bus, Inside Out, Disco Day và Our Season.', 'https://salt.tkbcdn.com/ts/ds/4d/92/65/4756312238e1ae1b8129074a53454f7b.jpg', 'LSK03', 'HCM', 113),
('SK23', '8Wonder Winter 2025 - SYMPHONY OF STARS - HÒA KHÚC CÁC VÌ SAO', '2025-12-06 18:30:00', 'https://salt.tkbcdn.com/ts/ds/c1/48/74/8c3630d25edf901b843473af6be4dd6a.jpg', '8WONDER WINTER 2025 - SYMPHONY OF STARS - HÒA KHÚC CÁC VÌ SAO\r\n\r\nGiữa mùa đông Hà Nội, 8Wonder thắp sáng bầu trời bằng “Symphony of Stars” – bản hoà khúc nơi những giọng ca đẳng cấp thế giới cất lên, khẳng định vị thế thương hiệu âm nhạc quốc tế tại Việt Nam. Không chỉ là concert, đây là một hành trình lễ hội sống: từ âm nhạc bùng nổ và nghệ thuật giao thoa, đến ẩm thực bốn phương, không gian văn hoá, thể thao, công nghệ và những kết nối cộng đồng. \r\n\r\nTiên phong theo đuổi xu hướng green festival, 8Wonder Winter 2025 mang đến một mùa hội trọn vẹn – nơi ánh sáng sân khấu, nhịp tim khán giả và hơi thở xanh của thời đại hòa làm một. Để mỗi khoảnh khắc ở đây trở thành một vì sao, cùng viết nên dải ngân hà bất tận của yêu thương, hy vọng và sự gắn kết.', NULL, 'LSK03', 'HN', 155),
('SK24', 'Y-CONCERT BY YEAH1 - Mình đoàn viên thôi', '2025-12-20 14:00:00', 'https://salt.tkbcdn.com/ts/ds/8e/89/4c/407e32bba0e4d1651175680a2452954e.jpg', 'V Concert “Rạng Rỡ Việt Nam” hứa hẹn sẽ chạm tới đỉnh cao của âm nhạc và cảm xúc, đánh dấu lần đầu tiên một sự kiện nghệ thuật đỉnh cao được tổ chức tại Trung tâm Triển lãm Việt Nam – công trình hiện đại bậc nhất cả nước, nằm trong top 10 khu triển lãm hội chợ lớn nhất thế giới. Vào ngày 9.8.2025, Đài Truyền hình Việt Nam sẽ mang đến một lễ hội âm nhạc rực rỡ và bùng nổ với sự góp mặt của dàn nghệ sĩ “trong mơ” gồm Hà Anh Tuấn, Hồ Ngọc Hà, Noo Phước Thịnh, Đen, Trúc Nhân, Tóc Tiên, Hoàng Thuỳ Linh, Hoà Minzy, Phương Mỹ Chi, RHYDER, Quang Hùng MasterD và 2pillz. Đây sẽ là một đại tiệc kết hợp giữa âm nhạc, ánh sáng và công nghệ, mang đến không gian cảm xúc thăng hoa cho 25.000 khán giả, đồng thời trở thành cột mốc rạng rỡ trong hành trình tôn vinh âm nhạc và văn hóa Việt. Concert dành cho người trên 14 tuổi; riêng khán giả từ 14 đến dưới 18 tuổi cần có người giám hộ trên 21 tuổi đi cùng và chịu trách nhiệm trong suốt chương trình. Đừng bỏ lỡ cơ hội trở thành một phần của sự kiện âm nhạc đáng mong đợi nhất năm 2025!', 'https://salt.tkbcdn.com/ts/ds/9e/33/3f/caa1f7e1cf3b04b1648c8973c60abb7e.png', 'LSK03', 'HY', 90),
('SK25', 'V CONCERT \"RẠNG RỠ VIỆT NAM\" - CHẠM VÀO ĐỈNH CAO CỦA ÂM NHẠC VÀ CẢM XÚC', '2025-12-09 17:00:00', 'https://salt.tkbcdn.com/ts/ds/4d/5d/93/c38fa1bc1f9ca5f95b882b12d45883bc.jpg', 'V Concert “Rạng Rỡ Việt Nam” hứa hẹn sẽ chạm đến đỉnh cao của âm nhạc và cảm xúc, đánh dấu lần đầu tiên một sự kiện nghệ thuật tầm cỡ được tổ chức tại Trung tâm Triển lãm Việt Nam – công trình triển lãm hiện đại bậc nhất cả nước, nằm trong top 10 khu triển lãm hội chợ lớn nhất thế giới. Vào ngày 9.12.2025, Đài Truyền hình Việt Nam sẽ mang đến một lễ hội âm nhạc rực rỡ, bùng nổ cảm xúc với sự góp mặt của dàn nghệ sĩ “trong mơ” lần đầu cùng hội tụ trên một sân khấu lớn: Hà Anh Tuấn, Hồ Ngọc Hà, Noo Phước Thịnh, Đen, Trúc Nhân, Tóc Tiên, Hoàng Thuỳ Linh, Hoà Minzy, Phương Mỹ Chi, RHYDER, Quang Hùng MasterD và 2pillz. Sự kiện hứa hẹn mang đến một đại tiệc kết hợp giữa âm nhạc – ánh sáng – công nghệ, tạo nên không gian cảm xúc thăng hoa cho 25.000 khán giả và trở thành cột mốc rạng rỡ trong hành trình tôn vinh âm nhạc cùng văn hóa Việt. Lưu ý, concert dành cho người trên 14 tuổi; khán giả từ 14 đến dưới 18 tuổi có thể tham gia nếu có người giám hộ trên 21 tuổi đi cùng và đồng hành trong suốt chương trình. Đừng bỏ lỡ cơ hội trở thành một phần của sự kiện âm nhạc đáng mong chờ nhất năm 2025!', NULL, 'LSK03', 'HN', 60),
('SK27', 'CINÉ FUTURE HITS #12: JUN PHẠM', '2025-06-08 21:00:00', 'https://salt.tkbcdn.com/ts/ds/67/7a/29/48a31568f2bdbce9104ad077f146b560.jpg', '     Tiếp nối hành trình tôn vinh và phát triển văn hoá, nghệ thuật Việt, Ciné Saigon chính thức mang Future Hits quay trở lại với số 12, cùng với đó là màn \"kỉ lục comeback\" đến từ anh chàng nghệ sĩ đa tài Jun Phạm!\r\n \r\n     Với sự trở lại cùng \"chiếc\" mini concert Day 2 đến từ anh tài gia tộc toàn năng, anh tài biến hoá X-Icon, nam diễn viên điện ảnh - truyền hình được yêu thích nhất, tác giả sách quốc gia 2024, số Future Hits #12 hứa hẹn sẽ tiếp tục được phủ kín bởi sự cuồng nhiệt và đầy yêu thương đến từ đại gia đình hâm mộ Jun Phạm! \r\n', NULL, 'LSK03', 'HCM', 41),
('SK28', 'ANH TRAI \"SAY HI\" 2025 CONCERT', '2025-12-27 12:00:00', 'https://salt.tkbcdn.com/ts/ds/b8/98/52/da316543950a9543d5b87c71b48838bf.png', 'ANH TRAI “SAY HI” 2025 CONCERT\r\n\r\nNgày 27.12.2025 tại Khu đô thị Vạn Phúc City, TP HCM ', 'https://salt.tkbcdn.com/ts/ds/56/47/a5/42d442d9f61e44b63514f211c0019c20.png', 'LSK03', 'HCM', 98),
('SK29', 'DOMIE HOMIE - 2025 Dương Domic Fan Meeting in DANANG', '2025-11-23 19:00:00', 'https://salt.tkbcdn.com/ts/ds/df/31/a4/51b62ea85fe1bc02d27862f6e391cca1.png', 'DOMIE HOMIE – 2025 Dương Domic Fan Meeting in DANANG\r\n\r\nDOMIE HOMIE – 2025 Dương Domic’s Fan Meeting in DANANG sẽ mang đến cho khán giả một không gian gần gũi và ấm áp, nơi âm nhạc và tình cảm gắn kết nghệ sĩ cùng người hâm mộ.', 'https://salt.tkbcdn.com/ts/ds/86/c0/36/043a5288b64fc560ce5a29710403ca7c.jpg', 'LSK01', 'DN', 30),
('SK30', 'THE GENTLEMEN - COUNTDOWN CONCERT 2026', '2025-12-31 19:30:00', 'https://salt.tkbcdn.com/ts/ds/27/b5/52/1b92d99147733d76b376b207dc45595f.jpg', 'THE GENTLEMEN - COUNTDOWN CONCERT 2026: ĐÊM NHẠC LỊCH LÃM ĐÓN CHÀO NĂM MỚI! \r\n\r\nCùng The Pearl Hoi An chào đón thời khắc giao mùa đáng nhớ nhất cuối năm 2025! Với kết hợp sự lịch lãm của những \"quý ông\" hát tình ca hứa hẹn sẽ tạo nên bầu không khí rực rỡ cảm xúc trong đêm Countdown 2026 tại THE GENTLEMEN – COUNTDOWN CONCERT 2026.\r\n\r\nChi tiết sự kiện:\r\n\r\n3 chàng trai – 3 hành trình âm nhạc khác nhau, nhưng đều có chung xuất phát điểm: bước ra từ những sân khấu truyền hình thực tế đình đám như The Voice, Vietnam Idol hay Sing My Song. Điều khiến khán giả nhớ về họ chính là chất giọng trữ tình, đậm đà cảm xúc – ba màu cảm xúc, nhưng cùng chung một ngôn ngữ đó là tình ca. Chính họ sẽ dẫn dắt khán giả bước sang năm mới bằng những giai điệu sâu lắng với trái tim chân thành dành cho tình yêu:\r\n\r\nLân Nhã – Giọng ca nồng nàn, đậm chất tự sự, mang đến những bản tình ca lãng mạn, mở đầu cho một năm mới tràn đầy cảm xúc.\r\nNguyễn Đình Tuấn Dũng – Giọng hát đầy nội lực và cảm xúc, với khả năng kể chuyện cuốn hút qua từng giai điệu, sẵn sàng khuấy động không khí trước thời khắc đếm ngược.\r\nHà An Huy – Quán quân Vietnam Idol, chàng trai mang đến làn gió mới, sự trẻ trung và năng lượng tươi sáng, hoàn hảo cho đêm Giao thừa rực rỡ. \r\nBa sắc màu âm nhạc hội tụ trong một đêm duy nhất. Một đêm nhạc nơi cảm xúc được thăng hoa, nơi những giai điệu tình ca và những nhịp đập Countdown hòa quyện thành bản giao hưởng hoàn hảo để chào đón năm 2026!\r\nĐiều khoản và điều kiện:\r\n\r\n*Lưu ý: Vé chương trình chỉ áp dụng cho khách từ 12 tuổi trở lên', 'NULL', 'LSK03', 'DN', 41);

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
  `Email_KH` varchar(50) DEFAULT NULL,
  `ChiTietThanhToan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thanhtoan`
--

INSERT INTO `thanhtoan` (`MaTT`, `PhuongThucThanhToan`, `SoTien`, `TenNguoiThanhToan`, `SDT`, `TrangThai`, `NgayTao`, `Email_KH`, `ChiTietThanhToan`) VALUES
('TT_690e074a00919', 'momo', 3500000, 'hi', '0123458436', 'Đã thanh toán', '2025-11-07 21:50:50', 'hi@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_690e07fb1e98c', 'card', 1700000, 'hi', '0275432389', 'Đã thanh toán', '2025-11-07 21:53:47', 'hi@gmail.com', '{\"payment_method\":\"card\",\"card_holder_name\":\"hi\",\"card_last_four\":\"0174\",\"card_expiry\":\"11\\/11\"}'),
('TT_690ec6d0c0e52', 'momo', 2000000, 'hi', '0123458436', 'Đã thanh toán', '2025-11-08 11:28:00', 'hi@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_69114ede2ae80', 'momo', 1099000, 'hi', '0123458436', 'Đã thanh toán', '2025-11-10 09:33:02', 'hi@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691155836c370', 'momo', 2997000, 'hi', '0123458436', 'Đã thanh toán', '2025-11-10 10:01:23', 'hi@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691491b626ef0', 'momo', 1700000, 'Hopi', '0123458436', 'Đã thanh toán', '2025-11-12 20:55:02', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}'),
('TT_691493bd15f65', 'momo', 2000000, 'Hopi', '0123458436', 'Đã thanh toán', '2025-11-12 21:03:41', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}'),
('TT_6915fc3a8772e', 'momo', 800000, 'Hopi', '0123458436', 'Đã thanh toán', '2025-11-13 22:41:46', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}'),
('TT_69160cd69371a', 'momo', 570000, 'test', '0123458157', 'Đã thanh toán', '2025-11-13 23:52:38', 'test@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_69160ee335f6b', 'momo', 800000, 'test', '0123458437', 'Đã thanh toán', '2025-11-14 00:01:23', 'test@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691611a3b8dc5', 'momo', 3600000, 'test', '0123458437', 'Đã thanh toán', '2025-11-14 00:13:07', 'test@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691613cc52afe', 'momo', 2500000, 'test', '0123458437', 'Đã thanh toán', '2025-11-14 00:22:20', 'test@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_69161535959b6', 'momo', 2000000, 'test', '0855743145', 'Đã thanh toán', '2025-11-14 00:28:21', 'test@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_6916f46415aac', 'momo', 6000000, 'Hopi', '0123458436', 'Đã thanh toán', '2025-11-14 16:20:36', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}'),
('TT_6919574461c54', 'momo', 4550000, 'helo', '0123458436', 'Đã thanh toán', '2025-11-16 11:47:00', 'helo@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691a9131d720b', 'momo', 10000000, 'tram', '0123458436', 'Đã thanh toán', '2025-11-17 10:06:25', 'tram@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691aec4cdce19', 'momo', 13650000, 'helo', '0123458436', 'Đã thanh toán', '2025-11-17 16:35:08', 'helo@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691b213ccff96', 'momo', 3600000, 'tram', '0123458436', 'Đã thanh toán', '2025-11-17 20:21:00', 'huynhtram020405@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_691b235d5169c', 'momo', 2200000, 'tram', '0123458436', 'Đã thanh toán', '2025-11-17 20:30:05', 'huynhtram020405@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_6920054e6f448', 'momo', 4550000, 'Hopi', '0123456789', 'Đã thanh toán', '2025-11-21 13:23:10', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}'),
('TT_6923ab9ddc219', 'momo', 3600000, 'Ngoc', '0123456789', 'Đã hoàn vé', '2025-11-24 07:49:33', 'slpluckysam@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_6923ae8fb9e99', 'momo', 5000000, 'Ngoc', '0123456789', 'Đã hoàn vé', '2025-11-24 08:02:07', 'slpluckysam@gmail.com', '{\"payment_method\":\"momo\"}'),
('TT_6923b8056c9ef', 'momo', 4000000, 'Hopi', '0123456789', 'Đã hoàn vé', '2025-11-24 08:42:29', 'a@ctu.edu.vn', '{\"payment_method\":\"momo\"}');

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
('VE016', 'chưa thanh toán', 'LV06', NULL),
('VE017', 'chưa thanh toán', 'LV06', NULL),
('VE018', 'chưa thanh toán', 'LV06', NULL),
('VE019', 'chưa thanh toán', 'LV07', NULL),
('VE020', 'chưa thanh toán', 'LV07', NULL),
('VE021', 'chưa thanh toán', 'LV07', NULL),
('VE022', 'chưa thanh toán', 'LV08', NULL),
('VE023', 'chưa thanh toán', 'LV08', NULL),
('VE024', 'chưa thanh toán', 'LV08', NULL),
('VE025', 'chưa thanh toán', 'LV09', NULL),
('VE026', 'chưa thanh toán', 'LV09', NULL),
('VE027', 'chưa thanh toán', 'LV09', NULL),
('VE049', 'chưa thanh toán', 'LV17', NULL),
('VE050', 'chưa thanh toán', 'LV17', NULL),
('VE051', 'chưa thanh toán', 'LV17', NULL),
('VE052', 'Đã bán', 'LV18', 'TT_69114ede2ae80'),
('VE053', 'chưa thanh toán', 'LV18', NULL),
('VE054', 'chưa thanh toán', 'LV18', NULL),
('VE055', 'chưa thanh toán', 'LV19', NULL),
('VE056', 'chưa thanh toán', 'LV19', NULL),
('VE057', 'chưa thanh toán', 'LV19', NULL),
('VE058', 'chưa thanh toán', 'LV20', NULL),
('VE059', 'chưa thanh toán', 'LV20', NULL),
('VE060', 'chưa thanh toán', 'LV20', NULL),
('VE061', 'chưa thanh toán', 'LV21', NULL),
('VE062', 'chưa thanh toán', 'LV21', NULL),
('VE063', 'chưa thanh toán', 'LV21', NULL),
('VE064', 'chưa thanh toán', 'LV22', NULL),
('VE065', 'chưa thanh toán', 'LV22', NULL),
('VE066', 'chưa thanh toán', 'LV22', NULL),
('VE067', 'Đã bán', 'LV23', 'TT_691155836c370'),
('VE068', 'Đã bán', 'LV23', 'TT_691155836c370'),
('VE069', 'Đã bán', 'LV23', 'TT_691155836c370'),
('VE070', 'chưa thanh toán', 'LV24', NULL),
('VE071', 'chưa thanh toán', 'LV24', NULL),
('VE072', 'chưa thanh toán', 'LV24', NULL),
('VE073', 'Đã bán', 'LV25', 'TT_691aec4cdce19'),
('VE074', 'Đã bán', 'LV25', 'TT_691aec4cdce19'),
('VE075', 'Đã bán', 'LV25', 'TT_691aec4cdce19'),
('VE076', 'Đã bán', 'LV26', 'TT_6919574461c54'),
('VE077', 'Đã bán', 'LV26', 'TT_6920054e6f448'),
('VE078', 'chưa thanh toán', 'LV26', NULL),
('VE079', 'Đã bán', 'LV27', 'TT_691b213ccff96'),
('VE080', 'chưa thanh toán', 'LV27', NULL),
('VE081', 'chưa thanh toán', 'LV27', NULL),
('VE082', 'Đã bán', 'LV28', 'TT_691611a3b8dc5'),
('VE083', 'chưa thanh toán', 'LV28', NULL),
('VE084', 'chưa thanh toán', 'LV28', NULL),
('VE085', 'chưa thanh toán', 'LV29', NULL),
('VE086', 'chưa thanh toán', 'LV29', NULL),
('VE087', 'chưa thanh toán', 'LV29', NULL),
('VE088', 'chưa thanh toán', 'LV30', NULL),
('VE089', 'chưa thanh toán', 'LV30', NULL),
('VE090', 'chưa thanh toán', 'LV30', NULL),
('VE091', 'chưa thanh toán', 'LV31', NULL),
('VE092', 'chưa thanh toán', 'LV31', NULL),
('VE093', 'chưa thanh toan', 'LV31', NULL),
('VE094', 'chưa thanh toan', 'LV32', NULL),
('VE095', 'chưa thanh toan', 'LV32', NULL),
('VE096', 'chưa thanh toan', 'LV32', NULL),
('VE097', 'Còn trống', 'LV33', NULL),
('VE098', 'chưa thanh toan', 'LV33', NULL),
('VE099', 'chưa thanh toan', 'LV33', NULL),
('VE100', 'chưa thanh toán', 'LV34', NULL),
('VE101', 'chưa thanh toán', 'LV34', NULL),
('VE102', 'chưa thanh toan', 'LV34', NULL),
('VE103', 'chưa thanh toan', 'LV35', NULL),
('VE104', 'chưa thanh toan', 'LV35', NULL),
('VE105', 'chưa thanh toan', 'LV35', NULL),
('VE106', 'chưa thanh toán', 'LV36', NULL),
('VE107', 'chưa thanh toán', 'LV36', NULL),
('VE108', 'chưa thanh toan', 'LV36', NULL),
('VE109', 'chưa thanh toán', 'LV37', NULL),
('VE110', 'chưa thanh toán', 'LV37', NULL),
('VE111', 'chưa thanh toán', 'LV37', NULL),
('VE112', 'chưa thanh toán', 'LV38', NULL),
('VE113', 'chưa thanh toán', 'LV38', NULL),
('VE114', 'chưa thanh toán', 'LV38', NULL),
('VE115', 'chưa thanh toán', 'LV39', NULL),
('VE116', 'chưa thanh toán', 'LV39', NULL),
('VE117', 'chưa thanh toán', 'LV39', NULL),
('VE118', 'chưa thanh toán', 'LV40', NULL),
('VE119', 'chưa thanh toán', 'LV40', NULL),
('VE120', 'chưa thanh toán', 'LV40', NULL),
('VE121', 'chưa thanh toán', 'LV41', NULL),
('VE122', 'chưa thanh toán', 'LV41', NULL),
('VE123', 'chưa thanh toán', 'LV41', NULL),
('VE124', 'chưa thanh toán', 'LV42', NULL),
('VE125', 'chưa thanh toán', 'LV42', NULL),
('VE126', 'chưa thanh toán', 'LV42', NULL),
('VE127', 'chưa thanh toán', 'LV43', NULL),
('VE128', 'chưa thanh toán', 'LV43', NULL),
('VE129', 'chưa thanh toán', 'LV43', NULL),
('VE130', 'chưa thanh toán', 'LV44', NULL),
('VE131', 'chưa thanh toán', 'LV44', NULL),
('VE132', 'chưa thanh toán', 'LV44', NULL),
('VE133', 'chưa thanh toán', 'LV45', NULL),
('VE134', 'chưa thanh toán', 'LV45', NULL),
('VE135', 'chưa thanh toán', 'LV45', NULL),
('VE136', 'chưa thanh toán', 'LV46', NULL),
('VE137', 'chưa thanh toán', 'LV46', NULL),
('VE138', 'chưa thanh toán', 'LV46', NULL),
('VE157', 'chưa thanh toán', 'LV53', NULL),
('VE158', 'chưa thanh toán', 'LV53', NULL),
('VE159', 'chưa thanh toán', 'LV53', NULL),
('VE160', 'chưa thanh toán', 'LV54', NULL),
('VE161', 'chưa thanh toán', 'LV54', NULL),
('VE162', 'chưa thanh toán', 'LV54', NULL),
('VE163', 'chưa thanh toán', 'LV55', NULL),
('VE164', 'chưa thanh toán', 'LV55', NULL),
('VE165', 'chưa thanh toán', 'LV55', NULL),
('VE166', 'chưa thanh toán', 'LV56', NULL),
('VE167', 'chưa thanh toán', 'LV56', NULL),
('VE168', 'chưa thanh toán', 'LV56', NULL),
('VE169', 'chưa thanh toán', 'LV57', NULL),
('VE170', 'chưa thanh toán', 'LV57', NULL),
('VE171', 'chưa thanh toán', 'LV57', NULL),
('VE172', 'Đã bán', 'LV58', 'TT_690e07fb1e98c'),
('VE173', 'Đã bán', 'LV58', 'TT_691491b626ef0'),
('VE174', 'chưa thanh toán', 'LV58', NULL),
('VE175', 'chưa thanh toán', 'LV59', NULL),
('VE176', 'chưa thanh toán', 'LV59', NULL),
('VE177', 'chưa thanh toán', 'LV59', NULL),
('VE178', 'chưa thanh toán', 'LV60', NULL),
('VE179', 'chưa thanh toán', 'LV60', NULL),
('VE180', 'chưa thanh toán', 'LV60', NULL),
('VE181', 'chưa thanh toán', 'LV61', NULL),
('VE182', 'chưa thanh toán', 'LV61', NULL),
('VE183', 'chưa thanh toán', 'LV61', NULL),
('VE184', 'chưa thanh toán', 'LV62', NULL),
('VE185', 'chưa thanh toán', 'LV62', NULL),
('VE186', 'chưa thanh toán', 'LV62', NULL),
('VE187', 'chưa thanh toán', 'LV63', NULL),
('VE188', 'chưa thanh toán', 'LV63', NULL),
('VE189', 'chưa thanh toán', 'LV63', NULL),
('VE190', 'chưa thanh toán', 'LV64', NULL),
('VE191', 'chưa thanh toán', 'LV64', NULL),
('VE192', 'chưa thanh toán', 'LV64', NULL),
('VE193', 'chưa thanh toán', 'LV65', NULL),
('VE194', 'chưa thanh toán', 'LV65', NULL),
('VE195', 'chưa thanh toán', 'LV65', NULL),
('VE196', 'chưa thanh toán', 'LV66', NULL),
('VE197', 'chưa thanh toán', 'LV66', NULL),
('VE198', 'chưa thanh toán', 'LV66', NULL),
('VE199', 'chưa thanh toán', 'LV67', NULL),
('VE200', 'chưa thanh toán', 'LV67', NULL),
('VE201', 'chưa thanh toán', 'LV67', NULL),
('VE202', 'chưa thanh toán', 'LV68', NULL),
('VE203', 'chưa thanh toán', 'LV68', NULL),
('VE204', 'chưa thanh toán', 'LV68', NULL),
('VE205', 'chưa thanh toán', 'LV69', NULL),
('VE206', 'chưa thanh toán', 'LV69', NULL),
('VE207', 'chưa thanh toán', 'LV69', NULL),
('VE208', 'chưa thanh toán', 'LV70', NULL),
('VE209', 'chưa thanh toán', 'LV70', NULL),
('VE210', 'chưa thanh toán', 'LV70', NULL),
('VE226', 'Đã bán', 'LV76', 'TT_69160cd69371a'),
('VE227', 'chưa thanh toán', 'LV76', NULL),
('VE228', 'chưa thanh toán', 'LV76', NULL),
('VE229', 'chưa thanh toán', 'LV77', NULL),
('VE230', 'chưa thanh toán', 'LV77', NULL),
('VE231', 'chưa thanh toán', 'LV77', NULL),
('VE232', 'chưa thanh toán', 'LV78', NULL),
('VE233', 'chưa thanh toán', 'LV78', NULL),
('VE234', 'chưa thanh toán', 'LV78', NULL),
('VE235', 'chưa thanh toán', 'LV79', NULL),
('VE236', 'chưa thanh toán', 'LV79', NULL),
('VE237', 'chưa thanh toán', 'LV79', NULL),
('VE238', 'chưa thanh toán', 'LV80', NULL),
('VE239', 'chưa thanh toán', 'LV80', NULL),
('VE240', 'chưa thanh toán', 'LV80', NULL),
('VE241', 'chưa thanh toán', 'LV81', NULL),
('VE242', 'chưa thanh toán', 'LV81', NULL),
('VE243', 'chưa thanh toán', 'LV81', NULL),
('VE244', 'chưa thanh toán', 'LV82', NULL),
('VE245', 'chưa thanh toán', 'LV82', NULL),
('VE246', 'chưa thanh toán', 'LV82', NULL),
('VE247', 'chưa thanh toán', 'LV83', NULL),
('VE248', 'chưa thanh toán', 'LV83', NULL),
('VE249', 'chưa thanh toán', 'LV83', NULL),
('VE250', 'chưa thanh toán', 'LV84', NULL),
('VE251', 'chưa thanh toán', 'LV84', NULL),
('VE252', 'chưa thanh toán', 'LV84', NULL),
('VE253', 'chưa thanh toán', 'LV85', NULL),
('VE254', 'chưa thanh toán', 'LV85', NULL),
('VE255', 'chưa thanh toán', 'LV85', NULL),
('VE256', 'chưa thanh toán', 'LV86', NULL),
('VE257', 'chưa thanh toán', 'LV86', NULL),
('VE258', 'chưa thanh toán', 'LV86', NULL),
('VE259', 'chưa thanh toán', 'LV87', NULL),
('VE260', 'chưa thanh toán', 'LV87', NULL),
('VE261', 'chưa thanh toán', 'LV87', NULL),
('VE262', 'chưa thanh toán', 'LV88', NULL),
('VE263', 'chưa thanh toán', 'LV88', NULL),
('VE264', 'chưa thanh toán', 'LV88', NULL),
('VE265', 'chưa thanh toán', 'LV89', NULL),
('VE266', 'chưa thanh toán', 'LV89', NULL),
('VE267', 'chưa thanh toán', 'LV89', NULL),
('VE268', 'chưa thanh toán', 'LV90', NULL),
('VE269', 'chưa thanh toán', 'LV90', NULL),
('VE270', 'chưa thanh toán', 'LV90', NULL),
('VE271', 'chưa thanh toán', 'LV91', NULL),
('VE272', 'chưa thanh toán', 'LV91', NULL),
('VE273', 'chưa thanh toán', 'LV91', NULL),
('VE274', 'chưa thanh toán', 'LV92', NULL),
('VE275', 'chưa thanh toán', 'LV92', NULL),
('VE276', 'chưa thanh toán', 'LV92', NULL),
('VE277', 'chưa thanh toán', 'LV93', NULL),
('VE278', 'chưa thanh toán', 'LV93', NULL),
('VE279', 'chưa thanh toán', 'LV93', NULL),
('VE280', 'chưa thanh toán', 'LV94', NULL),
('VE281', 'chưa thanh toán', 'LV94', NULL),
('VE282', 'chưa thanh toán', 'LV94', NULL),
('VE283', 'Đã bán', 'LV95', 'TT_691613cc52afe'),
('VE284', 'chưa thanh toán', 'LV95', NULL),
('VE285', 'chưa thanh toán', 'LV95', NULL),
('VE286', 'Đã bán', 'LV96', 'TT_690ec6d0c0e52'),
('VE287', 'Đã bán', 'LV96', 'TT_691493bd15f65'),
('VE288', 'chưa thanh toán', 'LV96', NULL),
('VE289', 'Đã bán', 'LV97', 'TT_69161535959b6'),
('VE290', 'chưa thanh toán', 'LV97', NULL),
('VE291', 'chưa thanh toán', 'LV97', NULL),
('VE292', 'Đã bán', 'LV98', 'TT_691a9131d720b'),
('VE293', 'chưa thanh toán', 'LV98', NULL),
('VE294', 'chưa thanh toán', 'LV98', NULL),
('VE295', 'chưa thanh toán', 'LV98', NULL),
('VE296', 'chưa thanh toán', 'LV98', NULL),
('VE297', 'chưa thanh toán', 'LV99', NULL),
('VE298', 'chưa thanh toán', 'LV99', NULL),
('VE299', 'chưa thanh toán', 'LV99', NULL),
('VE300', 'chưa thanh toán', 'LV99', NULL),
('VE301', 'chưa thanh toán', 'LV99', NULL),
('VE302', 'chưa thanh toán', 'LV100', NULL),
('VE303', 'chưa thanh toán', 'LV100', NULL),
('VE304', 'chưa thanh toán', 'LV100', NULL),
('VE305', 'chưa thanh toán', 'LV100', NULL),
('VE306', 'chưa thanh toán', 'LV100', NULL),
('VE307', 'chưa thanh toán', 'LV101', NULL),
('VE308', 'chưa thanh toán', 'LV101', NULL),
('VE309', 'chưa thanh toán', 'LV101', NULL),
('VE310', 'chưa thanh toán', 'LV101', NULL),
('VE311', 'chưa thanh toán', 'LV101', NULL),
('VE312', 'chưa thanh toán', 'LV102', NULL),
('VE313', 'chưa thanh toán', 'LV102', NULL),
('VE314', 'chưa thanh toán', 'LV102', NULL),
('VE315', 'chưa thanh toán', 'LV102', NULL),
('VE316', 'chưa thanh toán', 'LV102', NULL),
('VE317', 'chưa thanh toán', 'LV103', NULL),
('VE318', 'chưa thanh toán', 'LV103', NULL),
('VE319', 'chưa thanh toán', 'LV103', NULL),
('VE320', 'chưa thanh toán', 'LV103', NULL),
('VE321', 'chưa thanh toán', 'LV103', NULL),
('VE352', 'chưa thanh toán', 'LV110', NULL),
('VE353', 'chưa thanh toán', 'LV110', NULL),
('VE354', 'chưa thanh toán', 'LV110', NULL),
('VE355', 'chưa thanh toán', 'LV110', NULL),
('VE356', 'chưa thanh toán', 'LV110', NULL),
('VE357', 'chưa thanh toán', 'LV111', NULL),
('VE358', 'chưa thanh toán', 'LV111', NULL),
('VE359', 'chưa thanh toán', 'LV111', NULL),
('VE360', 'chưa thanh toán', 'LV111', NULL),
('VE361', 'chưa thanh toán', 'LV111', NULL),
('VE362', 'chưa thanh toán', 'LV112', NULL),
('VE363', 'chưa thanh toán', 'LV112', NULL),
('VE364', 'chưa thanh toán', 'LV112', NULL),
('VE365', 'chưa thanh toán', 'LV112', NULL),
('VE366', 'chưa thanh toán', 'LV112', NULL),
('VE367', 'chưa thanh toán', 'LV113', NULL),
('VE368', 'chưa thanh toán', 'LV113', NULL),
('VE369', 'chưa thanh toán', 'LV113', NULL),
('VE370', 'chưa thanh toán', 'LV113', NULL),
('VE371', 'chưa thanh toán', 'LV113', NULL),
('VE402', 'Đã bán', 'LV120', 'TT_69160ee335f6b'),
('VE403', 'chưa thanh toán', 'LV120', NULL),
('VE404', 'chưa thanh toán', 'LV120', NULL),
('VE405', 'chưa thanh toán', 'LV120', NULL),
('VE406', 'chưa thanh toán', 'LV120', NULL),
('VE407', 'chưa thanh toán', 'LV121', NULL),
('VE408', 'chưa thanh toán', 'LV121', NULL),
('VE409', 'chưa thanh toán', 'LV121', NULL),
('VE410', 'chưa thanh toán', 'LV122', NULL),
('VE411', 'chưa thanh toán', 'LV122', NULL),
('VE412', 'chưa thanh toán', 'LV122', NULL),
('VE413', 'Đã bán', 'LV123', 'TT_6916f46415aac'),
('VE414', 'chưa thanh toán', 'LV123', NULL),
('VE415', 'chưa thanh toán', 'LV123', NULL),
('VE416', 'chưa thanh toán', 'LV124', NULL),
('VE417', 'chưa thanh toán', 'LV124', NULL),
('VE418', 'chưa thanh toán', 'LV124', NULL),
('VE419', 'chưa thanh toán', 'LV125', NULL),
('VE420', 'chưa thanh toán', 'LV125', NULL),
('VE421', 'chưa thanh toán', 'LV125', NULL),
('VE422', 'chưa thanh toán', 'LV126', NULL),
('VE423', 'chưa thanh toán', 'LV126', NULL),
('VE424', 'chưa thanh toán', 'LV126', NULL),
('VE425', 'Chưa thanh toán', 'LV140', NULL),
('VE426', 'Chưa thanh toán', 'LV140', NULL),
('VE427', 'Chưa thanh toán', 'LV141', NULL),
('VE428', 'Chưa thanh toán', 'LV141', NULL),
('VE429', 'Chưa thanh toán', 'LV141', NULL),
('VE430', 'Chưa thanh toán', 'LV142', NULL),
('VE431', 'Chưa thanh toán', 'LV142', NULL),
('VE432', 'Chưa thanh toán', 'LV142', NULL),
('VE433', 'Chưa thanh toán', 'LV143', NULL),
('VE434', 'Chưa thanh toán', 'LV143', NULL),
('VE435', 'Chưa thanh toán', 'LV143', NULL),
('VE436', 'Chưa thanh toán', 'LV144', NULL),
('VE437', 'Chưa thanh toán', 'LV144', NULL),
('VE438', 'Chưa thanh toán', 'LV144', NULL),
('VE439', 'Chưa thanh toán', 'LV145', NULL),
('VE440', 'Chưa thanh toán', 'LV145', NULL),
('VE441', 'Chưa thanh toán', 'LV145', NULL),
('VE442', 'Chưa thanh toán', 'LV146', NULL),
('VE443', 'Chưa thanh toán', 'LV146', NULL),
('VE444', 'Chưa thanh toán', 'LV146', NULL),
('VE445', 'Chưa thanh toán', 'LV147', NULL),
('VE446', 'Chưa thanh toán', 'LV147', NULL),
('VE447', 'Chưa thanh toán', 'LV147', NULL),
('VE448', 'Chưa thanh toán', 'LV148', NULL),
('VE449', 'Chưa thanh toán', 'LV148', NULL),
('VE450', 'Chưa thanh toán', 'LV148', NULL),
('VE451', 'Chưa thanh toán', 'LV149', NULL),
('VE452', 'Chưa thanh toán', 'LV149', NULL),
('VE453', 'Chưa thanh toán', 'LV149', NULL),
('VE454', 'Chưa thanh toán', 'LV150', NULL),
('VE455', 'Chưa thanh toán', 'LV150', NULL),
('VE456', 'Chưa thanh toán', 'LV150', NULL),
('VE457', 'Chưa thanh toán', 'LV151', NULL),
('VE458', 'Chưa thanh toán', 'LV151', NULL),
('VE459', 'Chưa thanh toán', 'LV152', NULL),
('VE460', 'Chưa thanh toán', 'LV152', NULL),
('VE461', 'Chưa thanh toán', 'LV153', NULL),
('VE462', 'Chưa thanh toán', 'LV153', NULL),
('VE463', 'Chưa thanh toán', 'LV154', NULL),
('VE464', 'Chưa thanh toán', 'LV154', NULL),
('VE465', 'Chưa thanh toán', 'LV155', NULL),
('VE466', 'Chưa thanh toán', 'LV155', NULL),
('VE467', 'Chưa thanh toán', 'LV156', NULL),
('VE468', 'Chưa thanh toán', 'LV156', NULL),
('VE469', 'Chưa thanh toán', 'LV157', NULL),
('VE470', 'Chưa thanh toán', 'LV157', NULL),
('VE471', 'Chưa thanh toán', 'LV158', NULL),
('VE472', 'Chưa thanh toán', 'LV158', NULL),
('VE473', 'Chưa thanh toán', 'LV159', NULL),
('VE474', 'Chưa thanh toán', 'LV159', NULL),
('VE475', 'Chưa thanh toán', 'LV160', NULL),
('VE476', 'Chưa thanh toán', 'LV160', NULL),
('VE477', 'Chưa thanh toán', 'LV161', NULL),
('VE478', 'Chưa thanh toán', 'LV161', NULL),
('VE479', 'Chưa thanh toán', 'LV162', NULL),
('VE480', 'Chưa thanh toán', 'LV162', NULL),
('VE481', 'Chưa thanh toán', 'LV163', NULL),
('VE482', 'Chưa thanh toán', 'LV163', NULL),
('VE483', 'Chưa thanh toán', 'LV164', NULL),
('VE484', 'Chưa thanh toán', 'LV164', NULL),
('VE485', 'Chưa thanh toán', 'LV165', NULL),
('VE486', 'Chưa thanh toán', 'LV165', NULL),
('VE487', 'Chưa thanh toán', 'LV166', NULL),
('VE488', 'Chưa thanh toán', 'LV166', NULL),
('VE489', 'Chưa thanh toán', 'LV167', NULL),
('VE490', 'Chưa thanh toán', 'LV167', NULL),
('VE491', 'Chưa thanh toán', 'LV168', NULL),
('VE492', 'Chưa thanh toán', 'LV168', NULL),
('VE493', 'Chưa thanh toán', 'LV169', NULL),
('VE494', 'Chưa thanh toán', 'LV169', NULL),
('VE495', 'Chưa thanh toán', 'LV170', NULL),
('VE496', 'Chưa thanh toán', 'LV170', NULL),
('VE497', 'Chưa thanh toán', 'LV171', NULL),
('VE498', 'Chưa thanh toán', 'LV171', NULL),
('VE499', 'Chưa thanh toán', 'LV172', NULL),
('VE500', 'Chưa thanh toán', 'LV172', NULL),
('VE501', 'Chưa thanh toán', 'LV173', NULL),
('VE502', 'Chưa thanh toán', 'LV173', NULL),
('VE503', 'Chưa thanh toán', 'LV174', NULL),
('VE504', 'Chưa thanh toán', 'LV174', NULL),
('VE541', 'Chưa thanh toán', 'LV151', NULL),
('VE542', 'Chưa thanh toán', 'LV151', NULL),
('VE543', 'Chưa thanh toán', 'LV151', NULL),
('VE544', 'Chưa thanh toán', 'LV152', NULL),
('VE545', 'Chưa thanh toán', 'LV152', NULL),
('VE546', 'Chưa thanh toán', 'LV152', NULL),
('VE547', 'Chưa thanh toán', 'LV153', NULL),
('VE548', 'Chưa thanh toán', 'LV153', NULL),
('VE549', 'Chưa thanh toán', 'LV153', NULL),
('VE550', 'Chưa thanh toán', 'LV154', NULL),
('VE551', 'Chưa thanh toán', 'LV154', NULL),
('VE552', 'Chưa thanh toán', 'LV154', NULL),
('VE553', 'Chưa thanh toán', 'LV155', NULL),
('VE554', 'Chưa thanh toán', 'LV155', NULL),
('VE555', 'Chưa thanh toán', 'LV155', NULL),
('VE556', 'Chưa thanh toán', 'LV156', NULL),
('VE557', 'Chưa thanh toán', 'LV156', NULL),
('VE558', 'Chưa thanh toán', 'LV156', NULL),
('VE559', 'Chưa thanh toán', 'LV157', NULL),
('VE560', 'Chưa thanh toán', 'LV157', NULL),
('VE561', 'Chưa thanh toán', 'LV157', NULL),
('VE562', 'Chưa thanh toán', 'LV158', NULL),
('VE563', 'Chưa thanh toán', 'LV158', NULL),
('VE564', 'Chưa thanh toán', 'LV158', NULL),
('VE565', 'Chưa thanh toán', 'LV159', NULL),
('VE566', 'Chưa thanh toán', 'LV159', NULL),
('VE567', 'Chưa thanh toán', 'LV159', NULL),
('VE568', 'Chưa thanh toán', 'LV160', NULL),
('VE569', 'Chưa thanh toán', 'LV160', NULL),
('VE570', 'Chưa thanh toán', 'LV160', NULL),
('VE571', 'Chưa thanh toán', 'LV161', NULL),
('VE572', 'Chưa thanh toán', 'LV161', NULL),
('VE573', 'Chưa thanh toán', 'LV161', NULL),
('VE574', 'Chưa thanh toán', 'LV162', NULL),
('VE575', 'Chưa thanh toán', 'LV162', NULL),
('VE576', 'Chưa thanh toán', 'LV162', NULL),
('VE577', 'Chưa thanh toán', 'LV163', NULL),
('VE578', 'Chưa thanh toán', 'LV163', NULL),
('VE579', 'Chưa thanh toán', 'LV163', NULL),
('VE580', 'Chưa thanh toán', 'LV164', NULL),
('VE581', 'Chưa thanh toán', 'LV164', NULL),
('VE582', 'Chưa thanh toán', 'LV164', NULL),
('VE583', 'Chưa thanh toán', 'LV165', NULL),
('VE584', 'Chưa thanh toán', 'LV165', NULL),
('VE585', 'Chưa thanh toán', 'LV165', NULL),
('VE586', 'Chưa thanh toán', 'LV166', NULL),
('VE587', 'Chưa thanh toán', 'LV166', NULL),
('VE588', 'Chưa thanh toán', 'LV166', NULL),
('VE589', 'Chưa thanh toán', 'LV167', NULL),
('VE590', 'Chưa thanh toán', 'LV167', NULL),
('VE591', 'Chưa thanh toán', 'LV167', NULL),
('VE592', 'Chưa thanh toán', 'LV168', NULL),
('VE593', 'Chưa thanh toán', 'LV168', NULL),
('VE594', 'Chưa thanh toán', 'LV168', NULL),
('VE595', 'Chưa thanh toán', 'LV169', NULL),
('VE596', 'Chưa thanh toán', 'LV169', NULL),
('VE597', 'Chưa thanh toán', 'LV169', NULL),
('VE598', 'chưa thanh toán', 'LV193', NULL),
('VE599', 'Chưa thanh toán', 'LV193', NULL),
('VE600', 'Chưa thanh toán', 'LV193', NULL),
('VE601', 'Chưa thanh toán', 'LV194', NULL),
('VE602', 'Chưa thanh toán', 'LV194', NULL),
('VE603', 'Chưa thanh toán', 'LV194', NULL),
('VE604', 'Đã bán', 'LV195', 'TT_691b235d5169c'),
('VE605', 'Chưa thanh toán', 'LV195', NULL),
('VE606', 'Chưa thanh toán', 'LV195', NULL),
('VE607', 'Chưa thanh toán', 'LV196', NULL),
('VE608', 'Chưa thanh toán', 'LV196', NULL),
('VE609', 'Chưa thanh toán', 'LV196', NULL),
('VE610', 'Chưa thanh toán', 'LV197', NULL),
('VE611', 'Chưa thanh toán', 'LV197', NULL),
('VE612', 'Chưa thanh toán', 'LV197', NULL),
('VE613', 'Chưa thanh toán', 'LV198', NULL),
('VE614', 'Chưa thanh toán', 'LV198', NULL),
('VE615', 'Chưa thanh toán', 'LV198', NULL),
('VE616', 'Chưa thanh toán', 'LV199', NULL),
('VE617', 'Chưa thanh toán', 'LV199', NULL),
('VE618', 'Chưa thanh toán', 'LV199', NULL);

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
  ADD PRIMARY KEY (`email`);

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
  ADD KEY `fk_sukien_loaive` (`MaSK`);

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
  ADD KEY `FK_ThanhToan_Ve` (`MaTT`),
  ADD KEY `FK_LoaiVe_Ve` (`MaLoai`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loaive`
--
ALTER TABLE `loaive`
  ADD CONSTRAINT `fk_sukien_loaive` FOREIGN KEY (`MaSK`) REFERENCES `sukien` (`MaSK`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `FK_LoaiVe_Ve` FOREIGN KEY (`MaLoai`) REFERENCES `loaive` (`MaLoai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ThanhToan_Ve` FOREIGN KEY (`MaTT`) REFERENCES `thanhtoan` (`MaTT`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
