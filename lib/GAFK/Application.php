<?php

namespace GAFK;

class Application {

	use CommonFunctions;

	protected $name;
	protected $author;
	protected $version;


	/*********************************
	*#Purpose
	*Application constructor > define Application params from /App/Config/App.xml file
 	*
	*#Params
	*-
	*
 	*#Actions
 	* - Open /App/Config/App.xml file
 	* - For each "define" tag > set element if set method exists
	*
	*@return void
	**********************************/
	public function __construct()
	{
		//load application params from App.xml
		$xml = new \DOMDocument;
		$xml -> load(__DIR__.'/../../App/_Config/App.xml');

		$elements = $xml -> getElementsByTagName('define');

		foreach ($elements as $element)
		{
			$method = 'set'.ucfirst($element->getAttribute('var'));
			if(is_callable([$this, $method]))
			{
				$this -> $method($element->getAttribute('value'));
			}
		}



	}

	/* SETTERS */
	public function setName($name)
	{
		if($this -> isValidString($name))
		{
			$this -> name = $name;
		}
	}

	public function setAuthor($author)
	{
		if($this -> isValidString($author))
		{
			$this -> author = $author;
		}
	}

	public function setVersion($version)
	{
		if(is_float(floatval($version)))
		{
			$this -> version = floatval($version);
		}
	}

	public function setHost($host)
	{
		$this -> host = $host;
	}

	/* GETTERS */
	public function getName()
	{
		return $this -> name;
	}

	public function getAuthor()
	{
		return $this -> author;
	}

	public function getVersion()
	{
		return $this -> version;
	}
	public function getHost()
	{
		return $this -> host;
	}

}

?>