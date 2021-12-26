<?php


namespace App\utils;


class ScrapingTool
{
    private $url = 'https://api.scrapingant.com/v1/general';
    private $api_token = '4d7b4ce4bf7148afa33c9886716db9a8';
    public $target;
    public $headers = [];
    public $method;
    public function __construct(String $target, $method = 'GET'){
        $this->target = $target;
        $this->method = $method;
        $this->setUpAuthorization();
    }

    private function setUpAuthorization(){
        $headerName = 'x-api-key';
        $this->setHeader($headerName,$this->api_token);
    }
    public function setHeader(String $headerName, String $headerValue)
    {
        $this->headers[] = $headerName.': '.$headerValue;
    }

    public function setMethod(String $method){
        $this->method = $method;
    }

    public function makeRequest(){

    }

}
