<?php

namespace Grimm\Transformer;

use Grimm\Contract\RecordTransformer;
use Grimm\Import\Records\LetterRecord as TheLetterRecord;
use XBase\Column;
use XBase\Record;

class LetterRecord implements RecordTransformer {

    /**
     * Transform record data to target data
     * @param $data
     * @return mixed
     */
    public function transform($data)
    {
        $utf8DataArray = array_map(function ($column) use ($data)
        {
            return $this->utf8Convert($data, $column);
        }, $data->getColumns());

        $senders = $this->senders($utf8DataArray['absender']);
        $receivers = $this->receivers($utf8DataArray['empfaenger']);

        $id = (int) $utf8DataArray['nr'];
        $code = /*(float) */floatval(str_replace(',', '.', $utf8DataArray['code']));
        $newCode = $this->correctCode($code);
        if ($newCode !== null) {
            $code = $newCode;
        }
        $language = $utf8DataArray['sprache'];
        $date = $utf8DataArray['datum'];

        $information = array(
            'gesehen_12'               => $utf8DataArray['gesehen_12'],
            'absendeort'               => $utf8DataArray['absendeort'],
            'absort_ers'               => $utf8DataArray['absort_ers'],
            'empf_ort'                 => $utf8DataArray['empf_ort'],

            'absender'                 => $utf8DataArray['absender'],
            'senders'                  => $senders[0],
            'senders_contain_errors'   => $senders[1],

            'empfaenger'               => $utf8DataArray['empfaenger'],
            'receivers'                => $receivers[0],
            'receivers_contain_errors' => $receivers[1],

            'hs'                       => $utf8DataArray['hs'],
            'inc'                      => $utf8DataArray['inc'],
            'dr'                       => array(
                $utf8DataArray['dr_1'],
                $utf8DataArray['dr_2'],
                $utf8DataArray['dr_3'],
                $utf8DataArray['dr_4'],
                $utf8DataArray['dr_5'],
                $utf8DataArray['dr_6'],
                $utf8DataArray['dr_7']
            ),
            'faks'                     => $utf8DataArray['faks'],
            'konzept'                  => array(
                $utf8DataArray['konzept'],
                $utf8DataArray['konzept_2']
            ),
            'abschrift'                => array(
                $utf8DataArray['abschrift'],
                $utf8DataArray['abschr_2'],
                $utf8DataArray['abschr_3'],
                $utf8DataArray['abschr_4']
            ),
            'kopie'                    => $utf8DataArray['kopie'],
            'auktkat'                  => array(
                $utf8DataArray['auktkat'],
                $utf8DataArray['auktkat_2'],
                $utf8DataArray['auktkat_3'],
                $utf8DataArray['auktkat_4']
            ),
            'erschl_aus'               => $utf8DataArray['erschl_aus'],
            'empf_verm'                => $utf8DataArray['empf_verm'],
            'antw_verm'                => $utf8DataArray['antw_verm'],
            'zusatz'                   => array(
                $utf8DataArray['zusatz'],
                $utf8DataArray['zusatz_2']
            ),
            'ba'                       => $utf8DataArray['ba'],
            'nr_1992'                  => (int) $utf8DataArray['nr_1992'],
            'nr_1997'                  => (int) $utf8DataArray['nr_1997'],
            'couvert'                  => $utf8DataArray['couvert'],
            'verz_in'                  => $utf8DataArray['verz_in'],
            'beilage'                  => $utf8DataArray['beilage'],
            'ausg_notiz'               => $utf8DataArray['ausg_notiz'],
            'tb_nr'                    => $utf8DataArray['tb_nr'],
            'del'                      => $utf8DataArray['del']
        );

        $information['dr'] = array_filter($information['dr'], 'strlen');
        $information['konzept'] = array_filter($information['konzept'], 'strlen');
        $information['abschrift'] = array_filter($information['abschrift'], 'strlen');
        $information['auktkat'] = array_filter($information['auktkat'], 'strlen');
        $information['zusatz'] = array_filter($information['zusatz'], 'strlen');

        $information = array_filter($information);

        if ($id == 0)
        {
            return null;
        }

        return new TheLetterRecord($id, $code, $language, $date, $information);
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
     * Extracting senders, returns a tupel with the persons and an error check
     * @param $string
     * @return array
     */
    protected function senders($string)
    {
        // TODO: Maybe there're differences between senders and receivers
        return $this->receivers($string);
    }

    /**
     * Extracting receivers, returns a tupel with the persons and an error check
     * @param $string
     * @return array
     */
    protected function receivers($string)
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
        $string = preg_replace("/(.*)\\(.*?\\)(.*)/", "$1$2$3", $string);

        $persons = array_filter(array_map('trim', explode(';', $string)));
        $errors = (bool) str_contains($string, array("(", ")", "[", "]", ":", "?", ">", "<", ""));

        return array($persons, $errors);
    }

    /**
     * This converts code of the form yyyymmdd.10n to yyyymmdd.1n
     * @param $code
     * @return mixed
     */
    protected function correctCode($code)
    {
        return preg_replace('/^([0-9]{8}\.1)0([0-9])$/', '$1$2', $code);
    }
}