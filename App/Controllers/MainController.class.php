<?php

namespace App\Controllers;

class MainController extends AppController{

  /* Execute Index page */
  protected function executeIndex($params = null){
    \core\Template::setStatic("title", "Home page");
    \core\Template::setStatic("content", \core\Template::render("pages/home.html"));
  }

  /* Execute Index page */
  protected function executePage2($params = null){
    \core\Template::setStatic("title", "Second page");
    \core\Template::setStatic("param_val", $this->_params[0]);
    \core\Template::setStatic("content", \core\Template::render("pages/second_page.html"));
  }

}

?>
