<?php

namespace App\Controllers;

class ErrorController extends \core\Controller{

  protected function executeIndex($params = null){
    echo \core\Template::render("404.html");
  }

}

?>
