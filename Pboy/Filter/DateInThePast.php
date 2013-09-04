<?php

namespace Pboy\Filter;


class DateInThePast extends FilterAbstract
{
    const APPLIES_TO = 'item_list';

    public $date;

    public function __construct($dependencies = array())
    {
        parent::__construct($dependencies);

        $this->setDate(time());
    }

    public function alter($items)
    {
        foreach ($items as $key => $item) {
            if (strtotime($item['date']) > $this->date) {
                unset($items[$key]);
            }
        }

        return $items;
    }

    public function setDate($timestamp)
    {
        $this->date = $timestamp;

        return $this;
    }

}
