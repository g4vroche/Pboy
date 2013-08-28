<?php

namespace Pboy\Io;

interface IoInterface
{
    public function write($message, $type = 'info');


    public function progress($message);


    public function read($question);

}
