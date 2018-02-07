<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class UsersTest extends TestCase
{
    public function testGetUsers()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getUser();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/user', $req->getUrl());
    }
}
