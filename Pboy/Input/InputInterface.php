<?php

namespace Pboy\Input;

interface InputInterface
{
    public function getItemsList($source);

    public function getItems($source);

    public function getItem($source, $identifier);

}

