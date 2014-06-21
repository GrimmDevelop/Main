<?php

namespace Grimm\Controller;

use Grimm\Models\Letter;
use View;

class SearchController extends \Controller {

    protected $letter;

    public function __construct(Letter $letter) {
        $this->letter = $letter;
    }

    public function searchForm() {
        return View::make('pages.search', array(
            'codes' => $this->letter->codes()
        ));
    }

    public function searchResult() {
        /*
         * TODO: update to new data structure
         */

        if(Input::get('letter.nr') != '') {
            $s = \Grimm\Models\Letter::where('id', '=', Input::get('letter.nr'))
                ->orWhere('nr_1997', '=', Input::get('letter.nr'));
        } else {
            $s = \Grimm\Models\Letter::where(
                function($query) {
                    $query->where('absendeort', 'like', '%' . Input::get('send.location') . '%')
                        ->orWhere('absort_ers', 'like', '%' . Input::get('send.location') . '%');
                })
                ->where('absender', 'like', '%' . Input::get('send.name') . '%')
                ->where('empf_ort', 'like', '%' . Input::get('receive.location') . '%')
                ->where('empfaenger', 'like', '%' . Input::get('receive.name') . '%')
                ->where(function($query) {
                    foreach(explode(' ', Input::get('letter.inc')) as $incPart) {
                        if($incPart != '') {
                            $query->where('inc', 'like', '%' . $incPart . '%');
                        }
                    }
                })
                ->where('hs', 'like', '%' . Input::get('letter.hw_location') . '%');
        }

        return View::make('pages.search', array(
            'count' => $s->count(),
            'result' => $s->take(100)->get()
        ));
    }
}