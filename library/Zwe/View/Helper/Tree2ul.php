<?php

abstract class Zwe_View_Helper_Tree2ul extends Zend_View_Helper_Abstract
{
    const NODE_NAME = 'Name';

    const ROOT_ID = 'tree_root';
    const ROOT_CLASS = 'tree';

    public function tree2ul(Zend_Db_Table_Rowset $tree = null, $nodeName = null)
    {
        if(!isset($tree))
            return '';

        if(!isset($nodeName))
            $nodeName = static::NODE_NAME;

        $ret = '';
        foreach ($tree as $node) {
            $tableClass = get_class($node->getTable());
            $ret .= '<li id="' . static::ROOT_ID . '-' . $node->{$tableClass::getPrimary()} . '">';
            $ret .= '<span>';
            $ret .= $node->{$nodeName};
            $ret .= $this->_getModify($node->{$tableClass::getPrimary()});
            $ret .= $this->_getDelete($node->{$tableClass::getPrimary()});
            $ret .= '</span>';
            $ret .= $this->_getAdditions($node);
            if($node->{Zwe_Model_Tree::CHILDREN_KEY}) {
                $ret .= $this->tree2ul($node->{Zwe_Model_Tree::CHILDREN_KEY}, $nodeName);
            }
            $ret .= '</li>';
        }

        list(, $caller) = debug_backtrace(false);
        return '<ul' . ($caller['function'] != __FUNCTION__ ? ' id="' . static::ROOT_ID . '" class="' . static::ROOT_CLASS . '"' : '') . '>' . $ret . '</ul>';
    }

    protected function _getModify($id)
    {
        return '';
    }

    protected function _getDelete($id)
    {
        return '';
    }

    protected function _getAdditions($node)
    {
        return '';
    }
}