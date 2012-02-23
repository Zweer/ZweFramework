<?php

class Zwe_View_Helper_Navigation extends Zend_View_Helper_Navigation
{
    public function setAcl(Zwe_Acl $acl = null)
    {
        $this->_acl = $acl;
        return $this;
    }

    protected function _acceptAcl(Zend_Navigation_Page $page)
    {
        if (!$acl = $this->getAcl()) {
            // no acl registered means don't use acl
            return true;
        }

        $role = $this->getRole();
        $resource = $page->getResource();
        $privilege = $page->getPrivilege();

        if ($resource || $privilege) {
            return $acl->isAllowedAny($role, $resource, $privilege);
        }

        return true;
    }
}