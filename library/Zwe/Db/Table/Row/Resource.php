<?php

class Zwe_Db_Table_Row_Resource extends Zwe_Db_Table_Row_Tree
{
    public function __get($columnName)
    {
        switch($columnName) {
            case 'FullName':
                return ($this->IDParent == 0 ? '' : ($this->findParentRow('Zwe_Model_Resource')->FullName . '_')) . $this->Name;
            break;

            default:
                return parent::__get($columnName);
            break;
        }
    }
}