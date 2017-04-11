<?php

namespace SiteJSON;

class SiteJSONFrontendManager extends \GAFK\Manager
{

	public function executeIndex($vars)
	{
		$this -> setTemplate("./SiteJSON/SiteJSON.twig");

		switch($vars['lang'])
		{
			case 'en':
				$site_title = 'Welcome on the JSON website';
				break;
			case 'es':
				$site_title = 'Bienvenudo sobre el JSON sito';
				break;
			case 'fr':
			default:
				$site_title = 'Bienvenue sur le site en JSON';
				break;
		}
		$this -> setTemplateVars(["Page_Title" => $site_title]);
	}

	public function executeError()
	{
		echo 'Homepage Error';
	}


}

?>