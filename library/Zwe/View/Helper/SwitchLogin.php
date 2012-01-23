<?php

class Zwe_View_Helper_SwitchLogin extends Zend_View_Helper_Abstract
{
    public function switchLogin()
    {
        $return = array();
        
        if(Zend_Auth::getInstance()->hasIdentity()) {

        } else {
            $return['login']  = "<a href='" . $this->view->url(array('module' => 'default', 'controller' => 'login', 'action' => 'index'), 'locale_default') . "'>";
            $return['login'] .= $this->view->img('/images/icons/unlock_24x24.png', array('alt' => $this->view->translate('Login'), 'title' => $this->view->translate('Login')));
            $return['login'] .= "</a>";
        }
        
        return $return;
    }
}