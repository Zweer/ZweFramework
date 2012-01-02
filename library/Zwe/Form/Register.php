<?php

class Zwe_Form_Register extends Zwe_Form
{
    public function init()
    {
        $this->addElement('text', 'username');
        $this->getElement('username')->setLabel('Username:');
        $this->getElement('username')->addValidators(array(array('Db_NoRecordExists',
                                                                 false,
                                                                 array('table' => 'user',
                                                                       'field' => 'Username'))));

        $this->addElement('password', 'password');
        $this->getElement('password')->setLabel('Password:');

        $this->addElement('password', 'password2');
        $this->getElement('password2')->setLabel('Repeat Password:');
        $this->getElement('password2')->addValidators(array(array('Identical',
                                                                  false,
                                                                  array('token' => 'password'))));

        $this->addElement('email', 'email');
        $this->getElement('email')->setLabel('Email Address:');
        $this->getElement('email')->addValidators(array(array('Db_NoRecordExists',
                                                              false,
                                                              array('table' => 'user',
                                                                    'field' => 'Email'))));

        $this->addElement('captcha', 'captcha', array('captcha' => array('captcha' => 'Figlet',
                                                                        'wordLen' => 4,
                                                                        'fsize' => 20,
                                                                        'height' => 60,
                                                                        'width' => 250)));
        $this->getElement('captcha')->setLabel('Write what you see:');

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Register');
    }
}