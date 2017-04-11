<?php

namespace _Error;

class _ErrorFrontendManager extends \GAFK\Manager
{

	public function execute()
	{
		$this -> setTemplate("./HomePage/HomePage.twig");
		$this -> setTemplateVars(["Page_Title" => "Error - Page not found"]);
	}

	public function executeError()
	{

		$this -> execute();
	}
}

?>