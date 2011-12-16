<?php

/**
 * TODO: add the acl support
 */

class Zwe_Controller_Action_Admin extends Zwe_Controller_Action_Private
{
    public function init()
    {
        $this->view->headTitle()->append('Admin');
        $this->view->headTitle()->append($this->view->title);
    }
}