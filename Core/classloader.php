<?php

function load_classes($class){
  $class = str_replace("\\", "/", $class);
  require($class.".class.php");
}

spl_autoload_register('load_classes');
?>
