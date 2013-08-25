<?php


namespace Pboy\Cli;

use Pboy\Component\Component;

abstract class CliAbstract extends Component implements CliInterface
{
    public function write($message, $type = 'info')
    {
        echo $message."\n";
    }


    public function progress($message)
    {
        echo $message."\r";
    }


    public function read($question, $input)
    {
    }


    public function execute($command)
    {
    }
}
