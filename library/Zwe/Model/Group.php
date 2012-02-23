<?php

class Zwe_Model_Group extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_User_Group',
                                        'Zwe_Model_Privilege_Group',
                                        'Zwe_Model_Group');

    protected $_referenceMap = array(
        'ParentGroup' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Group',
            'refColumns' => 'IDGroup',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}