<?php

class Zwe_Controller_Action_Login extends Zwe_Controller_Action
{
    const LOGIN_NOTHING = 'LoginErrorOK';
    const LOGIN_CREDENTIALS = 'LoginErrorCredentials';
    const LOGIN_ALLOW = 'LoginErrorAllow';

    const REGISTRATION_OK = 'RegistrationOK';
    const REGISTRATION_EMAIL_SUBJECT = 'RegistrationEmailSubject';
    const REGISTRATION_EMAIL_TEXT = 'RegistrationEmailText';

    const ACTIVATE_OK = 'ActivateOK';
    const ACTIVATE_KO = 'ActivateKO';

    const RECOVER_EMAIL_SUBJECT = 'RecoverEmailSubject';
    const RECOVER_EMAIL_TEXT = 'RecoverEmailText';
    const RECOVER_OK = 'RecoverOK';

    const CHANGE_OK = 'ChangeOK';

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Login();

        if($this->getRequest()->isPost()) {
            $this->view->ko = self::LOGIN_NOTHING;

            if($this->view->form->isValid($this->getRequest()->getPost())) {
                if($user = Zwe_Model_User::isValidUser($this->view->form->getValue('email'), $this->view->form->getValue('password'))) {
                    if($user->canLogin()) {
                        if($this->view->form->isCookie()) {
                            setcookie('login', $user->Username, time() + 60 * 60 * 24 * 30);
                            setcookie('hash', $user->Password, time() + 60 * 60 * 24 * 30);
                        }

                        $this->_redirectAction();
                    } else {
                        $this->view->ko = self::LOGIN_ALLOW;
                    }
                } else {
                    $this->view->ko = self::LOGIN_CREDENTIALS;
                }
            } else {
                $this->view->ko = self::LOGIN_CREDENTIALS;
            }
        }
    }

    protected function _redirectAction()
    {
        $History = new Zend_Session_Namespace('History');
        $this->_redirect($History->last);
    }

    protected function _logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();

        if($_COOKIE['login']) {
            setcookie('login', false);
            setcookie('hash', false);
        }

        $this->_redirectAction();
    }

    protected function _registerAction()
    {
        $this->view->form = new Zwe_Form_Register();
        $this->view->ok = false;

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                $user = Zwe_Model_User::create(array('Username' => $this->view->form->getValue('username'),
                                                     'Email' => $this->view->form->getValue('email'),
                                                     'Password' => $this->view->form->getValue('password')));
                $user->save();

                $mail = new Zend_Mail();
                $mail->setFrom(Zend_Registry::get('parameters')->registry->email, Zend_Registry::get('parameters')->registry->emailName);
                $mail->addTo($user->Email);
                $mail->setSubject($this->view->translate(self::REGISTRATION_EMAIL_SUBJECT));
                $mail->setBodyText(str_replace(array('%site%',
                                                     '%username%',
                                                     '%password%',
                                                     '%email%',
                                                     '%link%'),
                                               array(Zend_Registry::get('parameters')->registry->siteTitle,
                                                     $user->Username,
                                                     $this->view->form->getValue('password'),
                                                     $user->Email,
                                                     $_SERVER['HTTP_HOST'] . $this->view->url(array('controller' => 'login', 'action' => 'activate', 'module' => 'default', 'activate' => sha1($user->Password . $user->Salt), 'user' => $user->Username), 'locale_default')),
                                               $this->view->translate(self::REGISTRATION_EMAIL_TEXT)));
                $mail->send();

                $this->view->ok = true;
            }
        }
    }

    protected function _activateAction()
    {
        $user = $this->_getParam('user');
        $sha = $this->_getParam('activate');
        $this->view->ok = false;

        if(Zwe_Model_User::activate($user, $sha))
            $this->view->ok = true;
    }

    protected function _recoverAction()
    {
        $this->view->form = new Zwe_Form_Recover();
        $this->view->form->initEmail();
        $this->view->ok = false;

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                $user = Zwe_Model_User::findByEmail($this->view->form->getValue('email'))->current();

                $mail = new Zend_Mail();
                $mail->setFrom(Zend_Registry::get('parameters')->registry->email, Zend_Registry::get('parameters')->registry->emailName);
                $mail->addTo($user->Email);
                $mail->setSubject($this->view->translate(self::RECOVER_EMAIL_SUBJECT));
                $mail->setBodyText(str_replace(array('%link%'), array($_SERVER['HTTP_HOST'] . $this->view->url(array('controller' => 'login', 'action' => 'doRecover', 'module' => 'default', 'recover' => sha1($user->Salt . $user->Password), 'user' => $user->Username), 'locale_default')), $this->view->translate(self::RECOVER_EMAIL_TEXT)));
                $mail->send();

                $this->view->ok = true;
            }
        }
    }

    protected function _doRecoverAction()
    {
        $user = $this->_getParam('user');
        $sha = $this->_getParam('recover');
        $this->view->ok = false;

        $this->view->form = new Zwe_Form_Recover();
        $this->view->form->initPassword();

        if($this->getRequest()->isPost()) {
            if($this->view->form->isValid($this->getRequest()->getPost())) {
                if(Zwe_Model_User::changePassword($this->view->form->getValue('user'),
                                                  $this->view->form->getValue('password'),
                                                  $this->view->form->getValue('recover'))) {
                    $this->view->ok = true;
                }
            }
        } else {
            $this->view->form->setDefault('user', $user);
            $this->view->form->setDefault('recover', $sha);
        }
    }
}