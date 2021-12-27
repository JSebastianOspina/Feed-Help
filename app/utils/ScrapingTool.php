<?php


namespace App\utils;


class ScrapingTool
{
    private $url = 'https://api.scrapingant.com/v1/general';
    private $api_token = '4d7b4ce4bf7148afa33c9886716db9a8';
    public $target;
    public $headers = [];
    public $method;
    private $httpClient;

    public function __construct(string $target, $method = 'GET')
    {
        $this->target = $target;
        $this->method = $method;
        $this->httpClient = new CurlCobain($this->url);
        $this->setUpClient();
    }

    private function setUpAuthorization()
    {
        $this->httpClient->setHeader('x-api-key', $this->api_token);
    }

    public function setHttpClientHeader($headerName,$headerValue){
        $this->headers[] = [
            $headerName => $headerValue
        ];
        $this->httpClient->setHeader($headerName,$headerValue);
    }
    public function setMethod(string $method)
    {
        $this->method = $method;
        $this->httpClient->setMethod($method);
    }

    public function makeRequest()
    {
        $response = $this->httpClient->makeRequest();
        dd($response);
    }

    private function setUpClient()
    {
        $this->httpClient->setQueryParam('url', urlencode($this->target));
        $this->setUpAuthorization();
    }


}
