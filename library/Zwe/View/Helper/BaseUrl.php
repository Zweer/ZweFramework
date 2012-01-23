<?php

class Zwe_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
    protected $_baseUrl = null;

    public function baseUrl()
    {
        if(!isset($this->_baseUrl)) {
            $this->_baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        }

        return $this->_baseUrl;
    }
}