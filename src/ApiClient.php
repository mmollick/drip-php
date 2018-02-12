<?php

namespace MMollick\Drip;

use MMollick\Drip\Errors\ApiException;
use MMollick\Drip\Errors\AuthException;
use MMollick\Drip\Errors\GeneralException;
use MMollick\Drip\Errors\HttpClientException;
use MMollick\Drip\Errors\RateLimitException;
use MMollick\Drip\Errors\ValidationException;
use MMollick\Drip\HttpClient\CurlClient;
use MMollick\Drip\HttpClient\HttpClientInterface;

class ApiClient
{
    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var string The base url for API requests
     */
    protected $baseUrl = 'https://api.getdrip.com/v2/';

    /**
     * @var int The maximum number of seconds to allow cURL functions to
     *     execute.
     */
    protected $timeout = 30;

    /**
     * @var string The content type for this request
     */
    protected $contentType = 'application/vnd.api+json';

    /**
     * @var array
     */
    protected $decodedResponse = [];

    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * @var
     */
    protected $statusCode = 0;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var string
     */
    protected $method;

    /**
     * ApiClient constructor.
     * @param Auth $auth
     * @param HttpClientInterface $clientInterface
     */
    public function __construct(Auth $auth, HttpClientInterface $clientInterface = null)
    {
        $this->auth = $auth;
        $this->client = $clientInterface ?: new CurlClient();
    }

    /**
     * @param string $url Set the base URL to use for API requests. This method
     *     exists for development purposes
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Enables debug mode (dumps out encoded params and response)
     */
    public function debug()
    {
        $this->debug = true;
    }

    /**
     * @param integer $timeout Sets the maximum number of seconds to allow the
     *     request to execute.
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = max((int) $timeout, 0);
        return $this;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @return array
     */
    public function getDecodedResponse()
    {
        return $this->decodedResponse;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return int
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $contentType Content type for the request; either
     *     application/x-www-form-urlencoded or application/json
     * @return $this
     * @throws HttpClientException
     */
    public function setContentType($contentType)
    {
        $supported = in_array(
            $contentType,
            ['application/vnd.api+json', 'application/json'],
            true
        );

        // Throw exception if unsupported content type is specified
        if (!$supported) {
            throw new HttpClientException(
                'An unsupported content type was supplied. This API '
                . 'supports either \'application/vnd.api+json\' '
                . 'or \'application/json\'.'
            );
        }

        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Account specific request (prepends the account id to the endpoint)
     * @param $method
     * @param $endpoint
     * @param array $payload
     * @return mixed
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws GeneralException
     * @throws ApiException
     * @throws AuthException
     * @throws ValidationException
     */
    public function accountRequest($method, $endpoint, array $payload = [])
    {
        return $this->request($method, $this->auth->getAccountId() . '/' . $endpoint, $payload);
    }

    /**
     * Base request method
     * @param string $method
     * @param string $endpoint
     * @param array $payload
     * @return mixed
     * @throws HttpClientException
     * @throws RateLimitException
     * @throws GeneralException
     * @throws ApiException
     * @throws AuthException
     * @throws ValidationException
     */
    public function request($method, $endpoint, array $payload = [])
    {
        $this->checkAuthCredentials();

        // Base url + endpoint resolved
        $this->url = $this->baseUrl . $endpoint;
        $this->method = strtoupper($method);
        $this->payload = $payload;

        $this->initCurl();
        $this->setCurlOpts();
        $this->rawResponse = $this->client->execute();

        if ($this->debug) {
            var_dump($this->rawResponse);
        }

        // Get status code
        $this->statusCode = $this->client->getInfo(CURLINFO_HTTP_CODE);
        $this->client->close();

        // Catches curl errors
        if ($this->rawResponse === false) {
            $this->handleCurlError();
        }

        return $this->parseResponse();
    }

    /**
     * @throws AuthException
     */
    protected function checkAuthCredentials()
    {
        // Check for a key
        if ($this->auth->getToken() === null || $this->auth->getAccountId() === null) {
            throw new AuthException(
                'The account ID and API key have not been set. Please use the '
                . '\MMollick\Drip\$this->auth->setApiCredentials() or the '
                . '\MMollick\Drip\$this->auth->setOauthCredentials()'
                . 'methods before making a request.'
            );
        }
    }

    /**
     * Initializes the Curl request and encodes the parameters
     */
    protected function initCurl()
    {
        // If this is a GET request append query to the end of the url
        if ($this->method === 'GET') {
            $this->client->init($this->getUrl() . '?' . http_build_query($this->payload));
            return;
        }

        // Assume all other requests are POST and set fields accordingly
        $this->client->init($this->getUrl());
        $this->client->setOpt(CURLOPT_POSTFIELDS, json_encode($this->payload));
        $this->client->setOpt(CURLOPT_CUSTOMREQUEST, $this->method);
    }

    /**
     * Sets the curl parameters
     */
    protected function setCurlOpts()
    {
        $headers = [
            'User-Agent: MMollick/Drip-PHP_v' . Utils::wrapperVersion(),
            'Content-Type: ' . $this->contentType
        ];

        // Determine whether to send credentials as Oauth or Token
        if ($this->auth->isOauth()) {
            $headers[] = 'Authorization: Bearer ' . $this->auth->getToken();
        } else {
            $this->client->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $this->client->setOpt(CURLOPT_USERPWD, $this->auth->getToken() . ':');
        }

        // Set options
        $this->client->setOpt(CURLOPT_HTTPHEADER, $headers);
        $this->client->setOpt(CURLOPT_TIMEOUT, $this->timeout);
        $this->client->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->client->setOpt(CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($headerLine, ':') === false) {
                return strlen($headerLine);
            }
            list($key, $value) = explode(':', trim($headerLine), 2);
            $this->responseHeaders[trim($key)] = trim($value);
            return strlen($headerLine);
        });
    }

