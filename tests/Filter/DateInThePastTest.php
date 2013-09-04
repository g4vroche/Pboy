<?php

use Pboy\Filter\DateInThePast;


class DateInThePastTest extends PHPUnit_Framework_TestCase
{

    public function dataProvider()
    {
        return array(array(array(
            'past'   => array('date' => date('Y/m/d H:i:s', time()-3600)),
            'future' => array('date' => date('Y/m/d H:i', time()+3600)),
        )));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAlterRemovesDateInTheFuture($items)
    {
        $Filter = new DateInThePast;
        
        $this->assertEquals(
            array('past'),
            array_keys($Filter->alter($items))
        );                      


    }
    
    /**
     * @dataProvider dataProvider
     * @depends  testAlterRemovesDateInTheFuture
     */
    public function testAlterTakeDateIntoAccount($items)
    {
        $Filter = new DateInThePast;

        $this->assertEquals($items, 
            $Filter->setDate(time()+4000)
                   ->alter($items)
               );

        $this->assertEquals(array(),
            $Filter->setDate(time()-4000)
                   ->alter($items)
               );

    }

}
