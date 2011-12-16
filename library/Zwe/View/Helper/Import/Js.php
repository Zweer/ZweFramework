<?php

/**
 * @file library/Zwe/View/Helper/Import/Js.php
 * Import dei file css.
 *
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 * @version     $Id: Js.php 172 2011-12-13 13:24:43Z flicofloc@gmail.com $
 */

/**
 * Classe che effettua l'import degli script javascript nell'html.
 *
 * @uses        Zwe_View_Helper_Import
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 */
class Zwe_View_Helper_Import_Js extends Zwe_View_Helper_Import
{
    /**
     * Il costruttore dell'oggetto.
     * Oltre a quanto fatto dal padre inizializza l'attributo $_directory.
     */
    public function __construct()
    {
        $this->_directory = 'js';

        parent::__construct();
    }

    /**
     * Rinomina import() per renderlo compatibile con la visione delle view in Zend
     *
     * @param array|string|null $File Il/I file(s) da importare
     * @param bool $Absolute Se il percorso dev'essere assoluto e relativo
     * @param bool $Prepend Se i file da includere devono essere messi in testa o meno
     */
    public function import_Js($File = null, $Absolute = false, $Prepend = false)
    {
        $this->import($File, $Absolute, $Prepend);
    }

    /**
     * Appende il il file di script all'elenco di quelli da importare.
     * 
     * @param string $File
     */
    protected function doImport($File)
    {
         $this->view->headScript()->appendFile($this->_baseurl . $File . '.js');
    }
}

?>