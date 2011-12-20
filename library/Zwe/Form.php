<?php

class Zwe_Form extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addPrefixPath("Zwe_Form", "Zwe/Form/");
        $this->addPrefixPath("Zwe_Form_Decorator", "Zwe/Form/Decorator", "decorator");
        parent::__construct($options);
    }
}