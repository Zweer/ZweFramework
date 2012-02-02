<?php

abstract class Zwe_Db_Table_Row_Tree extends Zend_Db_Table_Row_Abstract
{
    /**
     * @var Zend_Db_Table_Rowset_Abstract
     */
    protected $_children = null;
    protected $_childrenSet = false;

    public function __set($columnName, $value)
    {
        if(Zwe_Model_Tree::CHILDREN_KEY == $columnName) {
            if(!$this->_childrenSet) {
                if($value instanceof Zend_Db_Table_Rowset_Abstract) {
                    $this->_children = $value;
                    $this->_childrenSet = true;
                }
                else
                    throw new Exception("Invalid '" . Zwe_Model_Tree::CHILDREN_KEY . "' value");
            } else {
                throw new Exception(Zwe_Model_Tree::CHILDREN_KEY . " already set: only initialization is allowed");
            }
        } else {
            parent::__set($columnName, $value);
        }
    }

    public function __get($columnName)
    {
        if(Zwe_Model_Tree::CHILDREN_KEY == $columnName) {
            return $this->_children;
        } else {
            return parent::__get($columnName);
        }
    }
}