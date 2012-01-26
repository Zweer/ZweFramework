<?php

class Zwe_Model_User extends Zwe_Model
{
    /**
     * @static
     * @param string $email
     * @param string $password
     * @return null|Zwe_Db_Table_Row_User
     */
    public static function isValidUser($email, $password, $hashed = false)
    {
        $auth = Zend_Auth::getInstance();

        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $adapter->setTableName('user')
                ->setIdentityColumn('Email')
                ->setCredentialColumn('Password');

        if(!$hashed)
            $adapter->setCredentialTreatment("SHA1(CONCAT(?, Salt))");

        $adapter->setIdentity($email)
                ->setCredential($password);


        if($auth->authenticate($adapter)) {
            $user = Zwe_Model_User::findByPrimary($adapter->getResultRowObject()->IDUser)->current();
            $auth->getStorage()->write($user);
            return $user;
        }

        return null;
    }

    public static function activate($user, $code)
    {
        $userManager = new static();
        return $userManager->update(array('Active' => '1'), "Username = '$user' AND SHA1(CONCAT(Password, Salt)) = '$code'") > 0;
    }

    public static function changePassword($user, $password, $code = null)
    {
        $user = self::findByUsername($user)->current();
        return $user->changePassword($password, $code);
    }
}