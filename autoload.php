<?php

/**
 * PSR-0 complient autoload
 * Taken from https://gist.github.com/Thinkscape/1234504
 * Added file exists check to comply with spl_autoload
 */
function Pboy_autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($fileName)) {
        require $fileName;
    }
}

spl_autoload_register( 'Pboy_autoload' );

