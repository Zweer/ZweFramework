<?php

class Zwe_Model_User extends Zwe_Model
{
    /**
     * @static
     * @param string $email
     * @param string $password
     * @return null|Zwe_Db_Table_Row_User
     */
    public static function isValidUser($email, $password)
    {
        $auth = Zend_Auth::getInstance();

        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $adapter->setTableName('user')
                ->setIdentityColumn('Email')
                ->setCredentialColumn('Password')
                ->setCredentialTreatment("SHA1(CONCAT(?, Salt))")
                ->setIdentity($email)
                ->setCredential($password);


        if($auth->authenticate($adapter)) {
            $user = Zwe_Model_User::findByPrimary($adapter->getResultRowObject()->IDUser)->current();
            $auth->getStorage()->write($user);
            return $user;
        }

        return null;
    }
}