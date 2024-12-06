-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: localhost    Database: newsist_yii
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE DATABASE `newsist_yii`;
USE `newsist_yii`;

--
-- Table structure for table `news_item`
--

DROP TABLE IF EXISTS `news_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `posted_at` timestamp NOT NULL,
  `number_of_likes` int NOT NULL,
  `author_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `news_item_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_item`
--

LOCK TABLES `news_item` WRITE;
/*!40000 ALTER TABLE `news_item` DISABLE KEYS */;
INSERT INTO `news_item` VALUES (93,'I FOUND MY OLD VIDEO','I found my old video where i do sings a lot. There\'re no doubts that this is me. [Marshall Eriksen sings A LOT.mp4] I\'m indeed singer!!! by the way, <script>alert(\'hacked\')</script>','2024-12-06 13:08:09',2,104),(94,'I FOUND OUT THAT MY WEBSITE IS NOT ABOUT NEWS BUT ABOUT DUMB POSTS','When i finished the developing (actually, this thing needs approx 3 months to be completed), i posted there the Jaskier\'s song like the following one [Jaskier_-_Toss_a_Coin_to_Your_Witcher_OST_The_Witcher_67831347.mp3] That was interesting time. I wish it never finished.','2024-12-06 13:16:06',3,104),(95,'I THOUGHT MY WEBSITE DOES NOT HAVE A BUGS WITH FILES DISPLAYING BUT I FOUND ONE','The first time i thought i checked out the files displaying several times and thoroughly, but turned out.. just look at this picture: [bug_revealed.png] it yells about the error. sorry, bro, this post and all the next are with you. this bug is forever with us. ...or not?','2024-12-06 13:25:08',2,104),(96,'BUG DISAPPEARED AFTER I MENTIONED HIM','I made three posts, all with the same bug, but when i told the wold about this bug on my crazy website \"newsist\", it disappeared. Just look [bug_disappeared.png] I can\'t call it anything other than miracle. IT\'S MIRACLE DUDE! THE HEAVEN DESCENDED FROM EARTH TO MAKE THIS HAPPEN.','2024-12-06 13:31:11',0,104),(97,'THE BUG RETURNED BACK AFTER I MENTIONED THAT IT DISAPPEARED','Guys, this is my last post about this bug. This is cranky son of a computer system. Just look what he does [bug_appeared_again.png] Yes! he appeares when you mention that he disappeared and vice versa. Goodbye, bug.','2024-12-06 13:36:10',3,104),(98,'TO HAVE YOUR OWN SITE TURNED OUT TO BE MORE FUN THAN I EXPECTED','Haha, just so funny to have it. should i host it around the world? also you can check out the source code a little (yes, it has the error and works fine) [source code.png] the php is funny stuff','2024-12-06 13:42:22',0,104),(99,'I HAVE NO IDEA WHAT TO POST NEXT SO I WILL JUST ATTACH SOME SONGS','honestly, i\'m trying to post it the second time. the first time i got error like \'incorrect file type\' when i was trying to attach this: [The_Witcher_3_OST_-_Ladies_of_the_Woods_Extended_Version_63888518.mp3] i have no idea what it appeared. Uh, also the model with error was not complete, only title was saved. the tags and content were empty. Argh, gotcha. these fields have a default values \'content-info\' and \'tag1,tag2...\'. FIXED UP!','2024-12-06 14:17:18',3,104),(100,'GOT WEIRD BUG WHICH WAS \"FIXED\"','i can\'t upload some mp3 files because they are treated with application/octet-stream mime. at first, i thought that the problem is that i was listening this song and during attach it was considered as a *...stream*, because other songs uploaded fine. now listen to it. [The Witcher 3_ Wild Hunt OST — You\'re... Immortal_ (www.lightaudio.ru).mp3]','2024-12-06 14:22:36',2,104),(101,'I\'M TIRED TO POST NEWS, SO, THE NEXT WILL BE SIMILAR','i have been posting news too long, it\'s time to use my ctrl+c ctrl+v skills. also, recall you about this guy. such a crazy singer [Marshall Eriksen sings A LOT.mp4] bang bang bangity bang','2024-12-06 14:28:35',1,105),(106,'REMOVE IT','no i won\'t','2024-12-06 14:49:41',2,105),(107,'PARROT TEACHES ITSELF TO SING OPERA','A local parrot shocked its owners by belting out a flawless rendition of an iconic opera aria, leaving them wondering if they accidentally adopted a diva in disguise.','2024-12-06 14:50:46',1,105),(108,'SLOTH BREAKS WORLD RECORD FOR FASTEST CLIMB','In a shocking turn of events, a sloth at the local nature reserve managed to climb a tree in record time, leaving spectators in awe of its newfound speed.','2024-12-06 14:51:51',1,105),(109,'ELEPHANT BECOMES WORLD\'S FIRST BALLET DANCER','An elephant at the circus stunned audiences by gracefully performing a ballet routine, proving that you\'re never too big to learn how to dance.','2024-12-06 14:52:13',1,105),(110,'OSTRICH RIDES SKATEBOARD THROUGH CITY','Passersby were left speechless when an ostrich was caught cruising down the street on a skateboard, showing off some impressive tricks along the way.','2024-12-06 14:52:53',1,105),(111,'SQUIRREL BREAKS INTO LOCAL PIZZA SHOP','A mischievous squirrel was caught red-handed trying to steal a slice of pizza from a local shop, proving that even furry creatures can\'t resist a good slice of cheese.','2024-12-06 14:53:51',0,105),(112,'TIGER LEARNS TO RIDE UNICYCLE','Visitors at the circus were amazed when a tiger showcased its newfound talent by effortlessly balancing on a unicycle while roaring with excitement.','2024-12-06 14:55:02',2,105),(113,'IM SOVVY FOV MY GVAMMAV EVVOVS (JOKE, MIFTAKES) IMA NOT A ENGLISH ONE.','i do epologize, dudes.','2024-12-06 15:01:13',0,106),(114,'EMPTY CONTENT','','2024-12-06 15:02:40',0,106),(115,'XSS ATTEMPT','<script>alert(\'hello\')<script> in asp.net core this is encoded by default, but not in yii','2024-12-06 15:04:21',0,106),(116,'WELCOME TO TAG XSS TEST','','2024-12-06 15:07:29',0,107),(117,'THE LAST NEWS ITEM','good luck','2024-12-06 15:08:18',1,107);
/*!40000 ALTER TABLE `news_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_items_tags`
--

DROP TABLE IF EXISTS `news_items_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_items_tags` (
  `news_item_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `number` int NOT NULL,
  PRIMARY KEY (`news_item_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `news_items_tags_ibfk_1` FOREIGN KEY (`news_item_id`) REFERENCES `news_item` (`id`),
  CONSTRAINT `news_items_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_items_tags`
--

LOCK TABLES `news_items_tags` WRITE;
/*!40000 ALTER TABLE `news_items_tags` DISABLE KEYS */;
INSERT INTO `news_items_tags` VALUES (93,67,1),(93,68,2),(93,69,3),(93,70,4),(94,67,1),(94,68,2),(94,71,3),(94,72,4),(95,73,1),(95,74,2),(96,68,3),(96,73,5),(96,74,4),(96,75,1),(96,76,2),(97,73,2),(97,74,1),(97,76,3),(97,77,4),(98,78,1),(98,79,2),(98,80,3),(98,81,4),(98,82,5),(99,67,3),(99,71,2),(99,74,1),(99,79,4),(100,67,2),(100,68,3),(100,71,1),(100,74,4),(101,67,1),(101,68,2),(101,73,4),(101,83,3),(106,84,1),(107,67,5),(107,68,2),(107,79,3),(107,85,1),(107,86,4),(108,85,1),(108,87,2),(109,68,2),(109,85,1),(109,88,3),(110,76,4),(110,85,1),(110,89,2),(110,90,3),(111,85,2),(111,91,1),(111,92,3),(112,76,1),(112,85,3),(112,93,2),(112,94,4),(113,73,2),(113,95,1),(114,74,3),(114,96,1),(114,97,2),(114,98,4),(115,98,1),(115,99,2),(116,99,2),(116,100,1);
/*!40000 ALTER TABLE `news_items_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (70,'angel\'s voice'),(85,'animals'),(97,'another tag'),(74,'bug'),(75,'bugs slayer'),(69,'cool guy'),(84,'cranky'),(72,'discovery'),(88,'elephant'),(95,'epologizes'),(79,'fun'),(78,'he likes it'),(76,'miracle'),(92,'nice try'),(98,'not a bug actually'),(83,'not fun'),(90,'ostrich'),(86,'parrot'),(80,'php'),(87,'record'),(73,'reflection'),(67,'singer'),(89,'skateboard'),(96,'some tag'),(81,'source code'),(82,'startup'),(68,'talent'),(99,'test'),(77,'the end'),(71,'the witcher'),(91,'thieft'),(93,'tiger'),(94,'unicycle'),(100,'xss<script>alert(\'hello\')<script>');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `passwordHash` char(128) NOT NULL,
  `authKey` varchar(100) NOT NULL,
  `accessToken` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (104,'admin','c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec','5stQPnSL_tFmMCH7uQ2AjhMF3zxqpauu','OVvyXL-EneKdOtT6'),(105,'john','b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86','TKCWAy-T6WHjlJh_fhwO3MnHoFou6wih','DtPbFwXFvLLZKUcD'),(106,'anatolii','34c716b4603639733bd3ea3bd1a2f40eb1447902de4c0d624056fb2f5bd12d808dc52a9474353add273fa785e1705b21a134186399c0ee452a3c622026a6ccba','5mqG3S4yFzAjdzRGS2FB8jid5odJh2Ec','ZWO6A8fV8bBSP3NC'),(107,'xss<script>alert(\'hello\')<script>','34c716b4603639733bd3ea3bd1a2f40eb1447902de4c0d624056fb2f5bd12d808dc52a9474353add273fa785e1705b21a134186399c0ee452a3c622026a6ccba','tB4O4QQwhT5461z_vBvoZsL4LwEfiMKh','seZpCkkEpXDchhm7');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_news_item_like`
--

DROP TABLE IF EXISTS `user_news_item_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_news_item_like` (
  `user_id` int NOT NULL,
  `news_item_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`news_item_id`),
  KEY `news_item_id` (`news_item_id`),
  CONSTRAINT `user_news_item_like_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_news_item_like_ibfk_2` FOREIGN KEY (`news_item_id`) REFERENCES `news_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_news_item_like`
--

LOCK TABLES `user_news_item_like` WRITE;
/*!40000 ALTER TABLE `user_news_item_like` DISABLE KEYS */;
INSERT INTO `user_news_item_like` VALUES (104,93),(105,93),(104,94),(105,94),(106,94),(104,95),(106,95),(104,97),(105,97),(106,97),(104,99),(105,99),(106,99),(105,100),(106,100),(105,101),(105,106),(106,106),(105,107),(106,108),(106,109),(106,110),(105,112),(106,112),(107,117);
/*!40000 ALTER TABLE `user_news_item_like` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-06 18:11:10
