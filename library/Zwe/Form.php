<?php

class Zwe_Form extends Zend_Form
{
    protected $_convertTable = null;

    public function __construct($options = null)
    {
        $this->addPrefixPath("Zwe_Form", "Zwe/Form/");
        $this->addPrefixPath("Zwe_Form_Decorator", "Zwe/Form/Decorator", "decorator");
        parent::__construct($options);
    }

    public function populateFromDB(array $values)
    {
        if(isset($this->_convertTable)) {
            $modValues = array();
            foreach($this->getElements() as $name => $element) {
                $modValues[$name] = $values[$this->_convertTable[$name]];
            }

            $values = $modValues;
        }

        return parent::populate($values);
    }

    public function getValuesForDB()
    {
        $values = parent::getValues();

        if(isset($this->_convertTable)) {
            $modValues = array();
            foreach ($values as $key => $value) {
                $modValues[$this->_convertTable[$key]] = $value;
            }

            $values = $modValues;
        }

        return $values;
    }
}