<?php

use Grimm\Models\Letter;

class LetterController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->lettersChangedAfter(0, 0, 0);
    }


    /**
     * Display a listing of letters changed after given date
     * @param  int $year
     * @param  int $month
     * @param  int $day
     * @return Response
     */
    public function lettersChangedAfter($year, $month, $day)
    {
        return Letter::where('updated_at', '>=', \Carbon\Carbon::createFromDate($year, $month, $day))->get()->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
