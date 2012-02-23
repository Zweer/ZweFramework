<?php

class Zwe_View_Helper_Navigation extends Zend_View_Helper_Navigation
{
    public function navigation(Zend_Navigation_Container $container = null)
    {
        parent::navigation($container);

        if(!isset($this->_helpers['menu'])) {
            $helper = $this->view->getHelper('navigation_menu');
            $this->_inject($helper);
            $this->_helpers['menu'] = $helper;
        }

        return $this;
    }
}