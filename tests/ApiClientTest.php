<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\ApiClient;
use MMollick\Drip\Errors\AuthException;
use MMollick\Drip\Errors\GeneralException;
use MMollick\Drip\Errors\HttpClientException;
use MMollick\Drip\Errors\RateLimitException;
use MMollick\Drip\Errors\ValidationException;

class ApiClientTest extends TestCase
{
    public function testSetTimeout()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $property = $reflector->getProperty('timeout');
        $property->setAccessible(true);

        $api = new ApiClient($this->auth, $this->getMockHttpClient());
        $api->setTimeout(60);
        $this->assertEquals(60, $property->getValue($api));

        $api->setTimeout('60');
        $this->assertEquals(60, $property->getValue($api));

        $api->setTimeout(-1);
        $this->assertEquals(0, $property->getValue($api));
    }

    public function testSetContentType()
    {
        $reflector = new \ReflectionClass(ApiClient::class);
        $property = $reflector->getProperty('contentType');
        $property->setAccessible(true);

        $api = new ApiClient($this->auth, $this->getMockHttpClient());
        $this->assertEquals(
            'application/vnd.api+json',
            $property->getValue($api)
        );

        $api->setContentType('application/json');
        $this->assertEquals('application/json', $property->getValue($api));

        $this->expectException(HttpClientException::class);
        $api->setContentType('application/x-www-form-urlencoded');
    }

    public function testSuccessfulRequest()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $client = new ApiClient($this->auth, $mock);
        $resp = $client->request('GET', '/accounts');

        $this->assertArrayHasKey('null_object', $resp);
        $this->assertEquals(200, $client->getStatusCode());
    }

    public function testCurlError()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn(false);
        $mock->method('getErrno')->willReturn(7);
        $mock->method('getError')->willReturn('Curl Error');

        $this->expectException(HttpClientException::class);

        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
        $this->assertEmpty($client->getDecodedResponse());
    }

    public function testGeneralFailure()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('errors.generic_error'));
        $mock->method('getInfo')->willReturn(400);

        $this->expectException(GeneralException::class);
        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
    }

    public function testBadResponse()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn('NOT JSON!!!');
        $mock->method('getInfo')->willReturn(200);

        $this->expectException(GeneralException::class);
        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
    }

    public function testAuthFailure()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('errors.auth_error'));
        $mock->method('getInfo')->willReturn(403);

        $this->expectException(AuthException::class);
        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
    }

    public function testRateLimitError()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('errors.rate_limiting'));
        $mock->method('getInfo')->willReturn(429);

        $this->expectException(RateLimitException::class);
        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
    }

    public function testValidationError()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('errors.validation_errors'));
        $mock->method('getInfo')->willReturn(422);

        $this->expectException(ValidationException::class);
        $client = new ApiClient($this->auth, $mock);
        $client->request('GET', '/accounts');
    }
}
