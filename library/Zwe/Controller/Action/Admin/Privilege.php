<?php

class Zwe_Controller_Action_Admin_Privilege extends Zwe_Controller_Action
{
    protected $_title = 'Users\' Privileges';
    protected $_admin = 'admin_privilege';

    const PRIVILEGE_ORDER_OK = 'PrivilegeOrderOk';
    const PRIVILEGE_ORDER_KO = 'PrivilegeOrderKo';

    public  $contexts = array('order' => array('json'));

    protected function _indexAction()
    {
        $this->view->resources = Zwe_Model_Resource::getTree();
    }

    protected function _orderAction()
    {
        unset($this->view->title);

        if($this->getRequest()->isPost()) {
            if($order = Zend_Json::decode($this->getRequest()->getPost('order'))) {
                try {
                    Zwe_Model_Resource::orderTree($order, Admin_View_Helper_Tree2ul::ROOT_ID);
                    $this->view->message = $this->view->translate(self::PRIVILEGE_ORDER_OK);
                } catch(Exception $e) {
                    $this->view->message = $this->view->translate(self::PRIVILEGE_ORDER_KO);
                }
            }
        }
    }
}