SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";

CREATE DATABASE IF NOT EXISTS `qingzhi`;
use `qingzhi`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `auto_time` (
  `id` int(11) NOT NULL,
  `ua` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `wastetime` int(11) NOT NULL COMMENT '单位：毫秒(ms)',
  `querytime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(80) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `signup` (
  `no` int(11) NOT NULL,
  `name` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `classno` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `tworone` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `loc_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `times` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `go` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `fromwap` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `userpwd` (
  `id` int(11) NOT NULL,
  `certName` varchar(1024) NOT NULL,
  `certSerial` varchar(32) NOT NULL,
  `username` varchar(20) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `salt` varchar(5) NOT NULL,
  `img` varchar(128) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `userpwd` (`id`, `certName`, `certSerial`, `username`, `pwd`, `salt`, `img`, `datetime`) VALUES
(1, '', '', 'admin', '112165c4e586cbbbe58966d90feb7684', 'j2knn', '', '2016-02-13 18:22:40');

ALTER TABLE `auto_time`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `signup`
  ADD PRIMARY KEY (`no`);

ALTER TABLE `userpwd`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `auto_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `signup`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `userpwd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
