<?php

namespace Pboy\Task;

class Task extends TaskAbstract
{
    /**
     * Executes a task
     *
     * @param string $task  Name of the task
     * @return mixed        Result returned by the execution
     */
    public function execute($task)
    {
        if (!$params = $this->get($task)) {
            throw new \InvalidArgumentException("Unknow task: $task");
        }
        
        $options = $this->getOptions($task);

        $Service = $this->Loader->getService($params['service']);

        return call_user_func(array($Service, $params['method']), $options);
    }

    /**
     * Get task description from config
     *
     * @param string $task      Task name
     * @retrun mixed array|bool Array of parameters or false if not found
     */
    private function get($task)
    {
        try{
            return $this->Config['tasks'][$task];
        }
        catch (\DomainException $e)
        {
            return false;
        }
    }


    public function getOptions($task)
    {
        extract($this->options($task));

        return getopt(implode('', $short), $long);
    }


    /**
     * Gets shell options for task
     *
     * @param string $task  Task name
     * @return array        Shell options
     */
    public function options($task)
    {
        $options = array('short' => array( 't:'), 'long' => array( 'task:') );
        
        if (array_key_exists('arguments', $this->Config['tasks'][$task])) {
            foreach ($this->Config['tasks'][$task]['arguments'] as $flags => $description) {
                $options = array_merge_recursive($options, $this->parseOption($flags));
            }
        }

        return $options;
    }
    
    private function parseOption($flags)
    {
        $options = array('short'=>array(), 'long'=>array());

        preg_match_all( '/([a-z0-9]+)(:*)/i', $flags, $matches);

        foreach ($matches[1] as $index => $match) {
            $options[$this->optionType($match)] = $match.$matches[2][$index];
        }

        return $options;
    }

    private function optionType($option)
    {
        return (strlen($option) > 1) ? 'long' : 'short';
    }

    public function synopsis()
    {
        $result = 'Pboy v.'.$this->Config['conf']['Pboy']['version']."\n";
        $result .= "Tasks:\n";

        foreach ($this->Config['tasks'] as $name => $params) {
            
            $result .= "\t$name: ". $params['description']."\n";

            if (array_key_exists('arguments', $params)) {
                $result .= "\tOptions:\n";
                foreach ($params['arguments'] as $argument => $description) {
                    $result .= "\t  $argument \t$description\n";
                }
            }
            $result .="\t---\n";
        }

        return $result;

        
    }
}


