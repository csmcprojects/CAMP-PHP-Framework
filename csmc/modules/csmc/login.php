<?php

/*
 * This file is a module to be used on the CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\modules\csmc; /*PUT THE NAME OF THE INSTANCE YOU ARE USING HERE*/

use csmc\native\uinterface\nav as nav;
use csmc\native\framework\ios as ios;
use csmc\native\framework\config as config;
use csmc\native\db\omysqli as omysqli;
use csmc\native\framework\redirects as redirects;
use csmc\native\framework\json as json;
use csmc\native\oauth\session as session;

class login{
	//The functions that are public to users and can be called as a url request
	public static $func_whitelist = array('login', 'logout', 'register', 'vregister', 'validate', 'profile', 'changepassword', 'changeemail', 'deleteaccount', 'deleteaccountconfirm');

	public static function csmc_setup(){
		//Add database creation
		//Add parameters to config file
		if(!self::isLoggedIn()){			
			nav::setButton("csmc_login_login", "Login");
			nav::setButton("csmc_login_register", "Registar");
		} else {
			nav::setButton("csmc_login_logout", "Logout");
			nav::setButton("csmc_login_profile", "Profile");
		}	
	}

	/**
	 * vregister - Validates an account register form
	 * @return void
	 */
	public static function vregister(){
		//Requires user to be logged out
		if(login::notLogin()){return;}
		//Checks if Email, Passwd and ConfirmPasswd post parameters are set
		if(isset($_SESSION["post_params"]["Email"]) && isset($_SESSION["post_params"]["Passwd"]) && isset($_SESSION["post_params"]["ConfirmPasswd"])){
			//Declares variables for easy use
			$Email = $_SESSION["post_params"]["Email"];
			$Passwd = $_SESSION["post_params"]["Passwd"];
			$ConfirmPasswd = $_SESSION["post_params"]["ConfirmPasswd"];
			//Validation - All fields must be filled
			if(empty($Email) || empty($Passwd) || empty($ConfirmPasswd)){
				ios::out("", "All fields must be filled.");
			} else {
				//Validation - Checks if the email is a valid email
				if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
					//Creates new omysqli object
					$db = new omysqli();
					//Checks if connection was successful
					if(!$db->errorFlag){
						//Sanitize - Sanitizes the email address although already checked with filter_var !You never know...!
						$Email = $db->sanitize($Email);
						//Validation - Checks if email is unique
						if($db->countExecute("SELECT COUNT(id) FROM users WHERE email='".$Email."'") == 0){
							//Validation - Checks if Passwd and NewPasswd are the same
							if($Passwd == $ConfirmPasswd){
								//Inserts the new user into the database
								if($db->boolExecute("INSERT INTO users (email,password) VALUES ('".$Email."','".md5($Passwd)."')")){
									redirects::redirectHome("", "You have registered successfully.");
								} else {
									ios::out("", "It was not possible to register a new account.");
								}
							} else {
								ios::out("", "Passwords don't match.");
							}
						} else {
							ios::out("", "Email is already in use.");
						}
					}
				} else {
					ios::out("", "Invalid email.");
				}
			}
		} else {
			ios::out("", "Missing parameters.");
		}
	}
	/**
	 * [register The register form]
	 * @return void
	 */
	public static function register(){
		//Requires user to be logged out
		if(login::notLogin()){return;}
		//HTML
		$wrap = '
		<section id="login_box">
			<h1>Register</h1><br>
			<div class="login_logo">
				<img itemprop="image" class="logo" width="150" height="100" src="'.config::getInstanceInfoDetails()["image"].'" alt="Logo">
			</div>
			<br>
			<form id="register">
				<input id="Email" name="Email" maxlength="100" type="text" placeholder="Email" autocomplete="on" spellcheck="off" required/>
				<input id="Passwd" name="Passwd" type="password" maxlength="50" placeholder="Password" required/>
				<input id="ConfirmPasswd" name="Passwd" type="password" maxlength="50" placeholder="Confirm Password" required/>
				<input type="submit" value="Register" id="'.__INSTANCE__.'_login_vregister!Email&Passwd&ConfirmPasswd" class="desktop_option_button"/>
			</form>
		</section>
		';
		ios::out($wrap);
	}
	/**
	 * [deleteaccountconfirm Deletes an account from the database]
	 * @return void
	 */
	public static function deleteaccountconfirm(){
		//Require user to be logged in
		if(login::requireLogin()){return;}
		//New omysqli object
		$db = new omysqli();
		//Checks if db connection failed
		if(!$db->errorFlag){
			//Deletes the user from the database
			if($db->boolExecute("DELETE FROM users WHERE email = '".$_SESSION["csmc_modules_login_email"]."'")){
				//Forces redirect to homepage, now logged out
				redirects::link("#!/".__INSTANCE__."/login/logout", "Account deleted, bye!");
			} else {
				ios::out("", "It was not possible to delete the account right now...");
			}
		} else {
			ios::out("", "It was not possible to delete the account right now.");
		}
	}
	/**
	 * [deleteaccount Sends a javascript confirm pop up to delete the user account]
	 * @return void
	 */
	public static function deleteaccount(){
		//Require user to be logged in
		if(login::requireLogin()){return;}
		//Sends a javascript script that asks for confirmation to delete the account
		ios::out("","", "if(confirm('Are you sure you want to delete the account?')){location.hash='#!/".__INSTANCE__."/login/deleteaccountconfirm';}else{}");
	}
	/**
	 * [changeemail Changes the user email aka username]
	 * @return void
	 */
	public static function changeemail(){
		//Require user to be logged in
		if(login::requireLogin()){return;}
		//Checks if the post parameters are set
		if(isset($_SESSION["post_params"]["NewEmail"]) && isset($_SESSION["post_params"]["ConfirmNewEmail"])){
			//Declare variables for ease of use
			$NewEmail = $_SESSION["post_params"]["NewEmail"];
			$ConfirmNewEmail = $_SESSION["post_params"]["ConfirmNewEmail"];
			//Validation - Checks if NewEmail is not empty
			if(trim($NewEmail) != false){
				//Validation - Checks if ConfirmNewEmail is not empty
				if(trim($ConfirmNewEmail) != false){
					//Validation - Checks if email and email confirmation match.
					if($NewEmail == $ConfirmNewEmail){
						//New omysqli object
						$db = new omysqli();
						//Checks if db connection failed
						if(!$db->errorFlag()){
							//Updates the user email on db
							if($db->boolExecute("UPDATE users SET email='".$NewEmail."' WHERE email='".$_SESSION["csmc_modules_login_email"]."'")){
								ios::out("", "Email updated successfully.");
							} else {
								ios::out("", "It was not possible to update your email...");
							}
						}
					} else {
						ios::out("", "The emails don't match.");
					}
				} else {
					ios::out("", "A email confirmation is needed.");
				}
			} else {
				ios::out("", "Insert a new email.");
			}
		} else {
			ios::out("", "Parameters are missing.");
		}
	}
	/**
	 * [profile Shows the user profile page / account settings]
	 * @return void
	 */
	public static function profile(){
		//Require user to be logged in
		if(login::requireLogin()){return;}
		//New omysqli object
		$db = new omysqli();
		//Checks if db connection failed
		if(!$db->errorFlag){
			//Gets several fields about user information
			$data = $db->dataExecute("SELECT * FROM users WHERE email = '".$_SESSION["csmc_modules_login_email"]."'", "name,last_access_user_agent,last_access_ip,last_access_time");
			$data = $data[0];
			//HTML
			$wrap = '
			<section id="profile_box">
				<h1>Profile</h1>
				<h3><strong>'.$data["name"].'</strong></h3>
				<p><strong>Email:</strong> '.$_SESSION["csmc_modules_login_email"].'</p>
				<br>
				<h1>Change account information</h1>
				<p><strong>Change password</strong></p>
				<form id="changepassword">
					<input id="OldPasswd" name="OldPasswd" type="password" maxlength="50" placeholder="Old Password" required/>
					<input id="NewPasswd" name="NewPasswd" type="password" maxlength="50" placeholder="New Password" required/>
					<input id="ConfirmNewPasswd" name="ConfirmNewPasswd" type="password" maxlength="50" placeholder="Confirm New Password" required/>
					<input type="submit" id="'.__INSTANCE__.'_login_changepassword!OldPasswd&amp;NewPasswd&amp;ConfirmNewPasswd" class="desktop_option_button" value="Change" />
				</form>
				<p><strong>Change email</strong></p>
				<form id="changeemail">
					<input id="NewEmail" name="NewEmail" type="email" maxlength="1024" placeholder="New Email" required/>
					<input id="ConfirmNewEmail" name="ConfirmNewEmail" type="email" maxlength="1024" placeholder="Confirm New Email" required/>
					<input type="submit" value="Change" id="'.__INSTANCE__.'_login_changeemail!NewEmail&amp;ConfirmNewEmail" class="desktop_option_button"/>
				</form>
				<br>
				<h1>Last Access information</h1>
				<p><strong>User Agent</strong>: '.$data["last_access_user_agent"].'</p>
				<p><strong>IP Address</strong>: '.$data["last_access_ip"].'</p>
				<p><strong>Access time</strong>: '.$data["last_access_time"].'</p>
				<br>
				<h1>Delete account</h1>
				<form id="deleteaccount">
					<input type="submit" value="Delete" id="'.__INSTANCE__.'_login_deleteaccount" class="desktop_option_button"/>
				</form>
			</section>
			';
			//Return necessary for startup page
			return ios::out($wrap);
		}
	}
	/**
	 * [changepassword Changes the user password]
	 * @return void
	 */
	public static function changepassword(){
		//Require user to be logged in
		if(login::requireLogin()){return;}
		//Checks if post parameters are set
		if(isset($_SESSION["post_params"]["OldPasswd"]) && isset($_SESSION["post_params"]["NewPasswd"]) && isset($_SESSION["post_params"]["ConfirmNewPasswd"])){
			//Validation - Checks if fields are empty
			if(trim($_SESSION["post_params"]["OldPasswd"]) != false ||
               trim($_SESSION["post_params"]["NewPasswd"]) != false ||
               trim($_SESSION["post_params"]["ConfirmNewPasswd"]) != false){
				ios::out("", "All fields are required.");
				return;
			}
			//New omysqli object
			$db = new omysqli();
			//Checks if db connection failed
			if(!$db->errorFlag){
				//Gets the application salt key
				//Validation - Checks if old password is correct
				$count = $db->countExecute("SELECT COUNT(id) FROM users WHERE email='".$_SESSION["csmc_modules_login_email"]."' AND password='".md5($_SESSION["post_params"]["OldPasswd"])."'");
				if($count == 1){
					//Validation - Check if new password and confirmation match
					if($_SESSION["post_params"]["NewPasswd"] == $_SESSION["post_params"]["ConfirmNewPasswd"]){
						//Update the password
						if($db->boolExecute("UPDATE users SET password='".md5($_SESSION["post_params"]["NewPasswd"])."' WHERE `email`='".$_SESSION["csmc_modules_login_email"]."'")){
							ios::out("", "Password changed successfully.");
						} else {
							ios::out("", "An error happened while trying to make the change. Please try again later!");
						}
					} else {
						ios::out("", "New password confirmation doesn't match.");
					}
				} else {
					ios::out("","Old password is wrong.");
				}
			}
		} else {
			ios::out("", "Missing parameters for changing password.");
		}
	}
	/**
	 * [login Shows the login form]
	 * @return void
	 */
	public static function login(){
		//Require user to be logged out
		if(login::notLogin()){return;}
		//HTML
		$wrap = '
		<section id="login_box">
			<h1>Login</h1><br>
			<div class="login_logo">
				<img itemprop="image" class="logo" width="150" height="100" src="'.config::getInstanceInfoDetails()["image"].'" alt="Logo">
			</div>
			<br>
			<form id="login">
				<input id="Email" name="Email" maxlength="100" type="text" placeholder="Email" autocomplete="on" spellcheck="off" required/>
				<input id="Passwd" name="Passwd" type="password" maxlength="50" placeholder="Password" required/>
				<input type="submit" value="Login" id="'.__INSTANCE__.'_login_validate!Email&amp;Passwd" class="desktop_option_button"/>
			</form>
		</section>';
		return ios::out($wrap);
	}
	/**
	 * [validate Validates a login request]
	 * @return void
	 */
	public static function validate(){
		if(login::notLogin()){return;}
		//Creates the omysqli object
		$db = new omysqli();
		//Checks if db connection failed
		if($db->errorFlag === false){
			//Sanitizes username input
			if(isset($_SESSION["post_params"]["Email"]) && isset($_SESSION["post_params"]["Passwd"])){
				$email = $db->sanitize($_SESSION["post_params"]["Email"]);
				//Gets the application custom salt
				/*$salt = config::getInstanceDatabaseDetails();
				if(!isset($salt["salt"])){redirects::error(708);return;} else {$salt = $salt["salt"];}*/
				//Basic md5 hash of password + salt
				$password = md5($_SESSION["post_params"]["Passwd"]);
				//Finds database match
				$count = $db->countExecute("SELECT COUNT(id) FROM users WHERE email='".$email."' AND password='".$password."'");
				if($count == 1){
					/* SECURITY - LOGIN TIME INTERVAL BLOCKING */
					$current_time = date("Y-m-d H:i:s");
					$current_time = explode(' ', $current_time);
					$current_time_d = explode('-', $current_time[0]);
					$current_time_t = explode(':', $current_time[1]);
					$last_time = $db->dataExecute("SELECT last_failed_login_try FROM users WHERE email='".$email."'", "last_failed_login_try")[0]["last_failed_login_try"];
					$last_time = explode(' ', $last_time);
					$last_time_d = explode('-', $last_time[0]);
					$last_time_t = explode(':', $last_time[1]);
					if($last_time_d[1] == $current_time_d[1] && $last_time_d[2] == $current_time_d[2]){
						if($current_time_t[0] <= $last_time_t[0]){
							if($current_time_t[1] - $last_time_t[1] < 5){
								ios::out("", "Your account is blocked for failing consecutive logins. Try again in ".(5-($current_time_t[1] - $last_time_t[1]))." minutes.");
								return;
							}
						}
					}
					$db->boolExecute("UPDATE users SET last_failed_count=0 WHERE email='".$email."'");
					/**********************************/
					//Sets a global variable to store the user email
					$_SESSION["csmc_modules_login_email"] = $email;
					//Sets config status to false to force framework configuration
					$_SESSION["csmc_native_framework_config_status"] = false;
					//Sets a global variable to store login status
					$_SESSION["csmc_modules_login_status"] = true;
					//Forces redirect to homepage
					redirects::redirectHome("", "Logged In!", 500);
				} else {
					//Security - Increment counter if login failed
					$last_failed_count = $db->dataExecute("SELECT last_failed_count FROM users WHERE email='".$email."'", "last_failed_count")["last_failed_count"];
					if($last_failed_count >= 3){
						$db->boolExecute("UPDATE users SET last_failed_login_try='".date("Y-m-d H:i:s")."' WHERE email='".$email."'");
						ios::out("", "You failed your login three times in a row. Try again in 5 minutes.");
					} else {
						$db->boolExecute("UPDATE users SET last_failed_count = last_failed_count + 1 WHERE email='".$email."'");
						ios::out("", "Invalid credentials");
					}
				}
			} else {
				ios::out("", "Missing parameters");
			}
		} //If false the omysqli class already prompts an error message.
	}
	/**
	 * logout - Logout user
	 * @return void
	 */
	public static function logout(){
		//Requires user to be logged in
		if(login::requireLogin()){return;}
		//New db object
		$db = new omysqli;
		//Checks if a db connection failed
		if(!$db->errorFlag){
			//Security checks - Updates last access time, ip, user agent
			$db->boolExecute("UPDATE users SET last_access_time='".\date('m-d-Y h:i:s a', time())."',
							last_access_ip='".filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)."',
							last_access_user_agent='".$_SERVER["HTTP_USER_AGENT"]."'
							WHERE `email`='".$_SESSION["csmc_modules_login_email"]."'");
		}
		//Sets the login status to false
		$_SESSION["csmc_modules_login_status"] = false;
		//Calls the native reset method to destroy session data and redirect user
		session::reset();
	}
	/**
	 * [isLoggedIn Returns true if the user is logged in]
	 * @return bool
	 */
	public static function isLoggedIn(){
		if(isset($_SESSION["csmc_modules_login_status"])){
			return $_SESSION["csmc_modules_login_status"];
		} else {
			$_SESSION["csmc_modules_login_status"] = false;
		}
	}
	/**
	 * [requireLogin If the user is not logged in then it redirects him to the home page]
	 * @return bool
	 */
	public static function requireLogin(){
		if(!login::isLoggedIn()){
			$message = "<h1>Need Credentials</h1><p>You need to
						be logged in to access this page.</p>";
			$notification = "Redirecting...";
			$time = 2000;
			redirects::redirectHome($message,$notification,$time);
			return true;
		}
		return false;
	}
	/**
	 * [notLogin If the user is logged in then it redirects him to the home page]
	 * @return bool
	 */
	public static function notLogin(){
		if(login::isLoggedIn()){
			$message = "<h1>Noop</h1><p>You should not be here!</p>";
			$notification = "Well, well, well...";
			$time = 1000;
			redirects::redirectHome($message,$notification,$time);
			return true;
		}
		return false;
	}
}

?>