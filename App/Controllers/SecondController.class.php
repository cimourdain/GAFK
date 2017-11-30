<?php

namespace App\Controllers;

class SecondController extends AppController{

  /* Execute Index page */
  protected function executeIndex($params = null){
    \Core\Template::setStatic("title", "Second controller page");
    \Core\Template::setStatic("content", \Core\Template::render("pages/second/home.html"));
  }

}

?>
