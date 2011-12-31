<?php

class Zwe_Form_Recover extends Zwe_Form
{
    public function initEmail()
    {
        $this->addElement('email', 'email');
        $this->getElement('email')->setLabel('Email:');

        $this->addElement('submit', 'submit');
        $this->getElement('submit')->setLabel('Recover');
    }
}