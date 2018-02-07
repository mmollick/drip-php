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
trait Forms
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
    public function getForms($options = [])
    {
        return $this->client->accountRequest('GET', 'forms', $options);
    }

    /**
     * @param $form_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getForm($form_id)
    {
        return $this->client->accountRequest('GET', 'forms/' . $form_id);
    }
}
