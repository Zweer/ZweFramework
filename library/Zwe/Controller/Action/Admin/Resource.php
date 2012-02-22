<?php

class Zwe_Controller_Action_Admin_Resource extends Zwe_Controller_Action
{
    protected $_title = 'Resources';
    protected $_admin = 'admin_resource';

    const RESOURCE_ORDER_OK = 'ResourceOrderOk';
    const RESOURCE_ORDER_KO = 'ResourceOrderKo';

    public  $contexts = array('order' => array('json'));

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Resource();

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                Zwe_Model_Resource::create(array('Name' => $this->view->form->getValue('name')))->save();
                $this->view->form->reset();
            }
        }

        $this->view->resources = Zwe_Model_Resource::getTree();
    }

    protected function _orderAction()
    {
        unset($this->view->title);

        if($this->getRequest()->isPost()) {
            if($order = Zend_Json::decode($this->getRequest()->getPost('order'))) {
                try {
                    $this->view->getHelper('tree2ul');
                    Zwe_Model_Resource::orderTree($order, Admin_View_Helper_Tree2ul::ROOT_ID);
                    $this->view->message = $this->view->translate(self::RESOURCE_ORDER_OK);
                } catch(Exception $e) {
                    $this->view->message = $this->view->translate(self::RESOURCE_ORDER_KO);
                }
            }
        }
    }

    protected function _editAction()
    {

    }

    protected function _deleteAction()
    {
        $IDResource = $this->_getParam('id', 0);

        Zwe_Model_Resource::deleteByPrimary($IDResource);

        $this->_helper->redirector('index');
    }
}