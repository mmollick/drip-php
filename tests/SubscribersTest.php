<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Subscribers;
use MMollick\Drip\Request;

class SubscribersTest extends TestCase
{
    public function testGetSubscribers()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getSubscribers();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers', $req->getUrl());
    }

    public function testGetSubscriber()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getSubscriber('test@example.com');

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/test@example.com', $req->getUrl());
    }

    public function testCreateOrUpdate()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->createOrUpdateSubscriber('test@example.com', [
            'email' => 'john@acme.com', // Email defined in payload should be overwritten
            'time_zone' => 'America/Los_Angeles',
            'ip_address' => '192.168.0.1',
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/', $req->getUrl());
        $this->assertEquals([
            'subscribers' => [
                [
                    'email' => 'test@example.com',
                    'time_zone' => 'America/Los_Angeles',
                    'ip_address' => '192.168.0.1'
                ]
            ]
        ], $req->getPayload());
    }

    public function testCreateOrUpdateBatches()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(201);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->createOrUpdateSubscribers([
            [
                'email' => 'john@acme.com',
                'time_zone' => 'America/Los_Angeles',
                'ip_address' => '192.168.0.1',
            ],
            [
                'email' => 'joe@acme.com',
                'time_zone' => 'America/Los_Angeles',
                'ip_address' => '192.168.0.1',
            ]
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/batches', $req->getUrl());
        $this->assertEquals([
            'batches' => [
                [
                    'subscribers' => [
                        [
                            'email' => 'john@acme.com',
                            'time_zone' => 'America/Los_Angeles',
                            'ip_address' => '192.168.0.1'
                        ],
                        [
                            'email' => 'joe@acme.com',
                            'time_zone' => 'America/Los_Angeles',
                            'ip_address' => '192.168.0.1'
                        ]
                    ]
                ]
            ]
        ], $req->getPayload());
    }

    public function testRemoveFromCampaign()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->removeSubscriberFromCampaigns('test@example.com');

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/test@example.com/remove', $req->getUrl());
    }

    public function testRemoveFromAllMailings()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->removeSubscriberFromAllMailings('test@example.com');

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/test@example.com/unsubscribe_all', $req->getUrl());
    }

    public function testDelete()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->deleteSubscriber('test@example.com');

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/test@example.com', $req->getUrl());
    }

    public function testListSubscribersSubscriptions()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->listSubscribersSubscriptions(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/12345/campaign_subscriptions', $req->getUrl());
    }

    public function testUsubscribeBatch()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(201);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->unsubscribeSubscribers([
            [
                'email' => 'john@acme.com', // Email defined in payload should be overwritten
            ],
            [
                'email' => 'david@acme.com', // Email defined in payload should be overwritten
            ]
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/unsubscribes/batches', $req->getUrl());
        $this->assertEquals([
            'batches' => [
                [
                    'subscribers' => [
                        [
                            'email' => 'john@acme.com',
                        ],
                        [
                            'email' => 'david@acme.com',
                        ]
                    ]
                ]
            ]
        ], $req->getPayload());
    }
}
