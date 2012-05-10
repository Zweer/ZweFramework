<?php

class Zwe_Controller_Action_Admin_Page extends Zwe_Controller_Action
{
    protected $_title = 'Pages';
    protected $_admin = 'admin_page';

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Admin_Name();
        $this->view->pages = Zwe_Model_Page::getTree();
    }
}