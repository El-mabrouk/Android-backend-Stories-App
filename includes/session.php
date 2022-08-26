<?php

	ob_start();
	session_start();
	
	$currentTime = time() + 25200;
	$expired = 86400;
	
	if (!isset($_SESSION['user'])) {
		header("location:index.php");
	}
	
	if ($currentTime > $_SESSION['timeout']) {
		session_destroy();
		header("location:index.php");
	}
	
	unset($_SESSION['timeout']);
	$_SESSION['timeout'] = $currentTime + $expired;

?>