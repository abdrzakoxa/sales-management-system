<?php
/**
 * thank you for purchases I am happy
 * Documentation => http://oxa.ueuo.com/OXA/Documentation.html
 * Explanatory Video => https://www.youtube.com/watch?v=83WSXCbQKIE
 */



require 'app/functions/functions.php'; // require functions app

require_once 'app/configure/config.php';// require files configure

$config_template = require CONFIG_PATH . '/template.php'; // Template array resourc

session_start(); // start session
header('Content-Type: text/html; charset=utf-8'); // set header type text/html utf-8

require_once CORE_PATH . '/autoload.php'; // require file autoload classes

\Store\core\Settings::load_config(); // load config

$Language = new \Store\Core\Languages(); // new object of class language

$Language->set(); // start or set or select language user

/** get Instance classes for set in registry  */
$Session    = \Store\Core\Sessions::getInstance() ; // class session
$Messenger  = \Store\Core\Messenger::getInstance(); // class messanger
$Currency  = \Store\Core\Currency::get_instance(); // class currency
$Currency->update_currency(); // currency update
$registry = \Store\Core\Registry::getInstance(); // ok this is a class registry
/** and I set classes of registry ok => */
$registry->Session          = $Session;
$registry->Messenger        = $Messenger;
$registry->Language         = $Language;
$registry->Currency         = $Currency;
$registry->config_template  = $config_template;

$con = new \Store\Core\Controller($registry); // new class controller and set registry of construct this class

$con->_run(); // run the App


