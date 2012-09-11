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
        'title' => 'Title',
        'url' => 'Url'
    );

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);

        $this->addElement('text', 'title');
        $this->getElement('title')->setLabel('Title')
                                  ->setRequired(true);

        $this->addElement('text', 'url');
        $this->getElement('url')->setLabel('Url')
                                ->setDescription('Parent page\'s url is added automatically')
                                ->setRequired(true)
                                ->addValidator(new Zend_Validate_Regex(array('pattern' => '/[a-z0-9\-_\+]/i')))
                                ->addValidator(new Zend_Validate_Db_NoRecordExists(array('table' => 'page',
                                                                                         'field' => 'Url',
                                                                                         'exclude' => Zend_Db_Table::getDefaultAdapter()->quoteInto('IDParent = ?', intval($_POST['parent']))
                                                                                                      . ' AND ' .
                                                                                                      Zend_Db_Table::getDefaultAdapter()->quoteInto('IDPage != ?', intval($_POST['id'])))));

        $this->addElement('select', 'parent');
        $this->getElement('parent')->setLabel('Parent Page');

        $this->addElement('select', 'module');
        $this->getElement('module')->setLabel('Module')
                                   ->setRequired(true)
                                   ->setAttrib('id', 'page-module');

        $this->addElement('select', 'controller');
        $this->getElement('controller')->setLabel('Controller')
                                       ->setRequired(true)
                                       ->setAttrib('id', 'page-controller')
                                       ->setRegisterInArrayValidator(false);

        $this->addElement('select', 'action');
        $this->getElement('action')->setLabel('Action')
                                   ->setRequired(true)
                                   ->setAttrib('id', 'page-action')
                                   ->setRegisterInArrayValidator(false);

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

    public function setControllers(array $controllers)
    {
        $this->getElement('controller')->addMultiOptions(array_combine($controllers, $controllers));

        return $this;
    }

    public function setActions(array $actions)
    {
        $this->getElement('action')->addMultiOptions(array_combine($actions, $actions));

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