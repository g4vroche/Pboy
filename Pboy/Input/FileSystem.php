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
    public function getItemsList($source, $pattern)
    {
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
    public function getItems($source, $pattern)
    {
        $items = array();

        foreach ($this->getItemsList($source, $pattern) as $itemPath) {

            $path = "$source/$itemPath";
            $items[$itemPath] = trim(file_get_contents($path));
            $items[$itemPath] = $this->getMeta($items[$itemPath]);
            $items[$itemPath]['updated'] = filemtime($path);
            $items[$itemPath]['summary'] = '';
        }

        uasort($items, array($this,'dateSort'));

        return $items;
    }

    private function dateSort($a, $b)
    {
        $a = strtotime($a['date']);
        $b = strtotime($b['date']);

        if ($a == $b) {
            return 0;
        }

        return ($a > $b)? -1 : 1;
    }


    private function getMeta($item)
    {
        $data = array();
        $lines = explode("\n", $item);

        while (current($lines) != '') {
            $data = array_merge($data, $this->extractMeta(current($lines), $data));

            array_shift($lines);
        }

        $data['content'] = trim(implode("\n", $lines));

        return $data;
    }


    private function extractMeta($line)
    {
        preg_match( '/\.\. ([^:]+):([^\n]*)/i', $line, $matches);

        return array( $matches[1] => trim($matches[2]));
    }


}
