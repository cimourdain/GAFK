<?php

namespace Core;

class Logger{
    protected static $_messages = array();

    private function __construct(){

    }

    /* method to add message */
    public static function AddMessage($message, $type = \App\Config::LOG_TYPE_DEFAULT, $level = \App\Config::LOG_LEVEL_DEFAULT){
        $m = ["message" => $message, "type" => $type, "level" => $level];
        if(!in_array($m, self::$_messages))
            array_push(self::$_messages, $m);
    }

    /* method to get messages */
    public static function getMessages($types = \App\Config::LOG_TYPES, $levels = \App\Config::LOG_LEVELS){
        $messages = array();
        foreach(self::$_messages as $m){
          if(in_array($m["type"], $types) && in_array($m["level"], $levels))
            array_push($messages, $m);
        }
        return $messages;
    }

    /* method to get  messages as HTML string */
    public static function getMessagesHTML(){
        $html = '<div class="messages">';
        foreach(self::$_messages as $m){
            $html .= '<div>'.$m["type"].' - '.$m["message"].'</div>';
        }
        return $html."</div>";
    }

    /* method to get pretty print messages */
    protected static function prettyPrintMessages($types = \App\Config::LOG_TYPES, $levels = \App\Config::LOG_LEVELS){
        $m = self::getMessages($types, $levels);
        print("<pre>".print_r($m, true)."</pre>");
    }

}

?>
