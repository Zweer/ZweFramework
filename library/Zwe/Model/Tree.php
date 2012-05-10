<?php

abstract class Zwe_Model_Tree extends Zwe_Model
{
    const CHILDREN_KEY = 'Children';
    const ORDER_KEY = 'Order';

    public static function getTree($IDParent = 0, $adds = null)
    {
        $elements = static::findByIDParent($IDParent, static::hasField('Order') ? 'Order' : null);

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
        $elements = static::findByIDParent($IDParent, static::hasField('Order') ? 'Order' : null);

        if($elements->count() == 0)
            return array();

        foreach ($elements as $element) {
            $ret[$element->{static::getPrimary()}] = str_repeat($prefix, $level) . ($level > 0 ? ' ' : '') . $element->$nameKey;
            $ret += static::getStair($nameKey, $prefix, $element->{static::getPrimary()}, $level + 1);
        }

        return $ret;
    }

    public static function orderTree($tree, $prefix = '', $IDParent = 0, $order = null)
    {
        $count = 0;
        if(!isset($order)) {
            $cols = static::getInstance()->info(Zend_Db_Table_Abstract::COLS);
            if(in_array(static::ORDER_KEY, $cols))
                $order = true;
            else
                $order = false;
        }

        foreach ($tree as $IDNode => $children) {
            $ID = substr($IDNode, strlen($prefix) + 1);
            $node = static::findByPrimary($ID)->current();
            $node->IDParent = $IDParent;
            if($order)
                $node->{static::ORDER_KEY} = $count++;

            $node->save();

            if(is_array($children) && count($children) > 0)
                static::orderTree($children, $prefix, $node->{static::getPrimary()}, $order);
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