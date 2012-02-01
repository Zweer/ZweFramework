<?php

class Zwe_Model_Resource_User extends Zwe_Model
{
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'IDUser',
            'refTableClass' => 'Zwe_Model_User',
            'refColumns' => 'IDUser'
        ),
        'Resource' => array(
            'columns' => 'IDResource',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource'
        )
    );
}