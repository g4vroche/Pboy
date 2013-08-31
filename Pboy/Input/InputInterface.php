<?php

namespace Pboy\Input;

interface InputInterface
{
    public function getItemsList($source, $pattern);

    public function getItems($source, $pattern);
}

