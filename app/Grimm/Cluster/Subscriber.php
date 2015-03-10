<?php

namespace Grimm\Cluster;


interface Subscriber {

    public function notify();

    public function delete();

}