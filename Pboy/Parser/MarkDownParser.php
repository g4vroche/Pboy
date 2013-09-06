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

    public function parseItems($eventName, &$items, &$caller)
    {
        foreach ($items as $index => $item) {
            $items[$index] = $this->parse($item);
        }

    }
}
