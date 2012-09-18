<?php

class Zwe_Controller_Action_Admin_Page extends Zwe_Controller_Action
{
    protected $_title = 'Pages';
    protected $_admin = 'admin_page/manage';

    const PAGE_ORDER_OK = 'ZweControllerActionAdminPageOrderOk';
    const PAGE_ORDER_KO = 'ZweControllerActionAdminPageOrderKo';

    const PAGE_SELECT_MODULE = 'ZweControllerActionAdminPageSelectModule';
    const PAGE_SELECT_CONTROLLER = 'ZweControllerActionAdminPageSelectController';
    const PAGE_SELECT_ACTION = 'ZweControllerActionAdminPageSelectAction';

    public $contexts = array('order' => array('json'),
                             'getModules' => array('json'),
                             'getControllers' => array('json'),
                             'getActions' => array('json'));

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Name();

        if($this->getRequest()->isPost()) {
            $this->_forward('edit');
        }

        $this->view->pages = Zwe_Model_Page::getTree();
    }

    protected function _orderAction()
    {
        unset($this->view->title);

        if($this->getRequest()->isPost()) {
            if($order = Zend_Json::decode($this->getRequest()->getPost('order'))) {
                try {
                    $this->view->getHelper('tree2ul');
                    Zwe_Model_Page::orderTree($order, Admin_View_Helper_Tree2ul::ROOT_ID);
                    Zwe_Model_Page::rebuildNavigation();
                    $this->view->message = $this->view->translate(self::PAGE_ORDER_OK);
                } catch(Exception $e) {
                    $this->view->message = $this->view->translate(self::PAGE_ORDER_KO);
                }
            }
        }
    }

    protected function _editAction()
    {
        $littleForm = new Zwe_Form_Admin_Name();
        $this->view->form = new Zwe_Form_Admin_Page();
        $this->view->form->setParents(Zwe_Model_Page::getStair('Title'));
        $this->view->form->setModules($this->_getParamsAction('modules', false));

        if($this->getRequest()->isPost()) {
            if($littleForm->isValid($this->getRequest()->getPost())) {
                $this->view->form->setDefault('title', $littleForm->getValue('name'));
            } elseif($this->view->form->isValid($this->getRequest()->getPost())) {
                $page = null;
                if($this->getRequest()->getPost('id') !== null)
                    $this->view->form->setEditable($this->getRequest()->getPost('id'));

                if($this->view->form->getValue('id') === null) {
                    # Create new page
                    $page = Zwe_Model_Page::create($this->view->form->getValuesForDB());

                    $parent = Zwe_Model_Page::findByPrimary($page->IDParent)->current();
                    $page->Url = trim($parent->Url . Zend_Controller_Router_Route_Chain::URI_DELIMITER . $page->Url, Zend_Controller_Router_Route_Chain::URI_DELIMITER);

                    $maxOrderSiblin = Zwe_Model_Page::findByIDParent($page->IDParent, Zwe_Model_Tree::ORDER_KEY . ' DESC')->current();
                    $page->Order = $maxOrderSiblin->{Zwe_Model_Tree::ORDER_KEY} + 1;
                } else {
                    # Edit existing page
                    $page = Zwe_Model_Page::findByPrimary($this->view->form->getValue('id'))->current();
                    $page->setFromArray($this->view->form->getValuesForDB());

                    $parent = Zwe_Model_Page::findByPrimary($page->IDParent)->current();
                    $page->Url = trim($parent->Url . Zend_Controller_Router_Route_Chain::URI_DELIMITER . $page->Url, Zend_Controller_Router_Route_Chain::URI_DELIMITER);

                    if($page->IDPage == $page->IDParent)
                        $page->IDParent = 0;
                }

                $page->save();

                # Rebuild the navigation config file
                Zwe_Model_Page::rebuildNavigation();

                $this->_helper->redirector('index');
            }
        }

        if($idPage = $this->_getParam('id')) {
            $page = Zwe_Model_Page::findByPrimary($idPage)->current();

            $this->view->form->setEditable()
                             ->setControllers($this->_getParamsAction('controllers', false, $page->Module))
                             ->setActions($this->_getParamsAction('actions', false, $page->Module, $page->Controller))
                             ->populateFromDB($page->toArray())
                             ->setDefault('url', substr($page->Url, strrpos($page->Url, Zend_Controller_Router_Route_Chain::URI_DELIMITER+1)));
        }
    }

    protected function _deleteAction()
    {
        $IDPage = $this->_getParam('id', 0);

        Zwe_Model_Page::deleteByPrimary($IDPage);
        Zwe_Model_Page::rebuildNavigation();

        $this->_helper->redirector('index');
    }

    protected function _getParamsAction($what, $isAction = true, $module = null, $controller = null)
    {
        $method = '_get' . ucfirst($what);
        $ret = $this->$method($module, $controller);

        if($isAction) {
            unset($this->view->title);
            $this->view->{$what} = $ret;
        }

        return $ret;
    }

    protected function _getModules()
    {
        $path = APPLICATION_PATH . '/modules';
        $dir = opendir($path);
        $modules = array('default');

        while($file = readdir($dir)) {
            if(is_dir($path . '/' . $file) && $file != '.' && $file != '..' && strtolower($file) != 'admin' && strtolower($file) != 'helper') {
                $modules[] = strtolower($file);
            }
        }

        return $modules;
    }

    protected function _getControllers($module = null)
    {
        if(!isset($module)) {
            $module = $this->getRequest()->getPost('module');
        }
        if($module == $this->view->translate(static::PAGE_SELECT_MODULE))
            return array();

        $path = APPLICATION_PATH . ($module == 'default' ? '' : '/modules/' . $module) . '/controllers';
        $dir = opendir($path);
        $controllers = array($this->view->translate(static::PAGE_SELECT_CONTROLLER));

        while($file = readdir($dir)) {
            if($module == '') {
                break;
            } elseif(is_dir($path . '/' . $file) || $file == '.' || $file == '..') {
                continue;
            } elseif($module == 'default' && ($file == 'ErrorController.php' || $file == 'LoginController.php')) {
                continue;
            }

            $controllers[] = strtolower(str_replace('Controller.php', '', $file));
        }

        return $controllers;
    }

    protected function _getActions($module = null, $controller = null)
    {
        if(!isset($module)) {
            $module = $this->getRequest()->getPost('module');
        }
        if(!isset($controller)) {
            $controller = $this->getRequest()->getPost('controller');
        }
        if($module == $this->view->translate(static::PAGE_SELECT_MODULE) || $controller == $this->view->translate(static::PAGE_SELECT_CONTROLLER))
            return array();

        $class = ($module == 'default' ? '' : ucfirst($module) . '_') . '' . ucfirst($controller) . 'Controller';
        require_once(APPLICATION_PATH . ($module == 'default' ? '' : '/modules/' . $module) . '/controllers/' . ucfirst($controller) . 'Controller.php');
        $actions = array($this->view->translate(static::PAGE_SELECT_ACTION));

        return array_merge($actions, $class::getActions());
    }
}