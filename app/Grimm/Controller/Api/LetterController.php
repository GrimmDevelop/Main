<?php

namespace Grimm\Controller\Api;

use Grimm\Assigner\Letters\From as LetterFrom;
use Grimm\Assigner\Letters\To as LetterTo;
use Grimm\Assigner\Letters\Receiver as LetterReceiver;
use Grimm\Assigner\Letters\Sender as LetterSender;
use Grimm\Assigner\Exceptions\ItemAlreadyAssignedException;
use Grimm\Assigner\Exceptions\ItemNotFoundException;
use Grimm\Assigner\Exceptions\ObjectNotFoundException;
use Grimm\Facades\UserAction;
use Grimm\Letter\LetterService;
use Grimm\OutputTransformer\ArrayOutput;
use Grimm\OutputTransformer\JsonPaginationOutput;
use Grimm\Search\SearchService;
use Input;
use Response;

class LetterController extends \Controller {

    /**
     * @var array Assigner
     */
    protected $assigner;

    /**
     * @var LetterService
     */
    protected $letterService;

    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * @var JsonPaginationOutput
     */
    protected $paginationOutput;

    protected $arrayOutput;

    public function __construct(
        LetterService $letterService,
        SearchService $searchService,
        JsonPaginationOutput $paginationOutput,
        ArrayOutput $arrayOutput,
        LetterSender $letterSenderAssigner,
        LetterReceiver $letterReceiverAssigner,
        LetterFrom $letterFromAssigner,
        LetterTo $letterToAssigner
    )
    {
        $this->letterService = $letterService;
        $this->searchService = $searchService;
        $this->paginationOutput = $paginationOutput;
        $this->arrayOutput = $arrayOutput;

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
        $result = $this->searchService->search(
            ['information', 'senders', 'receivers', 'from', 'to'], [],
            abs((int)Input::get('items_per_page', 25)),
            Input::get('updated_after')
        );

        return $this->createSearchOutput($result);
    }

    /**
     * List's one letter json object per row
     */
    public function stream()
    {
        $result = $this->searchService->search(['information', 'senders', 'receivers', 'from', 'to'], [], abs((int)Input::get('items_per_page', 25)));

        foreach ($result->getCollection() as $row) {
            echo $row->toJson() . "\n";
            flush();
        }
    }

    public function linkTable()
    {

    }

    /**
     * Store a newly created letter in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = [
            'code' => Input::get('code'),
            'date' => Input::get('date'),
            'information' => Input::get('information', [])
        ];

        if($this->letterService->create($data)) {
            return $this->createMessageResponse('letter created');
        }

        return $this->createMessageResponse('letter not created', false, 400);
    }


    /**
     * Display the specified letter.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        if ($letter = $this->letterService->findById($id)) {
            return Response::json($letter->load('information', 'senders', 'receivers', 'from', 'to'));
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
        $data = [
            'code' => Input::get('code'),
            'date' => Input::get('date'),
            'information' => Input::get('information', [])
        ];

        if ($this->letterService->update($id, $data)) {
            return $this->createMessageResponse('changes saved');
        }

        return $this->createMessageResponse('changes not saved', false, 400);
    }

    /**
     * TODO: this has nothing to do here...
     * Assigns an items to an object by given mode
     * @param $mode
     * @return \Illuminate\Http\Response
     */
    public function assign($mode)
    {
        if (!isset($this->assigner[$mode])) {
            return $this->createMessageResponse('Unknown method ' . $mode, false);
        }

        // TODO: use of events
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
                return $this->createMessageResponse($itemResponseName . ' assigned to letter');
            } else {
                return $this->createMessageResponse('Unknown error occurred', false);
            }
        } catch (ObjectNotFoundException $e) {
            return $this->createMessageResponse('Letter not found', false, 404);
        } catch (ItemNotFoundException $e) {
            return $this->createMessageResponse($itemResponseName . ' not found', false, 404);
        } catch (ItemAlreadyAssignedException $e) {
            return $this->createMessageResponse($itemResponseName . ' already assigned', false);
        }
    }

    /**
     * TODO: this has nothing to do here...
     * Removes the link between two objects
     * @param $mode
     * @return \Illuminate\Http\JsonResponse
     */
    public function unassign($mode)
    {
        return $this->createMessageResponse('Unkown method ' . $mode, false);
    }

    /**
     * Remove the specified letter from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($this->letterService->delete($id)) {
            return $this->createMessageResponse('Letter successfully deleted');
        }

        return $this->createMessageResponse('Letter id not found', false, 404);
    }

    public function trashed() {
        return $this->createSearchOutput($this->letterService->findTrashed());
    }

    public function restore() {
        if($this->letterService->restore(Input::get('id'))) {
            return $this->createMessageResponse('Letter successfully restored');
        }

        return $this->createMessageResponse('Letter id not found', false, 404);
    }

    /**
     * @param $result \Illuminate\Pagination\Paginator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createSearchOutput($result)
    {
        return Response::json($this->paginationOutput->transform($result));
    }

    protected function createArrayOutput($result)
    {
        return Response::json($this->arrayOutput->transform($result));
    }

    protected function createEntityOutput($result)
    {
        return Response::json($result);
    }

    protected function createMessageResponse($message, $success = true, $responseCode = 200)
    {
        return Response::json([
            'type' => $success ? 'success' : 'danger',
            'message' => $message
        ], $responseCode);
    }
}
