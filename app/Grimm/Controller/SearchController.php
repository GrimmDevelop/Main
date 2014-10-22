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
        $s = Letter::with('information', 'senders', 'receivers');

        foreach(Input::get('filters', []) as $filter) {
            $this->buildWhere($s, $filter);
        }

        $result = $s->paginate(100);

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
