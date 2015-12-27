<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\oauth;

use csmc\native\debug\log as log;
use csmc\native\framework\json as json;
use csmc\native\framework\redirects as redirects;


class session
{
	public static $func_whitelist = array("reset");

	/**
	 * Initializes the session and places the locks.
	 */
	public function __construct(){
		if (session_status() !== PHP_SESSION_ACTIVE){
			//Starts the session
			if(session_start()){
				log::add(log::DEBUG, "Session started.");
			} else {
				log::add(log::EMERGENCY, "An error occurred while trying to start the session.");
				return false;
			}
			//Creates security locks that link the session_id to the user that created it
			//by creating keys based on the user ip and user agent
			if($this->checkLocks(session_id())){
				log::add(log::DEBUG, "Session set.");
				return true;
			} else {
				log::add(log::DEBUG, "Setting up locks.");
				$this->setLocks(session_id());
				log::add(log::DEBUG, "Session set.");
				return true;
			}
		}
	}
	/**
	 * [locks Sets a combination of 2 keys made from the client user agent and remote address.]
	 * @param  [string] $SID [Client session id]
	 * @return [boolean]      [Check status]
	*/
	private function checkLocks($SID){
		/**
		 * If all locks are set, checks if the keys match the locks, else session is destroyed.
		 */
		if(!empty($_SESSION["csmc_native_session"]["locks"][1]) && !empty($_SESSION["csmc_native_session"]["locks"][2])){
			$lock1 = $_SESSION["csmc_native_session"]["locks"][1];
			$key1  = md5($_SERVER["HTTP_USER_AGENT"].$SID);
			$lock2 = $_SESSION["csmc_native_session"]["locks"][2];
			$key2  = md5($_SERVER["REMOTE_ADDR"].$SID);
			if($lock1 == $key1  && $lock2 == $key2){
				log::add(log::DEBUG, "The locks and keys match.");
				return true;
			} else {
				log::add(log::DEBUG, "The locks and keys don't match. Destroying session.");
				self::destroy();
			}
		}
		/**
		 * If only one lock is set something is not right, session is destroyed.
		 */
		elseif(empty($_SESSION["csmc_native_session"]["locks"][1]) && !empty($_SESSION["csmc_native_session"]["locks"][2]) || !empty($_SESSION["csmc_native_session"]["locks"][1]) && empty($_SESSION["csmc_native_session"]["locks"][2])){
			log::add(log::DEBUG, "Not all locks are set. Destroying session");
			return false;
		} else { // If no lock is set then return false and create session locks.
			log::add(log::DEBUG, "No lock or key is set.");
			return false;
		}
	}
	/**
	 * [setLocks Sets the session locks if not set.]
	 * @param [string] $SID [Client session id.]
	 */
	private function setLocks($SID){
		/**
		 * If lock1 is set then set lock2, else set lock1.
		 */
		if(!empty($_SESSION["csmc_native_session"]["locks"][1])){
			/**
			 * If lock2 is also set then return true, else set lock2.
			 */
			if(!empty($_SESSION["csmc_native_session"]["locks"][2])){
				log::add(log::DEBUG, "All locks are set.");
				return true;
			} else {
				$_SESSION["csmc_native_session"]["locks"][2] = md5($_SERVER["REMOTE_ADDR"].$SID);
				log::add(log::DEBUG, "Lock2 was set.");
				$this->setLocks($SID);
			}
		} else {
			$_SESSION["csmc_native_session"]["locks"][1] = md5($_SERVER["HTTP_USER_AGENT"].$SID);
			log::add(log::DEBUG, "Lock1 was set.");
			$this->setLocks($SID);
		}
	}
	/**
	 * [destroy Destroys the session by cleaning all variables.]
	 * @return [void]
	 */
	public static function reset(){
		log::add(log::DEBUG, "Session destroyed!");
		//Cleans all session variables
		$_SESSION = array();
		//Destroys session cookies
		if(isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), '', time()-30000, '/');
		}
		//Destroys the session
		session_destroy();
		//Returns to homepage
		if(isset($_GET["exit"])){
			redirects::home();
		} else {
			redirects::home("", "Logout successfull", 1500);
		}
	}
}
?>