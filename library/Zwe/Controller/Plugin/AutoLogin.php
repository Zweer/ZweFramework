<?php

class Zwe_Controller_Plugin_AutoLogin extends Zend_Controller_Plugin_Abstract
{
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if(!Zend_Auth::getInstance()->hasIdentity() && $_COOKIE['login'] != '' && $request->getControllerName() != 'login') {
            $this->_response->setRedirect($request->getBaseUrl() . '/login/autologin');
        }
    }
}