<?php
// dont change the name of the variables

$user_actions = array_merge( $user_actions, array(
'list-users', 'add-user', 'edit-user', 'set-permissions', 'bulk-permissions',
'view-reports', 'view-history', 'change-email', 
'error-logs', 'access-logs', 'rewrite-log', 'php-log', 
'sessions-history', 
));

//$default_actions = array_merge( $default_actions, $array(
//));

$back_pages = array(
	//'sessions-history' => _('Sessions History'),
	//'bridge' => '',
	//'change-password' => _('Change Password'),
	//'sign-up' => _('Sign Up'),
	//'change-email' => _('Change Account'),
    //'users' => _('Users'),
	//'permissions' => _('Permissions'),
	//'bulk-permissions' => _('Bulk Permissions'),
 	//'history' => _('History'),
	//'download' => '',
	//'preview' => '',
	//'dashboard' => '',
	//'simple' => 'Simple Page',
	//'home' => _('Dashboard'),
	'login' => _('Login'),
	'logout' => _('Logout'),
);
$page_types = array('simple', 'link', 'contact');
//$pages = array_merge( $pages, $m_pages );

// dont touch this
if (!$IS_ADMIN){
	if (isset($page)){
		if (in_array($pages[$page][0], $page_types)){
			$current_module = basename(dirname(__FILE__));
		}
	}
} else{
	if (isset($page)){
		if (array_key_exists($page, $back_pages)){
			$current_module = basename(dirname(__FILE__));
		}
	}
}