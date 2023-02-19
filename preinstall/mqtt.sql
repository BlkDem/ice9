-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 19 2023 г., 13:18
-- Версия сервера: 10.3.38-MariaDB
-- Версия PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mqtt`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `CheckSensorParams` (IN `_sensor_idx` INT)   BEGIN
  DECLARE _sensor_id INT;
  select sensors.sensor_id from mqtt.sensors where (domoticz_idx=_sensor_idx) into _sensor_id;
  select sensor_params.param_name, sensor_params.param_value, sensor_params.param_cmd from mqtt.sensor_params 
  where (sensor_id = _sensor_id) and (param_dir="IN") and (param_type!=2);
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `check_inactive` ()   BEGIN

select count(dblog.message_id_by_idx._id), dblog.message_id_by_idx.sensor_id from dblog.message_id_by_idx
  where sensor_id not in 
  (select dblog.message_id_by_idx.sensor_id from mqtt.sensors, dblog.message_id_by_idx 
    where 
    (dblog.message_id_by_idx.sensor_id=sensors.sensor_id and (dblog.message_id_by_idx.datetime > date_sub(now(), INTERVAL 1 HOUR)) or (fl_active=1) and (fl_enabled=0))
    group by dblog.message_id_by_idx.sensor_id) group by sensor_id;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `pager` (IN `table_name` VARCHAR(32), IN `list_count` INT, IN `page_num` INT)  NO SQL BEGIN
  DECLARE total, p_offset INT;
  DECLARE n_table VARCHAR(32);

  SET n_table =CONCAT('SELECT * FROM ',table_name );
    PREPARE stmt3 FROM n_table;
    EXECUTE stmt3;
    DEALLOCATE PREPARE stmt3;
  SET p_offset = list_count*page_num;
  select count(*) from n_table INTO total;
  SELECT total, mqtt.* FROM n_table LIMIT list_count OFFSET p_offset;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `set_active` (`SID` INT, `Active` INT)   BEGIN
  update mqtt.sensors set fl_active = Active where sensor_id=SID;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `set_armed` (`SID` INT, `Armed` INT)   BEGIN
  update mqtt.sensors set fl_armed = Armed where sensor_id=SID;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `set_enabled` (`SID` INT, `Enabled` INT)   BEGIN
  update mqtt.sensors set fl_enabled = Enabled where sensor_id=SID;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `set_payed` (`SID` INT, `Payed` INT)   BEGIN
  update mqtt.sensors set fl_payed = Payed where sensor_id=SID;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `statistic` ()   BEGIN
  DECLARE sensors_log_count INT DEFAULT 0;
  DECLARE sensors_log_last DATETIME;
  DECLARE sensors_count INT DEFAULT 0;   
  DECLARE owners_count INT DEFAULT 0;   
  DECLARE db_size DOUBLE DEFAULT 0;
  DECLARE dblog_size DOUBLE DEFAULT 0;
  DECLARE topics_count DOUBLE DEFAULT 0;
  
 
  SELECT 
  ROUND(SUM(data_length + index_length) / 1024 / 1024 , 2)
  FROM information_schema.TABLES
  WHERE table_schema = 'mqtt'
  GROUP BY table_schema into db_size;
  SELECT 
  ROUND(SUM(data_length + index_length) / 1024 / 1024 , 2)
  FROM information_schema.TABLES
  WHERE table_schema = 'dblog'
  GROUP BY table_schema into dblog_size;
 
  SELECT count(dblog.message_id_by_idx._id) into sensors_log_count  FROM dblog.message_id_by_idx; 
  SELECT max(dblog.messages.datetime) into sensors_log_last   FROM dblog.messages; 
  SELECT count(mqtt.sensors.sensor_id) into sensors_count FROM sensors; 
  SELECT count(mqtt.sensor_owners.owner_id) into owners_count FROM sensor_owners; 
  SELECT count(dblog.mqtt_topics.id) into topics_count FROM dblog.mqtt_topics; 
  
  select db_size, sensors_log_count, sensors_log_last, sensors_count, owners_count, dblog_size, topics_count; 
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `statistic1` ()  NO SQL BEGIN
  DECLARE sensors_log_count INT DEFAULT 0;
  DECLARE sensors_log_last DATETIME;
  DECLARE sensors_count INT DEFAULT 0;   
  DECLARE owners_count INT DEFAULT 0;   
  DECLARE db_size INT DEFAULT 0;
 
  SELECT 
  ROUND(SUM(data_length + index_length) / 1024 / 1024 , 2)
  FROM information_schema.TABLES
  WHERE table_schema = 'mqtt'
  GROUP BY table_schema into db_size;
 
  SELECT count(mqtt.sensors_log.log_id) into sensors_log_count  FROM sensors_log; 
  SELECT max(mqtt.sensors_log.log_date) into sensors_log_last   FROM sensors_log; 
  SELECT count(mqtt.sensors.sensor_id) into sensors_count FROM sensors; 
  SELECT count(mqtt.sensor_owners.owner_id) into owners_count FROM sensor_owners; 
  select db_size, sensors_log_count, sensors_log_last, sensors_count, owners_count; 
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `UpdateSensorParam` (IN `_sensor_idx` INT, IN `_param_name` VARCHAR(32), IN `_param_value` VARCHAR(128))   BEGIN
  DECLARE _sensor_id INT;
  select sensors.sensor_id from mqtt.sensors where (domoticz_idx=_sensor_idx) into _sensor_id;
  update mqtt.sensor_params 
  set param_value = _param_value
  where (sensor_id = _sensor_id) and (param_name = _param_name);
  insert into mqtt.sensors_log (log_date, log_service, log_data, sensor_id, log_sensor_value, log_sensor_name) values (now(), "device log", "log", _sensor_id, _param_value, _param_name);
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `UpdateSensorParamID` (IN `_param_id` INT, IN `_param_value` VARCHAR(128))   BEGIN
  update mqtt.sensor_params 
  set param_value = _param_value
  where param_id = _param_id;
END$$

CREATE DEFINER=`modx_usr`@`localhost` PROCEDURE `_sensors_log` (IN `sid` INT, IN `rec_lim` INT, IN `_page` INT, `_date` DATETIME)   BEGIN
  DECLARE sensors_log_count INT DEFAULT 0;
  DECLARE sensors_log_count_filtered INT DEFAULT 0;
  DECLARE pages INT DEFAULT 0;
  DECLARE current_page INT DEFAULT 0;
  DECLARE rec_offset INT DEFAULT 0;
  DECLARE filter_date DATETIME DEFAULT NOW();
  SELECT count(dblog.message_id_by_idx._id) into sensors_log_count_filtered  FROM dblog.message_id_by_idx where ((sensor_id=sid) and ((datetime<=(DATE_ADD(_date, INTERVAL 1 DAY))) and (datetime>=(DATE(_date))))) ;
  SELECT count(dblog.message_id_by_idx._id) into sensors_log_count  FROM dblog.message_id_by_idx where sensor_id=sid;
  SET pages = (sensors_log_count_filtered DIV rec_lim);
  if ((sensors_log_count_filtered % rec_lim) > 0) then
    SET pages = pages + 1;  
  end if;
  SET rec_offset = _page*rec_lim;
  if (sensors_log_count_filtered<rec_offset) then SET rec_offset = sensors_log_count_filtered - (sensors_log_count_filtered % rec_lim);
    end if;
  SET current_page = rec_offset DIV rec_lim;
  
  SELECT pages, current_page, sensors_log_count, sensors_log_count_filtered, _id, datetime, param_name, payload, sensor_id, sensor_name
    from dblog.message_id_by_idx where ((sensor_id=sid) and (message_id_by_idx.datetime<=(DATE_ADD(_date, INTERVAL 1 DAY))) and ((message_id_by_idx.datetime>=(DATE(_date))))) order by message_id_by_idx.datetime desc limit rec_lim offset rec_offset ; 

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `img_id` int(11) NOT NULL,
  `img_name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `img_path` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `img_type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`img_id`, `img_name`, `img_path`, `img_type`) VALUES
(2, 'Move', 'move.png', 0),
(3, 'THP', 'thp.png', 0),
(4, 'End', 'end.png', 0),
(5, 'Relay 4', 'relay4.png', 0),
(6, 'Relay 2', 'relay2.png', 0),
(7, 'Relay 1', 'relay1.png', 0),
(8, 'LUX', 'lux.png', 0),
(9, 'CO', 'co.png', 0),
(10, 'CO2', 'co2.png', 0),
(11, 'Water', 'water.png', 0),
(12, 'Level', 'level.png', 0),
(13, 'RGB', 'RGB.png', 0),
(57, 'GSM', '2f028c5f46621982bb86df8f9fdde77e_100px.png', 0),
(84, 'default', 'a4b40aa936649551dc245b21abec251b_100px.jpg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `message_types`
--

CREATE TABLE `message_types` (
  `mt_id` int(11) NOT NULL,
  `mt_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Типы сообщений';

