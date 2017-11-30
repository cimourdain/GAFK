<?php

namespace App\Controllers;

class ErrorController extends AppController{

  protected function executeIndex($params = null){
    \Core\Template::setStatic("title", "Home page");
    \Core\Template::setStatic("content", \Core\Template::render("404.html"));
  }

}

?>
