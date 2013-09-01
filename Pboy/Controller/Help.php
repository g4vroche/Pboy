<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Help extends Component implements ControllerInterface
{

    public function run($options = array())
    {
        $result = $this->Io->format('Pboy v.'.$this->Config['conf']['Pboy']['version']);


        $result .= $this->Io->format("Tasks:");
        $result .= $this->Io->format("------");

        foreach ($this->Config['tasks'] as $name => $params) {
            
            $result .= $this->Io->format('# '.$name);
            $result .= $this->Io->format($params['description']);

            if (isset($params['arguments'])) {
                $result .= $this->Io->format($this->Io->help($name));
            }
            
            $result .= $this->Io->format('');
        }

        return $result;
    }
}
