<?php

class Zwe_Form_Element_Email extends Zwe_Form_Element_Text
{
    protected $_validators = array(array('validator' => 'EmailAddress'));
}