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

        $this->addElement('select', 'module');
        $this->getElement('module')->setLabel('Module')
                                   ->setRequired(true)
                                   ->setAttrib('id', 'page-module');

        $this->addElement('select', 'controller');
        $this->getElement('controller')->setLabel('Controller')
                                       ->setRequired(true)
                                       ->setAttrib('id', 'page-controller');

        $this->addElement('select', 'action');
        $this->getElement('action')->setLabel('Action')
                                   ->setRequired(true)
                                   ->setAttrib('id', 'page-action');

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Create');
    }

    public function setParents(array $parents)
    {
        $this->getElement('parent')->addMultiOption(0, '');
        $this->getElement('parent')->addMultiOptions($parents);

        return $this;
    }

    public function setModules(array $modules)
    {
        $this->getElement('module')->addMultiOption('', $this->getView()->translate(Zwe_Controller_Action_Admin_Page::PAGE_SELECT_MODULE));
        $this->getElement('module')->addMultiOptions(array_combine($modules, $modules));

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