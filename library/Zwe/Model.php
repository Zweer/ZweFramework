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

    public static function create(array $data = array(), $defaultSource = null)
    {
        return static::getInstance()->createRow($data, $defaultSource);
    }

    public static function __callStatic($name, array $arguments)
    {
        switch(true) {
            case strpos($name, 'ByPrimary') !== false:
                $pos = strpos($name, 'ByPrimary');
                if($pos == 0)
                    throw new Exception("You must specify an action");

                $what = substr($name, 0, $pos);
                $find = eval('return static::getInstance()->find(' . implode(', ', $arguments) . ');');
                switch($what) {
                    case 'find':
                        return $find;
                    break;

                    case 'toggle':
                        if($find->count() == 0) {
                            $data = array_combine(static::getPrimary(), $arguments);
                            static::create($data)->save();
                            return true;
                        } else {
                            eval('static::deleteByPrimary(' . implode(', ', $arguments) . ');');
                            return false;
                        }
                    break;

                    case 'delete':
                        return eval('return static::findByPrimary(' . implode(', ', $arguments) . ')->current()->delete();');
                    break;
                }
            break;

            case strpos($name, 'findBy') === 0 && $name != 'findByPrimary' && strlen($name) > strlen('findBy'):
                $field = substr($name, strlen('findBy'));
                if(!in_array($field, static::getInstance()->info(Zend_Db_Table_Abstract::COLS)))
                    throw new Exception("Field $field is not part of the data");

                $select = static::getInstance()->select()->where("$field = ?", $arguments[0]);
                if(isset($arguments[1]))
                    $select->order($arguments[1]);

                return static::getInstance()->fetchAll($select);
            break;

            case strpos($name, 'deleteBy') === 0 && $name != 'deleteByPrimary' && strlen($name) > strlen('deleteBy'):
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

    public static function hasField($field)
    {
        return in_array($field, static::getInstance()->info(Zend_Db_Table_Abstract::COLS));
    }
}