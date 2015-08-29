<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\framework;

use csmc\native\debug\log as log;
use csmc\native\oauth\session as session;
use csmc\native\uinterface\ui as ui;

//CSMC framework class
/**
 * The input/output class that handles all the requests and responses.
 */
class ios{

    //Initializes the instance
	/**
	 * [in The method that processes an incoming request from a user]
	 */
	public static function in(){
		//Checks if a url command was made
		if(ios::commands()){return;}
		//Ajax request
		if(isset($_POST["ajax"])){
			log::add(log::NOTICE, "A new ajax request was made.");
			//Set post parameters that are passed by an ajax call
			if(isset($_POST["params"])){
				$_SESSION["post_params"] = json_decode($_POST["params"], true);
			}
			//Checks if the model of the request is followed and all the necessary verifications to
			//a successful request
			if(!empty(trim($_POST["namespace"])) && !empty(trim($_POST["classname"])) && !empty(trim($_POST["method"]))){
				$method = $_POST["method"];
				$fully_qualified_classname = self::existsClass($_POST["namespace"], $_POST["classname"]);
				if($fully_qualified_classname != "" 								// If the fully qualified class name is not empty
				&& self::existsMethod($fully_qualified_classname, $method) 			// and the specified class and method exist
																					// in the native or module namespace
				&& isset($fully_qualified_classname::$func_whitelist) 				// and a func_whitelist array is set
				&& in_array($method, $fully_qualified_classname::$func_whitelist))	// and the method is a method name in the func_whitelist array
				{
					$objClass = new $fully_qualified_classname;
					$objClass->$method();
					log::add(LOG::NOTICE, "Ajax Request successful for ". $fully_qualified_classname . "::". $method ." .");
					return;
				} else {
					//If you get this redirect than you are sure that one of the above conditions failed and
					//should be checked by reading the debug log entries.
					log::add(LOG::NOTICE, "Ajax Request failed for ". $fully_qualified_classname . "::". $method ." .");
					redirects::error(404);
				}
			} else {
				log::add(LOG::NOTICE, "Ajax Request failed with missing parameters.");
				redirects::error(401);
			}
		} else if(isset($_GET["_escaped_fragment_"])){
			log::add(log::NOTICE, "A new _escaped_fragment_ request was made.");
			$ncm = explode("/", $_GET["_escaped_fragment_"]);
			echo count($ncm);
			if(count($ncm) >= 3){
				$fully_qualified_classname = self::existsClass($ncm[1], $ncm[2]);
				$method = $ncm[3];
				if($fully_qualified_classname != "" 								// If the fully qualified class name is not empty
				&& self::existsMethod($fully_qualified_classname, $ncm[3]) 			// and the specified class and method exist
																					// in the native or module namespace
				&& isset($fully_qualified_classname::$func_whitelist) 				// and a func_whitelist array is set
				&& in_array($method, $fully_qualified_classname::$func_whitelist))	// and the method is a method name in the func_whitelist array
				{
					$objClass = new $fully_qualified_classname;
					ui::create($objClass->$method());
					log::add(LOG::NOTICE, "Escaped fragment successful for ". $fully_qualified_classname . "::". $method ." .");
					return;
				} else {
					//If you get this redirect than you are sure that one of the above conditions failed and
					//should be checked by reading the debug log entries.
					log::add(LOG::NOTICE, "Escaped fragment request failed for ". $fully_qualified_classname . "::". $method ." .");
					redirects::error(404);
				}
			} else {
				log::add(LOG::NOTICE, "Escaped fragment request failed. With ".$ncm[1]." ".$ncm[2]." ".$ncm[3]);
				redirects::error(401);
			}
		} else {
			//If the application is being initialized for the first time or session the entire framework must be configured
			//after this the configuration is not required and the ui is created. This allows for faster ui display after the first
			//request.
			if(!isset($_SESSION["csmc_native_framework_config_status"]) || $_SESSION["csmc_native_framework_config_status"] == false){
				log::add(log::NOTICE, "IOS is initializing.");
				//Starts the configuration, if it fails several error pages exist to aid the programmer or the user.
				//If the application fails at this point it means something about the CSMC Framework is not configured correctly.
				if(config::start()){
					ui::create();
				} //There is no false condition because it already is handled with a proper redirect in the config class
			//This means that the application was already configured for this session and we only have
			//to initialize a new user interface.
			} else if(isset($_SESSION["csmc_native_framework_config_status"])){
				log::add(log::NOTICE, "IOS already initialized, starting a new ui.");
				ui::create();
			}
		}
	}

	/**
	 * [out - The method to output any kind of data to the user]
	 * @param  string  $message      The content to be displayed in the desktop area
	 * @param  string  $notification A notification to be shown to the user (i.e.: Login successful)
	 * @param  string  $script       A script to be executed when the data is sent
	 * @param  integer $statusHeader An HTTP status header to be sent, default is 200
	 * @param  string  $place        The location where the message will be shown, default is the javascript script default (desktop)
	 * @return void
	 */
	public static function out($message = "", $notification = "", $script = "", $statusHeader = 200, $place = json::NONE){
		if(isset($_POST["ajax"])){
			//This prevents up to a certain level PHP warnings and what not to mess up
			//the JSON response when the application is in a production environment
			if(__DEV_ENVIRONMENT__ == 0){ob_end_clean();}
			json::send($message, $notification, $place, $statusHeader, $script);
			return;
		} else {
			if(isset($_SESSION["csmc_native_uinterface_ui_startupcall"])){
				return $message;
			} else {
				echo ui::create($message);
				return;
			}
		}
	}

	/**
	 * [existsClass Checks if a certain class name exists in the native or module namespace]
	 * @param  string $namespace The namespace (folder) of the class
	 * @param  string $classname The name of the class
	 * @return bool/string 	     Returns false if class does not exist
	 *                           Returns a fully qualified class name  if the class exists
	 */
	private static function existsClass($namespace, $classname){
		$nativeNamespace = "\\csmc\\native";
		$moduleNamespace = "\\csmc\\modules";
		if(class_exists($nativeNamespace."\\".$namespace."\\".$classname)){
			return $nativeNamespace."\\".$namespace."\\".$classname;
		} else {
			if(class_exists($moduleNamespace."\\".$namespace."\\".$classname)){
				return $moduleNamespace."\\".$namespace."\\".$classname;
			} else {
				return false;
			}
		}
	}

	/**
	 * [existsMethod Checks if a certain method exists in a given "namespaced" class]
	 * @param  string $classname The fully qualified class name
	 * @param  string $method    The name of the method
	 * @return bool              Returns true if method exists
	 */
	public static function existsMethod($classname, $method){
		if(method_exists($classname, $method)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [commands A list of commands that can be executed in many situations]
	 */
	public static function commands(){
		//Reboots the application
		if(isset($_GET["exit"]) && !isset($_POST["ajax"])){
			session::destroy();
		}
		//Shows the application log if in debug mode
		// NOTE: IP restriction will be added in the future
		//       configurable through the config file.
		if(isset($_GET["log"]) && !isset($_POST["ajax"])){
			if(defined("__DEV_ENVIRONMENT__")){
				if(__DEV_ENVIRONMENT__ == 1){
					log::show();
					return true;
				}
			}
		}
		return false;
	}
}
?>