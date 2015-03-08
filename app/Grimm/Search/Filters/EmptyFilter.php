<?php


namespace Grimm\Search\Filters;


use Illuminate\Database\Eloquent\Builder;

class EmptyFilter extends BaseFilter {

    public function compile(Builder $query)
    {
        return $query;
    }
}