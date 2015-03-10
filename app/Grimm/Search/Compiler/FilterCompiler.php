<?php


namespace Grimm\Search\Compiler;


use Closure;

interface FilterCompiler {
    public function where($column, $operator = null, $value = null, $boolean = 'and');

    public function orWhere($column, $operator = null, $value = null);

    public function whereHas($relation, Closure $callback, $operator = '>=', $count = 1);

    public function getCompiled();
}