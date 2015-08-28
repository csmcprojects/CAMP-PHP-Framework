<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace csmc\native;

use csmc\native\debug\log as log;
use csmc\native\framework\ios as ios;
use csmc\native\oauth\session as session;

class csmc{

	public function __construct(){
		//Defines global variables used by the framework
		define("CSMC_CORE_ROOT", $_SERVER["DOCUMENT_ROOT"]."/csmc");
		define("CSMC_NATIVE_ROOT", CSMC_CORE_ROOT."/native/");
		define("CSMC_MODULES_ROOT", CSMC_CORE_ROOT."/modules/".__INSTANCE__."/");
		define("INSTANCE_CONFIG_ROOT", CSMC_CORE_ROOT."/instance.".__INSTANCE__.".config");
		define("AUTOLOAD_ROOT", CSMC_CORE_ROOT."/autoload.php");
		define("NATIVE_NAMESPACE", 'csmc\\native\\');
		define("MODULE_NAMESPACE", 'csmc\\modules\\'.__INSTANCE__.'\\');
		//Autoload mechanism, follows PSR-0 specifications
		//Autoload for native classes and modules classes
		require_once(AUTOLOAD_ROOT);
		//Starts the session
		new session();
		//Front Control End
	    log::add(log::NOTICE, "\/************* New request **************\/");
		//New ios request
		ios::in();
		//Front Control Start
		log::add(log::NOTICE, "\/************* End request **************\/");
	}
}

?>