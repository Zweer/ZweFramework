<?php

/**
 * @file library/Zwe/View/Helper/Import/Css.php
 * Import dei file css.
 *
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 * @version     $Id: Css.php 172 2011-12-13 13:24:43Z flicofloc@gmail.com $
 */

/**
 * Classe che effettua l'import dei fogli di stile nell'html.
 *
 * @uses        Zwe_View_Helper_Import
 * @category    Zwe
 * @package     Zwe_View
 * @subpackage  Zwe_View_Helper_Import
 */
class Zwe_View_Helper_Import_Css extends Zwe_View_Helper_Import
{
    /**
     * Il costruttore dell'oggetto.
     * Oltre a quanto fatto dal padre inizializza l'attributo $_directory.
     */
    public function __construct()
    {
        $this->_directory = 'css';

        parent::__construct();
    }

    /**
     * Rinomina import() per renderlo compatibile con la visione delle view in Zend
     *
     * @param array|string|null $File Il/I file(s) da importare
     * @param bool $Absolute Se il percorso dev'essere assoluto e relativo
     * @param bool $Prepend Se i file da includere devono essere messi in testa o meno
     */
    public function import_Css($File = null, $Absolute = false, $Prepend = false)
    {
        $this->import($File, $Absolute, $Prepend);
    }

    /**
     * Appende il foglio di stile all'elenco di quelli da importare.
     *
     * @param string $File
     */
    protected function doImport($File)
    {
         $this->view->headLink()->appendStylesheet((strpos($File, 'http://') !== false ? '' : $this->_baseurl) . $File . '.css');
    }
}

?>