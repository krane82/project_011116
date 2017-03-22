
/* ALTER TABLE `leads_delivery` ADD `postcode` VARCHAR(5) NULL DEFAULT NULL AFTER `timedate`; */

ALTER TABLE `clients_criteria` CHANGE `postcodes` `postcodes` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `leads_lead_fields_rel` CHANGE `note` `note` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `leads_delivery` ADD `open_email` TINYINT(4) NULL DEFAULT NULL

CREATE TABLE IF NOT EXISTS `queue` (
 `id` int(20) NOT NULL,
 `id_lead` int(20) NOT NULL,
 `id_client` int(20) NOT NULL,
 `timedata` int(10) NOT NULL,
 `status` tinyint(20) NOT NULL,
 `test` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;

ALTER TABLE `queue`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `queue`
 MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;

# ALTER TABLE leads_delivery ADD amount INT(20) NOT NULL
CREATE TABLE IF NOT EXISTS lead_settings (days int(3));

ALTER TABLE `leads_delivery` ADD `open_time` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `open_email`;
ALTER TABLE `leads_rejection` ADD `decline_reason` VARCHAR(500) NULL DEFAULT NULL AFTER `note`;

/*Mironenko 07032017*/
ALTER TABLE campaigns ADD
`NSW` int(11) DEFAULT NULL,
ADD `QLD` int(11) DEFAULT NULL,
ADD `SA` int(11) DEFAULT NULL,
ADD `TAS` int(11) DEFAULT NULL,
ADD `VIC` int(11) DEFAULT NULL,
ADD `WA` int(11) DEFAULT NULL



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--
-- Структура таблицы `states_postcodes`
--

CREATE TABLE IF NOT EXISTS `states_postcodes` (
  `id` int(2) NOT NULL,
  `state` enum('NSW','QLD','SA','TAS','VIC','WA') NOT NULL,
  `postcodes` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `states_postcodes`
--

INSERT INTO `states_postcodes` (`id`, `state`, `postcodes`) VALUES
(1, 'NSW', '1000:1999'),
(2, 'NSW', '2000:2599'),
(3, 'NSW', '2620:2898'),
(4, 'NSW', '2921:2999'),
(5, 'VIC', '3000:3999'),
(6, 'VIC', '8000:8999'),
(7, 'QLD', '4000:4999'),
(8, 'QLD', '9000:9999'),
(9, 'SA', '5000:5799'),
(10, 'SA', '5800:5999'),
(11, 'WA', '6000:6797'),
(12, 'WA', '6800:6999'),
(13, 'TAS', '7000:7799'),
(14, 'TAS', '7800:7999');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `states_postcodes`
--
ALTER TABLE `states_postcodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `states_postcodes`
--
ALTER TABLE `states_postcodes`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;  

/* Key for post:
9b434eba-e278-4522-a6ac-5ef14e1fb18d
*/

/* last result

ALTER TABLE `clients_criteria` CHANGE `postcodes` `postcodes` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;# MySQL вернула пустой результат (т.е. ноль строк).


ALTER TABLE `leads_lead_fields_rel` CHANGE `note` `note` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;# MySQL вернула пустой результат (т.е. ноль строк).



CREATE TABLE IF NOT EXISTS `queue` (
 `id` int(20) NOT NULL,
 `id_lead` int(20) NOT NULL,
 `id_client` int(20) NOT NULL,
 `timedata` int(10) NOT NULL,
 `status` tinyint(20) NOT NULL,
 `test` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;# MySQL вернула пустой результат (т.е. ноль строк).


ALTER TABLE `queue`
 ADD PRIMARY KEY (`id`);# MySQL вернула пустой результат (т.е. ноль строк).


ALTER TABLE `queue`
 MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;# MySQL вернула пустой результат (т.е. ноль строк).


CREATE TABLE IF NOT EXISTS lead_settings (days int(3));# MySQL вернула пустой результат (т.е. ноль строк).


ALTER TABLE `leads_delivery` ADD `open_time` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `open_email`;# Затронуто 3732 строки.

ALTER TABLE `leads_rejection` ADD `decline_reason` VARCHAR(500) NULL DEFAULT NULL AFTER `note`;# Затронуто 3722 строки.

 */
 
 
 /*Clients INFO*/
 
 ALTER TABLE `clients` ADD `address` VARCHAR(255) NULL DEFAULT NULL AFTER `phone`;
 
 ALTER TABLE `clients` ADD `abn` VARCHAR(255) NOT NULL AFTER `status`, ADD `authorised_person` VARCHAR(100) NOT NULL AFTER `abn`, ADD `position` VARCHAR(100) NOT NULL AFTER `authorised_person`, ADD `name_on_card` VARCHAR(100) NOT NULL AFTER `position`, ADD `credit_card_number` VARCHAR(50) NOT NULL AFTER `name_on_card`, ADD `expires_mm` INT NOT NULL AFTER `credit_card_number`, ADD `expires_yy` INT NOT NULL AFTER `expires_mm`;
 
 ALTER TABLE `clients` ADD `cvc` INT NOT NULL AFTER `expires_yy`;
 
 ALTER TABLE `clients` CHANGE `abn` `abn` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `authorised_person` `authorised_person` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `position` `position` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `name_on_card` `name_on_card` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `credit_card_number` `credit_card_number` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `expires_mm` `expires_mm` INT(11) NULL DEFAULT NULL, CHANGE `expires_yy` `expires_yy` INT(11) NULL DEFAULT NULL, CHANGE `cvc` `cvc` INT(11) NULL DEFAULT NULL;
 

ALTER TABLE `leads_lead_fields_rel` CHANGE `address` `address` VARCHAR(120)  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT NULL;

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 