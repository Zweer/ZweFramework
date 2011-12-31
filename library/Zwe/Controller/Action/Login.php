<?php

class Zwe_Controller_Action_Login extends Zwe_Controller_Action
{
    const LOGIN_NOTHING = 'LoginErrorOK';
    const LOGIN_CREDENTIALS = 'LoginErrorCredentials';
    const LOGIN_ALLOW = 'LoginErrorAllow';

    const REGISTRATION_OK = 'RegistrationOK';
    const REGISTRATION_EMAIL_SUBJECT = 'RegistrationEmailSubject';
    const REGISTRATION_EMAIL_TEXT = 'RegistrationEmailText';

    protected function _indexAction()
    {
        $this->view->form = new Zwe_Form_Login();

        if($this->getRequest()->isPost()) {
            $this->view->ko = self::LOGIN_NOTHING;

            if($this->view->form->isValid($this->getRequest()->getPost())) {
                if($user = Zwe_Model_User::isValidUser($this->view->form->getValue('email'), $this->view->form->getValue('password'))) {
                    if($user->isAllowed()) {
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
                                                     $_SERVER['HTTP_HOST'] . $this->view->url(array('controller' => 'login', 'action' => 'activate', 'module' => 'default', 'activate' => sha1($user->Password . $user->Salt), 'user' => $user->Username), 'default')),
                                               $this->view->translate(self::REGISTRATION_EMAIL_TEXT)));
                $mail->send();

                $this->view->ok = true;
            }
        }
    }
}