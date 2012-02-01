<?php

class Zwe_Model_Resource extends Zwe_Model
{
    protected $_dependentTable = array('Zwe_Model_Permission',
                                       'Zwe_Model_Resource');

    protected $_referenceMap = array(
        'ParentResource' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Resource',
            'refColumns' => 'IDResource'
        )
    );
}