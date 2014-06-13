<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

require_once('routes_admin.php');
require_once('routes_api.php');
require_once('routes_queue.php');

Route::get('/', function() {
    return Redirect::to(URL::to('search'));
});

Route::get('/search', function() {
    return View::make('pages.search');
});

Route::post('/search', function() {
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
});
