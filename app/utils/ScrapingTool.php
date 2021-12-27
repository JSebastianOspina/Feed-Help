<?php


namespace App\utils;


class ScrapingTool
{
    public $target;
    public $headers = [];
    public $method;
    private $url = 'https://api.scrapingant.com/v1/general';
    private $api_token = '4d7b4ce4bf7148afa33c9886716db9a8';
    private $httpClient;

    public function __construct(string $target, $method = 'GET')
    {
        $this->target = $target;
        $this->method = $method;
        $this->httpClient = new CurlCobain($this->url);
        $this->setUpClient();
    }

    private function setUpClient(): void
    {
        $this->httpClient->setQueryParam('url', $this->target);
        $this->httpClient->setQueryParam('browser','false');
        $this->httpClient->setQueryParam('return_text','true');
        $this->setUpAuthorization();
    }

    private function setUpAuthorization(): void
    {
        $this->httpClient->setHeader('x-api-key', $this->api_token);
    }

    public function setHttpClientHeader($headerName, $headerValue): void
    {
        $this->headers[] = [
            $headerName => $headerValue
        ];
        $this->httpClient->setHeader($headerName, $headerValue);
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
        $this->httpClient->setMethod($method);
    }

    public function makeRequest()
    {
        return $this->httpClient->makeRequest();
    }


}
