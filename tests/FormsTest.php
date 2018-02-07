<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Forms;
use MMollick\Drip\Request;

class FormsTest extends TestCase
{
    public function testGetForms()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getForms();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/forms', $req->getUrl());
    }

    public function testGetForm()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $resp = $drip->getForm(12345);

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/forms/12345', $req->getUrl());
    }
}
