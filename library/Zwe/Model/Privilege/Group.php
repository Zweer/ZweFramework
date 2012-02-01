<?php

class Zwe_Model_Privilege_Group extends Zwe_Model
{
    protected $_referenceMap = array(
        'Group' => array(
            'columns' => 'IDGroup',
            'refTableClass' => 'Zwe_Model_Group',
            'refColumns' => 'IDGroup'
        ),
        'Privilege' => array(
            'columns' => 'IDPrivilege',
            'refTableClass' => 'Zwe_Model_Privilege',
            'refColumns' => 'IDPrivilege'
        )
    );
}