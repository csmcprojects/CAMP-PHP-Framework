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
use csmc\native\other\misc as misc;
use csmc\native\framework\redirects as redirects;
use csmc\native\uinterface\nav as nav;

/**
 * This class is responsable for setting up the framework and i is initialized once per
 * user session. To reboot the framework use the /exit url. It is responsible to configure
 * the following stuff:
 *
 *   	- Instance configuration;
 *    	- Module configuration;
 *      - Other stuff.
 */
class config{
	/**
	 * start - Configures all the CSMC Framework things!
	 * @return [bool]
	 */
	public static function start(){
		//Checks if instance name exists and is set
		if(defined("__INSTANCE__")){
			if(__INSTANCE__ !== null){
				//Checks if dev_environment setting exists and is set, if exists but invalid a default is set
				if(defined("__DEV_ENVIRONMENT__")){
					if(__DEV_ENVIRONMENT__ == 0 || __DEV_ENVIRONMENT__ == 1){
						//Sets the error display
						if(__DEV_ENVIRONMENT__ == 0){
							error_reporting(0);
						} else if(__DEV_ENVIRONMENT__ == 1){
							error_reporting(E_ALL);
						}
						//Cleans the navigation bar
						nav::reboot();
						//Checks if the config file exists
						if(self::instanceConfigFileExists()){
							//Loads the config
							if(self::instanceConfig()){
								//Loads the modules
								if(self::modulesConfig()){
									//Gets the application salt key
                                    $salt = config::getSalt();
									//Checks if the salt field is missing from the configuration file
									if($salt == ""){
										redirects::error(704, "Missing salt index or salt index is empty.");
										return false;
									} else {
										//Checks if it is not empty
										if(trim($salt) != ""){
											//Sets a global variable to set that the application as been configured
											//and it does not need to do it in the following requests
											$_SESSION["csmc_native_framework_config_status"] = true;
											log::add(log::NOTICE, "Configuration Completed.");
											return true;
										}
									}
								} else {
									redirects::error(705);
									return false;
								}
							} else {
								redirects::error(704, "Instance configuration failed.");
								return false;
							}
						} else {
							redirects::error(703);
							return false;
						}
					} else {
						redirects::error(706);
						return false;
					}
				} else {
					redirects::error(702);
					return false;
				}
			} else {
				redirects::error(701);
				return false;
			}
		} else {
			redirects::error(701);
			return false;
		}
		return false;
	}

