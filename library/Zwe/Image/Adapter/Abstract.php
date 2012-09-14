<?php

abstract class Zwe_Image_Adapter_Abstract
{
    protected $_imagePath = null;

    public function setPath($imagePath)
    {
        $this->_imagePath = $imagePath;

        $this->create();
    }

    public function create($format = Zwe_Image::FORMAT_PNG)
    {
        if(isset($this->_imagePath)) {
            $this->_createFromSource();
        } else {
            $this->_createFromNew($format);
        }
    }

    protected abstract function _createFromSource();

    protected abstract function _createFromNew($format);
}