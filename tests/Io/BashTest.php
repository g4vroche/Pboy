<?php

use Pboy\Io\Bash;

class BashTest extends PHPUnit_Framework_TestCase
{

    public function testWrite()
    {
        $Bash = new Bash;

        $this->expectOutputString("foo\n");

        $Bash->write('foo');
        
        $this->expectOutputString("foo\nbar\n");

        $Bash->write('bar');
    }

    public function testProgress()
    {
        $Bash = new Bash;

        $this->expectOutputString("foo\r");

        $Bash->progress('foo');

        $this->expectOutputString("foo\rbar\r");

        $Bash->progress('bar');
    }


}
