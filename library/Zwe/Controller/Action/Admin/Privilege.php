<?php

class Zwe_Controller_Action_Admin_Privilege extends Zwe_Controller_Action
{
    protected $_title = 'Users\' Privileges';
    protected $_admin = 'privilege';

    protected function _indexAction()
    {
        $this->view->resources = Zwe_Model_Resource::getTree();
    }
}