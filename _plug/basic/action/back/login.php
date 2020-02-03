<?php
/**
 * 
 */

if(!defined('COMMON')){
	header('Location: //');
}

if (isset($_SESSION['back_login'])){
	header('Location: ' . $BASE_URL);	
}

if (isset($_POST['user']) && isset($_POST['pass'])){

	// spam filter
	if ($_POST['fill_me'] != ""){
		return false;
		exit;		
	}
	if ($_POST['app_key'] != $APP_KEY){
		return false;
		exit;		
	}

	$user = filter_input(INPUT_POST, 'user');
	$pass = filter_input(INPUT_POST, 'pass');

	if ($BACK_LOGIN == $user && $BACK_PASS == md5($pass)){

		$_SESSION['back_login'] = 'Admin'; 				
		header('Location: ' . $BASE_URL);

	} else {
		
		$tpl_vars['messages'][] = array('text' => 'Wrong username or password!', 'type' => 'warning');  
	}
}
