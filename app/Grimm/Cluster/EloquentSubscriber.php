<?php

namespace Grimm\Cluster;

use Guzzle\Http\Client;
use Illuminate\Database\Eloquent\Model as Eloquent;

class EloquentSubscriber extends Eloquent implements Subscriber {

    protected $table = 'subscribers';

    protected $fillable = ['secret', 'address', 'approved'];

    public function notify()
    {
        $client = new Client();

        $response = $client->post($this->attributes['address']);

        dd($response);
    }
}