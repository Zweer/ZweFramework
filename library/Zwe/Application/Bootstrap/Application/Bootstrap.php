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
     * If magic_quotes are added, it removes them from $_GET, $_POST, $_COOKIE and $_REQUEST
     */
    protected function _initStripSlashes()
    {
        if(get_magic_quotes_gpc())
        {
            function stripslashes_gpc(&$Value)
            {
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
        while($LibraryDir = opendir($D))
            if($LibraryDir != '.' && $LibraryDir != '..' && $LibraryDir != 'Zend')
                $Autoloader->addResourceType(strtolower($LibraryDir), dirname(APPLICATION_PATH) . '/library/' . $LibraryDir, $LibraryDir);

		return $Autoloader;
	}

    /**
     * Takes the site parameters from the config file.
     *
     * @return Zend_Config_Ini
     */
    protected function _initParameters()
    {
        $Config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/parameters.ini');
        Zend_Registry::set('parameters', $Config);

        return $Config;
    }

    /**
     * Initializes the Translate and the Locale
     */
    protected function _initTranslate()
    {
        Zend_Registry::set('Zend_Translate',
                           new Zend_Translate('array',
                                              realpath(APPLICATION_PATH . '/../language'),
                                              Zend_Registry::get('parameters')->registry->defaultLanguage,
                                              array('scan' => Zend_Translate::LOCALE_DIRECTORY)));
        Zend_Registry::set('Zend_Locale', new Zend_Locale());
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
        $View->headMeta()->appendHttpEquiv('Content-Style-Type', 'text/css');
        $View->headMeta()->appendHttpEquiv('Content-Script-Type', 'text/javascript');
        $View->headMeta()->appendHttpEquiv('imagetoolbar', 'no');

        $View->headLink()->headLink(array(
            'rel' => 'favicon',
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
        $ConfigFile = APPLICATION_PATH . '/configs/routes.ini';

        if(!file_exists($ConfigFile))
            return;

        $Config = new Zend_Config_Ini($ConfigFile, 'production');
        $Router = Zend_Controller_Front::getInstance()->getRouter();

        $Router->addConfig($Config, 'routes');
    }
}

