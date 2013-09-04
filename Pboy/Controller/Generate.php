<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Generate extends Component implements ControllerInterface
{

    
    public function run($options = array())
    {
        foreach ($this->Config['views'] as $view => $params) {
            if ($view != 'default') {
                $this->process($this->getConfig($view));
            }
        }

        return true;
    }


    /**
     * Retruns config with default setted
     *
     * @param string $view  View name
     * @return array
     */
    private function getConfig($view)
    {
        return array_merge(
            $this->Config['views']['default'],
            $this->Config['views'][$view]
        );
    }

    public function process($cfg)
    {
        $formats = $this->Parser->supportedFormats();

        $items = array();
        foreach ($cfg['repositories'] as $repository) {
            $items = array_merge($items, $this->fetchData($repository, $formats));
        }

        $assets = array(
            'css' => $this->getAssets('css', $cfg),
            'js'  => $this->getAssets('js', $cfg)
            );

        $this->Renderer->setOutputPath($cfg['output_path'])
                       ->setVariables($cfg['template_vars'])
                       ->setVariables($assets)
                       ->renderView($cfg['template'], $cfg['type'], $items);
    }


    /**
     * 
     * @param string $repository    An id to retrieve data
     * @param array $formats        Handled document formats 
     *      (avoid loading documents that won't be parsable)
     * @return array
     */
    private function fetchData($repository, $formats)
    {
        $items = $this->Input->getItems($repository, $formats);

        foreach ($items as $index => $item) {
            $items[$index] = $this->Parser->parse($item);
        }

        return $items;
    }


    /**
     * Generate assets if needed and return their paths
     *
     * @param string $type
     * @param array $cfg
     * @return array
     */
    private function getAssets($type, $cfg)
    {
        return  $this->Assets->generate(
                    $cfg["assets_$type"], 
                    ucfirst($type), 
                    $cfg['output_path'], 
                    $cfg['compress_assets']
                );
    }

}
