<?php

namespace Grimm\Cluster;


class TestSubscriber implements Subscriber {

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var \Carbon\Carbon
     */
    protected $last_notification;

    /**
     * @var bool
     */
    protected $approved;

    public static function create($secret, $address, $last_notification, $approved = false) {
        $object = new static();

        $object->secret = $secret;
        $object->address = $address;
        $object->last_notification = $last_notification;
        $object->approved = $approved;

        return $object;
    }

    public function notify()
    {
        return "post {$this->getAddress()}";
    }

    public function delete()
    {
        $this->secret = null;
        $this->address = null;
        $this->last_notification = null;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getLastNotification()
    {
        return $this->last_notification;
    }

    public function isApproved()
    {
        return $this->approved;
    }

    public function approve()
    {
        $this->approved = true;
    }
}