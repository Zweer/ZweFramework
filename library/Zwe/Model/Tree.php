<?php

abstract class Zwe_Model_Tree extends Zwe_Model
{
    const CHILDREN_KEY = 'Children';

    public static function getTree($IDParent = 0)
    {
        $elements = static::findByIDParent($IDParent);

        if($elements->count() == 0)
            return null;

        foreach ($elements as $element) {
            $element->{static::CHILDREN_KEY} = static::getTree($element->{static::getPrimary()});
        }

        return $elements;
    }

    public static function orderTree($tree, $prefix = '', $IDParent = 0)
    {
        foreach ($tree as $IDNode => $children) {
            $ID = substr($IDNode, strlen($prefix) + 1);
            $node = static::findByPrimary($ID)->current();
            $node->IDParent = $IDParent;
            $node->save();

            if(is_array($children) && count($children) > 0)
                static::orderTree($children, $prefix, $node->{static::getPrimary()});
        }
    }
}