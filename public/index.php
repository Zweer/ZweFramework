<?php

/**
 * @file public/index.php
 * This is the file from where the application starts.
 * It sets the main variables and then starts the application.
 *
 * @category    Site
 * @package     Index
 */

/**
 * It defines the url of the site.
 */
defined('SITE_URL') || define('SITE_URL', $_SERVER['HTTP_HOST']);

/**
 * It defines the application path.
 * It is "/application"
 */
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/**
 * It defines the cache path.
 * It is "/cache"
 */
defined('CACHE_PATH') || define('CACHE_PATH', realpath(dirname(__FILE__) . '/../cache'));

/**
 * It defines the language path.
 * It is "/language"
 */
defined('LANGUAGE_PATH') || define('LANGUAGE_PATH', realpath(dirname(__FILE__) . '/../language'));

/**
 * It defines the library path.
 * It is "/library"
 */
defined('LIBRARY_PATH') || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

/**
 * It defines the log path.
 * It is "/log"
 */
defined('LOG_PATH') || define('LOG_PATH', realpath(dirname(__FILE__) . '/../log'));

/**
 * It defines the public path.
 * It is "/public"
 */
defined('PUBLIC_PATH') || define('PUBLIC_PATH', dirname(__FILE__));

/**
 * It defines the temporary directory path.
 * It is "/temp"
 */
defined('TEMP_PATH') || define('TEMP_PATH', realpath(dirname(__FILE__) . '/../temp'));

/**
 * It defines the environment of the application.
 * It is taken directly from the htaccess file.
 * If it is not found, it is set to "production".
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

/**
 * It defines the inclusion path.
 * It adds "/library" to what already exist.
 */
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH . '/../library'), get_include_path())));

/**
 * @uses        Zend/Application.php
 */
require_once 'Zend/Application.php';

/**
 * It creates the application and runs it.
 * The configuration file chosen is "/application/configs/application.ini"
 */
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap()->run();

?>