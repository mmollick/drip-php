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
trait Subscribers
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
    public function getSubscribers($options = [])
    {
        return $this->client->accountRequest('GET', 'subscribers', $options);
    }

    /**
     * @param array $id_or_email
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getSubscriber($id_or_email)
    {
        return $this->client->accountRequest('GET', 'subscribers/' . $id_or_email);
    }

    /**
     * @param $email
     * @param $payload
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function createOrUpdateSubscriber($email, $payload)
    {
        return $this->client->accountRequest('POST', 'subscribers/', [
            'subscribers' => [
                array_merge($payload, ['email' => $email])
            ]
        ]);
    }

    /**
     * @param array $subscribers
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function createOrUpdateSubscribers(array $subscribers)
    {
        $this->client->accountRequest('POST', 'subscribers/batches', [
            'batches' => [['subscribers' => $subscribers]]
        ]);

        return true;
    }

    /**
     * @param array $id_or_email
     * @param null $campaign_id Leave empty for all campaigns
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function removeSubscriberFromCampaigns($id_or_email, $campaign_id = null)
    {
        return $this->client->accountRequest('POST', 'subscribers/' . $id_or_email . '/remove', [
            'campaign_id' => $campaign_id
        ]);
    }

    /**
     * @param array $id_or_email
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function removeSubscriberFromAllMailings($id_or_email)
    {
        return $this->client->accountRequest('POST', 'subscribers/' . $id_or_email . '/unsubscribe_all');
    }

    /**
     * @param array $id_or_email
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function deleteSubscriber($id_or_email)
    {
        $this->client->accountRequest('DELETE', 'subscribers/' . $id_or_email);
        return true;
    }

    /**
     * @param $subscriber_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function listSubscribersSubscriptions($subscriber_id)
    {
        return $this->client->accountRequest('GET', 'subscribers/' . $subscriber_id . '/campaign_subscriptions');
    }

    /**
     * @param $subscribers
     * @return bool
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function unsubscribeSubscribers($subscribers)
    {
        $this->client->accountRequest('POST', 'unsubscribes/batches', [
            'batches' => [['subscribers' => $subscribers]]
        ]);

        return true;
    }
}
