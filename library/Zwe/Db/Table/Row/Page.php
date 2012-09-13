<?php

class Zwe_Db_Table_Row_Page extends Zwe_Db_Table_Row_Tree
{
    public function getCompleteUrl($delimiter = '/')
    {
        $url = '';
        if($parent = $this->findParentRow('Zwe_Model_Page')) {
            $url = $parent->getCompleteUrl($delimiter) . $delimiter;
        }

        return $url . $this->Url;
    }
}