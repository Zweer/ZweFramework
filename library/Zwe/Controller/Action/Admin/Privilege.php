<?php

class Zwe_Controller_Action_Admin_Privilege extends Zwe_Controller_Action
{
    protected $_title = 'Users\' Privileges';
    protected $_admin = 'admin_privilege';

    public $contexts = array('add' => array('json'),
                             'delete' => array('json'),
                             'get' => array('json'));

    const ADD_OK = 'ZweControllerActionAdminPrivilegeAddOK';
    const ADD_KO = 'ZweControllerActionAdminPrivilegeAddKO';
    const DELETE_OK = 'ZweControllerActionAdminPrivilegeDeleteOK';
    const DELETE_KO = 'ZweControllerActionAdminPrivilegeDeleteKO';

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Privilege();
        $this->view->form->setResources(Zwe_Model_Resource::getStair());
    }

    protected function _getAction()
    {
        $form = new Zwe_Form_Admin_Privilege();
        $form->setResources(Zwe_Model_Resource::getStair());

        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $privileges = Zwe_Model_Privilege::findByIDResource($form->getValue('resource'));
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
        $form = new Zwe_Form_Admin_Privilege();
        $form->setResources(Zwe_Model_Resource::getStair());

        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $select = Zwe_Model_Privilege::getInstance()->select()->where("IDResource = ?", $form->getValue('resource'))
                                                                      ->where("Name = ?", $form->getValue('privileges'));
                if(Zwe_Model_Privilege::getInstance()->fetchAll($select)->count() == 0) {
                    $privilege = Zwe_Model_Privilege::create(array('IDResource' => $form->getValue('resource'), 'Name' => $form->getValue('privileges')));
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
        $form = new Zwe_Form_Admin_Privilege();
        $form->setResources(Zwe_Model_Resource::getStair());

        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                if(Zwe_Model_Privilege::findByPrimary($form->getValue('privileges'))->current()->delete()) {
                    Zwe_Model_Privilege_Group::deleteByIDPrivilege($form->getValue('privileges'));
                    Zwe_Model_Privilege_User::deleteByIDPrivilege($form->getValue('privileges'));
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