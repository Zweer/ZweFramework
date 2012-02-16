<?php

class Zwe_Form_Admin_Resource extends Zwe_Form
{
    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement('text', 'name');
        $this->getElement('name')->setLabel('Name')
                                 ->setRequired(true);

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Create');
    }
}