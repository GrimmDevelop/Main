<?php

namespace Grimm\Search;


use Carbon\Carbon;
use Grimm\Models\Letter;
use Grimm\Search\Compiler\EloquentFilterCompiler;
use Grimm\Search\Filters\BaseFilter;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\LetterField;
use Grimm\Search\Filters\LetterFilter;
use Grimm\Search\Filters\OperatorFilter;
use Illuminate\Database\Eloquent\Builder;
use Input;
use Sentry;

/**
 * TODO: First refactor the marked methods to the new filter structure and then resolve the FilterCompiler by IOC
 * Class FilterQueryGenerator
 * @package Grimm\Search
 */
class FilterQueryGenerator {

    /**
     * @var DateToCodeConverter
     */
    private $dateToCodeConverter;

    function __construct(DateToCodeConverter $dateToCodeConverter)
    {
        $this->dateToCodeConverter = $dateToCodeConverter;
    }


    /**
     * Builds the search query containing all requested and filtered letters
     * @param $with
     * @param $filters
     * @param $updatedAfter
     * @return Builder|static
     */
    public function buildSearchQuery($with, BaseFilter $filters, $updatedAfter, $dateRange = null)
    {
        $query = Letter::query();

        foreach ($with as $load) {
            if (in_array($load, ['information', 'senders', 'receivers', 'from', 'to'])) {
                $query->with($load);
            }
        }

        if ($updatedAfter !== null) {
            $dateTime = null;

            try {
                $dateTime = Carbon::createFromFormat('Y-m-d h:i:s', $updatedAfter);
            } catch (\InvalidArgumentException $e) {
                try {
                    $dateTime = Carbon::createFromFormat('Y-m-d', $updatedAfter);
                } catch (\InvalidArgumentException $e) {
                }
            }

            if($dateTime) {

                $dateFilter = new LetterFilter(new LetterField('updated_at'), '>=', new FilterValue($dateTime));

                $filters = new OperatorFilter($dateFilter, 'AND', $filters);
            }
        }

        if ($dateRange !== null) {
            $firstCode  = $this->dateToCodeConverter->convert(Carbon::parse($dateRange[0]));
            $secondCode = $this->dateToCodeConverter->convert(Carbon::parse($dateRange[1]), .99);

            $rangeFilter = new OperatorFilter(
                new LetterFilter(
                    new LetterField('code'),
                    '>=',
                    new FilterValue($firstCode)
                ),
                'AND',
                new LetterFilter(
                    new LetterField('code'),
                    '<=',
                    new FilterValue($secondCode)
                )
            );

            $filters = new OperatorFilter($rangeFilter, 'AND', $filters);
        }

        $filterCompiler = new EloquentFilterCompiler($query);
        $filters->compile($filterCompiler);

        $query = $filterCompiler->getCompiled();

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
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function withFromErrors(Builder $query)
    {
        return $query->orWhere(function ($query) {
            $query->where('from_id', null);
            $query->whereHas('information', function($q) {
                $q->where('code', 'absendeort')->orWhere('code', 'absort_ers');
            }, '>', 0);
            //$query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and (letter_information.code = "absendeort" or letter_information.code = "absort_ers") and data != "") > 0');
        });
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function withToErrors(Builder $query)
    {
        return $query->orWhere(function ($query) {
            $query->where('to_id', null);
            $query->whereHas('information', function($q) {
                $q->where('code', 'empf_ort');
            }, '>', 0);
            //$query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "empf_ort" and data != "") > 0');
        });
    }

    /**
     * TODO: realize this query with the new filter structure
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function withSendersErrors(Builder $query)
    {
        return $query->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
    }

    /**
     * TODO: realize this query with the new filter structure
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function withReceiversErrors(Builder $query)
    {
        return $query->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
    }
}