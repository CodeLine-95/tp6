/*
 Navicat Premium Data Transfer

 Source Server         : tp6_flv_pet
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 128.1.137.99:3306
 Source Schema         : tp6_flv_pet

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 13/04/2020 22:36:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `user_nick` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `user_face` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `user_host` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态：0正常，1冻结',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `user_tel` int(11) DEFAULT NULL COMMENT '手机号',
  `user_email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='管理员';

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES (3, 'admin', '$2y$10$GNHxUaVZj.oe50IwzCDS0O0DGvf7Vz.VYKAK/s7g6btSuSEXmx75S', '超级管理员', '', '123.121.167.58', '2020-04-13 22:14:18', 0, '2020-04-12 18:17:50', NULL, 'admin@qq.com', '2020-04-13 22:14:18');
INSERT INTO `admin` VALUES (5, 'test', '$2y$10$VkfWOW7RpPE51rGeSJel6ewMbuWWJc9BXICJp7hnaxse4BrzQFX0u', '测试账户', '/storage/admin/20200412/4a3142d4235785ea654bbf8d82a75d1d.jpg', '', '2020-04-12 18:17:57', 0, '2020-04-12 18:17:57', 123456789, 'test@qq.om', '2020-04-13 21:40:29');
INSERT INTO `admin` VALUES (6, 'test11', '$2y$10$v93fJsu/D.r7EWm15MgUyOFe88KpDPErMDgYhvdb8EGkE2qvDHN6C', '测试账户11', '/storage/admin/20200412/866c3cc87069df4d86e04f3ef44267f6.jpg', '', NULL, 1, '2020-04-12 21:18:39', 123456789, 'test11@qq.com', '2020-04-12 21:30:09');
COMMIT;

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品编号',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `goods_price` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `goods_source` varchar(50) NOT NULL DEFAULT '' COMMENT '商品来源',
  `goods_type` int(11) NOT NULL COMMENT '商品类型',
  `goods_stock` int(11) NOT NULL DEFAULT '0' COMMENT '商品库存量',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `goods_rfid` varchar(255) DEFAULT NULL COMMENT 'rfid卡号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

-- ----------------------------
-- Records of goods
-- ----------------------------
BEGIN;
INSERT INTO `goods` VALUES (2, '测试汽车商品', '/storage/admin/20200412/314386b9dba0a7eb8cb8480502414ca9.png', 12.99, '系统资源', 1, 701, '2020-04-12 18:13:58', '2020-04-13 22:22:39', '2324234242');
INSERT INTO `goods` VALUES (3, '测试商品2', '/storage/admin/20200412/c94c84d76107a2dd78c8013eb0890c06.png', 88.88, '系统资源', 5, 40, '2020-04-12 21:20:30', '2020-04-13 22:22:32', '1213121');
COMMIT;

-- ----------------------------
-- Table structure for goods_cates
-- ----------------------------
DROP TABLE IF EXISTS `goods_cates`;
CREATE TABLE `goods_cates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名称',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='类型表';

-- ----------------------------
-- Records of goods_cates
-- ----------------------------
BEGIN;
INSERT INTO `goods_cates` VALUES (1, '汽车1', NULL, NULL);
INSERT INTO `goods_cates` VALUES (2, '汽车2', NULL, NULL);
INSERT INTO `goods_cates` VALUES (5, '汽车3', NULL, NULL);
INSERT INTO `goods_cates` VALUES (6, '汽车6', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for login_log
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login_ip` varchar(255) DEFAULT NULL COMMENT '登录IP',
  `name` varchar(255) DEFAULT NULL COMMENT '登录名称',
  `create_t` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `content` varchar(255) DEFAULT NULL COMMENT '详情',
  `type` varchar(255) DEFAULT NULL COMMENT '操作类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='日志表';

-- ----------------------------
-- Table structure for warehousing
-- ----------------------------
DROP TABLE IF EXISTS `warehousing`;
CREATE TABLE `warehousing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '入库编号',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品编号',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `uid` int(11) DEFAULT NULL COMMENT '操作人id',
  `user_name` varchar(255) DEFAULT NULL COMMENT '操作人昵称',
  `create_time` datetime DEFAULT NULL COMMENT '入库时间',
  `num` int(11) DEFAULT NULL COMMENT '入库数量',
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `orderid` varchar(255) DEFAULT NULL COMMENT '入库单号',
  `user_tel` varchar(255) DEFAULT NULL COMMENT '联系人电话',
  `user_email` varchar(255) DEFAULT NULL COMMENT '联系人邮箱',
  `state` int(11) DEFAULT '1' COMMENT '状态：1入库  2出库',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='入库表';

-- ----------------------------
-- Records of warehousing
-- ----------------------------
BEGIN;
INSERT INTO `warehousing` VALUES (5, 2, '测试汽车商品', 3, 'admin', '2020-04-12 19:22:20', 1200, '2020-04-13 21:12:27', 'RK2020041219222061417', 'aa', 'aa', 1);
INSERT INTO `warehousing` VALUES (6, 3, '测试商品2', 3, 'admin', '2020-04-12 21:20:50', 10, '2020-04-13 21:12:28', 'RK2020041221205096728', '12312341122', 'test1@qq.com', 1);
INSERT INTO `warehousing` VALUES (7, 2, '测试汽车商品', 3, 'admin', '2020-04-13 21:19:51', 11, '2020-04-13 21:19:51', 'RK2020041321195198306', '12312341122', 'test@qq.om', 1);
INSERT INTO `warehousing` VALUES (8, 2, '测试汽车商品', 3, 'admin', '2020-04-13 21:31:28', 10, '2020-04-13 21:33:32', 'CK2020041321312858732', '12312341122', 'test@qq.om', 2);
INSERT INTO `warehousing` VALUES (9, 2, '测试汽车商品', 3, 'admin', '2020-04-13 21:33:43', 500, '2020-04-13 21:33:43', 'CK2020041321334343645', '12312341122', 'test1@qq.com', 2);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
