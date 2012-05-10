<?php

class Admin_PageController extends Zwe_Controller_Action_Admin_Page
{
    public function indexAction()
    {
        parent::_indexAction();
    }

    public function orderAction()
    {
        parent::_orderAction();
    }

    public function editAction()
    {
        parent::_editAction();
    }

    public function deleteAction()
    {
        parent::_deleteAction();
    }

    public function getmodulesAction()
    {
        parent::_getParamsAction('modules');
    }

    public function getcontrollersAction()
    {
        parent::_getParamsAction('controllers');
    }

    public function getactionsAction()
    {
        parent::_getParamsAction('actions');
    }
}