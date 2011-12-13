/*
MySQL Data Transfer
Source Host: localhost
Source Database: njufriends
Target Host: localhost
Target Database: njufriends
Date: 2011/12/13 13:36:21
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for activity
-- ----------------------------
CREATE TABLE `activity` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8,
  `leader_id` int(11) NOT NULL,
  `care_num` int(11) NOT NULL DEFAULT '0',
  `join_num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for caremember
-- ----------------------------
CREATE TABLE `caremember` (
  `aid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
CREATE TABLE `comment` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for joinmember
-- ----------------------------
CREATE TABLE `joinmember` (
  `aid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for type
-- ----------------------------
CREATE TABLE `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `tname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user
-- ----------------------------
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `rrid` varchar(255) CHARACTER SET utf8 NOT NULL,
  `uname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `headurl` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `activity` VALUES ('1', 'dota五人黑', '2011-12-08 22:20:37', '2011-12-22 22:20:55', '五栋宿舍', '1', '娱乐', '1', '0', '0');
INSERT INTO `activity` VALUES ('2', '看电影', '2011-12-09 22:51:29', '2011-12-28 23:51:32', '新街口', '2', '娱乐', '2', '0', '0');
INSERT INTO `caremember` VALUES ('2', '1', '1');
INSERT INTO `caremember` VALUES ('2', '2', '2');
INSERT INTO `comment` VALUES ('1', '1', '1', '2011-12-11 00:10:36', 'adfasdfasdf');
INSERT INTO `comment` VALUES ('2', '1', '2', '2011-12-21 00:00:12', 'good one');
INSERT INTO `joinmember` VALUES ('1', '1', '1');
INSERT INTO `joinmember` VALUES ('1', '2', '2');
INSERT INTO `type` VALUES ('1', '游戏', '网游、单机、竞技、休闲游戏');
INSERT INTO `type` VALUES ('2', '学习', '组团自习、求指导、求救赎');
INSERT INTO `type` VALUES ('3', '讲座', '大牛的个人时间');
INSERT INTO `type` VALUES ('4', '旅游', '一起暴走去');
INSERT INTO `type` VALUES ('5', '刷街', '逛街、K歌');
INSERT INTO `type` VALUES ('6', '电影', '组团电影');
INSERT INTO `type` VALUES ('7', '综合娱乐', 'K歌、桌游，莫作宅男');
INSERT INTO `type` VALUES ('8', '其他', '你的点子，我都喜欢');
INSERT INTO `user` VALUES ('1', 'asdf', '龚晨', 'asfd.adsf');
INSERT INTO `user` VALUES ('2', 'ghjk', '葛羽航', 'ghjk.ghjk');
