<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace csmc\native\other;

/**
 * This class stores miscellaneous functions that don't fit any particular place.
 */
class misc{
	/**
	 * [minifyHtml  Shortens html code by removing whitespaces where they are not necessary.]
	 * @param  [string] $buffer [A string that contains html code.]
	 * @return [string]         [A minified version of the input string.]
	 */
	public static function minifyHtml($buffer){
		$search = array(
			'/\>[^\S ]+/s',  // Removes whitespaces after tags, except space
			'/[^\S ]+\</s',  // Removes whitespaces before tags, except space
			'/(\s)+/s'       // Shorten multiple whitespace sequences
		);
		$replace = array('>','<','\\1');
		$buffer = preg_replace($search, $replace, $buffer);
		return $buffer;
	}
	/**
	 * [getFileContent Get the file content of a certain file.]
	 * @param  [string] $filePath [The file path.]
	 * @return [bool:string]      [Returns false if the file is not found. Returns the file data if the file is found.]
	 */
	public static function getFileContent($filePath){
		$data = @file_get_contents($filePath);
		if($data === FALSE){
			return false;
		} else {
			return $data;
		}
	}
	public static function stringContains($string, $keyword){
		if (strpos($string, $keyword) !== false) {
			return true;
		} else {
			return false;
		}
	}
}

?>