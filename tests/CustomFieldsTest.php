<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Request;

class CustomFieldsTest extends TestCase
{
    public function testGetCustomFields()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getCustomFields();

        $req = $drip->getClient();
        $this->assertEquals('https://api.getdrip.com/v2/123/custom_field_identifiers', $req->getUrl());
    }
}
