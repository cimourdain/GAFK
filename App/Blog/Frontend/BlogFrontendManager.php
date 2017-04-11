<?php

namespace Blog;

class BlogFrontendManager extends \GAFK\Manager
{

	public function execute()
	{
		echo 'Welcome in Module '.$this -> module.', let\'s execute action '.$this -> action.'<br />';
		$actionMethod = $this -> action;
		$this -> $actionMethod();
	}

	public function executeIndex()
	{
		echo 'Display blog homepage';
	}

	public function executeError()
	{
		echo 'Welcome on Blog: Error Page';
	}
}

?>