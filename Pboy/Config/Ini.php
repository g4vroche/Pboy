<?php

namespace Pboy\Config;

use Pboy\Component\Component;

class Ini extends ConfigAbstract
{
    
    /**
     * To be able to switch between config
     * format we need to be able to use
     * files regardeless of their suffix
     * from outside this class.
     *
     * @var string
     */
    const SUFFIX        = '.ini';
    
    /**
     * Path to config files
     *
     * @var string
     */
    const PATH          = 'Conf/';

    /**
     * Storage array for our data
     *
     * @var array
     */
    private $data = array();


    /**
     * Loads configuration into the storage array
     *
     * @param string $fileName  Path to config file
     */
    public function load($fileName)
    {
        $file = self::PATH.$fileName.self::SUFFIX;

        if (!is_readable($file)) {
            throw new \DomainException( "Wrong offset: $fileName (can't read file)");
        }

        $this->data[$fileName] = parse_ini_file($file, true);
    }


    /**
     * Loading is dynamic, the first depth of storage array
     * being the files name.
     * This allow us to : 
     *  have an extra level with ini format
     *  lazy load the needed files (thus, only used files)
     *
     * @param mixed string|int|null $offset  Asked array offset
     * @return void
     */
    private function loadIfNeeded($offset)
    {
        if (!$this->offsetExists($offset)) {
            $this->load($offset);
        }
    }

    /* ____________________________________________________

        Below is the basic ArrayAccess implementation
        from php.net documentation.
        See: http://php.net/ArrayAccess
        ________________________________________________ */

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
