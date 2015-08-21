<?php

namespace Grimm\Cluster;


interface Subscriber {

    public function getSecret();
    public function getAddress();
    public function getLastNotification();

    public function isApproved();
    public function approve();

    public function notify();

    public function delete();

}