<?php


namespace Grimm\Search;


use Carbon\Carbon;

class DateToCodeConverter {

    public function convert(Carbon $date, $decimalplaces=0.00)
    {
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;

        // Jahr * 10000 + Monat * 100 + Tag + 0.99

        return $year * 10000 + $month * 100 + $day + $decimalplaces;
    }
}