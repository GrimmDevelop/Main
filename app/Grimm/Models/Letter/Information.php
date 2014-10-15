<?php

namespace Grimm\Models\Letter;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter;

class Information extends Eloquent {
    protected $table = 'letter_informations';

    protected $fillable = ['code', 'data'];

    public function codes()
    {
        return array(
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
            'receivers'
        );
    }

    public function letter()
    {
        return $this->belongsTo('Grimm\Models\Letter');
    }
}
