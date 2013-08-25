<?php

namespace Pboy\Loader;

class Loader
{
    /**
     * Instanciate an object for the asked service,
     * choosing the class to instantiate according to $serviceName
     * and :
     *
     *  a) Configuration if $Config is given and is a Pboy\Config Object
     *  b) Given name if $Config is a string
     *  c) $serviceName if no $Config is provided
     *
     * @param string $serviceName Name of the service to load
     * @param mixed string|Pboy\Config : If string, it is the name of the class
     *  if it is a Config Object, class is retrieved from config data
     *
     * @return Object The object, with its dependecies loaded if $Config was provided
     */
    public function getService($serviceName, $Config = null)
    {
        $class = "\\Pboy\\$serviceName\\";

        $dependencies = array();
        
        if ($Config instanceof \Pboy\Config\ConfigInterface) {

            $class .= $Config->Providers[$serviceName];

            $dependencies = $this->loadDependencies($serviceName, $Config);

        } elseif(is_string($Config)) {
            $class .= $Config;

        } else {
            $class .= $serviceName;

        }

        return new $class($dependencies);
    }
    
    /**
     * Instantiate dependecies for loaded object
     * and return them in an associative array
     * to be passed to constructor
     *
     * @param string $serviceName Name of the service being loaded
     * @param Pboy\Config\ConfigInterface Object 
     * @return array
     */
    private function loadDependencies($serviceName, $Config)
    {
        $dependencies = array();

        $provider = $Config->Providers[$serviceName];
        
        if (array_key_exists('dependencies', $Config->$provider)) {

            foreach ($Config->{$provider}['dependencies'] as $dependency) {

                $dependencies[$dependency] = $this->getService($dependency, $Config);
            }
        }
        
        return $dependencies;
    }
}
