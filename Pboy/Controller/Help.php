<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Help extends Component implements ControllerInterface
{

    public function run($options = array())
    {
        $this->Io->write('Pboy v.'.$this->Config['conf']['Pboy']['version']);


        $this->Io->write("Tasks:");
        $this->Io->write("------");
        

        $indent = " ";

        foreach ($this->Config['tasks'] as $name => $params) {
            
            $this->Io->write('# '.$name);
            $this->Io->write($params['description']);

            if (isset($params['arguments'])) {
                $this->Io->write($this->Io->help($name));
            }
            
            $this->Io->write('');
        }

        return false;
    }
}
