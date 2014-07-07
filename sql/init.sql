CREATE DATABASE IF NOT EXISTS `stuff`
  DEFAULT CHARACTER SET utf8;
USE `stuff`;

CREATE TABLE IF NOT EXISTS `files` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `description` text,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `options` (
  `option_name` varchar(64) NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_name`),
  KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `options` (`option_name`, `option_value`) VALUES
('directory', ''),
('forbidden_exts', ''),
('forbidden_folders', ''),
('forbidden_prefix', ''),
('header', 'Files'),
('preview_height', '250'),
('preview_width', '250'),
('show_preview', 'false'),
('title', 'File Browser');