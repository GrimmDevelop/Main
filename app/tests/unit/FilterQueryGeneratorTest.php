<?php


use Grimm\Search\Compiler\TestFilterCompiler;
use Grimm\Search\DateToCodeConverter;
use Grimm\Search\FilterQueryGenerator;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;

class FilterQueryGeneratorTest extends PHPUnit_Framework_TestCase {

    public function testFilterUpdatedAfter()
    {
        $filterquerygen = new FilterQueryGenerator(new DateToCodeConverter(), new TestFilterCompiler());

        $compiled = $filterquerygen->buildSearchQuery(new \Grimm\Search\Filters\MatchFilter(new Code('absender'), 'equals', new FilterValue('Grimm')),
            '2015-03-08');

        $expected = '(( updated_at >= "2015-03-08 00:00:00") and (on information[ code = "absender" data = "Grimm"] >= 1))';

        $this->assertEquals($expected, $compiled);
    }
}
