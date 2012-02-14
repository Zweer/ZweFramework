<?php

abstract class Zwe_View_Helper_Tree2ul extends Zend_View_Helper_Abstract
{
    const NODE_ID = 'IDTree';
    const NODE_NAME = 'Name';

    const ROOT_ID = 'tree_root';
    const ROOT_CLASS = 'tree';

    public function tree2ul(Zend_Db_Table_Rowset $tree)
    {
        $ret = '';
        foreach ($tree as $node) {
            $ret .= '<li id="' . static::ROOT_ID . '-' . $node->{static::NODE_ID} . '">';
            $ret .= '<span>' . $node->{static::NODE_NAME} . '</span>';
            if($node->{Zwe_Model_Tree::CHILDREN_KEY}) {
                $ret .= $this->tree2ul($node->{Zwe_Model_Tree::CHILDREN_KEY});
            }
            $ret .= '</li>';
        }

        list(, $caller) = debug_backtrace(false);
        return '<ul' . ($caller['function'] != __FUNCTION__ ? ' id="' . static::ROOT_ID . '" class="' . static::ROOT_CLASS . '"' : '') . '>' . $ret . '</ul>';
    }
}