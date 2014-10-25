<?php

namespace Grimm\Controller;

use Grimm\Auth\Models\User;
use Grimm\Models\Letter;
use View;
use Input;
use Response;
use Sentry;

class SearchController extends \Controller {

    protected $letter;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    public function searchForm()
    {
        return View::make('pages.search', array(
            'codes' => $this->letter->codes()
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

    public function computeDistanceMap() {

        $query = $this->buildSearchQuery(
            ['from', 'to'],
            Input::get('filters', [])
        )->whereNotNull('from_id')->whereNotNull('to_id')/*->take(1000)*/;

        $distanceMapData = new \stdClass();
        $distanceMapData->computedLetters = $query->count();
        $distanceMapData->borderData = [];
        $distanceMapData->polylines = [];

        foreach($query->get() as $letter) {
            $this->addBorderData($distanceMapData, $letter->from);
            $this->addBorderData($distanceMapData, $letter->to);

            if(($index = $this->indexOfPolyline($distanceMapData->polylines, $letter->from_id, $letter->to_id)) != -1) {
                $distanceMapData->polylines[$index]['count']++;
            } else {
                if($letter->from->id > $letter->to->id) {
                    $id1 = $letter->to->id;
                    $id2 = $letter->from->id;
                } else {
                    $id1 = $letter->from->id;
                    $id2 = $letter->to->id;
                }

                $distanceMapData->polylines[] = [
                    'id1' => $id1,
                    'id2' => $id2,
                    'from' => [
                        'latitude' => $letter->from->latitude,
                        'longitude'=> $letter->from->longitude
                    ],
                    'to' => [
                        'latitude' => $letter->to->latitude,
                        'longitude'=> $letter->to->longitude
                    ],
                    'count' => 1
                ];
            }
        }

        return  json_encode($distanceMapData);
    }

    protected function addBorderData($mapData, $location) {
        $position = new \stdClass();
        $position->latitude = $location->latitude;
        $position->longitude = $location->longitude;

        if(!in_array($position, $mapData->borderData)) {
            $mapData->borderData[] = $position;
        }
    }

    protected function indexOfPolyline($lines, $id1, $id2) {
        if($id1 > $id2) {
            $t = $id2;
            $id2 = $id1;
            $id1 = $t;
        }

        foreach($lines as $index => $line) {
            if($line['id1'] == $id1 && $line['id2'] == $id2) {
                return $index;
            }
        }

        return -1;
    }

    protected function buildSearchQuery($with, $filters) {
        $query = Letter::query();

        foreach($with as $load) {
            $query->with($load);
        }

        foreach($filters as $filter) {
            $this->buildWhere($query, $filter);
        }

        return $query;
    }

    protected function buildWhere($query, $filter) {
        if($filter['code'] == '') {
            return $query;
        }

        return $query->whereHas('information', function($q) use($filter) {
            $compare = $this->getCompare($filter['compare'], $filter['value']);

            return $q->where('code', $filter['code'])->where('data', $compare['compare'], $compare['value']);
        });
    }

    protected function getCompare($string, $value) {
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

    public function loadFilters() {
        User::find(0);
        if($user = Sentry::getUser()) {
            $search_filters = $user->search_filter;

            if($search_filters == "") {
                $search_filters = "[]";
            }

            return $search_filters;
        }
    }

    public function saveFilters() {
        if($user = Sentry::getUser()) {
            $user->search_filter = json_encode(Input::get('filters', []));
            $user->save();
        }
    }

    public function codes() {
        return $this->letter->codes();
    }
}
