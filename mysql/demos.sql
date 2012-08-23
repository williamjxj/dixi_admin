-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 16, 2011 at 07:18 PM
-- Server version: 5.1.56
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cdatcom_demos`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `index_cont_earn`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` PROCEDURE `index_cont_earn`(
    retirement_date DATE,
    yearly_earnings DECIMAL(12,2),
    dob             DATE,
    txt_inc_per     INT
)
BEGIN

DECLARE earn DECIMAL(12,2);
DECLARE mem_age1 DECIMAL(12,8);
DECLARE txt_years2 DECIMAL(12,8);
DECLARE AGE_39 DECIMAL(12,2);
DECLARE AGE_49 DECIMAL(12,2);
DECLARE AGE_99 DECIMAL(12,2);
DECLARE ee DECIMAL(12,2);

  SET earn=yearly_earnings;
  SET mem_age1=0;
  SET @projected_earnings=0;
  SET @total_ee_conts=0;

  SET @j=0;
  SET @k=0;
  SET ee=0;
  SET AGE_39=0;
  SET AGE_49=0;
  SET AGE_99=0;
  #REPEAT SET @x = @x + 1; UNTIL @x > p1 END REPEAT;

  SET txt_years2=PERIOD_DIFF(DATE_FORMAT(retirement_date,'%Y%m'),'200912')/12;
  #SELECT txt_years2;
  SET @i=FLOOR(txt_years2);

  REPEAT

    SET @j = @j + 1; 
    #SELECT @j;
    #earn:=nvl(earn,0)*(1+(nvl(txt_inc_per,0)/100));
    SET earn:=IFNULL(earn,0)*(1+(IFNULL(txt_inc_per,0)/100));
    #mem_age1:=MONTHS_BETWEEN( add_months(to_date('31-DEC-2009','dd-mon-rrrr'),((j-1)*12)), last_day(dob))/12;
    #SET mem_age1=PERIOD_DIFF( ('20091231'+INTERVAL((@j-1)*12) MONTH), DATE_FORMAT(dob,'%y%m')  )/12;
    SET mem_age1=PERIOD_DIFF(DATE_FORMAT(('20091231'+ INTERVAL ((@j-1)*12) MONTH),'%Y%m'), DATE_FORMAT(dob,'%Y%m'))/12;
    #SELECT CONCAT('mem_age1:: ',mem_age1);

    SET @k=0;

    REPEAT

      SET @k = @k + 1; 

      IF mem_age1<30 THEN
        SET ee         =0;
        SET AGE_39     =0;
      ELSEIF mem_age1<40 THEN
        SET ee         =IFNULL(ee,0)    +(IFNULL(earn,0)*(0.01/12));
        SET AGE_39     =IFNULL(AGE_39,0)+(IFNULL(earn,0)*(0.01/12));
      ELSEIF mem_age1<50 THEN
        SET ee         =IFNULL(ee,0)    +(IFNULL(earn,0)*(0.02/12));
        SET AGE_49     =IFNULL(AGE_49,0)+(IFNULL(earn,0)*(0.02/12));
      ELSE
        SET ee    =IFNULL(ee,0)    +(IFNULL(earn,0)*(0.04/12));
        SET AGE_99=IFNULL(AGE_99,0)+(IFNULL(earn,0)*(0.04/12));
      END IF;
      #mem_age1:=MONTHS_BETWEEN(add_months(to_date('31-DEC-2009','dd-mon-rrrr'),((j-1)*12)+k),last_day(dob))/12;
      #SET mem_age1=PERIOD_DIFF( ('20091231'+ INTERVAL ((@j-1)*12+@k) MONTH), DATE_FORMAT(dob,'%Y%m')  )/12;
      SET mem_age1=PERIOD_DIFF(DATE_FORMAT(('20091231'+ INTERVAL ((@j-1)*12+@k) MONTH),'%Y%m'), DATE_FORMAT(dob,'%Y%m'))/12;
      #SELECT CONCAT('ee ', @i,':',@k,':: ',ee);
      #SELECT CONCAT('mem_age1:', mem_age1,', k:',@k,': ',ee);


    UNTIL @k > 11 END REPEAT;

    #projected_earnings:=nvl(projected_earnings,0)+nvl(earn,0);
    SET @projected_earnings:=IFNULL(@projected_earnings,0)+IFNULL(earn,0);

  UNTIL @j > txt_years2-1 END REPEAT;

  #earn:=nvl(earn,0)*(1+(nvl(txt_inc_per,0)/100))*(round((TXT_YEARS2-I)*12)/12);
  SET earn=IFNULL(earn,0)*(1+(IFNULL(txt_inc_per,0)/100))*(ROUND((txt_years2-@i)*12)/12);
  #ee:=nvl(ee,0)+(nvl(earn,0)*.04);
  SET ee=IFNULL(ee,0)+(IFNULL(earn,0)*0.04);
  #AGE_99:=NVL(AGE_99,0)+(nvl(earn,0)*.04);
  SET AGE_99=IFNULL(AGE_99,0)+(IFNULL(earn,0)*.04);
  #projected_earnings:=nvl(projected_earnings,0)+nvl(earn,0);
  SET @projected_earnings=IFNULL(@projected_earnings,0)+IFNULL(earn,0);
  #total_ee_conts:=nvl(ee,0);
  SET @total_ee_conts:=IFNULL(ee,0);

  SELECT @projected_earnings AS 'projected_earnings', @total_ee_conts AS 'total_ee_conts';

