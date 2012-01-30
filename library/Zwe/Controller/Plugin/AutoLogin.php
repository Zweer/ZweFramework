<?php

class Zwe_Controller_Plugin_AutoLogin extends Zend_Controller_Plugin_Abstract
{
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if(!Zend_Auth::getInstance()->hasIdentity() && $_COOKIE['login'] != '' && $request->getControllerName() != 'login' && $request->getControllerName() != 'error') {
            $this->_response->setRedirect($request->getBaseUrl() . '/' . Zend_Registry::getInstance()->parameters->registry->defaultLanguage . '/login/autologin');
        }
    }
}