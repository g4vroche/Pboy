<?php

namespace Pboy\Parser;

class MarkDown extends ParserAbstract implements ParserInterface
{

    public function parse($item)
    {
        $lines = explode("\n", $item);
        $i     = 0;
        $length = count($lines);
        
        $data = array();

        while (current($lines) != '') {

            preg_match( '/\.\. ([^:]+):([^\n]*)/i', current($lines), $matches);

            $data[$matches[1]] = trim($matches[2]);
            
            array_shift($lines);
        }
        
        $data['content'] = implode("\n", $lines);
        
        return $data;
    }
}
