<?php

namespace Grimm\Search;

use Carbon\Carbon;
use Grimm\Models\Letter;
use Grimm\Values\DateRange;
use Illuminate\Database\Eloquent\Builder;


class EloquentSearchService implements SearchService {


    /**
     * @var Letter
     */
    private $letter;

    /**
     * @var FilterQueryGenerator
     */
    private $filterParser;

    public function __construct(Letter $letter, FilterQueryGenerator $filterParser)
    {
        $this->letter = $letter;
        $this->filterParser = $filterParser;
    }

    public function getCodes($localized = false)
    {
        // TODO: Really localize the codes
        if ($localized) {
            $theCodes = $this->letter->codes();
            $result = [];
            foreach ($theCodes as $code) {
                $result[$code] = $this->translateCode($code);
            }

            return $result;
        }
        return $this->letter->codes();
    }

    /**
     * This will fetch a letter by an ID
     * If $allIDs is true, all possible id values are included in the search
     * @param $id
     * @param $includedRelations
     * @param bool $allIDs
     * @return \Illuminate\Pagination\Paginator
     */
    public function findById($id, $includedRelations, $allIDs = false, $perPage = 100)
    {
        $includedRelations += ['information'];

        $query = $this->createFindBuilder($includedRelations);

        if ($allIDs) {
            return $query->whereHas('information', function ($q) use ($id) {

                return $q->whereRaw("(code = 'nr_1992' or code = 'nr_1997')")->where('data', $id);
            })->orWhere('id', $id)->paginate($perPage);
        } else {
            return $query->where('id', $id)->paginate($perPage);
        }
    }

    /**
     * Fetches a letter by its letter code (date of the letter and order count)
     * @param $code
     * @param $includedRelations
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function findByCode($code, $includedRelations, $perPage = 100)
    {
        $includedRelations += ['information'];
        $query = $this->createFindBuilder($includedRelations);

        return $query->where('code', $code)->paginate($perPage);
    }

    protected function createFindBuilder($with)
    {
        $query = Letter::query();

        foreach ($with as $load) {
            if (in_array($load, ['information', 'senders', 'receivers', 'from', 'to'])) {
                $query->with($load);
            }
        }

        return $query;
    }

    /**
     * Searches the database for records matching the given filters
     * @param $includedRelations
     * @param $filters
     * @param int $perPage
     * @param null $updatedAfter
     * @param null $dateRange
     * @param $withErrors
     * @return \Illuminate\Pagination\Paginator
     */
    public function search($includedRelations, $filters, $perPage = 100, $updatedAfter = null, $dateRange = null, $withErrors = [])
    {
        $includedRelations += ['information'];

        $query = $this->filterParser->buildSearchQuery(
            $filters,
            $updatedAfter,
            $dateRange
        );

        foreach ($includedRelations as $load) {
            if (in_array($load, ['information', 'senders', 'receivers', 'from', 'to'])) {
                $query->with($load);
            }
        }

        if (count($withErrors) > 0) {
            $query->where(function ($query) use ($withErrors) {
                foreach ($withErrors as $error) {
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

        return $query->paginate($perPage);
    }

    /**
     * @return \Grimm\Values\DateRange
     */
    public function getDateRange()
    {
        $range = $this->letter->selectRaw('MIN(code) as min, MAX(code) as max')->where('valid', 1)->first();

        $min = Carbon::createFromFormat("Ymd", substr($range->min, 0, -3));
        $max = Carbon::createFromFormat("Ymd", substr($range->max, 0, -3));

        return new DateRange($min, $max);
    }

    /**
     * @param $code
     * @return string
     */
    protected function translateCode($code)
    {
        return ucfirst($code);
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