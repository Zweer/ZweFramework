<?php

/**
 * http://framework.zend.com/wiki/display/ZFPROP/Zend_Image+-+Dolf+Schimmel#Zend_Image-DolfSchimmel-9.ClassSkeletons
 * http://framework.zend.com/wiki/display/ZFPROP/Zend_Image+-+Eric+Potvin
 * http://framework.zend.com/wiki/display/ZFPROP/Zend_Image+Proposal+-+Davey+Shafik
 */

class Zwe_Image
{
    const ADAPTER_GD = 'Gd';
    const ADAPTER_IMAGEMICK = 'ImageMagick';

    const FORMAT_PNG = 'png';
    const FORMAT_JPG = 'jpg';
    const FORMAT_GIF = 'gif';

    public static $contentTypes = array(self::FORMAT_PNG => 'image/png',
                                        self::FORMAT_GIF => 'image/gif',
                                        self::FORMAT_JPG => 'image/jpeg');

    protected $_adapter = null;
    protected $_imagePath = null;

    public function __construct($path = null, $adapter = self::ADAPTER_GD)
    {
        if(isset($path)) {
            $this->setImagePath($path);
        }

        $this->setAdapter($adapter);
    }

    public function setAdapter($adapter = null)
    {
        $adapterClass = 'Zwe_Image_Adapter_' . (isset($adapter) ? $adapter : $this->_detectAdapter());
        $this->_adapter = new $adapterClass();
        if(!$this->_adapter->isAvailable()) {
            throw new Zwe_Image_Exception("Adapter '$adapter' is not available");
        }

        $this->setImagePath();
    }

    protected function _detectAdapter()
    {
        if(function_exists('gd_info')) {
            return self::ADAPTER_GD;
        } elseif(class_exists('Imagick')) {
            return self::ADAPTER_IMAGEMICK;
        } else {
            return null;
        }
    }

    public function setImagePath($path = null)
    {
        if(isset($path)) {
            if(!file_exists($path)) {
                throw new Zwe_Image_Exception('Image path does not exist');
            }

            $this->_imagePath = $path;
        }

        if(isset($this->_adapter)) {
            $this->_adapter->setPath($this->_imagePath);
        }
    }

    public function render($format = self::FORMAT_PNG)
    {
        return $this->_adapter->getImage($format);
    }

    public function display($format = self::FORMAT_PNG, $sendHeader = true)
    {
        if($sendHeader) {
            header('Content-type: ' . self::$contentTypes[$format]);
        }

        echo $this->render($format);

        return true;
    }

    public function __toString()
    {
        return $this->render();
    }
}