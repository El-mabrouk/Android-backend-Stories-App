<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require_once "library/PHPMailer.php";
	require_once "library/Exception.php";
	require_once "library/OAuth.php";
	require_once "library/POP3.php";
	require_once "library/SMTP.php";

	$response = array();

	require_once "smtp-config.php";

	$email		= $_POST['email'];
	$password	= $_POST['password'];
   
	$mail = new PHPMailer(true);

	try {

		//Enable verbose debug output
	    $mail->SMTPDebug = 0;
	    //Send using SMTP
	    $mail->isSMTP();
	    //Set the SMTP server to send through
	    $mail->Host       = $smtp_host;
	    //Enable SMTP authentication
	    $mail->SMTPAuth   = true;
	    //SMTP username
	    $mail->Username   = $smtp_email;
	   	//SMTP password
	    $mail->Password   = $smtp_password;
	    //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	   	$mail->SMTPSecure = $smtp_secure;
	   	//TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	    $mail->Port       = $smtp_port;

	    //Sender
	    $mail->setFrom($smtp_email, $sender);

	    //Rrecipient
	    $mail->addAddress($email);

	    // Content
	    // Set email format to HTML
	    $mail->isHTML(true);
	    
	    $mail->Subject = $subject;
	    $mail->Body    = $body.' <b>'.$password.'</b>';

	    $mail->send();

		$response["value"] = 1;
		$response["message"] = "Message has been sent successfully";
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($response);

	} catch (Exception $e) {
		$response["value"] = 0;
		$response["message"] = "Mailer Error";
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($response);
	}


?>