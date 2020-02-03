<?php
/**
 * 
 */

if(!defined('COMMON')){
	header('Location: //');
}

if(isset($_GET['type']))
	$type = $_GET['type'];
else
	$type = 'basic';// includes all the rest

# save page
if(isset($_POST['save'])){
	if (isset($_GET['se'])){
		$sql = "update content set body=:body, title=:title, path=:path, parent=:parent, pieces=:pieces, state=:state where id=:id";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $_GET['se']);
		$tpl_vars['messages'][] = array('text' => 'updated', 'type' => 'info');
	} else{
		$sql = "insert into content set body=:body, title=:title, path=:path, parent=:parent, pieces=:pieces, state=:state, type=:type";
		$stmt = $db->prepare($sql);
		//$type = 'simple';
		$stmt->bindParam(':type', $type);
		$tpl_vars['messages'][] = array('text' => 'inserted', 'type' => 'info');
	}

	$pieces = array(
		'featured_image' => $_POST['featured_image'],
		'more_images'    => $_POST['more_images'],
		'meta_description' => $_POST['meta_description'],
		'show_catalog_list' => isset($_POST['show_catalog_list']) ? 1 : 0,
		'show_contact_form' => isset($_POST['show_contact_form']) ? 1 : 0,
		'show_comments_box' => isset($_POST['show_comments_box']) ? 1 : 0,
		'show_publish_date' => isset($_POST['show_publish_date']) ? 1 : 0,
		'tags' => $_POST['tags'],
	);
	$pieces = json_encode($pieces);
	//$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt->bindParam(':body', $_POST['body']);
	$stmt->bindParam(':title', $_POST['title']);
	$stmt->bindParam(':path', $_POST['path']);
	$stmt->bindParam(':parent', $_POST['parent']);
	$stmt->bindParam(':pieces', $pieces);
	$state = isset($_POST['hide_this']) ? 0 : 1;
	$stmt->bindParam(':state', $state);
	$stmt->execute();

	header('Location: content');
}

# edit page
if(isset($_GET['se'])){
	$sql = "select * from content where id=:id";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':id', $_GET['se']);
	$stmt->execute();
	if ($stmt->rowCount() > 0 ){
		$item = $stmt->fetch(PDO::FETCH_ASSOC);
		$tpl_vars['current']  = $item;
		$tpl_vars['current']['pieces'] = json_decode($item['pieces'], true);
		$page_name = $item['title'];
	}
}

# remove page
if (isset($_GET['sd'])){
	$sql = "delete from content where id=:id";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':id', $_GET['sd']);
	//$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt->execute();

	header('Location: content');
}

# list of pages
$sql = "select id, title, type, parent, path, state from content where type=:type order by parent, title";
$stmt = $db->prepare($sql);
$stmt->bindParam(':type', $type);
$stmt->execute();
$simple_list = array();
if ($stmt->rowCount() > 0 ){
	$all = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($all as $row){ 
		$simple_list[] = $row;
	}
}
$tpl_vars['simple_list'] = $simple_list;