<!-- closesession.php
Created by Joseph Ridgway
11/20/21 -->

<?php

	session_start();
	
	$_SESSION = array();

	session_destroy();

	header("location: index.php");
	exit;
?>