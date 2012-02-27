<?php

class Zwe_Controller_Action_Admin_Privilege extends Zwe_Controller_Action
{
    protected $_title = 'Privileges Management';
    protected $_admin = 'admin_privilege';

    /**
     * @var Zwe_Form_Admin_List
     */
    protected $_form;

    public $contexts = array('add' => array('json'),
                             'delete' => array('json'),
                             'get' => array('json'));

    const ADD_OK = 'ZweControllerActionAdminPrivilegeAddOK';
    const ADD_KO = 'ZweControllerActionAdminPrivilegeAddKO';
    const DELETE_OK = 'ZweControllerActionAdminPrivilegeDeleteOK';
    const DELETE_KO = 'ZweControllerActionAdminPrivilegeDeleteKO';

    public function init()
    {
        parent::init();

        $this->_form = new Zwe_Form_Admin_List();
        $this->_form->setNames(Zwe_Model_Resource::getStair());
    }

    protected function _indexAction()
    {
        $this->view->form = $this->_form;
    }

    protected function _getAction()
    {
        if($this->getRequest()->isPost()) {
            if($this->_form->isValid($this->getRequest()->getPost())) {
                $privileges = Zwe_Model_Privilege::findByIDResource($this->_form->getValue('name'));
                $this->view->privileges = array();

                foreach ($privileges as $privilege) {
                    $this->view->privileges[] = array('name' => $privilege->Name,
                                                      'id' => $privilege->IDPrivilege);
                }

                unset($this->view->title);
            }
        }
    }

    protected function _addAction()
    {
        if($this->getRequest()->isPost()) {
            if($this->_form->isValid($this->getRequest()->getPost())) {
                $select = Zwe_Model_Privilege::getInstance()->select()->where("IDResource = ?", $this->_form->getValue('name'))
                                                                      ->where("Name = ?", $this->_form->getValue('list'));
                if(Zwe_Model_Privilege::getInstance()->fetchAll($select)->count() == 0) {
                    $privilege = Zwe_Model_Privilege::create(array('IDResource' => $this->_form->getValue('name'), 'Name' => $this->_form->getValue('list')));
                    $this->view->id = $privilege->save();
                    $this->view->message = $this->view->translate(self::ADD_OK);
                } else {
                    $this->view->id = false;
                    $this->view->message = $this->view->translate(self::ADD_KO);
                }

                unset($this->view->title);
            }
        }
    }

    protected function _deleteAction()
    {
        if($this->getRequest()->isPost()) {
            if($this->_form->isValid($this->getRequest()->getPost())) {
                if(Zwe_Model_Privilege::deleteByPrimary($this->_form->getValue('list'))) {
                    $this->view->ok = true;
                    $this->view->message = $this->view->translate(self::DELETE_OK);
                } else {
                    $this->view->ok = false;
                    $this->view->message = $this->view->translate(self::DELETE_KO);
                }

                unset($this->view->title);
            }
        }
    }
}