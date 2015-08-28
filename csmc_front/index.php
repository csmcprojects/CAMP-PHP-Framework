<?php
	define("__INSTANCE__", "webapp"); //the name of the config file that will be called instance.thisinstancename.config
	define("__DEV_ENVIRONMENT__", 1); //0 (production mode) and 1 (development mode)
	//Requires the framework initiator file.
	require_once($_SERVER["DOCUMENT_ROOT"]."/csmc/native/csmc.php");
	//Starts the framework
	new csmc\native\csmc();
?>