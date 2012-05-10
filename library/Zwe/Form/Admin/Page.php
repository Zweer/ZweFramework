<?php

class Zwe_Form_Admin_Page extends Zwe_Form
{
    protected $_convertTable = array(
        'id' => 'IDPage',
        'action' => 'Action',
        'controller' => 'Controller',
        'parent' => 'IDParent',
        'module' => 'Module',
        'parameters' => 'Parameters',
        'title' => 'Title'
    );

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement('text', 'title');
        $this->getElement('title')->setLabel('Title')
                                  ->setRequired(true);

        $this->addElement('select', 'parent');
        $this->getElement('parent')->setLabel('Parent Page');

        $this->addElement('text', 'module');
        $this->getElement('module')->setLabel('Module')
                                   ->setRequired(true)
                                   ->setValue('default');

        $this->addElement('text', 'controller');
        $this->getElement('controller')->setLabel('Controller')
                                       ->setRequired(true)
                                       ->setValue('static');

        $this->addElement('text', 'action');
        $this->getElement('action')->setLabel('Action')
                                   ->setRequired(true)
                                   ->setValue('index');

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Create');
    }

    public function setParents(array $parents)
    {
        $this->getElement('parent')->addMultiOption(0, '');
        $this->getElement('parent')->addMultiOptions($parents);

        return $this;
    }

    public function setEditable($id = null)
    {
        $this->addElement('hidden', 'id');
        if(isset($id))
            $this->getElement('id')->setValue($id);

        $this->getElement('submit')->setLabel('Edit');

        return $this;
    }
}