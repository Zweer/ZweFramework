<?php

class Zwe_Log_Filter_Db_NoRecordExists extends Zwe_Log_Filter_Db_RecordExists
{
    public function accept($event)
    {
        return !parent::accept($event);
    }
}