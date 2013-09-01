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

        $dependencies = $this->loadDependencies('Loader', 'service');

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

        $dependencies = $this->loadDependencies($service, 'service');

        $Service = new $class($dependencies);
        
        if ($global) {
            $this->tree['global'][$service] = $Service;
        }

        return $Service;
    }
    
    /**
     * Gets an initialized controller with its dependencies
     *
     * @param string $controller    Name of the controller
     *      Must match a class implementing ControlleInterface
     * @return object
     */
    public function getController($controller)
    {
        $namespace = 'Pboy\Controller';

        $fullControllerName = "$namespace\\$controller";

        if (!class_exists($fullControllerName)) {
            throw new \DomainException("Unknow class <$fullControllerName>");
        }

        if (!in_array("$namespace\ControllerInterface", class_implements($fullControllerName))) {
            throw new \DomainException("Class <$fullControllerName> is not a valid controller");
        }

        $dependencies = $this->loadDependencies($controller, 'controller');

        return new $fullControllerName($dependencies);
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
        $class = "\Pboy\\$service\\";

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
    private function loadDependencies($for, $type)
    {
        $dependencies = array();

        $getDependencies = 'get'.ucfirst($type).'Dependencies';

        foreach ($this->$getDependencies($for) as $dependency => $type) {
            $method = 'load'.ucfirst($type).'Dependency';

            $dependencies[$dependency] = $this->$method($for, $dependency);
        }

        if (count($dependencies) == 0) {
            $dependencies = null;
        }

        return $dependencies;
    }

    
    /**
     * Get dependencies list for a service provider
     *
     * @param string $service   Service name
     * @return array            Dependencies list
     */
    private function getServiceDependencies($service)
    {
        $provider = $this->Config['services'][$service]['Class'];
        
        if (isset($this->Config['providers'][$provider]['dependencies'])) {
            return $this->Config['providers'][$provider]['dependencies'];
        }

        return array();
    }


    /**
     * Get dependencies list for a controller
     *
     * @param string $service   controlle name
     * @return array            Dependencies list
     */
    private function getControllerDependencies($controller)
    {
        if (isset($this->Config['tasks'][$controller]['dependencies'])) {
            return $this->Config['tasks'][$controller]['dependencies'];
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
        if (!isset($this->tree['global'][$dependency])) {
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
       if (!isset($this->tree['shared'][$service][$dependency])) {
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
