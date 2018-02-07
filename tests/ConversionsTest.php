<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class ConversionsTest extends TestCase
{
    public function testConversions()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getConversions();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/goals', $req->getUrl());
    }

    public function testConversion()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getConversion(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/goals/12345', $req->getUrl());
    }
}
