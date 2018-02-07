<?php

namespace MMollick\Drip\HttpClient;

class CurlClient implements HttpClientInterface
{
    /**
     * @var resource
     */
    private $handle;

    /**
     * @param $url
     */
    public function init($url)
    {
        $this->handle = curl_init($url);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setOpt($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     * @return mixed
     */
    public function getErrno()
    {
        return curl_errno($this->handle);
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return curl_error($this->handle);
    }

    /**
     * Closes the curl interface
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
