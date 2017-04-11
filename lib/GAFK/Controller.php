<?php

namespace GAFK;

/*********************************
#Controller Class
> Contoller class handle global process
 - Instanciate Application
 - Get HTTP Request from User
 - Get Route (module, action) from Router
 	> Get Module Manager

*********************************/


class Controller
{

	protected $app; // Application object
 	protected $HTTPRequest; // HTTPRequest Object: cookies, get, post, url values
 	protected $router; //Router object
 	protected $route; // Route object found with Router class
 	protected $manager; //Manager to execute based on Route 
 	protected $loader; //PSR4 loader
 	protected $renderer; //twig renderer

 	public function __construct($loader)
 	{
 		$this -> setLoader($loader);
 		
 		$this -> setRenderer();
 		//Initiate app
 		$this -> setApp();

		//Initiate HTTPRequest
		$this -> setHTTPRequest();

		/*//initiate Router
		$this -> setRouter();

		//Get Route from Router
		$this -> setRoute();

		//get manager from Route
		$this -> setManager();

		$this -> manager -> execute();*/



 	}

 	/* SETTERS */
 	public function setLoader(Psr4AutoloaderClass $loader)
 	{
 		$this -> loader = $loader;
 	}

 	public function setRenderer()
 	{
		$twig_loader = new \Twig_Loader_Filesystem(__DIR__.'/../../App/_Templates');
		$this -> renderer = new \Twig_Environment($twig_loader);
 	}


 	public function setApp()
 	{
 		$this -> app = new Application();
 	}

 	public function setHTTPRequest()
 	{

		$this -> HTTPRequest = new HTTPRequest();
 	}

 	/*********************************
 	*#Purpose
 	* Instantiate Router Class with App/Config/Routes.xml as a paramter (xml file where routes are stored) and set it as this-> router value
	*
	* @return void
 	**********************************/
 	public function setRouter()
 	{
 		$this -> router = new Router();
 	}


	/*********************************
	*#Purpose
	*SetRoute function define route (Route Object) with Router object
 	*
	*
 	*#Actions
 	* 
	* - get route object from router (if route not found, Router will return a route object targeting Error Module)
	*   -> Define $this -> route value
	*
	*
	*@return void
	**********************************/
 	public function setRoute()
 	{
		$this -> route = $this -> router -> setRoute($this -> app -> getHost(), $this -> HTTPRequest -> HTTP_HOST(), $this -> HTTPRequest -> requestURI());
 	}



	/*********************************
	*#Purpose
	*Instanciate Manager Class to execute based on Route Module 
	*
 	*#Actions
 	* - $module = 
 	* 	  Namespace : route module name
 	*	  File Name : route manager Module file name
	* - $action = Route action
	*
	*@return void
	**********************************/
 	public function setManager()
 	{
 		$module = $this -> getRoute() -> getModule();
 		$type = $this -> getRoute() -> getType();
 		//load Manager Class
 		#print_r('Load Folder: '.__DIR__.'/../../App/'.$module.'/'.$type.'/ => as '.$module.'<br />');
		$this -> loader -> addNamespace($module, __DIR__.'/../../App/'.$module.'/'.$type.'/');

		#print_r('Load Class: '.$module.'\\'.$this -> route -> getManagerFileName($module).'<br />');
 		$module_class = $module.'\\'.$this -> route -> getManagerFileName($module);
 		if (class_exists($module_class)) {
 			$this -> manager = new $module_class($module, $type, $this -> route -> getAction(), $this -> route -> getRoutevars());
 		}else{
 			$this -> loader -> addNamespace('_Error', __DIR__.'/../../App/_Error/Frontend/');
 			$this -> manager = new \_Error\_ErrorFrontendManager($module, $type, $this -> route -> getAction(), $this -> route -> getRoutevars());
 		}

 		

 	}

 	/* GETTERS */

 	public function getRoute()
 	{
 		return (isset($this -> route))? $this -> route : null;
 	}

 	public function getRouter()
 	{
 		return (isset($this -> router))? $this -> router : null;
 	}


 	public function getManager()
 	{
 		return (isset($this -> manager))? $this -> manager : null;
 	}

 	public function renderOutput()
 	{
 		$template_vars = $this -> manager -> getTemplateVars();
 		$template_vars['base_dir'] = 'http://'.$this -> app -> getHost();
 		//print_r('Template: '.$this -> manager -> getTemplate());
 		#print_r(var_dump($template_vars));
 		echo $this -> renderer -> render($this -> manager -> getTemplate(), $template_vars);

 	}


}

?>