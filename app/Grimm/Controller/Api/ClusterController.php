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
     * @var ClusterService
     */
    protected $clusterService;

    public function __construct(ClusterService $clusterService)
    {
        $this->clusterService = $clusterService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changes()
    {
        $since = $this->clusterService->latestNotification();

        return Response::json($this->clusterService->changes($since));
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