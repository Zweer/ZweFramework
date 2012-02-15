<?php

abstract class Zwe_Log_Filter_Db_Abstract extends Zend_Log_Filter_Abstract
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var string
     */
    protected $_table = null;

    public function __construct(Zend_Db_Adapter_Abstract $db, $table)
    {
        $this->_db = $db;
        $this->_table = $table;
    }
}