	public static function getSalt(){
		$details = json_decode(misc::getFileContent(INSTANCE_CONFIG_ROOT), true);
		if(!isset($details["salt"])){
			return "";
		} else {
			return $details["salt"];
		}
	}
	/**
	 * [instanceConfig Configures the instance config file. ]
	 * @return [type] [description]
	 */
	private static function instanceConfig(){
        $_SESSION["csmc_native_config"] = misc::getFileContent(INSTANCE_CONFIG_ROOT);
		$_SESSION["csmc_native_config"] = json_decode($_SESSION["csmc_native_config"], true);
		//Checks the native configuration file strucuture
		if(!isset($_SESSION["csmc_native_config"]["info"]) ||
			!isset($_SESSION["csmc_native_config"]["info"]["app_name"]) ||
			!isset($_SESSION["csmc_native_config"]["info"]["url"]) ||
			!isset($_SESSION["csmc_native_config"]["info"]["image"]) ||
			!isset($_SESSION["csmc_native_config"]["info"]["webmaster_email"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["charset"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["viewport"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["generator"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["base"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["favicon"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["user_lang"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["description"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["atst"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["amwac"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["mwac"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["amawsbs"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["cti192"]) ||
			!isset($_SESSION["csmc_native_config"]["html"]["ati"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]["omysqli"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]["omysqli"]["host"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]["omysqli"]["user"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]["omysqli"]["pass"]) ||
			!isset($_SESSION["csmc_native_config"]["database"]["omysqli"]["db"]) ||
			!isset($_SESSION["csmc_native_config"]["salt"]) ||
			!isset($_SESSION["csmc_native_config"]["startup"]) ||
			!isset($_SESSION["csmc_native_config"]["startup"]["namespace"]) ||
			!isset($_SESSION["csmc_native_config"]["startup"]["classname"]) ||
			!isset($_SESSION["csmc_native_config"]["startup"]["method"]) ||
			!isset($_SESSION["csmc_native_config"]["startup"]["params"]) ||
			!isset($_SESSION["csmc_native_config"]["timezone"]))
		{
			print_r($_SESSION["csmc_native_config"]);
			log::add(log::WARNING, "Config file failed to load.");
			return false;
		}
		//We don't want db details to be stored in a global variable,
		//we can get the details from config::instanceDatabaseDetalis()
		if(isset($_SESSION["csmc_native_config"]["database"])){
			$_SESSION["csmc_native_config"]["database"] = array();
		}
		log::add(log::DEBUG, "Config file loaded.");
		return !empty($_SESSION["csmc_native_config"]);
	}
	/**
	 * [instanceConfigFileExists Checks if the instance config file exists.]
	 * @return [bool] [True if exist. False if does not exist.]
	 */
	private static function instanceConfigFileExists(){
		if(file_exists(INSTANCE_CONFIG_ROOT)){
			log::add(log::DEBUG, "Config file found!");
			return true;
		} else {
			log::add(log::EMERGENCY, "The <strong>instance.".__INSTANCE__.".config</strong> file was not found. The application will not be initialized!");
			return false;
		}
	}

	/**
	 * [modules Configures all the avaiable modules]
	 * @return [bool] [False if instance module root does not exists. True if try to load all modules.]
	 */
	private static function modulesConfig(){
		if(file_exists(CSMC_MODULES_ROOT)){
			//Scans the module root folder and stores in an array.
			$modulesList = scandir(CSMC_MODULES_ROOT);
			//Unsets the values . and .. that are set by default.
			unset($modulesList[0]);
			unset($modulesList[1]);
			//(Cleanup - Reorders the keys of the array)
			$modulesList = array_values($modulesList);
			//Sets the module config for each mod in the modules root.
			foreach ($modulesList as $key => $moduleName){
				//Removes the .php extension part of the filename
				$moduleName = str_replace(".php", "", $moduleName);
				log::add(log::DEBUG, "New module found: ".$moduleName." .");
				//Sets the fully qualiffied classname of the module
				$fqn = "\\csmc\\modules\\".__INSTANCE__."\\".$moduleName;
				//Checks if the obligatory csmc_setup method is set
				if(ios::existsMethod($fqn, "csmc_setup")){
					//Executes the method
					$fqn::csmc_setup();
				} else {
					log::add(log::WARNING, "The module did not have a csmc_setup initialization method.");
				}
			}
			log::add(log::DEBUG, "Modules loaded.");
			return true;
		} else {
			return false;
		}
	}
	/**
	 * [getInstanceInfoDetails Returns an array with the instance info as set in the config file.]
	 * @return [array] [The instance info details (app_name, url, image). Returns an empty array if not set.]
	 */
	public static function getInstanceInfoDetails(){
		if(isset($_SESSION["csmc_native_config"]["info"])){
			return $_SESSION["csmc_native_config"]["info"];
		} else {
			return array();
		}
	}
	/**
	 * [getInstanceHtmlDetails Returns an array with the instance html details as set in the config file.]
	 * @return [array] [[The instance html info details (charset, viewport, generator, base,
	 *                  favicon, user_lang, description, keywords, author, robots, atst, amwac, amawsbs). Returns an empty array if not set.]
	 */
	public static function getInstanceHtmlDetails(){
		if(isset($_SESSION["csmc_native_config"]["html"])){
			return $_SESSION["csmc_native_config"]["html"];
		} else {
			return array();
		}
	}
	/**
	 * [getInstanceDatabaseDetails Returns an array with the instance database connection details as set in the config file.]
	 * @return [array] [The instance database connection details (host, user, password, db).]
	 */
	public static function getInstanceDatabaseDetails(){
		$details = json_decode(misc::getFileContent(INSTANCE_CONFIG_ROOT), true);
		return $details["database"];
	}
	/**
	 * [getInstanceStartupDetails Returns the full qualified classname and method to be executed on startup as well the GET parameters.
     *                            If the user is logged in a different set of parameters is send as set in the config file.]
	 * @return [array] [ the full qualified classname, method and GET parameters if set. (classname, method, GET parameters).]
	 */
	public static function getInstanceStartupDetails(){
		//If the user is logged in a certain homepage is shown
		//else another page is shown, both are set in the config file
		if(isset($_SESSION["csmc_native_config"]["startup"])){
			if(class_exists("csmc\\modules\\".__INSTANCE__."\\login")){
				$class = "csmc\\modules\\".__INSTANCE__."\\login";
				if($class::isLoggedIn()){
					$startup = $_SESSION["csmc_native_config"]["loggedInStartup"];
				} else {
					$startup = $_SESSION["csmc_native_config"]["startup"];
				}
			} else {
				$startup = $_SESSION["csmc_native_config"]["startup"];
			}
			//Checks on the module namespace if the chosen method exists
			if(ios::existsMethod(MODULE_NAMESPACE.$startup["classname"], $startup["method"])){
				return array(MODULE_NAMESPACE.$startup["classname"], $startup["method"], $startup["params"]);
			} else {
				//Checks on the native namespace if the chosen method exists
				if(ios::existsMethod(NATIVE_NAMESPACE.$startup["namespace"]."\\".$startup["classname"], $startup["method"])){
					return array(NATIVE_NAMESPACE.$startup["namespace"]."\\".$startup["classname"], $startup["method"], $startup["params"]);
				} else {
					log::add(LOG::WARNING, "Invalid namespace, classname or method for startup page.");
					return array();
				}
			}
		} else {
			redirects::error(704, "Missing startup index.");
			exit();
		}
	}
	/**
	 * [setTimezone Sets the timezone as configured in the config file.]
	 * @return [bool] [True if timezone set successfully. False if timezone identifier is invalid.]
	 */
	public static function setInstanceTimezoneDetails(){
		//Gets the timezone details from the config file
		$timezone = self::getInstanceTimezoneDetails();
		//Sets the timezone
		if(date_default_timezone_set($timezone)){
			log::add(log::DEBUG, "Timezone set to: ".$timezone);
			return true;
		} else {
			log::add(log::WARNING, "The timezone ".$timezone." is not a valid timezone identifier.");
			return true;
		}
	}
	/**
	 * [getInstanceTimezoneDetails Returns the timezone as set in the config file.]
	 * @return [string] [The timezone. Returns a default gmt +00:00 timezone string if not set.]
	 */
	public static function getInstanceTimezoneDetails(){
		if(isset($_SESSION["csmc_native_config"]["timezone"])){
			if(!empty($_SESSION["csmc_native_config"]["timezone"])){
				return $_SESSION["csmc_native_config"]["timezone"];
			} else {
				return "Europe/Lisbon";
			}
		} else {
			//Default Europe/Lisbon time
			return "Europe/Lisbon";
		}
	}
}

?>