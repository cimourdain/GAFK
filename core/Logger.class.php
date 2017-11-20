<?php



class Logger{
    protected $_messages = array();

    public function AddMessage($message, $type = "info", $level = "dev"){
        $m = ["message" => $message, "type" => $type, "level" => $level];
        if(!in_array($m, $this->_messages))
            array_push($this->_messages, $m);
    }

    public function getMessages($types = ["success", "info", "error"], $levels = ["user", "dev"]){
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
