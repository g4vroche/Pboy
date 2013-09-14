<?php

use Pboy\Hook\Hook;

class test_observer 
{
    public function test($eventName, &$data, $caller)
    {
        $data = array('ok');
    }
}

class test_loader
{
    public function getService($service, $scope)
    {
        return new test_observer;
    }
}

class HookTest extends PHPUnit_Framework_TestCase
{

    public function HookProvider()
    {
        $Config = array(
            'hooks' => array(
                'test_event' => array(
                    'test_observer' =>  array( 'method' => 'test'),
                )
            ),
            'services' => array(
                'test_observer' => array( 'class' => 'test_observer'),
            ),
        );

        $Loader = new test_loader;

        return array(array(new Pboy\Hook\Hook(array(
            'Config' => $Config,
            'Loader' => $Loader
        ))));
    }

    /**
     * @dataProvider HookProvider
     */
    public function testNotify($Hook)
    {
        $data = array( 'foo' => 'bar' );
        $Hook->notify('test_event', $data, $this);
        
        $this->assertEquals( array('ok'), $data);
    }


}
