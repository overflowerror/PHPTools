<?php	
	if(!isset($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] != "on")) {
		header("LOCATION: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		die();
	}
?>
