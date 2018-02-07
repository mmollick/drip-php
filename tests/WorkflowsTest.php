<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Workflows;
use MMollick\Drip\Request;

class WorkflowsTest extends TestCase
{
    public function testGetWorkflows()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getWorkflows();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows', $req->getUrl());
    }

    public function testGetWorkflow()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getWorkflow(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows/12345', $req->getUrl());
    }

    public function testActivate()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->activateWorkflow(12345);

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows/12345/activate', $req->getUrl());
    }

    public function testPause()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->pauseWorkflow(12345);

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows/12345/pause', $req->getUrl());
    }

    public function testStartSubscriber()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->addSubscriberToWorkflow(12345, [
            'email' => 'john@acme.com',
            'time_zone' => 'America/Los_Angeles',
            'custom_fields' => [
                'name' => 'John Doe',
            ],
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows/12345/subscribers', $req->getUrl());
        $this->assertEquals([
            'subscribers' => [
                [
                    'email' => 'john@acme.com',
                    'time_zone' => 'America/Los_Angeles',
                    'custom_fields' => [
                        'name' => 'John Doe',
                    ],
                ]
            ]
        ], $req->getPayload());
    }

    public function testRemoveSubscriber()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->removeSubscriberFromWorkflow(12345, 'test@example.com');

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/workflows/12345/subscribers/test@example.com', $req->getUrl());
    }
}
