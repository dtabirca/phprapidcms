<?php
/**
 * development configuration
 */

// globals
$APP_NAME   = 'PHPrapidCMS Demo';
$APP_SLOGAN = 'A watched pot never boils';
$BACK_LOGIN = 'admin';
$BACK_PASS  = '21232f297a57a5a743894a0e4a801fc3';// md5 for "admin"
$APP_KEY    = 'B3558BC54D252913F7492B6421612';// used to validate forms, for now

// paths
define('ADMIN',   'backside-entrance');
define('UPLOADS', BASE_PATH . '_upls/');
define('CACHE',   BASE_PATH . '_cache/');
define('COMMON',  BASE_PATH . '_common/');

// mail server, for contact forms
$admin_email = 'demo@phprapidcms.com';
$mailsissmtp = TRUE; 
$mailconfig  = array('host' => 'smtp.example.com',
					'port'  => '587',
					'user' 	=> 'username',
					'pass'	=> 'password',
				);

// define modules
$MODULES = array(
		'basic',
//		'blog',
);

// theme
$theme       = 'default';

// page definition example
// old page types (simple,group,contact) were combined into a single "basic" type
$pages = array(
'' 		   => array( 'basic', _('Home')),
// menu group
'group'    => array( NULL, _('Group')),// only used as parent for visual grouping inside the menu
'group/item-1' => array('basic', _('Basic Item 1')),// path could be changed to anything since it doesn't really depend on the parent
'group/item-2' => array('basic', _('Basic Item 2')),
// link type will redirect to provided url
'link' => array( 'link', _('Link'), '//example.com'),
// with form
'contact'  => array( 'basic', _('Contact')),
// catalog
'store'    => array( 'basic', _('Store')),
'store/*'  => array( 'basic', NULL ),// wildcard defined pages will automatically be recognised as children
// blog 
'blog'    => array( 'basic', _('Blog')),
'blog/*'  => array( 'basic', NULL),// name is taken from the database
// footer
'privacy-policy'      => array('basic', _('Privacy Policy')),
'terms'      => array('basic', _('Terms')),
 );

// menus are defined based on the page paths above
$menu = array(
	'',
	'group' => array('group/item-1', 'group/item-2', 'link'),
	'store',
	'blog',
	'contact',
);
$footer = array(
	'privacy-policy',
	'terms',
);

// database
$DATABASE = 'mysql:host=localhost;port=3306;dbname=blank;';
$DBUSER = 'root';
$DBPASS = '';