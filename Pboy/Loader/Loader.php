<?php

namespace Pboy\Loader;

use Pboy\Component\Component;

class Loader extends Component
{

    private $tree = array( 
        'global' => array(), 
        'shared' => array(),
    );
    
    public function __construct($dependencies)
    {
        parent::__construct($dependencies);

        $dependencies = $this->loadDependencies('Loader');

        $this->assignDependencies($dependencies);
    }

    /**
     * Instanciate an object for the asked service,
     * choosing the class to instantiate according to $service
     *
     * @param string $service Name of the service to load
     * @param mixed string|Pboy\Config : If string, it is the name of the class
     *  if it is a Config Object, class is retrieved from config data
     *
     * @return Object The object, with its dependecies loaded if $Config was provided
     */
    public function getService($service, $global = false, $isDependency = false)
    {
        if (!$isDependency) {
            $this->tree['shared'][$service] = array();
        }

        $class = $this->getServiceProvider($service);

        $dependencies = $this->loadDependencies($service);

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
    private function getServiceProvider($service)
    {
        $class = "\\Pboy\\$service\\";

        $configured = $this->Config['services'][$service]['Class'];

        if (substr($configured, 0, 1) == '\\') {
            $class = $configured;
        } else {
            $class .= $configured;
        }

        return $class;
    }
    
    /**
     * Instantiate dependencies for loaded object
     * and return them in an associative array
     * to be passed to constructor
     *
     * @param string $service Name of the service being loaded
     * @return array
     */
    private function loadDependencies($service)
    {
        $dependencies = array();

        foreach ($this->getDependencies($service) as $dependency => $type) {
            $method = 'load'.ucfirst($type).'Dependency';

            $dependencies[$dependency] = $this->$method($service, $dependency);
        }

        return $dependencies;
    }

    
    /**
     * Get dependencies list for a service provider
     *
     * @param string $service   Service name
     * @return array            Dependencies list
     */
    private function getDependencies($service)
    {
        $provider = $this->Config['services'][$service]['Class'];
        
        if (array_key_exists('dependencies', $this->Config['providers'][$provider])) {
            return $this->Config['providers'][$provider]['dependencies'];
        }

        return array();
    }

    /**
     * Instantiate if needed and return globally shared dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @return Object
     */
    private function loadGlobalDependency($service, $dependency)
    {
        if (!array_key_exists($dependency, $this->tree['global'])) {
            $this->tree['global'][$dependency] =
                $this->getService($dependency, false, true);
        }

        return $this->tree['global'][$dependency];
    }

    /**
     * Instantiate if needed and return locally shared dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @return Object
     */
    private function loadSharedDependency($service, $dependency)
    {
       if (!array_key_exists($dependency, $this->tree['shared'][$service])) {
           $this->tree['shared'][$service][$dependency] =
            $this->getService($dependency, false, true);
       }

       return $this->tree['shared'][$service][$dependency];
    }

    /**
     * Instantiate and return dependency
     *
     * @param string $service       Service name
     * @param string $dependency    Dependency name
     * @return Object
     */
    private function loadSingleDependency($service, $dependency)
    {
        return $this->getService($dependency, false, true);
    }



}
