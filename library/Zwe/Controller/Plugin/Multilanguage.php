<?php

class Zwe_Controller_Plugin_Multilanguage extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $locale = Zend_Registry::get('Zend_Locale');
        $router = Zend_Controller_Front::getInstance()->getRouter()->addDefaultRoutes();
        $routeLang = new Zend_Controller_Router_Route(':language', array('language' => $locale->getLanguage()), array('language' => '[a-z]{2}'));
        $default = $router->getRoute('default');

        foreach($router->getRoutes() as $name => $route) {
            $router->addRoute($name, $routeLang->chain($route));
        }

        $router->addRoute('nolang_default', $default);
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $language = $request->getParam('language');
        if(!isset($language))
            $language = Zend_Registry::getInstance()->parameters->registry->defaultLanguage;
        $this->_setLanguage($language);
    }

    protected function _setLanguage($language)
    {
        $translate = Zend_Registry::get('Zend_Translate');

        if($translate->isAvailable($language))
            $translate->setLocale($language);
        else {
            $locale = $translate->getLocale();
            if($language instanceof Zend_Locale)
                $language = $locale->getLanguage();
            else
                $language = $locale;
        }

        Zend_Controller_Front::getInstance()->getRouter()->setGlobalParam('language', $language);
    }
}