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

    /**
     * @return mixed
     */
    public function unapprovedSubscribers();

    /**
     * @return mixed
     */
    public function addSubscriber();

    /**
     * @return mixed
     */
    public function removeSubscriber();
}