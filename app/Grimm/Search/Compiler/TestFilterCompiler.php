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
            /*$this->compiled[] = [
                'operator'  => $boolean,
                'fields'    => $compiler->getCompiled()
            ];*/
            $this->compiled .= ' ' . $boolean . '(' . $compiler->getCompiled() . ') ';
        } else {
            $val = ($value === null) ? $operator : $value;

            $op = ($value !== null) ? $operator : '=';

            /*$this->compiled[] = [
                'column'    => $column,
                'operator'  => $op,
                'value'     => $val
            ];*/

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
        /*$result = [
            'relation'  => $relation,
            'operator'  => $operator,
            'count'     => $count
        ];*/

        $compiler = new TestFilterCompiler();
        $callback($compiler);
        //$result['fields'] = $compiler->getCompiled();

        $this->compiled .= ' on ' . $relation . '[' . $compiler->getCompiled() . '] ' . $operator . ' ' . $count;
    }

    public function getCompiled()
    {
        return $this->compiled;
    }
}