END$$

DROP PROCEDURE IF EXISTS `test1`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` PROCEDURE `test1`(OUT param1 INT)
BEGIN
SELECT COUNT(*) INTO param1 FROM t;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `accumulated_earnings2`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `accumulated_earnings2`(retire_date date, earnings decimal(8,2)) RETURNS int(11)
return ROUND(TIMESTAMPDIFF(MONTH, concat(Year(CURDATE()), '-12-27'), LAST_DAY(retire_date)) / 12 * earnings, 2)$$

DROP FUNCTION IF EXISTS `get_yyyymm_from_date`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `get_yyyymm_from_date`(retire_date date) RETURNS char(6) CHARSET utf8
return DATE_FORMAT(retire_date,'%Y%m')$$

DROP FUNCTION IF EXISTS `member_age`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_age`(dob date) RETURNS int(11)
return (TIMESTAMPDIFF(MONTH, dob, CONCAT(Year(curdate()), '-12-31')) / 12)$$

DROP FUNCTION IF EXISTS `member_credited_years`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_credited_years`(total_service decimal(8,4), retire_date date) RETURNS tinyint(1)
begin
	declare flag boolean;
	set flag = IF((total_service + TIMESTAMPDIFF(MONTH, concat(Year(CURDATE()), '-12-27'), LAST_DAY(retire_date)) / 12) >= 15, 1, 0);
	return flag;
END$$

DROP FUNCTION IF EXISTS `member_dob_calc`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_dob_calc`(dob date) RETURNS char(6) CHARSET utf8
return DATE_FORMAT(dob, '%Y%m')$$

DROP FUNCTION IF EXISTS `member_earlyretiredate`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_earlyretiredate`(dob date) RETURNS date
return LAST_DAY(dob + INTERVAL 55*12 MONTH)$$

DROP FUNCTION IF EXISTS `member_enroldate_before1998`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_enroldate_before1998`(member_enroldate date) RETURNS tinyint(1)
begin
	declare flag boolean;
	set flag = IF(member_enroldate < '1998-01-02', 1, 0);
	return flag;
end$$

