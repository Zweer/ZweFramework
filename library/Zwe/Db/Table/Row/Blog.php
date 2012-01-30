<?php

class Zwe_Db_Table_Row_Blog extends Zend_Db_Table_Row_Abstract
{
    public function __get($columnName) {
        switch($columnName) {
            case 'Author':
                return $this->findParentRow('Zwe_Model_User');
            break;

            case 'Comments':
                return $this->findDependentRowset('Zwe_Model_Blog', 'Parent');
            break;

            case 'ImagePreview':
                if($this->Image != '') {
                    if($this->ImagePreview != '') {
                        return $this->Image; # TODO: dynamically cut the image
                    } else
                        return $this->Image;
                } else
                    return '';
            break;

            case 'TextPreview':
                if($this->TextPreview)
                    return $this->TextPreview;
                else {
                    if(strlen($this->Text) > Zwe_Model_Blog::TEXT_PREVIEW_DIMENSION)
                        return substr($this->Text, 0, Zwe_Model_Blog::TEXT_PREVIEW_DIMENSION) . '...';
                    else
                        return $this->Text;
                }
            break;

            default:
                return parent::__get($columnName);
            break;
        }
    }
}