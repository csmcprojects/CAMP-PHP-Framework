<?php

//The namespace of this particular module.
//A module can have many classes the namespace is the same for them all.
namespace csmc\modules\MyModuleName;

use csmc\native\oauth\login as login;
use csmc\native\uinterface\nav as nav;
use csmc\native\framework\ios as ios;

//The class that holds the methods for this module.
//IMPORTANT: The module filename must be the same as the classname.
class MyModuleName{
	
	//The functions that are public to users and can be called as a url request.
	//!Dont forget to use the ios::out() method to send out the data, page...
	//In this case myfunc can be accessed by url call but not myOtherFunc.
	public static $func_whitelist = array('myfunc');
	
	//This is called when the application is initializing a session.
	public static function csmc_setup(){
		//Example show certain menu options if it is logged in
		if(login::isLoggedIn()){		
			// nav::setButton("namespace_class_method", "Go Somewhere secret");
		} else {
			// nav::setButton("oauth_login_login", "Login");
		}
		//End of example
	}
	
	//Not required, use as intended
	public function __construct(){
		
	}
	
	//This is a valid method
	public function myfunc(){
		
	}
	//And this is a valid method as well
	public static function myOtherFunc(){
	
	}
}

?>