<?php

class Zwe_Db_Table_Row_User extends Zend_Db_Table_Row_Abstract
{
    /**
     * @var Zwe_Acl
     */
    protected $_acl = null;

    public function canLogin()
    {
        return $this->_isRegistrationActive();
    }

    protected function _isRegistrationActive()
    {
        return $this->Active == '1';
    }

    protected function _isRegistrationAllowed()
    {
        return $this->Allowed == '1';
    }

    public function setFromArray(array $data)
    {
        if(array_key_exists('Password', $data)) {
            $data['Salt'] = sha1(mt_rand());
            $data['Password'] = new Zend_Db_Expr("SHA1(CONCAT('" . $data['Password'] . "', '" . $data['Salt'] . "'))");
        }

        if($this->CreationDate == false && !array_key_exists('CreationDate', $data)) {
            $data['CreationDate'] = new Zend_Db_Expr('NOW()');
        }

        return parent::setFromArray($data);
    }

    public function changePassword($password, $code = null)
    {
        if(isset($code) && sha1($this->Salt . $this->Password) != $code)
            return false;

        $this->Salt = sha1(mt_rand());
        $this->Password = new Zend_Db_Expr("SHA1(CONCAT('$password', '" . $this->Salt . "'))");

        return $this->save();
    }

    public function equals(Zwe_Db_Table_Row_User $toCompare)
    {
        return $this->Username === $toCompare->Username;
    }

    public function __toString()
    {
        return $this->Username;
    }

    public function __get($columnName)
    {
        switch($columnName) {
            case 'Acl':
                if(!isset($this->_acl)) {
                    if(Zwe_Auth::getInstance()->hasIdentity() && Zwe_Auth::getInstance()->getIdentity()->equals($this)) {
                        if(Zend_Session::namespaceIsset('Zend_Acl') && false) {
                            $aclSession = new Zend_Session_Namespace('Zend_Acl');
                            $this->_acl = $aclSession->user;
                        } else {
                            $this->_acl = $this->_createAcl();
                            $aclSession = new Zend_Session_Namespace('Zend_Acl');
                            $aclSession->user = $this->_acl;
                        }
                    } else {
                        $this->_acl = $this->_createAcl();
                    }
                }

                return $this->_acl;
            break;

            default:
                return parent::__get($columnName);
            break;
        }
    }

    protected function _createAcl()
    {
        $acl = Zwe_Acl::getClone();
        $parents = array();

        $userGroups = $this->findDependentRowset('Zwe_Model_User_Group');
        if($userGroups) {
            foreach ($userGroups as $userGroup) {
                $parents[] = $userGroup->findParentRow('Zwe_Model_Group')->Name;
            }
        }

        $acl->addRole(new Zend_Acl_Role(Zwe_Acl::USER_ROLE), $parents);

        $userPrivileges = $this->findDependentRowset('Zwe_Model_Privilege_User');
        if($userPrivileges) {
            foreach ($userPrivileges as $userPrivilege) {
                $privilege = $userPrivilege->findParentRow('Zwe_Model_Resource');
                $whatToDo = $privilege->Deny == 1 ? 'deny' : 'allow';
                $acl->$whatToDo(Zwe_Acl::USER_ROLE, $privilege->FullName);
            }
        }

        return $acl;
    }

    public function isAllowed($resource = null, $privilege = null)
    {
        return $this->Acl->isAllowed(Zwe_Acl::USER_ROLE, $resource, $privilege);
    }

    public function isAdmin()
    {

    }
}