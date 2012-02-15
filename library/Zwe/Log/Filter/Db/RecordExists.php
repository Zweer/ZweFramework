<?php

class Zwe_Log_Filter_Db_RecordExists extends Zwe_Log_Filter_Db_Abstract
{
    /**
     * @var array
     */
    protected $_columnMap = null;

    public function __construct(Zend_Db_Adapter_Abstract $db, $table, array $columnMap)
    {
        parent::__construct($db, $table);

        $this->_columnMap = $columnMap;
    }

    public function accept($event)
    {
        $select = $this->_db->select()->from($this->_table);

        foreach ($this->_columnMap as $dbColumn => $eventColumn) {
            $select->where("$dbColumn = ?", $event[$eventColumn]);
        }

        return (bool) $this->_db->fetchRow($select);
    }

    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'db' => null,
            'table' => null,
            'columnMap' => null,
        ), $config);

        return new self(
            $config['db'],
            $config['table'],
            $config['columnMap']
        );
    }
}