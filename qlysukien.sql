-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 07:47 AM
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
('HCM', 'Thành phố Hồ Chí Minh'),
('HN', 'Hà Nội'),
('HY', 'Hưng Yên');

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
  `MLV` char(5) NOT NULL,
  `TenLV` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loaive`
--

INSERT INTO `loaive` (`MLV`, `TenLV`) VALUES
('LV01', 'Vé Standing'),
('LV02', 'Vé VIP'),
('LV03', 'Vé thường'),
('LV04', 'Vé Early Bird'),
('LV05', 'Vé Combo');

-- --------------------------------------------------------

--
-- Table structure for table `sukien`
--

CREATE TABLE `sukien` (
  `MaSK` char(5) NOT NULL,
  `TenSK` varchar(100) NOT NULL,
  `Gia` float NOT NULL,
  `Tgian` date DEFAULT NULL,
  `img_sukien` varchar(100) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `MaLSK` char(5) DEFAULT NULL,
  `MaDD` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sukien`
--

INSERT INTO `sukien` (`MaSK`, `TenSK`, `Gia`, `Tgian`, `img_sukien`, `mota`, `MaLSK`, `MaDD`) VALUES
('SK01', 'LULULOLA SHOW VŨ CÁT TƯỜNG | NGÀY NÀY, NGƯỜI CON GÁI NÀY', 560000, '2025-10-18', 'https://salt.tkbcdn.com/ts/ds/cb/5a/3b/13e9a9ccf99d586df2a7c6bd59d89369.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL'),
('SK02', '[CAT&MOUSE] CA SĨ ĐẠT G - ĐÊM LẶNG TÔ MÀU XÚC CẢM', 500000, '2025-10-31', 'https://salt.tkbcdn.com/ts/ds/37/25/63/9a82b897b7f175b5888016f161d0fa1e.png', 'Với không gian được đầu tư hệ thống ánh sáng - âm thanh đẳng cấp quốc tế với sức chứa lên đến 350 người, cùng quầy bar phục vụ cocktail pha chế độc đáo bởi bartender chuyên nghiệp.\n\n20g00 - 31/10/2025 (Thứ 6), một đêm nhạc sâu lắng và chân thành tại Cat&Mouse đã hé lộ. Sự góp mặt của Đạt G với chất giọng trầm ấm, đặc trưng, cùng phong cách âm nhạc giàu cảm xúc, sẽ giúp bạn tìm thấy chính mình trong những khoảnh khắc cô đơn nhưng cũng đầy sự an ủi.\n\nQuý khách tham dự đêm diễn sẽ được tặng 1 phần đồ ăn nhẹ.', 'LSK01', 'HCM'),
('SK03', 'G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI, PRESENTED BY VPBANK', 2000000, '2025-11-08', 'https://salt.tkbcdn.com/ts/ds/2b/62/6d/b72040ac36d256c6c51e4c01797cf879.png', 'Lần đầu tiên, \"Ông hoàng K-pop\" G-DRAGON chính thức tổ chức concert tại Việt Nam, mở màn cho chuỗi World Tour do 8Wonder mang tới. G-DRAGON 2025 WORLD TOUR [Übermensch] hứa hẹn sẽ bùng nổ với sân khấu kì công, âm thanh - ánh sáng mãn nhãn và những khoảnh khắc chạm đến trái tim người hâm mộ. G-DRAGON sẽ mang đến những bản hit từng gắn liền với thanh xuân của hàng triệu người hâm mộ. Một đêm nhạc không chỉ để thưởng thức, mà còn để lưu giữ trong ký ức.', 'LSK03', 'HY'),
('SK04', '[CAT&MOUSE] CA SĨ NGÔ LAN HƯƠNG - TUỔI MỘNG MƠ', 400000, '2025-11-08', 'https://salt.tkbcdn.com/ts/ds/7d/ac/94/9eb9dd09edfa026840340f8c75d2b520.png', 'Với không gian được đầu tư hệ thống ánh sáng - âm thanh đẳng cấp quốc tế với sức chứa lên đến 350 người, cùng quầy bar phục vụ cocktail pha chế độc đáo bởi bartender chuyên nghiệp.\n\n20h00 - 8/11/2025 (Thứ 7) này tại Cat&Mouse, một đêm nhạc mới cùng âm hưởng trong trẻo và trẻ trung.\n\nVới sự góp mặt của nàng ca sĩ - nhạc sĩ tài năng Ngô Lan Hương với bản hit “Đi giữa trời rực rỡ” từng đoạt giải \"Bài hát hiện tượng của năm\" (Làn Sóng Xanh 2024), “Tuổi mộng mơ”, “Thà bỏ lỡ”,... cùng nhiều bài hát ngọt ngào và hoài niệm khác chờ bạn đến thưởng thức.', 'LSK01', 'HCM'),
('SK05', 'Waterbomb Ho Chi Minh City 2025', 899000, '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/f3/80/f0/32ee189d7a435daf92b6a138d925381c.png', 'Vào hai ngày 15–16/11/2025, khu đô thị Vạn Phúc City (TP.HCM) sẽ trở thành tâm điểm của giới trẻ khi lễ hội âm nhạc WATERBOMB lần đầu tiên “cập bến” Việt Nam. Với mô hình kết hợp âm nhạc – trình diễn – hiệu ứng phun nước đặc trưng từ Hàn Quốc, sự kiện hứa hẹn mang đến trải nghiệm “ướt sũng” đầy phấn khích cùng dàn nghệ sĩ đình đám như Hwasa, Jay Park, B.I, Sandara Park, Rain, EXID, Shownu x Hyungwon (MONSTA X), cùng các ngôi sao Vpop như HIEUTHUHAI, tlinh, SOOBIN, Tóc Tiên, Chi Pu, MIN và nhiều cái tên hot khác.\n\nKhông chỉ là sân khấu âm nhạc, WATERBOMB còn là đại tiệc cảm xúc với khu vui chơi phun nước liên hoàn, khu check-in phong cách lễ hội, và các hạng vé đa dạng từ GA đến Splash Wave – nơi bạn có thể “quẩy” sát sân khấu cùng thần tượng. Đây là cơ hội hiếm có để fan Kpop và khán giả Việt cùng hòa mình vào không gian lễ hội quốc tế ngay giữa lòng Sài Gòn.\n', 'LSK02', 'HCM'),
('SK06', 'GS25 MUSIC FESTIVAL 2025', 699000, '2025-11-22', 'https://salt.tkbcdn.com/ts/ds/6e/2f/fa/32d07d9e0b2bd6ff7de8dfe2995619d5.jpg', 'GS25 MUSIC FESTIVAL 2025 sẽ diễn ra vào ngày 22/11 tại Công viên Sáng Tạo, Thủ Thiêm, TP.HCM, từ 10:00 đến 23:00. Đây là lễ hội âm nhạc ngoài trời hoành tráng do GS25 tổ chức, quy tụ nhiều nghệ sĩ nổi tiếng. Khách hàng có thể đổi vé tham dự bằng cách tích điểm khi mua sắm tại GS25 và CAFE25 từ 01/10 đến 15/11. Vé không cho phép hoàn trả và cần đeo vòng tay khi tham gia. Sự kiện hứa hẹn mang đến trải nghiệm âm nhạc sôi động và không gian lễ hội trẻ trung dành cho giới trẻ.', 'LSK02', 'HCM'),
('SK07', '2025 K-POP SUPER CONCERT IN HO CHI MINH', 1300000, '2025-11-22', 'https://salt.tkbcdn.com/ts/ds/bb/96/bd/28394979b702cd9dc934bef42824e6c1.png', 'Vào ngày 22/11/2025, sự kiện K-POP SUPER CONCERT sẽ chính thức diễn ra tại Vạn Phúc City, TP.HCM, do Golden Space Entertainment tổ chức. Đây là một lễ hội âm nhạc hoành tráng quy tụ dàn nghệ sĩ K-pop và Việt Nam, với sự góp mặt của các tên tuổi như XIUMIN, CHEN, DUCPHUC, ARrC, và nhóm nữ Gen Z đa quốc tịch We;Na – lần đầu tiên ra mắt tại Việt Nam.', 'LSK03', 'HCM'),
('SK08', 'SOOBIN LIVE CONCERT: ALL-ROUNDER THE FINAL', 800000, '2025-11-29', 'https://salt.tkbcdn.com/ts/ds/9c/9e/c1/2edd538cb4df21a0d13f95588cb44dc4.png', 'Các all-rounders chờ đã lâu rồi phải không? Một lần nữa hãy cùng đắm chìm trong trải nghiệm sân khấu \'all around you\', để SOOBIN cùng âm nhạc luôn chuyển động bên bạn mọi lúc - mọi nơi nhé!', 'LSK03', 'HCM'),
('SK09', 'Những Thành Phố Mơ Màng Year End 2025', 620000, '2025-12-07', 'https://salt.tkbcdn.com/ts/ds/e8/95/f3/2dcfee200f26f1ec0661885b2c816fa6.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến:  07/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại TP.HCM (sẽ cập nhật sau).', 'LSK03', 'HCM'),
('SK10', 'Những Thành Phố Mơ Màng Year End 2025', 620000, '2022-12-21', 'https://salt.tkbcdn.com/ts/ds/18/8f/59/2d0abe9be901a894cd3b0bf29fd01863.png', 'Chào mừng cư dân đến với NTPMM Year End 2025 - Wondertopia,  vùng đất diệu kỳ nơi âm nhạc cất lời và cảm xúc thăng hoa!\nTại đây, từng giai điệu sẽ dẫn lối, từng tiết tấu sẽ mở ra cánh cửa đến một thế giới đầy màu sắc, nơi mọi người cùng nhau hòa nhịp trong niềm vui và sự gắn kết.\n\nHành trình khép lại năm 2025 sẽ trở thành một đại tiệc của âm nhạc, sáng tạo và bất ngờ. Wondertopia không chỉ là một show diễn – mà là không gian nơi chúng ta tìm thấy sự đồng điệu, truyền cảm hứng cho một khởi đầu mới rực rỡ hơn.\n\nTHÔNG TIN SỰ KIỆN\n\nThời gian dự kiến: 21/12/2025 \n\nĐịa điểm: khu vực ngoài trời tại Hà Nội (sẽ cập nhật sau).', 'LSK03', 'HN'),
('SK11', '1900 Future Hits #75: Thanh Duy', 400000, '2025-10-24', 'https://salt.tkbcdn.com/ts/ds/df/d8/ec/9f46a4e587b39ccf5886e6ae6f1b27d0.png', 'Nhắc đến Thanh Duy (Á quân Vietnam Idol 2008) là nhắc đến một nghệ sĩ nhiều màu sắc, một chú \"tắc kè hoa\" của showbiz. Thanh Duy kể những câu chuyện độc đáo, chạm đến tim người nghe bằng âm nhạc. Mỗi bài hát là một mảnh ghép cá tính, không lẫn vào đâu được.\n \nVới style không ngại khác biệt, thời trang \"chơi trội\" và tinh thần sống thật, sống hết mình, Thanh Duy luôn là nguồn năng lượng tích cực, truyền cảm hứng sống vui, sống thật cho giới trẻ. \n \nNgày 24/10 tới đây, 1900 sẽ chào đón Thanh Duy đến với đêm nhạc Future Hits #75. Các bản hit sẽ được vang lên trên sân khấu 1900, hứa hẹn mang đến những moment cực peak.\n \nSave the date!', 'LSK01', 'HN'),
('SK12', 'RAVERSE #3: Clowns Du Chaos w/ MIKE WILLIAMS - Oct 31 (HALLOWEEN PARTY)', 450000, '2025-10-31', 'https://salt.tkbcdn.com/ts/ds/e0/71/b2/b213ce9427cfc01487c73df2ba849787.jpg', 'Sau những đêm cháy hết mình cùng DubVision và Maddix, RAVERSE đã chính thức quay trở lại và lần này, Raverse sẽ biến APLUS HANOI thành một RẠP XIẾC MA MỊ đúng nghĩa. Cùng chào đón Headliner – MIKE WILLIAMS, DJ/Producer top 72 DJ Mag - Người đứng sau hàng loạt hit Future Bounce tỉ lượt nghe, từng khuấy đảo những sân khấu lớn nhất thế giới Tomorrowland, Ultra Music Festival,... nay sẽ đổ bộ Raverse #3 mang theo năng lượng bùng nổ chưa từng có! ⚡Cánh cửa rạp xiếc sắp mở… Bạn đã sẵn sàng hóa thân, quẩy hết mình và bước vào thế giới hỗn loạn của RAVERSE chưa?', 'LSK02', 'HN'),
('SK13', 'Jazz concert: Immersed', 5000000, '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/43/54/98/924b6491983baf58b00222c9b5b7295b.jpg', 'JAZZ CONCERT – IMMERSED: SỰ KẾT HỢP ĐỈNH CAO TỪ NHỮNG TÊN TUỔI HÀNG ĐẦU\n\n🌿Được khởi xướng bởi GG Corporation, Living Heritage ra đời với sứ mệnh là quy tụ và tôn vinh những giá trị sống đích thực của cộng đồng người Việt trên khắp thế giới – từ trải nghiệm, tri thức đến nhân sinh quan sâu sắc của các thế hệ đi trước để trao truyền lại cho thế hệ tương lai.\n\n🌻Living Heritage là một hệ sinh thái nội dung gồm: trang web chính thức lưu trữ các cuộc trò chuyện ý nghĩa, sách điện tử (được phát phát hành trên Amazon), cùng chuỗi sự kiện nghệ thuật – giáo dục tầm vóc quốc tế thường niên. 🎼Khởi đầu hành trình này là Jazz Concert IMMERSED – đêm nhạc quốc tế với sự tham gia đặc biệt của “Hiệp sĩ” Jazz - Sir Niels Lan Doky, huyền thoại piano Jazz được biết đến như một trong những nghệ sĩ tiên phong của dòng Jazz châu Âu hiện đại. Báo chí Nhật Bản gọi ông là “nghệ sĩ xuất sắc nhất thế hệ”, còn tờ báo El Diario (Tây Ban Nha) gọi ông là “một trong những nghệ sĩ piano quan trọng nhất nửa thế kỷ qua”. Ông sẽ trình diễn cùng bộ đôi nghệ sĩ quốc tế Felix Pastorius (bass) và Jonas Johansen (trống), dưới sự dàn dựng của Tổng đạo diễn Phạm Hoàng Nam, Giám đốc Âm nhạc Quốc Trung, Kĩ sư âm thanh Doãn Chí Nghĩa, Nhà thiết kế Phục trang Tom Trandt, Biên đạo múa Ngọc Anh và Nghệ sĩ nghệ thuật thị giác Tùng Monkey.\n\n⭐️Điểm nhấn đặc biệt là những màn kết hợp giữa Sir Niels Lan Doky và các nghệ sĩ hàng đầu Việt Nam như NSND Thanh Lam, ca sĩ Hà Trần, nghệ sĩ saxophone Quyền Thiện Đắc và một số nghệ sĩ khác – những tên tuổi có dấu ấn rõ nét trong việc vừa gìn giữ nét đẹp bản sắc của âm nhạc Việt, vừa tìm tòi, sáng tạo và đổi mới để hội nhập vào dòng chảy âm nhạc thế giới. Sự hội ngộ này tạo nên một không gian âm nhạc đa chiều, nơi tinh thần Jazz quốc tế gặp gỡ hơi thở dân gian đương đại Việt Nam trong một cuộc đối thoại âm nhạc đỉnh cao, hoà quyện và đầy ngẫu hứng.\n\nChi tiết sự kiện:\n\nChương trình chính: Khách mời đặc biêt Sir Niels Lan Doky, Knight of Jazz cùng \nKhách mời: NSND Thanh Lam, Ca sỹ Hà Trần, Nghệ sỹ Quyền Thiện Đắc.', 'LSK03', 'HCM'),
('SK14', '[Dốc Mộng Mơ] Em Đồng Ý - Đức Phúc - Noo Phước Thịnh', 700000, '2025-11-15', 'https://salt.tkbcdn.com/ts/ds/6d/9b/da/438a1b16cba1c64f5befce0fdd32682a.jpg', 'Đêm nhạc đánh dấu chặng đường trưởng thành của Đức Phúc với những bản hit được phối mới đầy cảm xúc, sân khấu dàn dựng công phu cùng sự góp mặt của ca sĩ Noo Phước Thịnh.\n\nMột hành trình âm nhạc lãng mạn và bất ngờ, chắc chắn là khoảnh khắc không thể bỏ lỡ!\n\nChi tiết sự kiện \n\n	Chương trình chính: \n \nTrình diễn những ca khúc nổi bật nhất trong sự nghiệp ca hát của Đức Phúc. \n\nCác tiết mục dàn dựng công phu, phối khí mới mẻ.\n\nNhững phần trình diễn đặc biệt lần đầu ra mắt tại liveshow.\n\n	Khách mời: Ca sĩ Noo Phước Thịnh \n\n	Trải nghiệm đặc biệt: Không gian check-in mang concept riêng của “EM ĐỒNG Ý” cũng như khu trải nghiệm và những phần quà đặc biệt dành cho fan.', 'LSK01', 'HN'),
('SK15', 'EM XINH \"SAY HI\" CONCERT - ĐÊM 2', 800000, '2025-10-12', 'https://salt.tkbcdn.com/ts/ds/90/37/6e/cfa9510b1f648451290e0cf57b6fd548.jpg', 'Em Xinh “Say Hi” Concert – Đêm 2 sẽ diễn ra vào ngày 11/10/2025 tại sân vận động Mỹ Đình, Hà Nội, mang đến đại tiệc âm nhạc Gen Z với sân khấu ánh sáng 360 độ, loạt tiết mục viral như Run, Không đau nữa rồi, Vỗ tay. Lưu ý: Vé không hoàn trả, trẻ em dưới 7 tuổi không được tham gia, người dưới 16 tuổi cần có người lớn đi kèm.', 'LSK03', 'HN'),
('SK16', 'LULULOLA SHOW VICKY NHUNG & CHU THÚY QUỲNH | NGÀY MƯA ẤY', 460000, '2025-09-20', 'https://salt.tkbcdn.com/ts/ds/ee/86/df/261a5fd2fa0890c25f4c737103bbbe0c.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL'),
('SK17', 'ELAN & APLUS present: STEPHAN BODZIN', 650000, '2025-09-22', 'https://salt.tkbcdn.com/ts/ds/e3/06/ed/faff7ef36d95334510e51f7d337357d4.jpg', 'Không chỉ đơn thuần là một set nhạc, sự kiện kỷ niệm 2 năm của ELAN sẽ mang đến một “siêu phẩm” của âm thanh, năng lượng và cảm xúc. Hãy sẵn sàng đắm mình trong màn trình diễn live độc nhất vô nhị từ “nhạc trưởng” huyền thoại – Stephan Bodzin! Được mệnh danh là một trong những live performer xuất sắc nhất lịch sử nhạc điện tử, Stephan Bodzin luôn thiết lập những tiêu chuẩn mới cho nghệ thuật trình diễn và để lại dấu ấn sâu đậm trên các sân khấu, lễ hội âm nhạc điện tử lớn nhất thế giới. Suốt nhiều năm, ông vững vàng ở đỉnh cao của giới Techno, sánh vai cùng những huyền thoại như Solomun, Tale of Us, Carl Cox... Biểu diễn cùng Stephan Bodzin lần này còn có những tên tuổi đầy thực lực của làng Techno Việt: THUC, Mya, Heepsy và Tini Space. Từ 9 giờ tối, Chủ Nhật ngày 21 tháng 9, 2025 tại APLUS Hanoi, 78 Yên Phụ, Hà Nội.', 'LSK02', 'HN'),
('SK18', 'The Wandering Rose 02.08', 800000, '2025-08-02', 'https://salt.tkbcdn.com/ts/ds/c3/26/77/a3320dbc30151eb7de584ebf41a4c71f.jpg', 'The Wandering Rose – một đêm nhạc lãng mạn và đầy mộng mơ giữa thiên nhiên Ba Vì thơ mộng, nơi âm nhạc gặp gỡ cảm xúc, nơi mỗi nốt nhạc là một cánh hoa trôi lạc giữa miền ký ức. Với không gian tổ chức tại The Wandering Rose Villa, sự kiện hứa hẹn mang lại một trải nghiệm nghệ thuật trọn vẹn, tinh tế và khó quên. Điểm đặc sắc nhất của chương trình là sự kết hợp giữa bối cảnh nên thơ của núi rừng Ba Vì và những phần trình diễn đặc biệt đến từ Quang Hùng MasterD, Hà Nhi, Quân AP và Phạm Quỳnh Anh.', 'LSK01', 'HN'),
('SK19', 'LULULOLA SHOW TĂNG PHÚC | MONG MANH NỖI ĐAU', 570000, '2025-12-13', 'https://salt.tkbcdn.com/ts/ds/0f/f1/68/b57f2a3ecd1a9e516e8d1587c34fcc6e.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL'),
('SK20', 'LULULOLA SHOW PHAN MẠNH QUỲNH | TỪ BÀN TAY NÀY', 570000, '2025-12-06', 'https://salt.tkbcdn.com/ts/ds/57/04/b1/39315e2c790f67ecc938701754816d15.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL'),
('SK21', 'LULULOLA SHOW VĂN MAI HƯƠNG | ƯỚT LÒNG', 560000, '2025-09-13', 'https://salt.tkbcdn.com/ts/ds/fb/43/5c/52a43d006d2ec64b1dac74db8a62f72f.png', 'Lululola Show - Hơn cả âm nhạc, không gian lãng mạn đậm chất thơ Đà Lạt bao trọn hình ảnh thung lũng Đà Lạt, được ngắm nhìn khoảng khắc hoàng hôn thơ mộng đến khi Đà Lạt về đêm siêu lãng mạn, được giao lưu với thần tượng một cách chân thật và gần gũi nhất trong không gian ấm áp và không khí se lạnh của Đà Lạt. Tất cả sẽ  mang đến một đêm nhạc ấn tượng mà bạn không thể quên khi đến với Đà Lạt.', 'LSK01', 'DL'),
('SK22', 'DAY6 10th Anniversary Tour <The DECADE> in HO CHI MINH CITY', 2000000, '2025-10-18', 'https://salt.tkbcdn.com/ts/ds/c6/e1/c2/d3d41b377ea3d9a3cd18177d656516d7.jpg', 'Ngày 18/10/2025, ban nhạc Hàn Quốc DAY6 đã tổ chức concert đầu tiên tại Việt Nam – DAY6 10th Anniversary Tour <The DECADE> tại SECC Hall B2, Quận 7, TP.HCM, đánh dấu 10 năm hoạt động âm nhạc. Đây là lần đầu nhóm biểu diễn solo tại Việt Nam, thu hút đông đảo người hâm mộ My Days. Setlist trải dài từ các bản hit như Congratulations, Letting Go, I Loved You, Zombie đến những ca khúc mới trong album kỷ niệm như Dream Bus, Inside Out, Disco Day và Our Season.', 'LSK03', 'HCM');

