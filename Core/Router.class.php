<?php

namespace Core;

class Router {
    private $_routes = array();
    public function __construct(){
      $this->importRoutes();
    }

    /* fetch all routes from App/Routes.json file */
    private function importRoutes(){
      try{
        //get routes from router.json file
        $routes_json = json_decode(file_get_contents(__DIR__.'/../App/Routes.json'), true);

        //add routes to $this->routes
        foreach($routes_json as $route_name => $route){
          //check if route is properly defined
          if(isset($route["regex_pattern"]) && !empty($route["regex_pattern"]) && isset($route["controller"]) && !empty($route["controller"]) && isset($route["action"]) && !empty($route["action"])){
            $route["regex_pattern"] = $this->getPattern($route["regex_pattern"]);
            $route["controller"]    = $this->getControllerName($route["controller"]);
            $route["action"]        = $this->getActionName($route["action"]);
            $route["cache_seconds"] = $this->getCacheDuration($route);
            array_push( $this->_routes, $route);
          }else
            \Core\Logger::addMessage("Cannot add pattern ".$route_name." : ".implode(",", $route), "error", "dev");
        }

        if(empty($this->_routes)){
          \Core\Logger::addMessage("No route found in application.", "error", "dev");
          \Core\Logger::prettyPrintMessages();
          throw new \Exception("No route found in application.");
        }
      }
      catch(\Exception $e)
      {
        \Core\Logger::addMessage("Impossible to fetch routes from URL".$e->getMessage(), "error", "dev");
        \Core\Logger::prettyPrintMessages();
        throw new \Exception("Impossible to fetch routes from URL".$e->getMessage());
      }
    }

    protected function getPattern($p){
      return '/^' . str_replace('/', '\/', $p) . '$/';//update regex pattern
    }

    protected function getControllerName($c){
      return ucfirst(strtolower($c));
    }

    protected function getActionName($a){
      return ucfirst(strtolower($a));
    }

    protected function getCacheDuration($r){
      if(!isset($r["cache_seconds"]) || !is_int(intval($r["cache_seconds"])) || $r["cache_seconds"] < 0)
        return 0;
      else
        return intval($r["cache_seconds"]);
    }

    //get route for URL
    public function execute($uri) {
        $uri = str_replace(\App\Config::SCRIPT_FOLDER, "", $uri);
        foreach ($this->_routes as $r) {
            //echo "Check route ".$pattern." against ".$uri."<br />";
            if (preg_match($r["regex_pattern"], $uri, $r["params"]) === 1) {
                //remove first value of preg_match output
                array_shift($r["params"]);
                return $r;
            }
        }
        //return default controller
        return ["controller" => \App\Config::ERROR_CONTROLLER_NAME, "action" => \App\Config::ERROR_CONTROLLER_ACTION, "params"=>  \App\Config::ERROR_CONTROLLER_PARAMS, "cache_seconds" => \App\Config::ERROR_CONTROLLER_CACHE];
    }

}

?>
