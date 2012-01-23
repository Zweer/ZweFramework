<?php

class Zwe_View_Helper_Img extends Zend_View_Helper_Abstract
{
    /**
     * Il contenitore di tutti i check dell'esistenza delle immagini.
     * E' un array associativo.
     *
     * @var array
     */
    protected $_exists = array();

    /**
     * Costante col binario per la generazione dell'immagine che informa che non è stata trovata l'immagine voluta.
     */
    const NoImg = 'data:image/gif;base64,R0lGODlhFAAUAIAAAAAAAP///yH5BAAAAAAALAAAAAAUABQAAAI5jI+pywv4DJiMyovTi1srHnTQd1BRSaKh6rHT2cTyHJqnVcPcDWZgJ0oBV7sb5jc6KldHUytHi0oLADs=';

    public function img($File, $Params = array())
    {
        $ParamsList = array();
        $ImagePath = null;

        if(key_exists('external', $Params) && $Params['external'] && substr($File, 0, strlen('http://')) == 'http://') {
            unset($Params['external']);
            $this->_exists[$File] = true;
            $ImagePath = $File;
        } else {
            $ImagePath = $this->view->baseUrl() . $File;
            $RealPath = realpath(PUBLIC_PATH . '/' . substr($ImagePath, strlen($this->view->baseUrl())));

            if(key_exists('url', $Params) && $Params['url'])
                return $ImagePath;

            if(!isset($this->_exists[$File]))
                $this->_exists[$File] = file_exists($RealPath);
        }

        if(!isset($Params['alt']))
            $Params['alt'] = '';

        foreach($Params as $Param => $Value)
            $ParamsList[] = $Param . '="' . $this->view->escape($Value) . '"';

        $ParamString = ' ' . implode(' ', $ParamsList);

        return '<img src="' . ($this->_exists[$File] ? $ImagePath : self::NoImg) . '"' . $ParamString . ' />';
    }
}