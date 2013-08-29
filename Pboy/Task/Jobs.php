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
