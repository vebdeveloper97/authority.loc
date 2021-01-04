SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Структура таблицы `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `districts`
--

INSERT INTO `districts` (`id`, `region_id`, `name`) VALUES
(15, 1, 'Amudaryo tumani'),
(16, 1, 'Beruniy tumani'),
(17, 1, 'Kegayli tumani'),
(18, 1, 'Qonliko‘l tumani'),
(19, 1, 'Qorao‘zak tumani'),
(20, 1, 'Qo‘ng‘irot tumani'),
(21, 1, 'Mo‘ynoq tumani'),
(22, 1, 'Nukus tumani'),
(23, 1, 'Nukus shahri'),
(24, 1, 'Taxtako‘pir tumani'),
(25, 1, 'To‘rtko‘l tumani'),
(26, 1, 'Xo‘jayli tumani'),
(27, 1, 'CHimboy tumani'),
(28, 1, 'SHumanay tumani'),
(29, 1, 'Ellikqal‘a tumani'),
(30, 2, 'Andijon shahri'),
(31, 2, 'Andijon tumani'),
(32, 2, 'Asaka tumani'),
(33, 2, 'Baliqchi tumani'),
(34, 2, 'Buloqboshi tumani'),
(35, 2, 'Bo‘z tumani'),
(36, 2, 'Jalaquduq tumani'),
(37, 2, 'Izbosgan tumani'),
(38, 2, 'Qorasuv shahri'),
(39, 2, 'Qo‘rg‘ontepa tumani'),
(40, 2, 'Marhamat tumani'),
(41, 2, 'Oltinko‘l tumani'),
(42, 2, 'Paxtaobod tumani'),
(43, 2, 'Ulug‘nor tumani'),
(44, 2, 'Xonabod tumani'),
(45, 2, 'Xo‘jaobod shahri'),
(46, 2, 'Shaxrixon tumani'),
(47, 3, 'Buxoro shahri'),
(48, 3, 'Buxoro tumani'),
(49, 3, 'Vobkent tumani'),
(50, 3, 'G‘ijduvon tumani'),
(51, 3, 'Jondor tumani'),
(52, 3, 'Kogon tumani'),
(53, 3, 'Kogon shahri'),
(54, 3, 'Qorako‘l tumani'),
(55, 3, 'Qorovulbozor tumani'),
(56, 3, 'Olot tumani'),
(57, 3, 'Peshku tumani'),
(58, 3, 'Romitan tumani'),
(59, 3, 'Shofirkon tumani'),
(60, 4, 'Arnasoy tumani'),
(61, 4, 'Baxmal tumani'),
(62, 4, 'G‘allaorol tumani'),
(63, 4, 'Do‘stlik tumani'),
(64, 4, 'Sh.Rashidov tumani'),
(65, 4, 'Jizzax shahri'),
(66, 4, 'Zarbdor tumani'),
(67, 4, 'Zafarobod tumani'),
(68, 4, 'Zomin tumani'),
(69, 4, 'Mirzacho‘l tumani'),
(70, 4, 'Paxtakor tumani'),
(71, 4, 'Forish tumani'),
(72, 4, 'Yangiobod tumani'),
(73, 5, 'G‘uzor tumani'),
(74, 5, 'Dehqonobod tumani'),
(75, 5, 'Qamashi tumani'),
(76, 5, 'Qarshi tumani'),
(77, 5, 'Qarshi shahri'),
(78, 5, 'Kasbi tumani'),
(79, 5, 'Kitob tumani'),
(80, 5, 'Koson tumani'),
(81, 5, 'Mirishkor tumani'),
(82, 5, 'Muborak tumani'),
(83, 5, 'Nishon tumani'),
(84, 5, 'Chiroqchi tumani'),
(85, 5, 'Shahrisabz tumani'),
(86, 5, 'Yakkabog‘ tumani'),
(87, 6, 'Zarafshon shahri'),
(88, 6, 'Karmana tumani'),
(89, 6, 'Qiziltepa tumani'),
(90, 6, 'Konimex tumani'),
(91, 6, 'Navbahor tumani'),
(92, 6, 'Navoiy shahri'),
(93, 6, 'Nurota tumani'),
(94, 6, 'Tomdi tumani'),
(95, 6, 'Uchquduq tumani'),
(96, 6, 'Xatirchi tumani'),
(97, 7, 'Kosonsoy tumani'),
(98, 7, 'Mingbuloq tumani'),
(99, 7, 'Namangan tumani'),
(100, 7, 'Namangan shahri'),
(101, 7, 'Norin tumani'),
(102, 7, 'Pop tumani'),
(103, 7, 'To‘raqo‘rg‘on tumani'),
(104, 7, 'Uychi tumani'),
(105, 7, 'Uchqo‘rg‘on tumani'),
(106, 7, 'Chortoq tumani'),
(107, 7, 'Chust tumani'),
(108, 7, 'Yangiqo‘rg‘on tumani'),
(109, 8, 'Bulung‘ur tumani'),
(110, 8, 'Jomboy tumani'),
(111, 8, 'Ishtixon tumani'),
(112, 8, 'Kattaqo‘rg‘on tumani'),
(113, 8, 'Kattaqo‘rg‘on shahri'),
(114, 8, 'Qo‘shrabot tumani'),
(115, 8, 'Narpay tumani'),
(116, 8, 'Nurabod tumani'),
(117, 8, 'Oqdaryo tumani'),
(118, 8, 'Payariq tumani'),
(119, 8, 'Pastarg‘om tumani'),
(120, 8, 'Paxtachi tumani'),
(121, 8, 'Samarqand tumani'),
(122, 8, 'Samarqand shahri'),
(123, 8, 'Toyloq tumani'),
(124, 8, 'Urgut tumani'),
(125, 9, 'Angor tumani'),
(126, 9, 'Boysun tumani'),
(127, 9, 'Denov tumani'),
(128, 9, 'Jarqo‘rg‘on tumani'),
(129, 9, 'Qiziriq tumani'),
(130, 9, 'Qo‘mqo‘rg‘on tumani'),
(131, 9, 'Muzrabot tumani'),
(132, 9, 'Oltinsoy tumani'),
(133, 9, 'Sariosiy tumani'),
(134, 9, 'Termiz tumani'),
(135, 9, 'Termiz shahri'),
(136, 9, 'Uzun tumani'),
(137, 9, 'Sherobod tumani'),
(138, 9, 'Sho‘rchi tumani'),
(139, 10, 'Boyovut tumani'),
(140, 10, 'Guliston tumani'),
(141, 10, 'Guliston shahri'),
(142, 10, 'Mirzaobod tumani'),
(143, 10, 'Oqoltin tumani'),
(144, 10, 'Sayxunobod tumani'),
(145, 10, 'Sardoba tumani'),
(146, 10, 'Sirdaryo tumani'),
(147, 10, 'Xavos tumani'),
(148, 10, 'Shirin shahri'),
(149, 10, 'Yangier shahri'),
(150, 11, 'Angiren shahri'),
(151, 11, 'Bekabod tumani'),
(152, 11, 'Bekabod shahri'),
(153, 11, 'Bo‘ka tumani'),
(154, 11, 'Bo‘stonliq tumani'),
(155, 11, 'Zangiota tumani'),
(156, 11, 'Qibray tumani'),
(157, 11, 'Quyichirchiq tumani'),
(158, 11, 'Oqqo‘rg‘on tumani'),
(159, 11, 'Olmaliq shahri'),
(160, 11, 'Ohangaron tumani'),
(161, 11, 'Parkent tumani'),
(162, 11, 'Piskent tumani'),
(163, 11, 'O‘rtachirchiq tumani'),
(164, 11, 'Chinoz tumani'),
(165, 11, 'Chirchiq shahri'),
(166, 11, 'Yuqorichirchiq tumani'),
(167, 11, 'Yangiyo‘l tumani'),
(168, 12, 'Beshariq tumani'),
(169, 12, 'Bog‘dod tumani'),
(170, 12, 'Buvayda tumani'),
(171, 12, 'Dang‘ara tumani'),
(172, 12, 'Yozyovon tumani'),
(173, 12, 'Quva tumani'),
(174, 12, 'Quvasoy shahri'),
(175, 12, 'Qo‘qon shahri'),
(176, 12, 'Qo‘shtepa tumani'),
(177, 12, 'Marg‘ilon shahri'),
(178, 12, 'Oltiariq tumani'),
(179, 12, 'Rishton tumani'),
(180, 12, 'So‘x tumani'),
(181, 12, 'Toshloq tumani'),
(182, 12, 'Uchko‘prik tumani'),
(183, 12, 'O‘zbekiston tumani'),
(184, 12, 'Farg‘ona tumani'),
(185, 12, 'Farg‘ona shahri'),
(186, 12, 'Furqat tumani'),
(187, 13, 'Bog‘ot tumani'),
(188, 13, 'Gurlan tumani'),
(189, 13, 'Qo‘shko‘pir tumani'),
(190, 13, 'Urganch tumani'),
(191, 13, 'Urganch shahri'),
(192, 13, 'Xiva tumani'),
(193, 13, 'Xazarasp tumani'),
(194, 13, 'Xonqa tumani'),
(195, 13, 'Shavot tumani'),
(196, 13, 'Yangiariq tumani'),
(197, 13, 'Yangibozor tumani'),
(198, 14, 'Bektimer tumani'),
(199, 14, 'M.Ulug‘bek tumani'),
(200, 14, 'Mirobod tumani'),
(201, 14, 'Olmazor tumani'),
(202, 14, 'Sergeli tumani'),
(203, 14, 'Uchtepa tumani'),
(204, 14, 'Yashnobod tumani'),
(205, 14, 'Chilonzor tumani'),
(206, 14, 'Shayxontohur tumani'),
(207, 14, 'Yunusobod tumani'),
(208, 14, 'Yakkasaroy tumani'),
(209, 1, 'Taxiatosh shahri'),
(210, 2, 'Asaka shahri'),
(211, 9, 'Bandixon tumani'),
(212, 11, 'Ohangaron shahri'),
(213, 11, 'Yangiyo‘l shahri'),
(215, 11, 'Toshkent tumani');

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Qoraqalpog‘iston Respublikasi'),
(2, 'Andijon viloyati'),
(3, 'Buxoro viloyati'),
(4, 'Jizzax viloyati'),
(5, 'Qashqadaryo viloyati'),
(6, 'Navoiy viloyati'),
(7, 'Namangan viloyati'),
(8, 'Samarqand viloyati'),
(9, 'Surxandaryo viloyati'),
(10, 'Sirdaryo viloyati'),
(11, 'Toshkent viloyati'),
(12, 'Farg‘ona viloyati'),
(13, 'Xorazm viloyati'),
(14, 'Toshkent shahri');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;


--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
