<?php

class Zwe_Form_Element_Date extends Zend_Form_Element_Xhtml
{
    const PLACER_YEAR = '%year%';
    const PLACER_MONTH = '%month%';
    const PLACER_DAY = '%day%';

    const SEPARATOR = '-';

    protected $_dateFormat = null;

    protected $_year = null;
    protected $_month = null;
    protected $_day = null;

    protected $_validators = array('date' => array('validator' => 'Date', 'options' => array('format' => 'YYYY-mm-dd')));

    public function loadDefaultDecorators()
    {
        if($this->loadDefaultDecoratorsIsDisabled())
            return;

        $decorators = $this->getDecorators();
        if(empty($decorators))
            $this->addDecorator('Date_Select', array('diff' => $this->getAttrib('diff'),
                                                    'start' => $this->getAttrib('start'),
                                                    'end' => $this->getAttrib('end')))
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                 ->addDecorator('HtmlTag', array('tag' => 'dd', 'id' => $this->getName() . '-element'))
                 ->addDecorator('Label', array('tag' => 'dt'));
    }

    public function setDateFormat($dateFormat)
    {
        $dateFormat = str_replace(array('dd', 'mm', 'YYYY'),
                                  array(self::PLACER_DAY, self::PLACER_MONTH ,self::PLACER_YEAR),
                                  $dateFormat);

        $dateFormatPHP = str_replace(array(self::PLACER_DAY, self::PLACER_MONTH ,self::PLACER_YEAR),
                                        array('dd', 'mm', 'YYYY'),
                                        $dateFormat);

        if(strpos($dateFormat, self::PLACER_YEAR) === false || strpos($dateFormat, self::PLACER_MONTH) === false || strpos($dateFormat, self::PLACER_DAY) === false)
            throw new Exception('Invalid date format provided');

        $this->getValidator('date')->setFormat($dateFormatPHP);
        $this->removeValidator('date');

        $this->_dateFormat = $dateFormat;
        return $this;
    }

    protected function getDateFormat()
    {
        if(!isset($this->_dateFormat))
            $this->_dateFormat = self::PLACER_YEAR . self::SEPARATOR . self::PLACER_MONTH . self::SEPARATOR . self::PLACER_DAY;
        return $this->_dateFormat;
    }

    public function setDay($day)
    {
        $this->_day = (int) $day;
        return $this;
    }

    public function getDay()
    {
        return $this->_day;
    }

    public function setMonth($month)
    {
        $this->_month = (int) $month;
        return $this;
    }

    public function getMonth()
    {
        return $this->_month;
    }

    public function setYear($year)
    {
        $this->_year = (int) $year;
        return $this;
    }

    public function getYear()
    {
        return $this->_year;
    }

    public function setValue($value)
    {
        if(is_int($value))
            $this->setDay(date('d', $value))
                 ->setMonth(date('m', $value))
                 ->setYear(date('Y', $value));
        elseif(is_string($value)) {
            $date = DateTime::createFromFormat(str_replace(array(self::PLACER_YEAR, self::PLACER_MONTH, self::PLACER_DAY),
                                                           array('Y', 'm', 'd'),
                                                           $this->getDateFormat()), $value);
            $this->setDay($date->format('d'))
                 ->setMonth($date->format('m'))
                 ->setYear($date->format('Y'));
        } elseif(is_array($value) && isset($value['day']) && isset($value['month']) && isset($value['year']))
            $this->setDay($value['day'])
                 ->setMonth($value['month'])
                 ->setYear($value['year']);
        elseif(!isset($value))
            $this->setDay(date('d'))
                 ->setMonth(date('m'))
                 ->setYear((date('Y')));
        else
            throw new Exception('Invalid date value provided');

        return $this;
    }

    public function getValue()
    {
        return str_replace(array(self::PLACER_YEAR,
                                 self::PLACER_MONTH,
                                 self::PLACER_DAY),
                           array($this->getYear(),
                                 str_pad($this->getMonth(), 2, 0, STR_PAD_LEFT),
                                 str_pad($this->getDay(), 2, 0, STR_PAD_LEFT)),
                           $this->getDateFormat());
    }
}