<?php

namespace Pboy\Task;

use Pboy\Component\Component;

class Jobs extends Component
{

    public function generate($options)
    {
        $items = $this->Input->getItems('posts');

        foreach ($items as $index => $item) {
            $items[$index] = $this->Parser->parse($item);
        }

        $this->Renderer->render($items);

        return true;
    }

    public function synopsis()
    {
        $this->Io->write('Pboy v.'.$this->Config['conf']['Pboy']['version']);

        $this->Io->write("Tasks:");

        foreach ($this->Config['tasks'] as $name => $params) {
            
            $this->Io->write($name);
            $this->Io->write($params['description']);

            if (isset($params['arguments'])) {
                $this->Io->write($this->Io->help($name));
            }

            $this->Io->write('-------');
        }

        return $result;

        
    }
}
