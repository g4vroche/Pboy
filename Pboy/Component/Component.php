<?php


namespace Pboy\Component;

/**
 * Abstract class to be extended by any component
 * in order to get the lazy dependency injection working
 */
abstract class Component
{
    /**
     * Initialize the object with properties
     * for each dependencies
     * 
     * @param array $dependecies Hash with dependency name as key
     *     and object as value
     */
    public function __construct($dependencies = array())
    {
        if (!is_null($dependencies)) {
            $this->assignDependencies($dependencies);
        }
    }


    /**
     * @param array $dependencies
     */
    protected function assignDependencies($dependencies)
    {
        foreach ($dependencies as $dependency => $Object) {
            $this->$dependency = $Object;
        }
    }
    
    /**
     * Splits a camel case string into an array
     *
     * @param string $string
     * @return array
     */
    protected function parseCamelCase($string)
    {
        return explode(" ", 
            trim(
                preg_replace('/([A-Z])/', ' $1', $string)
            )
        );
    }
}

