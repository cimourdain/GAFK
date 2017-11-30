<?php

namespace Core;

abstract class Controller{
  use TLoggedClass;

  protected $_action = null;
  protected $_params = array();
  protected $_html = "";

  /* Build controller with action & route params */
  public function __construct($route, $logger = null){
    $this->setLogger($logger);
    $this->addMessage("Enter controller", "info", "dev");
    $this->setAction($route["action"]);
    $this->setParams($route["params"]);

  }

  protected function before(){

  }

  protected function after(){

  }

  /* execute action method (+ before & after function) */
  public function execute($cache = null){
      $this->before();
      $function = "execute".ucfirst($this->_action);
      $this->$function();
      $this->after();
      $this->render($cache);
  }

  //render : set HTML output to cache if necessary && render output
  protected function render($cache = null){
    $this->addMessage("Update cache file", "info", "dev");
    if($cache != null && $cache->isCacheRequired() && !$cache->isCacheAvailable())
      $cache->updateCache($this->getHTML());

    echo $this->getHTML();
  }

  /* define action */
  protected function setAction($action = "Index"){
    if(!empty($action))
      $this->_action = $action;
    else
      $this->_action = "Index";
  }

  /* define params */
  protected function setParams($params = []){
    if(is_array($params) && !empty($params))
      $this->_params = $params;
    else
      $this->_params = [];
  }

  //set HTML output
  protected function setHTML($html){
    $this->_html = $html;
  }

  //get HTML output
  protected function getHTML(){
    return $this->_html;
  }


}

 ?>
