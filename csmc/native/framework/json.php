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

class json{

	const NAV 			= "nav";
	const DESKTOP 		= "desktop";
	const DESKTOP_E		= "desktop_extension";
	const NOTIFICATION 	= "notifications";
	const NONE          = "";

	/*
	* [Sends a formated response to an ajax request.]
	* $response string   	- The content of the response to be sent.
	* $notification string 	- Any notification that might need to be sent.
	* $responseSpawn		- The page location where the response will be shown
	* $args					- A fourth argument that is a javascript script to be executed
	*/
	public static function send($response, $notification, $responseSpawn, $statusHeader = 200){
		$args = func_get_args();
		if(count($args) > 4){
			$script = $args[4];
		} else {
			$script = "";
		}
		header('Content-type: application/json');
		header('Status Code: '.$statusHeader);
		$resp = array("response" => $response, "notifications" => $notification, "spawn" => $responseSpawn, "script" => $script);
		echo json_encode($resp);
	}
}

?>