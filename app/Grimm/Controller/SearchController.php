<?php

namespace Grimm\Controller;

use Carbon\Carbon;
use Grimm\Auth\Models\User;
use Grimm\Models\Letter;
use Grimm\Search\FilterService;
use Grimm\Search\SearchService;
use Response;
use URL;
use View;
use Input;
use Sentry;


class SearchController extends \Controller {

    /**
     * @var SearchService
     */
    private $searchService;
    /**
     * @var FilterService
     */
    private $filterService;

    public function __construct(SearchService $searchService, FilterService $filterService)
    {
        $this->searchService = $searchService;
        $this->filterService = $filterService;
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
        $perPage = abs((int)Input::get('items_per_page', 100));

        $result = $this->searchService->search(Input::get('with', ['information']),Input::get('filters', []), $perPage);

        return $this->createSearchOutput($result);
    }

    public function findById($id)
    {

        $itemsPerPage = abs((int)Input::get('items_per_page', 100));

        $result = $this->searchService->findById($id, Input::get('with', ['information']), true, $itemsPerPage);

        return $this->createSearchOutput($result);
    }

    public function findByCode($code)
    {
        $itemsPerPage = abs((int)Input::get('items_per_page', 100));

        $result = $this->searchService->findByCode($code, Input::get('with', ['information']), $itemsPerPage);
        return $this->createSearchOutput($result);
    }

    /**
     * Returns a json array containing all filters from current user
     * @return string
     */
    public function loadFilters()
    {
        User::find(0); // Eloquent - Sentry bug fix hack...
        if ($user = Sentry::getUser()) {
            $result = $this->filterService->loadFiltersForUser($user);

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
        $filter = $this->filterService->getFilterByKey($key);

        if ($filter !== null) {
            return Response::json($filter);
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

            $token = $this->filterService->publishFilter($user, $filter);

            if ($token !== null) {
                return URL::to('search/' . $token);
            }
        }

        return null;
    }

    /**
     * Creates a new filter and returns the new filter list
     * @return string
     */
    public function newFilter()
    {
        if ($user = Sentry::getUser()) {
            $filter = Input::get('filter', []);

            $this->filterService->newFilter($user, $filter);

            // TODO: maybe include error message
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

            $this->filterService->saveFilter($user, $filter);

            // TODO: maybe include error message
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
            $this->filterService->deleteFilter($user, $id);

            return $this->loadFilters();
        }
    }

    public function codes()
    {
        return Response::json($this->searchService->getCodes());
    }

    public function dateRange()
    {
        $range = $this->searchService->getDateRange();

        return Response::json(['d' => $range->toArray()]);
    }

    /**
     * TODO: Extract this in some kind of OutputTransformer
     * @param $result \Illuminate\Pagination\Paginator
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
