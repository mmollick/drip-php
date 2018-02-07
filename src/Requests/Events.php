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
trait Events
{
    /**
     * @param array $options
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function listActions($options = [])
    {
        return $this->client->accountRequest('GET', 'event_actions', $options);
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
    public function recordEvent($payload)
    {
        $this->client->accountRequest('GET', 'events', [
            'events' => [$payload]
        ]);
        return true;
    }
}
