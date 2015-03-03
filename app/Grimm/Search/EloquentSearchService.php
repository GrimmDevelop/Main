<?php

namespace Grimm\Search;

use Carbon\Carbon;
use Grimm\Models\Letter;
use Grimm\Values\DateRange;


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

        return $this->letter->codes();
        // TODO: Implement getCodes() method.
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
     * @return \Illuminate\Pagination\Paginator
     */
    public function search($includedRelations, $filters, $perPage = 100)
    {
        $includedRelations += ['information'];

        return $this->filterParser->buildSearchQuery(
            $includedRelations,
            $filters
        )->paginate($perPage);
    }

    /**
     * @return \Grimm\Values\DateRange
     */
    public function getDateRange()
    {
        $range = $this->letter->selectRaw('MIN(code) as min, MAX(code) as max')->first();

        $min = Carbon::createFromFormat("Ymd", substr($range->min, 0, -3));
        $max = Carbon::createFromFormat("Ymd", substr($range->max, 0, -3));

        return new DateRange($min, $max);
    }
}