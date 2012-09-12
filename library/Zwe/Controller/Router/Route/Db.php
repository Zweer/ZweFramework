<?php

class Zwe_Controller_Router_Route_Db extends Zend_Controller_Router_Route
{
    public static function getInstance(Zend_Config $config)
    {
        # Useless at the moment
        $reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
        # Useless at the moment
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();

        return new self($config->route, $defs, $reqs);
    }

    public function match($path, $partial = false)
    {
        if(!$partial) {
            $path = trim($path, $this->_urlDelimiter);
        }

        $page = null;
        $matched = '';
        if($path !== '') {
            $path = explode($this->_urlDelimiter, $path);
            $idParent = 0;
            $parentPage = null;

            foreach ($path as $pathPart) {
                $select = Zwe_Model_Page::getInstance()->select()->where('IDParent = ?', $idParent)
                                                                 ->where('Url = ?', $pathPart);
                $page = Zwe_Model_Page::getInstance()->fetchRow($select);

                if(isset($page)) {
                    $idParent = $page->IDPage;
                    $parentPage = $page;
                    $matched .= $pathPart . $this->_urlDelimiter;
                } elseif(isset($parentPage) && $parentPage->AllowParams) {
                    break;
                } else {
                    return false;
                }
            }

            if(isset($page)) {
                Zwe_Model_Page::setThisPage($page);
                $this->setMatchedPath(rtrim($matched, $this->_urlDelimiter));
                if(isset($page->Parameters)) {
                    parse_str($page->Parameters, $this->_values);
                }

                $this->_defaults = array('module' => $page->Module, 'controller' => $page->Controller, 'action' => $page->Action);
                return $this->_defaults + $this->_values;
            }
        }

        return false;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {

    }
}