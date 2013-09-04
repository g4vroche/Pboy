<?php

namespace Pboy\Input;

interface InputInterface
{

    public function getItemsList($source, $filters, $sort = null, $limit = null);

    public function getItems($source, $filters, $sort = null, $limit = null);

}

