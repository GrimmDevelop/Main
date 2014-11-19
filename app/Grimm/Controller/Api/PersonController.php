<?php

namespace Grimm\Controller\Api;

use Grimm\Models\Person;
use Grimm\Models\Person\Information;
use Input;
use Response;

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
            Input::get('load', ['information'])
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

        if (Input::get('ahead', false)) {
            return $builder->select('*', 'name_2013 as name')->where('name_2013', 'like', \Input::get('name') . '%')->take(15)->get();
        } else {
            $builder->with('information')->select('*', 'name_2013 as name')->where('name_2013', \Input::get('name'));
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
            if (in_array($item, ['information'])) {
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

        if (Input::get('auto_generated', false)) {
            $builder->where('auto_generated', true);
        }

        $orderBy = in_array(Input::get('order_by'), ['name_2013', 'sended_letters_count', 'received_letters_count']) ? Input::get('order_by') : 'name_2013';
        $orderByDirection = in_array(Input::get('order_by_direction'), ['asc', 'desc']) ? Input::get('order_by_direction') : 'asc';

        $builder->orderBy($orderBy, $orderByDirection);

        return $builder->paginate($totalItems);
    }

    public function codes()
    {
        return (new Information)->codes();
    }

    public function correspondence()
    {
        // person
        // Person::find($id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (Person::create(Input::only('name_2013', 'auto_generated'))) {
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
        if ($person = Person::find($id)) {
            return $person->load('information')->toJson();
        }

        return null;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!($person = Person::find($id)))
        {
            return Response::json(['type' => 'danger', 'message' => 'unknown person ' . $id], 404);
        }

        if(Input::get('name_2013') != '') {
            $person->name_2013 = Input::get('name_2013');
        }

        $person->save();

        return Response::json(['type' => 'success', 'message' => 'changes saved'], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        // destroy
    }


}
