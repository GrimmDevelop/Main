<?php

namespace Grimm\Controller;

use DB;
use Grimm\Auth\Models\User;
use Grimm\Models\Filter;
use Grimm\Models\Letter;
use Grimm\Models\Location;
use URL;
use View;
use Input;
use Sentry;

class SearchController extends \Controller {

    protected $letter;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
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
            'codes' => $this->letter->codes(),
            'filter_key' => $filterKey
        ));
    }

    /**
     * Returns the letters requested by search as jsonised Paginator object
     * @return string
     */
    public function searchResult()
    {
        $result = $this->buildSearchQuery(
            ['information'],
            Input::get('filters', [])
        )->paginate(abs((int)Input::get('items_per_page', 100)));

        $return = new \stdClass();

        $return->total = $result->getTotal();
        $return->per_page = $result->getPerPage();
        $return->current_page = $result->getCurrentPage();
        $return->last_page = $result->getLastPage();
        $return->from = $result->getFrom();
        $return->to = $result->getTo();
        $return->data = $result->getCollection()->toArray();

        return json_encode($return);
    }

    protected $distanceMapData;

    protected $tmpAddedBorderIds;

    /**
     * Builds a json string containing computed letter count, border data and the line coordinates as latitude and longitude objects
     * @return string
     */
    public function computeDistanceMap()
    {
        $query = Letter::select(
            'letters.id as letter_id',
            DB::raw('COUNT(letters.id) as `count`'),
            'f.id as from_id',
            'f.latitude as from_lat',
            'f.longitude as from_long',
            't.id as to_id',
            't.latitude as to_lat',
            't.longitude as to_long'
        )
            ->join('locations as f', 'letters.from_id', '=', 'f.id')
            ->join('locations as t', 'letters.to_id', '=', 't.id')
            ->whereRaw('`f`.`id` != `t`.`id`')
            ->groupBy('from_id', 'to_id');

        foreach (Input::get('filters', []) as $filter) {
            $this->buildWhere($query, $filter);
        }

        $this->tmpAddedBorderIds = [];

        $this->distanceMapData = new \stdClass();
        $this->distanceMapData->computedLetters = 0;
        $this->distanceMapData->borderData = [];
        $this->distanceMapData->polylines = [];

        foreach ($query->get() as $dataSet) {
            $this->addBorderData($dataSet->from_id, $dataSet->from_lat, $dataSet->from_long);
            $this->addBorderData($dataSet->to_id, $dataSet->to_lat, $dataSet->to_long);

            if (($index = $this->indexOfPolyline($dataSet->from_id, $dataSet->to_id)) != - 1) {
                $this->distanceMapData->computedLetters++;
                $this->distanceMapData->polylines[$index]['count'] ++;
            } else {
                $this->addPolyline(
                    $dataSet->from_id,
                    $dataSet->from_lat,
                    $dataSet->from_long,
                    $dataSet->to_id,
                    $dataSet->to_lat,
                    $dataSet->to_long,
                    $dataSet->count
                );
            }
        }

        return json_encode($this->distanceMapData);
    }

    /**
     * Adds border data to map data
     * @param $id
     * @param $latitude
     * @param $longitude
     */
    protected function addBorderData($id, $latitude, $longitude)
    {
        if(isset($this->tmpBorderData[$id])) {
            return;
        }

        $this->tmpAddedBorderIds[$id] = true;

        $position = new \stdClass();
        $position->lat = $latitude;
        $position->long = $longitude;

        $this->distanceMapData->borderData[] = $position;
    }

    /**
     * returns the index of a poly line, else -1
     * @param $id1
     * @param $id2
     * @return int|string
     */
    protected function indexOfPolyline($id1, $id2)
    {
        if ($id1 > $id2) {
            $t = $id2;
            $id2 = $id1;
            $id1 = $t;
        }

        foreach ($this->distanceMapData->polylines as $index => $line) {
            if ($line['id1'] == $id1 && $line['id2'] == $id2) {
                return $index;
            }
        }

        return - 1;
    }

    /**
     * Adds a line to map data, always with smallest id first
     * @param $fromId
     * @param $fromLat
     * @param $fromLong
     * @param $toId
     * @param $toLat
     * @param $toLong
     * @param $count
     */
    protected function addPolyline($fromId, $fromLat, $fromLong, $toId, $toLat, $toLong, $count)
    {
        if ($fromId > $toId) {
            $id1 = $toId;
            $lat1 = $toLat;
            $long1 = $toLong;
            $id2 = $fromId;
            $lat2 = $fromLat;
            $long2 = $fromLong;
        } else {
            $id1 = $fromId;
            $lat1 = $fromLat;
            $long1 = $fromLong;
            $id2 = $toId;
            $lat2 = $toLat;
            $long2 = $toLong;
        }

        $this->distanceMapData->computedLetters+= $count;
        $this->distanceMapData->polylines[] = [
            'id1' => $id1,
            'id2' => $id2,
            'lat1' => $lat1,
            'long1' => $long1,
            'lat2' => $lat2,
            'long2' => $long2,
            'count' => $count
        ];
    }

    /**
     * Builds the search query containing all requested and filtered letters
     * @param $with
     * @param $filters
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    protected function buildSearchQuery($with, $filters)
    {
        $query = Letter::query();

        foreach ($with as $load) {
            $query->with($load);
        }

        foreach ($filters as $filter) {
            $this->buildWhere($query, $filter);
        }

        return $query;
    }

    /**
     * Build a filter where query and appends it to a given one
     * @param $query
     * @param $filter
     * @return mixed
     */
    protected function buildWhere($query, $filter)
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
            return json_encode($result);
        }

        return "[]";
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
            return $tmp;
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
        return $this->letter->codes();
    }
}
