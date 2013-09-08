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
    
    public function writeFile($path, $content)
    {
        $this->createPathIfNeeded($path);

        file_put_contents($path, $content);
    }

    public function copyFile($source, $destination)
    {
        $this->createPathIfNeeded($destination);

        if (is_dir($source)) {
            $files = $this->getFiles($source);

            foreach ($files as $file) {
                $this->copyFile($file, $destination.DIRECTORY_SEPARATOR.$file);
            }

        } else {
            copy($source, $destination);
        }
    }

    public function createPathIfNeeded($path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        
        array_pop($parts);

        $dirPath = implode(DIRECTORY_SEPARATOR, $parts);
        
        if (!is_dir($dirPath)) {
            return mkdir($dirPath, 0777, true);
        }

        return true;
    }


    public function getFiles($path)
    {
        $files = scandir($path);
        array_shift($files);
        array_shift($files);
        
        return $files;
    }

}

