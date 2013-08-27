<?php

namespace Pboy\Cli;

interface CliInterface
{
    public function write($message, $type = 'info');


    public function progress($message);


    public function read($question);


    public function execute($command);
}
