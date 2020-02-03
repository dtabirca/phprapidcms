<?php
/**
 *
 */

if(!defined('COMMON')){
	header('Location: //');
}


# default simple function
$sql = "select * from content where type=:type and path=:path";
$stmt = $db->prepare($sql);
$type = 'basic';
$stmt->bindParam(':type', $type);
$stmt->bindParam(':path', $page);
$stmt->execute();
if ($stmt->rowCount() > 0 ){
	$item = $stmt->fetch(PDO::FETCH_ASSOC);
	$item['pieces']		  = json_decode($item['pieces'], true);
	$item['body'] 	      = $Parsedown->text($item['body']);
	$tpl_vars['meta_description'] = $item['pieces']['meta_description'];
	$tpl_vars['tags'] = $item['pieces']['tags'];
	$tpl_vars['item']			= $item;	
}
else{
	header('Location: 404');
}

# contact form function
if ((int)$item['pieces']['show_contact_form'] === 1){
	if (isset($_POST['submit'])){

		// spam filter
		if ($_POST['fill_me'] != ""){
			return false;
			exit;		
		}
		if ($_POST['app_key'] != $APP_KEY){
			return false;
			exit;		
		}

		$name    = filter_input(INPUT_POST, 'name');
		$email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$message = filter_input(INPUT_POST, 'message');
		$human   = filter_input(INPUT_POST, 'human');

		if ($name!='' && $email!='' && $message!='' && $_SESSION['formula']==$human){
							
			$mail = new PHPMailer(true);
			if ($mailsissmtp){
				
				$mail->isSMTP();                          
				$mail->Host 		= $mailconfig['host'];  
				$mail->Port 		= $mailconfig['port'];
				$mail->SMTPAuth 	= true;                 
				$mail->Username 	= $mailconfig['user'];
				$mail->Password 	= $mailconfig['pass'];             
				$mail->SMTPSecure 	= 'tls';                
			}

			try{
				$mail->From 		= $email;
				$mail->FromName 	= $name;
				$mail->addReplyTo('no-reply@' . $APP_NAME, 'Do not reply to this e-mail!');
				$mail->AddAddress($admin_email, $APP_NAME . ' admin');
				$mail->isHTML(true);
				$mail->Subject = $APP_NAME . ' contact us form';
				$mail->Body    = $name . '[' . $email . ']';
				$mail->AltBody = implode("\n", $_POST);
				$mail->send();
				$tpl_vars['messages'][] = array('text' => 'Message Sent.', 'type' => 'info');
			} catch (phpmailerException $e){
				$tpl_vars['messages'][] = array('text' => 'Something went wrong. Please try again later.', 'type' => 'warning');
			}
		} else{
			$tpl_vars['messages'][] = array('text' => 'Fill all fields.', 'type' => 'warning');
		}
	}

	$operation   = array('-', '+');
	$operation   = $operation[array_rand($operation)];
	$digitone     = rand(1,9);
	$digittwo	  = rand(1,9);
	$formula      = $digitone . ' ' . $operation . ' ' . $digittwo;
	$tpl_vars['formula'] = $formula;
	eval('$_SESSION["formula"] = ' . $digitone . $operation . $digittwo . ';');
}

# catalog list function
if ((int)$item['pieces']['show_catalog_list'] === 1){
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
}