<?php

use Pboy\Renderer\RendererAbstract;


class RendererAbstractTest extends PHPUnit_Framework_TestCase
{

    const WRITABLE_PATH = '/tmp/';

    public function testGetFilesReturnsArray()
    {
        $Stub = $this->getMockForAbstractClass('Pboy\Renderer\RendererAbstract');

        $this->assertInternalType('array', $Stub->getFiles('.'));
    }

    public function testGetFilesExcludeDotAndDoubleDotFiles()
    {
        $Stub = $this->getMockForAbstractClass('Pboy\Renderer\RendererAbstract');
        
        $this->assertNotContains('.', $Stub->getFiles('.'));
        $this->assertNotContains('..', $Stub->getFiles('.'));
    }

        
    public function testGetFilesReturnsPboyFolder()
    {
        $Stub = $this->getMockForAbstractClass('Pboy\Renderer\RendererAbstract');

        $this->assertContains('Pboy', $Stub->getFiles('.'));
    }

    public function testCreatePathIfNeeded()
    {
        $Stub = $this->getMockForAbstractClass('Pboy\Renderer\RendererAbstract');

        $dir = self::WRITABLE_PATH.'test'.md5(time());

        $this->assertTrue($Stub->createPathIfNeeded($dir.'/foo'));

        $this->assertTrue(file_exists($dir));

        rmdir($dir);

    }


    public function testCopyFileWithSubDirNotExisting()
    {
        $Stub = $this->getMockForAbstractClass('Pboy\Renderer\RendererAbstract');
        
        $dir = self::WRITABLE_PATH.'test'.md5(time()).'/';

        $source = 'Pboy/Renderer/';

        $file = 'RendererAbstract.php';
        
        $Stub->copyFile($source.$file, $dir.$source.$file);

        $this->assertTrue(file_exists($dir.$source.$file));
        

    }
}
