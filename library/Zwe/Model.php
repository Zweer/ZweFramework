<?php

abstract class Zwe_Model extends Zend_Db_Table_Abstract
{
    protected function _setup()
    {
        parent::_setup();
        $this->_setupRowClass();
        $this->_setupRowsetClass();
    }

    protected function _setupTableName()
    {
        if(!isset($this->_name)) {
            $name = get_class($this);
            $name = str_replace('Zwe_Model_', '', $name);
            if(strpos($name, '_') !== false)
                $name = substr($name, 0, strpos($name, '_'));

            $inflector = new Zend_Filter_Inflector(':table');
            $inflector->setRules(array(
                ':table' => array('Word_CamelCaseToUnderscore', 'StringToLower')
            ));
            $this->_name = $inflector->filter(array('table' => $name));

            if(!isset($this->_primary))
                $this->_primary = 'ID' . $name;
        }

        parent::_setupTableName();
    }

    protected function _setupRowClass()
    {
        $rowClass = str_replace('Zwe_Model_', 'Zwe_Db_Table_Row_', get_class($this));

        if(file_exists(LIBRARY_PATH . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $rowClass) . '.php'))
            $this->_rowClass = $rowClass;
    }

    protected function _setupRowsetClass()
    {
        $rowsetClass = str_replace('Zwe_Model_', 'Zwe_Db_Table_Rowset_', get_class($this));

        if(file_exists(LIBRARY_PATH . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $rowsetClass) . '.php'))
            $this->_rowsetClass = $rowsetClass;
    }

    public static function findByPrimary($primary)
    {
        $model = new static();
        return $model->find($primary);
    }

    public static function create(array $data = array(), $defaultSource = null)
    {
        $model = new static();
        return $model->createRow($data, $defaultSource);
    }

    public static function __callStatic($name, array $arguments)
    {
        switch(true) {
            case strpos($name, 'findBy') === 0 && strlen($name) > strlen('findBy'):
                $field = substr($name, strlen('findBy'));
                $model = new static();
                if(!in_array($field, $model->info(Zend_Db_Table_Abstract::COLS)))
                    throw new Exception("Field $field is not part of the data");

                $select = $model->select()->where("$field = ?", $arguments[0]);
                return $model->fetchAll($select);
            break;

            default:
                throw new Exception("Static method '$name' not implemented'");
            break;
        }
    }
}