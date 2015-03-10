<?php


namespace Grimm\Search\Filters;


/**
 * Class FilterValue
 * This contains a simple filter value
 * @package Grimm\Search\Filters
 */
class FilterValue {
    protected $value;

    function __construct($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }
}