<?php

class Admin_View_Helper_Tree2ul extends Zwe_View_Helper_Tree2ul
{
    protected function _getModify($id)
    {
        return '<a href="' . $this->view->url(array('action' => 'edit', 'id' => $id), 'default') . '">' . $this->view->img('/images/icons/edit_yellow_16x16.png', array('alt' => 'modify', 'title' => 'Modify')) . '</a>';
    }

    protected function _getDelete($id)
    {
        return '<a href="' . $this->view->url(array('action' => 'delete', 'id' => $id), 'default') . '">' . $this->view->img('/images/icons/delete_red_16x16.png', array('alt' => 'delete', 'title' => 'Delete')) . '</a>';
    }
}