Drip PHP API Wrapper
===
<p align="center">
<a href="https://travis-ci.org/mmollick/drip-php"><img src="https://travis-ci.org/mmollick/drip-php.svg" alt="Build Status"></a>
<a href="https://codeclimate.com/github/mmollick/drip-php/maintainability"><img src="https://api.codeclimate.com/v1/badges/d8dbf59dacccf37a1c77/maintainability" /></a>
<a href="https://packagist.org/packages/mmollick/drip-php"><img src="https://img.shields.io/packagist/v/mmollick/drip-php.svg" /></a>
<a href="https://packagist.org/packages/mmollick/drip-php"><img src="https://img.shields.io/packagist/dm/mmollick/drip-php.svg" /></a>
</p>

### Requirements
* PHP 5.6 or greater
* PHP JSON extension
* PHP cURL extension

### Todo
* Add batch capabilities
* Add payment endpoints

Installation
---

### Composer Installation

Run the following command in the terminal:

```bash
composer require mmollick/drip-php
```

Or add the following to your `composer.json` file:

``` json
{
  "require": {
    "mmollick/drip-php":"~0.1.0"
  }
}
```

### Manual Installation

This package conforms to the PSR-4 autoloading standard. To include it in your project we recommend using composer, however you can use your own PSR-4 autoloader or manually load all the files contained in the `src` directory to load this library.

Usage
---

This package allows you use it in both an object-oriented or singleton approach depending on your preferences. All authentication and request methods are available with either approach.

### Initialization as Singleton

Specify your authentication credentials with either the `setTokenCredentials` or `setOAuthAccessToken` static methods on the Drip class.

```php
// Authenticating w/ API key
\MMollick\Drip\Drip::setTokenCredentials($accountId, $api_key)

// Authenticating w/ OAuth access_token
\MMollick\Drip\Drip::setOAuthAccessToken($accountId, $access_token)
```

### Initialization as Object

Using the object-oriented approach allows you to create multiple isolated instances of the Drip API. To do this first create new a `\MMollick\Drip\Auth` object and pass this into a new `\MMollick\Drip\Request` object.

```php
// Authentication w/ API Key
$auth = new \MMollick\Drip\Auth($account_id, $api_key);
$drip = new \MMollick\Drip\Request($auth);

// Authentication w/ OAuth access_token
$authUsingOauth = new \MMollick\Drip\Auth($account_id, $access_token, true);
$dripUsingOauth = new \MMollick\Drip\Request($authUsingOauth);
```

### Error Handling

This library will throw one of several exceptions when an error occurs either with the Request or the package itself. It's recommended that requests are made within in a `try...catch` block to properly handle errors as they arise. See the example below.

```php
try {
    $drip->getSubscribers();
}
catch (\MMollick\Drip\Errors\AuthException $e) {
    // Authentication failed, check API keys
}
catch (\MMollick\Drip\Errors\ValidationException $e) {
    //The request failed validation, see message or $e->getErrors() for more info
}
catch (\MMollick\Drip\Errors\ApiExceptions $e) {
    // Non-specific API error returned, see message or $e->getErrors() for more info
}
catch (\MMollick\Drip\Errors\RateLimitException $e) {
    // Requests are being throttled, try the request again in a while
}
catch (\MMollick\Drip\Errors\HttpClientException $e) {
    // Most likely a network or Curl related error, see the message for more details
}
catch (\MMollick\Drip\Errors\GeneralException $e) {
    // A generic exception, see message for details
}
catch (\Exception $e) {
    // Catch anything else just for good measure
}
```

### Methods

All of the request methods can be accessed statically from the `\MMollick\Drip\Drip` class or by calling them from the `\MMollick\Drip\Request` object.

##### Account

| Actions          | Methods                   |
|:-----------------|:--------------------------|
| List accounts    | `getAccounts()`           |
| Fetch an account | `getAccount($account_id)` |

##### Broadcasts

| Actions           | Methods                        |
|:------------------|:-------------------------------|
| List broadcasts   | `getBroadcasts($options = [])` |
| Fetch a broadcast | `getBroadcast($broadcast_id)`  |

##### Campaigns

| Actions                   | Request Methods                                        |
|:--------------------------|:-------------------------------------------------------|
| List campaigns            | `getCampaigns($options = [])`                          |
| Fetch a campaign          | `getCampaign($campaign_id)`                            |
| Activate a campaign       | `activateCampaign($campaign_id)`                       |
| Pause a campaign          | `pauseCampaign($campaign_id)`                          |
| List campaign subscribers | `listCampaignSubscribers($campaign_id, $options = [])` |
| Subscribe to a campaign   | `subscribeToCampaign($campaign_id, $subscriber)`       |

