<?php


namespace Grimm\Search\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter {

    public abstract function compile(Builder $query);
}