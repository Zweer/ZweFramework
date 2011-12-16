<?php

class Zwe_Controller_Plugin_StoreUri extends Zend_Controller_Plugin_Abstract
{
    protected $_ignoreList = array('login', 'error');

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($this->isNotToRemember($request))
            return;

        $History = new Zend_Session_Namespace('History');
        $History->last = $request->getRequestUri();
    }

    protected function isNotToRemember(Zend_Controller_Request_Abstract $request)
    {
        if(in_array($request->getControllerName(), $this->_ignoreList))
            return true;

        if($request->getRequestUri() == '/favicon.ico')
            return true;

        return false;
    }
}