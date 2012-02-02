<?php

class Zwe_Model_Group extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_User_Group',
                                        'Zwe_Model_Privilege_Group');
}