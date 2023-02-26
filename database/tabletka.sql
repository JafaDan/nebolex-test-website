-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Фев 27 2023 г., 01:33
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tabletka`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ADMIN`
--

CREATE TABLE `ADMIN` (
  `ID` int NOT NULL,
  `ADLOGIN` varchar(20) NOT NULL,
  `ADPASSWORD` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `ADMIN`
--

INSERT INTO `ADMIN` (`ID`, `ADLOGIN`, `ADPASSWORD`) VALUES
(1, 'admin', 'password');

-- --------------------------------------------------------

--
-- Структура таблицы `ANALYZES`
--

CREATE TABLE `ANALYZES` (
  `ID` int NOT NULL,
  `USER_ID` varchar(11) NOT NULL,
  `CREATION_TIME` int NOT NULL,
  `CHANGE_TIME` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `ANALYZES`
--

INSERT INTO `ANALYZES` (`ID`, `USER_ID`, `CREATION_TIME`, `CHANGE_TIME`) VALUES
(41, '88005553535', 1503938953, 1503938953),
(60, '79299090807', 1503930208, 1503934114);

-- --------------------------------------------------------

--
-- Структура таблицы `ANALYZES_DATA`
--

CREATE TABLE `ANALYZES_DATA` (
  `ID` int NOT NULL,
  `ANALYZE_ID` varchar(11) NOT NULL,
  `COMPONENT_ID` varchar(3) NOT NULL,
  `DATA` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `ANALYZES_DATA`
--

INSERT INTO `ANALYZES_DATA` (`ID`, `ANALYZE_ID`, `COMPONENT_ID`, `DATA`) VALUES
(71, '53', '118', 1),
(72, '53', '89', 1),
(73, '53', '93', 1),
(74, '53', '94', 1000),
(75, '53', '120', 1),
(76, '53', '96', 1),
(77, '53', '99', 1),
(78, '53', '101', 11),
(79, '53', '121', 1),
(82, '53', '107', 1),
(84, '53', '113', 1),
(85, '53', '116', 1),
(88, '53', '119', 1),
(89, '53', '92', 1),
(91, '53', '97', 1),
(92, '53', '98', 1),
(94, '53', '102', 1),
(97, '53', '105', 1),
(98, '53', '124', 1),
(99, '53', '125', 1),
(103, '53', '126', 1),
(105, '53', '114', 1),
(106, '53', '115', 1),
(107, '61', '85', 2),
(108, '61', '117', 3),
(109, '61', '118', 3),
(110, '61', '89', 3),
(111, '61', '119', 3),
(112, '61', '92', 3),
(113, '61', '93', 3),
(114, '61', '94', 3),
(115, '61', '95', 3),
(116, '61', '120', 3),
(117, '61', '96', 3),
(118, '61', '97', 3),
(119, '61', '98', 3),
(120, '61', '99', 3),
(121, '61', '100', 3),
(122, '61', '101', 3),
(123, '61', '102', 3),
(124, '61', '103', 3),
(125, '61', '121', 3),
(126, '61', '104', 3),
(127, '61', '105', 3),
(128, '61', '106', 3),
(129, '61', '124', 3),
(130, '61', '123', 3),
(131, '61', '125', 3),
(132, '61', '109', 3),
(133, '61', '107', 3),
(134, '61', '108', 3),
(135, '61', '110', 3),
(136, '61', '111', 3),
(137, '61', '126', 3),
(138, '61', '112', 3),
(139, '61', '113', 3),
(140, '61', '114', 3),
(141, '61', '115', 3),
(142, '61', '116', 3),
(143, '62', '85', 10.3825),
(144, '62', '117', 0.204),
(145, '62', '118', 0.0912),
(146, '62', '89', 0.3509),
(147, '62', '119', 0.258),
(148, '62', '92', 0.1188),
(149, '62', '93', 0.0027),
(150, '62', '94', 28.2811),
(151, '62', '95', 2.2254),
(152, '62', '120', 0.0405),
(153, '62', '96', 270.078),
(154, '62', '97', 678.624),
(155, '62', '98', 0.816),
(156, '62', '99', 5.5491),
(157, '62', '100', 0.0832),
(158, '62', '101', 45.3122),
(159, '62', '102', 0.3993),
(160, '62', '103', 14.4602),
(161, '62', '121', 0.0431),
(162, '62', '104', 450.065),
(163, '62', '105', 0.5869),
(164, '62', '106', 0.2753),
(165, '62', '124', 0.7886),
(166, '62', '123', 1.5803),
(167, '62', '125', 0.556),
(168, '62', '109', 1.3802),
(169, '62', '107', 0.4127),
(170, '62', '108', 0.04),
(171, '62', '110', 2.9343),
(172, '62', '111', 0.5985),
(173, '62', '126', 0.0094),
(174, '62', '112', 164.164),
(175, '62', '113', 0.6114),
(176, '62', '114', 0.04),
(177, '62', '115', 160.058),
(178, '62', '116', 0.0865);

-- --------------------------------------------------------

--
-- Структура таблицы `COMPONENTS`
--

CREATE TABLE `COMPONENTS` (
  `ID` int NOT NULL,
  `COMP_NAME` varchar(20) NOT NULL,
  `COMP_CODE` varchar(3) NOT NULL,
  `COMP_PRIORITY` int NOT NULL,
  `COMP_MIN` float NOT NULL,
  `COMP_MAX` float NOT NULL,
  `COMP_CRIT_MIN` float NOT NULL,
  `COMP_CRIT_MAX` float NOT NULL,
  `COMP_TOXIC` varchar(3) NOT NULL DEFAULT 'Нет'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `COMPONENTS`
--

INSERT INTO `COMPONENTS` (`ID`, `COMP_NAME`, `COMP_CODE`, `COMP_PRIORITY`, `COMP_MIN`, `COMP_MAX`, `COMP_CRIT_MIN`, `COMP_CRIT_MAX`, `COMP_TOXIC`) VALUES
(85, 'Алюминий', 'Al', 120, 7, 20, 5, 25, 'Нет'),
(89, 'Бор', 'B', 123, 0.1, 3.5, 0.08, 4, 'Нет'),
(92, 'Галий', 'Ga', 124, 0, 0.01, 0, 0.015, 'Нет'),
(93, 'Германий', 'Ge', 126, 0, 0.0003, 0, 0.0005, 'Нет'),
(94, 'Железо', 'Fe', 106, 15, 35, 12, 50, 'Нет'),
(95, 'Йод', 'II', 102, 0.3, 9, 0.2, 12, 'Нет'),
(96, 'Калий', 'K', 105, 250, 1000, 200, 1200, 'Нет'),
(97, 'Кальций', 'Ca', 101, 300, 1200, 250, 1600, 'Нет'),
(98, 'Кобальт', 'Co', 107, 0.01, 1, 0.008, 1.2, 'Нет'),
(99, 'Кремний', 'Si', 116, 5, 35, 4, 50, 'Нет'),
(100, 'Литий', 'Li', 111, 0.018, 0.5, 0.015, 0.7, 'Нет'),
(101, 'Магний', 'Mg', 100, 25, 50, 20, 70, 'Нет'),
(102, 'Марганец', 'Mn', 112, 0.25, 1.3, 0.2, 1.5, 'Нет'),
(103, 'Медь', 'Cu', 109, 10, 30, 8, 45, 'Нет'),
(104, 'Натрий', 'Na', 108, 280, 1000, 240, 1200, 'Нет'),
(105, 'Никель', 'Ni', 117, 0.005, 0.7, 0.004, 1, 'Нет'),
(106, 'Олово', 'Sn', 114, 0.3, 2.3, 0.24, 2.8, 'Нет'),
(107, 'Серебро', 'Ag', 115, 0.005, 0.2, 0.004, 0.25, 'Нет'),
(108, 'Скандий', 'Sc', 125, 0.0001, 0.05, 0.00008, 0.08, 'Нет'),
(109, 'Селен', 'Se', 104, 0.8, 1.5, 0.6, 2, 'Нет'),
(110, 'Стронций', 'Sr', 113, 0.25, 8, 0.2, 10, 'Нет'),
(111, 'Сурьма', 'Sb', 122, 0.005, 1, 0.004, 1.2, 'Нет'),
(112, 'Фосфор', 'P', 118, 100, 250, 80, 300, 'Нет'),
(113, 'Хром', 'Cr', 110, 0.5, 1.5, 0.4, 1.8, 'Нет'),
(114, 'Цезий', 'Cs', 121, 0.001, 0.05, 0.0008, 0.08, 'Нет'),
(115, 'Цинк', 'Zn', 103, 120, 250, 100, 300, 'Нет'),
(116, 'Цирконий', 'Zr', 119, 0, 1.5, 0, 2, 'Нет'),
(117, 'Барий', 'Ba', 5, 0.2, 1, 0.1, 1.2, 'Да'),
(118, 'Берилий', 'Be', 0, 0, 0, 0, 0, 'Да'),
(119, 'Ванадий', 'V', 5, 0.005, 0.5, 0.003, 0.7, 'Да'),
(120, 'Кадмий', 'Cd', 3, 0.001, 0.1, 0.0007, 0.12, 'Да'),
(121, 'Мышьяк', 'As', 4, 0.005, 0.1, 0.003, 0.12, 'Да'),
(123, 'Рубидий', 'Rb', 5, 0.5, 1.5, 0.3, 1.7, 'Да'),
(124, 'Ртуть', 'Hg', 1, 0.05, 0.2, 0.03, 0.3, 'Да'),
(125, 'Свинец', 'Pb', 2, 0.2, 1.3, 0.1, 1.5, 'Да'),
(126, 'Титан', 'Ti', 5, 0, 0.01, 0, 0.012, 'Да');

-- --------------------------------------------------------

--
-- Структура таблицы `FORMULS`
--

CREATE TABLE `FORMULS` (
  `ID` int NOT NULL,
  `FORM_CODE_1` int NOT NULL,
  `FORM_CODE_2` int NOT NULL,
  `FORM_PRIORITY` int NOT NULL,
  `FORM_MIN` float NOT NULL,
  `FORM_MAX` float NOT NULL,
  `FORM_CRIT_MIN` float NOT NULL,
  `FORM_CRIT_MAX` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `FORMULS`
--

INSERT INTO `FORMULS` (`ID`, `FORM_CODE_1`, `FORM_CODE_2`, `FORM_PRIORITY`, `FORM_MIN`, `FORM_MAX`, `FORM_CRIT_MIN`, `FORM_CRIT_MAX`) VALUES
(15, 85, 99, 120, 0.2, 5, 0.18, 5.5),
(16, 97, 96, 104, 1, 9, 0.8, 12),
(17, 97, 101, 101, 1, 9, 0.8, 12),
(18, 97, 112, 103, 1, 1.6, 0.9, 1.7),
(19, 94, 103, 119, 0.33, 1, 0.28, 1.3),
(20, 96, 101, 100, 5, 25, 4, 40),
(21, 104, 96, 117, 1, 4, 0.8, 5),
(22, 104, 101, 102, 1, 4, 0.7, 6),
(23, 112, 102, 16, 80, 1000, 60, 1200),
(24, 115, 103, 110, 1, 8.9, 0.9, 10),
(25, 97, 125, 4, 230, 4500, 180, 6000),
(26, 98, 120, 8, 0.1, 50, 0.07, 75),
(27, 103, 120, 10, 100, 1500, 70, 2000),
(28, 103, 125, 5, 50, 600, 40, 800),
(29, 94, 124, 2, 75, 700, 50, 1000),
(30, 94, 125, 6, 11.5, 175, 7, 250),
(31, 96, 117, 15, 500, 3000, 400, 4000),
(32, 96, 123, 18, 170, 1200, 150, 1500),
(33, 96, 126, 13, 6000, 250000, 5000, 300000),
(34, 101, 125, 3, 56, 1000, 40, 1200),
(35, 112, 125, 7, 500, 5000, 400, 7000),
(36, 109, 121, 11, 0.8, 300, 0.6, 450),
(37, 109, 117, 14, 1.6, 7.5, 1.2, 10),
(38, 109, 124, 0, 4, 30, 3, 45),
(39, 109, 126, 12, 0.55, 150, 0.4, 200),
(40, 119, 102, 17, 0.004, 2, 0.003, 2.5),
(41, 115, 120, 9, 1300, 12500, 1000, 15000),
(42, 115, 124, 1, 650, 5000, 500, 7000),
(43, 113, 119, 19, 0.8, 300, 0.6, 450),
(44, 103, 107, 121, 50, 6000, 40, 6500),
(45, 103, 105, 122, 15, 600, 12, 700),
(46, 103, 109, 112, 6.6, 37.5, 6, 45),
(47, 94, 98, 118, 365, 3150, 340, 3500),
(48, 94, 95, 107, 22, 116, 18, 130),
(49, 95, 98, 106, 0.3, 68, 0.25, 80),
(50, 95, 109, 105, 0.85, 27, 0.7, 30),
(51, 96, 98, 115, 3250, 54000, 3000, 60000),
(52, 96, 100, 116, 5000, 120000, 4000, 150000),
(53, 109, 107, 113, 4, 300, 3.5, 360),
(54, 109, 106, 114, 0.5, 5, 0.4, 7),
(55, 115, 113, 109, 90, 625, 70, 700),
(56, 115, 105, 108, 185, 5000, 150, 6000),
(57, 115, 106, 111, 60, 830, 50, 900);

-- --------------------------------------------------------

--
-- Структура таблицы `USERS`
--

CREATE TABLE `USERS` (
  `ID` int NOT NULL,
  `LOGIN` varchar(20) NOT NULL,
  `PASSWORD` varchar(15) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `SUBNAME` varchar(20) NOT NULL,
  `OTCH` varchar(20) NOT NULL,
  `DATE_REG` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `USERS`
--

INSERT INTO `USERS` (`ID`, `LOGIN`, `PASSWORD`, `NAME`, `SUBNAME`, `OTCH`, `DATE_REG`) VALUES
(3, '89299090807', '123', 'Иван', 'Иванович', 'Иванов', 1503938953),
(5, '89995556644', '123', 'Петров', 'Петр', 'Петрович', 1503938953);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `ANALYZES`
--
ALTER TABLE `ANALYZES`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `ANALYZES_DATA`
--
ALTER TABLE `ANALYZES_DATA`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `COMPONENTS`
--
ALTER TABLE `COMPONENTS`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `FORMULS`
--
ALTER TABLE `FORMULS`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ADMIN`
--
ALTER TABLE `ADMIN`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `ANALYZES`
--
ALTER TABLE `ANALYZES`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT для таблицы `ANALYZES_DATA`
--
ALTER TABLE `ANALYZES_DATA`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT для таблицы `COMPONENTS`
--
ALTER TABLE `COMPONENTS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT для таблицы `FORMULS`
--
ALTER TABLE `FORMULS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `USERS`
--
ALTER TABLE `USERS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
