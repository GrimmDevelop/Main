<?php

namespace Grimm\Cluster;

interface ClusterService {

    /**
     * @return \Carbon\Carbon
     */
    public function latestNotification();

    /**
     * @return mixed
     */
    public function subscribers();

    public function publish();

    public function notify(Subscriber $subscriber);

    /**
     * @return mixed
     */
    public function unapprovedSubscribers();

    /**
     * @param $subscriberSecret
     * @param $address
     * @return mixed
     */
    public function addSubscriber($subscriberSecret, $address);

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function removeSubscriber($subscriberSecret);

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function approveSubscriber($subscriberSecret);
}