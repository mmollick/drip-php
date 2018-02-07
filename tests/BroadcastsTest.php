<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class BroadcastsTest extends TestCase
{
    public function testGetBroadcasts()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getBroadcasts();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/broadcasts', $req->getUrl());
    }

    public function testGetBroadcast()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getBroadcast(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/broadcasts/12345', $req->getUrl());
    }
}
