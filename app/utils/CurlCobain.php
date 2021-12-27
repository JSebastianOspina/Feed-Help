<?php


namespace App\utils;


class CurlCobain
{
    public $url;
    public $finalUrl;
    public $method;
    public $data;
    public $queryParams = array();
    public $headers;
    public $cookies;
    public $requireSSL = false;
    private $ch;

    /**
     * CurlCobain constructor.
     * @param $url
     * @param $method
     */
    public function __construct($url, $method = 'GET')
    {
        $this->ch = curl_init();
        $this->url = $url;
        $this->method = $method;
        $this->basicSetUp();

    }



    private function basicSetUp(): void
    {
        $this->setCurlOption(CURLOPT_URL, $this->url);
        $this->setCurlOption(CURLOPT_POST, $this->method === 'POST');
        $this->setCurlOption(CURLOPT_RETURNTRANSFER, true); //Get text instead of void
        $this->setCurlOption(CURLOPT_SSL_VERIFYHOST, $this->requireSSL);
        $this->setCurlOption(CURLOPT_SSL_VERIFYPEER, $this->requireSSL);

    }

    public function setQueryParam(string $fieldName, string $value)
    {
        $this->queryParams[] = array(
            $fieldName => $value
        );
        $this->buildUrl();
    }

    public function setHeader(string $headerName, string $headerValue): void
    {
        $this->headers[] = $headerName . ': ' . $headerValue;
        $this->setCurlOption(CURLOPT_HTTPHEADER, $this->headers);
    }
    public function setHeadersAsArray(array $headers): void
    {
        $this->headers[] = $headers;
        $this->setCurlOption(CURLOPT_HTTPHEADER, $this->headers);
    }

    public function setCurlOption($option, $value): void
    {
        curl_setopt($this->ch, $option, $value);
    }

    public function makeRequest()
    {
        $resp = curl_exec($this->ch);
        curl_close($this->ch);
        return $resp;
    }

    public function enableSSL()
    {
        $this->requireSSL = true;
        $this->setCurlOption(CURLOPT_SSL_VERIFYPEER, $this->requireSSL);
    }

    public function disableSSL()
    {
        $this->requireSSL = false;
        $this->setCurlOption(CURLOPT_SSL_VERIFYPEER, $this->requireSSL);
    }

    private function buildUrl()
    {
        if (count($this->queryParams) === 0) {
            $this->finalUrl = $this->url;
        } else {
            $this->finalUrl = $this->url . '?' . http_build_query($this->queryParams);
        }
        $this->setCurlOption(CURLOPT_URL, $this->finalUrl);
    }

    public function setMethod(String $method){
        $this->method = $method;
        $this->setCurlOption(CURLOPT_POST, $this->method === 'POST');
    }


}
