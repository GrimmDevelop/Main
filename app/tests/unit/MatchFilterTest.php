<?php

use Grimm\Models\Letter;
use Grimm\Search\Compiler\TestFilterCompiler;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;

class MatchFilterTest extends PHPUnit_Framework_TestCase {


    public function testSimpleMatch()
    {

        $filter = new MatchFilter(new Code('absender'), 'starts with', new FilterValue('abs'));

        $compiler = new TestFilterCompiler();
        $filter->compile($compiler);

        $afterQ = $compiler->getCompiled();

        $expected = 'on information[ code = "absender" data like "abs%"] >= 1';

        $this->assertEquals($expected, $afterQ);
    }
}
