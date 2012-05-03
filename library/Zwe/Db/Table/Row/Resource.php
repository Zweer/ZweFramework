<?php

class Zwe_Db_Table_Row_Resource extends Zwe_Db_Table_Row_Tree
{
    protected $_privileges = null;

    public function __get($columnName)
    {
        switch($columnName) {
            case 'FullName':
                return ($this->IDParent == 0 ? '' : ($this->findParentRow('Zwe_Model_Resource')->FullName . '_')) . $this->Name;
            break;

            case Zwe_Model_Resource::PRIVILEGE_KEY:
                return $this->_privileges;
            break;

            default:
                return parent::__get($columnName);
            break;
        }
    }

    public function __set($columnName, $value)
    {
        switch($columnName) {
            case Zwe_Model_Resource::PRIVILEGE_KEY:
                $this->_privileges = $value;
            break;

            default:
                parent::__set($columnName, $value);
            break;
        }
    }
}