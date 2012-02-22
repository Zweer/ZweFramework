<?php

class Zwe_Model_Privilege_User extends Zwe_Model
{
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        ),
        'Privilege' => array(
            'columns' => 'IDPrivilege',
            'refTableClass' => 'Zwe_Model_Privilege',
            'refColumns' => 'IDPrivilege',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}