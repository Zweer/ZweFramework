<?php

class Zwe_Model_News extends Zwe_Model
{
    protected $_referenceMap = array(
        'Author' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser'
        )
    );

    /**
     * @param int|string  $page
     * @param int $count  OPTIONAL An SQL LIMIT count.
     * @param int $offset OPTIONAL An SQL LIMIT offset.
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function findByPage($page, $count = null, $offset = null)
    {
        $where = $this->getAdapter()->quoteInto('IDParent = ?', $page);
        return $this->fetchAll($where, 'CreationDate DESC', $count, $offset);
    }
}