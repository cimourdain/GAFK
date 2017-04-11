<?php

namespace GAFK;

class ErrorExceptionGAFK extends \ErrorException
{
  public function __toString()
  {
    switch ($this->severity)
    {
      case E_USER_ERROR :
        $type = 'Fatal Error';
        break;
      
      case E_WARNING : 
      case E_USER_WARNING : 
        $type = 'Warning';
        break;
      
      case E_NOTICE : 
      case E_USER_NOTICE : 
        $type = 'Note';
        break;
      
      default : 
        $type = 'Unknown';
        break;
    }
    
    return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> on line <strong>' . $this->line . '</strong>';
  }
}



