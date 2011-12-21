<?php

/**
 * @file library/Zwe/Controller/Action.php
 * Abstract class for the actions
 *
 * @category    Zwe
 * @package     Zwe_Controller
 */

/**
 * Abstract class for the actions
 * Defines default behaviour like contexts.
 *
 * @uses        Zend_Controller_Action
 * @category    Zwe
 * @package     Zwe_Controller
 */
abstract class Zwe_Controller_Action extends Zend_Controller_Action
{
    /**
     * All the contexts are specified here.
     *
     * @var array
     */
    public $contexts = null;

    /**
     * The current context used
     *
     * @var string
     */
    protected $_context = null;

    /**
     * @var string
     */
    protected $_title = null;

    /**
     * @var bool
     */
    protected $_private = null;
    /**
     * @var array
     */
    protected $_admin = array();

    /**
     * Class constructor
     *
     * It calls the parent constructor, then adds the personalized path for the helpers and initializes the contexts
     *
     * @see parent::__construct()
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        $this->_helper->addPath('Zwe/Controller/Action/Helper', 'Zwe_Controller_Action_Helper');
    }

    public function init()
    {
        $this->initContext();
        $this->initTitle();
    }

    protected function initTitle()
    {
        if($this->_admin)
            $this->view->headTitle()->append('Admin');

        #$this->view->thePage = Zwe_Model_Page::getThisPage();
        if(isset($this->view->thePage))
            $this->view->title = $this->view->thePage->Title;
        else
            $this->view->title = $this->_title;

        if($this->view->title)
            $this->view->headTitle()->append($this->view->title);
    }

    /**
     * Initializes the contexts if they are set.
     */
    protected function initContext()
    {
        if(isset($this->contexts)) {
            $this->_helper->contextSwitch->initContext();
            $this->_context = $this->_helper->contextSwitch->getCurrentContext();
        }
    }

    public function preDispatch()
    {
        if(!isset($this->_private) && $this->_admin)
            $this->_private = true;

        if($this->_private && !Zend_Auth::getInstance()->hasIdentity())
            $this->_helper->_redirector('auth', 'error', 'default');
    }

    /**
     * If a context is used, it disables the layout.
     */
    public function postDispatch()
    {
        if(isset($this->_context))
            $this->_helper->layout->disableLayout();
    }
}

?>