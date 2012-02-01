<?php

class Zwe_Model_Group extends Zwe_Model
{
    protected $_dependentTables = array('Zwe_Model_User_Group',
                                        'Zwe_Model_Resource_Group');
}