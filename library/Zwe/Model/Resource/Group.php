<?php

class Zwe_Model_Resource_Group extends Zwe_Model
{
    protected $_referenceMap = array(
        'Group' => array(
            'columns' => 'IDGroup',
            'refTableClass' => 'Zwe_Model_Group',
            'refColumns' => 'IDGroup'
        ),
        'Resource' => array(
            'columns' => 'IDResource',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource'
        )
    );
}