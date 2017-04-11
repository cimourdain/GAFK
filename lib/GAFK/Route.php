<?php
namespace GAFK;

/*****************************

Route Object is instanciated by router object based on route values found in XML file

Route object holds module type (backend/frontend), module name, action and url_params

******************************/

class Route
{

	use CommonFunctions;

	protected $type; //backend or frontend
	protected $module; //module name
	protected $action; //action to perform with module
	protected $route_vars;


	/*********************************
	*#Purpose
	*Instanciate route from Router Object, params are found in XML file
 	*
	*#Params
	*	- $module: module name
	*   - $type: backend/frontend
	*   - $action: action to perform on module
	*   - $matches: TO DO
	*
 	*#Actions
 	*	- call setter method to define route attributes
	*
	*@return void
	**********************************/
	public function __construct($module = "_Error", $type = "Frontend", $action = "Index", $route_vars = [])
	{
		$this -> hydrate(["module" => $module, "Type" => $type, "Action" => $action, "Routevars" => $route_vars]);
		$this -> checkIfValidRoute();
	}

	/* HYDRATE > used by contruct method */
	private function hydrate(array $construct_values)
	{
		foreach ($construct_values as $key => $value)
		{
		    $method = 'set'.ucfirst($key);
		    if (method_exists($this, $method))
		    {
		    	$this -> $method($value);
		    }
		}
	}


	/* SETTERS */
	//set module name
	public function setModule(string $module)
	{
		$module_name = ucfirst($module);
		if(!is_null($module_name) && $this -> isValidString($module_name))
		{
			$this -> module = $module_name;
		}else{
			$this -> module = 'Error';
		}
	}

	//set route type (can only be Frontend or Backend)
	public function setType(string $type)
	{
		if(!in_array(ucfirst($type), ['Frontend', 'Backend']))
		{
			$this -> type = 'Frontend';
		}
		else
		{
			$this -> type = ucfirst($type);
		}
	}

	public function setAction($action)
	{
		if(!is_null($action) && $this -> isValidString($action))
		{
			$this -> action = ucfirst($action);
		}
	}

	public function setRoutevars(array $route_vars)
	{
		$this -> route_vars =  $route_vars;
	}

	/*********************************
	*#Purpose
	*Check if current Route is valid (Route folder exists?, manager file exists?)
 	*
	*#Params
	*
	*
 	*#Actions
 	* - Define module path > App/Module name / Type (Frontend/Backend)
 	* - Define module manager file path  > module path / (module name + type + 'Manager').php
 	* - set Route to error if pah or manager file do not exists
	*
	*@return true/false
	**********************************/
	public function checkIfValidRoute()
	{

		$module_path = __DIR__.'/../../App/'.$this -> module.'/'.$this -> type; //module manager path
		$module_manager_path = $module_path.'/'.$this -> getManagerFileName().'.php'; //module manager file

		if(is_dir($module_path) && file_exists($module_manager_path))
		{
			return true;
		}else
		{
			$this -> setRouteToError();
			return false;
		}
	}

	/*********************************
	*#Purpose
	* Set route to target error module
	*
	*@return : void
	**********************************/
	private function setRouteToError()
	{
		$this -> module = '_Error';
		$this -> type = 'Frontend';
		$this -> action = 'Index';
		$this -> route_vars = [];
	}


	/* GETTERS */
	//get module manager file name > module name + (Admin depending on type) + 'Manager'
	public function getManagerFileName()
	{
		return $this-> module . $this -> type .'Manager';
	}

	public function getModule()
	{
		return (isset($this -> module)) ? $this -> module : null;
	}

	public function getType()
	{
		return (isset($this -> type)) ? $this -> type : null;
	}

	public function getAction()
	{
		return (isset($this -> action)) ? $this -> action : 'Error';
	}
	public function getRoutevars()
	{
		return (isset($this -> route_vars)) ? $this -> route_vars : [];
	}

}

?>