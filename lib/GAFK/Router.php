<?php
namespace GAFK;

/*****************************

Router Class is used by Controller to find the route matching url

******************************/

class Router{

	use CommonFunctions;

	protected $route_xml_file; //content of route xml file
	protected $subdomain;//route url subdomain
	protected $route; // route marching url
	protected $route_vars; //route vars


	/*********************************
	*#Purpose
	*Get content of xml Routes File 
 	*
	*#Params
	*	- $xml_file: xml routes file adress
	*
 	*#Actions
 	*	- get content of xml Routes file (default (App/Config/Routes.xml) 
 	*   - store result in route_xml_file value
	*
	*@return void
	**********************************/
	public function __construct($xml_file = __DIR__.'/../../App/_Config/Routes.xml')
	{
		$this -> route_xml_file = new \SimpleXMLElement($xml_file, NULL, TRUE);
	}


	/*********************************
	*#Purpose
	*Define subdomain value from app_host (site main url), and http_host (current url = $_SERVER['HTTP_HOST'])
	**********************************/
	public function setSubdomain($app_host, $http_host)
	{
		$this -> subdomain = substr(str_replace($app_host, "", $http_host), 0 , -1);
	}

	/*********************************
	*#Purpose
	*Set route from url
 	*
	*#Params
	* - $app_host: Website main adress (host value from Application object)
	* - $http_host : value of $_SERVER['HTTP_HOST'] (provided by HTTPRequests Class)
	* - $url : value of $_SERVER['REQUEST_URI'] (provided by HTTPRequests Class)
	*
 	*#Actions
 	* - define subdomain (setSubdomain method)
 	* - Search for backend & frontend route (call ParseRtoutes method)
 	* - Define $this -> route depending of previous result
	*
	*@return route object 
	**********************************/


	public function setRoute($app_host, $http_host, $url)
	{

		$this -> setSubdomain($app_host, $http_host);

		$backend_route = $this -> parseRoutes($this -> route_xml_file -> backend -> subdomain, 'Backend',  $url);
		$frontend_route = $this -> parseRoutes($this -> route_xml_file -> frontend -> subdomain, 'Frontend', $url);
		
		if(!is_null($backend_route))
		{
			$this -> route = $backend_route;
		}
		else if(!is_null($frontend_route))
		{
			$this -> route = $frontend_route;
		}else
		{
			$this -> route = new Route();//define new route with default values (> error module)
		}
		return $this -> route;
	}

	/*********************************
	*#Purpose
	*parse XML values to find route matching url
 	*
	*#Params
	* - $xml_section: Section of the XML file to analyse (backend or frontend)
	* - $type : type of Route to output (backend or frontend)
	* - $subdomain : subdomain to search in routes file
	* - $url: url to march with routes patterns
	*
 	*#Actions
 	* - parse XML sudomains tags
 	* - if subdomain tag value matches $subdomain from params
 	* 	- parse all routes tags for this subdomain
 	*	- if route tag URLPattern matches url
 	*     > return new Route object 
	*
	*@return route object if found, else return null
	**********************************/

	private function parseRoutes($xml_section, $type, $url)
	{
		foreach($xml_section as $routes_sub)
		{
			$sub_list = explode(',', (string)$routes_sub['value']);
			if(in_array($this -> subdomain, $sub_list))
			{
				foreach($routes_sub -> route as $route)
				{
					if(preg_match('`^'.$route['URLPattern'].'\/?$`', $url, $matches))
					{
						if(isset($route['vars']) && !empty($route['vars']) && count($matches) > 1)
						{
							$this -> route_vars = $this -> matchUrlVars(explode(',', str_replace(' ', '', $route['vars'])), $matches);
						}else
						{
							$this -> route_vars = [];
						}
						return new Route((string)$route['module'], (string)$type, (string) $route['action'], $this -> route_vars);
					}
				}
			}
		}
		return null;
	}

	private function matchUrlVars($vars, $matches)
	{
		array_shift($matches);
		return $this -> array_combine_diff_sizes($vars, $matches);
	}


	/* GETTERS */
	public function getSubdomain()
	{
		return (isset($this -> subdomain)) ? ucfirst($this -> subdomain) : null;
	}
}

?>