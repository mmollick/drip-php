<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class AccountsTest extends TestCase
{
    public function testGetAccounts()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getAccounts();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/accounts', $req->getUrl());
    }

    public function testGetAccount()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getAccount('abcdefg');

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/accounts/abcdefg', $req->getUrl());
    }
}
