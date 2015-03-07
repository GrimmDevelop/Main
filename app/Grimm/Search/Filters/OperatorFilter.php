<?php


namespace Grimm\Search\Filters;


use Illuminate\Database\Eloquent\Builder;

class OperatorFilter extends BaseFilter
{

    /**
     * @var BaseFilter
     */
    protected $filterA;
    /**
     * @var BaseFilter
     */
    protected $filterB;

    protected $operator;

    protected $operators = [
        'OR',
        'AND'
    ];

    public function __construct(BaseFilter $filterA, $operator, BaseFilter $filterB)
    {
        $this->filterA = $filterA;
        $this->operator = $operator;
        $this->filterB = $filterB;
    }

    public function compile(Builder $query)
    {
        $op = $this->operator;
        return $query->where(function ($q) use ($op) {
            if ($op === 'OR') {
                return $q->where(function ($qA) {
                    return $this->filterA->compile($qA);
                })->orWhere(function ($qB) {
                    return $this->filterB->compile($qB);
                });
            } else {
                return $q->where(function ($qA) {
                    return $this->filterA->compile($qA);
                })->where(function ($qB) {
                    return $this->filterB->compile($qB);
                });
            }
        });
        // TODO: Implement compile() method.
    }
}