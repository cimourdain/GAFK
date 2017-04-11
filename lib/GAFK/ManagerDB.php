<?php

namespace GAFK;

abstract class ManagerDB
{

	protected $db;

	protected $db_xml_file_content;

	protected $connexion_type;//local or remote
	protected $host;
	protected $db_name;
	protected $db_user;
	protected $db_pass;


	public function __construct($module)
	{
		$this -> setConnexionType();
		$this -> setDBCredentialsFromXML($module);
		
		if(isset($this -> host))
			$this -> create_connexion();
		else
			echo 'No DB Found in Database.xml <br />';

	}

	/* SETTERS */
	protected function setHost($host)
	{
		$this -> host = $host;
	}

	protected function setDBName($db_name)
	{
		$this -> db_name = $db_name;
	}

	protected function setDBUser($db_user)
	{
		$this -> db_user = $db_user;
	}

	protected function setDBPass($db_pass)
	{
		$this -> db_pass = $db_pass;
	}


	/*********************************
	*#Purpose
	*Function to set connexion type (local or remote) based on $_SERVER['REMOTE_ADDR'] value
 	*
	*#Params
	*-
	*
 	*#Actions
 	*Set $this -> connexion_type value
	*
	*@return void
	**********************************/
	protected function setConnexionType()
	{
		$localhost_ip = array('127.0.0.1', "::1");

		if(in_array($_SERVER['REMOTE_ADDR'], $localhost_ip))
		{
			$this -> connexion_type = 'local';
		}else
		{
			$this -> connexion_type = 'remote';
		}
	}


	/*********************************
	*#Purpose
	*Function to fetch Database credential for module in the App/Config/Databases.xml file
 	*
	*#Params
	*@param string $module -> module name 
	*
 	*#Actions
 	*Call set function for $this -> host, db_name, db_user and db_pass if credentials found in xml file
	*
	*@return void
	**********************************/
	protected function setDBCredentialsFromXML($module)
	{
		$xml_file =  __DIR__.'/../../App/_Config/Databases.xml';
		$this -> db_xml_file_content = new \SimpleXMLElement($xml_file, NULL, TRUE);

		$ct =  $this -> connexion_type;
		foreach($this -> db_xml_file_content -> $ct -> module as $db_credentials)
		{
			$line_modules = explode(',', str_replace(' ', '', $db_credentials['module_name']));
			if(in_array($module, $line_modules))
			{
				$this -> setHost($db_credentials['host']);
				$this -> setDBName($db_credentials['db_name']);
				$this -> setDBUser($db_credentials['db_user']);
				$this -> setDBPass($db_credentials['db_pass']);
			}
		}

	}


	/* CUSTOM */
	/*********************************
	*#Purpose
	*Abstract function to force inherited classes to implement a function to create connexion.
 	*This function will start with a call to setDBCredentialsFromXML($module) to get DB credentials
 	*
	*#Params
	*-
	*
 	*#Actions
 	*Instanciate new connexion to set $this -> db
	*
	*@return void
	**********************************/
	abstract public function create_connexion();


}

?>