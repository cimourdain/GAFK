<?php

namespace _Error;

class _ErrorFrontendManager extends \GAFK\Manager
{

	public function execute()
	{
		$this -> setTemplate("./HomePage/HomePage.twig");
		$this -> setTemplateVars(["Page_Title" => "Error"]);
	}

	public function executeError()
	{
		$this -> setTemplate("./HomePage/HomePage.twig");
		$this -> setTemplateVars(["Page_Title" => "Error"]);
	}


}

?>