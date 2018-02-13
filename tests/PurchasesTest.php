<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class PurchasesTest extends TestCase
{
    public function testGetPurchases()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getPurchasesForSubscriber('joe@acme.com');

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/joe@acme.com/purchases', $req->getUrl());
    }

    public function testGetPurchase()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getPurchaseForSubscriber('joe@acme.com', 12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/joe@acme.com/purchases/12345', $req->getUrl());
    }

    public function testCreatePurchase()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->addPurchaseToSubscriber('joe@acme.com', [
            'amount' => 12345
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/joe@acme.com/purchases', $req->getUrl());
        $this->assertEquals([
            'purchases' => [
                [
                    'amount' => 12345
                ]
            ]
        ], $req->getPayload());
    }
}
