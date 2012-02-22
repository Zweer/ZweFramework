<?php

class Zwe_Model_Privilege extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_Privilege_Group',
                                        'Zwe_Model_Privilege_User');

    protected $_referenceMap = array(
        'Resource' => array(
            'columns' => 'IDResource',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}