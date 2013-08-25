<?php

namespace Pboy\Config;

interface ConfigInterface
{
    public function load($fileName);

    public function __get($propertyName);

    public function __set($propertyName, $value);

    public function save();

}

