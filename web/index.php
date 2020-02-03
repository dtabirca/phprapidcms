<?php
/**
 * bootstrap file  
 */

// headers
header("Cache-Control: no-cache, must-revalidate");

// set environment
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('display_errors', TRUE);
error_reporting(E_ALL);
ini_set('log_errors', FALSE);
//ini_set('error_log', '');
session_start();

// global definition
define('BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
require_once BASE_PATH . 'config.php';
require_once COMMON . 'vendor/autoload.php';
$BASE_URL  = '/';
$url_parts = explode('/', $_GET['q']);
$HTTPS 	   = true;
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
	$HTTPS = false;
}
$DOMAIN    = (($HTTPS) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'];

// is admin entrance?
$IS_ADMIN = FALSE;
if (isset($url_parts[0])){
	if ($url_parts[0] == ADMIN) { 
		array_shift($url_parts);
		$IS_ADMIN = TRUE;
	}
}
if ($IS_ADMIN) $BASE_URL .= ADMIN;

// get requested page
$page 	   = ((isset($url_parts[0]) && $url_parts[0] !== "") ? implode('/', $url_parts) : '');

// template globals
$tpl_vars  = array();
$tpl_vars['DOMAIN']     = $DOMAIN;
$tpl_vars['APP_NAME']   = $APP_NAME;
$tpl_vars['SLOGAN'] 	= $APP_SLOGAN;
$tpl_vars['APP_KEY'] 	= $APP_KEY;
$tpl_vars['BASE_URL']   = $BASE_URL;
$tpl_vars['javascript'] = array();
$tpl_vars['css'] 		= array();
$tpl_vars['messages']	= array();
$layout = 'layout.tmpl';

// display system message
if (isset($_SESSION['messages'])){
	$tpl_vars['messages'] = array_merge($tpl_vars['messages'], $_SESSION['messages']);
	unset($_SESSION['messages']);
}

// admin login 
if ($IS_ADMIN){
	if (!isset($_SESSION['back_login'])){
		$page = 'login';
	}
	if ($page == ''){
		$page 	   = 'home';
	}
}

// database connection
try {
	$db = new PDO($DATABASE, $DBUSER, $DBPASS, array( PDO::ATTR_TIMEOUT => 10 ));
} catch (PDOException $e) {
	$db = FALSE;
}

// markdown parser init
$Parsedown = new Parsedown();

// load modules
foreach ($MODULES as $m){
	include_once BASE_PATH . '/_plug/' . $m . '/init.php';
}

// is page defined?
if (!$IS_ADMIN){
	if (isset($pages[$page])){
		$page_name = $pages[$page][1];
		$included  = $pages[$page][0];
	} else {
		$page = '404';
	}
} else{
	if (isset($back_pages[$page])){
		$page_name = $back_pages[$page];
		$included  = $page;
	} else {
		$page = '404';
	}
}

// build breadcrumbs
$breadcrumbs = array();
if ($page != ''){
	if (!$IS_ADMIN){
		$breadcrumbs[] = '';
		foreach ($pages as $key => $value) {
			if ($page == $key){
				$pagearr = explode('/', $page);
				array_pop($pagearr);
				foreach ($pagearr as $item) {
					foreach ($pages as $key2 => $value2) {
						if ($item == $key2){
							$breadcrumbs[] = $key2;
							break;
						}
					}
				}
				break;
			}
		}
		$breadcrumbs[] = $page;
	} else{//admin
		$breadcrumbs[] = $page;
	}
}

// is 404?
if ($page == '404'){
	$included    = '404';
	$page_name 	 = '404';
} else{
	$tpl_vars['breadcrumbs']     = $breadcrumbs;
}

// load action
if (isset($current_module)){
	if ($IS_ADMIN){
		include_once BASE_PATH . '_plug/' . $current_module . '/action/back/' . str_replace('-', '_', $included) . '.php';
	} else{
		include_once BASE_PATH . '_plug/' . $current_module . '/action/front/' . str_replace('-', '_', $included) . '.php';
	}
} elseif($included != '404'){
	header('Location: ' . $BASE_URL . '/404');
}

// more globals for templates
$tpl_vars['page']       = $page;
$tpl_vars['included']   = $included;
$tpl_vars['page_name']  = $page_name;
$tpl_vars['menu'] 		= $menu;
$tpl_vars['footer']     = $footer;	
$tpl_vars['SESSION']	= $_SESSION;
$tpl_vars['GET']		= $_GET;
$tpl_vars['POST']		= $_POST;
$tpl_vars['REQUEST']	= $_REQUEST;
$tpl_vars['SERVER']		= $_SERVER;
$tpl_vars['pages'] 		= $pages;

if ($IS_ADMIN){
	$tpl_vars['back_pages'] 		= $back_pages;
}

// define template directory location
$loader = new Twig_Loader_Filesystem();
if ($IS_ADMIN)
	$loader->addPath( BASE_PATH . 'web/schemes/' . $theme . '/back');
else
	$loader->addPath( BASE_PATH . 'web/schemes/' . $theme . '/front');

foreach ($MODULES as $m){
	if ($IS_ADMIN)
		$loader->addPath( BASE_PATH . '_plug/' . $m . '/display/back');
	else
		$loader->addPath( BASE_PATH . '_plug/' . $m . '/display/front');
}	

// initialize Twig environment
try {
	$twig = new Twig_Environment($loader, array(
    	//'cache' => CACHE . 'twig',
	));
	$twig->addExtension(new Twig_Extensions_Extension_Text());
} catch (Exception $e) {
	die ('ERROR: ' . $e->getMessage());
}

// load template
$template = $twig->loadTemplate($layout);

// render template
$all_page_types = array();
foreach ($MODULES as $m){
	$all_page_types = array_merge($all_page_types, ${$m . '_page_types'});
}
$tpl_vars['all_page_types'] = $all_page_types;
echo $template->render($tpl_vars);