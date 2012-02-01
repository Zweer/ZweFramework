<?php

class Zwe_Model_Privilege_User extends Zwe_Model
{
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser'
        ),
        'Privilege' => array(
            'columns' => 'IDPrivilege',
            'refTableClass' => 'Zwe_Model_Privilege',
            'refColumns' => 'IDPrivilege'
        )
    );
}