<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native\db;

use csmc\native\framework\config as config;
use csmc\native\framework\redirects as redirects;
use csmc\native\debug\log as log;

class omysqli {

    protected 	$omysqli; 		//The database connection object
    public      $errorFlag = false; //Error flag for the connection
    /**
     * [__construct Initializes a mysqli connection and stores the object in $omysqli]
     */
    public function __construct(){
        //Gets the mysql database information from the configuration file
        $dbOAuth = config::getInstanceDatabaseDetails();
        //Checks if the omysqli index exists
        if(isset($dbOAuth["omysqli"])){
            //Declare variable for ease of use
            $dbOAuth = $dbOAuth["omysqli"];
        } else {
            //Calls an error, redirects the user to a 707 costum error page and kills the script
            $errorFlag = true;
            redirects::error(707, "Configuration for database missing.");
            exit();
        }
        //Checks if all the fields of the configuration file are not empty
        if(empty($dbOAuth) || (!isset($dbOAuth["host"]) && !isset($dbOAuth["user"]) && !isset($dbOAuth["pass"]) && !isset($dbOAuth["db"]))){
            $this->errorFlag = true;
            redirects::error(707, "Database connection information missing or invalid.");
            exit();
        } else {
            if(empty(trim($dbOAuth["db"]))){
                log::add(LOG::WARNING, "A database must be specified.");
                $this->errorFlag = true;
                redirects::error(707, "A database name must be specified.");
                exit();
            }
            $this->omysqli = new \mysqli($dbOAuth["host"], $dbOAuth["user"], $dbOAuth["pass"], $dbOAuth["db"]);
            if($this->omysqli->connect_errno){
                $this->errorFlag = true;
                redirects::error(707, $this->omysqli->error);
                exit();
            }
        }
    }
	/**
     * [__destruct Ends the mysqli connection and stores the object in $omysqli]
     */
    public function __destruct(){
        //Only closes the omysqli connection has been open
        if($this->errorFlag === false){
            $this->omysqli->close();
        }
    }
    /**
     * errno - Returns a mysqli error message
     * @return [string]
     */
    public function error(){
        //Only shows errors if the omysqli connection has been open
        return $this->omysqli->error;
	}
    /**
     * [[Sanitize using real_escape, htmlentities and strip tags.]]
     * @param   [[string]] $dirtyObj [[The object to sanitize]]
     * @returns [[string]] [[The sanitized object.]]
     */
    public function sanitize($dirtyObj){
        $cleanObj = htmlentities(strip_tags($this->omysqli->real_escape_string($dirtyObj)));
        return $cleanObj;
    }
    /**
     * [[dataExecute Returns a multidimensional associative array of data]
     * @param   string $query [[Description]]
     * @param   string $rows  [[Description]]
     * @returns Boolean  [[Description]]
     */
    public function dataExecute($query, $rows){
        //Makes the omysqli query object
        $stmt = $this->omysqli->query($query);
        //If the query is executed successfully
        if($stmt){
            //Array that will return the results
            $return = array();
            //The name of the array keys, that are the names of the rows
            $arrayKeys = explode(',', $rows);
            $i = 0;
            //IMPORTANT: The array must be accessed at index 1
			$ii = 0;
            //For each row[i] => (colum[i])
			//Iterates over each row
            while($rows_f = $stmt->fetch_array(MYSQLI_ASSOC)){
				//Iterates over each column
                while($i < count($arrayKeys)){
                    $return[$ii][$arrayKeys[$i]] = $rows_f[$arrayKeys[$i]];
                    $i++;
                }
                $ii++;
                $i = 0;
            }
            //Frees the buffer from the data
            $stmt->free();
            //Returns the multidimensional array of the type array[i][colum_name]
            return $return;              
        } else {
            return false;
        }
    }
    /**
     * [[countExecute Returns a certain number from a COUNT type query]]
     * @returns Boolean or Integer  [if an error occurs returns false, else integer]
     */
    public function countExecute($query){
        //Use omysqli->prepare to set the query
		$stmt = $this->omysqli->prepare($query);
        if($stmt){
            if($stmt->execute()){
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                return $count;
            } else {
               $stmt->close();
               return false;
            }
        } else {
            return false;
        }
    }

	/**
     * [[boolExecute Executes a query that does not return a value.]]
     * @returns Boolean [if an error occurs returns false, else true]
     */
    public function boolExecute($query){
        //Use omysqli->prepare to set the query
		$stmt = $this->omysqli->prepare($query);
        if($stmt){
            if($stmt->execute()){
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        } else {
            return false;
        }
    }
}
?>