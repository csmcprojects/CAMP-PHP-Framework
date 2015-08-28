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

use csmc\native\framework\ios as ios;

class redirects{
	/**
	 * error - Returns a http header and a custom error message to certain error codes
	 * @param  [int] - The error code
	 * @param  string - Exception error aditional message
	 * @return [void]
	 */
	public static function error($errorNumber, $exceptionError = ""){
		switch ($errorNumber) {
			case '700':
				//Custom - Internal App Error.
				ios::out("<h1>700 - Internal App Error</h1><p>A critical error happened while trying to launch
							the CSMC Framework. Please read the log for more information.</p><p>".$exceptionError."</p>");
				break;
			case '701':
				//Application instance not set.
				ios::out("<h1>701 - Application Instance not set</h1><p>An application instance must be defined. Go to index.php to set one.");
				break;
			case '702':
				//Environment mode not set
				ios::out("<h1>702 - Environment mode not set</h1><p>An environment mode (development or production) must be defined. Go to index.php to set one.");
				break;
			case '703':
				//Config file missing
				ios::out("<h1>703 - Config File Missing</h1><p>There is no config file for instance ".__INSTANCE__." .");
				break;
			case '704':
				//Invalid config file
				ios::out("<h1>704 - Invalid Config File</h1><p>There is an error in the config file. Use an online JSON validator, such as JSONLint or others, to quickly find the problem.</p><p>".$exceptionError."</p>");
				break;
			case '705':
				//Modules folder missing
				ios::out("<h1>705 - Modules Folder Missing</h1><p>It is required the folder csmc\modules\\".__INSTANCE__." to load up any existing modules.</p>");
				break;
			case '706':
				//Unknown environment state
				ios::out("<h1>706 - Unknown environment state</h1><p>Define the __DEV_ENVIRONMENT__ variable in the index.php file either to 0 (production mode) or 1 (development mode).");
				break;
			case '707':
				//Database connection fail
				ios::out("<h1>707 - Failed to connect to database</h1><p>Failed to connect to database using the given configurations. ".$exceptionError.". Please check the instance config file.</p>");
				break;
			case '708':
				//Salt missing
				ios::out("<h1>708 - Salt Missing</h1><p>I think something is missing in this application and I think that it is salt. Please check the configuration file.");
				break;
			case '404':
				//Page not found.
				ios::out("<h1>404 - Page Not Found</h1><p>I don't know what you were doing here but I'm sure you are not suppose to be here...</p><p>".$exceptionError."</p>", "", "", 404);
				break;
			case '401':
				//Bad request
				ios::out("<h1>401 - Bad Request</h1><p>I'm almost sure that the url should not be like that...</p>", "", "", 401);
				break;
			default   :
				//Unknown Error
				ios::out("<h1>Unknown Error Code</h1><p>Something happened don't ask me what. I know nothing...</p>");
				break;
		}
	}
	/**
	 * home - Redirects the client to the base url
	 * @param  string
	 * @param  string
	 * @param  integer
	 * @return [void]
	 */
	public static function home($message = "", $notification = "", $time = 1000){
		if(strpos($_SERVER['REQUEST_URI'], "?") != 0){
			$str = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?"));
		} else {
			$str = $_SERVER['REQUEST_URI'];
		}
		if(strpos($str, "#") != 0){
			$str = substr($str, 0, strpos($str, "#"));
		}
		if(isset($_POST["ajax"])){
			ios::out($message, $notification, "setTimeout(function(){window.location.href=\"".$str."\";}, ".$time.")");
		} else {
			header("Location: ".$str."");
		}
		return;
	}
	/**
	 * [link Redirects the user to a certain link]
	 * @param  string  $link         [description]
	 * @param  string  $message      [description]
	 * @param  string  $notification [description]
	 * @param  integer $time         [description]
	 */
	public static function link($link, $message = "", $notification = "", $time = 1){
		if(isset($_POST["ajax"])){
			ios::out($message, $notification, "setTimeout(function(){window.location.href=\"".$link."\";}, ".$time.")");
		} else {
			header("Location: ".$link."");
		}
		return;
	}
}
?>