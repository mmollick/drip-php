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
trait WorkflowTriggers
{
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
    public function getWorkflowTriggers($workflow_id)
    {
        return $this->client->accountRequest('GET', 'workflows/' . $workflow_id . '/triggers');
    }

    /**
     * @param $workflow_id
     * @param $payload
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function createWorkflowTrigger($workflow_id, $payload)
    {
        return $this->client->accountRequest('POST', 'workflows/' . $workflow_id . '/triggers', [
            'triggers' => [$payload]
        ]);
    }

    /**
     * @param $workflow_id
     * @param $trigger_id
     * @param $payload
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function updateWorkflowTrigger($workflow_id, $trigger_id, $payload)
    {
        return $this->client->accountRequest('PUT', 'workflows/' . $workflow_id . '/triggers/' . $trigger_id, [
            'triggers' => [$payload]
        ]);
    }
}
