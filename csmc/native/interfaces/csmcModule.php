<?php

//The namespace of this particular module.
//A module can have many classes the namespace is the same for them all.
namespace csmc\native\interfaces;

//The class that holds the methods for this module.
//IMPORTANT: The module filename must be the same as the classname.
interface csmcModule{
	//This is called when the application is initializing a session.
	public static function csmc_setup();
}

?>