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





}


