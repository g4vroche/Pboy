<?php
/**
 * Handles input from file system:
 * The input data is to be found in some files
 * we will found according to configuration
 */
namespace Pboy\Input;

class FileSystem extends InputAbstract
{
 
    /**
     * Gets the list of items to process
     *
     * @param string $source Path to files
     * @return array file names
     */
    public function getItemsList($source, $pattern = null)
    {
        if (!$pattern) {
            $pattern = $this->Config['providers']['FileSystem']['file_type_pattern'];
        }

        $fileNames = scandir( $source );

        foreach ($fileNames as $index => $fileName) {
            
            if (!preg_match( $pattern, $fileName)) {
                unset($fileNames[$index]);
            }
        }

        return array_values($fileNames);
    }


    /**
     * Gets all the items to process
     *
     * @param string $source Path to files
     * @return array file contents
     */
    public function getItems($source, $pattern = null)
    {
        $items = array();

        foreach ($this->getItemsList($source, $pattern) as $itemPath) {           
            $items[$itemPath] = trim(file_get_contents("$source/$itemPath"));
        }

        return $items;
    }


}
