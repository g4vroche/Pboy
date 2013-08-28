<?php

namespace Pboy\Parser;

use \Michelf\Markdown;

class MarkDownParser extends ParserAbstract implements ParserInterface
{

    public function parse($item)
    {
        $data = $this->getMeta($item);

        $data['content'] = Markdown::defaultTransform($data['content']);
        
        return $data;
    }

    private function getMeta($item)
    {
        $data = array();
        $lines = explode("\n", $item);

        while (current($lines) != '') {
            $data = array_merge($data, $this->extractMeta(current($lines), $data));

            array_shift($lines);
        }

        $data['content'] = trim(implode("\n", $lines));

        return $data;
    }

    private function extractMeta($line)
    {
        preg_match( '/\.\. ([^:]+):([^\n]*)/i', $line, $matches);

        return array( $matches[1] => trim($matches[2]));
    }
}
