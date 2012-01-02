<?php

class Zwe_Form_Recover extends Zwe_Form
{
    public function initEmail()
    {
        $this->addElement('email', 'email');
        $this->getElement('email')->setLabel('Email:')->setRequired();

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Recover');
    }

    public function initPassword()
    {
        $this->addElement('hidden', 'user');
        $this->getElement('user')->addValidators(array(array('Db_RecordExists',
                                                             false,
                                                             array('table' => 'user',
                                                                   'field' => 'Username'))));

        $this->addElement('hidden', 'recover');

        $this->addElement('password', 'password');
        $this->getElement('password')->setLabel('New Password:')->setRequired();

        $this->addElement('password', 'password2');
        $this->getElement('password2')->setLabel('Repeat Password:')->setRequired()->addValidators(array(array('Identical',
                                                                                                               false,
                                                                                                               array('token' => 'password'))));

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Change Password');
    }
}