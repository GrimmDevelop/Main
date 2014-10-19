<?php

namespace Grimm\Controller\Api;

use Carbon\Carbon;
use Grimm\Assigner\LetterFrom;
use Grimm\Assigner\LetterTo;
use Grimm\Assigner\LetterReceiver;
use Grimm\Assigner\LetterSender;
use Grimm\Assigner\Exceptions\ItemAlreadyAssignedException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;
use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Facades\UserAction;
use Grimm\Models\Letter;
use Grimm\Models\Letter\Information;
use Grimm\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Input;
use Response;
use Sentry;

class LetterController extends \Controller {

    /**
     * @var array Assigner
     */
    protected $assigner;

    public function __construct(LetterSender $letterSenderAssigner, LetterReceiver $letterReceiverAssigner, LetterFrom $letterFromAssigner, LetterTo $letterToAssigner)
    {
        $this->assigner['senders'] = $letterSenderAssigner;
        $this->assigner['receivers'] = $letterReceiverAssigner;
        $this->assigner['from'] = $letterFromAssigner;
        $this->assigner['to'] = $letterToAssigner;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $result = $this->loadItems(
            abs((int)Input::get('items_per_page', 25)),
            Input::get('load', ['informations', 'senders', 'receivers', 'from', 'to'])
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
        return Letter::where('updated_at', '>=', Carbon::createFromDate($year, $month, $day))->take(Input::get('take', 100))->get()->toJson();
    }

    public function stream()
    {
        $result = $this->loadItems(
            abs((int)Input::get('items_per_page', 25)),
            Input::get('load', ['senders', 'receivers', 'from', 'to'])
        );

        if ($result instanceof JsonResponse) {
            return $result;
        }

        foreach ($result as $row) {
            echo $row->toJson() . "\n";
            flush();
        }
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

        $builder = Letter::query();

        foreach ($with as $item) {
            if (in_array($item, ['informations', 'senders', 'receivers', 'from', 'to'])) {
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

        if (Sentry::check()) {
            $builder->where(function ($builder) {
                foreach (Input::get('with_errors', []) as $error) {
                    switch ($error) {
                        case "from":
                            $this->withFromErrors($builder);
                            break;

                        case "to":
                            $this->withToErrors($builder);
                            break;

                        case "senders":
                            $this->withSendersErrors($builder);
                            break;

                        case "receivers":
                            $this->withReceiversErrors($builder);
                            break;
                    }
                }
            });
        }

        return $builder->paginate($totalItems);
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withFromErrors(Builder $builder)
    {
        return $builder->orWhere(function ($query) {
            $query->where('from_id', null);
            $query->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and (letter_informations.code = "absendeort" or letter_informations.code = "absort_ers") and data != "") > 0');
        });
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withToErrors(Builder $builder)
    {
        return $builder->orWhere(function ($query) {
            $query->where('to_id', null);
            $query->whereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "empf_ort" and data != "") > 0');
        });
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withSendersErrors(Builder $builder)
    {
        return $builder->orWhereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withReceiversErrors(Builder $builder)
    {
        return $builder->orWhereRaw('(select count(*) from letter_informations where letters.id = letter_informations.letter_id and letter_informations.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        if ($letter = Letter::find($id)) {
            return $letter->load('informations', 'senders', 'receivers', 'from', 'to')->toJson();
        }

        return Response::json(array('type' => 'danger', 'message' => 'given id not found in database'), 404);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('letters.edit'))) {
            return Response::json('Unauthorized action.', 403);
        }

        if (!($letter = Letter::find($id))) {
            return Response::json(['type' => 'danger', 'message' => 'unknown letter ' . $id], 404);
        }

        foreach (Input::get('informations', []) as $information) {
            switch ($information['state']) {
                case 'add':
                    $this->addInformation($letter, $information);
                    break;

                case 'keep':
                    if ($info = Information::find($information['id'])) {
                        if ($info->data != $information['data']) {
                            $this->updateInformation($letter, $info, $information['data']);
                        }
                    }
                    break;

                case 'remove':
                    if ($info = Information::find($information['id'])) {
                        $this->removeInformation($letter, $info);
                    }
                    break;
            }
        }

        return Response::json(['type' => 'success', 'message' => 'changes saved'], 200);
    }

    protected function addInformation(Letter $letter, array $information)
    {
        UserAction::log('letters.edit.add_information', [
            'letter_id' => $letter->id,
            'code' => $information['code'],
            'data' => $information['data']
        ]);

        return $letter->informations()->save(new Information([
            'code' => $information['code'],
            'data' => $information['data']
        ]));
    }

    protected function updateInformation(Letter $letter, Information $information, $newData)
    {
        UserAction::log('letters.edit.update_information', [
            'letter_id' => $letter->id,
            'code' => $information->code,
            'data_old' => $information->data,
            'data_new' => $newData
        ]);

        $letter->touch();
        $information->data = $newData;
        $information->save();
    }

    protected function removeInformation(Letter $letter, Information $information)
    {
        UserAction::log('letters.edit.remove_information', [
            'letter_id' => $letter->id,
            'code' => $information->code,
            'data' => $information->data
        ]);

        $letter->touch();
        return $information->delete();
    }

    public function assign($mode)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('letters.edit'))) {
            return Response::json('Unauthorized action.', 403);
        }

        if (!isset($this->assigner[$mode])) {
            return Response::json(array('type' => 'danger', 'message' => 'Unkown method ' . $mode));
        }

        UserAction::log('letters.assign', [
            'letter_id' => Input::get('object_id'),
            'mode' => $mode,
            'item_id' => Input::get('item_id')
        ]);

        $itemResponseName = $mode == 'from' || $mode == 'to' ? 'Location' : 'Person';
        try {
            if($this->assigner[$mode]->assign(
                Input::get('object_id'),
                Input::get('item_id')
            )) {
                return \Response::json(array('type' => 'success', 'message' => $itemResponseName . ' assigned to letter'), 200);
            } else {
                return \Response::json(array('type' => 'danger', 'message' => 'Unknown error occured'), 200);
            }
        } catch(ObjectNotFoundException $e) {
            return \Response::json(array('type' => 'danger', 'message' => 'Letter not found'), 404);
        } catch(ItemNotFoundException $e) {
            return \Response::json(array('type' => 'danger', 'message' => $itemResponseName . ' not found'), 404);
        } catch(ItemAlreadyAssignedException $e) {
            return \Response::make(array('type' => 'warning', 'message' => $itemResponseName . ' already assigned'), 200);
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
        //
    }

}
