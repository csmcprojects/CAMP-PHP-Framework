<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\debug;

use csmc\native\framework\ios as ios;
use csmc\native\framework\config as config;

class log{

	public static $func_whitelist = array("show");

	const EMERGENCY = "emergency";		// System is unusable.
    const ALERT     = "alert";			// Action must be taken immediately.Example: Entire website down, database unavailable, etc. 
										// This should trigger the SMS alerts and wake you up.
    const CRITICAL  = "critical";		// Critical conditions. Example: Application component unavailable, unexpected exception.
    const ERROR     = "error";			// Runtime errors that do not require immediate action but should typically be logged and monitored.
    const WARNING   = "warning";		// Exceptional occurrences that are not errors.
    const NOTICE    = "notice";			// Normal but significant events.
    const INFO      = "info"; 			// Interesting events. Example: User logs in, SQL logs.
	const DEBUG		= "debug"; 			// Detailed debug information.

	/**
	 * [Adds a new message to the log]
	 * @param [string] $level [One of the log constants types]
	 * @param [string] $log   [The log message]
	 */
	public static function add($level, $log){
		if(__DEV_ENVIRONMENT__ == "0") return;
		// csmc_native_log session var, stores the array of log strings
		// if it is not set, then it is set as an empty array
		if(!isset($_SESSION["csmc_native_log"])){$_SESSION["csmc_native_log"] = array();}
		if(!isset($_GET["log"])){
			if(count($_SESSION["csmc_native_log"]) > 250){
				$_SESSION["csmc_native_log"] = array();
			}
			$_SESSION["csmc_native_log"][] = self::color($level, self::format($level, $log));
		}
	}
    /**
     * [Formats the log string]
     * @param string $level [One of the log constants types]
	 * @param string $log   [The log message]
     * @returns String   [Description]
     */
    private static function format($level, $log){
    	//Sets the timezone
		@date_default_timezone_set(config::getInstanceTimezoneDetails());
        //Format as you wish
        return  "{".$level."} (".date("Y-m-d H:i:s").")".$log;
    }
	/**
	 * [Sets the color of the log message]
	 * @param [string] $level [One of the log constants types]
	 * @param [string] $log   [The formated log message]
	 * @returns String   [Description]
	 */
	private static function color($level, $formated_log){
        if($level == self::EMERGENCY || $level == self::ALERT || $level == self::CRITICAL || $level == self::ERROR){
			return '<p style="color:red">'.$formated_log.'</p>';
		} else if($level == self::WARNING){
			return '<p style="color:purple">'.$formated_log.'</p>';
		} else if($level == self::NOTICE || $level == self::INFO){
			return '<p style="color:green">'.$formated_log.'</p>';
		} else if($level == self::DEBUG){
			return '<p style="color:orange">'.$formated_log.'</p>';
		}
	}
	/**
	 * [Shows the log]
	 */
	public static function show(){
		//In the future may require login and admin permitions...
		$logString = "<h1>Log<h1><pre>";
		if(isset($_SESSION["csmc_native_log"])){
			for($i = 0; $i < count($_SESSION["csmc_native_log"]); $i++){
				$logString .= $_SESSION["csmc_native_log"][$i]."<br>";
			}
			$logString .= "</pre>";
			ios::out($logString, "Log loaded.");
		} else {
			ios::out("<h1>Log<h1><p>Log is empty...</p>");
		}
	}
}