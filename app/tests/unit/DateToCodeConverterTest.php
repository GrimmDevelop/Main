<?php


use Carbon\Carbon;
use Grimm\Search\DateToCodeConverter;

class DateToCodeConverterTest extends PHPUnit_Framework_TestCase {

    public function testConvertsDate()
    {
        $dt = Carbon::parse('01.01.1787');

        $converter = new DateToCodeConverter();

        $expected = floatval(17870101);

        $this->assertSame($expected, $converter->convert($dt));
    }

    public function testCanAddUpperBound()
    {
        $dt = Carbon::parse('01.01.1787');

        $converter = new DateToCodeConverter();

        $expected = floatval(17870101.99);

        $this->assertSame($expected, $converter->convert($dt, .99));
    }


}
