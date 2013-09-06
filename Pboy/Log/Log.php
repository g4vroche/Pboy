<?php

namespace Pboy\Log;

class Log extends LogAbstract
{

    public function write($message, $type)
    {
        file_put_contents(
            '/tmp/pboy.log',
            $this->format($message),
            FILE_APPEND
        );
    }

    public function writeEvent($eventName, $data) 
    {
        $this->write($eventName, 'NOTICE');
    }

    private function format($message)
    {
        return date('Y/m/d h:i:s', time())." $message\n";
    }
}
