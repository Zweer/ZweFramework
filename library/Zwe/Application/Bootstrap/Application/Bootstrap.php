<?php

/**
 * @file application/Bootstrap.php
 * It is the Bootstrap class that initialized the application.
 *
 * @category    application
 * @package     Default
 * @subpackage  Bootstrap
 */

/**
 * It controls the initialization of the application.
 * It runs all the functions that begin with "_init".
 *
 * @uses        Zend_Application_Bootstrap_Bootstrap
 * @category    Site
 * @package     Default
 * @subpackage  Bootstrap
 */
class Zwe_Application_Bootstrap_Application_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Automatically adds the missing directories in/out the "application" tree.
     *
     * They could be missing because of "git" that doesn't version the empty ones
     */
    protected function _initMissingDirectories()
    {
        $directories = array('configs', 'controllers', 'modules', 'views');

        foreach($directories as $directory) {
            if(!is_dir(APPLICATION_PATH . '/' . $directory))
                @mkdir(APPLICATION_PATH . '/' . $directory);
        }

        $directories = array('cache', 'log');

        foreach($directories as $directory) {
            if(!is_dir(dirname(APPLICATION_PATH) . '/' . $directory))
                @mkdir(dirname(APPLICATION_PATH) . '/' . $directory);
        }
    }

    /**
     * If magic_quotes are added, it removes them from $_GET, $_POST, $_COOKIE and $_REQUEST
     */
    protected function _initStripSlashes()
    {
        if(get_magic_quotes_gpc()) {
            function stripslashes_gpc(&$Value) {
                $Value = stripslashes($Value);
            }

            $GPCR = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            array_walk_recursive($GPCR, 'stripslashes_gpc');
        }
    }

    /**
     * Adds auto-loading library classes.
     * It opens the library directory and fetches all the libraries (obviously except '.', '..' and 'Zend')
     *
     * @return Zend_Application_Module_Autoloader
     */
	protected function _initAppAutoload()
	{
		$Autoloader = new Zend_Application_Module_Autoloader(array('namespace' => 'App',
			                                                       'basePath' => APPLICATION_PATH));
        $Dir = dirname(APPLICATION_PATH) . '/library/';
        $D = opendir($Dir);
        while($LibraryDir = readdir($D))
            if($LibraryDir != '.' && $LibraryDir != '..' && $LibraryDir != 'Zend')
                $Autoloader->addResourceType(strtolower($LibraryDir), dirname(APPLICATION_PATH) . '/library/' . $LibraryDir, $LibraryDir);

		return $Autoloader;
	}

    /**
     * Initializes the Cache and stores it in the Registry.
     */
    protected function _initCache()
    {
        $cacheManager = $this->bootstrap('cachemanager')->getResource('cachemanager');
        Zend_Registry::set('Zend_Cache', $cacheManager->getCache('coreCache'));
    }

    protected function _initLogger()
    {
        $log = new Zend_Log();
        $log->addPriority('TRANSLATE', 8);

        $this->bootstrap('db');
        $writerTranslate = new Zend_Log_Writer_Db(Zend_Db_Table_Abstract::getDefaultAdapter(), 'log', array('Priority' => 'priority', 'Message' => 'message', 'Date' => 'timestamp'));
        $filterTranslateDb = new Zwe_Log_Filter_Db_NoRecordExists(Zend_Db_Table_Abstract::getDefaultAdapter(), 'log', array('Message' => 'message'));
        $filterTranslatePriority = new Zend_Log_Filter_Priority(8, '==');
        $writerTranslate->addFilter($filterTranslateDb);
        $writerTranslate->addFilter($filterTranslatePriority);
        $log->addWriter($writerTranslate);

        $writerDebug = new Zwe_Log_Writer_Syslog(LOG_PATH . '/debug.log');
        $filterDebug = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
        $writerDebug->addFilter($filterDebug);
        $log->addWriter($writerDebug);

        $log->setTimestampFormat("Y-m-d H:i:s");

        Zend_Registry::set('Zend_Log', $log);
    }

    /**
     * Takes the site parameters from the config file.
     * If in the siteUrl param there is "localhost", it substitutes it with the ip address of the machine.
     *
     * @return Zend_Config_Ini
     */
    protected function _initParameters()
    {
        $Config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/parameters.ini', null, array('allowModifications' => true));
        $Config->registry->siteUrl = preg_replace('/localhost/', $_SERVER["HTTP_HOST"], $Config->registry->siteUrl);
        $Config->setReadOnly();
        Zend_Registry::set('parameters', $Config);

        return $Config;
    }

    /**
     * Initializes the Translate and the Locale
     */
    protected function _initTranslate()
    {
        $translate = new Zend_Translate('array',
                                        LANGUAGE_PATH,
                                        Zend_Registry::get('parameters')->registry->defaultLanguage,
                                        array('scan' => Zend_Translate::LOCALE_DIRECTORY));
        $translate->setOptions(array(
            'log'               => Zend_Registry::get('Zend_Log'),
            'logUntranslated'   => true,
            'logMessage'        => '(%locale%) %message%',
            'logPriority'       => 8
        ));

        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Registry::set('Zend_Locale', new Zend_Locale(Zend_Registry::get('parameters')->registry->defaultLanguage));
    }

    /**
     * Initializes the view parameters.
     * It sets the following params:
     * - helper path;
     * - site charset;
     * - site title with its separator.
     */
	protected function _initMyView()
	{
		$this->_bootstrap('view');
		$View = $this->getResource('view');

		$View->addHelperPath('Zwe/View/Helper', 'Zwe_View_Helper');

        $View->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

        $View->headLink()->headLink(array(
            'rel' => 'shortcut icon',
            'type' => 'image/ico',
            'href' => $View->baseUrl('favicon.ico')));

        $View->headTitle(Zend_Registry::get('parameters')->registry->siteTitle)->setSeparator(' :: ');
	}

    /**
     * Sets the layout.
     */
	protected function _initLayoutHelper()
	{
		$this->bootstrap('frontController');
		Zend_Controller_Action_HelperBroker::addHelper(new Zwe_Controller_Action_Helper_LayoutLoader());
	}

    /**
     * Sets the routing, taking it from the ini file.
     * If the routing config file doesn't exist, it will exit.
     */
    protected function _initRoutingFromConfig()
    {
        Zend_Controller_Front::getInstance()->setBaseUrl(Zend_Registry::get('parameters')->registry->baseUrl);

        # default routes
        $this->_routingFromConfig();

        $dir = opendir(APPLICATION_PATH . '/modules');
        while($module = readdir($dir)) {
            if($module != '.' && $module != '..')
                $this->_routingFromConfig('/modules/' . $module);
        }
    }

    protected function _routingFromConfig($directory = '')
    {
        $configFile = APPLICATION_PATH . $directory . '/configs/routes.ini';

        if(!file_exists($configFile))
            return;

        $config = new Zend_Config_Ini($configFile, 'production');
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addConfig($config, 'routes');
    }

    /**
     * Sets the navigation menu, taking it from the ini file.
     * If the navigation config file doesn't exist, it will create a dummy one.
     */
    protected function _initNavigation()
    {
        $configFile = APPLICATION_PATH . '/configs/navigation/application.ini';
        $pages = null;

        if(file_exists($configFile)) {
            $config = new Zend_Config_Ini($configFile, 'production');
            $pages = $config->get('navigation');
        } else {
            $pages = array(
                array('label' => 'Home',
                      'module' => 'default',
                      'controller' => 'index',
                      'action' => 'index',
                      'order' => -100)
            );
        }

        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $navigation = new Zend_Navigation($pages);

        Zend_Registry::set('Zend_Navigation', $navigation);
        $view->navigation($navigation);
    }
}

