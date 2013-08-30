<?php

namespace Pboy\Renderer;

class PhpTemplate extends RendererAbstract
{

    private $assets = array();

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
        $suffix = $this->Config['providers']['PhpTemplate']['template_suffix'];

        extract($this->provideMetaData());

        ob_start();
        include $this->getTemplate('Templates'.DIRECTORY_SEPARATOR.$template.$suffix);

        $content = ob_get_clean();
        
        if (isset($layout)) {
            ob_start();
            include $this->getTemplate('Templates'.DIRECTORY_SEPARATOR.$layout.$suffix);

            $content = ob_get_clean();
        }
        
        return $content; 
    }
    
    /**
     * Seeks for template file against configured designs
     * 
     * @param string $template  Template name
     */
    private function getTemplate($template)
    {
        $designRepo = $this->Config['conf']['Rendering']['designs_folder'];

        $designs = $this->Config['conf']['Rendering']['design'];
        foreach ($designs as $design) {
            $templatePath = $designRepo.DIRECTORY_SEPARATOR.$design.DIRECTORY_SEPARATOR.$template;

            if (is_readable($templatePath)) {
                return $templatePath;
            }
        }

        throw new \DomainException("Template not found: <$template>");
    }

    private function provideMetaData()
    {
        $vars = array('vars' =>  $this->Config['conf']['Rendering']['template_vars']);

        $vars['vars']['css'] = $this->getAssets('css');
        $vars['vars']['js'] = $this->getAssets('js');

        return $vars;
    }

    private function getAssets($type)
    {
        if (!isset($this->assets[$type])) {
            $assets = $this->Config['conf']['Rendering']['assets_'.$type];

            $this->assets[$type] = $this->assetsGenerate($assets, ucfirst($type), ".$type");
        }

        return $this->assets[$type];
    }

    private function assetsGenerate($assets, $type, $output)
    {
        $contents = $this->assetsCompress($assets, $type);

        $files = $this->writeAssetsFiles($contents, $output);

        return $files;
    }

    private function assetsCompress($assets, $type)
    {
        $result = array();

        foreach ($assets as $asset => $media) {
            if (!isset($result[$media])) {
                $result[$media] = '';
            }

            $content = file_get_contents($this->getTemplate($type.DIRECTORY_SEPARATOR.$asset));
            $result[$media] .= "\n/*$asset*/\n";
            $result[$media] .= $this->{"assetsCompress$type"}($content);
        }

        return $result;
    }
    
    private function writeAssetsFiles($result, $output)
    {
        $files = array();

        foreach ($result as $media => $content) {
            if (!isset($files[$media])) {
                $files[$media] = '';
            }

            $files[$media] = $media.'_'.md5($content).$output;
            file_put_contents($this->outputPath.DIRECTORY_SEPARATOR.$files[$media], $content);
        }

        return $files;
    }

    private function assetsCompressCss($content)
    {
        if (!$this->Config['conf']['Rendering']['compress_assets']) {
            return $content;
        }

        $content = self::removeMultilineComments($content);
        $content = self::replaceNewLineTabReturnBySpace($content);
        $content = self::replaceMultipleSpacesBySingle($content);     
        
        // remove spaces when css syntax does not need them
        $content = preg_replace('/[ ]?([:,;{}\n\r])[ ]?/', '\1', $content); 
        
        return $content;
    }
    
    private function assetsCompressJs($content)
    {
        if (!$this->Config['conf']['Rendering']['compress_assets']) {
            return $content;
        }

        $content = self::removeMultilineComments($content);
        $content = self::removeInlineComments($content);
        $content = self::replaceNewLineTabReturnBySpace($content);
        $content = self::replaceMultipleSpacesBySingle($content);        
        
        // remove spaces when js syntax does not need them
        $content = preg_replace('/[ ]?([:,;{}\(\)\r\n])[ ]?/', '\1', $content); 

        return $content;
    }

    public static function removeMultilineComments($content)
    {
        return preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', $content);
    }

    public static function removeInlineComments($content)
    {
        return preg_replace('#([^\'"])//([^\r\n\'"]*)[\n\r]#', '', $content);
    }

    public static function replaceMultipleSpacesBySingle($content)
    {
        // replace multiple spaces by single
        while (preg_match( '/  /', $content)) {
            $content = preg_replace( '/  /', ' ', $content);
        }

        return $content;
    }
    
    public static function replaceNewLineTabReturnBySpace($content)
    {
        return preg_replace('/[\n\r\t]/', ' ', $content);
    }
}

