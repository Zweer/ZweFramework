<?php

class Zwe_Form_Login extends Zwe_Form
{
    public function init()
    {
        $this->addElement('text', 'email');
        $this->getElement('email')->setLabel('Email:');

        $this->addElement('password', 'password');
        $this->getElement('password')->setLabel('Password:');

        $this->addElement('checkbox', 'cookie');
        $this->getElement('cookie')->setLabel('Remember credentials?');

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Login');
    }

    public function isCookie()
    {
        return $this->getValue('cookie') == '1';
    }
}