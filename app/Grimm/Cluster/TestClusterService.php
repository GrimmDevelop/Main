<?php

namespace Grimm\Cluster;


use Carbon\Carbon;

class TestClusterService implements ClusterService {

    protected $all;

    public function fill($data) {
        $this->all = $data;
    }

    public function changes($since)
    {
        if ($since < Carbon::parse('2015-01-01'))
        {
            return new Changes(35, 7, 8);
        }

        return new Changes(15, 3, 7);
    }

    /**
     * @return \Carbon\Carbon
     */
    public function latestNotification()
    {
        return Carbon::now();
    }

    protected function all()
    {
        return $this->all;
    }

    /**
     * @return mixed
     */
    public function subscribers()
    {
        $subscribers = $this->all();

        $filteredSubscribers = [];

        /** @var Subscriber $subscriber */
        foreach ($subscribers as $subscriber)
        {
            if ($subscriber->isApproved() && $this->latestNotification() > $subscriber->getLastNotification())
            {
                $filteredSubscribers[] = $subscriber;
            }
        }

        return $filteredSubscribers;
    }

    public function publish()
    {
        if($this->changes($this->latestNotification())->getTotal() == 0) {
            return;
        }

        /** @var \Grimm\Cluster\Subscriber $subscriber */
        foreach ($this->subscribers() as $subscriber)
        {
            $this->notify($subscriber);
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
        $subscribers = $this->all();

        $filteredSubscribers = [];

        /** @var Subscriber $subscriber */
        foreach ($subscribers as $subscriber)
        {
            if (!$subscriber->isApproved())
            {
                $filteredSubscribers[] = $subscriber;
            }
        }

        return $filteredSubscribers;
    }

    /**
     * @param $subscriberSecret
     * @param $address
     * @return mixed
     */
    public function addSubscriber($subscriberSecret, $address)
    {
        $this->all[] = TestSubscriber::create($subscriberSecret, $address, '0000-00-00', true);
    }

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function removeSubscriber($subscriberSecret)
    {
        // TODO: Implement removeSubscriber() method.
    }

    /**
     * @param $subscriberSecret
     * @return mixed
     */
    public function approveSubscriber($subscriberSecret)
    {
        /** @var Subscriber $subscriber */
        foreach ($this->all() as $subscriber)
        {
            if ($subscriber->getSecret() == $subscriberSecret && !$subscriber->isApproved())
            {
                $subscriber->approve();

                return;
            }
        }
    }
}