##### Campaign Subscriptions

| Actions                         | Methods                                           |
|:--------------------------------|:--------------------------------------------------|
| List subscriber's subscriptions | See `listSubscribersSubscriptions` in Subscribers |

##### Conversions

| Actions              | Methods                         |
|:---------------------|:--------------------------------|
| List all conversions | `getConversions($options = [])` |
| Fetch a conversion   | `getConversion($conversion_id)` |

##### Custom Fields

| Actions                | Methods             |
|:-----------------------|:--------------------|
| List all custom fields | `getCustomFields()` |

##### Events

| Actions                       | Methods                 |
|:------------------------------|:------------------------|
| Track an event                | `recordEvent($payload)` |
| Track a batch of events       | `recordEvents($events)` |
| List all custom event actions | `listActions()`         |

##### Forms

| Actions        | Methods                   |
|:---------------|:--------------------------|
| List all forms | `getForms($options = [])` |
| Fetch a form   | `getForm($form_id)`       |

##### Purchases

| Actions                         | Methods                                                  |
|:--------------------------------|:---------------------------------------------------------|
| List purchases for a subscriber | `getPurchasesForSubscriber($id_or_email, $options = [])` |
| Create a purchase               | `addPurchaseToSubscriber($id_or_email, $purchase_id)`    |
| Fetch a purchase                | `getPurchaseForSubscriber($id_or_email, $payload)`       |

##### Subscribers

| Actions                              | Methods                                                            |
|:-------------------------------------|:-------------------------------------------------------------------|
| List subscribers                     | `getSubscribers($options = [])`                                    |
| Create/update a subscriber           | `createOrUpdateSubscriber($email, $payload = {})`                  |
| Create/update a batch of subscribers | `createOrUpdateSubscribers($subscribers)`                          |
| Unsubscribe a batch of subscribers   | `unsubscribeSubscribers($subscribers)`                             |
| Fetch a subscriber                   | `getSubscriber($id_or_email)`                                      |
| Delete                               | `deleteSubscriber($id_or_email)`                                   |
| Subscribe to a campaign              | See `subscribeToCampaign` in Campaigns                             |
| Unsubscribe from all mailings        | `removeSubscriberFromAllMailings($id_or_email)`                    |
| Unsubscribe from campaigns           | `removeSubscriberFromCampaigns($id_or_email, $campaign_id = null)` |
| List subscriber's subscriptions      | `listSubscribersSubscriptions($subscriber_id)`                     |

##### Tags

| Actions      | Methods                   |
|:-------------|:--------------------------|
| List tags    | `getTags()`               |
| Apply a tag  | `applyTag($email, $tag)`  |
| Remove a tag | `removeTag($email, $tag)` |

##### Webhooks

| Actions              | Methods                      |
|:---------------------|:-----------------------------|
| List webhooks        | `getWebhooks()`              |
| Fetch a webhook      | `getWebhook($webhook_id)`    |
| Create a new webhook | `createWebhook($payload)`    |
| Delete a webhook     | `deleteWebhook($webhook_id)` |

##### Workflows

| Actions                             | Methods                                                    |
|:------------------------------------|:-----------------------------------------------------------|
| List workflows                      | `getWorkflows($options = [])`                              |
| Fetch a workflow                    | `getWorkflow($workflow_id)`                                |
| Activate a workflow                 | `activateWorkflow($workflow_id)`                           |
| Pause a workflow                    | `pauseWorkflow($workflow_id)`                              |
| Start a subscriber on a workflow    | `addSubscriberToWorkflow($workflow_id, $options = [])`     |
| Remove a subscriber from a workflow | `removeSubscriberFromWorkflow($workflow_id, $id_or_email)` |

##### Workflow Triggers

| Actions                   | Methods                                              |
|:--------------------------|:-----------------------------------------------------|
| List workflow triggers    | `getWorkflowTriggers($workflow_id)`                  |
| Create a workflow trigger | `createWorkflowTrigger($workflow_id, $options = [])` |
| Update a workflow trigger | `updateWorkflowTrigger($workflow_id, $options = [])` |

## Contributing

1) Fork it ( https://github.com/mmollick/drip-php/fork )
2) Create your feature branch (git checkout -b my-new-feature)
3) Commit your changes (git commit -am 'Add some feature')
    - Add tests when relevant
4) Push to the branch (git push origin my-new-feature)
    - Fix linting issues Code-Climate Identifies
5) Create a new Pull Request

## Support

This package is open-source and maintained by the community. Drip does not directly participate in the maintenance of this package. Any issues with this package should be addressed by [opening a new issue](https://github.com/mmollick/drip-php/issues/new).