    /**
     * Throw Curl/Network errors
     * @throws HttpClientException
     */
    protected function handleCurlError()
    {
        $errno = $this->client->getErrno();
        $message = $this->client->getError();
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Drip ({$this->url}).  Please check your "
                    . 'internet connection and try again. ';
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Drip's SSL certificate.  Please make sure "
                    . 'that your network is not intercepting certificates.  '
                    . "(Try going to {$this->url} in your browser.)  ";
                break;
            default:
                $msg = 'Unexpected error communicating with Drip.';
        }
        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new HttpClientException($msg);
    }

    /**
     * Parses the response string and handles any errors
     * @return mixed
     * @throws RateLimitException
     * @throws GeneralException
     * @throws ApiException
     * @throws AuthException
     * @throws ValidationException
     */
    protected function parseResponse()
    {
        $code = $this->getStatusCode();
        $raw = $this->getRawResponse();

        // Rate limiting
        if ($code === 429) {
            throw new RateLimitException(
                'API rate limit exceeded. Please try again in an hour.'
                . 'See more: https://www.getdrip.com/docs/rest-api#rate-limting'
                . "\n\n(Rate Limiting)"
            );
        }

        // 204 indicates success with no content
        if ($code === 204) {
            return null;
        }

        $this->decodedResponse = $decoded = json_decode($raw, true);

        // Check if the response was decoded properly
        if ($decoded === null) {
            throw new GeneralException(
                'The response from Drip was unable '
                . 'to be parsed as json.'
                . "\n\n(Internal error [status $code: $raw])"
            );
        }

        // Handle errors reported by API
        if (isset($decoded['errors'])) {
            $this->parseErrors();
        }

        // Anything else is passed through (assumed successful
        return $decoded;
    }

    /**
     * Decodes errors and throws appropriate Exception classes to allow proper
     * handling of specific error types
     * @throws ApiException
     * @throws AuthException
     * @throws ValidationException
     */
    protected function parseErrors()
    {
        $code = $this->getStatusCode();
        $decoded = $this->getDecodedResponse();

        switch ($code) {
            case 422:
                throw new ValidationException(
                    $decoded['errors'],
                    'An error occurred while attempting to create or '
                    . 'update a resource. The following information was'
                    . ' given:'
                    . $this->flattenErrors()
                    . "\nSee: http://developer.drip.com/?shell#validation-errors"
                    . "\n\n(Validation error [$code])"
                );
                break;

            case 403:
                throw new AuthException(
                    $decoded['errors'],
                    'The request was unable to be authorized. '
                    . 'The following information was given:'
                    . $this->flattenErrors()
                    . "\nSee: http://developer.drip.com/?shell#transition-errors"
                    . "\n\n(Transition error [$code])"
                );
                break;

            default:
                throw new ApiException(
                    $decoded['errors'],
                    'Your request was not completed successfully. '
                    . 'The following information was given:'
                    . $this->flattenErrors()
                    . "\n\n(Api error [$code])"
                );
        }
    }

    /**
     * Flattens errors returned by API into a string
     * @return string
     */
    protected function flattenErrors()
    {
        $string = '';
        $decoded = $this->getDecodedResponse();
        foreach ((array) $decoded['errors'] as $error) {
            // Prefer showing the error message over showing just the code
            if (isset($error['message'])) {
                $string .= "\n{$error['message']}";

                // Append code after message if available
                if (isset($error['code'])) {
                    $string .= " ({$error['code']})";
                }
                continue;
            } elseif (isset($error['code'])) {
                $string .= "\n{$error['code']}";
                continue;
            }

            // When all else fails, we'll just implode the error
            $string .= "\n" . implode((array) $error);
        }

        return $string;
    }
}
