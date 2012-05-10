<?php

class Zwe_Controller_Action_Admin_Page extends Zwe_Controller_Action
{
    protected $_title = 'Pages';
    protected $_admin = 'admin_page';

    const PAGE_ORDER_OK = 'ZweControllerActionAdminPageOrderOk';
    const PAGE_ORDER_KO = 'ZweControllerActionAdminPageOrderKo';

    public $contexts = array('order' => array('json'));

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Name();

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                $this->_forward('edit');
            }
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

        if($this->getRequest()->isPost()) {
            if($littleForm->isValid($this->getRequest()->getPost())) {
                $this->view->form->setDefault('title', $littleForm->getValue('name'));
            } elseif($this->view->form->isValid($this->getRequest()->getPost())) {
                $page = null;
                if($this->getRequest()->getPost('id') !== null)
                    $this->view->form->setEditable($this->getRequest()->getPost('id'));

                if($this->view->form->getValue('id') === null) {
                    $page = Zwe_Model_Page::create($this->view->form->getValuesForDB());
                } else {
                    $page = Zwe_Model_Page::findByPrimary($this->view->form->getValue('id'))->current();
                    $page->setFromArray($this->view->form->getValuesForDB());

                    if($page->IDPage == $page->IDParent)
                        $page->IDParent = 0;
                }

                $page->save();

                $this->_helper->redirector('index');
            }
        }

        if($idPage = $this->_getParam('id')) {
            $page = Zwe_Model_Page::findByPrimary($idPage)->current();

            $this->view->form->setEditable()
                             ->populateFromDB($page->toArray());
        }
    }

    protected function _deleteAction()
    {
        $IDPage = $this->_getParam('id', 0);

        Zwe_Model_Page::deleteByPrimary($IDPage);

        $this->_helper->redirector('index');
    }
}