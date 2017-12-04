<?php

namespace Core;

class App{

    public function __construct(){
        //analyse url with router
        $router = new Router();

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
          $cache = new \Core\Cache($route);
          //call controller if no cached version to render
          if(!$this->getCachedView($cache, $route)){
            \Core\Logger::addMessage("Enter controller ".$controllerClassName, "info", "dev");
            $c = new $controllerClassName($route);
            $c->execute($cache);
          }
      }
      else{
        \Core\Logger::addMessage("Invalid or empty route", "error", "dev");
        \Core\Logger::prettyPrintMessages();
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
