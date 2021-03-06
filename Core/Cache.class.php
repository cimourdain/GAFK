<?php

namespace Core;

class Cache{
  protected $_route = [];
  protected $_cache_file = null;

  public function __construct($route){
    $this->setRoute($route);
    $this->setCacheFile();
  }

  protected function setRoute($r){
    $this->_route = $r;
  }

  public function isCacheRequired(){
    if($this->_route["cache_seconds"] > 0)
      return true;
    return false;
  }

  public function getCacheFolder(){
    $folder = __DIR__."/../App/Cache/".$this->_route["controller"];
    if(!is_dir($folder)){
      if(!mkdir($folder, 0777, true)){
        \Core\Logger::addMessage("Impossible to create cache folder, check permissions", "error", "dev");
        return null;
      }
    }
    return $folder;
  }

  protected function getCacheFileName(){
    $param_str= "";
    if(!empty($this->_route["params"]))
      $param_str = "-".implode("-",$this->_route["params"]);
    return $this->_route["action"].$param_str.".html";
  }

  protected function setCacheFile(){
    if($this->isCacheRequired() && $folder = $this->getCacheFolder()){
      $file_path = $folder.'/'.$this->getCacheFileName();
      \Core\Logger::addMessage("Check if cache file ".$file_path." exists.", "info", "dev");
      //\Core\Logger::addMessage("Cache file age ".(time() - date (filemtime($file_path)))." seconds");
      if(file_exists($file_path) && (time() - date (filemtime($file_path))) < $this->_route["cache_seconds"]){
          \Core\Logger::addMessage("Cache file ".$file_path." exists.", "info", "dev");
          $this->_cache_file = $file_path;
          return;
        }
    }
    $this->_cache_file = null;
  }

  public function isCacheAvailable(){
    if($this->_cache_file != null ){
      \Core\Logger::addMessage("Cache file available.", "info", "dev");
      return true;
    }
    return false;
  }

  public function renderCache(){
    $cache_html = file_get_contents($this->_cache_file);
    echo '<!--Cached version -->'.$cache_html;
  }

  public function updateCache($html){
    \Core\Logger::addMessage("Update cache file.", "info", "dev");
    if($folder = $this->getCacheFolder()){
      try{
        $file_path = $folder.'/'.$this->getCacheFileName();
        $cache_file = fopen($file_path, 'w');
        fwrite($cache_file, $html);
        fclose($cache_file);
      }
      catch(Exception $e){
        error_log($e->getMessage()."\n", 3, \App\Config::ERROR_LOG_FILE);
      }
    }
  }
}

?>
