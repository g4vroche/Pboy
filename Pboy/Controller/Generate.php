<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Generate extends Component implements ControllerInterface
{

    
    public function run($options = array())
    {

        $pattern = $this->Config['providers']['FileSystem']['file_type_pattern'];

        $items = $this->Input->getItems('posts', $pattern);




        foreach ($items as $index => $item) {
            $items[$index] = $this->Parser->parse($item);
        }
        
        $views = $this->Config['conf']['Rendering']['views'];        
        $path  = $this->Config['tasks']['Generate']['output_path'];

        $this->Renderer->setOutputPath($path);
        $this->Renderer->setVariables($this->Config['conf']['Rendering']['template_vars']);

        $assets = array(
            'css' => $this->getAssets('css'),
            'js'  => $this->getAssets('js')
            );

        $this->Renderer->setVariables($assets);

        $this->Renderer->render($items, $views);

        return true;
    }

    private function getAssets($type)
    {
        $assets = $this->Config['conf']['Rendering']['assets_'.$type];
        $outputPath  = $this->Config['tasks']['Generate']['output_path'];

        $compress = $this->Config['conf']['Rendering']['compress_assets'];

        return  $this->Assets->generate($assets, ucfirst($type), $outputPath, $compress);
    }




}
