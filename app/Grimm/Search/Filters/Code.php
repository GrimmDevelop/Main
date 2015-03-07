<?php


namespace Grimm\Search\Filters;


class Code {

    protected $code;

    public function __construct($code)
    {
        if (!in_array($code, $this->codes())) {
            throw new \InvalidArgumentException();
        }

        $this->code = $code;
    }

    public function get()
    {
        return $this->code;
    }

    public function codes()
    {
        return [
            'abschrift',
            'absendeort',
            'absender',
            'absort_ers',
            'antw_verm',
            'auktkat',
            'ausg_notiz',
            'ba',
            'beilage',
            'couvert',
            'del',
            'dr',
            'empf_ort',
            'empf_verm',
            'empfaenger',
            'erschl_aus',
            'faks',
            'gesehen_12',
            'hs',
            'inc',
            'konzept',
            'kopie',
            'nr_1992',
            'nr_1997',
            'senders',
            'receivers'
        ];
    }
}