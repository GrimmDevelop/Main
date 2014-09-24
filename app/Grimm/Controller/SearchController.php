<?php

namespace Grimm\Controller;

use Grimm\Models\Letter;
use View;
use Input;

class SearchController extends \Controller {

    protected $letter;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    public function searchForm()
    {
        if (\Config::get('grimm.api.use_imported_letters')) {
            return View::make('pages.searchimport');
        }

        return View::make('pages.search', array(
            'codes' => $this->letter->codes()
        ));
    }

    public function searchResult()
    {
        if (\Config::get('grimm.api.use_imported_letters')) {
            return $this->searchResultImportedLetters();
        }


    }

    public function searchResultImportedLetters()
    {
        /*
         * TODO: update to new data structure
         */

        if (Input::get('letter.nr') != '') {
            $id = abs((int)Input::get('letter.nr'));

            $s = Letter::with('informations')
                ->where('id', $id)
                ->orWhereHas('informations', function ($q) use ($id) {
                    return $q->where('code', 'nr_1997')->where('data', $id);
                });
        } else {
            $s = Letter::with('informations')->where(
                function ($query) {
                    $query->where('absendeort', 'like', '%' . Input::get('send.location') . '%')
                        ->orWhere('absort_ers', 'like', '%' . Input::get('send.location') . '%');
                })
                ->where('absender', 'like', '%' . Input::get('send.name') . '%')
                ->where('empf_ort', 'like', '%' . Input::get('receive.location') . '%')
                ->where('empfaenger', 'like', '%' . Input::get('receive.name') . '%')
                ->where(function ($query) {
                    foreach (explode(' ', Input::get('letter.inc')) as $incPart) {
                        if ($incPart != '') {
                            $query->where('inc', 'like', '%' . $incPart . '%');
                        }
                    }
                })
                ->where('hs', 'like', '%' . Input::get('letter.hw_location') . '%');
        }

        echo $s->toSql();

        return View::make('pages.searchimport', array(
            'codes' => $this->letter->codes(),
            'count' => $s->count(),
            'result' => $s->take(100)->get()
        ));
    }
}
