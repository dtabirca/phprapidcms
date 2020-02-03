<?php
/**
 *
 */

if(!defined('COMMON')){
	header('Location: //');
}


$sql = "select * from content where type=:type and path=:path";
$stmt = $db->prepare($sql);
$type = 'simple';
$stmt->bindParam(':type', $type);
$stmt->bindParam(':path', $page);
$stmt->execute();

if ($stmt->rowCount() > 0 ){
	$item = $stmt->fetch(PDO::FETCH_ASSOC);
	$item['pieces']		  = json_decode($item['pieces'], true);
	$item['body'] 	      	  = $Parsedown->text($item['body']);
	$tpl_vars['meta_description'] = $item['pieces']['meta_description'];
	$tpl_vars['item']			= $item;
}
else{
	header('Location: 404');
}
