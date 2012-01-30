<?php

/**
 * @file library/Zwe/Controller/Action/Helper/LayoutLoader.php
 * Contains the class that handles the loading of layouts.
 *
 * @category    Zwe
 * @package     Zwe_Controller
 * @subpackage  Zwe_Controller_Action_Helper
 */

/**
 * Handles the loading of the layouts.
 * The layout is chosen by the module and the configuration.
 *
 * @uses        Zend_Controller_Action_Helper_Abstract
 * @category    Zwe
 * @package     Zwe_Controller
 * @subpackage  Zwe_Controller_Action_Helper
 */
class Zwe_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Sets the right layout.
     * First it controls if the current module has a layout file, and then applies it
     *
     * @return void
     */
	public function preDispatch()
	{
		$module = $this->getRequest()->getModuleName();
        $defaultLayoutScript = '/views/layouts';
        $layoutScript = APPLICATION_PATH . ($module == 'default' ? '' : '/modules/' . $module) . $defaultLayoutScript;
        $layout = $this->getActionController()->getHelper('layout');
        $layout->setLayout('layout');

        if(file_exists($layoutScript . 'layout.phtml'))
			$layout->setLayoutPath($layoutScript);
	}
}

?>