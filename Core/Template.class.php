<?php

namespace Core;

class Template{
    private static $_vars = ["base_url" => \App\Config::BASE_URL];
    private static $_templates_folder = __DIR__."/../App/Views/";

    private function __construct(){}

    public static function getStatic($name){
        return self::$_vars[$name];
    }

    public static function setStatic($name, $value){
        self::$_vars[$name] = $value;
    }

    private static function setTemplatesFolder($folder){
        if($folder != null)
            self::$_templates_folder = $folder;
    }

    public static function render($tpl_file){
        extract(self::$_vars);
        ob_start();
        include_once(self::$_templates_folder.$tpl_file);
        return ob_get_clean();
    }

}


?>
