<?php

abstract class Zwe_Controller_Action_Blog_Index extends Zwe_Controller_Action
{
    const POST_PER_PAGE = 5;

    const POSTED_BY = 'ZweBlogPostedBy';
    const COMMENT = 'ZweBlogComment';
    const COMMENTS = 'ZweBlogComments';
    const READ_MORE = 'ZweBlogReadMore';

    protected $_title = 'Blog';

    protected function _indexAction($page = 'blog')
    {
        $this->_setPageNumber();
        $this->_setPosts($page);
    }

    protected function _setPageNumber()
    {
        $this->view->page = intval($this->_getParam('page', 1));
    }

    protected function _setPosts($page)
    {
        $this->view->posts = Zwe_Model_Blog::getInstance()->findByPage($page, self::POST_PER_PAGE, self::POST_PER_PAGE * ($this->view->page - 1));
    }
}