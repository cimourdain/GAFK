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
    $this->_logger = new \Logger();
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

}

 ?>
