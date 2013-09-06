<?php

namespace Pboy\Log;

interface LogInterface
{
    public function write($message, $type);
}
