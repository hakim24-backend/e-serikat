/*
 Navicat Premium Data Transfer

 Source Server         : mysql_local
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : eserikat

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 14/11/2019 04:32:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activity
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activity_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `name_activity` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `background` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `purpose` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `target_activity` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `place_activity` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `place_activity_x` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `place_activity_y` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `role` int(255) NOT NULL,
  `finance_status` tinyint(1) NOT NULL,
  `department_status` tinyint(1) NOT NULL,
  `chief_status` tinyint(1) NOT NULL,
  `chief_code_id` int(255) NULL DEFAULT NULL,
  `department_code_id` int(255) NULL DEFAULT NULL,
  `done` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_ibfk_1`(`role`) USING BTREE,
  INDEX `activity_ibfk_2`(`department_code_id`) USING BTREE,
  INDEX `activity_ibfk_3`(`chief_code_id`) USING BTREE,
  CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_ibfk_2` FOREIGN KEY (`department_code_id`) REFERENCES `department` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_ibfk_3` FOREIGN KEY (`chief_code_id`) REFERENCES `chief` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_budget_chief
-- ----------------------------
DROP TABLE IF EXISTS `activity_budget_chief`;
CREATE TABLE `activity_budget_chief`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `chief_budget_id` int(255) NOT NULL,
  `budget_value_dp` double NOT NULL,
  `budget_value_sum` double NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chief_budget_id`(`chief_budget_id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_budget_chief_ibfk_1` FOREIGN KEY (`chief_budget_id`) REFERENCES `chief_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_budget_chief_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_budget_department
-- ----------------------------
DROP TABLE IF EXISTS `activity_budget_department`;
CREATE TABLE `activity_budget_department`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `department_budget_id` int(255) NOT NULL,
  `budget_value_dp` double NOT NULL,
  `budget_value_sum` double NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `department_budget_id`(`department_budget_id`) USING BTREE,
  CONSTRAINT `activity_budget_department_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_budget_department_ibfk_2` FOREIGN KEY (`department_budget_id`) REFERENCES `department_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_budget_secretariat
-- ----------------------------
DROP TABLE IF EXISTS `activity_budget_secretariat`;
CREATE TABLE `activity_budget_secretariat`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `secretariat_budget_id` int(255) NOT NULL,
  `budget_value_dp` double NOT NULL,
  `budget_value_sum` double NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `secretariat_budget_id`(`secretariat_budget_id`) USING BTREE,
  CONSTRAINT `activity_budget_secretariat_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_budget_secretariat_ibfk_2` FOREIGN KEY (`secretariat_budget_id`) REFERENCES `secretariat_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_budget_section
-- ----------------------------
DROP TABLE IF EXISTS `activity_budget_section`;
CREATE TABLE `activity_budget_section`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_budget_id` int(255) NOT NULL,
  `budget_value_dp` double NOT NULL,
  `budget_value_sum` double NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `section_budget_id`(`section_budget_id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_budget_section_ibfk_1` FOREIGN KEY (`section_budget_id`) REFERENCES `section_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_budget_section_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily`;
CREATE TABLE `activity_daily`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activity_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `finance_status` tinyint(1) NOT NULL,
  `department_status` tinyint(1) NOT NULL,
  `chief_status` tinyint(1) NOT NULL,
  `chief_code_id` int(255) NULL DEFAULT NULL,
  `department_code_id` int(255) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `role` int(255) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `done` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chief_code_id`(`chief_code_id`) USING BTREE,
  INDEX `department_code_id`(`department_code_id`) USING BTREE,
  INDEX `role`(`role`) USING BTREE,
  CONSTRAINT `activity_daily_ibfk_1` FOREIGN KEY (`chief_code_id`) REFERENCES `chief` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_ibfk_2` FOREIGN KEY (`department_code_id`) REFERENCES `department` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_ibfk_3` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_budget_chief
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_budget_chief`;
CREATE TABLE `activity_daily_budget_chief`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `chief_budget_id` int(255) NOT NULL,
  `budget_value_dp` float NOT NULL,
  `budget_value_sum` float NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `chief_budget_id`(`chief_budget_id`) USING BTREE,
  CONSTRAINT `activity_daily_budget_chief_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_budget_chief_ibfk_2` FOREIGN KEY (`chief_budget_id`) REFERENCES `chief_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_budget_depart
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_budget_depart`;
CREATE TABLE `activity_daily_budget_depart`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `department_budget_id` int(255) NOT NULL,
  `budget_value_dp` float NOT NULL,
  `budget_value_sum` float NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `department_budget_id`(`department_budget_id`) USING BTREE,
  CONSTRAINT `activity_daily_budget_depart_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_budget_depart_ibfk_2` FOREIGN KEY (`department_budget_id`) REFERENCES `department_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_budget_secretariat
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_budget_secretariat`;
CREATE TABLE `activity_daily_budget_secretariat`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `secretariat_budget_id` int(255) NOT NULL,
  `budget_value_dp` float NOT NULL,
  `budget_value_sum` float NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `secretariat_budget_id`(`secretariat_budget_id`) USING BTREE,
  CONSTRAINT `activity_daily_budget_secretariat_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_budget_secretariat_ibfk_2` FOREIGN KEY (`secretariat_budget_id`) REFERENCES `secretariat_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_budget_section
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_budget_section`;
CREATE TABLE `activity_daily_budget_section`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_budget_id` int(255) NOT NULL,
  `budget_value_dp` float NOT NULL,
  `budget_value_sum` float NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  INDEX `section_budget_id`(`section_budget_id`) USING BTREE,
  CONSTRAINT `activity_daily_budget_section_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_daily_budget_section_ibfk_2` FOREIGN KEY (`section_budget_id`) REFERENCES `section_budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_reject
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_reject`;
CREATE TABLE `activity_daily_reject`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activity_id` int(255) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_daily_reject_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_daily_responsibility
-- ----------------------------
DROP TABLE IF EXISTS `activity_daily_responsibility`;
CREATE TABLE `activity_daily_responsibility`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `responsibility_value` float NOT NULL,
  `file` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activity_id` int(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_daily_responsibility_ibfk_1`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_daily_responsibility_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity_daily` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_main_member
-- ----------------------------
DROP TABLE IF EXISTS `activity_main_member`;
CREATE TABLE `activity_main_member`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name_committee` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name_member` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `acitivity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_main_member_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 65 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_reject
-- ----------------------------
DROP TABLE IF EXISTS `activity_reject`;
CREATE TABLE `activity_reject`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activity_id` int(255) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_reject_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_responsibility
-- ----------------------------
DROP TABLE IF EXISTS `activity_responsibility`;
CREATE TABLE `activity_responsibility`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `responsibility_value` float NOT NULL,
  `file` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_responsibility_ibfk_1`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_responsibility_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_section
-- ----------------------------
DROP TABLE IF EXISTS `activity_section`;
CREATE TABLE `activity_section`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_section_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_section_member
-- ----------------------------
DROP TABLE IF EXISTS `activity_section_member`;
CREATE TABLE `activity_section_member`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_name_member` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `section_activity_id` int(255) NOT NULL,
  `activity_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `section_activity_id`(`section_activity_id`) USING BTREE,
  INDEX `activity_id`(`activity_id`) USING BTREE,
  CONSTRAINT `activity_section_member_ibfk_1` FOREIGN KEY (`section_activity_id`) REFERENCES `activity_section` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `activity_section_member_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for budget
-- ----------------------------
DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `budget_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `budget_year` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `budget_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `budget_value` float NOT NULL,
  `budget_rek` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for chief
-- ----------------------------
DROP TABLE IF EXISTS `chief`;
CREATE TABLE `chief`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `chief_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `chief_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_budget` tinyint(1) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `chief_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for chief_budget
-- ----------------------------
DROP TABLE IF EXISTS `chief_budget`;
CREATE TABLE `chief_budget`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `chief_budget_id` int(255) NOT NULL,
  `chief_budget_value` double NOT NULL,
  `chief_id` int(255) NOT NULL,
  `chief_budget_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chief_budget_id`(`chief_budget_id`) USING BTREE,
  INDEX `chief_id`(`chief_id`) USING BTREE,
  CONSTRAINT `chief_budget_ibfk_1` FOREIGN KEY (`chief_budget_id`) REFERENCES `budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chief_budget_ibfk_2` FOREIGN KEY (`chief_id`) REFERENCES `chief` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_chief` int(255) NOT NULL,
  `status_budget` tinyint(1) NOT NULL,
  `depart_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `department_ibfk_1`(`id_chief`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `department_ibfk_1` FOREIGN KEY (`id_chief`) REFERENCES `chief` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `department_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for department_budget
-- ----------------------------
DROP TABLE IF EXISTS `department_budget`;
CREATE TABLE `department_budget`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `department_budget_id` int(255) NOT NULL,
  `department_budget_value` double NOT NULL,
  `department_id` int(255) NOT NULL,
  `department_budget_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `department_budget_id`(`department_budget_id`) USING BTREE,
  INDEX `department_id`(`department_id`) USING BTREE,
  CONSTRAINT `department_budget_ibfk_1` FOREIGN KEY (`department_budget_id`) REFERENCES `budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `department_budget_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration`  (
  `version` varchar(180) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `apply_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name_role` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for secretariat
-- ----------------------------
DROP TABLE IF EXISTS `secretariat`;
CREATE TABLE `secretariat`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `secretariat_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `secretariat_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `secretariat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for secretariat_budget
-- ----------------------------
DROP TABLE IF EXISTS `secretariat_budget`;
CREATE TABLE `secretariat_budget`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `secretariat_budget_id` int(255) NOT NULL,
  `secretariat_budget_value` double NOT NULL,
  `secretariat_id` int(255) NOT NULL,
  `secretariat_budget_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `secretariat_budget_id`(`secretariat_budget_id`) USING BTREE,
  INDEX `secretariat_budget_ibfk_2`(`secretariat_id`) USING BTREE,
  CONSTRAINT `secretariat_budget_ibfk_1` FOREIGN KEY (`secretariat_budget_id`) REFERENCES `budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `secretariat_budget_ibfk_2` FOREIGN KEY (`secretariat_id`) REFERENCES `secretariat` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for section
-- ----------------------------
DROP TABLE IF EXISTS `section`;
CREATE TABLE `section`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_depart` int(255) NOT NULL,
  `status_budget` tinyint(1) NOT NULL,
  `section_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_depart`(`id_depart`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`id_depart`) REFERENCES `department` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `section_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for section_budget
-- ----------------------------
DROP TABLE IF EXISTS `section_budget`;
CREATE TABLE `section_budget`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_budget_id` int(255) NOT NULL,
  `section_budget_value` double NOT NULL,
  `section_id` int(255) NOT NULL,
  `section_budget_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `section_budget_id`(`section_budget_id`) USING BTREE,
  INDEX `section_id`(`section_id`) USING BTREE,
  CONSTRAINT `section_budget_ibfk_1` FOREIGN KEY (`section_budget_id`) REFERENCES `budget` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `section_budget_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transfer_record
-- ----------------------------
DROP TABLE IF EXISTS `transfer_record`;
CREATE TABLE `transfer_record`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `code_source` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `value` float NOT NULL,
  `code_dest` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `role` int(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  UNIQUE INDEX `password_reset_token`(`password_reset_token`) USING BTREE,
  INDEX `role`(`role`) USING BTREE,
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 130 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
