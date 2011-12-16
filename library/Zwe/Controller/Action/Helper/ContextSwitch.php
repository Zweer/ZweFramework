<?php

class Zwe_Controller_Action_Helper_ContextSwitch extends Zend_Controller_Action_Helper_ContextSwitch
{
    protected $_autoRssSerialization = true;

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->addContext('rss', array('suffix' => 'rss',
                                       'headers'   => array('Content-Type' => 'application/xml'),
                                       'callbacks' => array(
                                           'init' => 'initRssContext',
                                           'post' => 'postRssContext'
                                       )));
        $this->addContext('ajax', array());
    }

    public function setAutoRssSerialization($flag)
    {
        $this->_autoRssSerialization = (bool) $flag;
        return $this;
    }

    public function getAutoRssSerialization()
    {
        return $this->_autoRssSerialization;
    }

    public function initRssContext()
    {
        if (!$this->getAutoRssSerialization()) {
            return;
        }

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            $viewRenderer->setNoRender(true);
        }
    }

    public function postRssContext()
    {
        if (!$this->getAutorssSerialization()) {
            return;
        }

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            if(method_exists($view, 'getVars')) {
                $vars = $view->getVars();
                $feed = $vars['feed'];
                $feedObj = Zend_Feed::importArray($feed, 'rss');
                $feedString = $feedObj->saveXml();
                $feedObj->send();
            } else {
                require_once 'Zend/Controller/Action/Exception.php';
                throw new Zend_Controller_Action_Exception('View does not implement the getVars() method needed to encode the view into JSON');
            }
        }
    }
}