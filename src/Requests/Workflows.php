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
trait Workflows
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
    public function getWorkflows($options = [])
    {
        return $this->client->accountRequest('GET', 'workflows', $options);
    }

    /**
     * @param $workflow_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getWorkflow($workflow_id)
    {
        return $this->client->accountRequest('GET', 'workflows/' . $workflow_id);
    }

    /**
     * @param $workflow_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function activateWorkflow($workflow_id)
    {
        $this->client->accountRequest('POST', 'workflows/' . $workflow_id . '/activate');
        return true;
    }

    /**
     * @param $workflow_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function pauseWorkflow($workflow_id)
    {
        $this->client->accountRequest('POST', 'workflows/' . $workflow_id . '/pause');
        return true;
    }

    /**
     * @param $workflow_id
     * @param $subscriber
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function addSubscriberToWorkflow($workflow_id, $subscriber)
    {
        return $this->client->accountRequest('POST', 'workflows/' . $workflow_id . '/subscribers', [
            'subscribers' => [$subscriber]
        ]);
    }

    /**
     * @param $workflow_id
     * @param $id_or_email
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function removeSubscriberFromWorkflow($workflow_id, $id_or_email)
    {
        $this->client->accountRequest('DELETE', 'workflows/' . $workflow_id . '/subscribers/' . $id_or_email);
        return true;
    }
}
