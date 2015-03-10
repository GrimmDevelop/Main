<?php


use Grimm\Search\Compiler\TestFilterCompiler;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\LetterField;
use Grimm\Search\Filters\LetterFilter;

class LetterFilterTest extends PHPUnit_Framework_TestCase {


    public function testLetterFilter()
    {
        $filter = new LetterFilter(new LetterField('updated_at'), '>=', new FilterValue('2015-03-07'));

        $compiler = new TestFilterCompiler();
        $filter->compile($compiler);

        $afterQ = $compiler->getCompiled();

        $expected = ' updated_at >= "2015-03-07"';

        $this->assertEquals($expected, $afterQ);
    }
}
