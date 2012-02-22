<?php

class Zwe_Model_Resource extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_Resource');

    protected $_referenceMap = array(
        'ParentResource' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}