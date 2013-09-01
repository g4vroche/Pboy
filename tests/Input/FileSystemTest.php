<?php

use Pboy\Input\FileSystem;

class testFileSystem extends PHPUnit_Framework_TestCase
{


    public function testReturnListOfFiles()
    {
        $Fs = new FileSystem;

        $this->assertEquals( 
            array( 'fixture_1.rst', 'fixture_2.md'), 
            $Fs->getItemsList(__DIR__, '/\.(rst|txt|md)/')
        );
    }

    public function testGetItemsContent()
    {
        $Fs = new FileSystem;

        $keys = array('title', 'date', 'content', 'updated', 'summary');

        $items = $Fs->getItems(__DIR__, '/\.(rst|txt|md)/');

        foreach ($items as $item) {
            $this->assertEquals(array_keys($item), $keys);
        }

    }


}
