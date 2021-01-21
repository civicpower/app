<?php
/**
 * Initial Libs Loading
 * @access      public
 * @param       -
 * @return      -
 * @author      C2
 * @apache      include the php conf via
 *      php_value auto_prepend_file includes/auto_prepend_file.php
 *      php_value auto_append_file includes/auto_append_file.php
 */

    // Vive la France !
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
        ini_set('default_charset', 'utf-8');

    // Load Composer's libs
        if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../vendor/autoload.php"))) {
            include( str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../vendor/autoload.php") );
        }

    // Load global env
        if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."config/"))) {
                $dotenv = Dotenv\Dotenv::createImmutable(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."config/"));
                $dotenv->load();
        }

    // Load local env
        if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."config/".explode(".",$_SERVER['SERVER_NAME'])[0].".env"))) {
            $dotenv = Dotenv\Dotenv::createImmutable(
                str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../"."config/")
                ,explode(".",$_SERVER['SERVER_NAME'])[0].".env"
            );
            $dotenv->load();
        }

    // Load Civicpower's toolbox
        if (file_exists(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../inc/outils.php"))) {
            if (!include(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../inc/outils.php"))) {
                header('Location: '.$_ENV['URL_CIVICPOWER']);
            }
        }

    // Debug in file
        ini_set('error_log', ( ( isset($_ENV['LOG_ERROR'])&&($_ENV['LOG_ERROR']<>"") ) ? $_ENV['LOG_ERROR'] : false));

    // ERROR_SHOW_LENGTH
        if(!isset($_ENV['ERROR_SHOW_LENGTH'])){
            $_ENV['ERROR_SHOW_LENGTH'] = 1.5 * 60 * 60;
        }

    // REP_ROOT
        if(!isset($_ENV['REP_ROOT'])){
            $_ENV['REP_ROOT'] = preg_replace("~^\/data~","",realpath(dirname(__FILE__)  . '/../..'));
        }

    // Smarty's HTML root
        if(!isset($_ENV['HTML_ROOT'])){
            $_ENV['HTML_ROOT'] = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../html/");
            $GLOBALS['HTML_ROOT'] = $_ENV['HTML_ROOT'];
        }
?>