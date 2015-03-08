<?php


namespace Grimm\Search\Compiler;


use Closure;
use Illuminate\Database\Eloquent\Builder;

class EloquentFilterCompiler implements FilterCompiler {

    /**
     * @var Builder
     */
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column instanceof Closure) {
            $this->builder->where(function($q) use ($column) {
                $compiler = new EloquentFilterCompiler($q);
                $column($compiler);
                return $compiler->getCompiled();
            }, $operator, $value, $boolean);

            return $this;
        }
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function whereHas($relation, Closure $callback, $operator = '>=', $count = 1)
    {
        $this->builder->whereHas($relation, function($q) use ($callback) {
            $compiler = new EloquentFilterCompiler($q);
            $callback($compiler);
            return $compiler->getCompiled();
        }, $operator, $count);
    }

    public function getCompiled()
    {
        return $this->builder;
    }
}