<?php

class Zwe_Validate_Url extends Zend_Validate_Abstract
{
    const URL = 'notUrl';

    protected $_messageTemplates = array(self::URL => "'%value% is not a valid url");

    public function isValid($value)
    {
        $this->_setValue($value);

        if($value != preg_replace('/[^a-zA-Z0-9_-]/u', '', (string) $value)) {
            $this->_error(self::URL);
            return false;
        }

        return true;
    }
}