--
-- Дамп данных таблицы `message_types`
--

INSERT INTO `message_types` (`mt_id`, `mt_name`) VALUES
(1, 'Ошибка'),
(2, 'Уведомление'),
(3, 'Обслуживание');

-- --------------------------------------------------------

--
-- Структура таблицы `owner_rooms`
--

CREATE TABLE `owner_rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(45) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `room_desc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `owner_rooms`
--

INSERT INTO `owner_rooms` (`room_id`, `room_name`, `owner_id`, `room_desc`) VALUES
(2, 'room2', 1, 'hall'),
(3, 'room3', 1, 'kitchen'),
(4, 'room1_user45', 7, 'hall'),
(6, 'room4', 1, 'bathroom');

-- --------------------------------------------------------

--
-- Структура таблицы `param_values`
--

CREATE TABLE `param_values` (
  `pv_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `pv_value` varchar(45) NOT NULL,
  `param_value` varchar(45) NOT NULL,
  `pv_operator` varchar(16) NOT NULL DEFAULT 'EQ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `param_values`
--

INSERT INTO `param_values` (`pv_id`, `param_id`, `pv_value`, `param_value`, `pv_operator`) VALUES
(2, 29, 'test111', '1', 'EQ'),
(3, 31, 'Охрана', '0', 'EQ'),
(9, 31, 'Тревога!', '1', 'EQ'),
(13, 29, 'value5_12', '1', 'NOT EQ'),
(14, 25, 'Есть движение', '1', 'EQ'),
(16, 25, 'Нет движения', '0', 'EQ'),
(17, 24, 'Закрыта', '0', 'EQ'),
(18, 24, 'Открыта', '1', 'EQ'),
(19, 151, 'Выключен', '0', 'EQ'),
(20, 151, 'Включен', '1', 'EQ'),
(28, 26, 'Отсутствует', '0', 'EQ'),
(29, 26, 'датчик сработал!', '1', 'EQ'),
(30, 53, 'Выключен', '0', 'EQ'),
(31, 53, 'Включен', '1', 'EQ'),
(32, 39, 'Включен', '0', 'EQ'),
(33, 39, 'Выключен', '1', 'EQ'),
(35, 28, 'Отсутствует', '0', 'EQ'),
(36, 28, 'обнаружена протечка!', '1', 'EQ'),
(37, 58, 'выкл', '0', 'EQ'),
(38, 58, 'вкл', '1', 'EQ'),
(39, 32, 'Перегрев', '40', 'EQ');

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE `schedule` (
  `sch_id` int(11) NOT NULL,
  `sensor_id` int(11) NOT NULL,
  `mt_id` int(11) NOT NULL,
  `sch_newdate` datetime NOT NULL,
  `sch_ready` int(11) NOT NULL,
  `sch_desc` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `schedule`
--

INSERT INTO `schedule` (`sch_id`, `sensor_id`, `mt_id`, `sch_newdate`, `sch_ready`, `sch_desc`) VALUES
(1, 13, 2, '2021-03-11 14:18:48', 0, 'test message');

-- --------------------------------------------------------

--
-- Структура таблицы `sensors`
--

CREATE TABLE `sensors` (
  `sensor_id` int(11) NOT NULL,
  `sensor_name` varchar(255) DEFAULT NULL,
  `sensor_type_id` int(11) NOT NULL,
  `sensor_user_id` int(11) NOT NULL,
  `domoticz_idx` int(11) NOT NULL,
  `fl_enabled` int(11) DEFAULT NULL,
  `fl_payed` int(11) DEFAULT NULL,
  `fl_active` int(11) DEFAULT NULL,
  `fl_armed` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `sensor_pin` int(11) DEFAULT NULL,
  `img_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sensors`
--

INSERT INTO `sensors` (`sensor_id`, `sensor_name`, `sensor_type_id`, `sensor_user_id`, `domoticz_idx`, `fl_enabled`, `fl_payed`, `fl_active`, `fl_armed`, `room_id`, `sensor_pin`, `img_id`) VALUES
(1, 'GSM Module', 25, 1, 17, 0, 1, 0, 0, NULL, 1234, 57),
(2, 'Кухня - движение', 19, 1, 12, 1, 0, 0, 0, NULL, 1234, 2),
(5, 'Датчик дыма - кухня', 10, 1, 5, 1, 0, 0, 0, NULL, 1234, 9),
(10, 'sens1_user45', 3, 2, 678, 1, 0, 0, 0, NULL, 1234, 9),
(13, 'Гараж - климат', 22, 2, 16, 1, 0, 1, 1, NULL, 1234, 3),
(15, 'Входная дверь', 18, 1, 111, 1, 0, 0, 1, NULL, 1234, 4),
(16, 'Датчик протечки - ванная', 26, 1, 112, 1, 1, 0, 1, NULL, 1234, 11),
(17, 'Датчик протечки - туалет', 26, 1, 113, 0, 1, 0, 1, NULL, 1234, 11),
(18, 'Subwoofer1', 27, 1, 7, 1, 0, 1, 0, NULL, 1234, 7),
(19, 'Реле 2', 28, 1, 19, 0, 0, 0, 0, NULL, 1234, 6),
(20, 'Освещение зал', 29, 1, 20, 1, 1, 0, 0, NULL, 1234, 5),
(21, 'esp01_cub', 27, 1, 21, 1, 0, 0, 0, NULL, 1234, 7),
(22, 'Esp01_Cube2', 27, 1, 22, 0, 0, 0, 0, NULL, 1234, 7),
(23, 'Дверь - кладовка', 18, 1, 23, 0, 0, 0, 0, NULL, 1234, 4),
(24, 'relay 2-ch', 28, 1, 24, 1, 0, 0, 0, NULL, 1234, 6),
(25, 'Гирлянда', 27, 1, 7, 0, 0, 0, 0, NULL, 1234, 7),
(26, 'Движение - зал', 23, 1, 111111, 0, 0, 0, 0, NULL, 1234, 2),
(27, 'Уровень освещенности', 12, 1, 11, 1, 0, 1, 0, NULL, 1234, 8),
(28, 'Deepsleep test', 27, 1, 28, 0, 0, 1, 0, NULL, 1234, 12),
(29, 'Кулер верхний 26', 30, 2, 26, 1, 0, 1, 0, NULL, 1234, 13),
(30, 'LedStrip_U1', 30, 1, 37, 1, 0, 1, 1, NULL, 1234, 13),
(33, 'Test1000', 27, 1, 1030, 1, 0, 0, 0, NULL, 1234, 7),
(80, 'Sonoff RF', 36, 2, 15, 1, NULL, 1, 1, NULL, NULL, 4),
(81, 'Корпус Umolab Umbrella (U36)', 30, 2, 36, 1, NULL, 1, 1, NULL, NULL, 13),
(82, 'Реле (два перекидных контакта)', 28, 2, 115, 1, NULL, 1, 1, NULL, NULL, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `sensor_owners`
--

CREATE TABLE `sensor_owners` (
  `owner_id` int(11) NOT NULL,
  `owner_name` varchar(45) DEFAULT NULL,
  `owner_type` int(11) DEFAULT NULL,
  `owner_desc` varchar(255) DEFAULT NULL,
  `owner_regdate` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sensor_owners`
--

INSERT INTO `sensor_owners` (`owner_id`, `owner_name`, `owner_type`, `owner_desc`, `owner_regdate`, `user_id`) VALUES
(1, 'blkdem', 1, 'server admin', '2016-01-16 19:00:00', 1),
(2, 'owner11', 2, 'first owner', '2019-01-16 06:36:43', 2),
(7, 'owner2', 2, 'Алешка', '2019-01-17 09:49:37', 3),
(8, 'owner 3', 2, 'temp owner', '2019-01-28 11:35:08', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sensor_params`
--

CREATE TABLE `sensor_params` (
  `param_id` int(11) NOT NULL,
  `param_name` varchar(45) NOT NULL,
  `param_value` varchar(45) DEFAULT NULL,
  `sensor_id` int(11) NOT NULL,
  `param_dir` varchar(3) NOT NULL DEFAULT 'OUT',
  `param_caption` varchar(45) DEFAULT NULL,
  `param_visible` int(11) DEFAULT 1,
  `param_suffix` varchar(32) DEFAULT NULL,
  `param_cmd` varchar(512) DEFAULT NULL,
  `param_type` int(11) DEFAULT NULL,
  `param_min` double DEFAULT NULL,
  `param_max` double DEFAULT NULL,
  `param_alert_min` double DEFAULT NULL,
  `param_alert_max` double DEFAULT NULL,
  `param_alert_value` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sensor_params`
--

INSERT INTO `sensor_params` (`param_id`, `param_name`, `param_value`, `sensor_id`, `param_dir`, `param_caption`, `param_visible`, `param_suffix`, `param_cmd`, `param_type`, `param_min`, `param_max`, `param_alert_min`, `param_alert_max`, `param_alert_value`) VALUES
(24, 'nvalue', '0', 15, 'OUT', 'Входная дверь', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'nvalue', '0', 2, 'OUT', 'Движение', 1, '', '', 0, 0, 1, 0, 1, '1'),
(26, 'nvalue', '0', 5, 'OUT', 'Задымление', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'nvalue', '0', 17, 'OUT', 'Протечка', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'nvalue', '0', 16, 'OUT', 'Протечка', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'temp', ' 24.49', 13, 'OUT', 'Температура', 1, '&deg;C', NULL, NULL, 18, 40, NULL, NULL, NULL),
(33, 'humidity', ' 37.65', 13, 'OUT', 'Влажность', 1, '%', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'pressure', ' 745.44', 13, 'OUT', 'Давление', 1, 'mm', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'nvalue', '0', 18, 'IN', 'Реле 1', 1, '', '/idx7/nvalue', 1, NULL, NULL, NULL, NULL, NULL),
(41, 'nvalue1', '0', 19, 'IN', 'Реле 2_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'nvalue2', '0', 19, 'IN', 'Реле 2_2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'nvalue1', '1', 20, 'IN', 'Основной свет', 1, '', '/idx20/nvalue1', 1, NULL, NULL, NULL, NULL, NULL),
(44, 'nvalue2', '1', 20, 'IN', 'Шторы', 1, '', '/idx20/nvalue2', 1, NULL, NULL, NULL, NULL, NULL),
(45, 'nvalue3', '1', 20, 'IN', 'Дополнительный свет', 1, '', '/idx20/nvalue3', 1, NULL, NULL, NULL, NULL, NULL),
(46, 'nvalue4', '0', 20, 'IN', 'Свет3', 1, '', '/idx20/nvalue4', 1, NULL, NULL, NULL, NULL, NULL),
(47, 'RSSI', '7', 13, 'OUT', 'RSSI', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'RSSI', '7', 18, 'OUT', 'RSSI', 1, '', '', 0, 0, 1, 0, 1, '0'),
(49, 'RSSI', '7', 20, 'OUT', 'RSSI', 1, '', '', 0, NULL, NULL, NULL, NULL, NULL),
(51, 'timer1', '25', 20, 'OUT', 'Таймер 5c (канал 1)', 1, 'c', '/idx20/timer1', 2, NULL, NULL, NULL, NULL, NULL),
(52, 'uptime', ' 36841', 20, 'OUT', 'Uptime', 1, '', '', 0, NULL, NULL, NULL, NULL, NULL),
(53, 'nvalue', '0', 21, 'OUT', 'Состояние', 1, '', '/idx21/nvalue', 1, NULL, NULL, NULL, NULL, NULL),
(56, 'nvalue', '0', 23, 'OUT', 'Концевик - кладовка', 1, '', '', 0, NULL, NULL, NULL, NULL, NULL),
(58, 'firstrelay', '0', 24, 'IN', 'канал 1', 1, '', '/idx24/firstrelay', 1, NULL, NULL, NULL, NULL, NULL),
(59, 'secondrelay', '0', 24, 'IN', 'канал 2', 1, '', '/idx24/secondrelay', 1, NULL, NULL, NULL, NULL, NULL),
(60, 'nvalue', '0', 25, 'IN', 'alfa_cube2', 1, '', '/idx7/nvalue', 1, 0, 1, 0, 1, '1'),
(61, 'nvalue', '0', 26, 'OUT', 'Движение', 1, '', '', 1, NULL, NULL, NULL, NULL, NULL),
(66, 'svalue1', '23.40', 28, 'OUT', 'Уровень VCC', 1, '', '', 3, 3, 5, 2, 6, '0'),
(67, 'svalue2', '37.85', 28, 'OUT', 'Время', 1, '', '', 3, 0, 5, 0, 5, ''),
(69, 'V1', '3.45', 10, 'OUT', 'Напряжение', 1, 'В', '', 0, 0, 6, 2.8, 5, '8'),
(79, 'tacho', '23.40;37.85;0;741.51;0', 29, 'OUT', 'Обороты кулера', 1, '', '/idx37/svalue', 0, 3, 10, 2, 10, '100'),
(97, 'pwm', '100', 29, 'IN', 'Медленно', 1, '', '/idx26/pwm', 4, 0, 5, 0, 5, '10'),
(98, 'V2', '0', 10, 'IN', 'Btn', 1, '', 'V3', 4, 0, 0, 0, 0, '0'),
(99, 'pwm', '500', 29, 'IN', 'Средне', 1, '', '/idx26/pwm', 4, 0, 5, 0, 5, '10'),
(110, 'pwm', '1000', 29, 'IN', 'Быстро', 1, '', '/idx26/pwm', 4, 0, 5, 0, 5, '10'),
(113, 'nvalue', '0', 29, 'IN', 'Color Sweep Random', 0, '', '/idx29/nvalue', 4, 0, 5, 0, 5, '10'),
(116, 'nvalue', '0', 29, 'IN', 'Twinkle Fade Random', 0, '', '/idx29/nvalue', 4, 0, 5, 0, 5, '10'),
(117, 'nvalue', '0', 29, 'IN', 'Fire Flicker Intense', 0, '', '/idx29/nvalue', 4, 0, 5, 0, 5, '10'),
(119, 'nvalue', '0', 29, 'IN', 'Simple', 0, '', '/idx29/nvalue', 4, 0, 5, 0, 5, '10'),
(120, 'nvalue', '0', 29, 'IN', 'Color Wipe Random', 0, '', '/idx29/nvalue', 4, 0, 5, 0, 5, '10'),
(125, 'temp', ' 23.33', 29, 'OUT', 'Температура', 1, '', '', 0, 0, 10, 0, 10, '100'),
(128, 'uptime', ' 36841', 29, 'OUT', 'uptime', 1, '', '', 0, 0, 10, 0, 10, '100'),
(131, 'nvalue', '0', 22, 'IN', 'Esp01_cube54', 1, '', '/idx22/nvalue', 1, 0, 1, 0, 1, '2'),
(132, 'pressure', '23.40', 30, 'OUT', 'Давление', 1, '', '/idx37/pressure', 0, 0, 10, 0, 10, '0'),
(133, 'temp', '23.40;37.85;0;741.51;0', 30, 'OUT', 'Температура', 1, '°С', '/idx37/temp', 0, 0, 10, 0, 10, '0'),
(134, 'color', '16714501', 30, 'IN', 'Выбрать цвет', 1, '', '/idx37/color', 6, 0, 10, 0, 10, '0'),
(135, 'pwm', '100', 30, 'IN', 'Медленно', 1, '', '/idx26/pwm', 4, 0, 10, 0, 10, '0'),
(136, 'pwm', '700', 30, 'IN', 'Средне', 1, '', '/idx26/pwm', 4, 0, 10, 0, 10, '0'),
(137, 'pwm', '1000', 30, 'IN', 'Быстро', 1, '', '/idx26/pwm', 4, 0, 10, 0, 10, '0'),
(138, 'nvalue3', '22', 30, 'IN', 'Twinkle Fade Random', 0, '', '/idx30/nvalue', 4, 0, 10, 0, 10, '0'),
(139, 'nvalue4', '50', 30, 'IN', 'Fire Flicker Intense', 0, '', '/idx30/nvalue', 4, 0, 10, 0, 10, '0'),
(140, 'nvalue6', '64', 30, 'IN', 'Play All', 0, '', '/idx30/nvalue', 4, 0, 10, 0, 10, '0'),
(143, 'color', '14579832', 29, 'IN', 'Выбрать цвет', 0, '', '/idx29/color', 6, 0, 5, 0, 5, '10'),
(151, 'nvalue', '0', 27, 'OUT', 'Свет', 1, '', '', 1, 0, 1, 0, 1, 'alert'),
(153, 'svalue', '23.40;37.85;0;741.51;0', 27, 'OUT', 'Уровень освещенности', 1, 'lux', '', 3, 5, 1400, 20, 1000, 'alert'),
(155, 'freemem', '38672', 29, 'OUT', 'freemem', 0, ' b', '', 0, 0, 10, 0, 10, '100'),
(156, 'uptime', ' 36841', 30, 'OUT', 'uptime', 0, '', '/idx37/uptime', 0, 0, 10, 0, 10, '0'),
(157, 'freemem', '37960', 30, 'OUT', 'freemem', 0, '', '', 0, 0, 0, 0, 0, '0'),
(158, 'nvalue', '0', 33, 'IN', 'test3', 1, '', '/idx1030/nvalue', 1, 0, 1, 0, 1, '1'),
(168, 'RSSI', '7', 30, 'OUT', 'RSSI', 0, '', '/idx37/rssi', NULL, NULL, NULL, NULL, NULL, NULL),
(182, 'adasda', '', 5, '', 'temp', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(202, 'nvalue', '0', 1, 'OUT', 'Тревога', 1, '', '/idx1/nvalue', NULL, NULL, NULL, NULL, NULL, NULL),
(203, 'RESULT', '', 80, 'OUT', 'Параметры', 1, '', '/idx15/RESULT', NULL, NULL, NULL, NULL, NULL, NULL),
(204, 'STATUS', '', 80, 'OUT', 'Состояние', 1, '', '/idx15/STATUS', NULL, NULL, NULL, NULL, NULL, NULL),
(205, 'IP', ' 192.168.88.92', 13, 'OUT', 'IP Адрес', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(206, 'uptime', ' 39429', 13, 'OUT', 'uptime', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(207, 'uptime', ' 36841', 81, 'OUT', 'uptime', 1, '', '', NULL, 15, 40, NULL, NULL, NULL),
(208, 'temp', ' 23.33', 81, 'OUT', 'Температура', 1, '°С', '', NULL, NULL, NULL, NULL, NULL, NULL),
(209, 'pressure', ' 741.38', 81, 'OUT', 'Давление', 1, 'мм.рс.', '', NULL, NULL, NULL, NULL, NULL, NULL),
(210, 'tacho', '', 81, 'OUT', 'Кулеры', 1, 'rpm', '', NULL, NULL, NULL, NULL, NULL, NULL),
(211, 'color', '12314693', 81, 'IN', 'Цвет', 1, '', '/idx36/color', 6, NULL, NULL, NULL, NULL, NULL),
(212, 'pwm', '', 81, 'OUT', 'PWM', 1, '%', '', NULL, NULL, NULL, NULL, NULL, NULL),
(213, 'systime', '', 13, 'OUT', 'Локальное время', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(214, 'RSSI', '', 24, 'OUT', 'RSSI', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(215, 'uptime', '', 24, 'OUT', 'uptime', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(216, 'contact_one', '0', 82, 'IN', 'Контакт 1', 1, '', '', 1, NULL, NULL, NULL, NULL, NULL),
(217, 'contact_two', '0', 82, 'IN', 'Контакт 2', 1, '', '', 1, NULL, NULL, NULL, NULL, NULL),
(218, 'tacho', '', 30, 'OUT', 'Обороты кулера', 1, 'rpm', '/idx37/tacho', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `sensor_types`
--

CREATE TABLE `sensor_types` (
  `sensor_type_id` int(11) NOT NULL,
  `sensor_type_name` varchar(255) DEFAULT NULL,
  `sensor_type_tpl` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sensor_types`
--

INSERT INTO `sensor_types` (`sensor_type_id`, `sensor_type_name`, `sensor_type_tpl`) VALUES
(3, 'CO2', 'tpl.CO2'),
(10, 'CO', ''),
(12, 'Уровень', ''),
(18, 'Концевик', ''),
(19, 'PIR', ''),
(22, 'THP', '<div class=\"wrapper\">\r\n\r\n  \r\n    <div class=\"green firstline\">\r\n      <img src=\"images/ul_logo_800_8bit.png\">\r\n      <span>%temp_caption%:&nbsp;</span><span id=\"%temp%\">...</span><span>%temp_suffix%</span>\r\n     </div>\r\n    <div class=\"green secondline\">\r\n       <span>%humidity_caption%:&nbsp;</span><span id=\"%humidity%\">...</span><span>%humidity_suffix%</span>\r\n     </div>\r\n     <div class=\"green fourthline\">\r\n      <span>%pressure_caption%:&nbsp;</span><span id=\"%pressure%\">...</span><span>%pressure_suffix%</span>\r\n     </div>\r\n\r\n</div>'),
(23, 'Движение', ''),
(25, 'GSM Telemetry', ''),
(26, 'Датчик протечки', ''),
(27, 'Реле 1', 'tpl.Relay1'),
(28, 'Реле 2', ''),
(29, 'Реле 4', ''),
(30, 'Umolab_C2', ''),
(34, 'QWERTY1', ''),
(36, 'RF трансивер', '');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `setting` varchar(8) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`setting`, `state`, `timestamp`) VALUES
('unique', 0, '2018-10-18 12:40:35');

-- --------------------------------------------------------

--
-- Структура таблицы `timers`
--

CREATE TABLE `timers` (
  `timer_id` int(11) NOT NULL,
  `timer_name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `timer_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
  `timer_datetime` datetime DEFAULT NULL,
  `timer_repeat` int(11) NOT NULL DEFAULT 0,
  `timer_cmd` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `su` int(1) DEFAULT NULL,
  `mo` int(1) DEFAULT NULL,
  `tu` int(1) DEFAULT NULL,
  `we` int(1) DEFAULT NULL,
  `th` int(1) DEFAULT NULL,
  `fr` int(1) DEFAULT NULL,
  `sa` int(1) DEFAULT NULL,
  `sensor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `_sensors`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `_sensors` (
`sensor_id` int(11)
,`sensor_name` varchar(255)
,`sensor_type_id` int(11)
,`sensor_owner_id` int(11)
,`domoticz_idx` int(11)
,`fl_enabled` int(11)
,`fl_payed` int(11)
,`fl_active` int(11)
,`fl_armed` int(11)
,`room_id` int(11)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `_sensors_all`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `_sensors_all` (
`rec_count` bigint(21)
,`sensor_id` int(11)
,`sensor_name` varchar(255)
,`sensor_type_id` int(11)
,`sensor_user_id` int(11)
,`domoticz_idx` int(11)
,`sensor_type_name` varchar(255)
,`owner_name` varchar(45)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `_sensors_users`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `_sensors_users` (
`sensor_id` int(11)
,`sensor_name` varchar(255)
,`sensor_type_id` int(11)
,`sensor_user_id` int(11)
,`domoticz_idx` int(11)
,`fl_enabled` int(11)
,`fl_payed` int(11)
,`fl_active` int(11)
,`fl_armed` int(11)
,`room_id` int(11)
,`sensor_pin` int(11)
,`owner_id` int(11)
,`owner_name` varchar(45)
,`owner_type` int(11)
,`owner_desc` varchar(255)
,`owner_regdate` datetime
,`user_id` int(11)
,`id` int(10) unsigned
,`internalKey` int(10)
,`fullname` varchar(100)
,`email` varchar(100)
,`phone` varchar(100)
,`mobilephone` varchar(100)
,`blocked` tinyint(1) unsigned
,`blockeduntil` int(11)
,`blockedafter` int(11)
,`logincount` int(11)
,`lastlogin` int(11)
,`thislogin` int(11)
,`failedlogincount` int(10)
,`sessionid` varchar(100)
,`dob` int(10)
,`gender` int(1)
,`address` text
,`country` varchar(191)
,`city` varchar(191)
,`state` varchar(25)
,`zip` varchar(25)
,`fax` varchar(100)
,`photo` varchar(191)
,`comment` text
,`website` varchar(191)
,`extended` text
);

-- --------------------------------------------------------

--
-- Структура для представления `_sensors`
--
DROP TABLE IF EXISTS `_sensors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`modx_usr`@`localhost` SQL SECURITY DEFINER VIEW `_sensors`  AS SELECT `sensors`.`sensor_id` AS `sensor_id`, `sensors`.`sensor_name` AS `sensor_name`, `sensors`.`sensor_type_id` AS `sensor_type_id`, `sensors`.`sensor_user_id` AS `sensor_owner_id`, `sensors`.`domoticz_idx` AS `domoticz_idx`, `sensors`.`fl_enabled` AS `fl_enabled`, `sensors`.`fl_payed` AS `fl_payed`, `sensors`.`fl_active` AS `fl_active`, `sensors`.`fl_armed` AS `fl_armed`, `sensors`.`room_id` AS `room_id` FROM `sensors``sensors`  ;

-- --------------------------------------------------------

--
-- Структура для представления `_sensors_all`
--
DROP TABLE IF EXISTS `_sensors_all`;

CREATE ALGORITHM=UNDEFINED DEFINER=`modx_usr`@`localhost` SQL SECURITY DEFINER VIEW `_sensors_all`  AS SELECT count(`O`.`_id`) AS `rec_count`, `C`.`sensor_id` AS `sensor_id`, `C`.`sensor_name` AS `sensor_name`, `C`.`sensor_type_id` AS `sensor_type_id`, `C`.`sensor_user_id` AS `sensor_user_id`, `C`.`domoticz_idx` AS `domoticz_idx`, `D`.`sensor_type_name` AS `sensor_type_name`, `E`.`owner_name` AS `owner_name` FROM (((`dblog`.`message_id_by_idx` `O` join `sensors` `C` on(`O`.`sensor_id` = `C`.`sensor_id`)) join `sensor_types` `D` on(`D`.`sensor_type_id` = `C`.`sensor_type_id`)) join `sensor_owners` `E` on(`E`.`owner_id` = `C`.`sensor_user_id`)) GROUP BY `C`.`sensor_id`, `C`.`sensor_name`, `C`.`sensor_type_id`, `C`.`sensor_user_id`, `C`.`domoticz_idx`, `D`.`sensor_type_name`, `E`.`owner_name` ORDER BY count(`O`.`_id`) AS `DESCdesc` ASC  ;

-- --------------------------------------------------------

--
-- Структура для представления `_sensors_users`
--
DROP TABLE IF EXISTS `_sensors_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`modx_usr`@`localhost` SQL SECURITY DEFINER VIEW `_sensors_users`  AS SELECT `sensors`.`sensor_id` AS `sensor_id`, `sensors`.`sensor_name` AS `sensor_name`, `sensors`.`sensor_type_id` AS `sensor_type_id`, `sensors`.`sensor_user_id` AS `sensor_user_id`, `sensors`.`domoticz_idx` AS `domoticz_idx`, `sensors`.`fl_enabled` AS `fl_enabled`, `sensors`.`fl_payed` AS `fl_payed`, `sensors`.`fl_active` AS `fl_active`, `sensors`.`fl_armed` AS `fl_armed`, `sensors`.`room_id` AS `room_id`, `sensors`.`sensor_pin` AS `sensor_pin`, `sensor_owners`.`owner_id` AS `owner_id`, `sensor_owners`.`owner_name` AS `owner_name`, `sensor_owners`.`owner_type` AS `owner_type`, `sensor_owners`.`owner_desc` AS `owner_desc`, `sensor_owners`.`owner_regdate` AS `owner_regdate`, `sensor_owners`.`user_id` AS `user_id`, `modx`.`mxuser_attributes`.`id` AS `id`, `modx`.`mxuser_attributes`.`internalKey` AS `internalKey`, `modx`.`mxuser_attributes`.`fullname` AS `fullname`, `modx`.`mxuser_attributes`.`email` AS `email`, `modx`.`mxuser_attributes`.`phone` AS `phone`, `modx`.`mxuser_attributes`.`mobilephone` AS `mobilephone`, `modx`.`mxuser_attributes`.`blocked` AS `blocked`, `modx`.`mxuser_attributes`.`blockeduntil` AS `blockeduntil`, `modx`.`mxuser_attributes`.`blockedafter` AS `blockedafter`, `modx`.`mxuser_attributes`.`logincount` AS `logincount`, `modx`.`mxuser_attributes`.`lastlogin` AS `lastlogin`, `modx`.`mxuser_attributes`.`thislogin` AS `thislogin`, `modx`.`mxuser_attributes`.`failedlogincount` AS `failedlogincount`, `modx`.`mxuser_attributes`.`sessionid` AS `sessionid`, `modx`.`mxuser_attributes`.`dob` AS `dob`, `modx`.`mxuser_attributes`.`gender` AS `gender`, `modx`.`mxuser_attributes`.`address` AS `address`, `modx`.`mxuser_attributes`.`country` AS `country`, `modx`.`mxuser_attributes`.`city` AS `city`, `modx`.`mxuser_attributes`.`state` AS `state`, `modx`.`mxuser_attributes`.`zip` AS `zip`, `modx`.`mxuser_attributes`.`fax` AS `fax`, `modx`.`mxuser_attributes`.`photo` AS `photo`, `modx`.`mxuser_attributes`.`comment` AS `comment`, `modx`.`mxuser_attributes`.`website` AS `website`, `modx`.`mxuser_attributes`.`extended` AS `extended` FROM ((`sensors` join `sensor_owners` on(`sensors`.`sensor_user_id` = `sensor_owners`.`owner_id`)) join `modx`.`mxuser_attributes` on(`sensor_owners`.`user_id` = `modx`.`mxuser_attributes`.`id`))  ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`img_id`);

--
-- Индексы таблицы `message_types`
--
ALTER TABLE `message_types`
  ADD PRIMARY KEY (`mt_id`);

--
-- Индексы таблицы `owner_rooms`
--
ALTER TABLE `owner_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `owner_id_idx` (`owner_id`);

--
-- Индексы таблицы `param_values`
--
ALTER TABLE `param_values`
  ADD PRIMARY KEY (`pv_id`),
  ADD UNIQUE KEY `pv_id_UNIQUE` (`pv_id`);

--
-- Индексы таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`sch_id`),
  ADD KEY `mt_id` (`mt_id`),
  ADD KEY `sensor_id` (`sensor_id`);

--
-- Индексы таблицы `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`sensor_id`),
  ADD KEY `sensor_type_id_idx` (`sensor_type_id`),
  ADD KEY `sensor_img_id` (`img_id`);

--
-- Индексы таблицы `sensor_owners`
--
ALTER TABLE `sensor_owners`
  ADD PRIMARY KEY (`owner_id`);

--
-- Индексы таблицы `sensor_params`
--
ALTER TABLE `sensor_params`
  ADD PRIMARY KEY (`param_id`),
  ADD KEY `sensor_id_idx` (`sensor_id`);

--
-- Индексы таблицы `sensor_types`
--
ALTER TABLE `sensor_types`
  ADD PRIMARY KEY (`sensor_type_id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting`);

--
-- Индексы таблицы `timers`
--
ALTER TABLE `timers`
  ADD PRIMARY KEY (`timer_id`),
  ADD UNIQUE KEY `timer_id_UNIQUE` (`timer_id`),
  ADD KEY `idx_timers_sensor_id` (`sensor_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT для таблицы `message_types`
--
ALTER TABLE `message_types`
  MODIFY `mt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `owner_rooms`
--
ALTER TABLE `owner_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `param_values`
--
ALTER TABLE `param_values`
  MODIFY `pv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `schedule`
--
ALTER TABLE `schedule`
  MODIFY `sch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sensors`
--
ALTER TABLE `sensors`
  MODIFY `sensor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `sensor_owners`
--
ALTER TABLE `sensor_owners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `sensor_params`
--
ALTER TABLE `sensor_params`
  MODIFY `param_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT для таблицы `sensor_types`
--
ALTER TABLE `sensor_types`
  MODIFY `sensor_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `timers`
--
ALTER TABLE `timers`
  MODIFY `timer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `owner_rooms`
--
ALTER TABLE `owner_rooms`
  ADD CONSTRAINT `owner_id` FOREIGN KEY (`owner_id`) REFERENCES `sensor_owners` (`owner_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`mt_id`) REFERENCES `message_types` (`mt_id`),
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`sensor_id`) REFERENCES `sensors` (`sensor_id`);

--
-- Ограничения внешнего ключа таблицы `sensors`
--
ALTER TABLE `sensors`
  ADD CONSTRAINT `sensor_img_id` FOREIGN KEY (`img_id`) REFERENCES `images` (`img_id`),
  ADD CONSTRAINT `sensor_type_id` FOREIGN KEY (`sensor_type_id`) REFERENCES `sensor_types` (`sensor_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `sensor_params`
--
ALTER TABLE `sensor_params`
  ADD CONSTRAINT `sensor_id` FOREIGN KEY (`sensor_id`) REFERENCES `sensors` (`sensor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `timers`
--
ALTER TABLE `timers`
  ADD CONSTRAINT `fk_sensors` FOREIGN KEY (`sensor_id`) REFERENCES `sensors` (`sensor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
