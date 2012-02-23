<?php

class Zwe_View_Helper_Navigation extends Zend_View_Helper_Navigation
{
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
            if($acl instanceof Zwe_Acl)
                return $acl->isAllowedAny($role, $resource, $privilege);
            else
                return $acl->isAllowed($role, $resource, $privilege);
        }

        return true;
    }
}