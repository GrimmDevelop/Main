<?php

use Carbon\Carbon;
use Grimm\Cluster\TestClusterService;
use Grimm\Cluster\TestSubscriber;

class ClusterNotificationTest extends TestCase {

    /**
     * @var Grimm\Cluster\ClusterService
     */
    protected $clusterService;

    public function setUp()
    {
        parent::setUp();

        $this->clusterService = new TestClusterService();
    }

    public function testNotification()
    {
        $this->clusterService->fill([
            TestSubscriber::create('secret1', 'http://my-server.de/grimm', '2014-01-01'),
            TestSubscriber::create('secret2', 'http://my-server2.de/grimm', '2015-01-13', true),
            TestSubscriber::create('secret3', 'http://my-server3.de/grimm', '2015-03-09', true),
            TestSubscriber::create('secret4', 'http://my-server4.de/grimm', Carbon::now(), true),
        ]);

        $expected = [
            'post http://my-server2.de/grimm',
            'post http://my-server3.de/grimm'
        ];

        $actual = [];
        foreach ($this->clusterService->subscribers() as $subscriber)
        {
            $actual[] = $subscriber->notify();
        }

        $this->assertEquals($expected, $actual);
    }


}