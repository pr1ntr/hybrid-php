<?php

/**
 *
 */
class Logger
{

    private $logLocation = "../logs/";

    function __construct() {
        # code...
    }

    public function write($message, $level) {

        $dateStr = date("D, d M Y H:i:s");
        $dateStrDay = date("dMY");
        $logString = "[$dateStr] [Message: $message] [Level: $level]\n";

        $file = $this->logLocation . $dateStrDay .".log";

        $fh = fopen($file, "a");
        fwrite($fh, $logString);
        fclose($fh);

    }
}

?>