<?php

use Pboy\Io\Bash;

use Pboy\Task;

class BashTest extends PHPUnit_Framework_TestCase
{

    public function testWriteOutputs()
    {
        $Bash = new Bash;

        $this->expectOutputString("foo\n");

        $Bash->write('foo');
        
        $this->expectOutputString("foo\nbar\n");

        $Bash->write('bar');
    }

    public function testProgressOutputs()
    {
        $Bash = new Bash;

        $this->expectOutputString("foo\r");

        $Bash->progress('foo');

        $this->expectOutputString("foo\rbar\r");

        $Bash->progress('bar');
    }


    public function BashProvider()
    {
        // ___ Fixtures ___________________________________
        $config = array(
                        array('a', null, 0),
                        array('b', 'bar', 1),
                        array('f', 'foo', 2)
                    );
        
        $task = 'test';

        $argv   = array('./pboy', $task, '-a', '--bar=foo');

        $arguments = array('a' => 1, 'bar' => 'foo', 'b' => 'foo');

        // ________________________________________________


        $Task = $this->getMock('Task', array('options'));

        $Task->expects($this->any())
                ->method('options')
                ->will($this->returnValue($config));

        $Getopt = new Ulrichsg\Getopt;

        
        $Bash = new Bash(array( 
                'Task' => $Task, 
                'Getopt' => $Getopt
                ));

        $Bash->argv = $argv;

        return array(
            array($Bash, $task, $arguments)
        );
    }

    /**
     * @dataProvider BashProvider
     */
    public function testGetTaskName($Bash, $task)
    {
        $this->assertEquals('test', $Bash->getTask());
    }

    /**
     * @dataProvider BashProvider
     */
    public function testGetOptionsReturnParsedOptions($Bash, $task, $arguments)
    {
        $this->assertEquals($arguments, $Bash->getOptions('test'));
    }



}
