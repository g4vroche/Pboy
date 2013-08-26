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
        foreach ($this->Config->Rendering['views'] as $view => $type) {
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
        $config = $this->Config->PhpTemplate;
        extract( $config );

        $this->outputPath = $output_path;

        if ($this->Config->Rendering['views'][$view] == 'item') {
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
        $config = $this->Config->PhpTemplate;
        extract( $config );

        ob_start();

        $template = "$templates_path/$template.$templates_suffix";

        include $template;

        return ob_get_clean();
    }

}

