<?php
// dont change the name of the variables
$back_pages = array(
	'home' => _('Dashboard'),
	'content' => 'Content',
	'login' => _('Login'),
	'logout' => _('Logout'),
);
$basic_page_types = array('basic', 'link', 'simple', 'group', 'contact');

// dont touch this
if (!$IS_ADMIN){
	if (isset($page)){
		// defined page
		if (isset($pages[$page][0])){
			if (in_array($pages[$page][0], $basic_page_types)){
				$current_module = basename(dirname(__FILE__));
			}
		} else{
			// wildcard
			$wildcardarr = explode('/', $page);
			array_pop($wildcardarr);
			$wildcard    = implode('/', $wildcardarr) . '/*';
			if (isset($pages[$wildcard][0])){
				if (in_array($pages[$wildcard][0], $basic_page_types)){
					$current_module = basename(dirname(__FILE__));
					$sql  = "select * from content where type=:type and path=:path";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':type', $pages[$wildcard][0]);
					$stmt->bindParam(':path', $page);
					$stmt->execute();
					if ($stmt->rowCount() > 0 ){
						$content = $stmt->fetch(PDO::FETCH_ASSOC);
						$title   = $content['title'];
						$pages[$page] = array($pages[$wildcard][0], _($title));
					} else { //$pages[$page] = array('simple', '404'); 
					}
				}
			}
		}
	}
} else{
	if (isset($page)){
		if (array_key_exists($page, $back_pages)){
			$current_module = basename(dirname(__FILE__));
		}
	}
}