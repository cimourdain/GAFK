<?php





require __DIR__.'/../lib/vendor/autoload.php';

require __DIR__.'/../lib/GAFK/Psr4AutoloaderClass.php';

$loader = new \GAFK\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('GAFK', __DIR__.'/../lib/GAFK/');


function error2exception($code, $message, $file, $line)
{
  throw new \GAFK\ErrorExceptionGAFK($message, 0, $code, $file, $line);
}

function customException($e)
{
  echo 'Custom Exception => Line ', $e->getLine(), ' in ', $e->getFile(), '<br /><strong>Exception lanched</strong> : ', $e->getMessage();
}

set_error_handler('error2exception');
//set_exception_handler('customException');


//load controller
$controller = new GAFK\Controller($loader);

$controller -> setRouter();
$controller -> setRoute();
$controller -> setManager();

$controller -> getManager() -> execute();
$controller -> renderOutput();


/*
//get module
$module =  $controller -> getRoute() -> getModule();
$type = $controller -> getRoute() -> getType();
$action = $controller -> getRoute() -> getAction();

echo '<hr />Log ======================<br />';
echo 'Subdomain identified :'.$controller -> getRouter() -> getSubdomain().'<br />';
echo 'Open Module :'.$module.'<br />';
echo 'Type :'.$type.'<br />';
echo 'Action :'.$action.'<br />';
echo 'Execute Action: '.$controller -> getRoute() -> getAction().'<br />';*/

?>