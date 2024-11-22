<?php
	if(session_id() == "" || !isset($_SESSION)) {
	    //session isn't started
	    session_start();
	}

	$GLOBAL_DATETIME = "Asia/Kuala_Lumpur";
	date_default_timezone_set($GLOBAL_DATETIME);

	$DBSERVER = "localhost";
	$DBUSER = "root";	
    $DBPASS = "";
	$DBNAME = "onboarding_app";


	$baseURL = "http://localhost/app/";
	
	 // Path to the system directory (Temp)
	define('BASEPATH', $baseURL);
?>
