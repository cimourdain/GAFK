<?php

namespace core;

trait TLoggedClass
{
  protected $_logger = null;

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

  /* Method to add Messages to Logger */
  protected function addMessage($message, $type = "info", $level = "dev"){
      if($this->loggerDefined())
          $this->_logger->AddMessage($message, $type, $level);
  }

}


?>
