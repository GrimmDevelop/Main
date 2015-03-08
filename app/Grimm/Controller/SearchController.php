<?php

namespace Grimm\Controller;

use Grimm\Auth\Models\User;
use Grimm\OutputTransformer\ArrayOutput;
use Grimm\OutputTransformer\JsonPaginationOutput;
use Grimm\Search\FilterRequestParser;
use Grimm\Search\Filters\EmptyFilter;
use Grimm\Search\FilterService;
use Grimm\Search\InvalidFilterRequestException;
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
    /**
     * @var JsonPaginationOutput
     */
    private $paginationOutput;
    /**
     * @var ArrayOutput
     */
    private $arrayOutput;
    /**
     * @var FilterRequestParser
     */
    private $filterRequestParser;

    public function __construct(SearchService $searchService,
                                FilterService $filterService,
                                FilterRequestParser $filterRequestParser,
                                JsonPaginationOutput $paginationOutput,
                                ArrayOutput $arrayOutput)
    {
        $this->searchService = $searchService;
        $this->filterService = $filterService;
        $this->paginationOutput = $paginationOutput;
        $this->arrayOutput = $arrayOutput;
        $this->filterRequestParser = $filterRequestParser;
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

        try {
            $filters = $this->filterRequestParser->parse(Input::get('filters', []));
        } catch (InvalidFilterRequestException $e) {
            // TODO: Log errors and hand back to client!
            $filters = new EmptyFilter();
        }

        $dateRange = Input::get('dateRange', null);

        $result = $this->searchService->search(Input::get('with', ['information']),$filters, $perPage, null, $dateRange);

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

            return $this->createJsonResponse($result);
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
            return $this->createJsonResponse($filter);
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
        $localized = Input::has('localized');
        return $this->createJsonResponse($this->searchService->getCodes($localized));
    }

    public function dateRange()
    {
        $range = $this->searchService->getDateRange();


        return $this->createJsonResponse($range);
    }

    /**
     * @param $result \Illuminate\Pagination\Paginator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createSearchOutput($result)
    {
        return Response::json($this->paginationOutput->transform($result));
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createJsonResponse($data)
    {
        return Response::json($this->arrayOutput->transform($data));
    }
}
