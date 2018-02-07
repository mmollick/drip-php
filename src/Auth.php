<?php

namespace MMollick\Drip;

class Auth
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $accountId;

    /**
     * @var bool
     */
    protected $isOauth = false;

    /**
     * Auth constructor.
     * Credentials can either be supplied during construction or later with the
     *  setAPICredentials or setOAuthAccessToken methods
     * @param string $account_id
     * @param string $token
     * @param bool $is_auth
     */
    public function __construct($account_id = null, $token = null, $is_auth = false)
    {
        $this->accountId = $account_id;
        $this->token = $token;
        $this->isOauth = $is_auth;
    }

    /**
     * @param $account_id
     * @param $token
     */
    public function setApiCredentials($account_id, $token)
    {
        $this->accountId = $account_id;
        $this->token = $token;
        $this->isOauth = false;
    }

    /**
     * @param $account_id
     * @param $access_token
     */
    public function setOAuthAccessToken($account_id, $access_token)
    {
        $this->accountId = $account_id;
        $this->token = $access_token;
        $this->isOauth = true;
    }

    /**
     * @param $account_id
     */
    public function setAccountId($account_id)
    {
        $this->accountId = $account_id;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isOauth()
    {
        return $this->isOauth;
    }
}
