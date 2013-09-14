<?php

namespace Pboy\Renderer;

use Pboy\Component\Component;

abstract class RendererAbstract extends Component implements RendererInterface
{

    /**
     * Find a file in the designs scalefolding
     *
     * @param string $file          Filename
     * @param array $designs        Design list
     * @param string $repository    Design folder
     * @return string               Path to file
     *  Or tiggers an exception if the file is not found
     */
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
    
    /**
     * Writes a file to file system
     *
     * @param string $path
     * @param string $content
     */ 
    public function writeFile($path, $content)
    {
        $this->createPathIfNeeded($path);

        file_put_contents($path, $content);
    }

    
    /**
     * @param string $source        Source path
     * @param string $destination   Destination path
     */
    public function copyFile($source, $destination)
    {
        $this->createPathIfNeeded($destination);

        if (is_dir($source)) {
            $files = $this->getDirectory($source);

            foreach ($files as $file) {
                $this->copyFile($file, $destination.DIRECTORY_SEPARATOR.$file);
            }

        } else {
            copy($source, $destination);
        }
    }


    /**
     * Creates a directory tree if needed
     *
     * @param string $path
     * @return bool Wether the path now exists or not
     */
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

    
    /**
     * Lists the content of a directory
     *
     * @param string $path 
     * @return array        List of files and folder
     *      excluding current and up dir pointers (. & ..)
     */
    public function getDirectory($path)
    {
        $files = scandir($path);
        array_shift($files);
        array_shift($files);
        
        return $files;
    }

}

