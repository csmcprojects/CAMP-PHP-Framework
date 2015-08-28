<?php
/*
 * This file is part of CSMC Framework.
 *
 * (c) Carlos Campos <csamuelcampos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The autoloader functions seen bellow use the PSR-4 — Autoloader closure example,
 * as seen in (http://www.php-fig.org/psr/psr-4/examples/), adjusted for this platform.
 */

/**
 * [native_autoload Autoloads native namespaced classes.]
 * @param  [string] $class [The fully-qualified classname.]
 * @return [string]        [The path of the class file.]
 */
 function native_autoload($class) {

    // project-specific namespace prefix
    $prefix = NATIVE_NAMESPACE;

    // base directory for the namespace prefix
    $base_dir = CSMC_NATIVE_ROOT;

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)){
		require_once($file);
    }
}

/**
 * [modules_autoload Autoloads module namespaced classes.]
 * @param  [string] $class [The fully-qualified classname.]
 * @return [string]        [The path of the class file.]
 */
function modules_autoload($class) {

    $prefix = MODULE_NAMESPACE;

    $base_dir = CSMC_MODULES_ROOT;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)){
        require_once($file);
    }
}

//Adds the autoload functions to the spl autoload register.
spl_autoload_register("native_autoload");
spl_autoload_register("modules_autoload");
?>