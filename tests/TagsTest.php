<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Subscribers;
use MMollick\Drip\Tags;
use MMollick\Drip\Request;

class TagsTest extends TestCase
{
    public function testGetTags()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getTags();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/tags', $req->getUrl());
    }

    public function testApply()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(201);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->applyTag('test@example.com', 'foo');

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/tags', $req->getUrl());
        $this->assertEquals([
            'tags' => [
                [
                    'email' => 'test@example.com',
                    'tag' => 'foo'
                ]
            ]
        ], $req->getPayload());
    }

    public function testRemove()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('getInfo')->willReturn(204);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->removeTag('test@example.com', 'foo');

        $this->assertTrue($resp);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/subscribers/test@example.com/tags/foo', $req->getUrl());
    }
}
