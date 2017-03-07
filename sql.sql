
/* ALTER TABLE `leads_delivery` ADD `postcode` VARCHAR(5) NULL DEFAULT NULL AFTER `timedate`; */

CREATE TABLE IF NOT EXISTS lead_settings (days int(3));

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


/* Key for post:
9b434eba-e278-4522-a6ac-5ef14e1fb18d
*/