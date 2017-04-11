<?php

namespace GAFK;

abstract class ManagerMysqli extends ManagerDB
{

	public function create_connexion($host, $db_name, $login, $pass)
	{
	    return new MySQLi($host, $db_name, $login, $pass);
	}

}


?>