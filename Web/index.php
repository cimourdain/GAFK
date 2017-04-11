<?php

require __DIR__.'/../lib/vendor/autoload.php';

require __DIR__.'/../lib/GAFK/Psr4AutoloaderClass.php';


$loader = new \GAFK\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('GAFK', __DIR__.'/../lib/GAFK/');

//load controller
$controller = new GAFK\Controller($loader);



//get module
$module =  $controller -> getRoute() -> getModule();
$type = $controller -> getRoute() -> getType();
$action = $controller -> getRoute() -> getAction();

echo '<hr />Log ======================<br />';
echo 'Subdomain identified :'.$controller -> getRouter() -> getSubdomain().'<br />';
echo 'Open Module :'.$module.'<br />';
echo 'Type :'.$type.'<br />';
echo 'Action :'.$action.'<br />';
echo 'Execute Action: '.$controller -> getRoute() -> getAction().'<br />';








?>