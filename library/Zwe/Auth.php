<?php

class Zwe_Auth extends Zend_Auth
{
    public function getIdentity()
    {
        $identity = parent::getIdentity();
        if($identity === null)
            return null;

        $class = get_class($identity);
        if(strpos($class, '_Db_Table_Row_') !== false) {
            $table = 'Zwe_Model_' . substr($class, strpos($class, '_Db_Table_Row_') + strlen('_Db_Table_Row_'));
            $identity->setTable(new $table());
        }

        return $identity;
    }

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }
}