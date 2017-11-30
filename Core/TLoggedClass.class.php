<?php

namespace Core;

trait TLoggedClass
{
  protected $_logger = null;

  /* SETTERS */
  /* Method to set logger */
  protected function setLogger($l){
      if($l instanceof \Core\Logger)
          $this->_logger = $l;
  }


  /* GETTERS */
  /* Method to check if logger is defined */
  protected function loggerDefined(){
      if($this->_logger instanceof \Core\Logger)
          return true;
      return false;
  }

  /* Method to add Messages to Logger */
  protected function addMessage($message, $type = "info", $level = "dev"){
      if($this->loggerDefined())
          $this->_logger->AddMessage($message, $type, $level);
  }

  protected function getMessages($types = ["success", "info", "error"], $levels = ["user", "dev"]){
    if($this->loggerDefined()){
        return $this->_logger->getMessages($types, $levels);
    }
    return [];
  }

  protected function prettyPrintMessages($types = ["success", "info", "error"], $levels = ["user", "dev"]){
      $m = $this->getMessages($types, $levels);
      print("<pre>".print_r($m)."</pre>");
  }

}


?>
