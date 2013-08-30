<?php

namespace Pboy\Parser;

use \Michelf\Markdown;

class MarkDownParser extends ParserAbstract implements ParserInterface
{

    public function parse($data)
    {
        $data['content'] = Markdown::defaultTransform($data['content']);
        
        return $data;
    }
}
