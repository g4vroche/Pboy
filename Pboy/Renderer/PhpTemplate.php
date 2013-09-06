<?php

namespace Pboy\Renderer;

class PhpTemplate extends RendererAbstract
{

    const SUFFIX = '.php';

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

    /**
     * Append variables to an array whose elements
     * will be available in templates
     *
     * @param mixed array|string $variable  An array to merge or a key
     * @param mixed $value                  a value for key $variables
     *
     */
    public function setVariables($variables, $value = null)
    {
        if (is_array($variables)) {
            $this->vars = array_merge($this->vars, $variables);
        } elseif ($value) {
            $this->vars[$variables] = $value;
        }

        return $this;
    }

    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $items
     */
    public function renderView($view, $type, $output, $items)
    {
        $method = 'render'.ucfirst($type);

        if (!is_callable(array($this,$method))){
            throw new UnexpectedValueException("Unknow render type: <$type>");
        }

        
        $this->$method($items, $view, $output);

    }

    public function setOutputPath($path)
    {
        $this->outputPath = $path;

        return $this;
    }

    /**
     * TODO Rename this
     */
    private function getOutputFile($output, $item)
    {
        return preg_replace_callback(
                '/<([^>]+)>/', 
                function ($matches) use ($item) { return $item[$matches[1]]; },
                $output
        );
    }

    /**
     * Render in a file per item
     *
     * @param array $items
     * @param string $template  Template path
     */
    public function renderItem($items, $template, $output)
    {
        foreach ($items as $item) {

            $outputFile = $this->outputPath.'/'.$this->getOutputFile($output, $item);

            file_put_contents($outputFile, $this->renderTemplate($item, $template));
        }
    }

    /**
     * Render in a single file
     *
     * @param array $items
     * @param string $template  Template path
     */
    public function renderList($items, $template, $output)
    {
        $outputFile = $this->outputPath.'/'.$output;
        
        file_put_contents($outputFile, $this->renderTemplate($items, $template));
    }
    

    /**
     * Renders template with passed data and global data
     * If template returns a parent template, it is included
     * and last template inclusion result is returned
     * Usually parent template will display $content variable
     * which contais result from child template processing
     *
     * @param array $data       Data to be used in template
     * @param string $template  Path to template
     * @return string rendered template
     */
    private function renderTemplate($data, $template)
    {
        $template = $this->findTemplate($template);

        extract($this->vars);

        ob_start();
        include $template;
        $content = ob_get_clean();

        if (isset($TPL_parent)) {
            $this->setVariables('content', $content);

            $content = $this->renderTemplate($data, $TPL_parent);
        }

        return $content;
    }


    /**
     * Helper to retrieve template path
     *
     * @param string $name  Template name
     * @return string       Template path
     */
    private function findTemplate($name)
    {
        return $this->findInDesigns('Templates'.DIRECTORY_SEPARATOR.$name.self::SUFFIX);
    }
    
}

