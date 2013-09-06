<?php

use Pboy\Component\Component;


class ComponentTest extends PHPUnit_Framework_TestCase
{

    public function testCamelCaseToArray()
    {
        $input = 'testCamelCaseString';

        $expected = ['test', 'Camel', 'Case', 'String'];

        $Stub = $this->getMockForAbstractClass('Pboy\Component\Component');

        $this->assertEquals( $Stub->camelCaseToArray($input), $expected);
    }


    public function testUnderscoreToCamelCase()
    {
        $input = 'test_underscored_string';

        $expected = 'testUnderscoredString';

        $Stub = $this->getMockForAbstractClass('Pboy\Component\Component');

        $this->assertEquals( $Stub->underscoreToCamelCase($input), $expected);

    }

}


