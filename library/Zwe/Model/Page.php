<?php

class Zwe_Model_Page extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_Page');

    protected $_referenceMap = array(
        'ParentPage' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Page',
            'refColumns' => 'IDPage',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}