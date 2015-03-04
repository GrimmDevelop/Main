<?php

namespace Grimm\Search;


use Grimm\Models\Letter;
use Illuminate\Database\Query\Builder;
use Input;
use Sentry;

/**
 * TODO: This class does not only smell, it stinks!
 * Class FilterQueryGenerator
 * @package Grimm\Search
 */
class FilterQueryGenerator {
    /**
     * Builds the search query containing all requested and filtered letters
     * @param $with
     * @param $filters
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function buildSearchQuery($with, $filters)
    {
        $query = Letter::query();

        foreach ($with as $load) {
            if (in_array($load, ['information', 'senders', 'receivers', 'from', 'to'])) {
                $query->with($load);
            }
        }

        foreach ($filters as $filter) {
            $this->buildWhere($query, $filter);
        }

        if (Sentry::check()) {
            $query->where(function ($query) {
                foreach (Input::get('with_errors', []) as $error) {
                    switch ($error) {
                        case "from":
                            $this->withFromErrors($query);
                            break;

                        case "to":
                            $this->withToErrors($query);
                            break;

                        case "senders":
                            $this->withSendersErrors($query);
                            break;

                        case "receivers":
                            $this->withReceiversErrors($query);
                            break;
                    }
                }
            });
        }

        return $query;
    }

    /**
     * Build a filter where query and appends it to a given one
     * @param $query
     * @param $filter
     * @return mixed
     */
    public function buildWhere($query, $filter)
    {
        if ($filter['code'] == '') {
            return $query;
        }

        return $query->whereHas('information', function ($q) use ($filter) {
            $compare = $this->getCompare($filter['compare'], $filter['value']);

            return $q->where('code', $filter['code'])->where('data', $compare['compare'], $compare['value']);
        });
    }

    /**
     * convert's a compare string and a value to a valid mysql form
     * @param $string
     * @param $value
     * @return array
     */
    protected function getCompare($string, $value)
    {
        switch ($string) {
            case 'contains':
                return array(
                    'compare' => 'like',
                    'value' => "%$value%"
                );
            case 'starts with':
                return array(
                    'compare' => 'like',
                    'value' => "$value%"
                );
            case 'ends with':
                return array(
                    'compare' => 'like',
                    'value' => "%$value"
                );

            case "equals":
            default:
                return array(
                    'compare' => '=',
                    'value' => $value
                );
        }
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withFromErrors(Builder $query)
    {
        return $query->orWhere(function ($query) {
            $query->where('from_id', null);
            $query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and (letter_information.code = "absendeort" or letter_information.code = "absort_ers") and data != "") > 0');
        });
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withToErrors(Builder $query)
    {
        return $query->orWhere(function ($query) {
            $query->where('to_id', null);
            $query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "empf_ort" and data != "") > 0');
        });
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withSendersErrors(Builder $query)
    {
        return $query->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withReceiversErrors(Builder $query)
    {
        return $query->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
    }
}