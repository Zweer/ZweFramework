<?php

class Zwe_Log_Writer_Syslog extends Zend_Log_Writer_Stream
{
    public function __construct($streamOrUrl, $mode = null, $maxFileSize = 52428800) # 50 MB
    {
        parent::__construct($streamOrUrl, $mode);

        if(!is_resource($streamOrUrl)) {
            if($maxFileSize < filesize($streamOrUrl)) {
                @fclose($this->_stream);
                $bkp_fp = @fopen(substr($streamOrUrl, 0, strrpos($streamOrUrl, '.')) . '_' . strftime("%Y_%m_%d_%H_%M_%S") . substr($streamOrUrl, strrpos($streamOrUrl, '.')) . '.gz', 'w');
                fwrite($bkp_fp, gzencode(file_get_contents($streamOrUrl)));
                @fclose($bkp_fp);

                $this->_stream = @fopen($streamOrUrl, 'w+', false);
            }
        }
    }
}