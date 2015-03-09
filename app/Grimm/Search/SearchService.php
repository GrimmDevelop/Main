<?php

namespace Grimm\Search;



interface SearchService {
    public function getCodes($localized=false);

    /**
     * This will fetch a letter by an ID
     * If $allIDs is true, all possible id values are included in the search
     * @param $id
     * @param $includedRelations
     * @param bool $allIDs
     * @return \Illuminate\Pagination\Paginator
     */
    public function findById($id, $includedRelations, $allIDs=false);

    /**
     * Fetches a letter by its letter code (date of the letter and order count)
     * @param $code
     * @param $includedRelations
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function findByCode($code, $includedRelations, $perPage = 100);

    /**
     * Searches the database for records matching the given filters
     * @param $includedRelations
     * @param $filters
     * @param int $perPage
     * @param \DateTime $updatedAfter
     * @param array $dateRange
     * @param $withErrors
     * @return \Illuminate\Pagination\Paginator
     */
    public function search($includedRelations, $filters, $perPage = 100, $updatedAfter = null, $dateRange = null, $withErrors = []);

    /**
     * @return \Grimm\Values\DateRange
     */
    public function getDateRange();
}