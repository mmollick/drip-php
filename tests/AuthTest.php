<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Auth;

class DripTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthConstructorWithToken()
    {
        $auth = new Auth('12345', 'abcde');
        $this->assertEquals('12345', $auth->getAccountId());
        $this->assertEquals('abcde', $auth->getToken());
        $this->assertFalse($auth->isOauth());
    }

    public function testAuthConstructorWithOauth()
    {
        $auth = new Auth('12345', 'abcde', true);
        $this->assertEquals('12345', $auth->getAccountId());
        $this->assertEquals('abcde', $auth->getToken());
        $this->assertTrue($auth->isOauth());
    }

    public function testAuthSetAccountId()
    {
        $auth = new Auth('12345', 'abcde');

        $this->assertEquals('12345', $auth->getAccountId());
        $auth->setAccountId('67890');
        $this->assertEquals('67890', $auth->getAccountId());
    }

    public function testAuthSetOauthAccessToken()
    {
        $auth = new Auth('12345', 'abcde');
        $auth->setOAuthAccessToken('67890', 'xyz');
        $this->assertEquals('67890', $auth->getAccountId());
        $this->assertEquals('xyz', $auth->getToken());
        $this->assertTrue($auth->isOauth());
    }

    public function testAuthSetApiCredentials()
    {
        $auth = new Auth('12345', 'abcde', true);
        $auth->setApiCredentials('67890', 'xyz');
        $this->assertEquals('67890', $auth->getAccountId());
        $this->assertEquals('xyz', $auth->getToken());
        $this->assertFalse($auth->isOauth());
    }
}
