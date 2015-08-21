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
        return $this->clusterService->publish();
    }

    public function subscribe()
    {
        $secret = Input::get('secret');
        $address = Input::get('address');

        // TODO: check secret
        $publisherSecret = Input::get('publisher_secret');
        if ($publisherSecret !== 'publisher-secret')
        {
            return 'invalid secret';
        }

        if ($secret && $address)
        {
            if ($this->clusterService->hasSubscriber($secret))
            {
                return 'already subscribed';
            }

            if(Input::get('text')) {
                return 'not subscribed';
            }

            if ($this->clusterService->addSubscriber($secret, $address))
            {
                return 'subscription successful';
            }
        }

        return 'subscription failed';
    }

    public function unsubscribe()
    {
        $secret = Input::get('secret');

        // TODO: check secret
        $publisherSecret = Input::get('publisher_secret');
        if ($publisherSecret !== 'publisher-secret')
        {
            return 'invalid secret';
        }

        if ($secret)
        {
            if (!$this->clusterService->hasSubscriber($secret))
            {
                return 'not subscribed';
            }

            if ($this->clusterService->removeSubscriber($secret))
            {
                return 'canceling subscription successful';
            }
        }

        return 'canceling subscription failed';
    }
}