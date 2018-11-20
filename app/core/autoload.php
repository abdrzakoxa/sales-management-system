<?php

spl_autoload_register(function ($className)
{
	$className = strtolower($className); // replace uppercase to lowercase
	$className = str_replace(strtolower(APP_NAME_SPACE) . '\\' , '' , $className) ; // remove app name space in Class

	$ClassFile = APP_PATH . DS . $className . '.php'; /*** class file */

	if(file_exists($ClassFile)){ // if class file exists
		require_once $ClassFile; // require Class file
	}
});

