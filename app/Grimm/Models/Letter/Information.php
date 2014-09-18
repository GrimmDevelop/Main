<?php

namespace Grimm\Models\Letter;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter;

class Information extends Eloquent
{
    protected $table = 'letter_informations';

    public function codes()
    {
        return array(
            'code',
            'language',
            'prints',
            'script_location',
            'inc',
            'concept',
            'facsimile',
            'transcription',
            'duplicate',
            /*
             * TODO: better code
             */
            'auktkat',
            /*
             * TODO: better code
             */
            'deducted_from',

            'received_probably',
            'answered_probably',

            'addition',

            /*
             * TODO: better code
             */
            'ba',
            'nr_1992',
            'nr_1997',

            'couvert',
            'verz_in',
            'beilage',
            'ausg_notiz',
            'tb_nr',
        );
    }

    public function letter()
    {
        return $this->belongsTo('Grimm\Models\Letter');
    }
}
