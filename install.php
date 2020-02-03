<?php
/**
 * database installer
 */

try {
	$db = new PDO("mysql:host=localhost", "root", "");
} catch (PDOException $e) {
	print_r($e); 
}

if ($db->exec("CREATE DATABASE `blank`") === false) {
	print_r($db->errorInfo());
}
if ($db->exec("USE `blank`") === false) {
	print_r($db->errorInfo());
}
if ($db->exec("CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `path` varchar(255) NOT NULL COMMENT 'Relative path uri',
  `parent` varchar(255) NOT NULL COMMENT 'Parent path uri',
  `pieces` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Page elements' CHECK (json_valid(`pieces`)),
  `state` tinyint(1) NOT NULL COMMENT 'Status',
  `type` varchar(20) NOT NULL COMMENT 'Page type',
  `body` longtext NOT NULL COMMENT 'Body',
  `created` date NOT NULL DEFAULT current_timestamp() COMMENT 'Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4") === false) {
	print_r($db->errorInfo());
}