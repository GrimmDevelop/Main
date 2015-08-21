<?php


namespace Grimm\Search\Filters;


use Grimm\Search\Compiler\FilterCompiler;

class EmptyFilter extends BaseFilter {

    public function compile(FilterCompiler $query)
    {
        return $query;
    }
}