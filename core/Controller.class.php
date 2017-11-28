<?php

namespace core;

abstract class Controller{
  protected $_action = null;
  protected $_params = array();
  protected $_logger = null;

  /* Build controller with action & route params */
  public function __construct($action = "Index", $params = array()){
    $this->setAction($action);
    $this->setParams($params);
    $this->setLogger(new \Logger());
  }

  /* execute action method (+ before & after function) */
  public function execute(){
    $this->before();
    $function = "execute".ucfirst($this->_action);
    $this->$function();
    $this->after();
  }

  /* define action */
  protected function setAction($action){
    $this->_action = $action;
  }

  /* define params */
  protected function setParams($params){
    $this->_params = $params;
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
