<?php


namespace Grimm\Search;


class FilterParserStackItem {
    public $fields;
    public $operator;
    public $filter = null;

    function __construct($fields, $operator)
    {
        $this->fields = $fields;
        $this->operator = $operator;
    }

}