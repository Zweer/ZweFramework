<?php

class LoginController extends Zwe_Controller_Action_Login
{
    public $ajaxable = array('index' => array('html'));

    public function indexAction()
    {
        parent::_indexAction();
    }

    public function autologinAction()
    {
        parent::_indexAction(true);
    }

    public function logoutAction()
    {
        parent::_logoutAction();
    }

    public function registerAction()
    {
        parent::_registerAction();
    }

    public function activateAction()
    {
        parent::_activateAction();
    }

    public function recoverAction()
    {
        parent::_recoverAction();
    }

    public function dorecoverAction()
    {
        parent::_doRecoverAction();
    }
}