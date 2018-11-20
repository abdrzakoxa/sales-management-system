<?php
// * The most used constants
define('DS', DIRECTORY_SEPARATOR ); // directory separator
define('APP_NAME_SPACE' , 'Store'); // name Of parent file And Name space Like => Store

define('APP_PROTOCOL', getProtocol());
define('APP_DOMINE', $_SERVER['HTTP_HOST']);

// ** Paths

define('APP_PATH' , dirname(__FILE__) . DS . '..'  ); // app path

define('CONFIG_PATH' , dirname(__FILE__) ); // app path

define('VIEWS_PATH' ,APP_PATH . DS . 'views' ); // app path

define('CORE_PATH' , APP_PATH . DS . 'core' ); // core path


define('TEMPLATE_PATH' , APP_PATH . DS . 'template' ); // template Path

define('LANGUAGE_PATH' , APP_PATH . DS . 'languages' ); // template Path

define('FUNCTIONS_PATH' , APP_PATH . DS . 'functions' ); // template Path

define('STRUCTURE_PATH' , TEMPLATE_PATH . DS . 'templatestructure' ); // Template structure Path


// *** Links
define('APP_LINK' , APP_PROTOCOL . '/' . '/' . APP_DOMINE ); // core path

// files

define('BACKUP_PATH' , APP_PATH . DS . 'backupdatabase' ); // Template structure Path


// for include sources only :=>

define('PUBLIC_PATH' , DS ); // public path

define('CSS_PATH' ,PUBLIC_PATH . 'css' ); // css path

define('IMAGES_PATH' ,PUBLIC_PATH . 'images' ); // css path

define('UPLOADS_PATH' , PUBLIC_PATH . 'uploads' ); // css uploads

define('JS_PATH' ,PUBLIC_PATH . 'js' ); // js path

define('PLUGINS_CSS_PATH' ,CSS_PATH . DS . 'plugins' ); // plugins css Path path

define('LIBRARIES_CSS_PATH' ,CSS_PATH . DS . 'libraries' ); // plugins css Path path

define('LIBRARIES_JS_PATH' ,JS_PATH . DS . 'libraries' ); // libraries js Path path

define('PLUGINS_JS_PATH' ,JS_PATH . DS . 'plugins' ); // plugins js Path path
