<?php

namespace GAFK;

abstract class Manager
{
	
	protected $module;
	protected $type; //backend or frontend
	protected $action;
	protected $url_get_vars;

	protected $db_manager;

	/* template */
	protected $template;
	protected $templatevars;

	public function __construct($module, $type ,$action, $url_get_vars)
	{
		$this -> setModule($module);
		$this -> setType($type);
		$this -> setAction($action);
		$this -> setUrlGetVars($url_get_vars);
		$this -> setDBManager();
	}

	/* SETTERS */


	public function setModule(string $module)
	{
		$this -> module = $module;
	}

	public function setType(string $type)
	{
		$this -> type = $type;
	}

	public function setAction(string $action)
	{
		$method = 'execute'.ucfirst($action);
		if(is_callable([$this, $method]))
		{
			$this -> action = $method;
		}else{
			$this -> action = 'executeError';
		}
	}

	public function setUrlGetVars(array $url_get_vars)
	{
		$this -> url_get_vars = $url_get_vars;
	}

	public function setDBManager()
	{
		$dbManagerClass = '\\'.$this -> module .'\\'.$this -> module.$this -> type.'DBManager';

		if(class_exists ($dbManagerClass))
		{
			$this -> dbManager = new $dbManagerClass($this -> module);
		}else
		{
			$this -> dbManager = null;
		}
	}

	/* Force implementation of execute function */
	public function execute()
	{
		$actionMethod = $this -> action;
		$this -> $actionMethod($this -> url_get_vars);
	}
	
	public function setTemplate($template)
	{
		$this -> template = $template;
	}
	public function setTemplateVars(array $template_vars)
	{
		$this -> templatevars = $template_vars;
	}

	public function getTemplate()
	{
		return $this -> template;
	}

	public function getTemplateVars()
	{
		return $this -> templatevars;
	}

}

?>