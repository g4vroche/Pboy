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
        $task = ucfirst($task);

        if (!$params = $this->get($task)) {
            throw new \InvalidArgumentException("Unknow task: $task");
        }
        
        $Controller = $this->Loader->getController($task);
        
        return $Controller->run();
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

        if ($this->exists($task)) {

            if ($arguments = $this->arguments($task)) {

                foreach ($arguments as $flags => $description) {
                    $options[] = $this->parseConfiguredOption($flags, $description);
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

    /**
     * @param string
     */
    private function exists($task)
    {
        return isset($this->Config['tasks'][$task]);
    }

    
    /**
     * Parses option definition from config
     * and returns it as structured array
     *
     * @param string $flags         Key from configuration array
     * @param string $description   Option description from config
     * @return array
     */
    private function parseConfiguredOption($flags, $description)
    {
        $option     = explode('|', $flags);
        $option[2]  = (int)$option[2];
        $option[]   = $description;

        return $option;
    }
}


