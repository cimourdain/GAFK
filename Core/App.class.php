<?php

namespace Core;

class App{
    use TLoggedClass;

    public function __construct(){
        //define logger
        $this->setLogger(new Logger());

        //analyse url with router
        $router = new Router($this->_logger);

        if(\App\Config::MAINTENANCE_ACTIVE)
          $this->launchController(["controller" => \App\Config::MAINTENANCE_CONTROLLER, "action" => \App\Config::MAINTENANCE_ACTION, "params" => [], "cache_seconds" => \App\Config:: MAINTENANCE_CACHE_DURATION_SECONDS]);
        else
          $this->launchController($router->execute($_SERVER['REQUEST_URI']));

        //$this->prettyPrintMessages();
    }

    //function to get controllerName from Route
    private function launchController($route){
      if(!empty($route) && isset($route["controller"]) && isset($route["action"]) && isset($route["params"]) && class_exists(($controllerClassName = "\App\Controllers\\" . $route["controller"] . "Controller"))) {
          //instanciate cache
          $cache = new \Core\Cache($route, $this->_logger);
          //call controller if no cached version to render
          if(!$this->getCachedView($cache, $route)){
            $this->addMessage("Enter controller ".$controllerClassName, "info", "dev");
            $c = new $controllerClassName($route, $this->_logger);
            $c->execute($cache);
          }
      }
      else{
        $this->addMessage("Invalid or empty route", "error", "dev");
        $this->prettyPrintMessages();
        throw new \Exception ("Invalid or empty route");
      }
    }

    //render chached version if required and available
    protected function getCachedView($cache, $route){
      if($cache->isCacheRequired() && $cache->isCacheAvailable()){
          $cache->renderCache();
          return true;
        }
      return false;
    }
}

 ?>
