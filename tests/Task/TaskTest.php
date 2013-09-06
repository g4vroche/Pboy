<?php

use Pboy\Task\Task;

class testTask extends PHPUnit_Framework_TestCase
{

    public function TaskProvider()
    {   
        $Config = array(
                'tasks' => array(
                    'Foo' => array(
                        'descritpion' => 'A test task',
                        'arguments' => array(
                            'b|bar|0' => 'The bar option',
                            '|lorem|1' => 'Ipsum dolor sit amet'
                        )
                    ),
                    'Help' => array(
                        'description' => '',
                        'arguments' => array()
                    )
                ),
                'services' => array(
                    'Loader' => array('Class' => 'Loader'),
                    'Config' => array('Class' => 'Ini')

                ),
                'providers' => array(
                    'Loader' => array( 'dependencies' => array( 'Config' => 'global') )
                ),
                'hooks' => array(
                    'before_task_init' => array(),
                    'after_task_init' => array(),
                    'after_task_run' => array()
                ),
            );

        $Hook = $this->getMock('\Pboy\Hook\Hook', array('trigger'));
        $Hook->expects($this->any())
            ->method('trigger')
            ->will($this->returnValue(true));

        $Hook->Config = $Config;

        return array(array(new Pboy\Task\Task(array(
            'Config' => $Config,
            'Hook' => $Hook
        ))));
    }


    /**
     * @dataProvider TaskProvider
     */
    public function testOptionsReturnsConfiguredArgumentsAsArray($Task)
    {

        $options = array (
              array('b', 'bar', 0, 'The bar option'),
              array ('', 'lorem', 1, 'Ipsum dolor sit amet')
        ); 

        $this->assertEquals($options, $Task->options('Foo'));
    }

    /**
     * @dataProvider TaskProvider
     * @expectedException InvalidArgumentException
     */
    public function testOptionsForNonExistingTaskThrowAnExecption($Task)
    {
        $Task->options('NonExistingTask');
    }


    /**
     * @dataProvider TaskProvider
     * @expectedException InvalidArgumentException
     */
    public function testNonExistingTaskTrhowAnException($Task)
    {
        $Task->execute('bar');
    }

    
    /**
     * @dataProvider TaskProvider
     */
     public function testExecuteReturnSomeOutput($Task)
     {
         $Task->Loader = new Pboy\Loader\Loader(array('Config' => $Task->Config));

         $this->assertStringStartsWith('Pboy', $Task->execute('Help'));
     }

}

