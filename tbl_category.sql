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

/*Table structure for table `tbl_category` */

DROP TABLE IF EXISTS `tbl_category`;

CREATE TABLE `tbl_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_uid` varchar(100) NOT NULL,
  `cat_parent` int(11) NOT NULL DEFAULT '0',
  `cat_name` varchar(100) NOT NULL,
  `cat_name_en` varchar(100) DEFAULT NULL,
  `cat_level` tinyint(1) NOT NULL DEFAULT '0',
  `cat_dept1` int(2) unsigned zerofill NOT NULL DEFAULT '00',
  `cat_dept2` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `cat_dept3` int(5) unsigned zerofill DEFAULT '00000',
  PRIMARY KEY (`cat_id`)
) ENGINE=MEMORY AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_category` */

insert  into `tbl_category`(`cat_id`,`cat_uid`,`cat_parent`,`cat_name`,`cat_name_en`,`cat_level`,`cat_dept1`,`cat_dept2`,`cat_dept3`) values (1,'',0,'사회복지시설 디자인','Social Welfare Facilities Design',0,00,0000,00000),(2,'',1,'종합사회복지관','Social Welfare Center',1,00,0001,00000),(3,'',1,'노인복지관','Elderly Welfare Center',1,00,0002,00000),(4,'',1,'장애인복지관','Disabled Welfare',1,00,0003,00000),(5,'',1,'노인요양보호시설','Elderly care facilities',1,00,0004,00000),(6,'',1,'다문화','Multiculturalism',1,00,0005,00000),(7,'',1,'지역아동센터','Local children\'s center',1,00,0006,00000),(8,'',1,'자원봉사','Volunteer',1,00,0007,00000),(9,'',1,'후원/모금','Sponsorship / Fundraising',1,00,0008,00000),(10,'',1,'바자회','Bazaars',1,00,0009,00000),(11,'',1,'행사','event',1,00,0010,00000),(12,'',0,'일반디자인','Japanese Design',0,01,0000,00000),(13,'',12,'자동차','car',1,01,0001,00000),(14,'',12,'핸드폰','cellphone',1,01,0002,00000),(15,'',12,'병원/약국','Hospital / pharmacy',1,01,0003,00000),(16,'',12,'백화점/마트','Department Store / Mart',1,01,0004,00000),(17,'',12,'음식점','restaurant',1,01,0005,00000),(18,'',12,'부동산','real estate',1,01,0006,00000),(19,'',12,'돌잔치/가족행사','First birthday / family event',1,01,0007,00000),(20,'',0,'학원/교육','School / Education',0,02,0000,00000),(21,'',20,'음악/미술학원','Music / Art School',1,02,0001,00000),(22,'',20,'무대배경/포토존','Stage background / Photo Zone',2,02,0001,00001),(23,'',20,'입학/졸업','Admission / Graduation',2,02,0001,00002),(24,'',20,'원생모집','Aboriginal recruitment',2,02,0001,00003),(25,'',20,'발표회','Conference',2,02,0001,00004),(26,'',20,'행사','event',1,02,0001,00005),(27,'',20,'체육학원','Physical Education Institute',1,02,0002,00000),(28,'',20,'축구','Football',2,02,0002,00001),(29,'',20,'농구','basketball',2,02,0002,00002),(30,'',20,'태권도','Taekwondo',2,02,0002,00003),(31,'',20,'기타','guitar',2,02,0002,00004),(32,'',20,'공부방','Study room',1,02,0003,00000),(33,'',20,'헬스','Health',1,02,0003,00000),(34,'',0,'종교단체디자인','Religious Organizations Design',0,03,0000,00000),(35,'',34,'교회','church',1,03,0001,00000),(36,'',34,'불교','Buddhism',1,03,0002,00000),(37,'',34,'천주교','Catholic',1,03,0003,00000),(38,'',34,'기타','guitar',1,03,0004,00000),(39,'',0,'계절디자인','Seasonal design',0,04,0000,00000),(40,'',39,'봄','spring',1,04,0001,00000),(41,'',39,'여름','summer',1,04,0002,00000),(42,'',39,'가을','autumn',1,04,0003,00000),(43,'',39,'겨울','winter',1,04,0004,00000),(44,'',0,'스타일디자인','Style Design',0,05,0000,00000),(45,'',44,'심플디자인','Simple design',1,05,0001,00000),(46,'',44,'러블리디자인','Lovely design',1,05,0002,00000),(47,'',44,'엔틱디자인','Antique design',1,05,0003,00000),(48,'',44,'일러스트디자인','Illustration Design',1,05,0004,00000);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
