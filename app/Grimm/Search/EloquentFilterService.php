<?php

namespace Grimm\Search;


use Grimm\Auth\Models\User;
use Grimm\Models\Filter;

class EloquentFilterService implements FilterService {

    /**
     * Fetches all saved filters for a given user
     * @param User $user
     * @return mixed
     */
    public function loadFiltersForUser(User $user)
    {
        $filters = $user->filters()->get();

        $result = [];
        foreach ($filters as $filter) {
            $tmp = [];
            $tmp['id'] = $filter->id;
            $tmp['name'] = $filter->name;
            $tmp['filter_key'] = $filter->filter_key;
            $tmp['filters'] = json_decode($filter->fields);
            $result[] = $tmp;
        }
        return $result;
    }

    /**
     * Fetches a filter identified by its key
     * @param string $key
     * @return mixed
     */
    public function getFilterByKey($key)
    {
        if ($filter = Filter::where('filter_key', $key)->first()) {
            $tmp = [];
            $tmp['id'] = null;
            $tmp['name'] = $filter->name;
            $tmp['filter_key'] = $filter->filter_key;
            $tmp['filters'] = json_decode($filter->fields);
            return $tmp;
        }
        return null;
    }

    /**
     * publishes an unpublished filter and returns the URL token
     * @param User $user
     * @param array $filter
     * @return mixed the filter token
     */
    public function publishFilter(User $user, array $filter)
    {
        //$filter = Input::get('filter', []);

        if (!($filterObj = $this->findFilter($user, $filter))) {
            return null;
        }

        if ($filterObj->filter_key != null) {
            return $filterObj->filter_key;
        }

        $filterObj->filter_key = $this->generateKey($filterObj->id);
        $filterObj->save();

        return $filterObj->filter_key;
    }

    /**
     * Creates a new filter and returns the new filter list
     * @param $name
     * @param User $user
     * @param array $filter
     * @return mixed
     */
    public function newFilter($name, User $user, array $filter)
    {
        if (empty($filter['fields'])) {
            return false;
        }

        $user->filters()->save(new Filter([
            'name'   => $name,
            'fields' => json_encode($filter)
        ]));

        return true;
    }

    /**
     * Updates a filter
     * @param User $user
     * @param array $filter
     * @return bool true on success, false if the filter was not found
     */
    public function saveFilter(User $user, array $filter)
    {

        if (!($filterObj = $this->findFilter($user, $filter))) {
            return false;
        }

        $filterObj->fields = json_encode(($filter['filters']));
        $filterObj->save();

        return true;
    }

    /**
     * Deletes the given filter from database and returns all remaining
     * @param User $user
     * @param $filterId
     * @return mixed
     */
    public function deleteFilter(User $user, $filterId)
    {
        $filter = [
            'id' => $filterId
        ];
        if ($filter = $this->findFilter($user, $filter)) {
            $filter->delete();
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

    /**
     * generates a unique public filter key
     * @param $id
     * @return string
     */
    protected function generateKey($id)
    {
        return time() . '_' . md5($id . rand(0, 100));
    }
}