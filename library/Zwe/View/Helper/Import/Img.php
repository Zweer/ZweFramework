<?php

/**
 * @file library/Zwe/View/Helper/Import/Img.php
 * Inserimento delle immagini nell'html.
 *
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 * @version     $Id: Img.php 165 2011-10-12 13:04:35Z flicofloc@gmail.com $
 */

/**
 * Inserisce un'immagine nell'html.
 * Prima verifica che l'immagine esista nel file system, memorizzando ogni volta l'esistenza di quella determinata immagine in modo da non dover rifare tutte le volte la ricerca.
 * Se esiste allora la importa, altrimenti incolla un'immagine pregenerata che informa che non è presente alcuna immagine (giusto per non lasciare lo spazio vuoto o l'icona di "imamgine non presente".
 * Viene usata la superclasse per non dover riscrivere il metodo __construct(), anche se non si vuole utilizzare il metodo astratto doImport(). Proprio per questo ne viene reimplementato uno vuoto.
 *
 * @uses        Zwe_View_Helper_Import
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 */
class Zwe_View_Helper_Import_Img extends Zwe_View_Helper_Import
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

    /**
     * Il costruttore dell'oggetto.
     * Oltre a quanto fatto dal padre, inizializza l'attributo $_directory.
     */
    public function __construct()
    {
        parent::__construct();

        $this->_directory = 'images';
    }

    /**
     * Esegue l'inserimento dell'immagine.
     * Prima controlla che l'immagine esista, quindi se non è stato inserito il parametro alt lo inizializza.
     * Infine crea la stringa con tutti i parametri e ritorna la stringa html con l'immagine.
     *
     * @param string $File L'immagine da inserire
     * @param array $Params L'array di parametri (title, alt...)
     * @param bool $Absolute Se l'immagine è nel path delle immagini o meno
     * @return string La stringa html col tag dell'immagine
     */
    public function import_Img($File, $Params = array(), $Absolute = false)
    {
        $ParamsList = array();
        $ImagePath = null;

        if(key_exists('external', $Params) && $Params['external'] && substr($File, 0, strlen('http://')) == 'http://')
        {
            unset($Params['external']);
            $this->_exists[$File] = true;
            $ImagePath = $File;
        }
        else
        {
            $ImagePath = $this->_baseurl . ($Absolute ? '' : $this->_directory . '/') . ltrim($File, '/');
            $RealPath = realpath(PUBLIC_PATH . '/' . substr($ImagePath, strlen($this->_baseurl)));

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

    /**
     * Non fa nulla, viene solo implementato per compatibilità con la superclasse
     *
     * @param $File
     */
    protected function doImport($File)
    {
        # do nothing
    }
}

?>