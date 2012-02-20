<?php

class Zwe_Form_Admin_Privilege extends Zwe_Form
{
    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement('select', 'resource');
        $this->getElement('resource')->setLabel('Resource')
                                     ->setRequired(true);

        $this->addElement('text', 'privileges');
        $this->getElement('privileges')->setLabel('Privileges');
    }

    public function setResources(array $resources)
    {
        $this->getElement('resource')->setMultiOptions($resources);

        return $this;
    }
}