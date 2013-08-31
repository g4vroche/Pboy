<?php


namespace Pboy\Renderer;

use Pboy\Component\Component;


class Assets extends RendererAbstract
{

    public function render($items, $views)
    {
    }

    public function generate($assets, $type, $output, $compress = false)
    {        
        if ($compress) {
            $assets = $this->compress($assets, $type);
        } else {
            $assets = $this->fetch($assets, $type);
        }

        $files = $this->writeToFiles($assets, $output);

        return $files;
    }

    /**
     * Get all assets files contents into an array
     *
     * @param array $assets     List of assets
     * @return array
     */
    public function fetch($assets, $type)
    {
        foreach ($assets as $asset => $media) {
            $file = $this->findInDesigns($type.DIRECTORY_SEPARATOR.$asset);

            $assets[$asset] = array(
                'content' => file_get_contents($file),
                'media' => $media
            );
        }

        return $assets;
    }
    
    /**
     * Compresses assets
     *
     * @param array $assets     key: file name, value: [media, content]
     * @param string $type      js|css
     * @return array            Input array where contents are compressed
     *      one file per original array value
     */
    public function compress($assets, $type)
    {
        $result = array();
        
        $assets = $this->fetch($assets, $type);
        
        foreach ($assets as $asset => $value) {
            if (!isset($result[$value['media']])) {
                $result[$value['media']] = '';
            }

            $result[$value['media']] .= "\n/*$asset*/\n";
            $result[$value['media']] .= $this->{"compress$type"}($value['content']);
        }

        $assets = array();

        foreach ($result as $media => $content) {
            $name = md5($content).'.'.$type;

            $assets[$name] = array(
                'media' => $media, 
                'content' => $content
            );
        }

        return $assets;
    }

    /**
     * Writes assets to $outputDir
     *
     * @param array $assets         Key: filename, value: [content, media]
     * @param string $outputDir
     * @return array
     */
    public function writeToFiles($assets, $outputDir)
    {
        $files = array();
        foreach ($assets as $fileName => $value) {
            $files[$fileName] = $value['media'];
            file_put_contents($outputDir.DIRECTORY_SEPARATOR.$fileName, $value['content']);
        }

        return $files;
    }

    /**
     * @param string $content
     * @return string
     */
    private function compressCss($content)
    {
        $content = self::removeMultilineComments($content);
        $content = self::replaceNewLineTabReturnBySpace($content);
        $content = self::replaceMultipleSpacesBySingle($content);     
        $content = self::removeUselessSpacesFromCss($content);
 
        return $content;
    }


    /**
     * @param string $content
     * @return string
     */
    private function compressJs($content)
    {
        $content = self::removeMultilineComments($content);
        $content = self::removeInlineComments($content);
        $content = self::replaceNewLineTabReturnBySpace($content);
        $content = self::replaceMultipleSpacesBySingle($content);        
        $content = self::removeUselessSpacesFromJs($content);

        return $content;
    }


    /**
     * @param string $content
     * @return string
     */
    public static function removeMultilineComments($content)
    {
        return preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', $content);
    }


    /**
     * @param string $content
     * @return string
     */
    public static function removeInlineComments($content)
    {
        return preg_replace('#([^\'"])//([^\r\n\'"]*)[\n\r]#', '', $content);
    }


    /**
     * @param string $content
     * @return string
     */
    public static function replaceMultipleSpacesBySingle($content)
    {
        while (preg_match( '/  /', $content)) {
            $content = preg_replace( '/  /', ' ', $content);
        }

        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    public static function replaceNewLineTabReturnBySpace($content)
    {
        return preg_replace('/[\n\r\t]/', ' ', $content);
    }
    

    /**
     * @param string $content
     * @return string
     */
    public static function removeUselessSpacesFromJs($content)
    {
        return preg_replace('/[ ]?([:,;{}\(\)\r\n])[ ]?/', '\1', $content); 
    }


    /**
     * @param string $content
     * @return string
     */
    public static function removeUselessSpacesFromCss($content)
    {
        return preg_replace('/[ ]?([:,;{}\n\r])[ ]?/', '\1', $content); 
    }
}
