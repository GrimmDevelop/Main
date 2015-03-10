<?php

namespace Grimm\Cluster;

use Carbon\Carbon;
use Grimm\Letter\LetterService;
use Grimm\Location\LocationService;
use Grimm\Person\PersonService;

class EloquentClusterService implements ClusterService {

    /**
     * @var Subscriber
     */
    private $subscriberService;
    /**
     * @var LetterService
     */
    private $letterService;
    /**
     * @var PersonService
     */
    private $personService;
    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(Subscriber $subscriberService, LetterService $letterService, PersonService $personService, LocationService $locationService)
    {
        $this->subscriberService = $subscriberService;
        $this->letterService = $letterService;
        $this->personService = $personService;
        $this->locationService = $locationService;
    }

    public function changes($since)
    {
        $letters = $this->letterService->count($since);
        $persons = $this->personService->count($since);
        $locations = $this->locationService->count($since);

        return new Changes($letters, $persons, $locations);
    }

    /**
     * @return \Carbon\Carbon
     */
    public function latestNotification()
    {
        $result = $this->subscriberService->selectRaw('MIN(last_notification) as latest')->where('approved', 1)->first();

        if (!$result->latest)
        {
            return "0000-00-00 00:00:00";
        }

        return Carbon::parse($result->latest);
    }

    /**
     * @return mixed
     */
    public function subscribers()
    {
        return $this->subscriberService->where('approved', 1)->get();
    }

    public function publish()
    {
        /** @var Subscriber $subscriber */
        foreach ($this->subscribers() as $subscriber)
        {
            echo $this->notify($subscriber);
        }
    }

    public function notify(Subscriber $subscriber)
    {
        return $subscriber->notify();
    }

    /**
     * @return mixed
     */
    public function unapprovedSubscribers()
    {
        return $this->subscriberService->where('approved', 0)->get();
    }

    /**
     * @param $subscriberSecret
     * @param $address
     * @return mixed
     */
    public function addSubscriber($subscriberSecret, $address)
    {
        return $this->subscriberService->create([
            'secret'  => $subscriberSecret,
            'address' => $address
        ]) !== null;
    }

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function removeSubscriber($subscriberSecret)
    {
        return $this->subscriberService->where('secret', $subscriberSecret)->delete() > 0;
    }

    /**
     * @param $subscriberSecret
     * @return bool
     */
    public function approveSubscriber($subscriberSecret)
    {
        /** @var Subscriber $subscriber */
        if($subscriber = $this->subscriberService->where('secret', $subscriberSecret)->first())
        {
            $subscriber->approve();

            return $subscriber->isApproved();
        }

        return false;
    }

    /**
     * @param $subscriberSecret
     * @return bool
     */
    public function hasSubscriber($subscriberSecret)
    {
        return $this->subscriberService->where('secret', $subscriberSecret)->first() !== null;
    }
}