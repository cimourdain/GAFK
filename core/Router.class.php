<?php

namespace core;

class Router {

    private $_routes = array();

    public function __construct(){
        $this->setRoutes();
    }

    private function setRoutes(){

      $routes_str = file_get_contents(__DIR__.'/../App/Routes.json');

      $routes_json = json_decode($routes_str, true);

      foreach($routes_json as $route){
        $this->addRoute($route["regex_pattern"], $route["controller"],$route["action"]);
      }
    }

    private function addRoute($pattern, $controller, $action) {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        $this->_routes[$pattern] = ["controller"=>ucfirst($controller), "action"=>$action];
    }

    public function execute($uri) {
        $uri = str_replace(\App\Config::SCRIPT_FOLDER, "", $uri);
        foreach ($this->_routes as $pattern => $ctrl) {
            //echo "Check route ".$pattern." against ".$uri."<br />";
            if (preg_match($pattern, $uri, $params) === 1) {
                //echo "OK<br />";
                array_shift($params);
                return ["controller" => $ctrl["controller"], "action" => $ctrl["action"], "params"=>$params];
            }
        }
    }

}

?>
