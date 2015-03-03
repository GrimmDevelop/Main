<?php

namespace Grimm\Controller;

use Carbon\Carbon;
use DB;
use Grimm\Auth\Models\User;
use Grimm\Models\Filter;
use Grimm\Models\Letter;
use Grimm\Models\Location;
use Grimm\Search\FilterParser;
use Response;
use URL;
use View;
use Input;
use Sentry;


class SearchController extends \Controller {

    protected $letter;
    /**
     * @var FilterParser
     */
    private $filterParser;

    public function __construct(Letter $letter, FilterParser $filterParser)
    {
        $this->letter = $letter;
        $this->filterParser = $filterParser;
    }

    /**
     * Displays the search form view
     * @param null $filterKey
     * @return \Illuminate\View\View
     */
    public function searchForm($filterKey = null)
    {
        $filterKey = preg_replace('/[^\w]/', '', $filterKey);
        return View::make('pages.search', array(
            'filter_key' => $filterKey
        ));
    }

    /**
     * Returns the letters requested by search as jsonised Paginator object
     * @return string
     */
    public function searchResult()
    {
        $result = $this->filterParser->buildSearchQuery(
            Input::get('with', ['information']),
            Input::get('filters', [])
        )->paginate(abs((int)Input::get('items_per_page', 100)));

        return $this->createSearchOutput($result);
    }

    public function findById($id)
    {
        $query = $this->createFindBuilder(Input::get('with', ['information']));

        $result = $query->whereHas('information', function ($q) use ($id) {

            return $q->whereRaw("(code = 'nr_1992' or code = 'nr_1997')")->where('data', $id);
        })->orWhere('id', $id)->paginate(abs((int)Input::get('items_per_page', 100)));

        return $this->createSearchOutput($result);
    }

    public function findByCode($code)
    {
        return $this->findForField('code', $code);
    }

    protected function findForField($fieldName, $fieldValue)
    {

        $query = $this->createFindBuilder(Input::get('with', ['information']));

        $result = $query->where($fieldName, $fieldValue)->paginate(1);

        return $this->createSearchOutput($result);
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
     * Returns a json array containing all filters from current user
     * @return string
     */
    public function loadFilters()
    {
        User::find(0); // Eloquent - Sentry bug fix hack...
        if ($user = Sentry::getUser()) {
            $filters = $user->filters()->get();

            $result = [];
            foreach ($filters as $filter) {
                $tmp = [];
                $tmp['id'] = $filter->id;
                $tmp['filter_key'] = $filter->filter_key;
                $tmp['fields'] = json_decode($filter->fields);
                $result[] = $tmp;
            }
            return Response::json($result);
        }

        return Response::json([]);
    }

    /**
     * returns a public filter by given public key
     * @param $key
     * @return array|null
     */
    public function loadFilter($key)
    {
        if ($filter = Filter::where('filter_key', $key)->first()) {
            $tmp = [];
            $tmp['id'] = null;
            $tmp['filter_key'] = $filter->filter_key;
            $tmp['fields'] = json_decode($filter->fields);
            return Response::json($tmp);
        }
        return null;
    }

    /**
     * publishes an unpublished filter and returns the URL
     * @return null|string
     */
    public function publicFilter()
    {
        if ($user = Sentry::getUser()) {
            $filter = Input::get('filter', []);

            if (!($filterObj = $this->findFilter($user, $filter))) {
                return null;
            }

            if ($filterObj->filter_key != null) {
                return URL::to('search/' . $filterObj->filter_key);
            }

            $filterObj->filter_key = $this->generateKey($filterObj->id);
            $filterObj->save();

            return URL::to('search/' . $filterObj->filter_key);
        }
    }

    /**
     * generates a unique public filter key
     * @param $id
     * @return string
     */
    protected function generateKey($id)
    {
        return time() . '_' . md5($id . rand(0, 100));
    }

    /**
     * Creates a new filter and returns the new filter list
     * @return string
     */
    public function newFilter()
    {
        if ($user = Sentry::getUser()) {
            $filter = Input::get('filter', []);

            if (empty($filter['fields'])) {
                return $this->loadFilters();
            }

            $user->filters()->save(new Filter([
                'fields' => json_encode($filter['fields'])
            ]));

            return $this->loadFilters();
        }
    }

    /**
     * Updates a filter an returns the new filter list
     * @return string
     */
    public function saveFilter()
    {
        if ($user = Sentry::getUser()) {
            $filter = Input::get('filter', []);

            if (!($filterObj = $this->findFilter($user, $filter))) {
                return $this->loadFilters();
            }

            $filterObj->fields = json_encode(($filter['fields']));
            $filterObj->save();

            return $this->loadFilters();
        }
    }

    /**
     * Deletes the given filter from database and returns all remaining
     * @param $id
     * @return string
     */
    public function deleteFilter($id)
    {
        if ($user = Sentry::getUser()) {
            $filter = [
                'id' => $id
            ];
            if ($filter = $this->findFilter($user, $filter)) {
                $filter->delete();
            }

            return $this->loadFilters();
        }
    }

    /**
     *
     * @param User $user
     * @param $filter
     * @return null
     */
    protected function findFilter(User $user, $filter)
    {
        if (empty($filter['id'])) {
            return null;
        }

        return $user->filters()->where('id', $filter['id'])->first();
    }

    public function codes()
    {
        return Response::json($this->letter->codes());
    }

    public function dateRange()
    {
        $range = Letter::selectRaw('MIN(code) as min, MAX(code) as max')->first();

        $min = (string)Carbon::createFromFormat("Ymd", substr($range->min, 0, -3))->format("Y-m-d");
        $max = (string)Carbon::createFromFormat("Ymd", substr($range->max, 0, -3))->format("Y-m-d");

        return Response::json(['d' => ['min' => $min, 'max' => $max]]);
    }

    /**
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSearchOutput($result)
    {
        $return = new \stdClass();

        $return->total = $result->getTotal();
        $return->per_page = $result->getPerPage();
        $return->current_page = $result->getCurrentPage();
        $return->last_page = $result->getLastPage();
        $return->from = $result->getFrom();
        $return->to = $result->getTo();
        $return->data = $result->getCollection()->toArray();

        return Response::json($return);
    }
}
