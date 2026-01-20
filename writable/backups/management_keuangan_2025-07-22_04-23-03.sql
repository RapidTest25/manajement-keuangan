-- Manual SQL Dump
-- Database: `management_keuangan`
-- Generated: 2025-07-22 04:23:03

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auth_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_groups` (`id`,`name`,`description`) VALUES ('1','admin','Site Administrator');
INSERT INTO `auth_groups` (`id`,`name`,`description`) VALUES ('2','user','Regular User');

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) unsigned NOT NULL DEFAULT 0,
  `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  KEY `group_id_permission_id` (`group_id`,`permission_id`),
  CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) unsigned NOT NULL DEFAULT 0,
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_groups_users_user_id_foreign` (`user_id`),
  KEY `group_id_user_id` (`group_id`,`user_id`),
  CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('1','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('1','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('1','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('1','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('2','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('2','3');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('2','6');
INSERT INTO `auth_groups_users` (`group_id`,`user_id`) VALUES ('2','6');

CREATE TABLE `auth_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('1','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-03 09:07:47','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('2','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 16:31:43','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('3','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 17:10:09','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('4','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 18:17:04','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('5','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 18:19:28','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('6','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 18:19:43','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('7','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 18:53:58','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('8','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 18:56:19','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('9','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-05 19:07:15','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('10','127.0.0.1','testing1',NULL,'2025-05-05 21:11:28','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('11','127.0.0.1','aong@gmail.com','5','2025-05-05 21:12:17','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('12','127.0.0.1','aong@gmail.com','6','2025-05-05 22:09:37','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('13','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-06 12:10:05','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('14','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-06 18:28:59','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('15','127.0.0.1','aong@gmail.com','6','2025-05-06 22:22:36','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('16','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-06 22:30:23','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('17','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-07 07:54:47','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('18','127.0.0.1','aong@gmail.com','6','2025-05-07 08:13:18','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('19','127.0.0.1','aong@gmail.com','6','2025-05-07 10:02:35','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('20','127.0.0.1','aong@gmail.com','6','2025-05-07 10:03:38','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('21','127.0.0.1','aong@gmail.com','6','2025-05-07 10:04:52','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('22','127.0.0.1','aong@gmail.com','6','2025-05-07 10:07:02','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('23','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-07 15:00:41','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('24','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-07 15:10:27','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('25','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-07 18:30:06','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('26','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-07 22:22:30','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('27','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-08 04:36:42','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('28','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-08 04:55:18','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('29','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-08 05:06:13','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('30','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-08 11:55:54','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('31','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-08 12:02:09','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('32','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 04:35:01','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('33','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 05:37:48','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('34','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 05:37:56','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('35','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 05:38:04','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('36','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 05:38:38','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('37','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 05:38:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('38','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 09:55:56','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('39','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 09:59:38','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('40','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:00:46','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('41','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:01:03','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('42','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:01:16','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('43','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:12:37','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('44','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:13:21','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('45','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:19:46','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('46','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:30:22','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('47','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:35:27','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('48','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 10:35:54','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('49','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 11:05:07','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('50','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 11:05:48','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('51','127.0.0.1','aong@gmail.com','6','2025-05-09 11:10:32','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('52','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 17:41:38','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('53','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 17:46:05','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('54','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 17:55:11','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('55','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 18:15:13','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('56','127.0.0.1','aong@gmail.com','6','2025-05-09 18:20:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('57','127.0.0.1','aong@gmail.com','6','2025-05-09 18:24:26','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('58','127.0.0.1','aong@gmail.com','6','2025-05-09 18:25:30','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('59','127.0.0.1','aong@gmail.com','6','2025-05-09 18:25:55','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('60','127.0.0.1','aong@gmail.com','6','2025-05-09 18:26:15','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('61','127.0.0.1','aong@gmail.com','6','2025-05-09 18:35:17','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('62','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 18:36:25','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('63','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 18:39:52','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('64','127.0.0.1','aong@gmail.com','6','2025-05-09 20:00:31','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('65','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 21:55:09','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('66','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:02:12','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('67','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:06:57','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('68','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:07:01','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('69','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:07:27','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('70','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:08:46','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('71','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:12:21','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('72','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-09 22:18:08','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('73','127.0.0.1','aong@gmail.com','6','2025-05-09 22:19:23','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('74','127.0.0.1','aong@gmail.com','6','2025-05-09 22:21:50','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('75','127.0.0.1','aong@gmail.com','6','2025-05-09 22:26:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('76','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 06:37:51','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('77','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 07:09:49','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('78','127.0.0.1','aong@gmail.com','6','2025-05-10 07:10:32','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('79','127.0.0.1','aong@gmail.com','6','2025-05-10 07:20:46','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('80','127.0.0.1','aong@gmail.com','6','2025-05-10 07:22:11','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('81','127.0.0.1','aong@gmail.com','6','2025-05-10 07:26:37','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('82','127.0.0.1','aong@gmail.com','6','2025-05-10 07:29:14','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('83','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 07:45:06','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('84','127.0.0.1','aong@gmail.com','6','2025-05-10 07:45:31','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('85','127.0.0.1','aong@gmail.com','6','2025-05-10 07:54:26','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('86','127.0.0.1','aong@gmail.com','6','2025-05-10 07:57:50','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('87','127.0.0.1','aong@gmail.com','6','2025-05-10 08:04:30','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('88','127.0.0.1','aong@gmail.com','6','2025-05-10 08:05:51','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('89','127.0.0.1','aong@gmail.com','6','2025-05-10 08:06:33','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('90','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 08:14:50','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('91','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 08:31:59','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('92','127.0.0.1','aong@gmail.com','6','2025-05-10 08:57:02','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('93','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 19:07:14','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('94','127.0.0.1','aong@gmail.com','6','2025-05-10 21:35:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('95','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-10 21:51:51','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('96','127.0.0.1','aong@gmail.com','6','2025-05-10 21:55:33','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('97','127.0.0.1','aong@gmail.com','6','2025-05-11 07:57:55','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('98','127.0.0.1','aong@gmail.com','6','2025-05-11 08:11:36','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('99','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-11 09:13:21','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('100','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-11 09:47:47','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('101','127.0.0.1','aong@gmail.com','6','2025-05-11 17:12:32','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('102','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-12 08:32:31','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('103','127.0.0.1','aong@gmail.com','6','2025-05-12 10:23:57','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('104','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-12 18:50:12','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('105','127.0.0.1','aong@gmail.com','6','2025-05-12 20:17:42','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('106','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-13 07:55:56','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('107','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-19 15:23:37','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('108','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-20 15:15:15','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('109','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-21 23:06:18','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('110','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-22 00:58:22','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('111','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-22 09:42:36','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('112','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-22 09:42:37','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('113','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-22 23:12:24','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('114','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-22 23:27:07','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('115','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-24 09:12:18','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('116','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-24 09:23:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('117','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-25 01:18:00','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('118','127.0.0.1','aong@gmail.com','6','2025-05-25 04:46:24','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('119','127.0.0.1','ahmadkhadifar@gmail.com','3','2025-05-25 15:38:15','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('120','::1','ahmadkhadifar@gmail.com','3','2025-05-25 21:13:47','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('121','::1','ahmadkhadifar@gmail.com','3','2025-05-26 00:49:58','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('122','::1','ahmadkhadifar@gmail.com','3','2025-05-26 15:21:17','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('123','::1','ahmadkhadifar@gmail.com','3','2025-05-27 01:54:53','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('124','::1','ahmadkhadifar@gmail.com','3','2025-05-27 04:03:46','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('125','::1','ahmadkhadifar@gmail.com','3','2025-05-27 04:08:36','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('126','::1','ahmadkhadifar@gmail.com','3','2025-05-27 15:10:42','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('127','::1','ahmadkhadifar@gmail.com','3','2025-05-28 20:09:08','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('128','::1','ahmadkhadifar@gmail.com','3','2025-05-29 01:17:48','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('129','::1','ahmadkhadifar@gmail.com','3','2025-05-29 01:20:20','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('130','::1','ahmadkhadifar@gmail.com','3','2025-05-29 01:21:22','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('131','::1','ahmadkhadifar@gmail.com','3','2025-05-29 01:23:36','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('132','::1','ahmadkhadifar@gmail.com','3','2025-05-29 01:32:12','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('133','::1','ahmadkhadifar@gmail.com','3','2025-05-29 04:10:09','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('134','::1','ahmadkhadifar@gmail.com','3','2025-05-29 07:17:35','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('135','::1','ahmadkhadifar@gmail.com','3','2025-05-29 07:20:03','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('136','::1','aong@gmail.com','6','2025-05-29 07:20:30','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('137','::1','ahmadkhadifar@gmail.com','3','2025-05-29 07:28:56','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('138','::1','aong@gmail.com','6','2025-05-29 07:51:03','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('139','::1','aong@gmail.com','6','2025-05-29 08:10:24','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('140','::1','ahmadkhadifar@gmail.com','3','2025-05-29 08:11:47','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('141','::1','ahmadkhadifar@gmail.com','3','2025-05-29 08:33:15','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('142','::1','ahmadkhadifar@gmail.com','3','2025-07-19 12:53:18','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('143','::1','ahmadkhadifar@gmail.com','3','2025-07-19 18:50:38','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('144','::1','ahmadkhadifar@gmail.com',NULL,'2025-07-20 13:47:18','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('145','::1','ahmadkhadifar@gmail.com','3','2025-07-20 13:47:29','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('146','::1','gilang@gmail.com',NULL,'2025-07-20 16:32:33','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('147','::1','gilang@gmail.com',NULL,'2025-07-20 16:32:43','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('148','::1','ahmadkhadifar@gmail.com','3','2025-07-21 16:18:58','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('149','::1','gilangmugi',NULL,'2025-07-21 17:22:02','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('150','::1','ahmadkhadifar@gmail.com','3','2025-07-21 17:25:03','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('151','::1','gilangmugi',NULL,'2025-07-21 17:26:46','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('152','::1','aufahoki',NULL,'2025-07-21 17:30:36','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('153','::1','aufahoki',NULL,'2025-07-21 17:30:43','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('154','::1','aufahoki',NULL,'2025-07-21 17:30:52','0');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('155','::1','ahmadkhadifar@gmail.com','3','2025-07-21 20:52:48','1');
INSERT INTO `auth_logins` (`id`,`ip_address`,`email`,`user_id`,`date`,`success`) VALUES ('156','::1','ahmadkhadifar@gmail.com','3','2025-07-22 03:49:33','1');

CREATE TABLE `auth_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_permissions` (`id`,`name`,`description`) VALUES ('1','manage-user','Manage All Users');
INSERT INTO `auth_permissions` (`id`,`name`,`description`) VALUES ('2','manage-profile','Manage User\'s Profile');

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_tokens_user_id_foreign` (`user_id`),
  KEY `selector` (`selector`),
  CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `permission_id` int(11) unsigned NOT NULL DEFAULT 0,
  KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  KEY `user_id_permission_id` (`user_id`,`permission_id`),
  CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_users_permissions` (`user_id`,`permission_id`) VALUES ('3','1');
INSERT INTO `auth_users_permissions` (`user_id`,`permission_id`) VALUES ('3','1');
INSERT INTO `auth_users_permissions` (`user_id`,`permission_id`) VALUES ('3','2');
INSERT INTO `auth_users_permissions` (`user_id`,`permission_id`) VALUES ('3','2');

CREATE TABLE `biaya_efektif` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `nama_biaya` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `frekuensi` enum('harian','mingguan','bulanan','tahunan') NOT NULL DEFAULT 'bulanan',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_biaya_efektif_user` (`user_id`),
  CONSTRAINT `fk_biaya_efektif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `biaya_efektif` (`id`,`user_id`,`kategori`,`nama_biaya`,`jumlah`,`frekuensi`,`tanggal_mulai`,`tanggal_selesai`,`is_active`,`created_at`,`updated_at`) VALUES ('20','3','Rumah','331','4252.00','harian','2025-05-10','0000-00-00','1','2025-05-12 10:43:57','2025-05-12 10:43:57');
INSERT INTO `biaya_efektif` (`id`,`user_id`,`kategori`,`nama_biaya`,`jumlah`,`frekuensi`,`tanggal_mulai`,`tanggal_selesai`,`is_active`,`created_at`,`updated_at`) VALUES ('22','3','JAJAN','JAJAN','50000.00','mingguan','2025-05-23','0000-00-00','1','2025-05-23 02:49:43','2025-05-23 02:49:43');

CREATE TABLE `budget_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `daily_budget` decimal(15,2) NOT NULL DEFAULT 100000.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `budget_settings` (`id`,`username`,`daily_budget`,`created_at`,`updated_at`) VALUES ('14','aong123','300000.00','2025-05-10 23:40:27','2025-05-10 23:40:27');
INSERT INTO `budget_settings` (`id`,`username`,`daily_budget`,`created_at`,`updated_at`) VALUES ('20','ahmadkhadifar','200000.00','2025-07-20 14:11:20','2025-07-20 14:11:20');

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('EXPENSE','INCOME') NOT NULL DEFAULT 'EXPENSE',
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_user_id_foreign` (`user_id`),
  CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`id`,`name`,`type`,`user_id`,`created_at`,`updated_at`) VALUES ('21','Deposit','EXPENSE','3','2025-05-07 22:23:14','2025-05-07 22:23:14');
INSERT INTO `categories` (`id`,`name`,`type`,`user_id`,`created_at`,`updated_at`) VALUES ('28','WD','INCOME','3','2025-07-20 14:00:53','2025-07-20 14:00:53');

CREATE TABLE `cicilan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `monthly_amount` decimal(15,2) DEFAULT NULL,
  `tenor` int(11) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `remaining_amount` decimal(15,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','completed') DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cicilan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cicilan` (`id`,`user_id`,`name`,`type`,`total_amount`,`monthly_amount`,`tenor`,`paid_amount`,`remaining_amount`,`start_date`,`notes`,`status`,`created_at`,`updated_at`) VALUES ('1','3','beat','Kendaraan','2500000.00','650000.00','24','2500000.00','0.00','2025-05-05','','completed','2025-05-11 12:16:17','2025-05-11 12:17:24');
INSERT INTO `cicilan` (`id`,`user_id`,`name`,`type`,`total_amount`,`monthly_amount`,`tenor`,`paid_amount`,`remaining_amount`,`start_date`,`notes`,`status`,`created_at`,`updated_at`) VALUES ('2','3','Komputer','Elektronik','2400000.00','150000.00','16','450000.00','1950000.00','2025-05-05','','active','2025-05-11 12:24:10','2025-05-20 02:36:47');
INSERT INTO `cicilan` (`id`,`user_id`,`name`,`type`,`total_amount`,`monthly_amount`,`tenor`,`paid_amount`,`remaining_amount`,`start_date`,`notes`,`status`,`created_at`,`updated_at`) VALUES ('3','3','beat','Kendaraan','18658164.00','444242.00','42','1776968.00','16881196.00','2025-05-07','bakds','active','2025-05-11 12:25:57','2025-05-29 08:01:47');
INSERT INTO `cicilan` (`id`,`user_id`,`name`,`type`,`total_amount`,`monthly_amount`,`tenor`,`paid_amount`,`remaining_amount`,`start_date`,`notes`,`status`,`created_at`,`updated_at`) VALUES ('5','3','beat','Kendaraan','75000.00','25000.00','3','50000.00','25000.00','2025-05-23',NULL,'active','2025-05-23 02:32:44','2025-05-29 08:01:45');
INSERT INTO `cicilan` (`id`,`user_id`,`name`,`type`,`total_amount`,`monthly_amount`,`tenor`,`paid_amount`,`remaining_amount`,`start_date`,`notes`,`status`,`created_at`,`updated_at`) VALUES ('6','3','Komputer','Elektronik','399996.00','66666.00','6','66666.00','333330.00','2025-05-18',NULL,'active','2025-05-23 02:37:57','2025-05-29 08:01:42');

CREATE TABLE `debt_notes` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `borrowType` varchar(10) DEFAULT NULL,
  `application` varchar(100) DEFAULT NULL,
  `loan_amount` int(11) DEFAULT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_period` varchar(10) NOT NULL,
  `loan_duration` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'active',
  `lender_category` varchar(50) DEFAULT NULL,
  `lender_name` varchar(100) DEFAULT NULL,
  `borrower_name` varchar(100) DEFAULT NULL,
  `amount_paid` int(11) DEFAULT NULL,
  `payments` text DEFAULT NULL,
  `payments_count` int(11) DEFAULT 0,
  `loan_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `debt_notes` (`id`,`user_id`,`type`,`borrowType`,`application`,`loan_amount`,`payment_amount`,`payment_period`,`loan_duration`,`due_date`,`description`,`status`,`lender_category`,`lender_name`,`borrower_name`,`amount_paid`,`payments`,`payments_count`,`loan_date`,`created_at`,`updated_at`) VALUES ('29','3','borrowing','online','Spinjam','900000','300000','','3','2025-05-07','Untuk Kebutuhan','paid',NULL,NULL,NULL,'900000','[{\"date\":\"2025-05-27\",\"amount\":300000},{\"date\":\"2025-05-29\",\"amount\":300000},{\"date\":\"2025-05-29\",\"amount\":300000}]','3','2025-05-21','2025-05-27 01:57:37','2025-05-29 08:02:01');
INSERT INTO `debt_notes` (`id`,`user_id`,`type`,`borrowType`,`application`,`loan_amount`,`payment_amount`,`payment_period`,`loan_duration`,`due_date`,`description`,`status`,`lender_category`,`lender_name`,`borrower_name`,`amount_paid`,`payments`,`payments_count`,`loan_date`,`created_at`,`updated_at`) VALUES ('30','3','borrowing','offline',NULL,'510000','0','',NULL,NULL,'100','active','teman','samsul',NULL,'10000','[{\"date\":\"2025-05-27\",\"amount\":10000}]','0',NULL,'2025-05-27 04:04:07','2025-05-27 04:04:59');
INSERT INTO `debt_notes` (`id`,`user_id`,`type`,`borrowType`,`application`,`loan_amount`,`payment_amount`,`payment_period`,`loan_duration`,`due_date`,`description`,`status`,`lender_category`,`lender_name`,`borrower_name`,`amount_paid`,`payments`,`payments_count`,`loan_date`,`created_at`,`updated_at`) VALUES ('31','3','lending',NULL,NULL,'50000','0','',NULL,NULL,'depo','active',NULL,NULL,'celo','25000','[{\"date\":\"2025-05-27\",\"amount\":5000},{\"date\":\"2025-05-28\",\"amount\":20000}]','0','2025-05-26','2025-05-27 04:04:41','2025-05-28 20:09:50');

CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('1','2017-11-20-223112','Myth\\Auth\\Database\\Migrations\\CreateAuthTables','default','Myth\\Auth','1746211308','1');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('2','2017-11-20-223112','App\\Database\\Migrations\\CreateAuthTables','default','App','1746475360','2');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('11','2024-01-20-223112','App\\Database\\Migrations\\CreateBudgetSettings','default','App','1746788499','3');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('12','2024-01-20-223112','App\\Database\\Migrations\\CreateSavingsTables','default','App','1746788499','3');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('13','2024-01-20-223112','App\\Database\\Migrations\\DebtNotes','default','App','1746788499','3');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('14','2025-05-09-123000','App\\Database\\Migrations\\AddRoleColumn','default','App','1746788557','4');
INSERT INTO `migrations` (`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) VALUES ('15','2025-05-22-000001','App\\Database\\Migrations\\CreateSettingsTable','default','App','1747849389','5');

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `rating` int(1) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `reviews` (`id`,`name`,`role`,`content`,`rating`,`status`,`created_at`,`updated_at`) VALUES ('1','Edgar','Boss','Aplikasi yang sangat membantu untuk mengatur keuangan. Sekarang saya bisa menabung lebih baik!','5','active','2025-05-25 01:51:15','2025-05-25 01:51:58');
INSERT INTO `reviews` (`id`,`name`,`role`,`content`,`rating`,`status`,`created_at`,`updated_at`) VALUES ('12','Samsul','Mahasiswa','Fitur kategori dan laporan keuangannya lengkap. Cocok untuk yang mau mulai mengelola keuangan.','4','active','2025-05-25 01:58:10','2025-05-25 01:58:17');
INSERT INTO `reviews` (`id`,`name`,`role`,`content`,`rating`,`status`,`created_at`,`updated_at`) VALUES ('14','Rizky Arbian','Dosen Pembimbing','Design simple sangat mudah digunakan, dan banyak fitur','5','active','2025-05-29 07:18:16','2025-05-29 07:18:16');
INSERT INTO `reviews` (`id`,`name`,`role`,`content`,`rating`,`status`,`created_at`,`updated_at`) VALUES ('15','Celo','Pengusaha Batu Bara','Sangat membantu dalam mangament keuangan saya','5','active','2025-05-29 08:32:40','2025-05-29 08:32:40');

CREATE TABLE `saving_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `savings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `status` enum('done','missed') NOT NULL DEFAULT 'done',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `savings_id` (`savings_id`),
  KEY `user_id` (`user_id`),
  KEY `date` (`date`),
  CONSTRAINT `saving_records_ibfk_1` FOREIGN KEY (`savings_id`) REFERENCES `savings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `saving_records` (`id`,`savings_id`,`user_id`,`amount`,`date`,`status`,`created_at`,`updated_at`) VALUES ('4','3','3','25000.00','2025-05-28','done','2025-05-29 03:25:36','2025-05-29 03:25:36');
INSERT INTO `saving_records` (`id`,`savings_id`,`user_id`,`amount`,`date`,`status`,`created_at`,`updated_at`) VALUES ('5','3','3','25000.00','2025-05-29','done','2025-05-29 03:25:40','2025-05-29 07:54:50');
INSERT INTO `saving_records` (`id`,`savings_id`,`user_id`,`amount`,`date`,`status`,`created_at`,`updated_at`) VALUES ('6','4','6','25000.00','2025-05-29','done','2025-05-29 08:10:44','2025-05-29 08:10:44');

CREATE TABLE `savings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `target_amount` decimal(15,2) NOT NULL,
  `daily_amount` decimal(15,2) NOT NULL,
  `wish_target` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `saved_amount` decimal(15,2) DEFAULT 0.00,
  `payment_count` int(11) DEFAULT 0,
  `total_days_needed` int(11) DEFAULT 0,
  `start_date` date NOT NULL,
  `target_date` date DEFAULT NULL,
  `is_achieved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_achieved` (`is_achieved`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `savings` (`id`,`user_id`,`target_amount`,`daily_amount`,`wish_target`,`description`,`saved_amount`,`payment_count`,`total_days_needed`,`start_date`,`target_date`,`is_achieved`,`created_at`,`updated_at`) VALUES ('3','3','5000000.00','25000.00','Rumah','Beli Rumah di Monopoli','50000.00','2','200','2025-05-29',NULL,'0','2025-05-29 03:23:52','2025-05-29 07:54:50');
INSERT INTO `savings` (`id`,`user_id`,`target_amount`,`daily_amount`,`wish_target`,`description`,`saved_amount`,`payment_count`,`total_days_needed`,`start_date`,`target_date`,`is_achieved`,`created_at`,`updated_at`) VALUES ('4','6','5000000.00','25000.00','membeli keyboard','d','25000.00','1','200','2025-05-29',NULL,'0','2025-05-29 08:10:40','2025-05-29 08:10:44');

CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('1','website_name','FinanceFlow','2025-05-22 00:43:09','2025-05-25 01:32:12');
INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('2','website_description','Catat Keuangan Jadi Menyenangkan','2025-05-22 00:43:09','2025-05-25 01:32:12');
INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('3','admin_email','ahmadkhadifar@gmail.com','2025-05-22 00:43:09','2025-05-25 01:32:12');
INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('4','maintenance_mode','0','2025-05-22 00:43:09','2025-05-25 01:32:12');
INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('5','contact_phone','089666285670','2025-05-22 01:33:25','2025-05-22 01:47:54');
INSERT INTO `settings` (`id`,`setting_key`,`setting_value`,`created_at`,`updated_at`) VALUES ('6','contact_address','Jalan raya Krukut','2025-05-22 01:33:25','2025-05-22 01:47:54');

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `users` varchar(255) NOT NULL,
  `category` mediumtext NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci NOT NULL,
  `amount` double NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `image_receipt` mediumtext DEFAULT NULL,
  `status` enum('EXPENSE','INCOME') CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaction` (`id`,`transaction_id`,`users`,`category`,`description`,`amount`,`transaction_date`,`image_receipt`,`status`,`created_at`) VALUES ('196','683325c2b4e92TRx','ahmadkhadifar','Gaji','ff','89849','2025-05-25',NULL,'INCOME','2025-05-25 21:14:26');
INSERT INTO `transaction` (`id`,`transaction_id`,`users`,`category`,`description`,`amount`,`transaction_date`,`image_receipt`,`status`,`created_at`) VALUES ('197','68337a840901eTRx','ahmadkhadifar','Makanan','d','44444','2025-05-26','1748204163_29c0fb810b75aaa90d12.jpg','EXPENSE','2025-05-26 03:16:04');
INSERT INTO `transaction` (`id`,`transaction_id`,`users`,`category`,`description`,`amount`,`transaction_date`,`image_receipt`,`status`,`created_at`) VALUES ('198','687b364bc323eTRx','ahmadkhadifar','Makanan','','100000','2025-07-19',NULL,'EXPENSE','2025-07-19 13:08:11');

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) NOT NULL DEFAULT 'assets/images/default.jpg',
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('3','Ahmad Khadifar','default.jpg','ahmadkhadifar@gmail.com','ahmadkhadifar','$2y$05$wvCX64StKiOqUMnmg7icPu.g/6DsZ.B3mpgSxv/15XAxsAM6klE9K',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-05-03 08:41:44','2025-07-22 03:49:33',NULL,'admin');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('6','aong','default.jpg','aong@gmail.com','aong123','$2y$05$b6.OD/BGost1Jar3x8ciW.vRz5j4lUncyCPOYr5buwY184E7MPjl.',NULL,NULL,NULL,NULL,NULL,NULL,'1','0','2025-05-05 22:09:28','2025-05-29 08:10:24',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('7',NULL,'assets/images/default.jpg','edgar@gmail.com','edgar','$2y$10$p/81jyjGS4buwQwLmNnVpu.8TmFJbBlIEWh0BZxHf4xiGGNxkMSxe',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-05-20 20:14:22','2025-05-20 20:14:22',NULL,'admin');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('8',NULL,'assets/images/default.jpg','dddddd@gmail.com','ahmadkhadifar_','$2y$10$mM7JpQRNvCqLT.osM0wNBu0Nd70JlAa47BNMQe6YoeJ/RdZ7ZwlOa',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-05-20 21:03:51','2025-05-20 21:03:51',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('9',NULL,'assets/images/default.jpg','celo@gmail.com','celo','$2y$10$f3wNqfZgGsqj9noEGUStPOE2TwyxB6WXpAaYD1KEUgt.RWIRE1epu',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-05-29 04:00:31','2025-05-29 04:00:31',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('10',NULL,'assets/images/default.jpg','iki@gmail.com','iki123','$2y$10$UNHRlYGuZEWte7WHcbej9ec2Lxr.uzF5hwUvIHfqNWd6FGxl8bHd.',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-05-29 04:03:19','2025-05-29 04:03:19',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('11',NULL,'assets/images/default.jpg','bambang@gmail.com','bambang123','$2y$10$bXCLi21MdDoDlMFqzt/20ObO7xhuh6X2YBVtNrqzj0SqCLK7wMfAC',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-05-29 04:20:25','2025-05-29 04:20:25',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('14',NULL,'assets/images/default.jpg','gilangmugi@gmail.com','gilangmugi','$2y$12$kH2.ltqrkWK4Gok6iPT..usa4YhNPcSFWyyip50fZ9tU6S4lSQZRS',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-07-21 17:26:17','2025-07-21 17:26:17',NULL,'user');
INSERT INTO `users` (`id`,`fullname`,`user_image`,`email`,`username`,`password_hash`,`reset_hash`,`reset_at`,`reset_expires`,`activate_hash`,`status`,`status_message`,`active`,`force_pass_reset`,`created_at`,`updated_at`,`deleted_at`,`role`) VALUES ('15',NULL,'assets/images/default.jpg','aufarhoki@gmail.com','aufahoki23','$2y$12$S0jganWqgzYRuSauAkTnke5SiGJ1GjW18RumZjGaFtT9QyRP0mAQy',NULL,NULL,NULL,NULL,'active',NULL,'1','0','2025-07-21 17:30:01','2025-07-21 17:30:01',NULL,'user');

