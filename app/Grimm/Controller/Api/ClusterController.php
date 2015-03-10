<?php

namespace Grimm\Controller\Api;

use Carbon\Carbon;
use Grimm\Cluster\ClusterService;
use Grimm\Letter\LetterService;
use Grimm\Location\LocationService;
use Grimm\Person\PersonService;
use Input;
use Response;

class ClusterController extends \Controller {

    /**
     * @var LetterService
     */
    protected $letterService;
    /**
     * @var PersonService
     */
    protected $personService;
    /**
     * @var LocationService
     */
    protected $locationService;

    public function __construct(LetterService $letterService, PersonService $personService, LocationService $locationService, ClusterService $clusterService)
    {
        $this->letterService = $letterService;
        $this->personService = $personService;
        $this->locationService = $locationService;
        $this->clusterService = $clusterService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changes()
    {
        $since = $this->clusterService->latestNotification();

        $letters = $this->letterService->count($since);
        $persons = $this->personService->count($since);
        $locations = $this->locationService->count($since);

        return Response::json([
            'letters'   => $letters,
            'persons'   => $persons,
            'locations' => $locations,
            'total'     => $letters + $persons + $locations
        ]);
    }

    /**
     * Sends notification to all subscribers
     */
    public function publish()
    {

    }

    public function subscribe()
    {
        $secret = Input::get('secret');
        $address = Input::get('address');

        $this->clusterService->addSubscriber($secret, $address);
    }
}