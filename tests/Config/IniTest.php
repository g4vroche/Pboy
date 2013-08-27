<?php

use Pboy\Config\Ini;

class IniTest extends PHPUnit_Framework_TestCase
{

    const FIXTURE_FILE = 'tests/Config/fixture.ini';
    
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
        $Config->load(self::FIXTURE_FILE);        

        $this->assertInternalType('array', $Config->sectionOne);
    }

    /**
     * @depends testImplementsInterface
     */
    public function testGetData($Config)
    {
        $this->assertEquals($Config->sectionOne['simpleStringValue'], 'foo');
        
        $this->assertEquals($Config->sectionOne['numericIndexedArray'], array('lorem', 'ipsum'));
        
        $this->assertEquals($Config->sectionOne['Hash'], array('lorem' => 'foo', 'ipsum' => 'bar'));
    }

    /**
     * @depends testImplementsInterface
     */
    public function testSetData($Config)
    {
        $Config->sectionOne['bar'] = 'foo';

        $this->assertEquals($Config->sectionOne['bar'], 'foo');
    }






}


