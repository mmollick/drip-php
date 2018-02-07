<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Events;
use MMollick\Drip\Request;

class EventsTest extends TestCase
{
    public function testListActions()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->listActions();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/event_actions', $req->getUrl());
    }

    public function testRecord()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->recordEvent([
            'email' => 'john@acme.com',
            'action' => 'Logged in',
            'properties' => [
                'affiliate_code' => 'XYZ',
            ],
            'occurred_at'=> '2014-03-22T03:00:00Z'
        ]);
        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/events', $req->getUrl());
        $this->assertEquals([
            'events' => [
                [
                    'email' => 'john@acme.com',
                    'action' => 'Logged in',
                    'properties' => [
                        'affiliate_code' => 'XYZ',
                    ],
                    'occurred_at'=> '2014-03-22T03:00:00Z'
                ]
            ]
        ], $req->getPayload());
    }
}
