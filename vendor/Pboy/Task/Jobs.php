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

}
