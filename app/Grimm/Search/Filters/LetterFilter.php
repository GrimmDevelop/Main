<?php


namespace Grimm\Search\Filters;


use Grimm\Search\Compiler\FilterCompiler;
use InvalidArgumentException;

/**
 * Class LetterFilter
 *
 * This filter is for querying against properties that are not in the information subsection,
 * but belong to the core data of a letter object
 * @package Grimm\Search\Filters
 */
class LetterFilter extends BaseFilter {

    /**
     * @var
     */
    private $field;
    /**
     * @var
     */
    private $operator;
    /**
     * @var
     */
    private $value;

    private $operators = [
        '>=',
        '>',
        '<=',
        '<',
        '='
    ];

    public function __construct(LetterField $field, $operator, FilterValue $value)
    {

        $this->field = $field;

        if (!in_array($operator, $this->operators)) {
            throw new InvalidArgumentException('Invalid matcher provided');
        }

        $this->operator = $operator;
        $this->value = $value;
    }

    public function compile(FilterCompiler $query)
    {
        return $query->where($this->field->get(), $this->operator, $this->value->get());
    }
}