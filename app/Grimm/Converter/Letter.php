<?php

namespace Grimm\Converter;

use XBase\Table;
use XBase\Record;
use XBase\Column;

class Letter implements ConverterInterface {

    protected $file;

    protected $cache = null;

    public function __construct(\File $file) {
        $this->file = $file;
    }

    public function setSource($source) {
        if(!file_exists($source)) {
            throw new \InvalidArgumentException('Invalid source (file not found)');
        }
        $this->source = $source;
        $this->cache = null;
    }

    public function setFilter(array $filter) {
        $this->filter = $filter;
    }

    public function parse() {
        $table = new Table($this->source);

        $this->cache = array();
        while($record = $table->nextRecord()) {
            $data = $this->convert($record);
            $this->cache[] = $data;
            yield $data;
        }
    }

    public function convert($data)
    {
        $utf8DataArray = array_map(function($column) use($data) {
            return $this->utf8Convert($data, $column);
        }, $data->getColumns());


        $senders = $this->senders($utf8DataArray['absender']);
        $receivers = $this->receivers($utf8DataArray['empfaenger']);

        $record = array(
            'id' => (int)$utf8DataArray['nr'],
            'gesehen_12' => (int)$utf8DataArray['gesehen_12'],
            'code' => (float)str_replace(',', '.', $utf8DataArray['code']),
            'datum' => $utf8DataArray['datum'],
            'absendeort' => $utf8DataArray['absendeort'],
            'absort_ers' => $utf8DataArray['absort_ers'],
            'empf_ort' => $utf8DataArray['empf_ort'],

            'absender' => $utf8DataArray['absender'],
            'senders' => $senders[0],
            'senders_contain_errors' => $senders[1],

            'empfaenger' => $utf8DataArray['empfaenger'],
            'receivers' => $receivers[0],
            'receivers_contain_errors' => $receivers[1],

            'sprache' => $utf8DataArray['sprache'],
            'hs' => $utf8DataArray['hs'],
            'inc' => $utf8DataArray['inc'],
            'dr' => array(
                $utf8DataArray['dr_1'],
                $utf8DataArray['dr_2'],
                $utf8DataArray['dr_3'],
                $utf8DataArray['dr_4'],
                $utf8DataArray['dr_5'],
                $utf8DataArray['dr_6'],
                $utf8DataArray['dr_7']
            ),
            'faks' => $utf8DataArray['faks'],
            'konzept' => array(
                $utf8DataArray['konzept'],
                $utf8DataArray['konzept_2']
            ),
            'abschrift' => array(
                $utf8DataArray['abschrift'],
                $utf8DataArray['abschr_2'],
                $utf8DataArray['abschr_3'],
                $utf8DataArray['abschr_4']
            ),
            'kopie' => $utf8DataArray['kopie'],
            'auktkat' => array(
                $utf8DataArray['auktkat'],
                $utf8DataArray['auktkat_2'],
                $utf8DataArray['auktkat_3'],
                $utf8DataArray['auktkat_4']
            ),
            'erschl_aus' => $utf8DataArray['erschl_aus'],
            'empf_verm' => $utf8DataArray['empf_verm'],
            'antw_verm' => $utf8DataArray['antw_verm'],
            'zusatz' => array(
                $utf8DataArray['zusatz'],
                $utf8DataArray['zusatz_2']
            ),
            'ba' => $utf8DataArray['ba'],
            'nr_1992' => (int)$utf8DataArray['nr_1992'],
            'nr_1997' => (int)$utf8DataArray['nr_1997'],
            'couvert' => $utf8DataArray['couvert'],
            'verz_in' => $utf8DataArray['verz_in'],
            'beilage' => $utf8DataArray['beilage'],
            'ausg_notiz' => $utf8DataArray['ausg_notiz'],
            'tb_nr' => $utf8DataArray['tb_nr'],
            'del' => $utf8DataArray['del']
        );

        $record['dr'] = array_filter($record['dr'], 'strlen');
        $record['konzept'] = array_filter($record['konzept'], 'strlen');
        $record['abschrift'] = array_filter($record['abschrift'], 'strlen');
        $record['auktkat'] = array_filter($record['auktkat'], 'strlen');
        $record['zusatz'] = array_filter($record['zusatz'], 'strlen');

        return $record;
    }

    public function toArray()
    {
        if(is_null($this->cache)) {
            throw new \Exception("cache is null, run parse() first!");
        }

        return $this->cache;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Converst the record column to utf8 if needed
     * @param Record $record
     * @param Column $column
     * @return bool|float|int|string
     */
    protected function utf8Convert(Record $record, Column $column) {
        $field = $record->{$column->getName()};
        return ($column->getType() == Record::DBFFIELD_TYPE_MEMO ||
            $column->getType() == Record::DBFFIELD_TYPE_CHAR) ?
            iconv("IBM850", "UTF-8//TRANSLIT", $field) :
            $field;
    }

    /**
     * Extracting senders, returns a tupel with the persons and an error check
     * @param $string
     * @return array
     */
    protected function senders($string) {
        // TODO: Maybe there're differences between senders and receivers
        return $this->receivers($string);
    }

    /**
     * Extracting receivers, returns a tupel with the persons and an error check
     * @param $string
     * @return array
     */
    protected function receivers($string) {

        $string = preg_replace("/;([\\/~><])/", ":$1", $string);

        // remove -(...) and -[...]
        $string = preg_replace("/(.*)\\-\\(.*?\\)(.*)/", "$1$2", $string);
        $string = preg_replace("/(.*)\\-\\[.*?\\](.*)/", "$1$2", $string);

        // remove (:>...) and (:>...)
        $string = preg_replace("/(.*)\\(\\??:[\\/~><].*?\\)(.*)/", "$1$2", $string);

        // maybe
        $string = preg_replace("/(.*)\\(\\?(.*?)\\)(.*)/", "$1$2$3", $string);

        // remove remaining braces
        $string = preg_replace("/(.*)\\(.*?\\)(.*)/", "$1$2$3", $string);

        $persons = array_map('trim', explode(';', $string));
        $errors = (bool)str_contains($string, array("(", ")", "[", "]", ":", "?", ">", "<", ""));

        return array($persons, $errors);

    }
}
