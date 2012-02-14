<?php

class Zwe_Controller_Plugin_AutoLogin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = $this->getRequest()->getControllerName();
        if(!Zwe_Auth::getInstance()->hasIdentity() && $_COOKIE['login'] != '' && $controller != 'login' && $controller != 'error') {
            $url = $this->getRequest()->getBaseUrl() . '/' . Zend_Registry::getInstance()->parameters->registry->defaultLanguage . '/login/autologin';
            $this->_response->setRedirect($url);
            $this->_response->sendHeaders();
        }
    }
}