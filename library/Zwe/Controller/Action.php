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
     * All the ajax contexts are specified here.
     *
     * @var null
     */
    public $ajaxable = null;

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
     * @var string
     */
    protected $_admin = null;

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
        $this->_initContext();
        $this->_initAjaxContext();
        $this->_initTitle();
    }

    protected function _initTitle()
    {
        #$this->view->thePage = Zwe_Model_Page::getThisPage();
        if(isset($this->view->thePage))
            $this->view->title = $this->view->thePage->Title;
        else
            $this->view->title = $this->_title;

        if($this->view->title)
            $this->view->headTitle()->append($this->view->translate($this->view->title));
    }

    /**
     * Initializes the contexts if they are set.
     */
    protected function _initContext()
    {
        if(isset($this->contexts)) {
            $this->_helper->contextSwitch->initContext();
            $this->_context = $this->_helper->contextSwitch->getCurrentContext();
        }
    }

    /**
     * Initializes the ajax contexts if they are set.
     */
    protected function _initAjaxContext()
    {
        if(isset($this->ajaxable)) {
            $this->_helper->ajaxContext->initContext();
            if($this->_helper->ajaxContext->getCurrentContext() != null)
                $this->_context = $this->_helper->ajaxContext->getCurrentContext();
        }
    }

    public function preDispatch()
    {
        if(!isset($this->_private) && $this->_admin)
            $this->_private = true;

        if($this->_private && !Zwe_Auth::getInstance()->hasIdentity())
            $this->_helper->_redirector('auth', 'error', 'default');

        if(isset($this->_admin) && !Zwe_Auth::getInstance()->getIdentity()->isAllowedAny($this->_admin))
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

    public static function getActions()
    {
        $methods = get_class_methods(get_called_class());
        $actions = array();

        foreach ($methods as $method) {
            if(strpos($method, 'Action') !== false && strpos($method, '_') != 0) {
                $actions[] = $method;
            }
        }

        return $actions;
    }
}

?>