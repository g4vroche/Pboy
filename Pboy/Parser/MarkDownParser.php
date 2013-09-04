<?php

namespace Pboy\Parser;

use \Michelf\MarkdownExtra;

class MarkDownParser extends ParserAbstract implements ParserInterface
{

    public function parse($data)
    {
        $data['content'] = MarkdownExtra::defaultTransform($data['content']);
        
        return $data;
    }

    public function supportedFormats()
    {
        return array('md', 'markdown');
    }
}
