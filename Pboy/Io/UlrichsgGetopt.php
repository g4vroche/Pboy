<?php
/**
 * Extends Ulrichsg\Getopt https://github.com/ulrichsg/getopt-php
 * by adding a way to reset defined options
 */
namespace Pboy\Io;

use Ulrichsg\Getopt;

class UlrichsgGetopt extends Getopt
{
    
    /**
     * Resets optionList internal var
     */
    public function resetOptionList()
    {
        $this->optionList = array();
    }

}
