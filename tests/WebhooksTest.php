<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Webhooks;
use MMollick\Drip\Request;

class WebhooksTest extends TestCase
{
    public function testGetWebhooks()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getWebhooks();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/webhooks', $req->getUrl());
    }

    public function testGetWebhook()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getWebhook(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/webhooks/12345', $req->getUrl());
    }

    public function testCreate()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(201);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->createWebhook([
            'post_url' => 'http://www.mysite.com/my-webhook-endpoint',
            'events' => [
                'subscriber.created',
                'subscriber.subscribed_to_campaign',
            ],
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/webhooks/', $req->getUrl());
        $this->assertEquals([
            'webhooks' => [
                [
                    'post_url' => 'http://www.mysite.com/my-webhook-endpoint',
                    'events' => [
                        'subscriber.created',
                        'subscriber.subscribed_to_campaign',
                    ]
                ]
            ]
        ], $req->getPayload());
    }

    public function testDelete()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->deleteWebhook(12345);

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/webhooks/12345', $req->getUrl());
    }
}