-- --------------------------------------------------------

--
-- Table structure for table `sukien_loaive`
--

CREATE TABLE `sukien_loaive` (
  `MaSK` char(5) NOT NULL,
  `MaLoaiVe` char(5) NOT NULL,
  `GiaVe` float DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sukien_loaive`
--

INSERT INTO `sukien_loaive` (`MaSK`, `MaLoaiVe`, `GiaVe`, `SoLuong`) VALUES
('SK01', 'LV02', 1600000, 40),
('SK01', 'LV03', 560000, 180),
('SK01', 'LV04', 1000000, 80),
('SK02', 'LV03', 800000, 350),
('SK03', 'LV01', 3500000, 2000),
('SK03', 'LV02', 7300000, 3000),
('SK03', 'LV03', 5000000, 9000),
('SK03', 'LV04', 6500000, 6000),
('SK04', 'LV03', 400000, 300),
('SK05', 'LV01', 1039000, 10000),
('SK05', 'LV02', 2799000, 11000),
('SK05', 'LV03', 899000, 20000),
('SK05', 'LV04', 2399000, 5000),
('SK05', 'LV05', 5499000, 4000),
('SK06', 'LV01', 999000, 3600),
('SK06', 'LV02', 1999000, 1200),
('SK06', 'LV03', 699000, 7200),
('SK07', 'LV01', 3600000, 8250),
('SK07', 'LV02', 4500000, 2250),
('SK07', 'LV03', 1300000, 4500),
('SK08', 'LV01', 2800000, 500),
('SK08', 'LV02', 8000000, 500),
('SK08', 'LV03', 1300000, 5000),
('SK08', 'LV04', 10000000, 200),
('SK09', 'LV03', 755000, 7800),
('SK09', 'LV04', 620000, 3000),
('SK09', 'LV05', 717250, 1800),
('SK10', 'LV03', 755000, 7800),
('SK10', 'LV04', 620000, 3000),
('SK10', 'LV05', 717250, 1800),
('SK11', 'LV03', 500000, 420),
('SK11', 'LV04', 400000, 280),
('SK12', 'LV03', 6500000, 1200),
('SK13', 'LV02', 10000000, 80),
('SK14', 'LV02', 2100000, 200),
('SK14', 'LV03', 700000, 300),
('SK15', 'LV01', 800000, 15000),
('SK15', 'LV02', 2200000, 2000),
('SK15', 'LV03', 1200000, 8000),
('SK16', 'LV02', 1020000, 40),
('SK16', 'LV03', 460000, 100),
('SK17', 'LV03', 850000, 600),
('SK18', 'LV02', 3200000, 60),
('SK18', 'LV03', 800000, 120),
('SK19', 'LV02', 1700000, 40),
('SK19', 'LV03', 800000, 200),
('SK20', 'LV02', 1700000, 40),
('SK20', 'LV03', 800000, 200),
('SK21', 'LV02', 1230000, 30),
('SK21', 'LV03', 760000, 200),
('SK22', 'LV02', 4800000, 350),
('SK22', 'LV03', 2000000, 1200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diadiem`
--
ALTER TABLE `diadiem`
  ADD PRIMARY KEY (`MaDD`);

--
-- Indexes for table `loaisk`
--
ALTER TABLE `loaisk`
  ADD PRIMARY KEY (`MaloaiSK`);

--
-- Indexes for table `loaive`
--
ALTER TABLE `loaive`
  ADD PRIMARY KEY (`MLV`);

--
-- Indexes for table `sukien`
--
ALTER TABLE `sukien`
  ADD PRIMARY KEY (`MaSK`),
  ADD KEY `fk_maloaisk` (`MaLSK`),
  ADD KEY `fk_madd` (`MaDD`);

--
-- Indexes for table `sukien_loaive`
--
ALTER TABLE `sukien_loaive`
  ADD PRIMARY KEY (`MaSK`,`MaLoaiVe`),
  ADD KEY `fk_maloaive1` (`MaLoaiVe`);

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
-- Constraints for table `sukien_loaive`
--
ALTER TABLE `sukien_loaive`
  ADD CONSTRAINT `fk_maloaive1` FOREIGN KEY (`MaLoaiVe`) REFERENCES `loaive` (`MLV`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mask1` FOREIGN KEY (`MaSK`) REFERENCES `sukien` (`MaSK`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
