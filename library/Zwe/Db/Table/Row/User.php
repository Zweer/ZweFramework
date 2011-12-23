<?php

class Zwe_Db_Table_Row_User extends Zend_Db_Table_Row_Abstract
{
    public function isAllowed()
    {
        return $this->_isActive() && $this->_isAllowed();
    }

    protected function _isActive()
    {
        return $this->Active == '1';
    }

    protected function _isAllowed()
    {
        return $this->Allowed == '1';
    }

    public function setFromArray(array $data)
    {
        if(array_key_exists('Password', $data)) {
            $data['Salt'] = sha1(mt_rand());
            $data['Password'] = new Zend_Db_Expr("SHA1(CONCAT('" . $data['Password'] . "', '" . $data['Salt'] . "'))");
        }

        if($this->CreationDate == false && !array_key_exists('CreationDate', $data)) {
            $data['CreationDate'] = new Zend_Db_Expr('NOW()');
        }

        return parent::setFromArray($data);
    }
}