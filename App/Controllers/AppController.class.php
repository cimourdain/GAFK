<?php

namespace App\Controllers;

class AppController extends \Core\Controller{

  /* Action performed before execution */
  protected function before(){
    \Core\Template::setStatic("site_name", \App\Config::SITE_NAME);
    \Core\Template::setStatic("author", \App\Config::AUTHOR);
  }

  /* Action performed after execution */
  protected function after(){
    \Core\Template::setStatic("header", \Core\Template::render("partials/header.html"));
    \Core\Template::setStatic("footer", \Core\Template::render("partials/footer.html"));
    $this->setHTML(\Core\Template::render("partials/base.html"));
  }

}

?>
