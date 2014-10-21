<?php

namespace Grimm\Controller\Api;

use App;
use Input;
use Sentry;
use Response;
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

    public function search()
    {
        $builder = Person::query();

        if(Input::get('ahead', false)) {
            return $builder->select('*', 'name_2013 as name')->where('name_2013', 'like', \Input::get('name') . '%')->take(15)->get();
        } else {
            $builder->with('informations')->select('*', 'name_2013 as name')->where('name_2013', \Input::get('name'));
        }

        $result = $builder->get();

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

        $builder->selectRaw(
            'persons.*,
            (SELECT COUNT(letter_receiver.person_id) FROM letter_receiver WHERE persons.id = letter_receiver.person_id ) AS received_letters_count,
            (SELECT COUNT(letter_sender.person_id) FROM letter_sender WHERE persons.id = letter_sender.person_id ) AS sended_letters_count'
        );

        foreach ($with as $item) {
            if (in_array($item, ['informations'])) {
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

        if(Input::get('auto_generated', false)) {
            $builder->where('auto_generated', true);
        }

        $orderBy = in_array(Input::get('order_by'), ['name_2013', 'sended_letters_count', 'received_letters_count']) ? Input::get('order_by') : 'name_2013';
        $orderByDirection = in_array(Input::get('order_by_direction'), ['asc', 'desc']) ? Input::get('order_by_direction') : 'asc';

        $builder->orderBy($orderBy, $orderByDirection);

        return $builder->paginate($totalItems);
    }


    public function correspondence()
    {
        // person
        // Person::find($id);
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

        if(Person::create(Input::only('name_2013', 'auto_generated'))) {
            return Response::json(array('type' => 'success', 'message' => 'person generated.'), 200);
        } else {
            return Response::json(array('type' => 'danger', 'message' => 'creation failed!'), 500);
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
        if($person = Person::find($id)) {
            return $person->load('informations')->toJson();
        }

        return null;
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
