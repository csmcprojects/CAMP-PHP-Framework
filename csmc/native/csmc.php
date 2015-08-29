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