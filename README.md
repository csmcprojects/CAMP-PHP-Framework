# CAMP-PHP-Framework

A simple and easy to configure modular PHP framework.

#Concept

This framework was created with educational purposes but it can be used to develop applications and websites for production. There might be, for now, some types of website for which this framework might not be the best option (See the NOT AVAIABLE YET section bellow). Learn about the framework mechanics in the wiki and find out if it fits your project needs.

This framework was developed to be an easy way to create any kind of websites or applicationsuser interfaces for some software and hardware projects that require one, such as C, C++ or arduino that have no easy way of creating a modern and easy to set up interface.

The best part is that this framework is modular and the only thing you have to do is create your own php modules following the module creation specification (a model file comes with the project "csmc\modules\module_module.php") and from there it's all about letting your imagination flow. Communicate using sockets (tcp, http, websockets,...), mysql database or even serial communication! <3

#To go deep
To know a lot more attend to the wiki section to know about the framework mechanics and reference manuals.

This is where having an open source distribution is great and if you want to commit something just let me know.

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
First you MUST define the csmc core root accordingly to where the csmc folder is in relation to this file, so if the folder is in the previous path then the it MUST be defined as

```php 
	define("CSMC_CORE_ROOT", "../csmc")
``` 

so that the framework can find the folder without a problem. There SHOULD NOT be any need to change any additional paths on this or other framework file.

The only thing left to be done in this file is to define the name of the instance and to set the development environment accordingly to the status of your project.

##3 - Setup the config file

###3.1 - Naming the config file

Next, go to the csmc folder and copy the "instance.csmc.php" file and change the name so that if you defined the instance name as 

```php
	define("__INSTANCE__", "duck");
```
then the config file must be called "instance.duck.config".

###3.2 - Configuration

The config file

```json

info{
    "info": {
        "app_name": "WebApp",
        "url": "",
        "image": "/static/app.image.jpg",
		"webmaster_email": ""
    },
    "html": {
		"charset": "utf-8",
		"viewport": "width=device-width, initial-scale=1.0, user-scalable=no",
		"generator": "",
		"base": "/index.php",
		"favicon": "favicon.ico",
        "user_lang": "en",
        "description": "This is a web app.",
        "atst": "WebApp",
        "amwac": "yes",
		"mwac": "yes",
        "amawsbs": "black-translucent",
		"cti192": "",
        "ati": ""
    },
    "database": {
		"omysqli": {
			"host": "",
			"user": "",
			"pass": "",
			"db": ""
		}
    },
    "salt": "da$gR&/3$|!naerBr$%!#%gs#%RG",
    "startup" : {
		"namespace": "",
        "classname": "",
        "method": ""
	},
    "timezone": "Europe/Lisbon"
}

```

####3.2.1 - info - General information

1. app_name - The name of the application or website.
2. url - The base url of the application or website.
3. image - An image to be assigned to the og:image meta tag.
4. webmaster_email - The webmaster email.

####3.2.2 - html - Meta-tag information

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

####3.2.3 - database (mysql) - Database access

1. host - The url of the database host.
2. user - The username of the database.
3. pass - The password of the database.
4. db - The name of the database.
5. 

####3.2.4 - salt - Security

1. salt - A unique key for the application to be used by the framework.

####3.2.5 - startup - Landing page

1. namespace - The name of the namespace class that contains the method to be executed as the landing page.
2. classname - The name of the class that contains the method.
3. method - The name of the method to be executed.

####3.2.5 - timezone

1. timezone - The timezone.

###3.3 - Launching

By now you SHOULD be able to see a functional webpage showing up with a greenish top bar with the name of you application or website as you configured.

###3.4 - More

To learn more about the framework mechanics, how to create modules for a website or application and more attend to the wiki in this repository.

(C) Carlos Campos - Last updated (27-12-2015)
