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
    public function execute($task, $options = array())
    {
        if (!$params = $this->get($task)) {
            throw new \InvalidArgumentException("Unknow task: $task");
        }
        
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
        if (isset($this->Config['tasks'][$task])) {
            return $this->Config['tasks'][$task];
        }
        return false;
    }


    /**
     * Gets shell options for task
     *
     * @param string $task  Task name
     * @return array        Shell options
     */
    public function options($task)
    {
        $options = array();

        if (!$this->exists($task)) {

            if ($arguments = $this->arguments($task)) {

                foreach ($arguments as $flags => $description) {
                    $options[] = $this->parseOption($flags, $description);
                }
            }
        }

        return $options;
    }


    /**
     * Gets task arguments
     *
     * @param string $task  Task name
     * @return array|bool   Arguments array or false if non exists
     */

    private function arguments($task)
    {
        if (isset($this->Config['tasks'][$task]['arguments'])) {
            return $this->Config['tasks'][$task]['arguments'];
        }

        return false;
    }


    private function exists($task)
    {
        return isset($this->Config['tasks'][$task]);
    }


    private function parseOption($flags, $description)
    {
        $option     = explode('|', $flags);
        $option[2]  = (int)$option[2];
        $option[]   = $description;

        return $option;
    }
    

    private function optionType($option)
    {
        return (strlen($option) > 1) ? 'long' : 'short';
    }

}


