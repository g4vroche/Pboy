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
        foreach ($dependencies as $dependency => $Object) {
            $this->$dependency = $Object;
        }
    }
}

