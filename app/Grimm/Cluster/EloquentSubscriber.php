<?php

namespace Grimm\Cluster;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model as Eloquent;

class EloquentSubscriber extends Eloquent implements Subscriber {

    protected $table = 'subscribers';

    protected $fillable = ['secret', 'address', 'approved'];

    public function notify()
    {
        $client = new Client();

        $response = $client->post($this->getAddress());

        if ($response->getBody()->getContents() == 1)
        {
            $this->last_notification = Carbon::now();
            $this->save();

            return true;
        }

        return false;
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
        return (bool) $this->approved;
    }

    public function approve()
    {
        $this->approved = true;
        $this->save();
    }
}
