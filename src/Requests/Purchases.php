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
trait Purchases
{
    /**
     * @param $id_or_email
     * @param $payload
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function addPurchaseToSubscriber($id_or_email, $payload)
    {
        $this->client->accountRequest('POST', 'subscribers/' . $id_or_email . '/purchases', [
            'purchases' => [$payload]
        ]);
        return true;
    }

    /**
     * @param $id_or_email
     * @param array $options
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getPurchasesForSubscriber($id_or_email, $options = [])
    {
        return $this->client->accountRequest('GET', 'subscribers/' . $id_or_email . '/purchases', $options);
    }

    /**
     * @param $id_or_email
     * @param $purchase_id
     * @return mixed
     * @throws ApiException
     * @throws AuthException
     * @throws GeneralException
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws ValidationException
     */
    public function getPurchaseForSubscriber($id_or_email, $purchase_id)
    {
        return $this->client->accountRequest('GET', 'subscribers/' . $id_or_email . '/purchases/' . $purchase_id);
    }
}
