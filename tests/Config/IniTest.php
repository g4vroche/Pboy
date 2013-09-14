<?php

use Pboy\Config\Ini;

class IniTest extends PHPUnit_Framework_TestCase
{

    public function testImplementsInterface()
    {
        $Config = new Ini;

        $this->assertInstanceOf('\Pboy\Config\ConfigInterface', $Config);

        return $Config;

    }

    /**
     * @depends testImplementsInterface
     */
    public function testFileIsLoaded($Config)
    {

        $this->assertInternalType('array', $Config['conf']);

    }

    /**
     * @depends testImplementsInterface
     */
    public function testArrayAccessReturnsSection($Config)
    {
        $this->assertArrayHasKey('Pboy', $Config['conf']);
    }

    /**
     * @depends testImplementsInterface
     */
    public function testArrayAccessReturnsVariable($Config)
    {
        $this->assertNotNull($Config['conf']['Pboy']['version']);
    }

    /**
     * @depends testImplementsInterface
     */
    public function testOffseGetNonExistingKey($Config)
    {
        $this->assertFalse(isset($Config['conf']['toto']));
    }


    /**
     * @depends testImplementsInterface
     */
    public function testOffsetSet($Config)
    {
        $Config['conf']['Pboy']['foo'] = 'bar';
        $this->assertEquals('bar', $Config['conf']['Pboy']['foo']);

        $Config['conf']['Pboy']['foo'] = 'foo';
        $this->assertEquals('foo', $Config['conf']['Pboy']['foo']);


        $Config->offsetSet('foo', 'bar');
        $this->assertEquals('bar', $Config['foo']);

        $Config->offsetUnset('foo');

        $this->assertFalse(isset($Config['foo']));
    }




}


