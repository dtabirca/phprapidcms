<?php
/**
 * 
 */

if(!defined('VENDOR')){
	header('Location: //');
}

if(isset($_GET['type']))
	$type = $_GET['type'];
else
	$type = 'simple';


if(isset($_POST['save'])){
	if (isset($_GET['sl'])){
		$sql = "update content set body=:body, title=:title, path=:path, description=:description, created=:created where id=:id";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $_GET['sl']);
		$tpl_vars['messages'][] = array('text' => 'updated', 'type' => 'info');
	} else{
		$sql = "insert into content set body=:body, title=:title, path=:path, description=:description, created=:created, type=:type";
		$stmt = $db->prepare($sql);
		//$type = 'simple';
		$stmt->bindParam(':type', $type);
		$tpl_vars['messages'][] = array('text' => 'inserted', 'type' => 'info');
	}
	$stmt->bindParam(':body', $_POST['body']);
	$stmt->bindParam(':title', $_POST['title']);
	$stmt->bindParam(':path', $_POST['path']);
	//$stmt->bindParam(':keywords', $_POST['keywords']);
	$stmt->bindParam(':description', $_POST['description']);
	$stmt->bindParam(':created', $_POST['created']);
	$stmt->execute();
}

if(isset($_GET['sl'])){
	$sql = "select * from content where id=:id";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':id', $_GET['sl']);
	$stmt->execute();
	if ($stmt->rowCount() > 0 ){
		$tpl_vars['current']  = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
	}
}

$sql = "select id, title from content where type=:type";
$stmt = $db->prepare($sql);
// $type = 'simple';
$stmt->bindParam(':type', $type);
$stmt->execute();

$simple_list = array();
if ($stmt->rowCount() > 0 ){
	$all = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($all as $row){ 
		$simple_list[$row['id']] = $row['title'];
	}
}

$tpl_vars['simple_list'] = $simple_list;