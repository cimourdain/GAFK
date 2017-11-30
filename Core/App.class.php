<?php

namespace Core;

class App{
    use TLoggedClass;

    public function __construct(){
        //define logger
        $this->setLogger(new Logger());

        //analyse url with router
        $router = new Router($this->_logger);
        $this->launchController($router->execute($_SERVER['REQUEST_URI']));

        //$this->prettyPrintMessages();
    }

    //function to get controllerName from Route
    private function launchController($route){
      if(!empty($route) && isset($route["controller"]) && isset($route["action"]) && isset($route["params"])){
        $controllerClassName = "\App\Controllers\\" . $route["controller"] . "Controller";
        if(class_exists($controllerClassName)){
          $this->addMessage("Enter controller ".$controllerClassName, "info", "dev");
          $c = new $controllerClassName($route, $this->_logger);
          $c->execute();
        }
        else{
          $this->addMessage("ControllerClass not found", "error", "dev");
          $this->prettyPrintMessages();
          throw new \Exception ("ControllerClass not found");
        }
      }
      else{
        $this->addMessage("Invalid or empty route", "error", "dev");
        $this->prettyPrintMessages();
        throw new \Exception ("Invalid or empty route");
      }
    }

}

 ?>
