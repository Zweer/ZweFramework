<?php

class Zwe_Form_Element_Text extends Zend_Form_Element_Text
{
    protected $_filters = array(array('filter' => 'StringTrim'));
}