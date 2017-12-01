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
  protected function addMessage($message, $type = \App\Config::LOG_TYPE_DEFAULT, $level = \App\Config::LOG_LEVEL_DEFAULT){
      if($this->loggerDefined())
          $this->_logger->AddMessage($message, $type, $level);
  }

  protected function getMessages($types = \App\Config::LOG_TYPES, $levels = \App\Config::LOG_LEVELS){
    if($this->loggerDefined()){
        return $this->_logger->getMessages($types, $levels);
    }
    return [];
  }

  protected function prettyPrintMessages($types = \App\Config::LOG_TYPES, $levels = \App\Config::LOG_LEVELS){
      $m = $this->getMessages($types, $levels);
      print("<pre>".print_r($m, true)."</pre>");
  }

}


?>
