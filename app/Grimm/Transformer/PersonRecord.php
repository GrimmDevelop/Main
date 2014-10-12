<?php

namespace Grimm\Transformer;

use Grimm\Contract\RecordTransformer;
use XBase\Column;
use XBase\Record;

class PersonRecord implements RecordTransformer {

    /**
     * Transform record data to target data
     * @param $data
     * @return mixed
     */
    public function transform($data)
    {
        $utf8DataArray = array_map(function ($column) use ($data) {
            return $this->utf8Convert($data, $column);
        }, $data->getColumns());

        $transformedRecord = [
            'name_2013' => $this->name($utf8DataArray['name_2013']),
            'informations' => [
                'name' => $utf8DataArray['name'],
                'name_alt' => $utf8DataArray['name_alt'],
                'standard' => $utf8DataArray['standard'],
                'wann_gepr' => $utf8DataArray['wann_gepr'],
                'q_standard' => $utf8DataArray['q_standard'],
                'nichtstand' => $utf8DataArray['nichtstand'],
                'freigabe' => $utf8DataArray['freigabe'],
                'nichtverz' => $utf8DataArray['nichtverz'],
                'druck' => [
                    $utf8DataArray['druck_1'],
                    $utf8DataArray['druck_2'],
                    $utf8DataArray['druck_3'],
                    $utf8DataArray['druck_4'],
                    $utf8DataArray['druck_5'],
                    $utf8DataArray['druck_6'],
                    $utf8DataArray['druck_7'],
                    $utf8DataArray['druck_8'],
                    $utf8DataArray['druck_9'],
                    $utf8DataArray['druck_10'],
                    $utf8DataArray['druck_11'],
                    $utf8DataArray['druck_12'],
                    $utf8DataArray['druck_13'],
                    $utf8DataArray['druck_14']
                ],
                'lebt' => $utf8DataArray['lebt'],
                // 'taetig' => $utf8DataArray['t?tig'], todo: find correct index...
                'literatur' => $utf8DataArray['literatur'],
                'biografie' => $utf8DataArray['biografie'],
                'briefe_von' => $utf8DataArray['briefe_von'],
                'briefe_an' => $utf8DataArray['briefe_an'],
                'asu_j_n' => $utf8DataArray['asu_j_n'],
                'nl' => [
                    $utf8DataArray['nl_1'],
                    $utf8DataArray['nl_2'],
                    $utf8DataArray['nl_3'],
                    $utf8DataArray['nl_4'],
                    $utf8DataArray['nl_5'],
                    $utf8DataArray['nl_6'],
                    $utf8DataArray['nl_7'],
                    $utf8DataArray['nl_8'],
                    $utf8DataArray['nl_9'],
                    $utf8DataArray['nl_10']
                ],
                'leb_orte' => $utf8DataArray['leb_orte'],
                'herkunft' => $utf8DataArray['herkunft'],
            ]
        ];

        $transformedRecord['informations']['druck'] = array_filter($transformedRecord['informations']['druck'], 'strlen');
        $transformedRecord['informations']['nl'] = array_filter($transformedRecord['informations']['nl'], 'strlen');

        $transformedRecord['informations'] = array_filter($transformedRecord['informations']);

        if($transformedRecord['name_2013'] == '') {
            return null;
        }

        return $transformedRecord;
    }

    /**
     * Converst the record column to utf8 if needed
     * @param Record $record
     * @param Column $column
     * @return bool|float|int|string
     */
    protected function utf8Convert(Record $record, Column $column)
    {
        $field = $record->{$column->getName()};
        return ($column->getType() == Record::DBFFIELD_TYPE_MEMO ||
            $column->getType() == Record::DBFFIELD_TYPE_CHAR) ?
            iconv("IBM850", "UTF-8//TRANSLIT", $field) :
            $field;
    }


    /**
     * Extracting receivers, returns a tupel with the persons and an error check
     * @param $string
     * @return array
     */
    protected function name($string)
    {
        $string = preg_replace("/;([\\/~><])/", ":$1", $string);

        // remove -(...) and -[...]
        $string = preg_replace("/(.*)\\-\\(.*?\\)(.*)/", "$1$2", $string);
        $string = preg_replace("/(.*)\\-\\[.*?\\](.*)/", "$1$2", $string);

        // remove (:>...) and (:>...)
        $string = preg_replace("/(.*)\\(\\??:[\\/~><].*?\\)(.*)/", "$1$2", $string);

        // maybe
        $string = preg_replace("/(.*)\\(\\?(.*?)\\)(.*)/", "$1$2$3", $string);

        // remove remaining braces
        $string = preg_replace("/(.*)\\(.*?\\)(.*)/", "$1$3", $string);

        // remove double spaces
        while(strpos($string, '  ') !== false) {
            $string = str_replace('  ', ' ', $string);
        }

        return trim($string);
    }
}