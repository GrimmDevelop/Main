<?php

use Grimm\Models\Letter;
use Grimm\Search\Filters\Code;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\MatchFilter;

class MatchFilterTest extends TestCase {


    public function testSimpleMatch()
    {
        $q = Letter::query();

        $filter = new MatchFilter(new Code('absender'), 'starts with', new FilterValue('abs'));

        $afterQ = $filter->compile($q);

        $sql = $afterQ->toSql();

        $expected = 'select * from "letters" where "letters"."deleted_at" is null and (select count(*) from "letter_information" where "letter_information"."letter_id" = "letters"."id" and "code" = ? and "data" like ?) >= 1';

        $this->assertEquals($expected, $sql);
    }
}
