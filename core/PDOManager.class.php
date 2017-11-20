<?php

namespace core;
use PDO;
class PDOManager{

    protected $_pdo = null;
    protected $_logger = null;

    public function __construct($logger = null){
        $this->setLogger($logger);
        $this->connect_db();
    }

    /* Method to connect DB */
    protected function connect_db(){

        try{
            $dsn = "mysql:host=".\App\Config::DB_HOST.";dbname=".\App\Config::DB_NAME.";charset=".\App\Config::DB_CHARSET.";port=".\App\Config::DB_PORT;
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->_pdo = new \PDO($dsn, \App\Config::DB_USER, \App\Config::DB_PASSWORD, $opt);
            $this->addMessage("Connection to DB successful.", "success", "dev");
            $this->_connected = true;
        }
        catch (PDOException $e) {
            error_log($e->getMessage()."\n", 3, \App\Config::ERROR_LOG_FILE);

        }

    }

    /* Method to execute PDO request (with try/catch) */
    public function executePDO($s, $data){
        try{
            $s->execute($data);
            return $s;
        }
        catch (PDOException $e) {
            error_log($e->getMessage()."\n", 3, \App\Config::ERROR_LOG_FILE);
            $this->addMessage("Error performing request in DB ".$e->getMessage(), "error", "dev");
            return null;
        }
    }

    /* Method to add Messages to Logger */
    protected function addMessage($message, $type = "info", $level = "dev"){
        if($this->loggerDefined())
            $this->_logger->AddMessage($message, $type, $level);
    }

    /* SETTERS */
    /* Method to set logger */
    protected function setLogger($l){
        //if($l instanceof \core\Logger)
            $this->_logger = $l;
    }


    /* GETTERS */
    /* Method to check if logger is defined */
    protected function loggerDefined(){
        if($this->_logger != null)
            return true;

        return false;
    }

    /* Method to define if DB is connected */
    public function isConnected(){
        if($this->_pdo != null)
            return true;
        return false;
    }

}

?>
