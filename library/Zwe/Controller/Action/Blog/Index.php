<?php

class Zwe_Controller_Action_Blog_Index extends Zwe_Controller_Action
{
    const POST_PER_PAGE = 5;

    protected function _indexAction($page = 'blog')
    {
        $this->_setPageNumber();
    }

    protected function _setPageNumber()
    {
        $this->view->page = intval($this->_getParam('page'), 1);
    }

    protected function _setPosts()
    {

    }
}