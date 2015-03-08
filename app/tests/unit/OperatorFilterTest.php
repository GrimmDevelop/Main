<?php


use Grimm\Models\Letter;
use Grimm\Search\Compiler\TestFilterCompiler;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;
use Grimm\Search\Filters\OperatorFilter;

class OperatorFilterTest extends PHPUnit_Framework_TestCase {


    public function testSimpleOperator()
    {

        $filterA = new MatchFilter(new Code('absender'), 'starts with', new FilterValue('Grimm'));
        $filterB = new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm'));

        $filter = new OperatorFilter($filterA, 'OR', $filterB);

        $compiler = new TestFilterCompiler();
        $filter->compile($compiler);

        $q = $compiler->getCompiled();

        $expected = ' and( and( on information[ code = "absender" data like "Grimm%"] >= 1)  or( on information[ code = "empfaenger" data like "Grimm%"] >= 1) ) ';

        $this->assertEquals($expected, $q);
    }
}
