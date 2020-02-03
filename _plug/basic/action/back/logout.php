<?php
/**
 * 
 */

if(!defined('VENDOR')){
	header('Location: //');
}

// portal
unset($_SESSION['back_login']);
header('Location: ' . $BASE_URL);		

exit;