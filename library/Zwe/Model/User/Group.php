<?php

class Zwe_Model_User_Group extends Zwe_Model
{
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser'
        ),
        'Group' => array(
            'columns' => 'IDGroup',
            'refTableClass' => 'Zwe_Model_Group',
            'refColumns' => 'IDGroup'
        )
    );
}