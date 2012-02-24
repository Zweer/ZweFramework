<?php

class Zwe_View_Helper_Navigation_Menu extends Zend_View_Helper_Navigation_Menu
{
    protected function _acceptAcl(Zend_Navigation_Page $page)
    {
        $role = $this->getRole();
        $resource = $page->getResource();
        $privilege = $page->getPrivilege();

        if (!$acl = $this->getAcl()) {
            if(isset($resource) || isset($privilege)) {
                return false;
            } else {
                return true;
            }
        }

        if ($resource || $privilege) {
            try {
                if($acl instanceof Zwe_Acl)
                    return $acl->isAllowedAny($role, $resource, $privilege);
                else
                    return $acl->isAllowed($role, $resource, $privilege);
            } catch (Exception $e) {
                return false;
            }
        }

        return true;
    }
}