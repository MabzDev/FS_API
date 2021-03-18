CREATE DATABASE 'FS_api'

CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `fname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `lname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
 `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
) 

CREATE TABLE `customerticket` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `userid` int(11) COLLATE utf8mb4_unicode_ci NOT NULL,
 `message` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) 