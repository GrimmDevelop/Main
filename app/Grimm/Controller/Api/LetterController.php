<?php

namespace Grimm\Controller\Api;

use Carbon\Carbon;
use Grimm\Assigner\Letters\From as LetterFrom;
use Grimm\Assigner\Letters\To as LetterTo;
use Grimm\Assigner\Letters\Receiver as LetterReceiver;
use Grimm\Assigner\Letters\Sender as LetterSender;
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
     * Display a listing of letters as Paginator json object
     *
     * @return Response|string
     */
    public function index()
    {
        $result = $this->loadItems(
            abs((int)Input::get('items_per_page', 25)),
            Input::get('load', ['information', 'senders', 'receivers', 'from', 'to'])
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
     * List's one letter json object per row
     */
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

    public function linkTable()
    {

    }

    /**
     * builds a Paginator object containing all letters requested
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
            if (in_array($item, ['information', 'senders', 'receivers', 'from', 'to'])) {
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
            $query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and (letter_information.code = "absendeort" or letter_information.code = "absort_ers") and data != "") > 0');
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
            $query->whereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "empf_ort" and data != "") > 0');
        });
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withSendersErrors(Builder $builder)
    {
        return $builder->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "senders" and data != "") != (select count(*) from letter_sender where letters.id = letter_sender.letter_id)');
    }

    /**
     * @param $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    protected function withReceiversErrors(Builder $builder)
    {
        return $builder->orWhereRaw('(select count(*) from letter_information where letters.id = letter_information.letter_id and letter_information.code = "receivers" and data != "") != (select count(*) from letter_receiver where letters.id = letter_receiver.letter_id)');
    }

    /**
     * Store a newly created letter in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified letter.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        if ($letter = Letter::find($id)) {
            return $letter->load('information', 'senders', 'receivers', 'from', 'to')->toJson();
        }

        return Response::json(array('type' => 'danger', 'message' => 'given id not found in database'), 404);
    }

    /**
     * Update the specified letter in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!($letter = Letter::find($id))) {
            return Response::json(['type' => 'danger', 'message' => 'unknown letter ' . $id], 404);
        }

        foreach (Input::get('information', []) as $info) {
            switch ($info['state']) {
                case 'add':
                    $this->addInformation($letter, $info);
                    break;

                case 'keep':
                    if ($infoObj = Information::find($info['id'])) {
                        if ($infoObj->data != $info['data']) {
                            $this->updateInformation($letter, $infoObj, $info['data']);
                        }
                    }
                    break;

                case 'remove':
                    if ($info = Information::find($info['id'])) {
                        $this->removeInformation($letter, $info);
                    }
                    break;
            }
        }

        $newCode = (float)Input::get('code');
        if ($newCode != $letter->code) {
            UserAction::log('letters.edit.update_field', [
                'letter_id' => $letter->id,
                'field' => 'code',
                'data_old' => $letter->code,
                'data_new' => $newCode
            ]);
            $letter->code = $newCode;
        }

        $letter->save();

        return Response::json(['type' => 'success', 'message' => 'changes saved'], 200);
    }

    /**
     * Adds a information to a letter
     * @param Letter $letter
     * @param array $info
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function addInformation(Letter $letter, array $info)
    {
        UserAction::log('letters.edit.add_information', [
            'letter_id' => $letter->id,
            'code' => $info['code'],
            'data' => $info['data']
        ]);

        return $letter->information()->save(new Information([
            'code' => $info['code'],
            'data' => $info['data']
        ]));
    }

    /**
     * Updates a information from given letter and touches the letter
     * @param Letter $letter
     * @param Information $info
     * @param $newData
     */
    protected function updateInformation(Letter $letter, Information $info, $newData)
    {
        UserAction::log('letters.edit.update_information', [
            'letter_id' => $letter->id,
            'code' => $info->code,
            'data_old' => $info->data,
            'data_new' => $newData
        ]);

        $letter->touch();
        $info->data = $newData;
        $info->save();
    }

    /**
     * Remove the information from the letter and touches the letter
     * @param Letter $letter
     * @param Information $info
     * @return bool|null
     * @throws \Exception
     */
    protected function removeInformation(Letter $letter, Information $info)
    {
        UserAction::log('letters.edit.remove_information', [
            'letter_id' => $letter->id,
            'code' => $info->code,
            'data' => $info->data
        ]);

        $letter->touch();

        return $info->delete();
    }

    /**
     * Assigns an items to an object by given mode
     * @param $mode
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function assign($mode)
    {
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
            if ($this->assigner[$mode]->assign(
                Input::get('object_id'),
                Input::get('item_id')
            )
            ) {
                return \Response::json(array('type' => 'success', 'message' => $itemResponseName . ' assigned to letter'), 200);
            } else {
                return \Response::json(array('type' => 'danger', 'message' => 'Unknown error occured'), 200);
            }
        } catch (ObjectNotFoundException $e) {
            return \Response::json(array('type' => 'danger', 'message' => 'Letter not found'), 404);
        } catch (ItemNotFoundException $e) {
            return \Response::json(array('type' => 'danger', 'message' => $itemResponseName . ' not found'), 404);
        } catch (ItemAlreadyAssignedException $e) {
            return \Response::make(array('type' => 'warning', 'message' => $itemResponseName . ' already assigned'), 200);
        }
    }

    /**
     * Removes the link between two objects
     * @param $mode
     * @return JsonResponse
     */
    public function unassign($mode)
    {
        return Response::json(array('type' => 'danger', 'message' => 'Unkown method ' . $mode));
    }

    /**
     * Remove the specified letter from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($letter = Letter::find($id)) {
            $letter->delete();
            return \Response::json(array('type' => 'success', 'message' => 'Letter successfully deleted'), 200);
        }

        return \Response::json(array('type' => 'danger', 'message' => 'Letter id not found'), 404);
    }
}
