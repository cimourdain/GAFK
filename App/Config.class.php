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


    /* Logging types and values */
    const LOG_TYPES  =  ["success", "info", "error"];
    const LOG_TYPE_DEFAULT = "info";
    const LOG_LEVELS = ["user", "dev"];
    const LOG_LEVEL_DEFAULT = "info";

    /* Error (url non resolved by route) controller, action, params & cache duraction*/
    const ERROR_CONTROLLER_NAME = "Error";
    const ERROR_CONTROLLER_ACTION = "Index";
    const ERROR_CONTROLLER_PARAMS = [];
    const ERROR_CONTROLLER_CACHE = 0;


    /* Maintenance */
    const MAINTENANCE_ACTIVE = false;
    const MAINTENANCE_CONTROLLER = "Maintenance";
    const MAINTENANCE_ACTION = "Index";
    const MAINTENANCE_CACHE_DURATION_SECONDS = 20;
}

?>
