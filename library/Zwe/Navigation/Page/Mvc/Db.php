<?php

class Zwe_Navigation_Page_Mvc_Db extends Zend_Navigation_Page_Mvc
{
    public function isActive($recursive = false)
    {
        Zend_Registry::get('Zend_Log')->debug('Zwe: ' . Zwe_Model_Page::getThisPage()->getCompleteUrl());
        Zend_Registry::get('Zend_Log')->debug('getHref: ' . $this->getHref());
        if(Zwe_Model_Page::getThisPage()->getCompleteUrl() == substr($this->getHref(), -1 * strlen(Zwe_Model_Page::getThisPage()->getCompleteUrl()))) {
            $this->_active = true;
        }

        return Zend_Navigation_Page::isActive($recursive);
    }
}