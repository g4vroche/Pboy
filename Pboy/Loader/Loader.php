<?php

namespace Pboy\Loader;

class Loader
{

    private $tree = array( 
        'global' => array(), 
        'shared' => array(),
    );

    /**
     * Instanciate an object for the asked service,
     * choosing the class to instantiate according to $service
     * and :
     *
     *  a) Configuration if $Config is given and is a Pboy\Config Object
     *  b) Given name if $Config is a string
     *  c) $service if no $Config is provided
     *
     * @param string $service Name of the service to load
     * @param mixed string|Pboy\Config : If string, it is the name of the class
     *  if it is a Config Object, class is retrieved from config data
     *
     * @return Object The object, with its dependecies loaded if $Config was provided
     */
    public function getService($service, $Config = null, $global = false, $isDependency = false)
    {
        if (!$isDependency) {
            $this->tree['shared'][$service] = array();
        }

        $class = $this->getServiceProvider($service, $Config);

        $dependencies = $this->loadDependencies($service, $Config);

        $Service = new $class($dependencies);
        
        if ($global) {
            $this->tree['global'][$service] = $Service;
        }

        return $Service;
    }

    /**
     * Gets the class to instantiate
     *
     * @param string $service   Name of the service to instantiate
     * @param mixed $Config     See below code...
     * @return string           Full classname
     */
    private function getServiceProvider($service, $Config)
    {
        $class = "\\Pboy\\$service\\";

        if ($this->hasConfigObject($Config)) {
            $class .= $Config->Providers[$service];

        } elseif(is_string($Config)) {
            $class .= $Config;

        } else {
            $class .= $service;
        }

        return $class;
    }
    
    /**
     * Instantiate dependencies for loaded object
     * and return them in an associative array
     * to be passed to constructor
     *
     * @param string $service Name of the service being loaded
     * @param Pboy\Config\ConfigInterface Object 
     * @return array
     */
    private function loadDependencies($service, $Config)
    {
        $dependencies = array();

        if (!$this->hasConfigObject($Config)) {
            return $dependencies;
        }

        foreach ($this->getDependencies($service, $Config) as $dependency => $type) {
            $method = 'load'.ucfirst($type).'Dependency';

            $dependencies[$dependency] = $this->$method($service, $dependency, $Config);
        }

        return $dependencies;
    }

    
    /**
     * Get dependencies list for a service provider
     *
     * @param string $service   Service name
     * @param Pboy\Config\ConfigInterface $Config
     * @return array            Dependencies list
     */
    private function getDependencies($service, $Config)
    {
        $provider = $Config->Providers[$service];
        
        if (array_key_exists('dependencies', $Config->$provider)) {
            return $Config->{$provider}['dependencies'];
        }

        return array();
    }

    /**
     * Instantiate if needed and return globally shared dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @param Pboy\Config\ConfigInterface $Config
     * @return Object
     */
    private function loadGlobalDependency($service, $dependency, $Config)
    {
        if (!array_key_exists($dependency, $this->tree['global'])) {
            $this->tree['global'][$dependency] =
                $this->getService($dependency, $Config, false, true);
        }

        return $this->tree['global'][$dependency];
    }

    /**
     * Instantiate if needed and return locally shared dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @param Pboy\Config\ConfigInterface $Config
     * @return Object
     */
    private function loadSharedDependency($service, $dependency, $Config)
    {
       if (!array_key_exists($dependency, $this->tree['shared'][$service])) {
           $this->tree['shared'][$service][$dependency] =
            $this->getService($dependency, $Config, false, true);
       }

       return $this->tree['shared'][$service][$dependency];
    }

    /**
     * Instantiate and return dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @param Pboy\Config\ConfigInterface $Config
     * @return Object
     */
    private function loadSingleDependency($service, $dependency, $Config)
    {
        return $this->getService($dependency, $Config, false, true);
    }

    private function hasConfigObject($variable)
    {
        return ($variable instanceof \Pboy\Config\ConfigInterface);
    }

}
