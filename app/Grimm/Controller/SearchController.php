<?php

namespace Grimm\Controller;

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

    public function searchForm($filterKey = null)
    {
        $filterKey = preg_replace('/[^\w]/', '', $filterKey);
        return View::make('pages.search', array(
            'codes' => $this->letter->codes(),
            'filter_key' => $filterKey
        ));
    }

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

    public function computeDistanceMap()
    {

        $query = $this->buildSearchQuery(
            ['from', 'to'],
            Input::get('filters', [])
        )->whereNotNull('from_id')->whereNotNull('to_id')/*->take(1000)*/
        ;

        $distanceMapData = new \stdClass();
        $distanceMapData->computedLetters = $query->count();
        $distanceMapData->borderData = [];
        $distanceMapData->polylines = [];

        foreach ($query->get() as $letter) {
            $this->addBorderData($distanceMapData, $letter->from);
            $this->addBorderData($distanceMapData, $letter->to);

            if (($index = $this->indexOfPolyline($distanceMapData, $letter->from_id, $letter->to_id)) != - 1) {
                $distanceMapData->polylines[$index]['count'] ++;
            } else {
                $this->addPolyline($distanceMapData, $letter->from, $letter->to);
            }
        }

        return json_encode($distanceMapData);
    }

    protected function addBorderData($mapData, $location)
    {
        $position = new \stdClass();
        $position->latitude = $location->latitude;
        $position->longitude = $location->longitude;

        if (!in_array($position, $mapData->borderData)) {
            $mapData->borderData[] = $position;
        }
    }

    protected function indexOfPolyline($distanceMapData, $id1, $id2)
    {
        if ($id1 > $id2) {
            $t = $id2;
            $id2 = $id1;
            $id1 = $t;
        }

        foreach ($distanceMapData->polylines as $index => $line) {
            if ($line['id1'] == $id1 && $line['id2'] == $id2) {
                return $index;
            }
        }

        return - 1;
    }

    protected function addPolyline($distanceMapData, Location $from, Location $to)
    {
        if ($from->id > $to->id) {
            $id1 = $to->id;
            $id2 = $from->id;
        } else {
            $id1 = $from->id;
            $id2 = $to->id;
        }

        $distanceMapData->polylines[] = [
            'id1' => $id1,
            'id2' => $id2,
            'from' => [
                'latitude' => $from->latitude,
                'longitude' => $from->longitude
            ],
            'to' => [
                'latitude' => $to->latitude,
                'longitude' => $to->longitude
            ],
            'count' => 1
        ];
    }

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

    public function loadFilters()
    {
        User::find(0);
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

    protected function generateKey($id)
    {
        return time() . '_' . md5($id . rand(0, 100));
    }

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

    public function deleteFilter($id)
    {
        if ($user = Sentry::getUser()) {
            if ($filter = $user->filters()->where('id', $id)->first()) {
                $filter->delete();
            }

            return $this->loadFilters();
        }
    }

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
