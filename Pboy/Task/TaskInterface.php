<?php

namespace Pboy\Task;

interface TaskInterface
{
    
    public function execute($task);

    public function options($task);

}
