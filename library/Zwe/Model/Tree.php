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
}