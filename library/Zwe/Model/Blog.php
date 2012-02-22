<?php

class Zwe_Model_Blog extends Zwe_Model
{
    const TEXT_PREVIEW_DIMENSION = 50;

    protected $_referenceMap = array(
        'Author' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser',
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        ),
        'Parent' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Blog',
            'refColumns' => 'IDBlog',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );

    protected $_dependentTables = array('Zwe_Model_Blog');

    /**
     * @param int|string  $page
     * @param int $count  OPTIONAL An SQL LIMIT count.
     * @param int $offset OPTIONAL An SQL LIMIT offset.
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function findByPage($page, $count = null, $offset = null)
    {
        $where = $this->getAdapter()->quoteInto('IDPage = ?', $page);
        return $this->fetchAll($where, 'CreationDate DESC', $count, $offset);
    }
}