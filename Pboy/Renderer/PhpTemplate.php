<?php

namespace Pboy\Renderer;

class PhpTemplate extends RendererAbstract
{

    private $vars = array();

    /**
     * Renders data to output
     *
     * @param array $items  Data list
     */
    public function render($items, $views)
    {
        foreach ($views as $view => $type) {
            $this->renderView($view, $type, $items);
        }
    }

    public function setVariables($variables)
    {
        $this->vars = array_merge($this->vars, $variables);
    }

    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $items
     */
    public function renderView($view, $type, $items)
    {
        $method = 'render'.ucfirst($type);

        if (!is_callable(array($this,$method))){
            throw new UnexpectedValueException("Unknow render type: <$type>");
        }
        
        $this->$method($items, $view);

    }

    public function setOutputPath($path)
    {
        $this->outputPath = $path;
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
        $suffix = $this->Config['providers']['PhpTemplate']['template_suffix'];
        extract($this->vars);
        
        $file = $this->findInDesigns('Templates'.DIRECTORY_SEPARATOR.$template.$suffix);

        ob_start();
        include $file;
        $content = ob_get_clean();

        
        if (isset($layout)) {
            ob_start();
            include $this->findInDesigns('Templates'.DIRECTORY_SEPARATOR.$layout.$suffix);

            $content = ob_get_clean();
        }
        
        return $content; 
    }
    
}

