<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\uinterface;

use csmc\native\framework\config as config;

class html{

	/**
     * [[Returns a html template]]
     * @returns [[string]] [[Returns the string that contains all the html]]
     */
    public static function htmlTemplate($startup){
		//Gets the head information from the configuration file
		$configHtml = config::getInstanceHtmlDetails();
		$configInfo = config::getInstanceInfoDetails();
		$navStatus = nav::getStatus();
		$navContent = nav::getContent();

		//Sets the configuration, if the $configHtml comes empty it falls back to
		//a default template
		if(!empty($configHtml) && !empty($configInfo)){
			$wrap = '<!DOCTYPE html>
			<html lang="'.$configHtml["user_lang"].'">
				<head>
					<!-- General Meta -->
					<title>'.$configInfo["app_name"].'</title>
					<meta charset="'.$configHtml["charset"].'">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="'.$configHtml["viewport"].'">
					<meta name="description" content="'.$configHtml["description"].'">
					<meta name="fragment" content="!">
					<!-- Open Graph Tags -->
					<meta property="og:type" content="website">
					<meta property="og:title" content="'.$configInfo["app_name"].'">
					<meta property="og:description" content="'.$configHtml["description"].'">
					<meta property="og:url" content="'.$configInfo["url"].'">
					<meta property="og:site_name" content="'.$configInfo["app_name"].'">
					<meta property="og:image" content="'.$configInfo["image"].'">
					<!-- Mobile & Fav Icons -->
					<link rel="shortcut icon" type="image/x-icon" href="static/'.$configHtml["favicon"].'">
					<!-- Add to homescreen for Chrome on Android -->
					<meta name="mobile-web-app-capable" content="'.$configHtml["mwac"].'">
					<meta name="application-name" content="'.$configInfo["app_name"].'">
					<link rel="icon" sizes="192x192" href="'.$configHtml["cti192"].'">
					<!-- Add to homescreen for Safari on iOS -->
					<meta name="apple-mobile-web-app-title" content="'.$configInfo["app_name"].'">
					<meta name="apple-mobile-web-app-capable" content="'.$configHtml["amwac"].'">
					<meta name="apple-mobile-web-app-status-bar-style" content="'.$configHtml["amawsbs"].'">
					<link rel="apple-touch-icon" href="'.$configHtml["ati"].'">
					<!-- Other Tags <meta name="fragment" content="!"> -->
					<!-- Stylesheets -->
					<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
					<link href="css/csmc.css" rel="stylesheet" type="text/css">
					<!-- Scripts -->
					<script src="js/jquery.js"></script>
					<script src="js/csmc.js"></script>
				</head>
				<body>
					<header id="header">
						<a href="'.$configInfo["url"].'" hreflang="'.$configHtml["user_lang"].'" rel="bookmark" target="_self">
							<h1 class="logo">'.$configInfo["app_name"].'</h1>
						</a>
					</header>
					<nav class="'.$navStatus.'">'.$navContent.'</nav>
					<section id="desktop_extension" class="">
						<span class="nob">&#9776;</span>
						<span id="loading_bar"></span>
						<section id="notifications"></section>
					</section>
					<section id="desktop">'.$startup.'</section>
					<footer></footer>
				</body>
			</html>
			';
		} else {
			$wrap = '<!DOCTYPE html>
			<html lang="en">
				<head>
					<!-- General Meta -->
					<title>CSMC Framework</title>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<meta name="fragment" content="!">
					<!-- Stylesheets -->
					<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
					<link href="css/csmc.css" rel="stylesheet" type="text/css">
					<!-- Scripts -->
					<script src="js/jquery.js"></script>
					<script src="js/csmc.js"></script>
				</head>
				<body>
					<header id="header">
						<a href="?exit" target="_self">
							<h1 class="logo">CSMC Framework</h1>
						</a>
					</header>
					<nav class="'.$navStatus.'">'.$navContent.'</nav>
					<section id="desktop_extension" class="">
						<span class="nob">&#9776;</span>
						<span id="loading_bar"></span>
						<section id="notifications"></section>
					</section>
					<section id="desktop">'.$startup.'</section>
					<footer></footer>
				</body>
			</html>';
		}
		return $wrap;
	}

	/**
	 * [setHtmlHeaders Sets an html header to be sent to the client when it loads the ui. THIS IS USEFULL FOR MODULES AND
	 *  SHOULD BE SET IN THE CSMC_SETUP METHOD.]
	 * @param [string] $string [The header string.]
	 */
	public static function setHtmlHeaders($string){
		if(!isset($_SESSION["csmc_native_uinterface_html_headers"])){
			$_SESSION["csmc_native_uinterface_html_headers"] = array();
		}
		$_SESSION["csmc_native_uinterface_html_headers"][] = $string;
	}
	/**
	 * [getHtmlHeaders Gets the html headers to be sent to the client when it loads the ui.]
	 * @return [array] [An array of headers strings.]
	 */
	public static function getHtmlHeaders(){
		if(isset($_SESSION["csmc_native_uinterface_html_headers"])){
			return $_SESSION["csmc_native_uinterface_html_headers"];
		} else {
			return array();
		}
	}
}
?>