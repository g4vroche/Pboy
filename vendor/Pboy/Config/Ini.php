<?php

namespace Pboy\Config;

use Pboy\Component\Component;

class Ini extends ConfigAbstract
{

    /**
     * Default config file
     */
    const SUFFIX        = '.ini';

    const PATH          = 'Conf/';

    private $data = array();


    /**
     * Loads configuration into the object
     *
     * @param string $fileName  Path to config file
     * @return Pboy\Config\Ini Object (to allow chain call)
     */
    public function load($fileName)
    {
        $file = self::PATH.$fileName.self::SUFFIX;

        if (!is_readable($file)) {
            throw new \DomainException( "Wrong offset: $fileName (can't read file)");
        }

        $this->data[$fileName] = parse_ini_file($file, true);
    }

    private function loadIfNeeded($offset)
    {
        if (!$this->offsetExists($offset)) {
            $this->load($offset);
        }
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        $this->loadIfNeeded($offset);

        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

}
