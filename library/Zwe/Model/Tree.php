<?php

abstract class Zwe_Model_Tree extends Zwe_Model
{
    const CHILDREN_KEY = 'Children';

    public static function getTree($IDParent = 0, $adds = null)
    {
        $elements = static::findByIDParent($IDParent);

        if($elements->count() == 0)
            return null;

        foreach ($elements as $element) {
            $element->{static::CHILDREN_KEY} = static::getTree($element->{static::getPrimary()}, $adds);
            if(isset($adds)) {
                if(!is_array($adds)) {
                    $adds = array($adds);
                }

                foreach($adds as $add) {
                    $method = '_getTree' . $add;
                    static::$method($element);
                }
            }
        }

        return $elements;
    }

    public static function getStair($nameKey = 'Name', $prefix = '-', $IDParent = 0, $level = 0)
    {
        $ret = array();
        $elements = static::findByIDParent($IDParent);

        if($elements->count() == 0)
            return array();

        foreach ($elements as $element) {
            $ret[$element->{static::getPrimary()}] = str_repeat($prefix, $level) . ($level > 0 ? ' ' : '') . $element->$nameKey;
            $ret += static::getStair($nameKey, $prefix, $element->{static::getPrimary()}, $level + 1);
        }

        return $ret;
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

    public static function __callStatic($name, array $arguments)
    {
        switch(true) {
            case strpos($name, 'getTree') === 0 && strlen($name) > strlen('getTree'):
                $adds = explode('_', substr($name, strlen('getTree')));
                return static::getTree(0, $adds);
            break;

            default:
                return parent::__callStatic($name, $arguments);
            break;
        }
    }
}