<?php

namespace MMollick\Drip\Tests;

use MMollick\Drip\Workflows;
use MMollick\Drip\Request;

class WorkflowTriggersTest extends TestCase
{
    public function testGetWorkflowTriggers()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->getWorkflowTriggers(12345);

        $req = $drip->getClient();
        $this->assertEquals(
            'https://api.getdrip.com/v2/123/workflows/12345/triggers',
            $req->getUrl()
        );
    }

    public function testCreateWorkflowTrigger()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->createWorkflowTrigger(12345, [
            'provider' => 'leadpages',
            'trigger_type' => 'submitted_landing_page',
            'properties' => [
                'landing_page' => 'My Landing Page',
            ],
        ]);

        $req = $drip->getClient();
        $this->assertEquals(
            'https://api.getdrip.com/v2/123/workflows/12345/triggers',
            $req->getUrl()
        );
        $this->assertEquals([
            'triggers' => [
                [
                    'provider' => 'leadpages',
                    'trigger_type' => 'submitted_landing_page',
                    'properties' => [
                        'landing_page' => 'My Landing Page',
                    ]
                ]
            ]
        ], $req->getPayload());
    }

    public function testUpdateWorkflowTrigger()
    {
        $mock = $this->getMockHttpClient();
        $mock->method('execute')->willReturn($this->getData('null'));
        $mock->method('getInfo')->willReturn(200);

        $drip = new Request($this->auth, $mock);
        $drip->updateWorkflowTrigger(12345, 'abcde', [
            'provider' => 'leadpages',
            'trigger_type' => 'submitted_landing_page',
            'properties' => [
                'landing_page' => 'My Landing Page',
            ],
        ]);

        $req = $drip->getClient();
        $this->assertEquals(
            'https://api.getdrip.com/v2/123/workflows/12345/triggers/abcde',
            $req->getUrl()
        );
        $this->assertEquals([
            'triggers' => [
                [
                    'provider' => 'leadpages',
                    'trigger_type' => 'submitted_landing_page',
                    'properties' => [
                        'landing_page' => 'My Landing Page',
                    ]
                ]
            ]
        ], $req->getPayload());
    }
}
