<?php
	//Defines global variables used by the framework
	define("CSMC_CORE_ROOT", $_SERVER["DOCUMENT_ROOT"]."/csmc");
	define("CSMC_NATIVE_ROOT", CSMC_CORE_ROOT."/native/");
	define("CSMC_MODULES_ROOT", CSMC_CORE_ROOT."/modules/".__INSTANCE__."/");
	define("INSTANCE_CONFIG_ROOT", CSMC_CORE_ROOT."/instance.".__INSTANCE__.".config");
	define("AUTOLOAD_ROOT", CSMC_CORE_ROOT."/autoload.php");
	define("NATIVE_NAMESPACE", 'csmc\\native\\');
	define("MODULE_NAMESPACE", 'csmc\\modules\\'.__INSTANCE__.'\\');

	define("__INSTANCE__", "webapp"); //the name of the config file that will be called instance.thisinstancename.config
	define("__DEV_ENVIRONMENT__", 1); //0 (production mode) and 1 (development mode)
	//Requires the framework initiator file.
	require_once($_SERVER["DOCUMENT_ROOT"]."/csmc/native/csmc.php");
	//Starts the framework
	new csmc\native\csmc();
?>