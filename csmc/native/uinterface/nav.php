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

use csmc\native\debug\log as log;

class nav{

	/**
	 * [getNavStatus Gets the nav status from $_COOKIE["csmc_native_nav_status"]. If not set then it sets the status to show.]
	 * @return [type] [description]
	 */
	public static function getStatus(){
		//Sets the nav status
		if(isset($_COOKIE["csmc_native_nav_status"])){
			return $_COOKIE["csmc_native_nav_status"];
		} else {
			self::setStatus("show");
		}
	}
	/**
	 * [setStatus Sets the nav status.]
	 * @param [string] $status [The status that can be one of two options (show or hidden)]
	 */
	public static function setStatus($status){
		if($status == "show" || $status == "hidden"){
			$_COOKIE["csmc_native_nav_status"] = $status;
		} else {
			return false;
		}
	}
	/**
	 * [getContent Gets the nav content from the $_SESSION["csmc_native_uinterface_nav_content"] array ]
	 * @return [string] [The nav content.]
	 */
	public static function getContent(){
		if(isset($_SESSION["csmc_native_uinterface_nav_content"])){
			return $_SESSION["csmc_native_uinterface_nav_content"];
		} else {
			return "";
		}
	}
	public static function reboot(){
		if(isset($_SESSION["csmc_native_uinterface_nav_content"])){
			$_SESSION["csmc_native_uinterface_nav_content"] = "";
			log::add(LOG::DEBUG, "Navigation bar cleared.");
		}
	}
	/**
	 * [setContent Appends new content to the $_SESSION["csmc_native_uinterface_nav_content"] array.]
	 * @param [string] $content [The content to be added.]
	 */
	protected static function setContent($content){
		if(!isset($_SESSION["csmc_native_uinterface_nav_content"])){
			$_SESSION["csmc_native_uinterface_nav_content"] = "";
		}
		$_SESSION["csmc_native_uinterface_nav_content"] .= $content;
	}
	/**
	 * [button The ncm-button template]
	 * @param  [string] $id   [A ncm id compatible with the ajax request protocol.]
	 * @param  [string] $name [The name to be displayed by the button.]
	 * @return [string]       [The formated button template.]
	 */
	protected static function button($id, $name){
		return '<button id="'.$id.'" class="nav_option_button">'.$name.'</button>';
	}
	/**
	 * [link The link-button template]
	 * @param  [string] $url   	[The url to go to.]
	 * @param  [string] $target [The target attribute of the tag <a>. Ex.: _blank.]
	 * @param  [string] $name 	[The name to be displayed by the link-button.]
	 * @return [string]       	[The formated link-button template.]
	 */
	protected static function link($action, $target, $name){
		return '<button><a href="'.$url.'" target="'.$target.'"><span>'.$name.'</span></a></button>';
	}
	/**
	 * [setBlock Adds a new block to the navigation bar.]
	 * @param [string] $id      [The block id.]
	 * @param [string] $content [The content of the block.]
	 */
	public static function setBlock($content){
		self::setContent($content);
	}
	/**
	 * [setButton Adds a ncm-button to the navigation bar. ]
	 * @param [string] $id   	[The ncm id.]
	 * @param [type] $name 		[The name to be displayed by the button.]
	 */
	public static function setButton($id, $name){
		self::setContent(self::button($id, $name));
	}
	/**
	 * [getButton Returns a formatted ncm-button template.]
	 * @param  [type] $id   [description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public static function getButton($id, $name){
		return(self::button($id, $name));
	}
	/**
	 * [setLink Adds a link-button to the navigation bar.]
	 * @param [string] $url    	[The url to go to.]
	 * @param [string] $target 	[The target attribute of the tag <a>. Ex.: _blank.]
	 * @param [string] $name 	[The name to be displayed by the button.]
	 */
	public static function setLink($url, $target, $name){
		self::setContent(self::link($url, $target, $name));
	}
	/**
	 * [setLink Returns a formatted link-button.]
	 * @param [string] $url    	[The url to go to.]
	 * @param [string] $target 	[The target attribute of the tag <a>. Ex.: _blank.]
	 * @param [string] $name 	[The name to be displayed by the button.]
	 */
	public static function getLink($url, $target, $name){
		return(self::link($url, $target, $name));
	}
}

?>