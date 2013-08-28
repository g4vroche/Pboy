<?php

namespace Pboy\Input;

class FileSystem extends InputAbstract
{
 
    /**
     * Gets the list of items to process
     *
     * @param string $source Path to files
     * @return array file names
     */
    public function getItemsList($source)
    {
        $pattern = $this->Config['providers']['FileSystem']['file_type_pattern'];

        $fileNames = scandir( $source );

        foreach ($fileNames as $index => $fileName) {
            
            if (!preg_match( $pattern, $fileName)) {
                unset($fileNames[$index]);
            }
        }

        return $fileNames;
    }
    
    /**
     * Gets the items to process
     *
     * @param string $source Path to files
     * @return array file contents
     */
    public function getItems($source)
    {
        $items = array();

        foreach ($this->getItemsList( $source ) as $itemPath) {           
            $items[$itemPath] = file_get_contents("$source/$itemPath");
        }

        return $items;
    }
    
    /**
     *
     */
    public function getItem($source, $identifier)
    {
    }



}
