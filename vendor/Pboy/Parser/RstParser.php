<?php

namespace Pboy\Parser;


class RstParser extends ParserAbstract
{
    
    public function parse($item)
    {
        $lines = explode( "\n", $item );

        return $lines[0];
    }

}
