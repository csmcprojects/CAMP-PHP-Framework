<?php

//The namespace of this particular module.
//A module can have many classes the namespace is the same for them all.
namespace csmc\modules\logbook;

use csmc\native\framework\ios as ios;
use csmc\native\uinterface\nav as nav;
use csmc\native\db\omysqli as omysqli;
use csmc\native\interfaces\csmcModule as csmcModule;

//The class that holds the methods for this module.
//IMPORTANT: The module filename must be the same as the classname.
class pages implements csmcModule{

	//The functions that are public to users and can be called as a url request.
	//!Dont forget to use the ios::out() method to send out the data, page...
	//In this case myfunc can be accessed by url call but not myOtherFunc.
	public static $func_whitelist = array('home', 'sensor');

	//This is called when the application is initializing a session.
	public static function csmc_setup(){
		nav::setButton("logbook_pages_home", "Home");
        nav::setButton("logbook_pages_home", "Create LogBook");
        nav::setButton("logbook_pages_home", "Open LogBook");
        nav::setButton("logbook_pages_sensor", "Create sensor watch");
        nav::setButton("logbook_pages_home", "Upload data");
        nav::setButton("logbook_pages_home", "Share this LogBook");
	}

	//And this is a valid method as well
	public static function home(){
		$wrap ='<h1>Welcome</h1><p>This website is under construction...</p><form action="demo_form.asp">
                <input type="file" name="pic" accept="image/*">
                <input type="submit">
                </form>';
		return ios::out($wrap);
	}
    public static function sensor(){
		$wrap ='Heat sensor! -> Register to book -> timeline graphic + number register';
		return ios::out($wrap);
	}
	public static function test(){
        ios::out("");
	}
}

?>