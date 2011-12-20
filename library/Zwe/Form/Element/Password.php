<?php

class Zwe_Form_Element_Password extends Zend_Form_Element_Password
{
    protected $_filters = array(array('filter' => 'StringTrim'));
}