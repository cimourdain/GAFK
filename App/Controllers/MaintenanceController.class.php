<?php

namespace App\Controllers;

class MaintenanceController extends AppController{

  protected function executeIndex($params = null){
    \Core\Template::setStatic("title", "Maintenance Page");
    \Core\Template::setStatic("content", \Core\Template::render("Maintenance.html"));
  }

}

?>
