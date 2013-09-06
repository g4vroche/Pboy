<?php

namespace Pboy\Hook;

use Pboy\Component\Component;

class Hook extends Component
{

    /**
     * Triggers an event
     *
     * @param string $eventName
     * @param mixed $data       Some data passed by the event caller to be altered
     *                          It is up to the caller object to expose or not some
     *                          data to any obeserver thaht will want to alter those.
     * @param object $caller    Object that triggered the event
     */
    public function notify($eventName, &$data, &$caller)
    {
        $this->event = $eventName;

        if (!isset($this->Config['hooks'][$eventName])) {
            throw new \Exception("Unknow event <$eventName>");
        }

        foreach ($this->Config['hooks'][$eventName] as $observerName => $params) {
            
            $Observer = $this->Loader->getService($observerName, $this->scope($params));
                
            $method = $this->method($params, $observerName);

            $Observer->$method($eventName, $data, $caller);
        }

    }
    
    /**
     * Gets method to be executed for given observer definition
     * from hook configuration
     *
     * @param array $params
     * @param string $observerName
     * @return string
     */
    private function method($params, $observerName)
    {
        if (!isset($params['method'])) {
            throw new \Exception('No configured method for event: '.$this->event .' observer: '.$observerName);
        } else {
            return $params['method'];
        }
    }

    /**
     * Gets the scope of an object to be loaded by Loader
     *
     * @params array $params
     * @return string
     */
    private function scope($params)
    {
        if (isset($params['scope'])) {
            return $params['scope'];
        }

        return 'global';
    }

}
