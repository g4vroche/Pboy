<?php

namespace Pboy\Parser;

interface ParserInterface
{
    public function parse($item);

    public function supportedFormats();

}
