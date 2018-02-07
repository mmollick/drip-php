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
trait Campaigns
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
    public function getCampaigns($options = [])
    {
        return $this->client->accountRequest('GET', 'campaigns', $options);
    }

    /**
     * @param $campaign_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getCampaign($campaign_id)
    {
        return $this->client->accountRequest('GET', 'campaigns/' . $campaign_id);
    }

    /**
     * @param $campaign_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function activateCampaign($campaign_id)
    {
        $this->client->accountRequest('POST', 'campaigns/' . $campaign_id . '/activate');
        return true;
    }

    /**
     * @param $campaign_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function pauseCampaign($campaign_id)
    {
        $this->client->accountRequest('POST', 'campaigns/' . $campaign_id . '/pause');
        return true;
    }

    /**
     * @param $campaign_id
     * @param array $options
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getCampaignSubscribers($campaign_id, $options = [])
    {
        return $this->client->accountRequest('GET', 'campaigns/' . $campaign_id . '/subscribers', $options);
    }

    /**
     * @param $campaign_id
     * @param $subscriber
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function subscribeToCampaign($campaign_id, $subscriber)
    {
        return $this->client->accountRequest(
            'POST',
            'campaigns/' . $campaign_id . '/subscribers',
            [
                'subscribers' => [$subscriber]
            ]
        );
    }
}
