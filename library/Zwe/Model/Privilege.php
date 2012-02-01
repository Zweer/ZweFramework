<?php

class Zwe_Model_Privilege extends Zwe_Model
{
    protected $_dependentTables = array('Zwe_Model_Privilege_Group',
                                        'Zwe_Model_Privilege_User');

    protected $_referenceMap = array(
        'Resource' => array(
            'columns' => 'IDResource',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource'
        )
    );
}