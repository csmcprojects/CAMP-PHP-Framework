<?php

//The namespace of this particular module.
//A module can have many classes the namespace is the same for them all.
namespace csmc\modules\webapp;

use csmc\native\framework\ios as ios;

//The class that holds the methods for this module.
//IMPORTANT: The module filename must be the same as the classname.
class model{

	//The functions that are public to users and can be called as a url request.
	//!Dont forget to use the ios::out() method to send out the data, page...
	//In this case myfunc can be accessed by url call but not myOtherFunc.
	public static $func_whitelist = array('myfunc');

	//This is called when the application is initializing a session.
	public static function csmc_etup(){
		//Example show certain menu options if it is logged in
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