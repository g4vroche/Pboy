<?php

use Pboy\Input\FileSystem;

class testFileSystem extends PHPUnit_Framework_TestCase
{
    const WRITABLE_PATH = '/tmp/';

    public function testReturnListOfFiles()
    {
        $Fs = new FileSystem;

        $this->assertEquals( 
            array( 'fixture_1.rst', 'fixture_2.md'), 
            $Fs->getItemsList(__DIR__, array('rst', 'txt','md'))
        );
    }

    public function testGetItemsContent()
    {
        $Fs = new FileSystem;

        $keys = array('title', 'date', 'slug', 'content', 'format', 'updated', 'summary');

        $items = $Fs->getItems(__DIR__, array('rst', 'txt','md'));

        foreach ($items as $item) {
            $this->assertEquals(array_keys($item), $keys);
        }

    }

    public function testSaveItem()
    {
        $Fs = new FileSystem;

        $source = self::WRITABLE_PATH;
 
        $items = $Fs->getItems(__DIR__, array('md'));

        $item = current($items);


        $Fs->saveItem($item, $source);

        $path = self::WRITABLE_PATH.$item['slug'].'.md';

        $this->assertTrue(file_exists($path));
        
        unlink($path);
    }


    public function test__ClassForSorting()
    {
        $Fs = new FileSystem;

        $a = array( 'key' => 'y');
        $b = array( 'key' => 'z');
        
        $this->assertEquals(-1, $Fs->sortByKeyAsc($a, $b));
        $this->assertEquals(1, $Fs->sortByKeyAsc($b, $a));
        $this->assertEquals(0, $Fs->sortByKey($b, $b));
        $this->assertEquals(1, $Fs->sortByKeyDesc($a, $b));
        $this->assertEquals(-1, $Fs->sortByKeyDesc($b, $a));
    }

    public function testGlobally__CallForSorting()
    {
        $Fs = new FileSystem;

        $data = array(
            array('key' => 'b'),
            array('key' => 'c'),
            array('key' => 'a'),
        );


        $expects = array(
            array('key' => 'a'),
            array('key' => 'b'),
            array('key' => 'c'),
        );
        
        uasort($data, array($Fs, "sortByKeyAsc"));

        $data = array_values($data);


        $this->assertEquals(
            $expects,
            $data
        );

    }


}
