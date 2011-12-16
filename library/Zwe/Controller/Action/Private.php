<?php

class Zwe_Controller_Action_Private extends Zwe_Controller_Action_Default
{
    public function preDispatch()
    {
        if(!Zend_Auth::getInstance()->hasIdentity())
            $this->_helper->_redirector('auth', 'error', 'default');
    }
}