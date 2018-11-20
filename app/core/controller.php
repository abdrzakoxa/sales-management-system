<?php
namespace Store\Core;



class Controller
{
    private $controller='dashboard' , $action='default' , $registry, $authorization;
    const NOT_FOUND_CONTROLLER = 'Notfound';
    const NOT_FOUND_ACTION = 'notfound';

    use sanitize;
    use Helper;

    public function __construct($registry)
    {
        $this->registry = $registry; // set class registry to { registry property } ok .
        $this->authorization = Authorization::getInstance(); // set instance class Authorization to { authorization property }
    }


	/**
	 * @description this method can to parse url and filer the url you requested
	 */

    public function parseUrl()
    {
    	// follow me step by step

		// for example : I request this url => https://www.domin.com/controller/action

		$url = isset($_GET['uri']) ? self::filterUrlPath($_GET['uri']) : ''; // check to is set GET uri || get uri is REQUEST URI || in this link above I will get => controller/action


		$url_array = explode('/' , $url); // in this case I will get array ['controller','action']


        if(count($url_array) == 1 && !empty(trim($url_array[0]))){ // if count variable $url_array equal 1 and his index 0 not empty, ok it's a just controller don't set action
            list($this->controller) =  $url_array; // set controller equal $url_array[0] ok
        }
        elseif(count($url_array) == 2) // else if count $url_array equal 2 , ok it's a controller and action like example above
        {
            list($this->controller , $this->action) = $url_array; // set controller equal $url_array[0] and action equal $url_array[1]
        }
        elseif(count($url_array) > 2)
        {
            // if count this variable is larger than 2, ok set controller not found in property
            $this->controller = self::NOT_FOUND_CONTROLLER ;
            $this->action = self::NOT_FOUND_ACTION;
        }
        $this->controller = ucfirst($this->controller); // set first case controller to upper case
    }


	/**
	 * @description this method can to run app and show content and filer the url you requested
	 */
	public function _run()
    {
        $this->parseUrl(); // parse url method above

        $ClassName = 'Store\controllers\\' . $this->controller . 'Controller'; // set class with name space in the first and Word Controller in the last
        $ActionName = $this->action . 'Action'; // set action width word Action in the last

        $urlToo = strtolower($this->action) == 'default' ? '' : '/' . strtolower($this->action) ; // if action equal default set in this variable empty
        $url = strtolower($this->controller) . $urlToo;

        $this->authorization->Auth($this->controller,$this->action); // authorization and check link

        if(!class_exists($ClassName) || !method_exists($ClassName,$ActionName)){ // check if class and method not exist
			// set not found page
            $ClassName =  'Store\controllers\\' . self::NOT_FOUND_CONTROLLER . 'Controller';
            $this->controller = self::NOT_FOUND_CONTROLLER;
            $this->action = self::NOT_FOUND_ACTION ;
        }elseif (!$this->authorization->isAuth() && !self::pages_not_access($url) && $url != 'install') // check if is authorization and check the permission user
        {
        	// set page login
            $ClassName =  'Store\controllers\AuthController';
            $this->controller = 'Auth';
            $this->action = 'login' ;
        }



        $classOBJ = new $ClassName($this->registry);

        if ($this->authorization->isAuth()) // is authorization
        {
        	// ok connect to database
            if (DB::connect()){
                $permissions = $classOBJ->is_permission_user($url); // check if is permission user
                $permissionController = $classOBJ->is_permission_user($this->controller);
                if(!$permissions || !$permissionController) { // if $permissions equal false or $permissionController equal false
                	// set page Not your permission
                    $ClassName =  'Store\controllers\\' . self::NOT_FOUND_CONTROLLER . 'Controller';
                    $this->controller = self::NOT_FOUND_CONTROLLER;
                    $this->action = 'notPermission' ;
                    $classOBJ = new $ClassName($this->registry);
                }
            }
        }

        // set controller and action

        $classOBJ->setController($this->controller);
        $classOBJ->setAction($this->action);


        $method = $this->action . 'Action';

        $classOBJ->$method(); // run class and method

    }



}