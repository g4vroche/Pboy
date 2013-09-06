<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Generate extends Component implements ControllerInterface
{

    public $foo;
    
    public function run($options = array())
    {
        $this->hook('before_generate');

        foreach ($this->Config['views'] as $view => $params) {
            if ($view != 'default') {
                $this->process($this->getConfig($view));
            }
        }

        $this->hook('after_generate');

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
        $this->hook('before_generate_process');

        $items = $this->fetchFromRepositories($cfg);

        $variables = array('cfg' => $cfg);
        
        $this->hook('before_render_view', $variables);

        $this->Renderer->setOutputPath($cfg['output_path'])
                       ->setVariables($cfg['template_vars'])
                       ->setVariables($variables)
                       ->renderView($cfg['template'], $cfg['type'], $items);

        $this->hook('after_generate_process', $this);
    }

    /**
     * Fetchs data from configured reposities
     *
     * @param array $cfg    Configuration for a view
     * @return array
     */
    private function fetchFromRepositories($cfg)
    {
        $items = array();

        $formats = $this->Parser->supportedFormats();

        foreach ($cfg['repositories'] as $repository) {
            $items = array_merge($items, $this->fetchFromRepository($repository, $formats));
        }

        return $items;
    }


    /**
     * 
     * @param string $repository    An id to retrieve data
     * @param array $formats        Handled document formats 
     *      (avoid loading documents that won't be parsable)
     * @return array
     */
    private function fetchFromRepository($repository, $formats)
    {
        $this->hook('before_fetch_from_repository');

        $items = $this->Input->getItems($repository, $formats);

        $this->hook('after_fetch_from_repository', $items);

        return $items;
    }


}
