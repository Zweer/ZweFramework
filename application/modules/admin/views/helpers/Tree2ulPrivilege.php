<?php

class Admin_View_Helper_Tree2ulPrivilege extends Zwe_View_Helper_Tree2ul
{
    const ROOT_CLASS = 'tree noPointer';

    public function tree2ulPrivilege(Zend_Db_Table_Rowset $tree)
    {
        return parent::tree2ul($tree);
    }
    
    protected function _getAdditions($node)
    {
        $ret = '<ul class="tree-resource-privilege">';
        
        $privileges = $node->{Zwe_Model_Resource::PRIVILEGE_KEY};
        foreach ($privileges as $privilege) {
            $ret .= '<li id="tree-resource-privilege-' . $privilege->IDPrivilege . '">';
            $ret .= $privilege->Name;
            $ret .= ' ';
            $ret .= $this->view->img('/images/icons/delete_red_16x16.png', array('alt' => 'Set Privilege', 'title' => 'Grant/negate privilege'));
            $ret .= '</li>';
        }

        $ret .= '</ul>';
        return $ret;
    }
}