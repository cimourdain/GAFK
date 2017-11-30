<?php

namespace App\Controllers;

class MainController extends AppController{

  /* Execute Index page */
  protected function executeIndex($params = null){
    \Core\Template::setStatic("title", "Home page");
    \Core\Template::setStatic("content", \Core\Template::render("pages/home.html"));
  }

  /* Execute Index page */
  protected function executePage2($params = null){
    \Core\Template::setStatic("title", "Second page");
    \Core\Template::setStatic("param_val", $this->_params[0]);
    \Core\Template::setStatic("content", \Core\Template::render("pages/second_page.html"));
  }

}

?>
