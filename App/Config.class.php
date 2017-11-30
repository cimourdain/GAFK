<?php

namespace App;

class Config{
    const WEBSITE_ADDRESS = 'http://localhost/';
    const SCRIPT_FOLDER = 'rendu/GAFK/';
    const BASE_URL = self::WEBSITE_ADDRESS.self::SCRIPT_FOLDER;
    const TIMEZONE = 'Europe/Paris';
    
    /* MAIN */
    const SITE_NAME = "My website";
    const AUTHOR = "My Name";

    /* DEFINE ERROR LOG FILE */
    const ERROR_LOG_FILE = "error.log";

    /* DB PARAMS */
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = '';
    const DB_PORT = '3306';
    const DB_CHARSET = 'utf8';

    /*CACHE*/
    const ERROR_CONTROLLER_CACHE = 0;
}

?>
