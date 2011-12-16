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
     * First it controls it is set in the config file, then associates the correct layout.
     *
     * @return void
     */
	public function preDispatch()
	{
		$Bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$Config = $Bootstrap->getOptions();
		$Module = $this->getRequest()->getModuleName();

		if(isset($Config[$Module]['resources']['layout']['layout']))
		{
			$LayoutScript = $Config[$Module]['resources']['layout']['layout'];
			$this->getActionController()->getHelper('layout')->setLayout($LayoutScript);
		}
	}
}

?>