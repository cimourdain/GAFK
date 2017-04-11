<?php

namespace GAFK;

abstract class ManagerPDO extends ManagerDB
{

	public function create_connexion()
	{
	    $db = new \PDO('mysql:host='.$this -> host.';dbname='.$this -> db_name, $this -> db_user, $this -> db_pass);
	    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	    
	    return $db;
	}

}


?>