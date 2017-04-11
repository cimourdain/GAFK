<?php

namespace Homepage;

class HomepageFrontendManager extends \GAFK\Manager
{

	public function executeIndex($vars)
	{
		$this -> setTemplate("./HomePage/HomePage.twig");
		#$this -> setTemplateVars(["Page_Title" => "Welcome on my page"]);
	}


}

?>