<?php

/**
 * @file library/Zwe/View/Helper/Import.php
 * Contiene la classe per gli import nell'html.
 *
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 * @version     $Id: Import.php 172 2011-12-13 13:24:43Z flicofloc@gmail.com $
 */

/**
 * Classe astratta per gli import dell'html.
 * Implementa già il costruttore e l'import, ma bisogna implementare di volta in volta l'import adatto in quanto è lì che risiede la differenza tra un import e l'altro.
 *
 * @uses        Zend_View_Helper_Abstract
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 */
abstract class Zwe_View_Helper_Import extends Zend_View_Helper_Abstract
{
    /**
     * I file da includere.
     *
     * @var array
     */
    protected $_toImport = array();
    /**
     * L'url di base da cui partire per gli include.
     * Identifica il path pubblico che tutti vedono del sito.
     *
     * @var string
     */
    protected $_baseurl = '';
    /**
     * La directory dentro cui sono locati i file.
     *
     * @var string
     */
    protected $_directory = '';

    /**
     * Il costruttore.
     * Si occupa di inizializzare $_baseurl.
     * Nei figli deve essere esteso per valorizzare $_directory e $_toImport.
     */
    public function __construct()
    {
        $URL = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $Root = '/' . trim($URL, '/');

        if('/' == $Root)
            $Root = '';

        $this->_baseurl = $Root . '/';
    }

    /**
     * Esegue l'import di un file.
     * Prende i file passati e li importa.
     * A seconda di come è settato $Absolute, i path vengono generati come assoluti o relativi (è utile per includere files fuori dalla directory di base).
     *
     * @param array|string|null $File Il/I file(s) da importare
     * @param bool $Absolute Se il percorso dev'essere assoluto e relativo
     * @param bool $Prepend Se i file da includere devono essere messi in testa o meno
     */
    protected function import($File = null, $Absolute = false, $Prepend = false)
    {
        if(isset($File))
        {
            if(is_string($File))
            {
                $F = ($Absolute ? '' : ($this->_directory . '/')) . $File;

                if($Prepend)
                    array_unshift($this->_toImport, $F);
                else
                    array_push($this->_toImport, $F);
            }
            elseif(is_array($File))
                foreach($File as $F)
                    $this->import($F, $Absolute, $Prepend);
        }
        else
        {
            $this->_toImport = array_unique($this->_toImport);
            
            foreach($this->_toImport as $F)
                $this->doImport($F);
        }
    }

    /**
     * Appende il file all'elenco degli import dell'html.
     * Deve essere implementato in ogni sottoclasse.
     *
     * @abstract
     * @param string $File
     */
    abstract protected function doImport($File);
}

?>