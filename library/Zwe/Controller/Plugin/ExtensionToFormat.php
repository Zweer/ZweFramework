<?php

class Zwe_Controller_Plugin_ExtensionToFormat extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $Uri = $request->getRequestUri();

        $Get = null;
        $QuestionMarkPosition = strpos($Uri, '?');
        if(false !== $QuestionMarkPosition) {
            $Get = substr($Uri, $QuestionMarkPosition + 1);
            $Uri = substr($Uri, 0, $QuestionMarkPosition);
        }

        $Extension = null;
        $PointPosition = strrpos($Uri, '.');
        if(false !== $PointPosition) {
            $Extension = substr($Uri, $PointPosition + 1);
            $Uri = substr($Uri, 0, $PointPosition);
        }

        if($Extension)
            $request->setRequestUri($Uri . ($Get ? ('?' . $Get . "&format=$Extension") : "?format=$Extension"));
    }
}