DROP FUNCTION IF EXISTS `member_month`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_month`(dob date) RETURNS int(11)
return TIMESTAMPDIFF(MONTH, dob, CONCAT(YEAR(NOW()-1),'-12-31'))$$

DROP FUNCTION IF EXISTS `member_projectedmonth`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_projectedmonth`(retire_date date) RETURNS int(11)
return TIMESTAMPDIFF(MONTH, concat(Year(CURDATE()), '-12-27'), LAST_DAY(retire_date))$$

DROP FUNCTION IF EXISTS `member_projected_retirementmonths`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_projected_retirementmonths`(retire_date date) RETURNS int(11)
return ROUND(PERIOD_DIFF(date_format(retire_date,'%Y%m'), concat(Year(CURDATE()), '12')) % 12, 0)$$

DROP FUNCTION IF EXISTS `member_projected_retirementyears`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_projected_retirementyears`(retire_date date) RETURNS int(11)
return FLOOR(PERIOD_DIFF(date_format(retire_date,'%Y%m'), concat(Year(CURDATE()), '12')) / 12)$$

DROP FUNCTION IF EXISTS `member_retiredate`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retiredate`(dob date) RETURNS date
return LAST_DAY(dob + INTERVAL 65*12 MONTH)$$

DROP FUNCTION IF EXISTS `member_retiredate_formated`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retiredate_formated`(dob date) RETURNS char(11) CHARSET utf8
return DATE_FORMAT(LAST_DAY(dob + INTERVAL 65*12 MONTH),'%b %d %Y')$$

DROP FUNCTION IF EXISTS `member_retiredate_max`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retiredate_max`(dob date) RETURNS date
return LAST_DAY(dob + INTERVAL 71*12 MONTH)$$

DROP FUNCTION IF EXISTS `member_retiremonth`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retiremonth`(dob date, retire_date date) RETURNS int(11)
return TIMESTAMPDIFF(MONTH, dob, LAST_DAY(retire_date))$$

