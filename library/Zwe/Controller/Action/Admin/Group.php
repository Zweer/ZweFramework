<?php

class Zwe_Controller_Action_Admin_Group extends Zwe_Controller_Action
{
    protected $_title = 'Groups';
    protected $_admin = 'admin_group';

    const GROUP_ORDER_OK = 'ZweControllerActionAdminGroupOrderOk';
    const GROUP_ORDER_KO = 'ZweControllerActionAdminGroupOrderKo';

    public  $contexts = array('order' => array('json'));

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Name();

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                Zwe_Model_Group::create(array('Name' => $this->view->form->getValue('name')))->save();
                $this->view->form->reset();
            }
        }

        $this->view->groups = Zwe_Model_Group::getTree();
    }

    protected function _orderAction()
    {
        unset($this->view->title);

        if($this->getRequest()->isPost()) {
            if($order = Zend_Json::decode($this->getRequest()->getPost('order'))) {
                try {
                    $this->view->getHelper('tree2ul');
                    Zwe_Model_Group::orderTree($order, Admin_View_Helper_Tree2ul::ROOT_ID);
                    $this->view->message = $this->view->translate(self::GROUP_ORDER_OK);
                } catch(Exception $e) {
                    $this->view->message = $this->view->translate(self::GROUP_ORDER_KO);
                }
            }
        }
    }

    protected function _editAction()
    {
        $IDGroup = $this->_getParam('id', 0);
        $this->view->form = new Zwe_Form_Admin_Name();
        $this->view->group = Zwe_Model_Group::findByPrimary($IDGroup)->current();

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                $this->view->group->Name = $this->view->form->getValue('name');
                $this->view->group->save();

                $this->_helper->redirector('index');
            }
        }

        if($this->view->group) {
            $this->view->form->getElement('name')->setValue($this->view->group->Name);
        } else {
            $this->_helper->redirector('index');
        }
    }

    protected function _deleteAction()
    {
        $IDGroup = $this->_getParam('id', 0);

        Zwe_Model_Group::deleteByPrimary($IDGroup);

        $this->_helper->redirector('index');
    }

    protected function _permissionAction()
    {
        
    }
}