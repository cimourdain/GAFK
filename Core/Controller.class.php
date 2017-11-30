<?php

namespace Core;

abstract class Controller{
  use TLoggedClass;

  protected $_action = null;
  protected $_params = array();

  /* Build controller with action & route params */
  public function __construct($route, $logger = null){
    $this->setLogger($logger);
    $this->addMessage("Enter controller", "info", "dev");
    $this->setAction($route["action"]);
    $this->setParams($route["params"]);

  }

  /* execute action method (+ before & after function) */
  public function execute(){
    $this->before();
    $function = "execute".ucfirst($this->_action);
    $this->$function();
    $this->after();
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


  protected function before(){

  }

  protected function after(){

  }

  /* Method to add Messages to Logger */
  protected function addMessage($message, $type = "info", $level = "dev"){
      if($this->loggerDefined())
          $this->_logger->AddMessage($message, $type, $level);
  }

  /* SETTERS */
  /* Method to set logger */
  protected function setLogger($l){
      if($l instanceof \Logger)
          $this->_logger = $l;
  }


  /* GETTERS */
  /* Method to check if logger is defined */
  protected function loggerDefined(){
      if($this->_logger != null)
          return true;

      return false;
  }


}

 ?>
