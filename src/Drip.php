<?php

namespace MMollick\Drip;

use MMollick\Drip\HttpClient\HttpClientInterface;

/**
 * Class Drip
 * @method static void setApiCredentials($account_id, $token)
 * @method static void setOAuthAccessToken($account_id, $access_token)
 * @method static void setAccountId($account_id)
 * @method static string getAccountId()
 * @method static string getToken()
 * @method static bool isOauth()
 * @method static ApiClient getClient()
 * @method static Auth getAuth()
 * @method static mixed getAccounts()
 * @method static mixed getAccount($account_id)
 * @method static mixed getBroadcasts($options = [])
 * @method static mixed getBroadcast($broadcast_id)
 * @method static mixed getCampaigns($options = [])
 * @method static mixed getCampaign($campaign_id)
 * @method static mixed activateCampaign($campaign_id)
 * @method static mixed pauseCampaign($campaign_id)
 * @method static mixed getCampaignSubscribers($campaign_id, $options = [])
 * @method static mixed subscribeToCampaign($campaign_id, $subscriber)
 * @method static mixed getConversions($options = [])
 * @method static mixed getConversion($conversion_id)
 * @method static mixed getCustomerFields()
 * @method static mixed listActions($options = [])
 * @method static mixed recordEvent($payload)
 * @method static mixed recordEvents($events)
 * @method static mixed getForms($options = [])
 * @method static mixed getForm($form_id)
 * @method static mixed addPurchaseToSubscriber($id_or_email, $payload)
 * @method static mixed getPurchasesForSubscriber($id_or_email, $options = [])
 * @method static mixed getPurchaseForSubscriber($id_or_email, $purchase_id)
 * @method static mixed getSubscribers($options = [])
 * @method static mixed getSubscriber($id_or_email)
 * @method static mixed createOrUpdateSubscriber($email,  $payload)
 * @method static mixed createOrUpdateSubscribers($subscribers)
 * @method static mixed removeSubscriberFromCampaigns($id_or_email, $campaign_id = null)
 * @method static mixed removeSubscriberFromAllMailings($id_or_email)
 * @method static mixed deleteSubscriber($id_or_email)
 * @method static mixed unsubscribeSubscribers($subscribers)
 * @method static mixed getTags()
 * @method static mixed applyTag($email, $tag)
 * @method static mixed removeTag($email, $tag)
 * @method static mixed getUser()
 * @method static mixed getWebhooks()
 * @method static mixed getWebhook($webhook_id)
 * @method static mixed createWebhook($payload)
 * @method static mixed deleteWebhook($webhook_id)
 * @method static mixed getWorkflows($options = [])
 * @method static mixed getWorkflow($workflow_id)
 * @method static mixed activateWorkflow($workflow_id)
 * @method static mixed pauseWorkflow($workflow_id)
 * @method static mixed addSubscriberToWorkflow($workflow_id, $subscriber)
 * @method static mixed removeSubscriberFromWorkflow($workflow_id, $id_or_email)
 * @method static mixed getWorkflowTriggers($workflow_id)
 * @method static mixed createWorkflowTrigger($workflow_id, $payload)
 * @method static mixed updateWorkflowTrigger($workflow_id, $payload)
 */
class Drip
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Request
     */
    protected $requests;

    /**
     * Drip constructor.
     */
    public function __construct()
    {
        $this->auth = new Auth();
        $this->requests = new Request($this->auth);
    }

    /**
     * Allows us to statically call Request methods
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic(x$method, $args)
    {
        if (method_exists(self::getInstance()->auth, $method)) {
            return call_user_func_array([self::getInstance()->auth, $method], $args);
        }

        return call_user_func_array([self::getInstance()->requests, $method], $args);
    }

    /**
     * @return Drip
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param HttpClientInterface $client
     */
    public static function setRequest(HttpClientInterface $client)
    {
        self::getInstance()->requests = new Request(
            self::getInstance()->auth,
            $client
        );
    }

    /**
     * @return Request
     */
    public static function getRequest()
    {
        return self::getInstance()->requests;
    }
}
