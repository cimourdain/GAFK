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

    /* Error controller cache duraction*/
    const ERROR_CONTROLLER_CACHE = 0;


    /* Maintenance */
    const MAINTENANCE_ACTIVE = true;
    const MAINTENANCE_CONTROLLER = "Maintenance";
    const MAINTENANCE_ACTION = "Index";
    const MAINTENANCE_CACHE_DURATION_SECONDS = 20;
}

?>
