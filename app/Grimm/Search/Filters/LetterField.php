<?php


namespace Grimm\Search\Filters;


use InvalidArgumentException;

class LetterField {

    protected $fields = [
        'id',
        'code',
        'language',
        'date',
        'from_id',
        'to_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'valid'
    ];

    protected $field;

    public function __construct($field)
    {
        if (!in_array($field, $this->fields)) {
            throw new InvalidArgumentException();
        }

        $this->field = $field;
    }

    public function get()
    {
        return $this->field;
    }
}