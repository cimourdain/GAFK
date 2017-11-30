<?php

namespace Core;

class FilesManager{

  public function createDirIfDoesNotExists($dir){
    //create dir if does not exists
    if (!file_exists($dir)) {
      mkdir($dir, 0777, true);
    }
  }

  public function deleteAllFromDir($dir, $rm_dir = false){
    //delete all files existing in dir
    if (file_exists($dir)) {
      $files = glob($dir.'*'); // get all file names
      foreach($files as $file){ // iterate files
        if(is_file($file))
          unlink($file); // delete file
      }

      if($rm_dir)
        rmdir($dir);
    }
  }


}


?>
