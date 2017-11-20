<?php

namespace App\Controllers;

class AppController extends \core\Controller{

  /* Action performed before execution */
  protected function before(){
    \core\Template::setStatic("site_name", \App\Config::SITE_NAME);
    \core\Template::setStatic("author", \App\Config::AUTHOR);
  }

  /* Action performed after execution */
  protected function after(){
    \core\Template::setStatic("header", \core\Template::render("partials/header.html"));
    \core\Template::setStatic("footer", \core\Template::render("partials/footer.html"));
    echo \core\Template::render("partials/base.html");
  }

}

?>
