<?php

class Zwe_Form_Admin_List extends Zwe_Form
{
    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement('select', 'name');
        $this->getElement('name')->setRequired(true);

        $this->addElement('text', 'list');
    }

    public function setNames(array $names)
    {
        $this->getElement('name')->setMultiOptions($names);

        return $this;
    }
}