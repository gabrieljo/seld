/*
SQLyog Ultimate v8.6 Beta2
MySQL - 5.6.24 : Database - db_seld
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_seld` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_seld`;

/*Table structure for table `tbl_clients` */

DROP TABLE IF EXISTS `tbl_clients`;

CREATE TABLE `tbl_clients` (
  `cl_id` int(11) NOT NULL AUTO_INCREMENT,
  `cl_uid` varchar(50) DEFAULT NULL,
  `cl_firstname` varchar(50) DEFAULT NULL,
  `cl_lastname` varchar(50) DEFAULT NULL,
  `cl_company` varchar(100) DEFAULT NULL,
  `cl_email` varchar(250) DEFAULT NULL,
  `cl_telephone` varchar(20) DEFAULT NULL,
  `cl_mobile` varchar(20) DEFAULT NULL,
  `cl_address1` varchar(100) DEFAULT NULL,
  `cl_address2` varchar(100) DEFAULT NULL,
  `cl_address3` varchar(100) DEFAULT NULL,
  `cl_postcode` varchar(4) DEFAULT NULL,
  `cl_password` varchar(250) DEFAULT NULL,
  `cl_created_at` datetime DEFAULT NULL,
  `cl_updated_at` datetime DEFAULT NULL,
  `cl_status` enum('active','inactive','pending','deleted') NOT NULL DEFAULT 'pending',
  `cl_last_login` datetime DEFAULT NULL,
  `cl_last_ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_clients` */

/*Table structure for table `tbl_design_products` */

DROP TABLE IF EXISTS `tbl_design_products`;

CREATE TABLE `tbl_design_products` (
  `d_pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_pr_uid` varchar(100) DEFAULT NULL,
  `d_pr_name` varchar(100) DEFAULT NULL,
  `d_pr_description` varchar(250) DEFAULT NULL,
  `d_pr_image` varchar(50) DEFAULT NULL,
  `d_pr_face` tinyint(1) NOT NULL DEFAULT '1',
  `d_pr_page` tinyint(1) NOT NULL DEFAULT '1',
  `d_pr_created_at` datetime DEFAULT NULL,
  `d_pr_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`d_pr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_design_products` */

insert  into `tbl_design_products`(`d_pr_id`,`d_pr_uid`,`d_pr_name`,`d_pr_description`,`d_pr_image`,`d_pr_face`,`d_pr_page`,`d_pr_created_at`,`d_pr_updated_at`) values (1,'6a14e7a4f35baa7093ea85c4c7d33389','Business Card','Lorem ipsum dolor sit amet.','icon-business-card.png',1,1,'2015-10-22 11:29:00',NULL),(2,'d20e4cb31fa5e9fdaa5383bdd7beb0e9','Leaflet','Lorem ipsum dolor sit amet.','icon-leaflet.png',2,6,'2015-10-22 11:29:00',NULL),(3,'1747cd87dd06dddca961fc4e32f538a1','Flyer','Lorem ipsum dolor sit amet.','icon-flyer.png',1,1,'2015-10-22 11:29:00',NULL),(4,'977e214689884aee1de95791ba1b86f7','Catalog','Lorem ipsum dolor sit amet.','icon-catalog.png',1,1,'2015-10-22 11:29:00',NULL),(5,'1c9f37fe2778d25d8abc1dc1caa750b1','Poster','Lorem ipsum dolor sit amet.','icon-poster.png',1,1,'2015-10-22 11:29:00',NULL);

/*Table structure for table `tbl_design_themes` */

DROP TABLE IF EXISTS `tbl_design_themes`;

CREATE TABLE `tbl_design_themes` (
  `d_th_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_th_uid` varchar(100) DEFAULT NULL,
  `d_th_pid` int(11) NOT NULL DEFAULT '0',
  `d_th_name` varchar(100) DEFAULT NULL,
  `d_th_description` varchar(255) DEFAULT NULL,
  `d_th_image` varchar(100) DEFAULT NULL,
  `d_th_order` int(11) NOT NULL DEFAULT '0',
  `d_th_properties` text,
  `d_th_status` tinyint(1) NOT NULL DEFAULT '0',
  `d_th_created_at` datetime DEFAULT NULL,
  `d_th_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`d_th_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_design_themes` */

insert  into `tbl_design_themes`(`d_th_id`,`d_th_uid`,`d_th_pid`,`d_th_name`,`d_th_description`,`d_th_image`,`d_th_order`,`d_th_properties`,`d_th_status`,`d_th_created_at`,`d_th_updated_at`) values (1,'32aa9783c1394b2b37398dfe8858108e',2,'Theme 1','theme 1','1446609790-525986.png',1,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL),(2,'e90f797aab0737fef271526a07b9a881',2,'Theme 2','theme 2','1446609801-307729.png',2,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL),(3,'c77db190fbcbdc6b06b34cc248f0d106',2,'Theme 3','theme 3','1446609809-659550.png',3,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL),(4,'7e54f6517b48f8a8574ea933da325d37',2,'Theme 4','theme 4','1446609814-168678.png',4,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL),(5,'cec2e11ee3f698435134bc11c552c950',2,'Theme 5','theme 5','1446609821-56211.png',5,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL),(6,'c256da2e014ec7337ba5853cc5ec4025',2,'Theme 6','theme 6','1446609827-96819.png',6,'a:3:{s:5:\"width\";i:400;s:6:\"height\";i:600;s:5:\"scale\";i:2;}',1,NULL,NULL);

/*Table structure for table `tbl_favourites` */

DROP TABLE IF EXISTS `tbl_favourites`;

CREATE TABLE `tbl_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `fav_uid` varchar(100) DEFAULT NULL,
  `fav_cl_id` int(11) NOT NULL DEFAULT '0',
  `fav_thm_id` int(11) DEFAULT '0',
  `fav_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_favourites` */

/*Table structure for table `tbl_products` */

DROP TABLE IF EXISTS `tbl_products`;

CREATE TABLE `tbl_products` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_uid` varchar(100) DEFAULT NULL,
  `pr_cl_id` int(11) NOT NULL DEFAULT '0',
  `pr_type` int(11) NOT NULL DEFAULT '0',
  `pr_th_id` int(11) NOT NULL DEFAULT '0',
  `pr_options` text,
  `pr_contents` text,
  `pr_status` enum('pending','completed','designing') NOT NULL DEFAULT 'designing',
  `pr_created_at` datetime DEFAULT NULL,
  `pr_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_products` */

insert  into `tbl_products`(`pr_id`,`pr_uid`,`pr_cl_id`,`pr_type`,`pr_th_id`,`pr_options`,`pr_contents`,`pr_status`,`pr_created_at`,`pr_updated_at`) values (1,'1666055120ac901e0ac2ee2b974f6d86',1,2,1,'a:12:{s:8:\"set-size\";s:2:\"A1\";s:11:\"set-cutting\";s:1:\"1\";s:12:\"set-quantity\";s:8:\"spin-box\";s:12:\"set-printing\";s:7:\"default\";s:13:\"set-frequency\";s:23:\"both sides to 8 degrees\";s:11:\"set-quality\";s:9:\"Art Paper\";s:11:\"set-coating\";s:4:\"None\";s:11:\"set-folding\";s:12:\"접지없음\";s:12:\"set-folding2\";s:6:\"1 line\";s:10:\"set-dotted\";s:6:\"1 line\";s:9:\"set-holes\";s:6:\"1 hole\";s:14:\"set-holes-size\";s:4:\"3 mm\";}',NULL,'designing','2015-11-10 22:18:32','2015-11-13 12:17:31'),(2,'af42654cab3b82b8a83c276cd18aa8c5',1,0,0,NULL,NULL,'designing','2015-11-12 09:43:17',NULL),(3,'22a61c2b95bf5e60e236cec0c04ee794',0,2,0,NULL,NULL,'designing','2015-11-12 09:43:19',NULL),(4,'4646690cb6f8bfba31a88c858ddc2041',0,0,2,NULL,NULL,'designing','2015-11-12 09:43:21',NULL),(5,'739f29e1b05cfd01a5e4d49d69f67342',0,0,1,NULL,NULL,'designing','2015-11-12 09:43:28',NULL),(6,'a9241a1e27715b1cc605c7f12031603f',0,0,1,NULL,NULL,'designing','2015-11-12 09:59:12',NULL),(7,'3dc109ef6c178afdf70e22b086925865',0,0,1,NULL,NULL,'designing','2015-11-12 09:59:51',NULL),(8,'c421ad427fe0b3df93afe4afd0dfcc2c',0,0,1,NULL,NULL,'designing','2015-11-12 10:00:39',NULL),(9,'aa0058aee12a1053b9ed18f1480fa9b1',0,2,0,NULL,NULL,'designing','2015-11-12 10:00:48',NULL),(10,'b3dfde76420923ba47eae0af07aede81',0,0,1,NULL,NULL,'designing','2015-11-12 10:00:51',NULL),(11,'2611907d351cf1304094bf8f67c9b32b',0,2,0,NULL,NULL,'designing','2015-11-12 10:06:08',NULL),(12,'7114c90fbf442ed40cc59306e2f377ab',0,2,0,NULL,NULL,'designing','2015-11-12 10:06:18',NULL),(13,'7871f0adaefbc0d5cb9add1b6e0461f9',0,2,0,NULL,NULL,'designing','2015-11-12 10:06:19',NULL);

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_uid` varchar(50) DEFAULT NULL,
  `usr_firstname` varchar(50) DEFAULT NULL,
  `usr_lastname` varchar(50) DEFAULT NULL,
  `usr_email` varchar(250) DEFAULT NULL,
  `usr_username` varchar(50) DEFAULT NULL,
  `usr_password` varchar(250) DEFAULT NULL,
  `usr_last_login` datetime DEFAULT NULL,
  `usr_last_ip` varchar(20) DEFAULT NULL,
  `usr_token` varchar(250) DEFAULT NULL,
  `usr_token_date` datetime DEFAULT NULL,
  `usr_token_ip` varchar(20) DEFAULT NULL,
  `usr_created_at` datetime DEFAULT NULL,
  `usr_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
