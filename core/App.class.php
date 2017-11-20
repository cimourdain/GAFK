<?php

namespace core;

class App{

    public function __construct(){
        //analyse url with router
        $router = new Router();
        $route = $router->execute($_SERVER['REQUEST_URI']);

        //instanciate controller
        if(($controller = $this->getController($route)) != false)
          $controller->execute();

    }

    //function to get controllerName from Route
    private function getController($route){
      $controllerName = "\App\Controllers\\".ucfirst($route["controller"])."Controller";
      if($route != null && $route != null && !empty($route) && isset($route["controller"]) && isset($route["action"]) && class_exists($controllerName)){
        return new $controllerName($route["action"], $route["params"]);
      }
      else if(class_exists("\App\Controllers\\ErrorController")){
        echo "Return error controller";
        return new \App\Controllers\ErrorController();
      }else{
        echo "Error, impossible to resolve route.";
        return false;
      }
    }

}

 ?>
