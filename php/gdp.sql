/*
Navicat MySQL Data Transfer

Source Server         : 我的服务器2
Source Server Version : 50726
Source Host           : 47.98.140.221:3306
Source Database       : gdp

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2019-09-04 18:30:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `gdp_admin_type`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_admin_type`;
CREATE TABLE `gdp_admin_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL COMMENT '1：管理员 2：设计师  3：游客',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_admin_type
-- ----------------------------
INSERT INTO `gdp_admin_type` VALUES ('1', '管理员');
INSERT INTO `gdp_admin_type` VALUES ('2', '设计师');
INSERT INTO `gdp_admin_type` VALUES ('3', '游客');

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
-- Table structure for `gdp_image`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_image`;
CREATE TABLE `gdp_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_url` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `jump_url` varchar(255) DEFAULT NULL COMMENT '跳转url',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_image
-- ----------------------------
INSERT INTO `gdp_image` VALUES ('5', 'http://thirdwx.qlogo.cn/mmopen/vi_32/orgwnDFzhAqQC4ibRxicccDiaSDwCk54P77WH5XXjNzS8l3hkytIiad4mPB7XnNDPCjfIqsDxJOtS9vx6d77fbtZVA/132', '', '2019-09-04 15:47:51');
INSERT INTO `gdp_image` VALUES ('8', 'http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaELR90JubUaVqDBbnicWicLJxO9YcdiaHJcDiaSXfAYYERRR0dbtBJktNTsdpgAeYonULLcw88ZgoWlqTA/132', '', '2019-09-04 15:58:13');

-- ----------------------------
-- Table structure for `gdp_open`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_open`;
CREATE TABLE `gdp_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `dateline` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_open
-- ----------------------------
INSERT INTO `gdp_open` VALUES ('23', 'odFwTwDZsYj3-yY-2ZAfU2BfwwCI', '2019-09-04 15:47:51');
INSERT INTO `gdp_open` VALUES ('26', 'odFwTwORh-G51iijMX2noMVBH_tg', '2019-09-04 15:58:13');

-- ----------------------------
-- Table structure for `gdp_tags`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_tags`;
CREATE TABLE `gdp_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) DEFAULT NULL COMMENT '标签名字',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '0：可用  1：不可用',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_tags
-- ----------------------------
INSERT INTO `gdp_tags` VALUES ('1', '地中海', '0', '2019-09-04 17:50:51');
INSERT INTO `gdp_tags` VALUES ('6', '哈哈哈', '0', '2019-09-04 17:38:25');
INSERT INTO `gdp_tags` VALUES ('7', '哈哈哈', '1', '2019-09-04 17:39:14');

-- ----------------------------
-- Table structure for `gdp_user`
-- ----------------------------
DROP TABLE IF EXISTS `gdp_user`;
CREATE TABLE `gdp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '1' COMMENT '预留：用户类型 1 管理员  2 设计师 3游客',
  `nickname` varchar(255) DEFAULT NULL COMMENT '微信名',
  `user` varchar(255) DEFAULT NULL COMMENT '账号',
  `pass` varchar(255) DEFAULT NULL COMMENT '密码',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别 1男 2女',
  `pic_id` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `phone` char(255) DEFAULT '0' COMMENT '预留：手机号',
  `openid` int(11) DEFAULT NULL COMMENT 'openid',
  `province` varchar(255) DEFAULT NULL COMMENT '省份',
  `city` varchar(255) DEFAULT NULL COMMENT '城市',
  `country` varchar(255) DEFAULT NULL COMMENT '国家',
  `ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  `coin` varchar(255) DEFAULT '0' COMMENT '预留：虚拟货币 1元=10个币',
  `level_score` char(255) DEFAULT '0' COMMENT '预留：用户积分',
  `level` int(11) DEFAULT '0' COMMENT '预留：等级 ',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `last_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '0' COMMENT '0:可以使用 1:已封禁',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gdp_user
-- ----------------------------
INSERT INTO `gdp_user` VALUES ('3', '1', 'asdf', 'gdp_67892054', '670b14728ad9902aecba32e22fa4f6bd', '1', '5', '0', '23', '山西', '晋中', '中国', '222.129.17.246', '0', '0', '0', '2019-09-04 15:47:51', '2019-09-04 15:47:51', '0');
INSERT INTO `gdp_user` VALUES ('6', '1', '༊྄ཻStormHunter࿐ོེ', 'gdp_20178645', '670b14728ad9902aecba32e22fa4f6bd', '1', '8', '0', '26', '北京', '西城', '中国', '111.207.202.4', '0', '0', '0', '2019-09-04 15:58:13', '2019-09-04 15:58:13', '0');
