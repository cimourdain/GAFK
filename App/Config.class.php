<?php

namespace App;

class Config{
    const WEBSITE_ADDRESS = 'http://ca/';
    const SCRIPT_FOLDER = 'php_rush/';
    const BASE_URL = self::WEBSITE_ADDRESS.self::SCRIPT_FOLDER;

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

}

?>
