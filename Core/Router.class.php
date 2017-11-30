<?php

namespace Core;

class Router {
    use TLoggedClass;

    private $_routes = array();

    public function __construct($logger = null){
      $this->setLogger($logger);
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
            $route["regex_pattern"] =  '/^' . str_replace('/', '\/', $route["regex_pattern"]) . '$/';//update regex pattern
            $route["controller"] = ucfirst(strtolower($route["controller"]));
            $route["action"] = ucfirst(strtolower($route["action"]));
            array_push( $this->_routes, $route);
          }else
            $this->addMessage("Cannot add pattern ".$route_name." : ".implode(",", $route), "error", "dev");
        }

        if(empty($this->_routes)){
          $this->addMessage("No route found in application.", "error", "dev");
          $this->prettyPrintMessages();
          throw new \Exception("No route found in application.");
        }
      }
      catch(\Exception $e)
      {
        $this->addMessage("Impossible to fetch routes from URL".$e->getMessage(), "error", "dev");
        $this->prettyPrintMessages();
        throw new \Exception("Impossible to fetch routes from URL".$e->getMessage());
      }
    }

    //get route for URL
    public function execute($uri) {
        $uri = str_replace(\App\Config::SCRIPT_FOLDER, "", $uri);
        foreach ($this->_routes as $r) {
            //echo "Check route ".$pattern." against ".$uri."<br />";
            if (preg_match($r["regex_pattern"], $uri, $params) === 1) {
                //remove first value of preg_match output
                array_shift($params);
                return ["controller" => $r["controller"], "action" => $r["action"], "params"=>$params];
            }
        }
        return ["controller" => "Error", "action" => "Index", "params"=>[]];
    }

}

?>
