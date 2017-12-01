<?php

namespace Core;

class Logger{
    protected $_messages = array();

    public function __construct(){

    }

    public function AddMessage($message, $type = \App\Config::LOG_TYPE_DEFAULT, $level = \App\Config::LOG_LEVEL_DEFAULT){
        $m = ["message" => $message, "type" => $type, "level" => $level];
        if(!in_array($m, $this->_messages))
            array_push($this->_messages, $m);
    }

    public function getMessages($types = \App\Config::LOG_TYPES, $levels = \App\Config::LOG_LEVELS){
        $messages = array();
        foreach($this->_messages as $m){
          if(in_array($m["type"], $types) && in_array($m["level"], $levels))
            array_push($messages, $m);
        }
        return $messages;
    }


    public function getMessagesHTML(){
        $html = '<div class="messages">';
        foreach($this->_messages as $m){
            $html .= '<div>'.$m["type"].' - '.$m["message"].'</div>';
        }
        return $html."</div>";
    }

}

?>
