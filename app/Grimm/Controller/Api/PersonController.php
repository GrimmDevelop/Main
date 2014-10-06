<?php

namespace Grimm\Controller\Api;

use App;
use Input;
use Grimm\Models\Person;

class PersonController extends \Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $result = $this->loadItems(
            abs((int)Input::get('items_per_page', 25)),
            Input::get('load', ['informations'])
        );

        if ($result instanceof JsonResponse) {
            return $result;
        }

        $return = new \stdClass();

        $return->total = $result->getTotal();
        $return->per_page = $result->getPerPage();
        $return->current_page = $result->getCurrentPage();
        $return->last_page = $result->getLastPage();
        $return->from = $result->getFrom();
        $return->to = $result->getTo();
        $return->data = $result->getCollection()->toArray();

        return json_encode($return);
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
        return Person::where('updated_at', '>=', \Carbon\Carbon::createFromDate($year, $month, $day))->get()->toJson();
    }

    public function search()
    {
        $result = Person::with('informations')->where('name_2013', \Input::get('name'))->get();

        if ($result->count() > 0) {
            return $result->toJson();
        }

        return \Response::json(array('type' => 'danger', 'message' => 'Person not found'), 404);

    }

    /**
     * @param $totalItems
     * @param array $with
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Pagination\Paginator
     */
    protected function loadItems($totalItems, array $with = [])
    {
        $totalItems = abs((int)$totalItems);

        if ($totalItems > 500) {
            $totalItems = 500;
        }

        $builder = Person::query();

        foreach ($with as $item) {
            if (in_array($item, ['informations', 'receivedLetters', 'sendedLetters'])) {
                $builder->with($item);
            }
        }

        if (Input::get('updated_after')) {
            try {
                $dateTime = Carbon::createFromFormat('Y-m-d h:i:s', Input::get('updated_after'));
            } catch (\InvalidArgumentException $e) {
                try {
                    $dateTime = Carbon::createFromFormat('Y-m-d', Input::get('updated_after'));
                } catch (\InvalidArgumentException $e) {
                    return Response::json(array('type' => 'danger', 'given date does not fit format (Y-m-d [h:i:s]'), 500);
                }
            }

            $builder->where('updated_at', '>=', $dateTime);
        }

        return $builder->paginate($totalItems);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.create'))) {
            return \Response::json('Unauthorized action.', 403);
        }


    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.create'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Person::find($id)->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.edit'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.edit'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.delete'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


}
