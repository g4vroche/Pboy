<?php

use Pboy\Cli\Cli;

class CliTest extends PHPUnit_Framework_TestCase
{

    public function testWrite()
    {
        $Cli = new Cli;

        $this->expectOutputString("foo\n");

        $Cli->write('foo');
        
        $this->expectOutputString("foo\nbar\n");

        $Cli->write('bar');
    }

    public function testProgress()
    {
        $Cli = new Cli;

        $this->expectOutputString("foo\r");

        $Cli->progress('foo');

        $this->expectOutputString("foo\rbar\r");

        $Cli->progress('bar');
    }


}
