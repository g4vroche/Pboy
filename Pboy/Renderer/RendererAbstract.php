<?php

namespace Pboy\Renderer;

use Pboy\Component\Component;

abstract class RendererAbstract extends Component implements RendererInterface
{

    public function findInDesigns($file, $designs = null, $repository = null)
    {
        if (is_null($repository)) {
            $repository = $this->Config['conf']['Rendering']['designs_folder'];
        }

        if (is_null($designs)) {
            $designs = $this->Config['conf']['Rendering']['design'];
        }

        foreach ($designs as $design) {
            $filePath = $repository.DIRECTORY_SEPARATOR.$design.DIRECTORY_SEPARATOR.$file;

            if (is_readable($filePath)) {
                return $filePath;
            }
        }

        throw new \DomainException("File not found in any designs: <$file>");
  

    }
}

