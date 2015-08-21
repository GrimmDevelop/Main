<?php

namespace Grimm\Search;


use Grimm\Auth\Models\User;

/**
 * TODO: Add some kind of FilterCollection for filter arrays and wrap filters in object
 * Interface FilterService
 * @package Grimm\Search
 */
interface FilterService {
    /**
     * Fetches all saved filters for a given user
     * @param User $user
     * @return mixed
     */
    public function loadFiltersForUser(User $user);

    /**
     * Fetches a filter identified by its key
     * @param string $key
     * @return mixed
     */
    public function getFilterByKey($key);

    /**
     * publishes an unpublished filter and returns the URL token
     * @param User $user
     * @param array $filter
     * @return mixed the filter token
     */
    public function publishFilter(User $user, array $filter);

    /**
     * Creates a new filter and returns the new filter list
     * @param $name
     * @param User $user
     * @param array $filter
     * @return mixed
     */
    public function newFilter($name, User $user, array $filter);

    /**
     * Updates a filter an returns the new filter list
     * @param User $user
     * @param array $filter
     * @return mixed
     */
    public function saveFilter(User $user, array $filter);

    /**
     * Deletes the given filter from database and returns all remaining
     * @param User $user
     * @param array $filter
     * @return mixed
     */
    public function deleteFilter(User $user, $filterId);
}