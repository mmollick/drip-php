<?php

namespace MMollick\Drip\Requests;

use MMollick\Drip\ApiClient;
use MMollick\Drip\Errors\ApiException;
use MMollick\Drip\Errors\AuthException;
use MMollick\Drip\Errors\GeneralException;
use MMollick\Drip\Errors\HttpClientException;
use MMollick\Drip\Errors\RateLimitException;
use MMollick\Drip\Errors\ValidationException;

/**
 * @property ApiClient client
 */
trait Webhooks
{
    /**
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getWebhooks()
    {
        return $this->client->accountRequest('GET', 'webhooks');
    }

    /**
     * @param $webhook_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getWebhook($webhook_id)
    {
        return $this->client->accountRequest('GET', 'webhooks/' . $webhook_id);
    }

    /**
     * @param $payload
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function createWebhook($payload)
    {
        return $this->client->accountRequest('POST', 'webhooks/', [
            'webhooks' => [
                $payload
            ]
        ]);
    }

    /**
     * @param $webhook_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function deleteWebhook($webhook_id)
    {
        $this->client->accountRequest('DELETE', 'webhooks/' . $webhook_id);
        return true;
    }
}
