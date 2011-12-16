<?php

/**
 * @file library/Zwe/Controller/Action/Default.php
 * The default controller.
 *
 * @category    Zwe
 * @package     Zwe_Controller
 * @subpackage  Zwe_Controller_Action
 */

/**
 * The default controller.
 * Its main concern is to set the page title.
 *
 * @uses        Zwe_Controller_Action
 * @category    Zwe
 * @package     Zwe_Controller
 * @subpackage  Zwe_Controller_Action
 */
class Zwe_Controller_Action_Default extends Zwe_Controller_Action
{
    /**
     * The title of the page
     *
     * @var string
     */
    protected $_title = '';

    /**
     * Initializes the controller.
     * It saves the page and the title.
     */
    public function init()
    {
        $this->view->thePage = Zwe_Model_Page::getThisPage();
        if(isset($this->view->thePage))
            $this->view->title = $this->view->thePage->Title;
        else
            $this->view->title = $this->_title;

        if($this->view->title)
            $this->view->headTitle()->append($this->view->title);
    }
}

?>