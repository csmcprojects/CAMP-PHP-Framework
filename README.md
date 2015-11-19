# CSMC-PHP-Framework
A simple and easy to configure modular PHP framework.

#How to use

##1 - Moving the files (csmc folder and csmc_front folder)
The csmc folder MUST stay in a not public zone of the server and the
csmc_front folder content MUST be on the public zone of the server.

##2 - Open the index.php file in the csmc_front folder
In there you will find the following code:

```php
<?php
	define("__INSTANCE__", "csmc"); //the name of the config file that will be called instance.thisinstancename.config
	define("__DEV_ENVIRONMENT__", 1); //0 (production mode) and 1 (development mode)

	//Defines global variables used by the framework
	define("CSMC_CORE_ROOT", "../csmc");
	define("CSMC_NATIVE_ROOT", CSMC_CORE_ROOT."/native/");
	define("CSMC_MODULES_ROOT", CSMC_CORE_ROOT."/modules/".__INSTANCE__."/");
	define("INSTANCE_CONFIG_ROOT", CSMC_CORE_ROOT."/instance.".__INSTANCE__.".config");
	define("AUTOLOAD_ROOT", CSMC_CORE_ROOT."/autoload.php");
	define("NATIVE_NAMESPACE", 'csmc\\native\\');
	define("MODULE_NAMESPACE", 'csmc\\modules\\'.__INSTANCE__.'\\');

	//Requires the framework initiator file.
	require_once(CSMC_CORE_ROOT."/native/csmc.php");
	//Starts the framework
	new csmc\native\csmc();
?>
```

The only thing you need to do in here is to set the name of the instance that this domain will be running and that must also be the name of your config file.
Set the development environment accordingly to the status of your project.

##3 - Setup the config file

###3.1 - Naming the config file
Go to the csmc folder and copy the "instance.csmc.php" file and change the name with account to the name of the instance you set earlier. So, if you name the instance duck the config file must be called "instance.duck.config".

###3.2 - Configuration
####3.2.1 - info

1. app_name - The name of the application or website.
2. url - The base url of the application or website.
3. image - An image to be assigned to the og:image meta tag.
4. webmaster_email - The webmaster email.

####3.2.2 - html

1. charset - The charset of the application or website.
2. viewport - The viewport configuration.
3. generator - The generator of the content.
4. base - The base url.
5. favicon - The favicon of the application or website. 
6. user_lang - The website language.
7. description - A description of the application or website content.
8. atst - The apple mobile web app title tag.
9. amwac - The apple mobile web app capable tag.
10. mwac - The mobile web app capable tag.
11. amawsbs - The apple mobile web app status bar tag.
12. cti192 - The logo of the application 192x192 for some phones.
13. ati - The logo of the application for an apple homescreen touch icon.

####3.2.3 - database (mysql)

1. host - The url of the database host.
2. user - The username of the database.
3. pass - The password of the database.
4. db - The name of the database.
5. 

####3.2.4 - salt

1. salt - A unique key for the application to be used by the framework.

####3.2.5 - startup

1. namespace - The name of the namespace class that contains the method to be executed as the landing page.
2. classname - The name of the class that contains the method.
3. method - The name of the method to be executed.

####3.2.5 - timezone

1. timezone - The timezone.


