<?php

namespace MMollick\Drip;

use MMollick\Drip\HttpClient\HttpClientInterface;

class Request
{
    use Requests\Accounts,
        Requests\Broadcasts,
        Requests\Campaigns,
        Requests\Conversions,
        Requests\CustomFields,
        Requests\Events,
        Requests\Forms,
        Requests\Purchases,
        Requests\Subscribers,
        Requests\Tags,
        Requests\Users,
        Requests\Webhooks,
        Requests\Workflows,
        Requests\WorkflowTriggers;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var ApiClient
     */
    protected $client;

    /**
     * @param Auth $auth
     * @param HttpClientInterface|null $clientInterface
     */
    public function __construct(Auth $auth, HttpClientInterface $clientInterface = null)
    {
        $this->auth = $auth;
        $this->client = new ApiClient($auth, $clientInterface);
    }

    /**
     * @return ApiClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }
}
