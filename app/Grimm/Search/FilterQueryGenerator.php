<?php

namespace Grimm\Search;


use Carbon\Carbon;
use Grimm\Models\Letter;
use Grimm\Search\Compiler\FilterCompiler;
use Grimm\Search\Filters\BaseFilter;
use Grimm\Search\Filters\FilterValue;
use Grimm\Search\Filters\LetterField;
use Grimm\Search\Filters\LetterFilter;
use Grimm\Search\Filters\OperatorFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterQueryGenerator
 * @package Grimm\Search
 */
class FilterQueryGenerator {

    /**
     * @var DateToCodeConverter
     */
    private $dateToCodeConverter;

    /**
     * @var FilterCompiler
     */
    private $filterCompiler;

    function __construct(DateToCodeConverter $dateToCodeConverter, FilterCompiler $filterCompiler)
    {
        $this->dateToCodeConverter = $dateToCodeConverter;
        $this->filterCompiler = $filterCompiler;
    }


    /**
     * Builds the search query containing all requested and filtered letters
     * @param BaseFilter $filters
     * @param $updatedAfter
     * @param null $dateRange
     * @return Builder|static
     */
    public function buildSearchQuery(BaseFilter $filters, $updatedAfter, $dateRange = null)
    {

        if ($updatedAfter !== null) {
            $dateTime = null;

            try {
                $dateTime = Carbon::createFromFormat('Y-m-d h:i:s', $updatedAfter);
            } catch (\InvalidArgumentException $e) {
                try {
                    $dateTime = Carbon::createFromFormat('Y-m-d', $updatedAfter);
                } catch (\InvalidArgumentException $e) {
                }
            }

            if($dateTime) {

                $dateFilter = new LetterFilter(new LetterField('updated_at'), '>=', new FilterValue($dateTime));

                $filters = new OperatorFilter($dateFilter, 'AND', $filters);
            }
        }

        if ($dateRange !== null) {
            $firstCode  = $this->dateToCodeConverter->convert(Carbon::parse($dateRange[0]));
            $secondCode = $this->dateToCodeConverter->convert(Carbon::parse($dateRange[1]), .99);

            $rangeFilter = new OperatorFilter(
                new LetterFilter(
                    new LetterField('code'),
                    '>=',
                    new FilterValue($firstCode)
                ),
                'AND',
                new LetterFilter(
                    new LetterField('code'),
                    '<=',
                    new FilterValue($secondCode)
                )
            );

            $filters = new OperatorFilter($rangeFilter, 'AND', $filters);
        }

        $filters->compile($this->filterCompiler);

        return $this->filterCompiler->getCompiled();
    }
}