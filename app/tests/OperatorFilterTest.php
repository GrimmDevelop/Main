<?php


use Grimm\Models\Letter;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;
use Grimm\Search\Filters\OperatorFilter;

class OperatorFilterTest extends TestCase {


    public function testSimpleOperator()
    {
        $q = Letter::query();

        $filterA = new MatchFilter(new Code('absender'), 'starts with', new FilterValue('Grimm'));
        $filterB = new MatchFilter(new Code('empfaenger'), 'starts with', new FilterValue('Grimm'));

        $filter = new OperatorFilter($filterA, 'OR', $filterB);

        $q = $filter->compile($q);

        $expected = 'select * from "letters" where "letters"."deleted_at" is null and (((select count(*) from "letter_information" where "letter_information"."letter_id" = "letters"."id" and "code" = ? and "data" like ?) >= 1) or ((select count(*) from "letter_information" where "letter_information"."letter_id" = "letters"."id" and "code" = ? and "data" like ?) >= 1))';

        $this->assertEquals($expected, $q->toSql());
    }
}
