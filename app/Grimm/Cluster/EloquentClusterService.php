<?php

namespace Grimm\Cluster;

use Carbon\Carbon;

class EloquentClusterService implements ClusterService {

    /**
     * @return \Carbon\Carbon
     */
    public function latestNotification()
    {
        return Carbon::parse(Subscriber::selectRaw('MIN(last_notification)')->where('approved', 1)->first());
    }

    /**
     * @return mixed
     */
    public function subscribers()
    {
        return Subscriber::where('approved', 1)->get();
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
            'secret' => $subscriberSecret,
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
    public function approveSubscriber($subscriberSecret) {

    }
}