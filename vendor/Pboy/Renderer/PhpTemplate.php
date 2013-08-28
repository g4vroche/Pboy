<?php

namespace Pboy\Renderer;

class PhpTemplate extends RendererAbstract
{

    /**
     * Renders data to output
     *
     * @param array $items  Data list
     */
    public function render($items)
    {
        foreach ($this->Config['conf']['Rendering']['views'] as $view => $type) {
            $this->renderView($view, $items);
        }
    }

    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $items
     */
    public function renderView($view, $items)
    {
        $this->outputPath = $this->Config['providers']['PhpTemplate']['output_path'];

        if ($this->Config['conf']['Rendering']['views'][$view] == 'item') {
            $this->renderItem($items, $view);

        } else {
            $this->renderList($items, $view);
        }
    }

    /**
     * Render in a file per item
     *
     * @param array $items
     * @param string $template  Template path
     */
    public function renderItem($items, $template)
    {
        $output_suffix = end(explode('.', $template));

        foreach ($items as $item) {
            $outputFile = $this->outputPath.'/'.$item['slug'].'.'.$output_suffix;

            file_put_contents($outputFile, $this->renderData($item, $template));
        }
    }

    /**
     * Render in a single file
     *
     * @param array $items
     * @param string $template  Template path
     */
    public function renderList($items, $template)
    {
        $outputFile = $this->outputPath.'/'.$template;

        file_put_contents($outputFile, $this->renderData($items, $template));
    }
    
    /**
     * Actually do the data rendering
     *
     * @param array $data   Data to use in template
     * @param string $template  Template path
     * @return string rendered data
     */
    private function renderData($data, $template)
    {
        $conf = $this->Config['providers']['PhpTemplate']['templates'];

        ob_start();
        include $conf['path'].$template.$conf['suffix'];

        return ob_get_clean();
    }

}

