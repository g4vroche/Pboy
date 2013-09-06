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
    final public function camelCaseToArray($string)
    {
        return explode(" ", 
            trim(
                preg_replace('/([A-Z])/', ' $1', $string)
            )
        );
    }
    
    /**
     * Transforms an underscore separated string into
     * a "camelCased" one
     *
     * @param string $string
     * @return string
     */
    final public function underscoreToCamelCase($string)
    {
        $callback = function($matches) { return strtoupper($matches[1]); };

        return preg_replace_callback('/_([a-z])/', $callback, $string);

    }

    /**
     * Shortcut to give a hook
     *
     * @param string $eventName
     * @param mixed $data
     * @return mixed
     */
    final public function hook($eventName, &$data = array())
    {
        return $this->Hook->notify($eventName, $data, $this);
    }
}

