<?php

namespace App\Controllers;

class SecondController extends AppController{

  /* Execute Index page */
  protected function executeIndex($params = null){
    \core\Template::setStatic("title", "Second controller page");
    \core\Template::setStatic("content", \core\Template::render("pages/second/home.html"));
  }

}

?>
