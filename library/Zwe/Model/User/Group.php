<?php

class Zwe_Model_User_Group extends Zwe_Model
{
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        ),
        'Group' => array(
            'columns' => 'IDGroup',
            'refTableClass' => 'Zwe_Model_Group',
            'refColumns' => 'IDGroup',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}