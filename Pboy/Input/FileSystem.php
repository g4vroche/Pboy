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
    public function getItemsList($source, $formats, $sort = null, $limit = null)
    {
        $fileNames = scandir( $source );

        array_shift($fileNames);
        array_shift($fileNames);

        foreach ($fileNames as $index => $fileName) {
            
            if (!$this->isSupportedFormat($fileName, $formats)) {
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
    public function getItems($source, $formats, $sort = null, $limit = null)
    {
        $items = array();

        foreach ($this->getItemsList($source, $formats) as $itemPath) {

            $path = "$source/$itemPath";
            $items[$itemPath] = trim(file_get_contents($path));
            $items[$itemPath] = $this->getMeta($items[$itemPath]);

            $items[$itemPath]['format'] = $this->suffix($itemPath);
            $items[$itemPath]['updated'] = filemtime($path);
            $items[$itemPath]['summary'] = '';
        }

        uasort($items, array($this,"sortBy$sort"));

        return $items;
    }

    /**
     * Checks if format is supported by system
     * @param string $fileName
     * @param array $formats    List of supported format 
     * returned by an instance of ParserInterface::supportedFromats()
     */
    private function isSupportedFormat($fileName, $formats)
    {
        return in_array($this->suffix($fileName), $formats);
    }


    /**
     * Overload method for sorting array purpose
     * to use with uasort.
     * Callback function handled MUST be named as folow :
     * sortBy<Column><Direction> wherre direction is asc or desc.
     * eg. sortByDateDesc
     * Direction may be omited and defauts to asc.
     *
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'sortBy') === false) {
            throw new \BadMethodCallException("Unknow method $name");
        }

        $parts = $this->camelCaseToArray($name);

        if (count($parts) == 3) {
            $parts[] = 'asc';
        }
        
        return $this->sort(strtolower($parts[2]), strtolower($parts[3]), $arguments[0], $arguments[1]);
    }


    /**
     * Advanced callback function for uasort
     * via __call
     * 
     * @param string $key   Column to sort on
     * @param string $dir   Sort direction : asc|desc
     * @param mixed $a      first value to compare
     * @param mixed $b      second value to compare
     * @return int          0, 1 or -1 . see http://php.net/usort
     */
    private function sort($key, $dir, $a, $b)
    {
        $a = $a[$key];
        $b = $b[$key];

        if ($a == $b) {
            return 0;
        }

        $dir = ($dir == 'desc') ? 1 : -1;
        $sort = ($a > $b)? -1 : 1;

        return $sort*$dir;
    }

    
    /**
     *  Extracts suffix form a filename
     *
     *  @param string $fileName
     *  @return string
     */
    private function suffix($fileName)
    {
        preg_match('/\.([^\.]+)$/', $fileName, $matches);

        if (isset($matches[1])) { 
            return $matches[1];
        }

        return false;
    }


    /**
     * Extracts meta information from full text content
     *
     * @param string $item
     * @return array
     */
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

    
    /**
     * Extracts meta information from a line
     *
     * @param string $line  Textline
     * @return array        key: meta name, value: meta value
     */
    private function extractMeta($line)
    {
        preg_match( '/\.\. ([^:]+):([^\n]*)/i', $line, $matches);

        return array( $matches[1] => trim($matches[2]));
    }


}
