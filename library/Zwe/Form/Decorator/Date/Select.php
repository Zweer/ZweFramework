<?php

class Zwe_Form_Decorator_Date_Select extends Zend_Form_Decorator_Abstract
{
    protected $_days = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);
    protected $_months = null;
    protected $_years = null;

    public function render($content)
    {
        $element = $this->getElement();
        if(!$element instanceof Zwe_Form_Element_Date)
            return $content;

        $view = $element->getView();
        if(!$view instanceof Zend_View_Interface)
            return $content;

        $day = $element->getDay();
        $month = $element->getMonth();
        $year = $element->getYear();
        $name = $element->getFullyQualifiedName();

        $date  = $view->formSelect($name . '[day]', $day, null, $this->getDays()) . ' ';
        $date .= $view->formSelect($name . '[month]', $month, null, $this->getMonths()) . ' ';
        $date .= $view->formSelect($name . '[year]', $year, null, $this->getYears());

        switch($this->getPlacement())
        {
            case self::PREPEND:
                return $date . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $date;
        }
    }

    protected function getDays()
    {
        return $this->_days;
    }

    protected function getMonths()
    {
        if(!isset($this->_months))
            $this->_months = Zend_Locale::getTranslationList('month');

        return $this->_months;
    }

    protected function getYears()
    {
        if(!isset($this->_years))
        {
            $diff = $this->getOption('diff');
            $start = $this->getOption('start');
            $end = $this->getOption('end');

            switch(!isset($start) || !isset($end)) {
                case strpos($diff, '-') !== false && strpos($diff, '+') !== false:
                    $start = date('Y') + intval(substr($diff, strpos($diff, '-')));
                    $end = date('Y') + intval(substr($diff, strpos($diff, '+')));
                break;
                case substr($diff, 0, 1) == '+':
                case substr($diff, 0, 1) == '-':
                    $diff = intval($diff);
                case is_int($diff):
                    if($diff > 0) {
                        $start = date('Y');
                        $end = $start + $diff;
                    } else {
                        $end = date('Y');
                        $start = $end + $diff;
                    }
                break;

                case isset($start):
                    $end = date('Y');
                break;
                case isset($end):
                    $start = date('Y');
                break;

                default:
                    $diff = 1900;
                    if(date('Y') > $diff) {
                        $start = $diff;
                        $end = date('Y');
                    } else {
                        $start = date('Y');
                        $end = $diff;
                    }
                break;
            }

            foreach(range($start, $end) as $year)
                $this->_years[$year] = $year;
        }

        return $this->_years;
    }
}