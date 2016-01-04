<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\uinterface;

use csmc\native\debug\log as log;
use csmc\native\other\misc as misc;
use csmc\native\uinterface\html as html;
use csmc\native\framework\config as config;
use csmc\native\framework\redirects as redirects;
use csmc\native\framework\ios as ios;

class ui{
	/**
	 * [create Sends headers and displays the html template page with a certain startup content]
	 * @param  string $startup Content to be displayed in the desktop
	 */
	public static function create($startup = ""){
		//Sends html headers
		foreach(html::getHtmlHeaders() as $value){
			header($value);
		}
		//Sets the startup content
		if($startup != ""){
			//Sets the desktop content to what is passed in parameter
			$page = misc::minifyHtml(html::htmlTemplate($startup));
			echo $page;
			return;
		} else {
			//Get the startup method from the configuration file
			$startup = config::getInstanceStartupDetails();
			//Check if it is empty
			if(!empty($startup)){
				$fully_qualified_classname = $startup[0];
				$method = $startup[1];
			} else {
				$fully_qualified_classname = "";
				$method = "";
			}
			if($fully_qualified_classname != "" 								// If the fully qualified class name is not empty
			&& ios::existsMethod($fully_qualified_classname, $method) 			// and the specified class and method exist
																				// in the native or module namespace
			&& isset($fully_qualified_classname::$func_whitelist) 				// and a func_whitelist array is set
			&& in_array($method, $fully_qualified_classname::$func_whitelist))	// and the method is a method name in the func_whitelist array
			{
				$objClass = new $fully_qualified_classname;
				$_SESSION["csmc_native_uinterface_ui_startupcall"] = true;
				if(isset($startup[2])){
					log::add(log::DEBUG, "Loading startup parameters.");
					foreach (explode("/", $startup[2]) as $key => $value) {
						ios::setGetParams($value);
					}
				}
				$page = misc::minifyHtml(html::htmlTemplate($objClass->$method()));
				echo $page;
				log::add(log::NOTICE, "HTML Template loaded.");
				unset($_SESSION["csmc_native_uinterface_ui_startupcall"]);
				return;
			} else {
				//If you get this redirect than you are sure that one of the above conditions failed and
				//should be checked by reading the debug log entries.
				log::add(log::ALERT, "Failed to initialize UI startup page for class ".$fully_qualified_classname." and method ".$method." .");
				redirects::error(404, "Logged in startup page not set in configuration.");
				return;
			}
		}

	}
}
?>