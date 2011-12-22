<?php

class Zwe_Controller_Action_News extends Zwe_Controller_Action
{
    const NEWS_PER_PAGE = 10;

    protected function _indexAction($page = 'news')
    {
        $this->view->page = intval($this->_getParam('page')) || 1;
        $News = new Zwe_Model_News();
        $this->view->news = $News->findByPage($page, self::NEWS_PER_PAGE, self::NEWS_PER_PAGE * ($this->view->page - 1));
    }
}