<?php

abstract class Zwe_Model extends Zend_Db_Table_Abstract
{
    protected static $_instance = null;

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
            $name = str_replace(array('Zwe_Model_', '_'), '', $name);

            $inflector = new Zend_Filter_Inflector(':table');
            $inflector->setRules(array(
                ':table' => array('Word_CamelCaseToUnderscore', 'StringToLower')
            ));
            $this->_name = $inflector->filter(array('table' => $name));
        }

        if(!isset($this->_primary)) {
            if(strpos($this->_name, '_') !== false) {
                $this->_primary = array();
                $name = explode('_', $this->_name);

                foreach ($name as $primary) {
                    $this->_primary[] = 'ID' . ucfirst($primary);
                }
            } else {
                $this->_primary = 'ID' . ucfirst($this->_name);
            }
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

    public static function findByPrimary($id)
    {
        return static::getInstance()->find($id);
    }

    public static function deleteByPrimary($id)
    {
        return static::findByPrimary($id)->current()->delete();
    }

    public static function create(array $data = array(), $defaultSource = null)
    {
        return static::getInstance()->createRow($data, $defaultSource);
    }

    public static function __callStatic($name, array $arguments)
    {
        switch(true) {
            case strpos($name, 'findBy') === 0 && strlen($name) > strlen('findBy'):
                $field = substr($name, strlen('findBy'));
                if(!in_array($field, static::getInstance()->info(Zend_Db_Table_Abstract::COLS)))
                    throw new Exception("Field $field is not part of the data");

                $select = static::getInstance()->select()->where("$field = ?", $arguments[0]);
                return static::getInstance()->fetchAll($select);
            break;

            case strpos($name, 'deleteBy') === 0 && strlen($name) > strlen('deleteBy'):
                $method = 'find' . substr($name, strlen('delete'));
                $rowset = static::$method($arguments[0]);
                $deleted = 0;

                foreach ($rowset as $row) {
                    $deleted += $row->delete();
                }

                return $deleted;
            break;

            case strpos($name, 'get') === 0 && strlen($name) > strlen('get'):
                $what = '_' . lcfirst(substr($name, strlen('get')));
                return static::getInstance()->$what;
            break;

            default:
                throw new Exception("Static method '$name' not implemented'");
            break;
        }
    }

    /**
     * @static
     * @return Zwe_Model
     */
    public static function getInstance()
    {
        return new static();
    }
}