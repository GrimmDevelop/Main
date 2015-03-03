<?php
/**
 * Created by PhpStorm.
 * User: davidbohn
 * Date: 03.03.15
 * Time: 01:26
 */

namespace Grimm\Controller;


use DB;
use Grimm\Models\Letter;
use Grimm\Search\FilterQueryGenerator;
use Input;
use Response;

class DistanceMapController extends \Controller {
    protected $distanceMapData;

    protected $tmpAddedBorderIds;

    /**
     * @var
     */
    private $filterParser;

    function __construct(FilterQueryGenerator $filterParser)
    {
        $this->filterParser = $filterParser;
    }


    /**
     * TODO: nothing to do in the SearchController
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
            $this->filterParser->buildWhere($query, $filter);
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

        return Response::json($this->distanceMapData);
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
}