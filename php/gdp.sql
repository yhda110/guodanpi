/*
Navicat MySQL Data Transfer

Source Server         : 我的服务器2
Source Server Version : 50726
Source Host           : 47.98.140.221:3306
Source Database       : gdp

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2019-09-04 10:11:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `gdp_banner`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_banner`;
CREATE TABLE `gdp_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_id` int(11) DEFAULT NULL COMMENT '图片id',
  `title` varchar(255) DEFAULT NULL COMMENT '图片标题',
  `content` varchar(255) DEFAULT NULL COMMENT '图片内容',
  `dateline` timestamp NULL DEFAULT NULL COMMENT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_banner
-- ----------------------------

-- ----------------------------
-- Table structure for `gdp_city_copy`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_city_copy`;
CREATE TABLE `gdp_city_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_city_copy
-- ----------------------------

-- ----------------------------
-- Table structure for `gdp_image`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_image`;
CREATE TABLE `gdp_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_url` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `jump_url` varchar(255) DEFAULT NULL COMMENT '跳转url',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_image
-- ----------------------------

-- ----------------------------
-- Table structure for `gdp_open`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_open`;
CREATE TABLE `gdp_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `dateline` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_open
-- ----------------------------
INSERT INTO `gdp_open` VALUES ('1', 'odFwTwDZsYj3-yY-2ZAfU2BfwwCI', '2019-09-03 17:01:31');
INSERT INTO `gdp_open` VALUES ('4', 'odFwTwORh-G51iijMX2noMVBH_tg', '2019-09-03 17:23:29');

-- ----------------------------
-- Table structure for `gdp_province`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_province`;
CREATE TABLE `gdp_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_province
-- ----------------------------

-- ----------------------------
-- Table structure for `gdp_town`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_town`;
CREATE TABLE `gdp_town` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_town
-- ----------------------------

-- ----------------------------
-- Table structure for `gdp_user`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_user`;
CREATE TABLE `gdp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL COMMENT '微信名',
  `user` varchar(255) DEFAULT NULL COMMENT '账号',
  `pass` varchar(255) DEFAULT NULL COMMENT '密码',
  `pic_id` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `phone` char(255) DEFAULT NULL COMMENT '手机号',
  `openid` int(11) DEFAULT NULL COMMENT 'openid',
  `province_id` int(11) DEFAULT NULL COMMENT '省份',
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `town_id` int(11) DEFAULT NULL COMMENT '城镇',
  `ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `last_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '0' COMMENT '0:可以使用 1:已封禁',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_user
-- ----------------------------
