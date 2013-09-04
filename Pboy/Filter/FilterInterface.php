<?php


namespace Pboy\Filter;


interface FilterInterface
{
    const APPLIES_TO = '';
    
    public function alter($input);

}
