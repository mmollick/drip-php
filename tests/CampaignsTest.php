<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Campaigns;
use MMollick\Drip\Request;

class CampaignsTest extends TestCase
{
    public function testGetCampaigns()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getCampaigns();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns', $req->getUrl());
    }

    public function testGetCampaign()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getCampaign(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns/12345', $req->getUrl());
    }

    public function testActivate()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->activateCampaign(12345);
        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns/12345/activate', $req->getUrl());
    }

    public function testPause()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->pauseCampaign(12345);
        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns/12345/pause', $req->getUrl());
    }

    public function testListSubscribers()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getCampaignSubscribers(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns/12345/subscribers', $req->getUrl());
    }

    public function testSubscribe()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->subscribeToCampaign(12345, [
            'email' => 'john@acme.com',
            'utc_offset' => 660,
            'double_optin' => true,
            'starting_email_index' => 0,
            'reactivate_if_removed' => true,
            'custom_fields' => [
                'name' => 'John Doe',
            ]
        ]);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/campaigns/12345/subscribers', $req->getUrl());
        $this->assertEquals([
            'subscribers' => [
                [
                    'email' => 'john@acme.com',
                    'utc_offset' => 660,
                    'double_optin' => true,
                    'starting_email_index' => 0,
                    'reactivate_if_removed' => true,
                    'custom_fields' => [
                        'name' => 'John Doe',
                    ],
                ]
            ],
        ], $req->getPayload());
    }
}
