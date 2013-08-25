<?php

namespace Pboy\Config;

use Pboy\Component\Component;

class Ini extends Component implements ConfigInterface
{

    /**
     * Default config file
     */
    const MAIN_FILE = 'Conf/conf.ini';
    
    private $fileName;

    private $data;
    
    public function __construct($dependencies)
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
        $this->fileName = $fileName;

        $this->data = parse_ini_file($this->fileName, true);

        return $this;
    }
    
    public function __get($propertyName)
    {
        if (!array_key_exists($propertyName, $this->data)) {
            throw new \Exception("Unknow property <$propertyName>");
        }

        return $this->data[$propertyName];
    }

    public function __set($propertyName, $value)
    {
        $this->data[$propertyName] = $value;

        return $this;
    }

    public function save()
    {
        throw new \Exception(__METHOD__ ." is not implemented !");
    }

}
