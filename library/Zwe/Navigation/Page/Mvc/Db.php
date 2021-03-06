<?php

class Zwe_Navigation_Page_Mvc_Db extends Zend_Navigation_Page_Mvc
{
    public function isActive($recursive = false)
    {
        if(Zwe_Model_Page::getThisPage() !== null && Zwe_Model_Page::getThisPage()->Url == substr($this->getHref(), -1 * strlen(Zwe_Model_Page::getThisPage()->Url))) {
            $this->_active = true;
        }

        return Zend_Navigation_Page::isActive($recursive);
    }
}