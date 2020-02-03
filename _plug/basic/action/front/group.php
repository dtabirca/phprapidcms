<?php
/**
 * 
 */

if(!defined('COMMON')){
	header('Location: //');
}


$sql = "select * from content where type=:type and path=:path";
$stmt = $db->prepare($sql);
$type = 'group';
$stmt->bindParam(':type', $type);
$stmt->bindParam(':path', $page);
$stmt->execute();
if ($stmt->rowCount() > 0 ){
	$item = $stmt->fetch(PDO::FETCH_ASSOC);
	$item['pieces']		  = json_decode($item['pieces'], true);
	$item['body'] 	      = $Parsedown->text($item['body']);
	$tpl_vars['meta_description'] = $item['pieces']['meta_description'];
	$tpl_vars['item']			= $item;
}
else{
	header('Location: 404');
}


$sql = "select id, path, title, created, body, state, pieces from content where parent=:path order by created desc";
$stmt = $db->prepare($sql);
$stmt->bindParam(':path', $page);
$stmt->execute();

$post_list = array();
if ($stmt->rowCount() > 0 ){
	$all = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($all as $row){ 
		$row['pieces'] = json_decode($row['pieces'], true);
		$post_list[]     = $row;
	}
}

$tpl_vars['post_list'] = $post_list;