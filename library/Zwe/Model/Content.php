<?php

class Zwe_Model_Content extends Zwe_Model
{
    protected $_referenceMap = array(
        'ParentPage' => array(
            'columns' => 'IDPage',
            'refTableClass' => 'Zwe_Model_Page',
            'refColumns' => 'IDPage',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );
}