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

        $this->assertEquals(
            array( 'fixture_1.rst' => 'content of fixture 1',
                   'fixture_2.md'  => 'content of fixture 2'),
            $Fs->getItems(__DIR__, '/\.(rst|txt|md)/')
        );
    }


}
