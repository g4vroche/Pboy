<?php

namespace Pboy\Config;

interface ConfigInterface
{
    public function load($fileName);

    public function get($propertyName);


}

