<?php


namespace Grimm\Search\Compiler;


use Closure;

class TestFilterCompiler implements FilterCompiler {

    protected $compiled = '';

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column instanceof Closure) {
            $compiler = new TestFilterCompiler();
            $column($compiler);

            if ($this->compiled !== '') {
                $this->compiled .= ' ' . $boolean . ' ';
            }

            $this->compiled .= '(' . $compiler->getCompiled() . ')';
        } else {
            $val = ($value === null) ? $operator : $value;

            $op = ($value !== null) ? $operator : '=';

            $this->compiled .= ' ' . $column . ' ' . $op . ' "' . $val . '"';
        }

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function whereHas($relation, Closure $callback, $operator = '>=', $count = 1)
    {
        $compiler = new TestFilterCompiler();
        $callback($compiler);

        $this->compiled .= 'on ' . $relation . '[' . $compiler->getCompiled() . '] ' . $operator . ' ' . $count;
    }

    public function getCompiled()
    {
        return $this->compiled;
    }
}