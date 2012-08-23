-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipw10.eigbox.net
-- Generation Time: Feb 13, 2012 at 06:50 PM
-- Server version: 5.0.91
-- PHP Version: 4.4.9
-- 
-- Database: `church`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `actions`
-- 

CREATE TABLE `actions` (
  `uid` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `keyword` varchar(20) default NULL,
  `action` text NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `aid` int(9) NOT NULL auto_increment,
  PRIMARY KEY  (`aid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `actions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `admin_users`
-- 

CREATE TABLE `admin_users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `level` tinyint(1) default '1',
  `lname` varchar(100) character set gb2312 default NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `firstname` varchar(30) character set gb2312 default NULL,
  `lastname` varchar(30) character set gb2312 default NULL,
  `email` varchar(100) default NULL,
  `description` text,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `admin_users`
-- 

INSERT INTO `admin_users` VALUES (4, 1, 'SurreyOneFamily', 'OneFamily', 'ILoveJesus', 'Surrey One Family Admin User', 'Surrey One Family Admin User', 'admin@surreyonefamily.ca', 'Surrey One Family Admin User', 'Y', NULL, '0000-00-00 00:00:00', NULL, '2012-02-12 22:00:16');

-- --------------------------------------------------------

-- 
-- Table structure for table `answers`
-- 

CREATE TABLE `answers` (
  `uid` int(11) NOT NULL auto_increment,
  `qid1` tinyint(2) NOT NULL,
  `answer1` varchar(100) NOT NULL,
  `qid2` tinyint(2) NOT NULL,
  `answer2` varchar(100) NOT NULL,
  `adate` date default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `answers`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `common_users`
-- 

CREATE TABLE `common_users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `firstname` varchar(30) default NULL,
  `lastname` varchar(30) default NULL,
  `gwl` varchar(20) default NULL,
  `dob` date default NULL,
  `sex` enum('M','F') default NULL,
  `division` varchar(1) default NULL,
  `address1` varchar(100) default NULL,
  `address2` varchar(100) default NULL,
  `city` varchar(50) default NULL,
  `prov` char(2) default NULL,
  `postalcode` char(7) default NULL,
  `phone` varchar(15) default NULL,
  `email` varchar(100) default NULL,
  `description` text,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `common_users`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `contact`
-- 

CREATE TABLE `contact` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `question` text,
  `active` enum('Y','N') default 'Y',
  `reply` enum('Y','N') default 'N',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `afile` mediumblob,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `contact`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `contents`
-- 

CREATE TABLE `contents` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `linkname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` text NOT NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `sname` varchar(255) default NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `mname` varchar(255) default NULL,
  `division` varchar(1) default NULL,
  `weight` tinyint(3) unsigned default '0',
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `title_mid` (`linkname`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- 
-- Dumping data for table `contents`
-- 

INSERT INTO `contents` VALUES (1, '??????????', '??????????', '??????????', '<p><span style="font-size: 16px; line-height: 20px; font-family: comic sans ms,sans-serif;">??????, ??: </span></p>\r\n<p>&nbsp;</p>\r\n<p>????????, ???????????!</p>\r\n<p>&nbsp;</p>\r\n<p>?????????????????,???????????????????,????????????(??????????),?????? ?,????????????????,??????????????,???????????????????,??????????</p>\r\n<p>&nbsp;</p>\r\n<p>?????????????,???????????,????????????????????????,????????;????????? ???,???????,????????????????;?????????????????????????????,??????,?????? ???????Scarborough Community Alliance Church?,???????<a href="http://scac.org/">????????</a>??,????????????????!</p>\r\n<p>&nbsp;</p>\r\n<p>??????????????,????????????????,??????;??????????????????????,????????????????????????????????????????!</p>\r\n<p>&nbsp;</p>\r\n<p>? ???????!</p>', 1, 'SurreyOneFamily', 1, '??', '', 255, 'Y', 'SurreyOneFamily', '2011-12-24 17:35:25', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (2, '????', '????', '????', '', 1, 'SurreyOneFamily', 2, '?????', '', 0, 'Y', 'SurreyOneFamily', '2011-12-24 17:37:46', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (3, '????', '????', '????', '', 1, 'SurreyOneFamily', 2, '?????', '', 0, 'Y', 'SurreyOneFamily', '2011-12-24 17:38:30', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (4, '????', '????', '????', '', 1, 'SurreyOneFamily', 2, '?????', '', 0, 'Y', 'SurreyOneFamily', '2011-12-24 17:39:04', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (5, '????', '????', '????', '', 1, 'SurreyOneFamily', 2, '?????', '', 0, 'Y', 'SurreyOneFamily', '2011-12-24 17:39:42', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (6, '????', 'Admin', '????', '', 1, 'SurreyOneFamily', 2, '?????', '', 0, 'Y', 'SurreyOneFamily', '2011-12-24 17:40:11', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (24, 'Welcome the new baby coming to the world!?', 'ABEL LIU', 'Welcome the new baby coming to the world!?', '<p><span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">Hi William:</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">&nbsp;</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">We have new member coming to church:</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">&nbsp;</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">???<span class="Apple-converted-space">&nbsp;</span></span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">&nbsp;</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">Tel: 604-588-2970</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">&nbsp;</span><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /> <span style="color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 17px; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff; display: inline !important; float: none;">E-mail:<span class="Apple-converted-space">&nbsp;</span></span><a style="line-height: 17px; font-weight: normal; text-decoration: underline; color: #0068cf; cursor: pointer; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" href="mailto:johnny.li@ymail.com">johnny.li@ymail.com</a><br style="line-height: 17px; color: #2a2a2a; font-family: ''Segoe UI'', Tahoma, Verdana, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: #ffffff;" /></p>', 1, 'SurreyOneFamily', 9, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 16:46:12', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (18, '???', 'Kevin Xu', '??? - (Kevin Xu)', '<p>?????</p>\r\n<p><span lang="EN-US"><a title="blocked::http://www.tudou.com/programs/view/N6dbt-mWaec/" href="http://www.tudou.com/programs/view/N6dbt-mWaec/" target="_blank">http://www.tudou.com/programs/view/N6dbt-mWaec/</a></span></p>\r\n<p>«??????»</p>\r\n<p><span lang="EN-US"><a title="blocked::http://fyyy.tv/html/558.html" href="http://fyyy.tv/html/558.html" target="_blank">http://fyyy.tv/html/558.html</a></span></p>\r\n<p>??????????????????????????????</p>\r\n<p>??<span lang="EN-US">&nbsp;Psalms</span></p>\r\n<p><span lang="EN-US">11:3 [hgb]&nbsp;</span>?????,?????????<span lang="EN-US">&nbsp;</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [kjv]&nbsp; If the foundations be destroyed, what can the righteous do?</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [bbe]&nbsp; If the bases are broken down, what is the upright man to do?</span></p>\r\n<p>????<span lang="EN-US">&nbsp;Isaiah</span></p>\r\n<p><span lang="EN-US">33:22 [hgb]&nbsp;</span>???????????,???????????,????????????????<span lang="EN-US">&nbsp;</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [kjv]&nbsp; For the LORD is our judge, the LORD is our lawgiver, the LORD is our king; he will save us.</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [bbe]&nbsp; For the Lord is our judge, the Lord is our law-giver, the Lord is our king; he will be our saviour.</span></p>\r\n<p>????<span lang="EN-US">&nbsp;Jeremiah</span></p>\r\n<p><span lang="EN-US">17:9 [hgb]&nbsp;</span>????????,????,??????<span lang="EN-US">&nbsp;</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [kjv]&nbsp; The heart is deceitful above all things, and desperately wicked: who can know it?</span></p>\r\n<p><span lang="EN-US">&nbsp;&nbsp;&nbsp; [bbe]&nbsp; The heart is a twisted thing, not to be searched out by man: who is able to have knowledge of it?</span></p>', 1, 'SurreyOneFamily', 8, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 20:11:02', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (17, '????????WENDY ???????', 'Zi Qin', '????????WENDY ???????', '<p>This Saturday morning (2012.02.04) around 9:00AM, Wendy gave birth in BC Women''s Hospital safely and in good health.   It is a boy, weighing 4300g. Actually a big baby!   Welcome the new baby coming to the world!   Thanks to our Lord for the watching out and love.   As happy grandparents, John and Zi-Qin are now busy at the hospital to care about their daugher and grandson.    They feel sorry if they miss your call for celebrating and enquirying purposes.   All glory to our Gracious Father, please keep on looking after the baby and the family.</p>\r\n<p>&nbsp;</p>\r\n<p>?????????, ???!   ????????WENDY ??????? ?????42?? ??????????????? ???????? ????? ????????? ????BC Womens ???????? ????????  ??????????????? ????????????????????????????? ???????????   ????     ???</p>', 1, 'SurreyOneFamily', 5, '?????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 20:06:05', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (10, '????', '????', '????', '<div class="wrapper bot">\r\n<div class="img-box"><a href="http://www.surreyonefamily.ca/index.php"><img class="png" src="images/pic1.png" alt="Surrey Christian Alliance Church 2012 Theme" /></a>2012 Church Theme:<br /> <br /> <strong>Happy to serve Lord</strong></div>\r\n<div class="img-box"><img class="png" src="images/pic2.png" alt="Surrey Christian Alliance Church 2012?????" /> 2012?????:<br /> <br /> <strong>???????</strong></div>\r\n</div>', 1, 'SurreyOneFamily', 1, '??', '', 254, 'Y', 'SurreyOneFamily', '2012-02-04 18:04:10', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (11, '??banner', 'Admin', '??banner', '<div class="flexslider">\r\n<ul class="slides">\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-10.jpg" src="images/slide-10.jpg" alt="" width="308" height="285" />\r\n<p class="flex-caption">????</p>\r\n</a> </li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-11.jpg" src="images/slide-11.jpg" alt="" width="308" height="285" /></a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-12.jpg" src="images/slide-12.jpg" alt="" width="308" height="285" /></a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-20.jpg" src="images/slide-20.jpg" alt="" width="308" height="285" />\r\n<p class="flex-caption">?????</p>\r\n</a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-21.jpg" src="images/slide-21.jpg" alt="" width="308" height="285" /></a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-22.jpg" src="images/slide-22.jpg" alt="" width="308" height="285" /> </a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-30.jpg" src="images/slide-30.jpg" alt="" width="308" height="285" />\r\n<p class="flex-caption">?????</p>\r\n</a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-31.jpg" src="images/slide-31.jpg" alt="" width="308" height="285" /> </a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-32.jpg" src="images/slide-32.jpg" alt="" width="308" height="285" /></a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-40.jpg" src="images/slide-40.jpg" alt="" width="308" height="285" />\r\n<p class="flex-caption">???</p>\r\n</a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-41.jpg" src="images/slide-41.jpg" alt="" width="308" height="285" /> </a></li>\r\n<li><a href="http://www.surreyonefamily.ca"><img title="slide-42.jpg" src="images/slide-42.jpg" alt="" width="308" height="285" /> </a></li>\r\n</ul>\r\n</div>', 1, 'SurreyOneFamily', 1, '??', '', 253, 'Y', 'SurreyOneFamily', '2012-02-04 18:19:59', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (12, '????', '????', '????', '<h2>????</h2>\r\n<div class="wrapper"><img class="img-indent" src="images/2page-img1.jpg" alt="" />\r\n<h5>??????(?????,??:Christian and Missionary Alliance,??: C&amp;MA)</h5>\r\n<p>??????(?????,??:Christian and Missionary Alliance,??: C&amp;MA),????????????,??????1887????????????????????????????Colorado Springs??1888???,??????????????????????????????????????75???,??????200??</p>\r\n<p>???1887????????????(the Christian Alliance)?????,?????????(the Evangelical Missionary Alliance),?????????????1897?,????????????(Christian and Missionary Alliance,??????????????),????????????????????<br /> ????????????????????????,?????????????,?????????????????????????????,??,??????????????????????</p>\r\n</div>', 1, 'SurreyOneFamily', 4, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 19:25:01', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (13, '????', '????', '????', '<h2>????</h2>\r\n<div class="wrapper"><img class="img-indent" src="images/2page-img2.jpg" alt="" />\r\n<h5>????</h5>\r\n<p>??????,????,??????:??,??,???     (?44:6,45:5-6;?5:48;?32:4;?3:16-17;?28:19)<br /> ??????????????????,??????????????,?????,?????,????????,?????????????????????????????????????????????     (?2:6-11;?2:14-18;?2:9;?1:18;?1:35;??15:3-5;??2:2;?13:39;?4:14-15,9:24-28;?25:31-34;?1:11)<br /> ???????,??????,???????,????,?????????????     (?14:16-17,16:7-11;??2:10-12)<br /> ????????,???????,????????????,???????????     (??3:16-17;??1:20-21)<br /> ??????,????????,????????,????????,??????,??????????,??????,???????????????     (?1:27;?8:8;??2:2;?25:41-46;??1:7-10)<br /> ??????????;??????????,??????,???????     (?3:5-7;?2:38;?1:12;??6:11)<br /> ?????????????,????,???????,????,????,??????,??????????????,?????????????????????     (??5:23;?1:8;?12:1-2;?5:16-25)<br /> ????????,??????????,???????????????,???????????     (?8:16-17;?5:13-16)<br /> ????????????,???????,???????(??)???,?????????????????????????????????????????,??????,????,????,????,?????????,????,????,?????     (?3:6-12;?28:19-29;?1:22-23;?2:41-47;?10:25)<br /> ??????,??????,????????     (??15:20-23;??1:7-10)<br /> ????????,??????????????????,??????????,???????     (??4:13-17;??1:7;?2:11-14)</p>\r\n</div>', 1, 'SurreyOneFamily', 4, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 19:29:53', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (14, '????', '????', '????', '<h2>????</h2>\r\n<div class="wrapper"><img class="img-indent" src="images/2page-img3.jpg" alt="" />\r\n<h5>??????????? (Church Purpose Statement) :</h5>\r\n<p><strong>Church Purpose Statement:</strong><br /> <br /> &ldquo;To bring people to membership in Jesus&rsquo; family, develop them to Christ-like maturity, and equip them for their ministry and mission in the world in order to magnify God&rsquo;s name.&rdquo;</p>\r\n<br />\r\n<p style="line-height: 35px;"><strong>???????????:</strong><br /> ?????????,?????????<span class="highlight">??</span>,???????<span class="highlight">??</span>,??????????<span class="highlight">??</span>,?????<span class="highlight">??</span>,???<span class="highlight">??</span>????</p>\r\n</div>', 1, 'SurreyOneFamily', 4, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 19:31:47', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (15, '??????????', 'Admin', '??????????', '<h2>??????????</h2>\r\n<div class="wrapper"><img class="img-indent" src="images/2page-img4.jpg" alt="" />\r\n<h5>??????????</h5>\r\n<p style="line-height: 30px;"><strong>????:</strong> &lt;&gt;?????&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>???:</strong> &lt;&gt;?????<br /> <strong>??:</strong>&lt;&gt;???&lt;&gt;???&lt;&gt;???&lt;&gt;???<br /> <strong>??:</strong>&lt;&gt;???&lt;&gt;???&lt;&gt;????&lt;&gt;???&lt;&gt;????<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&gt;????&lt;&gt;???&lt;&gt;????&lt;&gt;?????<br /> <br /> <br /></p>\r\n<table border="0" cellspacing="5" cellpadding="10" width="100%">\r\n<tbody>\r\n<tr>\r\n<td align="center"><img src="images/yang.jpg" alt="?????" /></td>\r\n<td align="center"><img src="images/lin.jpg" alt="?????" /></td>\r\n</tr>\r\n<tr>\r\n<td align="center"><br /> ?????</td>\r\n<td align="center"><br /> ?????</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n</div>', 1, 'SurreyOneFamily', 4, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 19:32:41', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (16, '????', 'Admin', '????', '<div class="wrapper">\r\n<div class="box">\r\n<div class="border-right">\r\n<div class="xcontent">\r\n<div class="inner">\r\n<h2>Worship Services<br /> ????</h2>\r\n<div class="special"><img src="images/4page-img1.jpg" alt="" />\r\n<h5>\r\n<p>English:<br /> <span style="font-weight: normal;">Sunday 9:30 AM<br /> ??:?????9:30</span></p>\r\n<p>Chinese(Mandarin/Cantonese):<br /> <span style="font-weight: normal;">Sunday 11:00 AM<br /> ?????/???:?????11:00</span></p>\r\n<p>Children Worship:<br /> <span style="font-weight: normal;">Sunday 9:30 AM<br /> ????: ?????9:30</span></p>\r\n</h5>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class="box">\r\n<div class="border-right">\r\n<div class="xcontent">\r\n<div class="inner">\r\n<h2>Sunday School<br /> ???</h2>\r\n<div class="special"><img src="images/4page-img2.jpg" alt="" />\r\n<h5>\r\n<p>Chinese Adult (Mandarin):9:30 AM <br /> <span style="font-weight: normal;">?????????????9:30</span></p>\r\n<p>Chinese Adult (Cantonese):9:30 AM<br /> <span style="font-weight: normal;">?????????????9:30</span></p>\r\n<p>Children/Teens/Young Adult/Adult (English):11:00 AM<br /> <span style="font-weight: normal;">??/???/???????????11:00</span></p>\r\n</h5>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class="box">\r\n<div class="border-right">\r\n<div class="xcontent">\r\n<div class="inner">\r\n<h2>Fellowship<br /> ??</h2>\r\n<div class="special"><img src="images/4page-img3.jpg" alt="" />\r\n<h5>\r\n<p><span style="font-weight: normal;"><strong>????????</strong><br /> ??? ?????10:00</span></p>\r\n<p><span style="font-weight: normal;"><strong>????????</strong><br /> ??? ?????7:30</span></p>\r\n<p><span style="font-weight: normal;"><strong>????????</strong><br /> ??? ?????7:30</span></p>\r\n<p><span style="font-weight: normal;"><strong>???????/???</strong><br /> ??????7:30</span></p>\r\n<p><span style="font-weight: normal;"><strong>????????</strong><br /> ??????</span></p>\r\n<p><span style="font-weight: normal;"><strong>???????/???</strong><br /> ??????</span></p>\r\n</h5>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', 1, 'SurreyOneFamily', 3, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 19:37:33', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (19, 'Worship God As You Are', 'Winston Wu', 'Worship God As You Are (Winston Wu)', '<table border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td valign="bottom" bgcolor="white">Worship God As You Are</td>\r\n</tr>\r\n<tr>\r\n<td valign="center" bgcolor="white">\r\n\r\n<div>Ephesians 5:27<br /> <strong>27</strong><strong>that He might present her to Himself a glorious church, not having spot or wrinkle or any such thing, but that she should be holy and without blemish.</strong><strong>&nbsp;</strong><strong></strong><strong></strong><strong></strong></div>\r\n\r\n</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td width="420" align="left" valign="top" bgcolor="white">\r\n<table border="0" cellspacing="0" cellpadding="0" width="420" align="left" bgcolor="white">\r\n<tbody>\r\n<tr>\r\n<td bgcolor="white">Have you been to a worship service where you stood with your hands raised, ready to worship God, only to hear the worship leader say, &ldquo;Before we worship God, let&rsquo;s all search our hearts&rdquo;? Then, as you search your heart, before long, you dig up something that you don&rsquo;t like. The next thing you know, your hands are no longer raised and soon, you find yourself sitting down &mdash; you don&rsquo;t feel good worshiping God any more.</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">My friend, I have good news for you. Come and worship Him as you are. Come into God&rsquo;s presence Jesus-conscious, forgiveness-conscious and grace-conscious! Come into God&rsquo;s presence bringing only Jesus the spotless Lamb of God as your offering.</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">Your worshiping God is like the times God&rsquo;s people in the Old Testament brought their offerings to the priest. The priest examined the animal sacrifice to ensure that it was without blemish. The priest did not examine the sinner. If the animal was without blemish, God accepted the animal sacrifice as well as the offerer who brought the animal.</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">So when you come into God&rsquo;s presence, don&rsquo;t do any self-examination and be discouraged when you find faults in your life. God does not examine you. He sees you through the full value, and all the loveliness and acceptance of His Son. (Ephesians 1:6) He sees you holy and without blemish!</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">Remember the woman who was referred to as a sinner, possibly a prostitute? She went to the house of the Pharisee who was hosting Jesus and wiped Jesus&rsquo; feet with her hair. (Luke 7:36&ndash;50) That was her act of worship.</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">She came to Him as she was. Yes, she knew that she had sinned, but she worshiped Jesus first because of who He is. She was Jesus-conscious. Then, she heard Jesus say, &ldquo;Your sins are forgiven.&rdquo; (Luke 7:48) She received the forgiveness she needed from Him.</td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white"><br /></td>\r\n</tr>\r\n<tr>\r\n<td bgcolor="white">Whatever you need from the Savior, come to Him as you are. Worship Him for who He is and then you will hear Him say, &ldquo;Go your way. Your restoration has come. Your prosperity is here. You are healed. You have been made whole!&rdquo;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, 'SurreyOneFamily', 8, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 20:56:34', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (20, '????2012????????', '????2012????????', '????2012????????', '\r\n<div class="WordSection1">\r\n<p class="MsoNormal"><span lang="FR-CA">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: center; background: white;"><strong><span style="font-size: 20.0pt; line-height: 105%; font-family: ??; color: red;" lang="ZH-CN">????</span></strong><strong><span style="font-size: 20.0pt; line-height: 105%; font-family: ??; color: red;" lang="FR-CA">2012</span></strong><strong><span style="font-size: 20.0pt; line-height: 105%; font-family: ??; color: red;" lang="ZH-CN">????????</span></strong></p>\r\n<table class="MsoTableLightShadingAccent6" style="border-collapse: collapse; border: none;" border="1" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border-top: solid #F79646 1.0pt; border-left: none; border-bottom: solid #F79646 1.0pt; border-right: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">??</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border-top: solid #F79646 1.0pt; border-left: none; border-bottom: solid #F79646 1.0pt; border-right: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">First </span></strong></p>\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">Service Person</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border-top: solid #F79646 1.0pt; border-left: none; border-bottom: solid #F79646 1.0pt; border-right: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">Second </span></strong></p>\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">Service Person</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border-top: solid #F79646 1.0pt; border-left: none; border-bottom: solid #F79646 1.0pt; border-right: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">Third</span></strong></p>\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">&nbsp;Service Person</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">2</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">05</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Bing An</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Daniel</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Sandy</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 961 &ndash; 4811</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(778) 999 &ndash; 7680</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 507 &ndash; 2983</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">3</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">12</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Ping Xu</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">??</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Grace Sang</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(778) 395 - 5282</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 552 &ndash; 8599</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 288 &ndash; 4653</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">4</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">01</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Rebecca Lee</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Ingrid Yuan</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Deborah Chen</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 538 - 2377</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 274 &ndash; 6829</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 365 &ndash; 0558</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">5</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">06</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">???</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">??</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">??</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 544 - 3049</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 538 &ndash; 9225</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 998 &ndash; 1869</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">6</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">03</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Steve Cheng</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Howard</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Lily </span><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(778) 893 &ndash; 7060</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 839 &ndash; 9668</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 588 &ndash; 8298</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">7</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">01</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">???</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 12.0pt; font-family: ??; color: #e36c0a;" lang="ZH-CN">??</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(778) 574 &ndash; 7277</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 288 &ndash; 4611</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">8</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">05</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">9</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">02</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">10</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">7</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">Elaine Roh</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" rowspan="2" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 12.0pt; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;; color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 119.7pt; border: none; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">(604) 585 &ndash; 8816</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">11</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">4</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; background: #FDE4D0; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n<tr style="height: 22.7pt;">\r\n<td style="width: 110.8pt; border: none; border-bottom: solid #F79646 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="148">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="color: #e36c0a;" lang="FR-CA">2012</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">12</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong><strong><span style="color: #e36c0a;" lang="FR-CA">2</span></strong><strong><span style="font-family: ??; color: #e36c0a;" lang="ZH-CN">?</span></strong></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; border-bottom: solid #F79646 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; border-bottom: solid #F79646 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n<td style="width: 119.7pt; border: none; border-bottom: solid #F79646 1.0pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 22.7pt;" width="160" valign="top">\r\n<p class="MsoNormal" style="margin-bottom: .0001pt; line-height: normal;"><span style="color: #e36c0a;" lang="FR-CA">&nbsp;</span></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p class="MsoNormal"><span lang="FR-CA">&nbsp;</span></p>\r\n</div>', 1, 'SurreyOneFamily', 9, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 21:11:51', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (21, '2012????????????', 'Admin', '2012????????????', '<h2>2012????????????</h2>\r\n<table border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr style="height: 15.0pt;">\r\n<td height="20">&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl67" style="height: 24.75pt;" height="33">????</td>\r\n<td class="xl67" style="border-left: none;">????</td>\r\n<td class="xl67" style="border-left: none;">??</td>\r\n<td class="xl67" style="border-left: none;">????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Jan-28</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl79" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;</span></td>\r\n<td class="xl75">?????? (????????---????+??)</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Feb-10</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">???</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">778.574.7277</td>\r\n<td class="xl73" style="border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>?????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Feb-24</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">ABEL</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604-593-2514</td>\r\n<td class="xl76"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????:?????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Mar-09</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604-961.4811</td>\r\n<td class="xl73" style="border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>??????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Mar-23</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604.522.8599</td>\r\n<td class="xl77" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp; </span>????:?????????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Mar-30</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl78" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;</span></td>\r\n<td class="xl74" style="mso-ignore: colspan;" colspan="2">????????       (?????,?????????)</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Apr-13</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604-998-1869</td>\r\n<td class="xl78" style="border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>?????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Apr-27</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">???</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">778.574.7277</td>\r\n<td class="xl84" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????:?? ????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">May-11</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">ABEL</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604-593-2514</td>\r\n<td class="xl78" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">May-25</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604-961.4811</td>\r\n<td class="xl84" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????:????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Jun-08</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">???</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604.544.3049</td>\r\n<td class="xl78" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Jun-22</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">604.522.8599</td>\r\n<td class="xl84" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>????:?????</td>\r\n<td class="xl65">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td class="xl68" style="height: 24.75pt; border-top: none;" height="33">Jun-29</td>\r\n<td class="xl69" style="border-top: none; border-left: none;">??</td>\r\n<td class="xl69" style="border-top: none; border-left: none;"><span style="mso-spacerun: yes;">&nbsp;</span></td>\r\n<td class="xl74" style="mso-ignore: colspan;" colspan="2">????????       (?????,?????????)</td>\r\n</tr>\r\n<tr>\r\n<td class="xl66" style="height: 28.5pt;" colspan="5" height="38">1.<span style="mso-spacerun: yes;">&nbsp; </span>????<span>????       19:30PM</span></td>\r\n</tr>\r\n<tr>\r\n<td class="xl66" style="height: 28.5pt;" colspan="5" height="38">2.<span style="mso-spacerun: yes;">&nbsp; </span><span class="font10">????</span><span><span class="font10">???????</span>&nbsp;&nbsp;<span class="font5">ABEL<span style="mso-spacerun: yes;">&nbsp; </span>(604.593-2514) </span><span class="font10">???????</span></span></td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, 'SurreyOneFamily', 9, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 21:34:33', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (22, 'A nice Concert with good testimony', 'Daniel Lynn', 'A nice Concert with good testimony to share with you? (Daniel Lynn)', '<p>Dear Brother and Sister<br /><br />http://www.youtube.com/watch?v=_S9JSoyjsSM&amp;feature=share<br /><br />???????&nbsp;??????? <br /><br />?i''M??????,???????????,????????????????,???????????????,????????????????,???????&shy;,?????????,?????????,????????????,????????????,?????,??????????!??????????,?????&shy;,???????????????????,?????????????????,????????,????????????????????????,???????&shy;??????????????????,????????????<br /><br />Daniel Lynn<br /><br /></p>', 1, 'SurreyOneFamily', 8, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 21:41:19', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (23, '????????: HXMS the Lunar New Year of the Dragon 2012', 'William Jiang', '????????: HXMS the Lunar New Year of the Dragon 2012', '<p>????:<br /><br />???????? <br /><br />??????????????????<br /><br />????2?18????????????,????????????<br /><br />??:2?18??????5:00--9:00<br /><br />??:84 Ave/160 St ,Fleetwood????<br /><br />????:<br /><br />5:00?????<br /><br />6:00???????<br /><br />7:00??<br /><br />8:00??? <br /><br />??:??7?,6-12?3?,6?????<br /><br />??????????,??!<br /><br />Hello Members<br /><br />Here''s to the New Year... and all the promises, and hopes it brings!<br /><br />Wish you had a great start of the New Year with your families. Perhaps you will also celebrate the Lunar New Year of the Dragon 2012. Hua Xia Multiculture Society will host the annual celebration on February 19, 2012 at Fleetwood Recreation Centre. Please join us with delicious dumpling, Chinese traditional dancing show, music performance, ballroom dancing, prices draw and much much more&hellip;<br /><br />Date and Time: Saturday 5:00PM-9:00PM, February 19, 2012<br /><br />Location: Fleetwood Community Centre<br /><br />15996 - 84 Avenue<br /><br />Surrey, BC V4N 0W1<br /><br />Fee: Adult $7.00/per person, Age 6-12, $3.00, Age 6 or blow free<br /><br />Please RSVP by reply this email with your name and the number of persons to attend.<br /><br />Thank you,<br /><br />Nola Young<br /><br />Hua Xia Multiculture Society&nbsp;|&nbsp;www.hxms.org<br /><br /><br />604-782-9207 · Fax 604-583-9208<br /><br />nola.young@hxms.org<br /><br /><br />The Hua Xia Multiculture Society is actively involved in activities and events in the Chinese community as well as in larger society to promote the understanding of Chinese heritage and culture, and to contribute to the cultural diversity in the Great Vancouver and Lower mainland.<br /><br /><br />This electronic transmission, including attached documents, is intended for the sole use of the individual or entity to whom it is addressed.&nbsp;It is confidential and may be subject to attorney/client privilege.&nbsp;If you received this message in error, please do not distribute this to any other person, notify me at your earliest convenience, and destroy the attached message and attached documents, immediately.&nbsp;Thank you.<br /><br /></p>', 1, 'SurreyOneFamily', 11, '??????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-04 21:50:26', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (25, 'O Come All Ye Faithful by Celtic Woman', 'Admin', 'O Come All Ye Faithful by Celtic Woman\r\n\r\nhttp://youtu.be/45VGDNHJ4Zo', '<p>\r\n<object width="640" height="480" data="http://www.youtube.com/v/45VGDNHJ4Zo?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash">\r\n<param name="allowFullScreen" value="true" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="src" value="http://www.youtube.com/v/45VGDNHJ4Zo?version=3&amp;hl=en_US&amp;rel=0" />\r\n<param name="allowfullscreen" value="true" />\r\n</object>\r\n</p>', 1, 'SurreyOneFamily', 12, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 17:18:15', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (26, 'Away In A Manger', 'Admin', 'AWAY IN A MANGER', '<p>\r\n<object width="640" height="480" data="http://www.youtube.com/v/vftEpuxUo1E?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash">\r\n<param name="allowFullScreen" value="true" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="src" value="http://www.youtube.com/v/vftEpuxUo1E?version=3&amp;hl=en_US&amp;rel=0" />\r\n<param name="allowfullscreen" value="true" />\r\n</object>\r\n</p>', 1, 'SurreyOneFamily', 12, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 17:35:16', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (27, '????', 'Admin', '????', '<p>\r\n<object width="640" height="480" data="http://www.youtube.com/v/Oq7OglEixSM?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash">\r\n<param name="allowFullScreen" value="true" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="src" value="http://www.youtube.com/v/Oq7OglEixSM?version=3&amp;hl=en_US&amp;rel=0" />\r\n<param name="allowfullscreen" value="true" />\r\n</object>\r\n</p>', 1, 'SurreyOneFamily', 12, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 17:44:16', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (28, 'Introduction', 'Admin', 'Introduction', '<h3>?????????????</h3>', 1, 'SurreyOneFamily', 12, '????', '', 253, 'Y', 'SurreyOneFamily', '2012-02-05 17:46:35', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (29, '????????', 'Admin', '????????', '<p>\r\n<object width="853" height="480" data="http://www.youtube.com/v/FinxnwONLBE?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash">\r\n<param name="allowFullScreen" value="true" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="src" value="http://www.youtube.com/v/FinxnwONLBE?version=3&amp;hl=en_US&amp;rel=0" />\r\n<param name="allowfullscreen" value="true" />\r\n</object>\r\n</p>', 1, 'SurreyOneFamily', 12, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 17:51:33', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (30, '?????? Most Understanding Friend', 'Admin', '?????? Most Understanding Friend', '<p>\r\n<object width="640" height="480" data="http://www.youtube.com/v/vRcApTIwx7c?version=3&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash">\r\n<param name="allowFullScreen" value="true" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="src" value="http://www.youtube.com/v/vRcApTIwx7c?version=3&amp;hl=en_US&amp;rel=0" />\r\n<param name="allowfullscreen" value="true" />\r\n</object>\r\n</p>', 1, 'SurreyOneFamily', 12, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-05 20:42:10', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (31, '????', 'Rebecca', '????', '<div><span style="font-size: x-large; font-family: ''PrimaSans BT'', Verdana, sans-serif;">?????<span style="background-color: #ffffff; color: #ff0000;"><strong><span>???</span>,?</strong></span></span><span style="font-size: x-large;"><span style="background-color: #ffffff; color: #ff0000;"><strong><span style="font-family: ''times new roman'',''new york'',times,serif;">???</span><span style="font-family: Arial;">,&nbsp;7:30 PM</span></strong></span></span><span style="font-size: x-large; color: #2a2a2a; font-family: ''PrimaSans BT'', Verdana, sans-serif;"><span style="color: #000000;"><span style="color: #000000;">,?</span></span></span><span style="font-size: x-large; font-family: ''times new roman'',''new york'',times,serif;">??? (&nbsp;</span><span style="color: #2a2a2a; font-family: ''PrimaSans BT'', Verdana, sans-serif;"><span style="color: #000000;"><span style="color: #000000;"><span style="font-size: medium;">John</span><span style="font-size: large;"> </span><span style="font-size: medium;">Wang</span><span style="font-size: large;"> )&nbsp;</span><span style="font-size: medium;">and</span><span style="font-size: large;"> ???&nbsp;</span></span></span></span><span style="font-size: x-large; color: #2a2a2a; line-height: 15px; font-family: ''PrimaSans BT'', Verdana, sans-serif;" dir="ltr" lang="ZH-TW">?</span><span style="font-size: x-large; color: #2a2a2a; line-height: 15px; font-family: ''PrimaSans BT'', Verdana, sans-serif;" dir="ltr" lang="ZH-TW">??</span><span style="font-size: x-large; font-family: ''times new roman'',''new york'',times,serif;">??,??????</span></div>\r\n<div style="font-family: ''times new roman'', ''new york'', times, serif;"><span style="font-size: large;"><br /></span></div>\r\n<div style="font-family: ''times new roman'', ''new york'', times, serif;"><span style="font-size: large;"><span style="font-family: ''PrimaSans BT'', Verdana, sans-serif;">&nbsp;</span>?? : 15988 &nbsp; 36A st. Surrey</span></div>\r\n<div style="font-family: ''times new roman'', ''new york'', times, serif;"><span style="font-size: large;">&nbsp;?? :&nbsp;778.574.7277</span></div>\r\n<div><span style="font-size: large;"><br /></span></div>\r\n<div><span style="font-size: large;"><span class="ecxApple-tab-span" style="white-space: pre;"> </span><span class="ecxApple-tab-span" style="white-space: pre;"> </span><span class="ecxApple-tab-span" style="white-space: pre;"> </span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></div>\r\n<div><span style="font-size: large;">?????????:?????</span></div>\r\n<div><span style="font-size: large;">&nbsp;</span></div>\r\n<div><span style="font-size: large;"><br /></span></div>\r\n<div><span style="font-size: large;">Hope to see you all.</span></div>\r\n<div><span style="font-size: large;"><br /></span></div>\r\n<div><span style="font-size: large;"><br /></span></div>\r\n<div><span style="font-size: large;">Rebecca</span></div>', 1, 'SurreyOneFamily', 9, '????', '', 0, 'Y', 'SurreyOneFamily', '2012-02-09 18:48:39', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (32, 'banner', 'Admin', 'this is a banner', '<p>Enjoy the church life!  God Bless!</p>', 1, 'SurreyOneFamily', 3, '????', '', 253, 'Y', 'SurreyOneFamily', '2012-02-09 22:00:25', 'SurreyOneFamily', '2012-02-13 18:41:25');
INSERT INTO `contents` VALUES (33, '???!???? ???? ?????????', 'OneFamily', '???!???? ???? ?????????(?)', '<p>???????,?1????????,13??????<span style="color: #1e50a2;">??</span>,14???<span style="color: #1e50a2;">??</span>&hellip;&hellip;??????????????????????,?????:??5?1??10?2??10?20?,????????&ldquo;??&rdquo;?<span style="color: #1e50a2;">?&middot;??</span>???????????,???????????&middot;?????????,????????????&ldquo;??&rdquo;??????,??????????2012?????&hellip;&hellip;</p>\r\n<p>??????&ldquo;??&rdquo;</p>\r\n<p>???????????????????:????????&middot;????????????????1.8??,???????????,?????? ??&middot;????????,??????????????????????????????????&ldquo;??&rdquo;:???????25??????1:2??? ??????,????????????!????????????&hellip;&hellip;</p>\r\n<p>????90?,?????????????,???????????????&middot;?????,???????????????21????????????????,?????????????????????,?????????</p>\r\n<p>?2011???,?????????????????????2010?????????????????,???????&ldquo;????&rdquo;??? &ldquo;?????&rdquo;:2011?5?1?,???????????1:0????,??,?&middot;?????;??10?2?,?????????1:2??,? ??????,???,????????????,???????????,????????????????,??????????10?19?,?? ???1:0????,????????,???,?????????????,???????2012?????&ldquo;?????&rdquo;??????????? ?????????2:1??????,?????????</p>\r\n<p>&ldquo;???????,?????,??????????,?????????,?,?????????&hellip;&hellip;&rdquo;????????????????????????????,??2012???????,??????????????,????????<a title="??" href="http://tuan.163.com/?tag=%E5%A8%B1%E4%B9%90"><span style="color: #1e50a2;">??</span></a>????????????&middot;??????????????,&ldquo;?????,??????????????,??????????????,???????????&hellip;&hellip;&rdquo;</p>\r\n<p>???????G????</p>\r\n<p>??????????,???????????????????????,??????,??????&ldquo;??&rdquo;????????????????? ?&middot;??????,?????????????????,???,&ldquo;????????,??????,??????&rdquo;????,&ldquo;??????????? ??????,?????????????????!&rdquo;</p>\r\n<p>????????????????????????????,?????????1990?12?26??&ldquo;12?26?&rdquo;??????????? ??:2006?,?????????&middot;????;1993?,???????????;1972?,??????&middot;S&middot;?????;1968?,??? ?????????;1931?,????????????????????&hellip;&hellip;???????????????????:1???,????;13?? ?,?????;14???,???????,???????16?????,???????,18????,???????????????????? ??????,???????????????,??????????????,???????</p>\r\n<p>?????????????,?????????????:?????????????;???????????,?&ldquo;???&rdquo;????????? ???????????????????LKJH,????:&ldquo;????,?????G!???G??????????!?????????????? ?!&rdquo;</p>', 1, 'SurreyOneFamily', 1, '??', '', 0, 'Y', 'OneFamily', '2012-02-12 22:06:17', 'OneFamily', '2012-02-12 22:06:17');

-- --------------------------------------------------------

-- 
-- Table structure for table `contents_resources`
-- 

CREATE TABLE `contents_resources` (
  `cid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  UNIQUE KEY `crid` (`rid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `contents_resources`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `divisions`
-- 

CREATE TABLE `divisions` (
  `division` varchar(1) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) default NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `divisions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `levels`
-- 

CREATE TABLE `levels` (
  `level` tinyint(1) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text,
  `active` enum('Y','N') character set ucs2 default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `levels`
-- 

INSERT INTO `levels` VALUES (1, 'general purpose', 'general purpose', 'Y', 'SurreyOneFamily', '2011-12-24 00:15:05', 'SurreyOneFamily', '2012-01-02 16:04:32');

-- --------------------------------------------------------

-- 
-- Table structure for table `login_info`
-- 

CREATE TABLE `login_info` (
  `uid` int(10) unsigned NOT NULL,
  `ip` varchar(15) default NULL,
  `browser` varchar(200) default NULL,
  `username` varchar(30) default NULL,
  `session` varchar(32) default NULL,
  `count` int(11) NOT NULL default '0',
  `login_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `logout` enum('Y','N') default 'N',
  `logout_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `expired` datetime default NULL,
  UNIQUE KEY `u3` (`uid`,`ip`,`browser`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `login_info`
-- 

INSERT INTO `login_info` VALUES (2, '207.6.38.43', 'Mozilla/5.0 (Windows NT 6.0; WOW64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1 FirePHP/0.6', 'Demo1', NULL, 3, '2012-02-12 21:56:38', 'Y', '2012-02-12 22:01:56', '2012-02-13 07:56:38');
INSERT INTO `login_info` VALUES (4, '207.6.38.43', 'Mozilla/5.0 (Windows NT 6.0; WOW64; rv:10.0.1) Gecko/20100101 Firefox/10.0.1 FirePHP/0.6', 'Onefamily', '7b3fd3e412557396de6f0a39241c8def', 1, '2012-02-12 22:02:16', 'N', '0000-00-00 00:00:00', '2012-02-13 08:02:16');

-- --------------------------------------------------------

-- 
-- Table structure for table `modules`
-- 

CREATE TABLE `modules` (
  `mid` smallint(5) unsigned NOT NULL auto_increment,
  `site_id` tinyint(4) NOT NULL,
  `sname` varchar(255) default NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `url_flag` varchar(1) default 'N',
  `left1` enum('Y','N') default 'Y',
  `right3` enum('Y','N') default 'Y',
  `weight` tinyint(3) unsigned default '1',
  `description` text,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`mid`),
  KEY `sid` (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `modules`
-- 

INSERT INTO `modules` VALUES (1, 1, 'SurreyOneFamily', '??', 'http://localhost/church/', 'N', 'Y', 'N', 255, '??', 'Y', 'SurreyOneFamily', '2011-12-24 12:47:57', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (2, 1, 'SurreyOneFamily', '?????', 'http://localhost/church/', 'N', 'Y', 'N', 2, '?????', 'N', 'SurreyOneFamily', '2011-12-24 12:49:30', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (3, 1, 'SurreyOneFamily', '????', 'http://chn.scac.org/', 'N', 'Y', 'N', 6, '????', 'Y', 'SurreyOneFamily', '2011-12-24 12:50:44', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (4, 1, 'SurreyOneFamily', '????', 'http://localhost/church/', 'N', 'Y', 'N', 8, '????', 'Y', 'SurreyOneFamily', '2011-12-24 12:53:06', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (5, 1, 'SurreyOneFamily', '?????', 'http://www.surreycac.com/res/', 'N', 'Y', 'N', 3, '?????', 'Y', 'SurreyOneFamily', '2011-12-24 17:45:29', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (9, 1, 'SurreyOneFamily', '????', 'http://www.surreyonefamily.ca/', 'N', 'Y', 'N', 2, '????', 'Y', 'SurreyOneFamily', '2012-02-04 21:10:27', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (8, 1, 'SurreyOneFamily', '????', 'http://www.surreyonefamily.ca/', 'N', 'Y', 'N', 5, '????', 'Y', 'SurreyOneFamily', '2012-02-04 20:08:19', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (10, 1, 'SurreyOneFamily', '????', 'http://www.surreycac.com/res/', 'N', 'Y', 'Y', 4, '????', 'Y', 'SurreyOneFamily', '2012-02-04 21:46:34', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (11, 1, 'SurreyOneFamily', '??????', 'http://www.surreyonefamily.ca/', 'N', 'Y', 'Y', 7, '??????', 'Y', 'SurreyOneFamily', '2012-02-04 21:48:08', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (12, 1, 'SurreyOneFamily', '????', 'http://www.surreycac.com/res/', 'N', 'Y', 'Y', 5, '????', 'Y', 'SurreyOneFamily', '2012-02-05 17:10:48', 'SurreyOneFamily', '2012-02-13 18:46:33');
INSERT INTO `modules` VALUES (13, 1, 'SurreyOneFamily', '??', 'http://surreyonefamily.ca/', 'N', 'N', 'N', 6, '??', 'Y', 'OneFamily', '2012-02-12 23:50:35', 'OneFamily', '2012-02-13 00:04:53');

-- --------------------------------------------------------

-- 
-- Table structure for table `onefamily`
-- 

CREATE TABLE `onefamily` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(50) character set utf8 NOT NULL,
  `firstname` varchar(30) character set utf8 default NULL,
  `lastname` varchar(30) character set utf8 default NULL,
  `dob_month` varchar(3) character set utf8 default NULL,
  `dob_date` varchar(2) character set utf8 default NULL,
  `img` blob,
  `sex` enum('M','F') character set utf8 default NULL,
  `fellowship` varchar(30) character set utf8 default '????',
  `address` varchar(100) character set utf8 default NULL,
  `city` varchar(50) character set utf8 default NULL,
  `postal` char(7) character set utf8 default NULL,
  `phone` varchar(15) character set utf8 default NULL,
  `email` varchar(100) character set utf8 default NULL,
  `description` text character set utf8,
  `active` enum('Y','N') character set utf8 default 'Y',
  `createdby` varchar(50) character set utf8 default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) character set utf8 default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- 
-- Dumping data for table `onefamily`
-- 

INSERT INTO `onefamily` VALUES (1, 'rebecca_lee', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'rebecca_lee@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (2, 'abelliu_59', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'abelliu_59@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (3, 'jy.scac', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'jy.scac@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (4, 'benyliu46', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'benyliu46@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (5, 'bigidea.liu', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'bigidea.liu@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (6, 'chaoyw20', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'chaoyw20@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (7, 'pcvtcc', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'pcvtcc@yahoo.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (8, 'chendebo', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'chendebo@yahoo.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (9, 'daniel', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'daniel@dmc-osl.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (10, 'daniel.lynn', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'daniel.lynn@dm-s.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (11, 'duoduof', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'duoduof@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (12, 'amenfung', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'amenfung@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (13, 'hydong', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'hydong@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (14, 'wjhyhy', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'wjhyhy@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (15, 'johnwang2007', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'johnwang2007@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (16, 'john51491', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'john51491@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (17, 'kevinho401', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'kevinho401@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (18, 'kporlai', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'kporlai@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (19, 'lauralee888', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'lauralee888@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (20, 'lilylee61', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'lilylee61@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (21, 'lilychaiyang', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'lilychaiyang@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (22, 'liuzhuoguang', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'liuzhuoguang@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (23, 'mirandashen', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'mirandashen@live.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (24, 'ppangyx', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'ppangyx@yahoo.com.cn', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (25, 'samuel30hong', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'samuel30hong@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (26, 'liao_sanli', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'liao_sanli@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (27, 'songjane816', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'songjane816@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (28, 'stevechen', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'stevechen@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (29, 'susanna77', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'susanna77@sina.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (30, 'suyuanli', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'suyuanli@ms36.hinet.net', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (31, 'tanchunyan', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'tanchunyan@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (32, 'yyh_ca', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'yyh_ca@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (33, 'williamjxj', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'williamjxj@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (34, 'weixiachen', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'weixiachen@yahoo.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (35, 'benny.an', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'benny.an@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (36, 'yuaningrid', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'yuaningrid@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (37, 'jimshi2007', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'jimshi2007@yahoo.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (38, 'window444444', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'window444444@yahoo.com.tw', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (39, 'imanely', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'imanely@shaw.ca', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (40, 'taozhi1958', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'taozhi1958@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (41, 'elaineroh', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'elaineroh@hotmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (42, 'kevindxu', NULL, NULL, NULL, NULL, NULL, NULL, '????', NULL, NULL, NULL, NULL, 'kevindxu@gmail.com', NULL, 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:23:23');
INSERT INTO `onefamily` VALUES (43, 'hhbwjb', 'firstname', 'lastname', NULL, NULL, NULL, NULL, '????', 'guildform mall', 'surrey', 'v3r123', '123 456', 'hhbwjb@gmail.com', 'description.', 'Y', 'Admin', '2012-01-30 16:12:11', 'Admin', '2012-01-30 16:14:42');
INSERT INTO `onefamily` VALUES (44, 'new user', 'william', 'jiang', NULL, NULL, NULL, 'M', '????', 'address', 'city', '123456', '1234567890', 'jxjwilliam@gmail.com', 'william', 'Y', 'Admin', '2012-01-31 15:48:08', 'Admin', '0000-00-00 00:00:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `pages`
-- 

CREATE TABLE `pages` (
  `pid` smallint(5) unsigned NOT NULL auto_increment,
  `site_id` tinyint(4) NOT NULL,
  `sname` varchar(255) default NULL,
  `name` varchar(255) NOT NULL,
  `weight` tinyint(3) unsigned default '1',
  `url` varchar(255) default NULL,
  `description` text,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`pid`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `pages`
-- 

INSERT INTO `pages` VALUES (1, 1, 'SurreyOneFamily', 'Public Home', 255, 'http://localhost/demo', '?? - ??', 'Y', 'SurreyOneFamily', '2011-12-24 12:46:50', 'SurreyOneFamily', '2012-02-13 18:43:33');

-- --------------------------------------------------------

-- 
-- Table structure for table `pages_modules`
-- 

CREATE TABLE `pages_modules` (
  `cmid` int(10) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL,
  `pid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`cmid`),
  UNIQUE KEY `cmid` (`mid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

-- 
-- Dumping data for table `pages_modules`
-- 

INSERT INTO `pages_modules` VALUES (57, 5, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (56, 11, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (55, 9, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (54, 1, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (53, 3, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (58, 12, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (59, 4, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (60, 10, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (61, 8, 1, 'OneFamily', '2012-02-13 00:04:26');
INSERT INTO `pages_modules` VALUES (62, 13, 1, 'OneFamily', '2012-02-13 00:04:26');

-- --------------------------------------------------------

-- 
-- Table structure for table `questions`
-- 

CREATE TABLE `questions` (
  `qid1` tinyint(2) NOT NULL,
  `question1` varchar(100) NOT NULL,
  `qid2` tinyint(2) NOT NULL,
  `question2` varchar(100) NOT NULL,
  `qdate` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `questions`
-- 

INSERT INTO `questions` VALUES (1, 'What was your mother''s maiden name?', 1, 'What was the name of your first boyfriend or girlfriend?', '2010-12-31');
INSERT INTO `questions` VALUES (2, 'In what town or city did your parents meet?', 2, 'In what town or city did your parents meet?', '2010-12-31');
INSERT INTO `questions` VALUES (3, 'What was the name of your favorite teacher?', 3, 'On what street did you grow up?', '2010-12-31');
INSERT INTO `questions` VALUES (4, 'On what street did you grow up?', 4, 'What was your mother''s maiden name?', '2010-12-31');
INSERT INTO `questions` VALUES (5, 'What was your high school Mascot?', 5, 'What was the name of your favorite teacher?', '2010-12-31');
INSERT INTO `questions` VALUES (6, 'What was the name of your first boyfriend or girlfriend?', 6, 'What was your high school Mascot?', '2010-12-31');

-- --------------------------------------------------------

-- 
-- Table structure for table `reports`
-- 

CREATE TABLE `reports` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` mediumblob NOT NULL,
  `content_hash` char(32) NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `reports`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `resources`
-- 

CREATE TABLE `resources` (
  `rid` int(10) unsigned NOT NULL auto_increment,
  `file` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL default 'resources',
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` mediumblob,
  `site_id` tinyint(3) unsigned NOT NULL,
  `sname` varchar(255) default NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `mname` varchar(255) default NULL,
  `division` varchar(1) default NULL,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rid`),
  UNIQUE KEY `fmdid` (`file`,`mid`,`division`,`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- 
-- Dumping data for table `resources`
-- 

INSERT INTO `resources` VALUES (1, 'ad_Nov_28.gif', 'image/gif', 14231, './resources/williamjiang/', 'some pictures.', 'some pictures.', NULL, 1, 'SurreyOneFamily', 7, 'Lucy Module', '', 'Y', 'SurreyOneFamily', '2011-12-25 14:34:08', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (2, 'jiahua2.gif', 'image/gif', 50265, './resources/williamjiang/', 'some pictures.', 'some pictures.', NULL, 1, 'SurreyOneFamily', 7, 'Lucy Module', '', 'Y', 'SurreyOneFamily', '2011-12-25 14:34:08', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (3, 'radio.jpg', 'image/jpeg', 3082, './resources/williamjiang/', 'some pictures.', 'some pictures.', NULL, 1, 'SurreyOneFamily', 7, 'Lucy Module', '', 'Y', 'SurreyOneFamily', '2011-12-25 14:34:09', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (4, 'thumbsup.gif', 'image/gif', 304, './resources/williamjiang/', 'some pictures.', 'some pictures.', NULL, 1, 'SurreyOneFamily', 7, 'Lucy Module', '', 'Y', 'SurreyOneFamily', '2011-12-25 14:34:09', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (5, 'vancouver2.jpg', 'image/jpeg', 12673, './resources/williamjiang/', 'some pictures.', 'some pictures.', NULL, 1, 'SurreyOneFamily', 7, 'Lucy Module', '', 'Y', 'SurreyOneFamily', '2011-12-25 14:34:10', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (6, 'login-icon.png', 'image/png', 5480, './resources/williamjiang/', '', '', NULL, 1, 'SurreyOneFamily', 3, '????', '', 'Y', 'SurreyOneFamily', '2012-01-02 15:55:25', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (7, 'login-button.jpg', 'image/jpeg', 1084, './resources/williamjiang/', 'DIRECTORY_SEPARATOR', 'DIRECTORY_SEPARATOR', NULL, 1, 'SurreyOneFamily', 6, '????', '', 'Y', 'SurreyOneFamily', '2012-01-02 15:56:23', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (8, 'login-button-new.jpg', 'image/jpeg', 1361, './resources/williamjiang/', 'DIRECTORY_SEPARATOR again', '//$targetDir = SITEROOT . DIRECTORY_SEPARATOR . ''resources'' . DIRECTORY_SEPARATOR . $t;', NULL, 1, 'SurreyOneFamily', 6, '????', '', 'Y', 'SurreyOneFamily', '2012-01-02 15:59:32', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (9, 'pic2.png', 'image/png', 11497, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:54', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (10, 'pic1.png', 'image/png', 14375, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:54', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (11, 'slide-30.jpg', 'image/jpeg', 17695, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:54', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (12, 'slide-31.jpg', 'image/jpeg', 19879, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:55', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (13, 'slide-32.jpg', 'image/jpeg', 19404, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:55', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (14, 'slide-42.jpg', 'image/jpeg', 26707, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:55', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (15, 'slide-41.jpg', 'image/jpeg', 28518, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:55', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (16, 'slide-40.jpg', 'image/jpeg', 29695, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:56', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (17, 'slide-22.jpg', 'image/jpeg', 15767, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:56', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (18, 'slide-21.jpg', 'image/jpeg', 22291, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:56', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (19, 'slide-20.jpg', 'image/jpeg', 23020, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:56', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (20, 'slide-12.jpg', 'image/jpeg', 21583, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:56', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (21, 'slide-11.jpg', 'image/jpeg', 10181, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:57', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (22, 'slide-10.jpg', 'image/jpeg', 29811, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:57', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (23, 'logo.png', 'image/png', 27445, './resources/williamjiang/', 'from http://www.surreycac.com/', 'http://www.surreycac.com/', NULL, 1, 'SurreyOneFamily', 1, '??', '', 'Y', 'SurreyOneFamily', '2012-02-04 18:27:57', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (24, '2page-img1.jpg', 'image/jpeg', 11308, './resources/williamjiang/', 'images/2page-img1.jpg', 'images/2page-img1.jpg', NULL, 1, 'SurreyOneFamily', 4, '????', '', 'Y', 'SurreyOneFamily', '2012-02-04 19:23:25', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (25, '2012_NewYear.jpg', 'image/jpeg', 122895, './resources/surreyonefamily/', '', '2012_NewYear', NULL, 1, 'SurreyOneFamily', 13, '??', '', 'Y', '', '2012-02-12 23:57:52', 'SurreyOneFamily', '2012-02-13 18:45:34');
INSERT INTO `resources` VALUES (26, '2012_NewYear.jpg', 'image/jpeg', 122895, './resources/surreyonefamily/', '2012_NewYear', '2012_NewYear', NULL, 1, 'SurreyOneFamily', 13, '??', 'u', 'Y', '', '2012-02-13 00:02:56', 'SurreyOneFamily', '2012-02-13 18:45:34');

-- --------------------------------------------------------

-- 
-- Table structure for table `sites`
-- 

CREATE TABLE `sites` (
  `site_id` tinyint(3) unsigned NOT NULL auto_increment,
  `level` tinyint(4) default NULL,
  `lname` varchar(100) character set gb2312 default NULL,
  `tid` tinyint(3) unsigned default NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `sites`
-- 

INSERT INTO `sites` VALUES (1, 1, NULL, 0, 'SurreyOneFamily', 'http://surreyonefamily.ca/', '???.', 'Y', 'Demo', '2011-12-24 00:23:37', 'Demo', '2011-12-24 03:16:02');

-- --------------------------------------------------------

-- 
-- Table structure for table `themes`
-- 

CREATE TABLE `themes` (
  `tid` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) default NULL,
  `path` varchar(255) NOT NULL default 'default',
  `filename` varchar(100) NOT NULL,
  `preview` blob NOT NULL,
  `active` enum('Y','N') default 'Y',
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `themes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tracks`
-- 

CREATE TABLE `tracks` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `notes` text,
  `content` text NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updatedby` varchar(50) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tracks`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users_modules`
-- 

CREATE TABLE `users_modules` (
  `umid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `mid` smallint(5) unsigned NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`umid`),
  UNIQUE KEY `u_m_id` (`uid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `users_modules`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users_sites`
-- 

CREATE TABLE `users_sites` (
  `usid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `site_id` tinyint(3) unsigned NOT NULL,
  `createdby` varchar(50) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`usid`),
  UNIQUE KEY `u_s_id` (`uid`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `users_sites`
-- 

