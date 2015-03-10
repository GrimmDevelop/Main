<?php

namespace Grimm\Cluster;

use Carbon\Carbon;

class EloquentClusterService implements ClusterService {

    /**
     * @var Subscriber
     */
    private $subscriberService;

    public function __construct(Subscriber $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    /**
     * @return \Carbon\Carbon
     */
    public function latestNotification()
    {
        $result = $this->subscriberService->selectRaw('MIN(last_notification) as latest')->where('approved', 1)->first();

        if(!$result->latest) {
            return "0000-00-00 00:00:00";
        }

        return Carbon::parse($result->latest);
    }

    /**
     * @return mixed
     */
    public function subscribers()
    {
        return Subscriber::where('approved', 1)->get();
    }

    public function publish()
    {
        // TODO: Implement publish() method.
    }

    public function notify(Subscriber $subscriber)
    {
        // TODO: Implement notify() method.
    }

    /**
     * @return mixed
     */
    public function unapprovedSubscribers()
    {
        return Subscriber::where('approved', 0)->get();
    }

    /**
     * @param $subscriberSecret
     * @param $address
     * @return mixed
     */
    public function addSubscriber($subscriberSecret, $address)
    {
        return Subscriber::create([
            'secret'  => $subscriberSecret,
            'address' => $address
        ]);
    }

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function removeSubscriber($subscriberSecret)
    {
        return Subscriber::where('secret', $subscriberSecret)->delete();
    }

    /**
     * @param $subscriberSecret
     * @return mixed|void
     */
    public function approveSubscriber($subscriberSecret)
    {

    }
}