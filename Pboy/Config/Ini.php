<?php

namespace Pboy\Config;

use Pboy\Component\Component;

class Ini extends ConfigAbstract
{

    /**
     * Default config file
     */
    const MAIN_FILE     = 'conf';

    const SUFFIX        = '.ini';

    const PATH          = 'Conf/';

    const OFFSET_SEP    = ':';

    
    private $fileName;

    private $data;
    
    public function __construct($dependencies = array())
    {
        parent::__construct($dependencies);

        $this->load(self::MAIN_FILE);
    }
    
    /**
     * Loads configuration into the object
     *
     * @param string $fileName  Path to config file
     * @return Pboy\Config\Ini Object (to allow chain call)
     */
    public function load($fileName)
    {
        $this->fileName = self::PATH.$fileName.self::SUFFIX;

        if (!is_readable($this->fileName)) {
            throw new \DomainException( "Wrong offset: $fileName (can't read file)");
        }

        $this->data[$fileName] = parse_ini_file($this->fileName, true);

        return $this;
    }

    public function get($propertyPath)
    {
        $this->loadIfNeeded($propertyPath);

        $property = $this->data;

        foreach ($this->offsets($propertyPath) as $offset) {
            $property = $this->find($property, $offset);
        }

        return $property;
    }

    private function find($property, $offset)
    {
        if (!array_key_exists($offset, $property)) {
            throw new \DomainException( "Wrong offset: $offset (no such offset)");
        }

        return $property[$offset];
    }

    private function offsets($propertyPath)
    {
        return explode(self::OFFSET_SEP, $propertyPath);
    }

    private function loadIfNeeded($propertyPath)
    {
        $fileName = current($this->offsets($propertyPath));

        if (!array_key_exists($fileName, $this->data)) {
            $this->load($fileName);
        }
    }



}