DROP FUNCTION IF EXISTS `member_retireprojectedmonth`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retireprojectedmonth`(dob date, retire_date date) RETURNS int(11)
return TIMESTAMPDIFF(MONTH, concat(year(dob), '-12-31'), LAST_DAY(retire_date))$$

DROP FUNCTION IF EXISTS `member_retireyear`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `member_retireyear`(dob date) RETURNS char(4) CHARSET utf8
return YEAR(LAST_DAY(dob + INTERVAL 65*12 MONTH))$$

DROP FUNCTION IF EXISTS `retirement_displaydate`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `retirement_displaydate`(retire_date date) RETURNS varchar(20) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE year varchar(4);
SET year = substring(retire_date, 1, 4);
return concat(monthname(retire_date), ', ', year);
END$$

DROP FUNCTION IF EXISTS `retirement_legaldate`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `retirement_legaldate`(retire_date date) RETURNS date
    DETERMINISTIC
return LAST_DAY(retire_date)$$

DROP FUNCTION IF EXISTS `spouse_age`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `spouse_age`(dob date) RETURNS int(11)
return (TIMESTAMPDIFF(MONTH, dob, CONCAT(Year(curdate()),'-12-31')) / 12)$$

DROP FUNCTION IF EXISTS `spouse_dob2`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `spouse_dob2`(spouse_dob date) RETURNS date
begin
declare flag boolean;
-- set flag = IF(spouse_dob='0000-00-00','1900--01',IF('".$spouse_birth_smartydate."'!='','".$spouse_birth_smartydate."',spouse_dob)) AS spouse_dob2
return spouse_dob;
end$$

DROP FUNCTION IF EXISTS `spouse_retiremonth`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `spouse_retiremonth`(spouse_date date, retire_date date) RETURNS int(11)
return TIMESTAMPDIFF(MONTH, spouse_date, LAST_DAY(retire_date))$$

DROP FUNCTION IF EXISTS `supplement_date1`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `supplement_date1`(dob date) RETURNS char(8) CHARSET utf8
return DATE_FORMAT(dob + INTERVAL 60 YEAR-INTERVAL 1 MONTH, '%b %Y')$$

DROP FUNCTION IF EXISTS `supplement_date2`$$
CREATE DEFINER=`cdatcom_demo`@`localhost` FUNCTION `supplement_date2`(dob date) RETURNS char(8) CHARSET utf8
return DATE_FORMAT(dob + INTERVAL 65 YEAR-INTERVAL 1 MONTH, '%b %Y')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
CREATE TABLE IF NOT EXISTS `actions` (
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `keyword` varchar(20) DEFAULT NULL,
  `action` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `aid` int(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`aid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=368 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) DEFAULT '1',
  `lname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

DROP TABLE IF EXISTS `components`;
CREATE TABLE IF NOT EXISTS `components` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(4) NOT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Triggers `components`
--
DROP TRIGGER IF EXISTS `delete_cm2`;
DELIMITER //
CREATE TRIGGER `delete_cm2` AFTER DELETE ON `components`
 FOR EACH ROW begin
	delete from components_modules where cid=OLD.cid;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `components_modules`
--

DROP TABLE IF EXISTS `components_modules`;
CREATE TABLE IF NOT EXISTS `components_modules` (
  `cmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(5) unsigned NOT NULL,
  `cid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cmid`),
  UNIQUE KEY `cmid` (`mid`,`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
CREATE TABLE IF NOT EXISTS `contents` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linkname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` text NOT NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `division` varchar(1) DEFAULT NULL,
  `weight` tinyint(3) unsigned DEFAULT '0',
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `title_mid` (`title`,`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=143 ;

--
-- Triggers `contents`
--
DROP TRIGGER IF EXISTS `delete_cr1`;
DELIMITER //
CREATE TRIGGER `delete_cr1` AFTER DELETE ON `contents`
 FOR EACH ROW begin
	delete from contents_resources where mid=OLD.cid;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contents_resources`
--

DROP TABLE IF EXISTS `contents_resources`;
CREATE TABLE IF NOT EXISTS `contents_resources` (
  `cid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `crid` (`rid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `c_benefits`
--

DROP TABLE IF EXISTS `c_benefits`;
CREATE TABLE IF NOT EXISTS `c_benefits` (
  `gwl` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `todate` date DEFAULT NULL,
  `hours` float(9,2) DEFAULT NULL,
  `deduct_hours` float(9,2) DEFAULT NULL,
  `closing_hours` float(9,2) DEFAULT NULL,
  `in_benefit` enum('Y','N') CHARACTER SET utf8 DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `gwl` (`gwl`),
  KEY `gwl_2` (`gwl`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c_dependents`
--

DROP TABLE IF EXISTS `c_dependents`;
CREATE TABLE IF NOT EXISTS `c_dependents` (
  `gwl` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `dep_no` tinyint(4) DEFAULT NULL,
  `surname` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `firstname` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `relation` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `effectived` date DEFAULT NULL,
  `termed` date DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c_employers`
--

DROP TABLE IF EXISTS `c_employers`;
CREATE TABLE IF NOT EXISTS `c_employers` (
  `employer` varchar(20) CHARACTER SET utf8 NOT NULL,
  `division` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `employer_name` varchar(150) CHARACTER SET utf8 NOT NULL,
  `payment_type` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'CHQ',
  `contactor` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `province` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `postal` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `fax` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`employer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c_members`
--

DROP TABLE IF EXISTS `c_members`;
CREATE TABLE IF NOT EXISTS `c_members` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `gwl` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `passwd` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `surname` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `given` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `employer` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `division` varchar(1) CHARACTER SET utf8 DEFAULT NULL,
  `sex` enum('M','F') CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `prov` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `postalcode` char(7) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `beneficiary` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `relationship` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `enrollcarddate` date DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE IF NOT EXISTS `divisions` (
  `division` varchar(1) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
CREATE TABLE IF NOT EXISTS `levels` (
  `level` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`level`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_info`
--

DROP TABLE IF EXISTS `login_info`;
CREATE TABLE IF NOT EXISTS `login_info` (
  `uid` int(10) unsigned NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `browser` varchar(200) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `session` varchar(32) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` enum('Y','N') DEFAULT 'N',
  `logout_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expired` datetime DEFAULT NULL,
  UNIQUE KEY `u3` (`uid`,`ip`,`browser`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `mid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(4) NOT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `url_flag` varchar(1) DEFAULT 'N',
  `left1` enum('Y','N') DEFAULT 'Y',
  `right3` enum('Y','N') DEFAULT 'Y',
  `weight` tinyint(3) unsigned DEFAULT '1',
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`),
  KEY `sid` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Triggers `modules`
--
DROP TRIGGER IF EXISTS `delete_cm1`;
DELIMITER //
CREATE TRIGGER `delete_cm1` AFTER DELETE ON `modules`
 FOR EACH ROW begin
	delete from components_modules where mid=OLD.mid;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `pid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(4) NOT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `weight` tinyint(3) unsigned DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Triggers `pages`
--
DROP TRIGGER IF EXISTS `delete_pm2`;
DELIMITER //
CREATE TRIGGER `delete_pm2` AFTER DELETE ON `pages`
 FOR EACH ROW begin
	delete from pages_modules where pid=OLD.pid;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pages_modules`
--

DROP TABLE IF EXISTS `pages_modules`;
CREATE TABLE IF NOT EXISTS `pages_modules` (
  `cmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(5) unsigned NOT NULL,
  `pid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cmid`),
  UNIQUE KEY `cmid` (`mid`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Table structure for table `pensions`
--

DROP TABLE IF EXISTS `pensions`;
CREATE TABLE IF NOT EXISTS `pensions` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL DEFAULT '',
  `sin` char(33) NOT NULL DEFAULT '',
  `division` char(1) NOT NULL DEFAULT '',
  `firstname` char(30) NOT NULL DEFAULT '',
  `lastname` char(30) NOT NULL DEFAULT '',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `gender` char(1) NOT NULL DEFAULT '',
  `enroldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `address` char(40) NOT NULL DEFAULT '',
  `city` char(25) NOT NULL DEFAULT '',
  `province` varchar(25) NOT NULL DEFAULT '',
  `postalcode` char(8) NOT NULL DEFAULT '',
  `employer_code` char(100) NOT NULL DEFAULT '',
  `employer_name` char(100) NOT NULL DEFAULT '',
  `spouse_firstname` varchar(30) NOT NULL DEFAULT '',
  `spouse_lastname` varchar(30) NOT NULL DEFAULT '',
  `spouse_dob` date NOT NULL DEFAULT '0000-00-00',
  `past_service` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `pre98_service` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `post97_pension` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `total_service` decimal(8,4) unsigned NOT NULL DEFAULT '0.0000',
  `total_earnings` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `earnings` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `tot_ee_cont` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `ee_cont` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `tot_er` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `curr_er` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `tot_int` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `total_pension` decimal(7,2) NOT NULL DEFAULT '0.00',
  `ee_contr_ind` char(1) NOT NULL DEFAULT '',
  `ee_bridge_falg` char(1) NOT NULL DEFAULT '',
  KEY `sin` (`sin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT 'resources',
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` mediumblob,
  `site_id` tinyint(3) unsigned NOT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `division` varchar(1) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `fmdid` (`file`,`mid`,`division`,`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

--
-- Triggers `resources`
--
DROP TRIGGER IF EXISTS `delete_cr2`;
DELIMITER //
CREATE TRIGGER `delete_cr2` AFTER DELETE ON `resources`
 FOR EACH ROW begin
	delete from contents_resources where rid=OLD.rid;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(4) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `tid` tinyint(3) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `table_a`
--

DROP TABLE IF EXISTS `table_a`;
CREATE TABLE IF NOT EXISTS `table_a` (
  `L0` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G10` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G15` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `AGE` int(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_b`
--

DROP TABLE IF EXISTS `table_b`;
CREATE TABLE IF NOT EXISTS `table_b` (
  `L0` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G10` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G15` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `AGE` int(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_g`
--

DROP TABLE IF EXISTS `table_g`;
CREATE TABLE IF NOT EXISTS `table_g` (
  `L0` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G10` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `G15` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `AGE` int(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js60_a`
--

DROP TABLE IF EXISTS `table_js60_a`;
CREATE TABLE IF NOT EXISTS `table_js60_a` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js60_b`
--

DROP TABLE IF EXISTS `table_js60_b`;
CREATE TABLE IF NOT EXISTS `table_js60_b` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js60_g`
--

DROP TABLE IF EXISTS `table_js60_g`;
CREATE TABLE IF NOT EXISTS `table_js60_g` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js75_a`
--

DROP TABLE IF EXISTS `table_js75_a`;
CREATE TABLE IF NOT EXISTS `table_js75_a` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js75_b`
--

DROP TABLE IF EXISTS `table_js75_b`;
CREATE TABLE IF NOT EXISTS `table_js75_b` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js75_g`
--

DROP TABLE IF EXISTS `table_js75_g`;
CREATE TABLE IF NOT EXISTS `table_js75_g` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js100_a`
--

DROP TABLE IF EXISTS `table_js100_a`;
CREATE TABLE IF NOT EXISTS `table_js100_a` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js100_b`
--

DROP TABLE IF EXISTS `table_js100_b`;
CREATE TABLE IF NOT EXISTS `table_js100_b` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `table_js100_g`
--

DROP TABLE IF EXISTS `table_js100_g`;
CREATE TABLE IF NOT EXISTS `table_js100_g` (
  `AGE` int(3) unsigned NOT NULL DEFAULT '0',
  `Y50` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y51` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y52` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y53` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y54` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y55` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y56` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y57` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y58` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y59` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y60` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y61` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y62` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y63` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y64` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y65` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y66` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y67` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y68` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y69` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y70` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000',
  `Y71` decimal(5,4) unsigned NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `tid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT 'default',
  `filename` varchar(100) NOT NULL,
  `preview` blob NOT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(50) DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_components`
--

DROP TABLE IF EXISTS `users_components`;
CREATE TABLE IF NOT EXISTS `users_components` (
  `ucid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `cid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ucid`),
  UNIQUE KEY `u_c_id` (`uid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_modules`
--

DROP TABLE IF EXISTS `users_modules`;
CREATE TABLE IF NOT EXISTS `users_modules` (
  `umid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`umid`),
  UNIQUE KEY `u_m_id` (`uid`,`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_sites`
--

DROP TABLE IF EXISTS `users_sites`;
CREATE TABLE IF NOT EXISTS `users_sites` (
  `usid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`usid`),
  UNIQUE KEY `u_s_id` (`uid`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `u_members`
--

DROP TABLE IF EXISTS `u_members`;
CREATE TABLE IF NOT EXISTS `u_members` (
  `employer` varchar(255) NOT NULL DEFAULT '',
  `sin` varchar(255) NOT NULL DEFAULT '',
  `division` varchar(255) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `surname` varchar(255) NOT NULL DEFAULT '',
  `address1` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) NOT NULL DEFAULT '',
  `post_code` varchar(255) NOT NULL DEFAULT '',
  `birth_date` varchar(255) NOT NULL DEFAULT '',
  `gender` varchar(255) NOT NULL DEFAULT '',
  `sp_first_name` varchar(255) NOT NULL DEFAULT '',
  `sp_last_name` varchar(255) NOT NULL DEFAULT '',
  `sp_dob` varchar(255) NOT NULL DEFAULT '',
  `plan_entr_date` varchar(255) NOT NULL DEFAULT '',
  `total_service` varchar(255) NOT NULL DEFAULT '',
  `tot_earn` varchar(255) NOT NULL DEFAULT '',
  `curr_earn` varchar(255) NOT NULL DEFAULT '',
  `tot_ee` varchar(255) NOT NULL DEFAULT '',
  `curr_ee` varchar(255) NOT NULL DEFAULT '',
  `tot_er` varchar(255) NOT NULL DEFAULT '',
  `curr_er` varchar(255) NOT NULL DEFAULT '',
  `total_pension` varchar(255) NOT NULL DEFAULT '',
  KEY `sin` (`sin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_contents`
--
DROP VIEW IF EXISTS `vw_contents`;
CREATE TABLE IF NOT EXISTS `vw_contents` (
`cid` int(10) unsigned
,`linkname` varchar(255)
,`title` varchar(255)
,`notes` text
,`content` text
,`site_id` tinyint(3) unsigned
,`sname` varchar(255)
,`mid` smallint(5) unsigned
,`mname` varchar(255)
,`division` varchar(1)
,`weight` tinyint(3) unsigned
,`active` enum('Y','N')
,`createdby` varchar(50)
,`created` timestamp
,`updatedby` varchar(50)
,`updated` timestamp
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_resources`
--
DROP VIEW IF EXISTS `vw_resources`;
CREATE TABLE IF NOT EXISTS `vw_resources` (
`rid` int(10) unsigned
,`file` varchar(255)
,`title` varchar(255)
,`notes` text
,`content` mediumblob
,`site_id` tinyint(3) unsigned
,`sname` varchar(255)
,`mid` smallint(5) unsigned
,`mname` varchar(255)
,`division` varchar(1)
,`active` enum('Y','N')
,`createdby` varchar(50)
,`created` timestamp
,`updatedby` varchar(50)
,`updated` timestamp
);
-- --------------------------------------------------------

--
-- Structure for view `vw_contents`
--
DROP TABLE IF EXISTS `vw_contents`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cdatcom_demo`@`localhost` SQL SECURITY DEFINER VIEW `vw_contents` AS select `contents`.`cid` AS `cid`,`contents`.`linkname` AS `linkname`,`contents`.`title` AS `title`,`contents`.`notes` AS `notes`,`contents`.`content` AS `content`,`contents`.`site_id` AS `site_id`,(select `sites`.`name` from `sites` where (`sites`.`site_id` = `contents`.`site_id`)) AS `sname`,`contents`.`mid` AS `mid`,(select `modules`.`name` from `modules` where (`modules`.`mid` = `contents`.`mid`)) AS `mname`,`contents`.`division` AS `division`,`contents`.`weight` AS `weight`,`contents`.`active` AS `active`,`contents`.`createdby` AS `createdby`,`contents`.`created` AS `created`,`contents`.`updatedby` AS `updatedby`,`contents`.`updated` AS `updated` from `contents`;

-- --------------------------------------------------------

--
-- Structure for view `vw_resources`
--
DROP TABLE IF EXISTS `vw_resources`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cdatcom_demo`@`localhost` SQL SECURITY DEFINER VIEW `vw_resources` AS select `resources`.`rid` AS `rid`,`resources`.`file` AS `file`,`resources`.`title` AS `title`,`resources`.`notes` AS `notes`,`resources`.`content` AS `content`,`resources`.`site_id` AS `site_id`,(select `sites`.`name` from `sites` where (`sites`.`site_id` = `resources`.`site_id`)) AS `sname`,`resources`.`mid` AS `mid`,(select `modules`.`name` from `modules` where (`modules`.`mid` = `resources`.`mid`)) AS `mname`,`resources`.`division` AS `division`,`resources`.`active` AS `active`,`resources`.`createdby` AS `createdby`,`resources`.`created` AS `created`,`resources`.`updatedby` AS `updatedby`,`resources`.`updated` AS `updated` from `resources`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
