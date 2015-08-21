<?php


namespace Grimm\Search\Filters;


use Grimm\Search\Compiler\FilterCompiler;

abstract class BaseFilter {

    public abstract function compile(FilterCompiler $query);
}