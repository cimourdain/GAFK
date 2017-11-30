<?php

namespace Core;
use PDO;

abstract class PDOManager{
    use TLoggedClass;

    protected static $_pdo = null;

    public function __construct($logger = null){
        $this->setLogger($logger);
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

            self::$_pdo = new \PDO($dsn, \App\Config::DB_USER, \App\Config::DB_PASSWORD, $opt);
            $this->addMessage("Connection to DB successful.", "success", "dev");
        }
        catch (PDOException $e) {
            error_log($e->getMessage()."\n", 3, \App\Config::ERROR_LOG_FILE);

        }

    }

    /* Method to define if DB is connected */
    public static function isConnected(){
        if(self::$_pdo != null && self::$_pdo->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS")) != null)
            return true;
        return false;
    }

    /* Method to execute PDO request (with try/catch) */
    protected function executePDO($sql, $data = []){
      //connect to DB if not already done
      if(!self::isConnected())
        self::connect_db();

      if(self::isConnected()){
        try{
            if(empty($data))
              $s = self::$_pdo->query($sql);
            else{
              $s = self::$_pdo->prepare($sql);
              $s->execute($data);
            }
            return $s;
        }
        catch (PDOException $e) {
            error_log($e->getMessage()."\n", 3, \App\Config::ERROR_LOG_FILE);
            $this->addMessage("Error performing request in DB ".$e->getMessage(), "error", "dev");
            return null;
        }
      }
      $this->addMessage("Database not connected", "error", "dev");
      return null;
    }
}

?>
