<?php

namespace Pboy\Cli;


class Bash extends CliAbstract
{
    public function write($message, $type = 'info')
    {
        echo $message."\n";
    }


    public function progress($message)
    {
        echo $message."\r";
    }


    public function read($question, $showInput = true)
    {
        $option = '';

        if (!$showInput) {
            $option = ' -s';
        }

        $command = '/usr/bin/env bash -c \'read '.$option.' -p "'. addslashes($question). ' " input && echo $input\'';

        return rtrim(shell_exec($command));
    }

    public function options($arguments)
    {
        $taskName = $arguments[1];

    }

}
