<?php

namespace Grimm\Transformer;

use Grimm\Contract\RecordTransformer;

class LocationRecord implements RecordTransformer {

    /**
     * Transform record data to target data
     * @param $data
     * @return mixed
     */
    public function transform($data)
    {
        $record = array();

        $record['id'] = $data[0];
        $record['name'] = $data[1];
        $record['asciiname'] = $data[2];
        $record['alternatenames'] = ',' . $data[3] . ',';
        $record['latitude'] = floatval($data[4]);
        $record['longitude'] = floatval($data[5]);
        $record['feature_class'] = $data[6];
        $record['feature_code'] = $data[7];
        $record['country_code'] = $data[8];
        $record['cc2'] = $data[9];
        $record['admin1_code'] = $data[10];
        $record['admin2_code'] = $data[11];
        $record['admin3_code'] = $data[12];
        $record['admin4_code'] = $data[13];
        $record['population'] = (int)$data[14];
        $record['elevation'] = (int)$data[15];
        $record['dem'] = (int)$data[16];
        $record['timezone'] = $data[17];
        $record['modification_date'] = $data[18];

        return $record;
    }
}